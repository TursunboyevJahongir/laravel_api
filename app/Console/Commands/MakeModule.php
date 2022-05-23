<?php

namespace App\Console\Commands;

use App\Helpers\Generators\ContractGenerator;
use App\Helpers\Generators\ControllerGenerator;
use App\Helpers\Generators\EditAppServiceProvider;
use App\Helpers\Generators\EditRoute;
use App\Helpers\Generators\Generator;
use App\Helpers\Generators\PolicyGenerator;
use App\Helpers\Generators\RepositoryGenerator;
use App\Helpers\Generators\RequestGenerator;
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

        if (File::exists(config('customizegenerator.contract_path') . "/{$module}RepositoryContract.php")) {
            $this->error("RepositoryContract already exists");
            die();
        }
        if (File::exists(config('customizegenerator.repository_path') . "/{$module}Repository.php")) {
            $this->error("Repository already exists");
            die();
        }
        if (File::exists(config('customizegenerator.contract_path') . "/{$module}ServiceContract.php")) {
            $this->error("ServiceContract already exists");
            die();
        }
        if (File::exists(config('customizegenerator.service_path') . "/{$module}Service.php")) {
            $this->error("Service already exists");
            die();
        }

        if (config('customizegenerator.web') && File::exists(config('customizegenerator.web.controller_path') . "/{$module}Controller.php")) {
            $this->error("Controller already exists");
            die();
        }

        if (config('customizegenerator.api') && File::exists(config('customizegenerator.api.controller_path') . "/{$module}Controller.php")) {
            $this->error("Controller already exists");
            die();
        }

        if (File::exists(config('customizegenerator.policy_path') . "/{$module}Policy.php")) {
            $this->error("Policy already exists");
            die();
        }

        $progressBar = $this->output->createProgressBar(10);
        $progressBar->start();
        $model = $this->option('model') ?? $module;
        Artisan::call("make:model " . str_replace('\\', '/', config('customizegenerator.model_path')) . '/' . $model . "$migrate");
        $progressBar->advance();
        $this->info("\n" . '<fg=green> Model created</>');

        new Generator($module, config('customizegenerator.contract_path'),
                      ['repository-contract', 'service-contract',], $model);
        $progressBar->advance();
        $this->info("\n" . '<fg=green>Contract created</>');

        new Generator($module, config('customizegenerator.repository_path'), 'repository', $model);
        $progressBar->advance();
        $this->info("\n" . '<fg=green>Repository created</>');

        new Generator($module, config('customizegenerator.service_path'), 'service', $model);
        $progressBar->advance();
        $this->info("\n" . '<fg=green>Service created</>');

        new Generator($module, config('customizegenerator.api.controller_path'), 'controller', $model);
        $progressBar->advance();
        $this->info("\n" . '<fg=green>Controller created</>');

        //new Generator($module, config('customizegenerator.request_path'), 'request', $model);
        Artisan::call("make:request " . str_replace('\\', '/', config('customizegenerator.request_path')) . '/' . $module . "CreateRequest");
        Artisan::call("make:request " . str_replace('\\', '/', config('customizegenerator.request_path')) . '/' . $module . "UpdateRequest");
        $progressBar->advance();
        $this->info("\n" . '<fg=green>Requests created</>');

        EditAppServiceProvider::handle($module);
        $progressBar->advance();
        $this->info("\n" . '<fg=green>AppServiceProvider edited</>');

        new Generator($module, config('customizegenerator.policy_path'), 'policy', $model);
        $progressBar->advance();
        $this->info("\n" . '<fg=green>Policy created</>');

        EditRoute::handle($module);
        $progressBar->advance();
        $this->info("\n" . '<fg=green>Route edited</>');

        $progressBar->finish();
        $this->info("\n" . 'Command completed successfully ✅');
    }
}
