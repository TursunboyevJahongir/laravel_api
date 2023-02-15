<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class RefreshAppCommand extends Command
{
    protected $signature = 'app:refresh';

    protected $description = 'Command description';

    public function handle(): void
    {
        /*

██████╗ ██╗  ██╗██████╗
██╔══██╗██║  ██║██╔══██╗
██████╔╝███████║██████╔╝
██╔═══╝ ██╔══██║██╔═══╝
██║     ██║  ██║██║
╚═╝     ╚═╝  ╚═╝╚═╝

        */
        $this->info('
            <fg=cyan>*</> <fg=red>* * * * * * * * * * * * *</> <fg=cyan>*</>
            <fg=red>*</> <fg=default>██████<bg=red>╗</> ██<bg=red>╗</>  ██<bg=red>╗</>██████<bg=red>╗</>   <fg=red>*</>
            <fg=red>*</> ██<bg=red>╔══</>██<bg=red>╗</>██<bg=red>║</>  ██<bg=red>║</>██<bg=red>╔══</>██<bg=red>╗</>  <fg=red>*</>
            <fg=red>*</> ██████<bg=red>╔╝</>███████<bg=red>║</>██████<bg=red>╔╝</>  <fg=red>*</>
            <fg=red>*</> ██<bg=red>╔═══╝</> ██<bg=red>╔══</>██<bg=red>║</>██<bg=red>╔═══╝</>   <fg=red>*</>
            <fg=red>*</> ██<bg=red>║</>     ██<bg=red>║</>  ██<bg=red>║</>██<bg=red>║</>       <fg=red>*</>
            <fg=red>*</> <bg=red>╚═╝</>     <bg=red>╚═╝</>  <bg=red>╚═╝╚═╝</></>       <fg=red>*</>
            <fg=cyan>*</> <fg=red>* * * * * * * * * * * * *</> <fg=cyan>*</>');

        /**
         * check not in production
         */
        if ($this->getLaravel()->isProduction() || !$this->getLaravel()->isLocal()) {
            $this->error('This command is not available in production.');
        } elseif ($this->confirm("<bg=yellow>Do you really want to refresh DB ?</><bg=red> All data will lost!</>",
                                 true)) {
            $progressBar = $this->output->createProgressBar(10);
            $progressBar->start();
            Artisan::call('migrate:refresh');
            $progressBar->advance(5);
            $this->info("\n" . '<fg=green>Migration refreshed</>');
            $progressBar->advance();
            \Storage::disk('public')->deleteDirectory('/uploads');
            $this->info("\n" . '<fg=green>Uploads folder deleted</>');
            Artisan::call('db:seed');
            $output = Artisan::output();
            /**
             * check if seed is ok
             */
            if (Str::contains($output, 'Illuminate\Database\QueryException')) {
                $this->error("\n" . 'Error');
                dd($output);
            }
            $this->info("\n" . '<fg=green>Seeded</>');
            $progressBar->finish();
            $this->info("\n" . 'The command was successful ✅');
        }
    }
}
