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
        'only_deleted'   => 'only_deleted',
        'is_active'      => 'is_active',
        'per_page'       => 'per_page',
        'list_type'      => 'list_type',
        'search'         => 'search',
        'searchFields'   => 'searchFields',
    ],
    'default'           =>//request default value
        [
            "list_type"       => 'pagination',//pagination,collection
            "page_size"       => 30,
            "pagination_size" => 30,
            "order_by"        => 'id',
            "sort_by"         => 'DESC',
        ],
    'check'             => //checking column
        [
            'is_active' => 'is_active',
        ],
];
