<?php

namespace App\Console\Commands;

use App\Generators\ContractGenerator;
use App\Generators\EditAppServiceProvider;
use App\Generators\RepositoryGenerator;
use App\Generators\ServiceGenerator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModule extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module {Module} {--model= : Get Model from App/Model}{--m : create migration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simple way create module';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $module = Str::studly($this->argument('Module'));
        $migrate = $this->option('m') ? '-m' : '';
        if ($this->option('model') && !File::exists("App/Models/{$this->option('model')}.php")) {
            $this->error("Model dosn't exists");
            die();
        }
        $model = $this->option('model') ?? $module;
        Artisan::call("make:model $model $migrate");
        new ContractGenerator($module, $model);
        new RepositoryGenerator($module, $model);
        new ServiceGenerator($module, $model);
        $use = [
            'use App\Contracts\\' . $module . 'ServiceContract;',
            'use App\Services\\' . $module . 'Service;'
        ];
        $bind = '$this->app->bind(' . $module . 'ServiceContract::class, ' . $module . 'Service::class);';
        new EditAppServiceProvider('App/Providers/AppServiceProvider.php', $use, $bind);//todo
        return 0;
    }
}
