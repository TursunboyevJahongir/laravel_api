<?php

namespace App\Helpers\Generators;

class RequestGenerator extends Generator
{
    public function __construct(string $name)
    {
        parent::__construct($name);
        $this->setPath(config('customizegenerator.request_path'));
        $this->setStub('request');
    }
}
