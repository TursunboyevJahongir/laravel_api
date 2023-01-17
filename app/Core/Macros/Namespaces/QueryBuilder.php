<?php
/** @noinspection all */


namespace Illuminate\Database\Query {

    use Illuminate\Pagination\LengthAwarePaginator;
    use Illuminate\Support\Collection;

    /**
     * @method $this between(array $between = null)
     * @method $this dbQuery(\Illuminate\Database\Query\Builder $query, array $columns = null)
     * @method $this notBetween(array $notBetween = null)
     * @method $this conditions(array $conditions = null, string $boolean = 'and')
     * @method $this orConditions(array $orConditions = null)
     * @method $this notConditions(array $notConditions = null)
     * @method $this isActive(boolen $status = null)
     * @method closure($class, string $status)
     * @method LengthAwarePaginator|Collection paginationOrCollection()
     * @method LengthAwarePaginator pagination()
     * @method Collection collection(): Collection
     * @method mixed getBy(string $type = null)
     * @method int restore()
     * @method $this search(string|null $search = null, string|array $searchFields = null)
     * @method $this sortBy(string $orderBy = "id", string $sort = 'DESC')
     * @method $this whereConditions(array $conditions = null, string $boolean = 'and')
     * @method $this whereLike(array|string $columns, string $search)
     * @method $this orWhereLike(array|string $columns, string $search)
     */
    class Builder
    {
    }
}
