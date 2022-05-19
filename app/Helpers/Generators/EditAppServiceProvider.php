<?php

namespace App\Helpers\Generators;

class EditAppServiceProvider
{
    public static function handle($module): void
    {
        $uses  =
            "use App\Contracts\\" . $module . "ServiceContract;\n"
            . "use App\Services\\" . $module . "Service;\n"
            . "use App\Contracts\\" . $module . "RepositoryContract;\n"
            . "use App\Repositories\\" . $module . "Repository;";
        $binds = '$this->app->bind(' . $module . 'ServiceContract::class, ' . $module . 'Service::class);' . "\n"
            . '$this->app->bind(' . $module . 'RepositoryContract::class, ' . $module . 'Repository::class);' . "\n";

        $file    = 'App/Providers/AppServiceProvider.php';
        $reading = fopen($file, 'r+');
        $writing = fopen(public_path('/providerEditHelper.tmp'), 'w');
        while (!feof($reading)) {
            $line = fgets($reading);
            if (stristr($line, 'namespace App\Providers')) {
                $line = "$line\n$uses";
            }
            if (stristr($line, '#biding to here')) {
                $line = "$line\n$binds";
            }

            if ($line != '') {
                fputs($writing, $line);
            }
        }
        fclose($reading);
        fclose($writing);
        rename(public_path('providerEditHelper.tmp'), $file);
    }

}
