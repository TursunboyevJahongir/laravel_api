<?php

namespace App\Core\Repositories;

use App\Core\Contracts\CoreRepositoryContract;
use App\Enums\AvailableLocalesEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\{Builder, Model, Relations\Pivot};
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

abstract class CoreRepository implements CoreRepositoryContract
{
    public Model|Pivot $model;

    /**
     * @param Model|Pivot $model
     */
    public function __construct(Model|Pivot $model)
    {
        $this->model = $model;
    }

    /**
     * for any checks
     *
     * @param Builder|Model $query
     *
     * @return Builder|Model
     */
    public function availability(
        Builder|Model $query
    ): Builder|Model {
        return $query;
    }

    public function mainQuery(
        array $columns = ['*'],
        array $relations = [],
        int|null $status = null,
        string $search = null,
        array|null $filters = null,
        array|null $notFilters = null,
        array|null $orFilters = null,
        string $orderBy = 'id',
        string $sort = 'desc',
        bool $trashed = false,
        Builder|null $query = null
    ): Builder {
        return $this->model
            ->when($status, function ($query) use ($status) {
                $query->where('is_active', $status);
            })
            ->select($columns)
            ->when($search, function ($query) use ($search) {
                $this->search($query, $search);
            })
            /**
             * to filter filters[0][status]=activated&filters[0][name]="Jahongir"
             */
            ->when($filters, function ($query, $filters) {
                $this->filters($query, $filters);
            })
            /**
             * not equal
             * not filter not_filters[0][status]=activated
             */
            ->when($notFilters, function ($query, $filters) {
                $query->whereNot(function ($query) use ($filters) {
                    $this->filters($query, $filters);
                });
            })
            /**
             * or filter
             * or_filters[0][first_name]=Jahongir&or_filters[0][last_name]=Jahongir&or_filters[0][middle_name]=Jahongir
             */
            ->when($orFilters, function ($query, $filters) {
                $this->filters($query, $filters, 'or');
            })
            ->when(true, function ($query) use ($orderBy, $sort) {
                return $this->orderBy($query, $orderBy, $sort);
            })
            ->when($trashed, fn($query) => $query->onlyTrashed())
            ->with($relations);
    }

    protected function search(Builder $query, $search): void
    {
        $search = rtrim($search, " \t.");
        $query->where(function (Builder $query) use ($search) {
            foreach ($this->model->getSearchable() as $key => $field) {
                if (is_array($field)) {
                    $relation = $field[0];
                    foreach ($field[1] as $index => $value) {
                        if ($index === "json") {
                            foreach (AvailableLocalesEnum::toArray() as $lang) {
                                $query->orWhereRelation($relation, "$value->$lang", "like", "%$search%");
                            }
                        } elseif ($index === "date") {
                            $time = Carbon::createFromTimestamp(strtotime($search));
                            $query->orWhereDate($index, $time);
                        } else {
                            $query->orWhereRelation($relation, $value, 'like', "%$search%");
                        }
                    }
                } elseif (str_contains($field, '.')) {
                    $relation = explode('.', $field);
                    $column   = array_pop($relation);
                    $query->orWhereRelation(implode('.', $relation), $column, "like", "%$search%");
                } elseif ($this->isJson($field)) {
                    foreach (AvailableLocalesEnum::toArray() as $lang) {
                        $query->orWhere("$field->$lang", "like", "%$search%");
                    }
                } elseif ($this->inDates($field)) {
                    $time = Carbon::createFromTimestamp(strtotime($search));
                    $query->orWhereDate($field, $time);
                } else {
                    $query->orWhere($field, "like", "%$search%");
                }
            }
        });
    }

    private function orderBy(
        Builder $query,
        string $orderBy = "id",
        string $sort = 'DESC'
    ) {
        if (str_contains($orderBy, ',')) {
            $fields = explode(',', $orderBy);
            foreach ($fields as $field) {
                $field = $this->isJson($field) ?
                    $field . "->" . app()->getLocale() : $field;
                $query->orderBy($field, $sort);
            }
        } else {
            $orderBy = $this->isJson($orderBy) ?
                $orderBy . "->" . app()->getLocale() : $orderBy;
            $query->orderBy($orderBy, $sort);
        }
    }

