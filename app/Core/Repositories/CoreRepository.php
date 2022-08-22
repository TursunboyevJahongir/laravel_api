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
     * @return void
     */
    public function availability(
        Builder|Model $query
    ): void {
    }

    public function query(
        array $columns = ['*'],
        array $relations = [],
        string $search = null,
        array|null $filters = null,
        array|null $notFilters = null,
        array|null $orFilters = null,
        bool $trashed = false,
        string $orderBy = 'id',
        string $sort = 'desc',
        Builder|null $query = null
    ): Builder {
        $columns    = request()->get('columns', ['*']);
        $relations  = request()->get('relations', []);
        $search     = request()->get('search');
        $filters    = request()->get('filters');
        $notFilters = request()->get('not_filters');
        $orFilters  = request()->get('or_filters');
        $trashed    = request()->get('only_deleted', false);
        $orderBy    = request()->get('order', 'id');
        $sort       = request()->get('sort', 'DESC');

        return $this->mainQuery($columns, $relations, $search, $filters, $notFilters, $orFilters, $trashed, $orderBy, $sort, $query);
    }

    public function mainQuery(
        array $columns = [' * '],
        array $relations = [],
        string $search = null,
        array|null $filters = null,
        array|null $notFilters = null,
        array|null $orFilters = null,
        bool $trashed = false,
        string $orderBy = 'id',
        string $sort = 'desc',
        Builder|null $query = null
    ): Builder {
        return $this->model
            ->select($columns)
            ->closure($this, 'availability')
            ->when($search, fn($q) => $this->search($q, $search))
            /**
             * to filter filters[0][status]=activated&filters[0][name]="Jahongir"
             */
            ->when($filters, fn($q) => $q->filters($filters,'and'))
            /**
             * not equal
             * not filter not_filters[0][status]=activated
             */
            ->when($notFilters, fn($que) => $que->whereNot(fn($q) => $q->filters($q, $notFilters)))
            /**
             * or filter
             * or_filters[0][first_name]=Jahongir&or_filters[0][last_name]=Jahongir&or_filters[0][middle_name]=Jahongir
             */
            ->when($orFilters, fn($q) => $q->filters($orFilters, 'or'))
            ->when($trashed, fn($query) => $query->onlyTrashed())
            ->when($trashed, fn($query) => $query->onlyTrashed())
            ->when(true, fn($q) => $this->orderBy($q, $orderBy, $sort))
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

    public function orderBy(
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

    public function collection(
        Builder $query,
        int|string $limit = 30,
        array $appends = [],
        string|array|null $pluck = null
    ): Collection {
        $query = $query->when(true, fn($q) => $this->availability($q))
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
        return $query->when(true, fn($q) => $this->availability($q))
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
        return $this->model
            ->closure($this, 'availability')
            ->select($columns)
            ->with($relations)
            ->findOrFail($modelId)
            ->append($appends);
    }
}
