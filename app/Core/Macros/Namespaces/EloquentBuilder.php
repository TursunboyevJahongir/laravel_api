<?php
/** @noinspection all */

namespace Illuminate\Database\Eloquent {

    use Illuminate\Database\Eloquent\Relations\Relation;
    use Illuminate\Pagination\LengthAwarePaginator;
    use Illuminate\Support\Collection as SupportCollection;

    /**
     * @method $this between(array $between = null)
     * @method $this notBetween(array $notBetween = null)
     * @method $this conditions(array $conditions = null, string $boolean = 'and')
     * @method $this orConditions(array $orConditions = null)
     * @method $this notConditions(array $notConditions = null)
     * @method $this isActive(boolen $status = null)
     * @method Builder|Relation eloquentQuery(Builder|Relation|null $query = null, array|string $columns = null,array|string $relations = null, bool $trashed = null)
     * @method closure($class, string $status)
     * @method bool inDates(string $field)
     * @method bool isSearchable(string $field)
     * @method bool isTranslatable(string $field)
     * @see Builder::jsonTranslate()
     * @method string jsonTranslate(string $lang = null)
     * @method $this onlyTrashed()
     * @method $this orWhereLikeRelation(string $relation, string $column, string $search)
     * @method LengthAwarePaginator|SupportCollection paginationOrCollection()
     * @method LengthAwarePaginator pagination()
     * @method SupportCollection collection(): Collection
     * @method int restore()
     * @method $this search(string|null $search = null, string|array $searchFields = null)
     * @method $this sortBy(string $orderBy = "id", string $sort = 'DESC')
     * @method $this whereConditions(array $conditions = null, string $boolean = 'and')
     * @method $this withTrashed($withTrashed = true)
     * @method $this withoutTrashed()
     * @method $this whereInRelation($relation, $column, array $value, $boolean = 'and')
     * @method $this whereLike(array|string $columns, string $search)
     * @method $this orWhereLike(array|string $columns, string $search)
     */
    class Builder
    {
    }

    /**
     * @method mixed appends(array|string $appends = [])
     */
    class Collection
    {
    }
}
