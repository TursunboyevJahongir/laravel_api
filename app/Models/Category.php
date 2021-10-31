<?php

namespace App\Models;

use App\Traits\HasTranslatableJson;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * Class Category
 * @package App\Models
 * @property int id
 * @property int parent_id
 * @property int position
 * @property string name
 * @property boolean active
 * @property Resource ico
 */
class Category extends Model
{
    use HasFactory, HasTranslatableJson;

    public const CATEGORY_RESOURCES = 'CATEGORY_RESOURCES';

    protected $fillable = [
        'name',
        'position',
        'active',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];
    private $descendants = [];


    public function ico(): MorphOne
    {
        return $this->morphOne(Resource::class, 'resource');
    }

//    public function products(): HasMany
//    {
//        return $this->hasMany(Product::class, 'category_id', 'id');
//    }

    public function scopeActive($q)
    {
        return $q->where('active', '=', true);
    }
}
