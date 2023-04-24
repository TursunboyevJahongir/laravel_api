<?php

namespace App\Core\Helpers\Generators;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

trait Generator
{
    private $getStubVariables = null;

    public function getStubVariables()
    {
        return $this->getStubVariables ??
            $this->getStubVariables = [
                '#ClassName'    => $this->module,
                '#ModelName'    => $this->model ?? $this->module,
                '#namePlural'   => Str::plural(Str::camel($this->model ?? $this->module)),
                '#nameSingular' => Str::singular(Str::camel($this->model ?? $this->module)),

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

    private function generateByStub($path, $stub)
    {
        $this->makeFolder($path);
        $this->make($path, $stub);
    }

    public function make($path, $stub)
    {
        foreach (Arr::wrap($stub) as $stub) {
            $_path = str_replace('\\', '/', $path) . '/' . $this->getFilename($stub);
            $this->info('Path: ' . $_path);
            if (!File::exists($_path)) {
                File::put($_path, $this->getContents($stub), 0777);
                echo 'File $_path created successfull \n';
            }
        }
    }

    public function getFilename($stub)
    {
        return ($stub == 'route' ? strtolower($this->module) : $this->module . Str::studly($stub)) . '.php';
    }

    public function getContents($stub)
    {
        $contents = file_get_contents(__DIR__ . '/Stubs/' . $stub . '.stub');

        foreach ($this->getStubVariables() as $search => $replace) {
            $contents = str_replace($search, $replace, $contents);
        }

        return $contents;
    }

    public function makeFolder($path)
    {
        File::makeDirectory(str_replace('\\', '/', $path), 0777, true, true);
    }
}
