<?php

namespace Tests\Unit;

use App\Services\BarcodeService;
use Tests\TestCase;

class BarcodeGenerateTest extends TestCase
{
    public function testGenerate()
    {
        $barcode = BarcodeService::generate('products', 'barcode', 'products');
        $this->assertIsArray($barcode);
        $this->assertArrayHasKey('barcode', $barcode);
        $this->assertArrayHasKey('barcode_path', $barcode);
        \Storage::disk('public')->assertExists(data_get($barcode, 'barcode_path'));
        \Storage::disk('public')->delete(data_get($barcode, 'barcode_path'));
    }
}
