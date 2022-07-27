<?php

namespace App\Models;

use App\Core\Traits\CoreModel;
use App\Helpers\TranslatableJson;
use App\Traits\Author;
use App\Traits\IsActive;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes, Author, CoreModel, IsActive;

    public const MAIN_IMAGE = 'MAIN_IMAGE';
    public const VIDEO      = 'VIDEO';
    public const IMAGES     = 'IMAGES';

    protected $fillable = [
        'category_id',
        'name',
        'description',
        'position',
        'barcode',
        'barcode_path',
    ];

    protected $casts = [
        'category_id' => 'int',
        'name'        => TranslatableJson::class,
        'description' => TranslatableJson::class,
    ];

    protected $appends = ['sub_description'];

    protected array  $json     = ['name', 'description'];
    protected string $filePath = 'products';

    protected array $searchable = ['name',
                                   'description',
                                   'barcode'];

    public function mainImage(): MorphOne
    {
        return $this->morphOne(Resource::class, 'resource')
            ->where('additional_identifier', self::MAIN_IMAGE)
            ->withDefault([
                              'path_original' => 'images/default/no_image_original.png',
                              'path_1024'     => 'images/default/no_image_1024.png',
                              'path_512'      => 'images/default/no_image_512.png',
                          ]);
    }

    public function video(): MorphOne
    {
        return $this->morphOne(Resource::class, 'resource')
            ->where('additional_identifier', self::VIDEO);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Resource::class, 'resource')
            ->where('additional_identifier', self::IMAGES);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function getSubDescriptionAttribute(): string|null
    {
        return subText($this->description);
    }
}
