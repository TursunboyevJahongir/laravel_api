<?php

namespace App\Models;

use App\Core\Traits\CoreModel;
use App\Helpers\TranslatableJson;
use App\Traits\{Author, IsActive};
use Illuminate\Database\Eloquent\{
    Builder,
    Model,
    SoftDeletes,
    Factories\HasFactory,
    Relations\BelongsTo,
    Relations\HasMany,
    Relations\MorphOne
};

class Category extends Model
{
    use HasFactory, SoftDeletes, Author, CoreModel, IsActive;

    public function newQuery(): Builder
    {
        return parent::newQuery()->when(notSystem(), function ($query) {
            $query->whereNull('deleted_at')->active();
        });
    }

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
        return $this->hasMany(Product::class)
            ->when(notSystem(), function ($query) {
                $query->whereNull('deleted_at')->active();
            });
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')
            ->when(notSystem(), function ($query) {
                $query->whereNull('deleted_at')->active();
            });
    }

    public function getAbAttribute(){
        return "ab";
    }

    public function getBcAttribute(){
        return "Bc";
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function getSubDescriptionAttribute(): string|null
    {
        return subText($this->description);
    }
}
