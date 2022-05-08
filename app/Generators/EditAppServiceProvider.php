<?php

namespace App\Generators;

class EditAppServiceProvider
{
    public function __construct(protected string $file, protected array $use, protected string $bind)
    {
        $reading = fopen($this->file, 'r');
        $writing = fopen(public_path('/newModule.tmp'), 'w');

        while (!feof($reading)) {
            $line = fgets($reading);
            if (stristr($line, 'namespace App\Providers')) {
                foreach ($this->use as $use) {
                    $line = "$line\n$use";
                }
            }
            if (stristr($line, '$this->app->bind')) $line = "$line\n$this->bind";

            if ($line != '') fputs($writing, $line);
        }
        fclose($reading);
        fclose($writing);
        rename(public_path() . '/newModule.tmp', $this->file);
    }

}
