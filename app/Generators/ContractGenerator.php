<?php

namespace App\Generators;

class ContractGenerator extends BaseGenerator
{
    protected string $path = 'App/Contracts';
    protected array $stub = [
        'repository-contract',
        'service-contract',
    ];
}
