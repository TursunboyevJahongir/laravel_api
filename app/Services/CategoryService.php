<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Resource;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;

class CategoryService
{
    public function __construct(private Resource $ico)
    {
    }

    private const ICO_PATH = 'category';

    public function index($orderBy, $sort): Collection|array
    {
        return Category::active()->orderBy($orderBy, $sort)->get();
    }

    public static function create(array $data)
    {
        $category = Category::create($data);
        if (array_key_exists('ico', $data)) {
            self::saveResource($data['ico'], $category, Category::CATEGORY_RESOURCES);
        }
        $category->load('ico');
        return $category;
    }

    public static function update(array $data)
    {
        $category = Category::query()->find($data['id']);
        $category->update($data);
        if (array_key_exists('ico', $data)) {
            if ($category->ico()->exists()) {
                $category->ico->removeFile();
                $category->ico()->delete();
            }
            self::saveResource($data['ico'], $category, Category::CATEGORY_RESOURCES);
        }
        return $category->load('ico');
    }

    /**
     * @param UploadedFile $file
     * @param Category $category
     * @param string $identifier
     */
    private static function saveResource(UploadedFile $file, Category $category, string $identifier): void
    {
        $fileName = md5(time() . $file->getFilename()) . '.' . $file->getClientOriginalExtension();
        $file->storeAs(self::ICO_PATH, $fileName);

        $category->ico()->create([
            'name' => $fileName,
            'type' => $file->getExtension(),
            'full_url' => 'uploads/' . self::ICO_PATH . '/' . $fileName,
            'additional_identifier' => $identifier
        ]);
    }

    public function delete(Category $category): ?bool
    {
        return $category->delete();
    }
}
