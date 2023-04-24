<?php

namespace App\Console\Commands;

use App\Core\Helpers\Generators\Generator;
use App\Core\Helpers\Generators\OwnGenerator;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class MakeModule extends Command
{
    use Generator, OwnGenerator;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:module {Module : module name} {--model= : Get Model from App/Model}{--all : generate all}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Simple way create module';

    protected string  $module;
    protected ?string $model = null;

    private $options = ['model',
                        'migration',
                        'factory',
                        'controller',
                        'request_validator',
                        'policy',
                        'service',
                        'repository',
                        'featureResourceTest',
                        'route',
    ];

    /** @var array<string> $fields */
    private array $fields = [];

    /** @var string ['own','default'] */
    private string $generate = 'default';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $this->module = Str::studly($this->argument('Module'));
        $this->model  = $this->option('model') ?? $this->module;

        $type = $this->choice('need custom or default stub ?', ['default', 'own'], 'default');

        if ($type == 'own') {
            $fieldsInMemory = cache()->rememberForever('module-fields', function () {
                return [];
            });

            $input = $this->anticipate(
                'you need to write fields with a comma(e.g. title,description)',
                $fieldsInMemory
            );
            if (!in_array($input, $fieldsInMemory)) {
                $fieldsInMemory[] = $input;
            }

            foreach (explode(',', $input) as $item) {
                $field = Str::snake(trim($item));
                if (!in_array($field, $fieldsInMemory)) {
                    $fieldsInMemory[] = $field;
                }

                $this->fields[] = $field;
            }

            cache()->forever('module-fields', $fieldsInMemory);
            $this->generate = 'own';
        }

        if ($this->option('all') || $this->confirm('Generate All ?', true)) {
            $this->generateAll();
        } else {
            $this->confirmOptions();
        }

        $this->info('\n' . 'Command completed successfully ✅');
    }

    protected function generateAll()
    {
        $options = array_fill_keys($this->options, true);
        $this->generateOptions($options);
    }

    /**
     * Confirm which options to generate.
     *
     * @return void
     */
    protected function confirmOptions()
    {
        $options = [];
        $this->info('Please select options!');
        foreach ($this->options as $option) {
            $options[$option] = $this->confirm($option, true);
        }

        $this->generateOptions($options);
    }

    /**
     * Generate the specified options.
     *
     * @param array $options
     *
     * @return void
     */
    protected function generateOptions(array $options)
    {
        $progressBar = $this->output->createProgressBar(count($options));

        $migrate = $options['migration'] ? '-m' : '';
        if ($options['model']) {
            dump('make:model ' . $this->model . ' ' . $migrate);
            if ($this->generate == 'own') {
                $this->generateOwn(config('modulegenerator.model_path'), 'generateModel');
            } else {
                $model = $this->option('model') ?? $this->module;
                Artisan::call("make:model " . str_replace('\\', '/', config('modulegenerator.model_path')) . '/' . $model . "$migrate");
            }
            $progressBar->advance();
        }

        if ($options['migration']) {
            if ($this->generate == 'own') {
                $this->createMigration(config('modulegenerator.migration_path'));
            } else {
                Artisan::call("make:migration " . 'create' . Str::studly(Str::snake($this->model)) . 'Table');
            }

            $progressBar->advance();
        }

        if ($options['service']) {
            $this->generateByStub(config('modulegenerator.service_path'), 'generateService');
            $progressBar->advance();
        }

        if ($options['repository']) {
            $this->generateByStub(config('modulegenerator.repository_path'), 'generateRepository');
            $progressBar->advance();
        }

        if ($options['controller']) {
            $this->generateByStub(config('modulegenerator.controller_path'), 'generateController');
            $progressBar->advance();
        }

        if ($options['request_validator']) {
            if ($this->generate == 'own') {
                $this->generateOwn(config('modulegenerator.request_path'), 'generateValidationCreateRequest');
                $this->generateOwn(config('modulegenerator.request_path'), 'generateValidationUpdateRequest');
            } else {
                Artisan::call("make:request " . str_replace('\\', '/', config('modulegenerator.request_path')) . '/' . $this->module . "CreateRequest");
                Artisan::call("make:request " . str_replace('\\', '/', config('modulegenerator.request_path')) . '/' . $this->module . "UpdateRequest");
            }
            $progressBar->advance();
        }

        if ($options['policy']) {
            $this->generateByStub(config('modulegenerator.policy_path'), 'generatePolicy');
            $progressBar->advance();
        }

        if ($options['route']) {
            $this->generateByStub(config('modulegenerator.policy_path'), 'route');
            $progressBar->advance();
        }

        if ($options['test']) {
            $this->generateByStub('tests/Feature', 'featureResourceTest');
            $progressBar->advance();
        }

        $progressBar->finish();
    }
}
