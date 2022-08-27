<?php

return [
    'core_controller' => 'App\Core\Http\Controllers\CoreController',
    'core_service'    => 'App\Core\Services\CoreService',
    'core_repository' => 'App\Core\Repositories\CoreRepository',
    'core_policy'     => 'App\Core\Repositories\CoreRepository',

    'route_path'      => 'Routes\api',
    'repository_path' => 'App\Contracts',
    'service_path'    => 'App\Services',
    'model_path'      => 'App\Models',
    'request_path'    => 'App\Http\Requests',
    'policy_path'     => 'App\Policies',
    'controller_path' => 'App\Http\Controllers',
    'api'             => [
        'controller_path' => 'App\Http\Controllers\Api',
        'request_path'    => 'App\Http\Requests',
        'route'           => 'routes/api.php',
    ],

    'web' => [
        'controller_path' => 'App\Http\Controllers',
        'request_path'    => 'App\Http\Requests',
        'route'           => 'routes/web.php',
    ],
];
