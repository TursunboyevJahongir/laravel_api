<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class RefreshAppCommand extends Command
{
    protected $signature = 'app:refresh';

    protected $description = 'Command description';

    public function handle()
    {
        if ($this->confirm('Do you really want to refresh DB? All data will lost!', true)) {
            Artisan::call('migrate:refresh', [
                '--seed' => true,
            ]);
            File::deleteDirectory(public_path('uploads'));
//            Artisan::call('module:seed');

            $this->info('The command was successful!');
        }
    }
}
