<?php

namespace App\Helpers\Generators;

class ContractGenerator extends Generator
{
    protected $path = 'App/Contracts';
    protected $stub = [
        'repository-contract',
        'service-contract',
    ];
}
