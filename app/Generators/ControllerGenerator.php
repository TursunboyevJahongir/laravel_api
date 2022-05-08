<?php

namespace App\Generators;

class ControllerGenerator extends BaseGenerator
{
    protected $path = 'Http/Controllers';
    protected $stub = 'controller.stub';
    protected $changeFilename = 'Controller';
}
