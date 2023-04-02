<?php

namespace App\Models;

use App\Core\Traits\CoreModel;
use App\Traits\Author;
use App\Traits\IsActive;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsToMany, MorphOne};
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes, Author, CoreModel, IsActive;

    protected $fillable = [
        'name',
        'position',
    ];

    protected array $searchable = ['name', 'author.first_name'];

    public function image(): MorphOne
    {
        return $this->morphOne(Resource::class, 'resource')
            ->withDefault([
                              'path_original' => 'images/default/no_image_original.png',
                              'path_1024'     => 'images/default/no_image_1024.png',
                              'path_512'      => 'images/default/no_image_512.png',
                          ]);
    }

    public function writer(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'book_writer' , 'book_id','writer_id');
    }
}
