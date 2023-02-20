<?php

namespace App\Console\Commands;

use App\Core\Helpers\Generators\Generator;
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
    protected $signature = 'make:module {Module : module name} {--model= : Get Model from App/Model}{--m : create migration}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simple way create module';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $module  = Str::studly($this->argument('Module'));
        $migrate = $this->option('m') ? '-m' : '';
        if ($this->option('model') && !File::exists(str_replace('\\', '/', config('modulegenerator.model_path')) . "/{$this->option('model')}.php")) {
            $this->error("'{$module}' Model dosn't exists");
            die();
        }

        if (File::exists(config('modulegenerator.repository_path') . "/{$module}Repository.php")) {
            $this->error("Repository '{$module}' already exists");
            die();
        }

        if (File::exists(config('modulegenerator.service_path') . "/{$module}Service.php")) {
            $this->error("Service '{$module}' already exists");
            die();
        }

        if (config('modulegenerator.api') && File::exists(config('modulegenerator.controller_path') . "/{$module}Controller.php")) {
            $this->error("Controller '{$module}' already exists");
            die();
        }

        if (File::exists(config('modulegenerator.policy_path') . "/{$module}Policy.php")) {
            $this->error("Policy '{$module}' already exists");
            die();
        }

        if (File::exists("tests/Feature/{$module}Test.php")) {
            $this->error("Feature Test '{$module}' already exists");
            die();
        }

        $progressBar = $this->output->createProgressBar(10);
        $progressBar->start();
        $model = $this->option('model') ?? $module;
        Artisan::call("make:model " . str_replace('\\', '/', config('modulegenerator.model_path')) . '/' . $model . "$migrate");
        $progressBar->advance();
        //$this->info("\n" . '<fg=green> Model created</>');

        new Generator($module, config('modulegenerator.repository_path'), 'repository', $model);
        $progressBar->advance();
        //$this->info("\n" . '<fg=green>Repository created</>');

        new Generator($module, config('modulegenerator.service_path'), 'service', $model);
        $progressBar->advance();
        //$this->info("\n" . '<fg=green>Service created</>');

        new Generator($module, config('modulegenerator.controller_path'), 'controller', $model);
        $progressBar->advance();
        //$this->info("\n" . '<fg=green>Controller created</>');

        //new Generator($module, config('modulegenerator.request_path'), 'request', $model);
        Artisan::call("make:request " . str_replace('\\', '/', config('modulegenerator.request_path')) . '/' . $module . "CreateRequest");
        Artisan::call("make:request " . str_replace('\\', '/', config('modulegenerator.request_path')) . '/' . $module . "UpdateRequest");
        $progressBar->advance();
        //$this->info("\n" . '<fg=green>Requests created</>');

        new Generator($module, config('modulegenerator.policy_path'), 'policy', $model);
        $progressBar->advance();
        //$this->info("\n" . '<fg=green>Policy created</>');

        new Generator($module, config('modulegenerator.route_path'), 'route', $model);
        $progressBar->advance();
        //$this->info("\n" . '<fg=green>route created</>');

        new Generator($module, 'tests/Feature', 'featureResourceTest', $model);
        $progressBar->advance();
        //$this->info("\n" . '<fg=green>route created</>');

        $progressBar->finish();
        $this->info("\n" . 'Command completed successfully ✅');
    }
}
