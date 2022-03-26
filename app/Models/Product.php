<?php

namespace App\Models;

use App\Core\Models\CoreModel;
use App\Helpers\TranslatableJson;
use App\Traits\Author;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Product extends CoreModel
{
    use HasFactory, SoftDeletes, Author;

    public const PRODUCT_MAIN_IMAGE_RESOURCES = 'PRODUCT_MAIN_IMAGE_RESOURCES';
    public const PRODUCT_VIDEO_RESOURCES = 'PRODUCT_VIDEO_RESOURCES';
    public const PRODUCT_IMAGES_RESOURCES = 'PRODUCT_IMAGES_RESOURCES';

    protected $fillable = [
        'author_id',
        'category_id',
        'name',
        'description',
        'price',
        'position',
        'tag',
        'is_active',
        'barcode',
        'barcode_path'
    ];

    protected $casts = [
        'name' => TranslatableJson::class,
        'description' => TranslatableJson::class,
    ];

    public function mainImage(): MorphOne
    {
        return $this->morphOne(Resource::class, 'resource')->where('additional_identifier', self::PRODUCT_MAIN_IMAGE_RESOURCES);
    }

    public function video(): MorphOne
    {
        return $this->morphOne(Resource::class, 'resource')->where('additional_identifier', self::PRODUCT_VIDEO_RESOURCES);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Resource::class, 'resource')->where('additional_identifier', self::PRODUCT_IMAGES_RESOURCES);
    }

    public function scopeActive($q)
    {
        return $q->whereActive('=', true);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function getSubDescriptionAttribute(): string|null
    {
        return subText($this->description);
    }
}
