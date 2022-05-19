<?php

namespace App\Console\Commands;

use App\Helpers\Generators\ContractGenerator;
use App\Helpers\Generators\ControllerGenerator;
use App\Helpers\Generators\EditAppServiceProvider;
use App\Helpers\Generators\EditRoute;
use App\Helpers\Generators\PolicyGenerator;
use App\Helpers\Generators\RepositoryGenerator;
use App\Helpers\Generators\ServiceGenerator;
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
     *
     * @return void
     */
    public function handle()
    {
        $module  = Str::studly($this->argument('Module'));
        $migrate = $this->option('m') ? '-m' : '';
        if ($this->option('model') && !File::exists("App/Models/{$this->option('model')}.php")) {
            $this->error("Model dosn't exists");
            die();
        }

        if (File::exists(config('modulegenerator.contract_path') . "/{$module}RepositoryContract.php")) {
            $this->error("RepositoryContract already exists");
            die();
        }
        if (File::exists(config('modulegenerator.repository_path') . "/{$module}Repository.php")) {
            $this->error("Repository already exists");
            die();
        }
        if (File::exists(config('modulegenerator.contract_path') . "/{$module}ServiceContract.php")) {
            $this->error("ServiceContract already exists");
            die();
        }
        if (File::exists(config('modulegenerator.service_path') . "/{$module}Service.php")) {
            $this->error("Service already exists");
            die();
        }

        if (config('modulegenerator.web') && File::exists(config('modulegenerator.web.controller_path') . "/{$module}Controller.php")) {
            $this->error("Controller already exists");
            die();
        }

        if (config('modulegenerator.api') && File::exists(config('modulegenerator.api.controller_path') . "/{$module}Controller.php")) {
            $this->error("Controller already exists");
            die();
        }

        if (File::exists(config('modulegenerator.policy_path') . "/{$module}Policy.php")) {
            $this->error("Policy already exists");
            die();
        }

        $progressBar = $this->output->createProgressBar(10);
        $progressBar->start();
        $model = $this->option('model') ?? $module;
        Artisan::call("make:model " . str_replace('\\', '/', config('modulegenerator.model_path')) . '/' . $model . "$migrate");
        $progressBar->advance();
        $this->info("\n" . '<fg=green> Model created</>');

        new ContractGenerator($module, $model);
        $progressBar->advance();
        $this->info("\n" . '<fg=green>Contract created</>');

        new RepositoryGenerator($module, $model);
        $progressBar->advance();
        $this->info("\n" . '<fg=green>Repository created</>');

        new ServiceGenerator($module, $model);
        $progressBar->advance();
        $this->info("\n" . '<fg=green>Service created</>');

        new ControllerGenerator($module, $model);
        $progressBar->advance();
        $this->info("\n" . '<fg=green>Controller created</>');

        Artisan::call("make:request " . str_replace('\\', '/', config('modulegenerator.request_path')) . '/' . $module . "CreateRequest");
        Artisan::call("make:request {$module}UpdateRequest");
        $progressBar->advance();
        $this->info("\n" . '<fg=green>Requests created</>');

        EditAppServiceProvider::handle($module);
        $progressBar->advance();
        $this->info("\n" . '<fg=green>AppServiceProvider edited</>');

        new PolicyGenerator($module, $model);
        $progressBar->advance();
        $this->info("\n" . '<fg=green>Policy created</>');

        EditRoute::handle($module);
        $progressBar->advance();
        $this->info("\n" . '<fg=green>Route edited</>');

        $progressBar->finish();
        $this->info("\n" . 'Command completed successfully ✅');
    }
}
