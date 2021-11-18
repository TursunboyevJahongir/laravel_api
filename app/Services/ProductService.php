<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ProductService
{
    private const FILE_PATH = 'products';

    public function categoryProducts(Category $category, $size, $orderBy, $sort, $min, $max): array
    {
        $query = $category->products()->active();
        $minimal = $query->min('price');//productlarni filterlashdan olishdan maqsad ,undan keyin olinsa min_price max_price o'zgaib ketmasligi uchun
        $maximal = $query->max('price');
        $query->when($min, function ($query) use ($min) {
            return $query->where('price', '>=', $min);
        })->when($max, function ($query, $max) {
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

    public function AdminCategoryProducts(Category $category, $size, $orderBy, $sort, $min, $max): array
    {
        $query = $category->products();
        $minimal = $query->min('price');
        $maximal = $query->max('price');
        $query->when($min, function ($query) use ($min) {
            return $query->where('price', '>=', $min);
        })->when($max, function ($query, $max) {
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

    public function show($id)
    {
        $product = Product::query()->whereId($id)->active()->first();
        if (is_null($product)) {
            throw new \Exception(__('messages.product_not_found'), 404);
        }
        return $product;
    }

    public function similar(Product $id, $size): LengthAwarePaginator
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

    public static function create(array $data)
    {
        $data['slug'] = Str::slug($data['title']);
        $product = Product::create($data);
        if (array_key_exists('main_image', $data)) {
            self::saveResource($data['main_image'], $product->mainImage(), Product::PRODUCT_MAIN_IMAGE_RESOURCES, self::FILE_PATH);
            $product->load('mainImage');
        }
        if (array_key_exists('images', $data)) {
            foreach ($data['images'] as $image)
                self::saveResource($image, $product->images(), Product::PRODUCT_IMAGES_RESOURCES, self::FILE_PATH);
            $product->load('images');
        }
        if (array_key_exists('video', $data)) {
            self::saveResource($data['video'], $product->video(), Product::PRODUCT_VIDEO_RESOURCES, self::FILE_PATH . "/videos");
            $product->load('video');
        }
        return $product;
    }

    public static function update(array $data, Product $product)
    {
        $data['slug'] = Str::slug($data['title']);
        $product->update($data);
        if (array_key_exists('main_image', $data)) {
            if ($product->mainImage()->exists()) {
                $product->mainImage->removeFile();
                $product->mainImage()->delete();
            }
            self::saveResource($data['main_image'], $product->mainImage(), Product::PRODUCT_MAIN_IMAGE_RESOURCES, self::FILE_PATH);
            $product->load('mainImage');
        }
        if (array_key_exists('images', $data)) {
            foreach ($data['images'] as $image)
                self::saveResource($image, $product->images(), Product::PRODUCT_IMAGES_RESOURCES, self::FILE_PATH);
            $product->load('images');
        }
        if (array_key_exists('video', $data)) {
            if ($product->video()->exists()) {
                $product->video->removeFile();
                $product->video()->delete();
            }
            self::saveResource($data['video'], $product->video(), Product::PRODUCT_VIDEO_RESOURCES, self::FILE_PATH . "/videos");
            $product->load('video');
        }
        return $product;
    }

    /**
     * @param UploadedFile $file
     * @param $product
     * @param string $identifier
     * @param string $path
     */
    private static function saveResource(UploadedFile $file, $product, string $identifier, string $path)
    {
        $fileName = md5(time() . $file->getFilename()) . '.' . $file->getClientOriginalExtension();
        $file->storeAs($path, $fileName);

        $product->create([
            'name' => $fileName,
            'type' => $file->Extension(),
            'full_url' => "uploads/$path/$fileName",
            'additional_identifier' => $identifier
        ]);
    }

    public function delete(Product $product): ?bool
    {
        if ($product->mainImage()->exists()) {
            $product->mainImage->removeFile();
            $product->mainImage()->delete();
        }
        foreach ($product->images as $image)
            if ($image->exists()) {
                $image->removeFile();
                $image->delete();
            }
        if ($product->video()->exists()) {
            $product->video->removeFile();
            $product->video()->delete();
        }
        return $product->delete();
    }

    public function search(string $search, $size, $orderBy, $sort, $min, $max): array
    {
        $query = Product::query()
            ->when(!auth('sanctum')->check(), function ($query) {
                return $query->active();
            })
            ->where(function ($query) use ($search) {
                $query->where('title', 'like', "%$search%")
                    ->orWhere('description', 'like', "%$search%")
                    ->orWhere('tag', 'like', "%$search%")
                    ->orWhereHas('category', function ($query) use ($search) {
                        $query->where('title', 'like', "%$search%")->active();
                    });
            });
        $minimal = $query->min('price');
        $maximal = $query->max('price');

        $query->when($min, function ($query) use ($min) {
            return $query->where('price', '>=', $min);
        })
            ->when($max, function ($query, $max) {
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
}
