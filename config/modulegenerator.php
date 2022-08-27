<?php

return [
    'core_controller' => 'app\Core\Http\Controllers\CoreController',
    'core_service'    => 'app\Core\Services\CoreService',
    'core_repository' => 'app\Core\Repositories\CoreRepository',
    'core_policy'     => 'app\Core\Repositories\CoreRepository',

    'route_path'      => 'routes\api',
    'repository_path' => 'app\Contracts',
    'service_path'    => 'app\Services',
    'model_path'      => 'app\Models',
    'request_path'    => 'app\Http\Requests',
    'policy_path'     => 'app\Policies',
    'controller_path' => 'app\Http\Controllers',
    'api'             => [
        'controller_path' => 'app\Http\Controllers\Api',
        'request_path'    => 'app\Http\Requests',
        'route'           => 'routes/api.php',
    ],

    'web' => [
        'controller_path' => 'app\Http\Controllers',
        'request_path'    => 'app\Http\Requests',
        'route'           => 'routes/web.php',
    ],
];