    private function filters($query, $filters, string $boolean = 'and'): void
    {
        $query->where(function ($query) use ($filters, $boolean) {
            $filters = $filters[array_key_first($filters)];
            foreach ($filters as $key => $filter) {
                if (in_array($key, $this->model->getFillable(), true)
                    || in_array($key, $this->model->getDates(), true)
                    || $key === "id") {
                    if ($this->isSearchable($key)) {
                        if ($this->isJson($key)) {
                            $query->where(function ($query) use ($key, $filter) {
                                foreach (AvailableLocalesEnum::toArray() as $lang) {
                                    $query->orWhere("$key->$lang", "like", "%$filter%");
                                }
                            });
                        } elseif ($this->inDates($key)) {
                            $time = Carbon::createFromTimestamp(strtotime($filter));
                            $query->orWhereDate($key, $time);
                        } else {
                            $query->where($key, 'like', "%$filter%", boolean: $boolean);
                        }
                    } elseif (in_array($key, $this->model->getDates(), true)) {
                        $time = Carbon::createFromTimestamp(strtotime($filter));
                        $query->orWhereDate($key, $time);
                    } elseif ($key === "id" || is_array($filter)) {
                        $filter = is_array($filter) ? $filter : explode(',', $filter);
                        $query->whereIn($key, $filter, boolean: $boolean);
                    } else {
                        $query->where($key, '=', $filter, boolean: $boolean);
                    }
                } elseif (str_contains($key, '.')) {
                    $relation = explode('.', $key);
                    $column   = array_pop($relation);
                    $this->whereInRelation($query, implode('.', $relation), $column, Arr::wrap($filter), $boolean);
                } else {
                    $query->where($key, '=', $filter, boolean: $boolean);
                }
            }
        });
    }

    public function whereInRelation(Builder $query, $relation, $column, array $value, $boolean = 'and')
    {
        return $query->whereHas($relation, function (Builder $query) use ($column, $value, $boolean) {
            $query->whereIn($column, $value, boolean: $boolean);
        });
    }

    protected function inDates(string $field)
    {
        $dates = Cache::remember($this->model->getTable(), 60 * 60 * 24, function () {
            $keys = collect($this->model->getCasts())
                ->filter(function ($value, $key) {
                    if (str_contains($value, 'DateCasts')
                        || str_contains($value, 'datetime')
                        || str_contains($value, 'date')) {
                        return $key;
                    }
                });

            return $keys->keys();
        })->toArray();

        return in_array($field, $dates);
    }

    public function collection(
        Builder $query,
        int|string $limit = 30,
        array $appends = [],
        string|array|null $pluck = null
    ): Collection {
        $query = $this->availability($query)
            ->when($limit !== 'all', function ($query) use ($limit) {
                $query->limit($limit);
            });

        if ($pluck) {
            if (is_array($pluck)) {
                $column = $pluck['column'];
                $key    = $pluck['key'] ?? null;
            } else {
                $column = $pluck;
                $key    = null;
            }

            return $query->pluck($column, $key);
        } else {
            return $query->get()
                ->append($appends);
        }
    }

    public function pagination(
        Builder $query,
        int $per_page = 30,
        array $appends = [],
    ): LengthAwarePaginator {
        return $this->availability($query)
            ->paginate($per_page);
    }

    public function inFillable(string $field): bool
    {
        return in_array($field, $this->model->getFillable(), true);
    }

    public function isJson(string $field): bool
    {
        return method_exists($this->model, 'getJsonColumns') &&
            in_array($field, $this->model->getJsonColumns() ?? [], true);
    }

    public function isSearchable(string $field): bool
    {
        return method_exists($this->model, 'getSearchable') &&
            in_array($field, $this->model->getSearchable() ?? [], true);
    }

    /**
     * Show entity
     *
     * @param Model|int $model
     * @param array|string[] $columns
     * @param array $relations
     * @param array $appends
     *
     * @return Model|null
     */
    public function show(
        Model|int $model,
        array $columns = ['*'],
        array $relations = [],
        array $appends = []
    ): ?Model {
        $id = ($model instanceof Model) ? $model->id : $model;

        return $this->findById($id, $columns, $relations, $appends);
    }

    /**
     * Create element
     *
     * @param array $payload
     *
     * @return mixed
     */
    public function create(array $payload): mixed
    {
        return $this->model->create($payload);
    }

    /**
     * Update element
     *
     * @param Model|int $model
     * @param array $payload
     *
     * @return bool
     */
    public function update(Model|int $model, array $payload): bool
    {
        return ($model instanceof Model)
            ? $model->update($payload)
            : $this->findById($model)->update($payload);
    }

    /**
     * Delete element
     *
     * @param Model|int $model
     *
     * @return bool
     */
    public function delete(Model|int $model): bool
    {
        return ($model instanceof Model)
            ? $model->delete()
            : $this->findById($model)->delete();
    }

    /**
     * Find element by id
     *
     * @param int $modelId
     * @param array|string[] $columns
     * @param array $relations
     * @param array $appends
     *
     * @return Model|null
     */
    public function findById(
        int $modelId,
        array $columns = ['*'],
        array $relations = [],
        array $appends = [],
    ): ?Model {
        return $this->availability($this->model)->select($columns)
            ->with($relations)
            ->findOrFail($modelId)
            ->append($appends);
    }
}
