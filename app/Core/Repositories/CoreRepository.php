<?php

namespace App\Core\Repositories;

use App\Core\Contracts\CoreRepositoryContract;
use App\Core\Helpers\ResponseCode;
use Illuminate\Database\Eloquent\{Builder as EloquentBuilder, Builder, Model, Relations\Relation};
use Illuminate\Database\Query\Builder as QueryBuilder;
use Illuminate\Support\Collection;

abstract class CoreRepository implements CoreRepositoryContract
{
    public Model $model;

    /**
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function index(EloquentBuilder|Relation|null $query = null): mixed
    {
        return $this->model->query()
            ->eloquentQuery($query)
            //->columns()
            ->withRelations()
            ->withRelationsAggregates()
            ->conditions()
            ->orConditions()
            ->notConditions()
            ->search()
            //->between(['position'=>'100to200','column2'=>',to200','column3'=>'300'])
            ->between()
            ->notBetween()
            ->isActive()
            ->when(!$query, function (EloquentBuilder $query) {
                $query->closure($this, 'availability')->closure($this, 'appends');
            })
            ->sortBy()
            ->getBy();
    }

    /**
     * for any checks
     */
    public function availability(
        EloquentBuilder|Model $query
    ): void {
    }

    public function appends(EloquentBuilder $query): void
    {
    }

    public function indexDb(QueryBuilder $query): mixed
    {
        return \DB::query()
            ->dbQuery($query)
            ->conditions()
            ->notBetween()
            ->between()
            ->orConditions()
            ->notConditions()
            ->search()
            ->isActive()
            ->sortBy()
            ->getBy();
    }

    public function show(
        mixed $value,
        string $column = null,
        EloquentBuilder|Relation $query = null
    ): ?Model {
        return $this->firstBy($value, $column, $query);
    }

    public function create(array|Collection $payload): mixed
    {
        return $this->model->create($payload);
    }

    public function update(Model|int $model, array $payload): bool
    {
        return $this->show($model)->update($payload);
    }

    public function delete(Model|int $model): bool
    {
        return $this->show($model)->delete();
    }

    /**
     * Find element by column
     */
    public function firstBy(
        mixed $value,
        string $column = null,
        EloquentBuilder|Relation $query = null,
        bool $fail = true
    ): ?Model {
        if ($value instanceof Model) {
            //$by   = $find->getKeyName();
            $column = $column ?? $value->getKeyName();
            $value  = $value->{$value->getKeyName()};
        } else {//todo to'g'rimi
            $column = $column ?? $this->model->getKeyName();
        }

        $query = $this->model
            ->query()
            ->eloquentQuery($query)
            ->withRelations()
            ->withRelationsAggregates()
            ->when(!$query, function ($query) {
                $query->closure($this, 'availability');
            })
            ->where($column, $value);
        if ($fail) {
            return $query->firstOrFail()?->appends();
        }

        return $query->first()?->appends();
    }

    public function dbFirstBy(
        QueryBuilder $query,
        mixed $value,
        string $column = 'id',
        bool $fail = true
    ) {
        if ($query = \DB::query()->dbQuery($query)->where($column, $value)->first()) {
            return $query;
        }

        if ($fail) {
            throw new \Exception(__('errors.no_records'), ResponseCode::HTTP_NOT_FOUND);
        }

        return null;
    }
}
