<?php

namespace App\Core\Repositories;

use App\Enums\AvailableLocalesEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use App\Core\Contracts\CoreRepositoryContract;
use App\Core\Models\CoreModel;

abstract class CoreRepository implements CoreRepositoryContract
{
    public CoreModel|Model|Pivot $model;

    /**
     * @param CoreModel|Model|Pivot $model
     */
    public function __construct(CoreModel|Model|Pivot $model)
    {
        $this->model = $model;
    }

    public function availability(
            Builder|CoreModel $query
    ): CoreModel|Builder {
        return $query;
    }

    public function mainQuery(
            array $columns = ['*'],
            array $relations = [],
            int|null $status = null,
            int $start = 1,
            string $search = null,
            array|null $filters = null,
            array|null $notFilters = null,
            string $filterBy = 'id',
            string $order = 'desc'
    ): Builder {
        return $this->model
                //->where('id', '>=', $start)
                ->when(!is_null($status), function ($query) use ($status) {
                    $query->where('is_active', $status);
                })
                ->select($columns)
                ->when($search, function ($query) use ($search) {
                    $search = rtrim($search, " \t.");
                    $query->where(function ($query) use ($search) {
                        foreach ($this->model->getSearchable() as $field) {
                            if (is_array($field)) {
                                $relation = $field[0];
                                foreach ($field[1] as $key => $item) {
                                    if ($key === "json") {
                                        foreach (AvailableLocalesEnum::toArray() as $lang) {
                                            $query->orWhereRelation($relation, "$item->$lang", "like", "%$search%");
                                        }
                                    } elseif ($key === "date") {
                                        $time = Carbon::createFromTimestamp(strtotime($search));
                                        $query->orWhereDate($key, $time);
                                    } else {
                                        $query->orWhereRelation($relation, $item, 'like', "%$search%");
                                    }
                                }
                            } elseif ($this->isJson($field)) {
                                foreach (AvailableLocalesEnum::toArray() as $lang) {
                                    $query->orWhere("$field->$lang", "like", "%$search%");
                                }
                            } elseif (in_array($field, $this->model->getDates(), true)) {
                                $time = Carbon::createFromTimestamp(strtotime($search));
                                $query->orWhereDate($field, $time);
                            } else {
                                $query->orWhere($field, "like", "%$search%");
                            }
                        }
                    });
                })
                ->when($filters, function ($query, $filters) {
                    $filters = $filters[array_key_first($filters)];
                    $query->where(function ($query) use ($filters) {
                        foreach ($filters as $key => $filter) {
                            $query->when(in_array($key, $this->model->getFillable(), true)
                                         || in_array($key, $this->model->getDates(), true),
                                    function ($query) use ($key, $filter) {
                                        if ($this->isSearchable($key)) {
                                            if ($this->isJson($key)) {
                                                $query->where(function ($query) use ($key, $filter) {
                                                    foreach (AvailableLocalesEnum::toArray() as $lang) {
                                                        $query->orWhere("$key->$lang", "like", "%$filter%");
                                                    }
                                                });
                                            } elseif (in_array($key, $this->model->getDates(), true)) {
                                                $time = Carbon::createFromTimestamp(strtotime($filter));
                                                $query->orWhereDate($key, $time);
                                            } else {
                                                $query->where($key, 'like', "%$filter%");
                                            }
                                        } elseif (in_array($key, $this->model->getDates(), true)) {
                                            $time = Carbon::createFromTimestamp(strtotime($filter));
                                            $query->orWhereDate($key, $time);
                                        } else {
                                            $query->where($key, '=', $filter);
                                        }
                                    });
                        }
                    });
                })
                ->when($notFilters, function ($query, $filters) {
                    $filters = $filters[array_key_first($filters)];
                    $query->whereNot(function ($query) use ($filters) {
                        $query->where(function ($query) use ($filters) {
                            foreach ($filters as $key => $filter) {
                                $query->when(in_array($key, $this->model->getFillable(), true)
                                             || in_array($key, $this->model->getDates(), true),
                                        function ($query) use ($key, $filter) {
                                            if ($this->isSearchable($key)) {
                                                if ($this->isJson($key)) {
                                                    $query->where(function ($query) use ($key, $filter) {
                                                        foreach (AvailableLocalesEnum::toArray() as $lang) {
                                                            $query->orWhere("$key->$lang", "like", "%$filter%");
                                                        }
                                                    });
                                                } elseif (in_array($key, $this->model->getDates(), true)) {
                                                    $time = Carbon::createFromTimestamp(strtotime($filter));
                                                    $query->orWhereDate($key, $time);
                                                } else {
                                                    $query->where($key, 'like', "%$filter%");
                                                }
                                            } elseif (in_array($key, $this->model->getDates(), true)) {
                                                $time = Carbon::createFromTimestamp(strtotime($filter));
                                                $query->orWhereDate($key, $time);
                                            } else {
                                                $query->where($key, '=', $filter);
                                            }
                                        });
                            }
                        });
                    });
                })
                ->orderBy($filterBy, $order)
                ->with($relations);
    }

    public function collection(
            Builder $query,
            int|string $limit = 30,
            array $appends = [],
    ): Collection {
        return $this->availability($query)
                ->when($limit !== 'all', function ($query) use ($limit) {
                    $query->limit($limit);
                })
                ->get()
                ->append($appends);
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
        return in_array($field, $this->model->getJsonColumns(), true);
    }

    public function isSearchable(string $field): bool
    {
        return in_array($field, $this->model->getSearchable(), true);
    }

    /**
     * Show entity
     *
     * @param CoreModel|int $model
     * @param array|string[] $columns
     * @param array $relations
     * @param array $appends
     *
     * @return CoreModel|null
     */
    public function show(
            CoreModel|int $model,
            array $columns = ['*'],
            array $relations = [],
            array $appends = []
    ): ?CoreModel {
        $id = ($model instanceof CoreModel) ? $model->id : $model;

        return $this->findById($id, $columns, $relations, $appends);
    }

    /**
     * Create element
     *
     * @param array $payload
     *
     * @return CoreModel|Model|Pivot|null
     */
    public function create(array $payload): CoreModel|Model|Pivot|null
    {
        return $this->model->create($payload);
    }

    /**
     * Update element
     *
     * @param CoreModel|int $model
     * @param array $payload
     *
     * @return bool
     */
    public function update(CoreModel|int $model, array $payload): bool
    {
        return ($model instanceof CoreModel)
                ? $model->update($payload)
                : $this->findById($model)->update($payload);
    }

    /**
     * Delete element
     *
     * @param CoreModel|int $model
     *
     * @return bool
     */
    public function delete(CoreModel|int $model): bool
    {
        return ($model instanceof CoreModel)
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
     * @return CoreModel|null
     */
    public function findById(
            int $modelId,
            array $columns = ['*'],
            array $relations = [],
            array $appends = [],
    ): ?CoreModel {
        return $this->availability($this->model)->select($columns)
                ->with($relations)
                ->findOrFail($modelId)
                ->append($appends);
    }
}
