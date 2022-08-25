<?php

namespace App\Console\Commands;

use App\Helpers\Generators\Generator;
use App\Helpers\Generators\RequestGenerator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CreateRequest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:request {Name : Request name} {path: Path to save the request}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Custom request generator';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $name = Str::studly($this->argument('Name'));
        dd($this->argument('path'));
        $path = $this->argument('path') ?? config('customizegenerator.request_path');

        if (File::exists(config('customizegenerator.request_path') . "/{$name}.php")) {
            $this->error("Request already exists!");
            die();
        }

        new Generator($name, $path, 'request');
        //Artisan::call("make:request " . str_replace('\\', '/', config('customizegenerator.request_path')) . '/' . $module . "CreateRequest");
        //Artisan::call("make:request {$module}UpdateRequest");
        $this->info($name . " Request created successfully.");
    }
}
