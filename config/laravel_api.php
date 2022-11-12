<?php

return [
    'params'  => [
        'order_by'     => 'orderBy',
        'sort_by'      => 'sortBy',
        'filters'      => 'filters',
        'or_filters'   => 'or_filters',
        'not_filters'  => 'not_filters',
        'between'      => 'between',
        'not_between'  => 'not_between',
        'columns'      => 'columns',
        'limit'        => 'limit',
        'appends'      => 'appends',
        'pluck'        => 'pluck',
        'relations'    => 'relations',
        'only_deleted' => 'only_deleted',
        'is_active'    => 'is_active',
        'per_page'     => 'per_page',
        'list_type'    => 'list_type',
        'search'       => 'search',
        'search_by'    => 'search_by',
    ],
    'default' =>//request default value
        [
            "list_type"       => 'pagination',//pagination,collection
            "page_size"       => 30,
            "pagination_size" => 30,
            "order_by"        => 'id',
            "sort_by"         => 'DESC',
        ],
    'check'   => //checking column
        [
            'is_active' => 'is_active',
        ],
];
