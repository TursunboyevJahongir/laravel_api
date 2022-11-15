<?php
/** @noinspection all */


namespace Illuminate\Database\Query {

    use Illuminate\Database\Eloquent\Relations\Relation;
    use Illuminate\Pagination\LengthAwarePaginator;
    use Illuminate\Support\Collection;

    /**
     * @method $this between(array $between = null)
     * @method Builder dbQuery(\Illuminate\Database\Query\Builder $query, array $columns = null)
     * @method $this notBetween(array $notBetween = null)
     * @method $this conditions(array $conditions = null, string $boolean = 'and')
     * @method $this orConditions(array $orConditions = null)
     * @method $this notConditions(array $notConditions = null)
     * @method $this isActive(boolen $status = null)
     * @method closure($class, string $status)
     * @method bool inDates(string $field)
     * @method bool isSearchable(string $field)
     * @method bool isTranslatable(string $field)
     * @method string jsonTranslate(string $lang = null)
     * @method $this onlyTrashed()
     * @method $this orWhereLikeRelation(string $relation, string $column, string $search)
     * @method LengthAwarePaginator|Collection paginationOrCollection()
     * @method LengthAwarePaginator pagination()
     * @method Collection collection(): Collection
     * @method int restore()
     * @method $this search(string|null $search = null)
     * @method $this searchBy(array|null $searchBy = null)
     * @method $this sortBy(string $orderBy = "id", string $sort = 'DESC')
     * @method $this whereConditions(array $conditions = null, string $boolean = 'and')
     * @method $this withTrashed($withTrashed = true)
     * @method $this withoutTrashed()
     * @method whereInRelation($relation, $column, array $value, $boolean = 'and')
     * @method $this whereLike(array|string $columns, string $search)
     * @method $this orWhereLike(array|string $columns, string $search)
     */
    class Builder
    {
    }
}
