<?php

return [
    'base_controller'          => 'App\Core\Http\Controllers\CoreController',
    'base_service'             => 'App\Core\Services\CoreService',
    'base_repository'          => 'App\Core\Repositories\CoreRepository',
    'base_repository_contract' => 'App\Core\Contracts\CoreRepositoryContract',
    'base_service_contract'    => 'App\Core\Contracts\CoreServiceContract',

    'repository_path' => 'App\Contracts',
    'service_path'    => 'App\Services',
    'contract_path'   => 'App\Contracts',
    'model_path'      => 'App\Models',
    'request_path'    => 'App\Http\Requests',
    'policy_path'     => 'App\Policies',
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
