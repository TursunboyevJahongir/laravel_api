<?php

namespace App\Helpers\Generators;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Generator
{
    public function __construct(
        protected string $name,
        protected $path,
        protected $stub,
        protected string|null $model = null,
    ) {
        $this->makeFolder();
        $this->make();
    }

    public function getStubVariables()
    {
        return [
            '#namespace'    => str_replace('/', '\\', $this->path),
            '#ClassName'    => $this->name,
            '#ModelName'    => $this->model ?? $this->name,
            '#namePlural'   => Str::plural(Str::camel($this->model ?? $this->name)),
            '#nameSingular' => Str::singular(Str::camel($this->model ?? $this->name)),

            '#CoreController' => config('modulegenerator.core_controller'),
            '#CoreService'    => config('modulegenerator.core_service'),
            '#CoreRepository' => config('modulegenerator.core_repository'),

            '#webController' => config('modulegenerator.web.controller_path'),
            '#webRoute'      => config('modulegenerator.web.route'),

            '#apiController' => config('modulegenerator.api.controller_path'),
            '#apiRoute'      => config('modulegenerator.api.route'),

            '#controllerPath' => config('modulegenerator.controller_path'),
            '#repasitoryPath' => config('modulegenerator.repository_path'),
            '#routePath'      => config('modulegenerator.route_path'),
            '#servicePath'    => config('modulegenerator.service_path'),
            '#policyPath'     => config('modulegenerator.policy_path'),
            '#modelPath'      => config('modulegenerator.model_path'),
            '#requestsPath'   => config('modulegenerator.request_path'),
        ];
    }

    public function make()
    {
        foreach (Arr::wrap($this->stub) as $stub) {
            $path = $this->path . '/' . $this->getFilename($stub);
            if (!File::exists($path)) {
                File::put($path, $this->getContents($stub));
                echo "File $path created successfull \n";
            }
        }
    }

    public function getFilename($stub)
    {
        return $this->name . Str::studly($stub) . '.php';
    }

    public function getContents($stub)
    {
        $contents = file_get_contents(__DIR__ . '/Stubs/' . $stub . '.stub');

        foreach ($this->getStubVariables() as $search => $replace) {
            $contents = str_replace($search, $replace, $contents);
        }

        return $contents;
    }

    public function makeFolder()
    {
        File::makeDirectory($this->path, 0777, true, true);
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getStub()
    {
        return $this->stub = Arr::wrap($this->stub);
    }
}
