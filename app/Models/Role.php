<?php

namespace App\Models;

use App\Core\Traits\CoreModel;
use App\Helpers\TranslatableJson;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use CoreModel;

    protected       $fillable   = ['title', 'name', 'guard_name'];
    protected array $json       = ['title'];
    protected array $searchable = ['name'];

    protected $casts = [
        'title' => TranslatableJson::class,
    ];
}
