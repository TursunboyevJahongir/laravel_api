<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Category
 * @package App\Models
 * @property int id
 * @property int parent_id
 * @property int position
 * @property string title
 * @property boolean active
 * @property Resource ico
 */
class Category extends Model
{
    use HasFactory, SoftDeletes;

    public const CATEGORY_RESOURCES = 'CATEGORY_RESOURCES';

    protected $fillable = [
        'title',
        'slug',
        'position',
        'active',
    ];

    protected $casts = [
        'active' => 'bool',
    ];
    private $descendants = [];


    public function ico(): MorphOne
    {
        return $this->morphOne(Resource::class, 'resource');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }

    public function scopeActive($q)
    {
        return $q->where('active', '=', true);
    }
}
