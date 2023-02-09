<?php

namespace App\Observers;

use App\Events\AttachImages;
use App\Events\DestroyFiles;
use App\Events\UpdateFile;
use App\Events\UpdateImage;
use App\Models\Product;
use App\Services\BarcodeService;
use Illuminate\Database\Eloquent\Model;

class ProductObserver
{
    private function checkFile(Model $model)
    {
        if (request()->hasFile('mainImage')) {
            UpdateImage::dispatch(request('mainImage'), $model->mainImage(), $model->getFilePath(), $model::MAIN_IMAGE);
        }
        if (request()->hasFile('video')) {
            UpdateFile::dispatch(request('mainImage'), $model->video(), $model->getFilePath(), $model::VIDEO);
        }
        if (request()->hasFile('images')) {
            AttachImages::dispatch(request('images'), $model->images(), $model->getFilePath(), $model::IMAGES);
        }
    }

    public function creating(Product $product)
    {
        $barcode                 = BarcodeService::generate('products', 'barcode', $product->getFilePath());
        $product['barcode']      = $barcode['barcode'];
        $product['barcode_path'] = $barcode['barcode_path'];
    }

    public function created(Product $product)
    {
        $this->checkFile($product);
    }

    public function updated(Product $product)
    {
        $this->checkFile($product);
    }

    public function deleting(Product $product)
    {
        DestroyFiles::dispatch($product->images?->pluck('id')->toArray());
        DestroyFiles::dispatch($product->mainImage?->id);
        DestroyFiles::dispatch($product->video?->id);
    }
}
