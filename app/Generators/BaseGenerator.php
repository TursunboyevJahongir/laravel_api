<?php

namespace App\Generators;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class BaseGenerator
{
    protected string $path;
    protected array $stub;

    public function __construct(protected string $name, protected string|null $model = null)
    {
        $this->makeFolder();
        $this->make();
    }

    public function getStubVariables()
    {
        return [
            '$namespace' => str_replace('/', '\\', $this->path),
            '$classname' => $this->name,
            '$modelname' => $this->model??$this->name,
        ];
    }

    public function make()
    {
        foreach ($this->stub as $stub) {
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
        $contents = file_get_contents(app_path('Generators/Stubs/' . $stub . '.stub'));

        foreach ($this->getStubVariables() as $search => $replace) {
            $contents = str_replace($search, $replace, $contents);
        }
        return $contents;
    }

    public function makeFolder()
    {
        File::makeDirectory($this->path, 0777, true, true);
    }
}
