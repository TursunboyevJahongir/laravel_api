<?php

namespace App\Helpers\Generators;

use Illuminate\Support\Str;

class EditRoute
{
    public static function handle($module): void
    {
        $route = Str::plural(Str::kebab($module));
        $uses  = "use App\Http\Controllers\Api\$moduleController;\n";
        $route = "        Route::apiResource('$route', {$module}Controller::class);\n";

        $file    = 'Routes/api.php';
        $reading = fopen($file, 'r+');
        $writing = fopen(public_path('/RouteEditHelper.tmp'), 'w');
        while (!feof($reading)) {
            $line = fgets($reading);
            if (stristr($line, '<?php')) {
                $line = "$line\n$uses";
            }
            if (stristr($line, '#new Resource to here')) {
                $line = "$line\n$route";
            }

            if ($line != '') {
                fputs($writing, $line);
            }
        }
        fclose($reading);
        fclose($writing);
        rename(public_path('RouteEditHelper.tmp'), $file);
    }

}
