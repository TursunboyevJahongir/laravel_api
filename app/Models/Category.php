<?php

namespace App\Models;

use App\Core\Traits\CoreModel;
use App\Helpers\TranslatableJson;
use App\Traits\Author;
use App\Traits\IsActive;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes, Author, CoreModel, IsActive;

    protected $fillable = [
        'name',
        'description',
        'parent_id',
        'position'];

    protected $casts = [
        'parent_id'   => 'int',
        'name'        => TranslatableJson::class,
        'description' => TranslatableJson::class,
    ];

    protected array $searchable = ['name',
                                   'description',
                                   'author.first_name',//relation with dot .
                                   ['author', ['last_name', 'phone']]];//relation in array

    protected array $json = ['name', 'description'];

    public function ico(): MorphOne
    {
        return $this->morphOne(Resource::class, 'resource');
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function scopeActive($query)
    {
        return $query->whereActive(true);
    }

    public function getSubDescriptionAttribute(): string|null
    {
        return subText($this->description);
    }
}
