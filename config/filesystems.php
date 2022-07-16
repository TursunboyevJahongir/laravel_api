<?php

return [
    'default' => env('FILESYSTEM_DRIVER', 'local'),

    'disks' => [

        'local' => [
            'driver' => 'local',
            'root'   => storage_path('/app/public/uploads'),
        ],

        'public' => [
            'driver'     => 'local',
            'root'       => storage_path('/app/public'),
            'url'        => env('APP_URL') . '/storage',
            'visibility' => 'public',
        ],

        's3' => [
            'driver'   => 's3',
            'key'      => env('AWS_ACCESS_KEY_ID'),
            'secret'   => env('AWS_SECRET_ACCESS_KEY'),
            'region'   => env('AWS_DEFAULT_REGION'),
            'bucket'   => env('AWS_BUCKET'),
            'url'      => env('AWS_URL'),
            'endpoint' => env('AWS_ENDPOINT'),
        ],

    ],

    //php artisan storage:link
    'links' => [
        public_path('uploads') => storage_path('app/public/uploads'),
    ],
];
