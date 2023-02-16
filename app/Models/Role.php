<?php

namespace App\Models;

use App\Core\Traits\CoreModel;
use App\Helpers\TranslatableJson;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as SpatieRole;

class Role extends SpatieRole
{
    use CoreModel, HasFactory;

    protected       $fillable   = ['title', 'name', 'guard_name'];
    protected array $searchable = ['name'];

    protected $casts = [
        'title' => TranslatableJson::class,
    ];
}
