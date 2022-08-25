<?php

namespace App\Helpers\Generators;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Generator
{
    public function __construct(
        protected string $name,
        protected string $path,
        protected string|array $stub,
        protected string|null $model = null,
    ) {
        $this->makeFolder();
        $this->make();
    }

    public function getStubVariables()
    {
        return [
            '#namespace'     => str_replace('/', '\\', $this->path),
            '#ClassName'     => $this->name,
            '#ModelName'     => $this->model ?? $this->name,
            '#modelPlural'   => Str::plural(Str::camel($this->model ?? $this->name)),
            '#modelSingular' => Str::singular(Str::camel($this->model ?? $this->name)),

            '#BaseController'         => config('customizegenerator.base_controller'),
            '#BaseService'            => config('customizegenerator.base_service'),
            '#BaseRepository'         => config('customizegenerator.base_repository'),
            '#BaseRepositoryContract' => config('customizegenerator.base_repository_contract'),
            '#BaseServiceContract'    => config('customizegenerator.base_service_contract'),

            '#webController' => config('customizegenerator.web.controller_path'),
            '#webRoute'      => config('customizegenerator.web.route'),

            '#apiController' => config('customizegenerator.api.controller_path'),
            '#apiRoute'      => config('customizegenerator.api.route'),

            '#repasitoryPath' => config('customizegenerator.repository_path'),
            '#servicePath'    => config('customizegenerator.service_path'),
            '#policyPath'     => config('customizegenerator.policy_path'),
            '#modelPath'      => config('customizegenerator.model_path'),
            '#contractPath'   => config('customizegenerator.contract_path'),
            '#requestsPath'   => config('customizegenerator.request_path'),
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

    public function getFilename($stub) {
        return $this->name . Str::studly($stub) . '.php';
    }

    public function getContents($stub)
    {
        $contents = file_get_contents(app_path('Helpers/Generators/Stubs/' . $stub . '.stub'));

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
