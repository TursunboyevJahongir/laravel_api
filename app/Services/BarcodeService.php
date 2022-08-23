<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\{DB, Storage};
use Picqer\Barcode\BarcodeGeneratorSVG;

class BarcodeService
{
    /**
     * @param string $table
     * @param string $column
     * @param string $path
     *
     * @return array
     * @throws Exception
     */
    public static function generate(string $table, string $column, string $path): array
    {
        $random = rand(1000000000, 9999999999);
        DB::table($table)->where($column, $random)->doesntExist() ? : self::generate($table, $column, $path);
        $generator = new BarcodeGeneratorSVG();
        Storage::disk('public')
            ->put("uploads/$path/barcodes/$random.svg", $generator
                ->getBarcode($random, $generator::TYPE_CODE_128));

        return [$column => $random, $column . "_path" => "/uploads/$path/barcodes/$random.svg"];
    }
}
