<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

/**
 * Class Product
 * @package App\Models
 * @property int id
 * @property int $user_id creator
 * @property int category_id
 * @property string name
 * @property string description
 * @property string tag
 * @property int price
 * @property int position
 * @property boolean active
 * @property Resource mainImage
 * @property Resource video
 * @property Resource images
 * @property Category category
 * @property User user
 */
class Product extends Model
{
    use HasFactory, SoftDeletes;

    public const PRODUCT_MAIN_IMAGE_RESOURCES = 'PRODUCT_MAIN_IMAGE_RESOURCES';
    public const PRODUCT_VIDEO_RESOURCES = 'PRODUCT_VIDEO_RESOURCES';
    public const PRODUCT_IMAGES_RESOURCES = 'PRODUCT_IMAGES_RESOURCES';

    protected $fillable = [
        'user_id',
        'category_id',
        'name',
        'description',
        'price',
        'position',
        'tag',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
    private $descendants = [];


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
        return $q->where('active', '=', true);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getSubDescriptionAttribute(): string
    {
        return $this->description ? Str::limit($this->description, 15, '...') : "";
    }

    public function moneyFormatter($number): string
    {

//          show with residues
        list($whole, $decimal) = sscanf($number, '%d.%d');
        $money = number_format($number, 0, ',', ' ');
        return $decimal ? $money . ",$decimal" : $money;

//        without residues
//        return number_format(ceil($number), 0, ',', ' ');

    }
}
