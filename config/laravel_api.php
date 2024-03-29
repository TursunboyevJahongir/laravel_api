<?php

return [
    'main_locale'       => 'ru',
    'available_locales' => ['ru', 'uz', 'en'],
    'request'           => [
        'order_by'       => 'orderBy',
        'sort_by'        => 'sortBy',
        'conditions'     => 'conditions',
        'or_conditions'  => 'or_conditions',
        'not_conditions' => 'not_conditions',
        'between'        => 'between',
        'not_between'    => 'not_between',
        'columns'        => 'columns',
        'limit'          => 'limit',
        'appends'        => 'appends',
        'pluck'          => 'pluck',
        'relations'      => 'relations',
        'onlyDeleted'    => 'onlyDeleted',
        'withDeleted'    => 'withDeleted',
        'only_deleted'   => 'only_deleted',
        'is_active'      => 'is_active',
        'per_page'       => 'per_page',
        'list_type'      => 'list_type',
        'getBy'          => 'getBy',
        'search'         => 'search',
        'searchFields'   => 'searchFields',
    ],
    'default'           =>//request default value
        [
            "getBy"           => 'pagination',
            "list_type"       => 'pagination',//pagination,collection
            "page_size"       => 30,
            "pagination_size" => 30,
            "order_by"        => 'id',
            "sort_by"         => 'DESC',
        ],
    'in'                => [
        'getBy' => ['pagination',
                    'collection',
                    'sum',
                    'avg',
                    'count',
                    'max',
                    'min',
                    'exists',
                    'doesntExists'],
    ],
    'check'             => //checking column
        [
            'is_active' => 'is_active',
        ],
];
