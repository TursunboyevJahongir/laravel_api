<?php

namespace App\Helpers\Generators;

class ContractGenerator extends BaseGenerator
{
    protected $path = 'App/Contracts';
    protected $stub = [
        'repository-contract',
        'service-contract',
    ];
}
