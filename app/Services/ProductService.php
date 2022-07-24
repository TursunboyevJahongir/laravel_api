<?php

namespace App\Services;

use App\Contracts\{ProductServiceContract, ProductRepositoryContract};
use App\Core\Services\CoreService;
use App\Events\{AttachImages, DestroyFiles, UpdateFile, UpdateImage};
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

class ProductService extends CoreService implements ProductServiceContract
{
    public function __construct(ProductRepositoryContract $repository)
    {
        parent::__construct($repository);
    }

    public function creating(FormRequest $request)
    {
        $barcode                 = BarcodeService::generate('products', 'sku', $this->repository->model->getFilePath());
        $request['barcode']      = $barcode['barcode'];
        $request['barcode_path'] = $barcode['barcode_path'];
    }

    public function created(Model $model, FormRequest $request): void
    {
        if ($request->hasFile('mainImage')) {
            UpdateImage::dispatch($request['mainImage'], $model->mainImage(), $model->getFilePath(), $model::MAIN_IMAGE);
        }
        if ($request->hasFile('video')) {
            UpdateFile::dispatch($request['video'], $model->video(), $model->getFilePath(), $model::VIDEO);
        }
        if ($request->hasFile('images')) {
            AttachImages::dispatch($request['images'], $model->images(), $model->getFilePath(), $model::IMAGES);
        }
    }

    public function updated(Model $model, FormRequest $request): void
    {
        $tags = explode(',', $id->tag);

        return Product::query()
            ->where('id', '!=', $id->id)
            ->where(function ($query) use ($tags) {
                foreach ($tags as $tag)
                    $query->orWhere('tag', 'LIKE', "%$tag%");
            })
            ->when(!auth('sanctum')->check(), function ($query) {//ro'yhatdan o'tgan va productlarni ko'rishga huquqi bor bo'lganlarga ko'rinadi
                return $query->active();
            })
            ->orderBy('position', 'DESC')
            ->orderBy("title", 'ASC')
            ->paginate($size);
    }


    public function products($size, $orderBy, $sort, $min, $max): array
    {
        $query = Product::query()
            ->when($min, function ($query) use ($min) {
                return $query->where('price', '>=', $min);
            });
        $minimal = $query->min('price');
        $maximal = $query->max('price');
        $query->when($max, function ($query, $max) {
            return $query->where('price', '<=', $max);
        })
            ->orderBy($orderBy, $sort);

        $data['products'] = $query->paginate($size);
        $data['append'] = [
            'min_price' => $minimal,
            'max_price' => $maximal
        ];

        return $data;
    }

    public function deleting(Model $model)
    {
        DestroyFiles::dispatch($model->images->pluck('id')->toArray());
        DestroyFiles::dispatch($model->mainImage->id);
        DestroyFiles::dispatch($model->video->id);
    }
}
