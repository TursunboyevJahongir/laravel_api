<?php

namespace App\Observers;

use App\Models\Product;
use App\Services\BarcodeService;

class ProductObserver
{
    public function creating(Product $product)
    {
        $barcode                 = BarcodeService::generate('products', 'barcode', $product->getFilePath());
        $product['barcode']      = $barcode['barcode'];
        $product['barcode_path'] = $barcode['barcode_path'];
    }
}
