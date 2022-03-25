<?php

namespace App\Helpers;

use Illuminate\Support\Facades\DB;

class ExistsCheck
{
    public static function check(string $table, string $column, mixed $value, bool $softDeletes = true)
    {
        return DB::table($table)
                ->when($softDeletes, function ($query) {
                    $query->whereNull('deleted_at');
                })
                ->where($column, $value)
                ->exists();
    }
}
