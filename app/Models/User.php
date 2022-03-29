<?php

namespace App\Models;

use App\Core\Models\Authenticatable;
use App\Traits\Author;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, Author, SoftDeletes, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'is_active',
        'phone_confirmed',
        'phone_confirmed_at',
        'author_id',
        'password',
    ];

    protected $hidden = [
        'password'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'];

    protected $searchable = ['first_name',
        'last_name',
        'phone'];

    protected $casts = [
        'created_at'=>'datetime:d.m.Y H:i',
        'updated_at'=>'datetime:d.m.Y H:i',
    ];

    public const USER_AVATAR_RESOURCES = 'USER_AVATAR_RESOURCES';
    public const PATH = 'avatars';

    public function avatar(): MorphOne
    {
        return $this->morphOne(Resource::class, 'resource')
            ->withDefault([
                'path_original' => 'images/default/avatar_original.png',
                'path_1024' => 'images/default/avatar_1024.png',
                'path_512' => 'images/default/avatar_512.png'
            ]);
    }

    public function setPasswordAttribute($password)
    {
        $this->attributes['password'] = Hash::make($password);
    }
}
