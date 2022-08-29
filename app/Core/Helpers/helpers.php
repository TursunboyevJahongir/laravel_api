<?php

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Picqer\Barcode\BarcodeGeneratorSVG;
use Spatie\Permission\Models\Permission;

if (!function_exists('diffMinutesOnString')) {
    function diffMinutesOnString(Carbon $datetime1, Carbon $datetime2)
    {
        $difference = $datetime1->diff($datetime2);

        return __('sms.minutes_diff', ['minutes' => $difference->i, 'seconds' => $difference->s]);
    }
}
if (!function_exists('subText')) {
    function subText($text, $length = 15, $end = '...')
    {
        return !$text ? : Str::limit($text, $length, $end);
    }
}

if (!function_exists('generateRandomString')) {
    function generateRandomString($length = 4, $big_later = true, $small_later = true, $number = true)
    {
        $characters = ($number ? '0123456789' : "") . ($small_later ? 'abcdefghijklmnopqrstuvwxyz' : "") . ($big_later ? 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' : "");

        $charactersLength = strlen($characters);
        $randomString     = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}

if (!function_exists('moneyFormatter')) {
    function moneyFormatter($number): string
    {
//          show with residues
        [$whole, $decimal] = sscanf($number, '%d.%d');
        $money = number_format($number, 0, ',', ' ');

        return $decimal ? $money . ",$decimal" : $money;

//        without residues
//        return number_format(ceil($number), 0, ',', ' ');

    }

    if (!function_exists('barcodeGenerator')) {
        function barcodeGenerator(string $table, string $column, string $path): array
        {
            $random = random_int(1000000000, 9999999999);
            DB::table($table)->where($column, $random)->doesntExist() ? : barcodeGenerator($table, $column, $path);
            $generator = new BarcodeGeneratorSVG();
            Storage::disk('public')
                ->put("uploads/$path/barcodes/$random.svg", $generator
                    ->getBarcode($random, $generator::TYPE_CODE_128));

            return [$column => $random, $column . "_path" => "/uploads/$path/barcodes/$random.svg"];
        }
    }
}

if (!function_exists('hasPermission')) {
    /**
     * Determine if the model may perform the given permission.
     *
     * @param string|int|Permission $permission
     * @param null $user
     * @param string|null $guardName
     *
     * @return bool
     */
    function hasPermission($permission, $user = null, string $guardName = null): bool
    {
        $user = $user ?? auth()->user();
        if (hasRole(user: $user)) {
            return true;
        }

        return $user->hasPermissionTo($permission, $guardName);
    }
}

if (!function_exists('hasRole')) {
    /**
     * Determine if the model has (one of) the given role(s).
     *
     * @param null $role
     * @param string|null $guard
     * @param null $user
     *
     * @return bool
     */
    function hasRole($role = null, $user = null, string $guard = null): bool
    {
        $role = $role ? [$role, 'superadmin'] : 'superadmin';
        $user = $user ?? auth()->user();

        return $user->hasRole($role, $guard);
    }
}

if (!function_exists('isSystem')) {
    function isSystem(): bool
    {
        return hasPermission("system") || hasRole('superadmin');
    }
}

if (!function_exists('notSystem')) {
    function notSystem(): bool
    {
        return !hasPermission("system") || !hasRole('superadmin');
    }
}

if (!function_exists('mine')) {
    function isMine(int $id): bool
    {
        return auth()->user()->isMine($id);
    }
}

if (!function_exists('core_path')) {
    /**
     * Get the path to the application folder.
     *
     * @param string $path
     *
     * @return string
     */
    function core_path($path = '')
    {
        return app_path('Core/' . $path);
    }
}

if (!function_exists('isEloquentModel')) {
    function isEloquentModel($query): bool
    {
        return isEloquent($query) and $query->getModel() instanceof \Illuminate\Database\Eloquent\Model;
    }
}

if (!function_exists('isEloquent')) {
    function isEloquent($query): bool
    {
        return $query instanceof EloquentBuilder;
    }
}

if (!function_exists('like')) {
    function like(): string
    {
        return \DB::connection()->getDriverName() === 'postgresql' ? 'ilike' : 'like';
    }
}
