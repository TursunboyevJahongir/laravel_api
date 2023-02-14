<?php

namespace App\Models;

use App\Core\Traits\CoreModel;
use App\Traits\IsActive;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Helpers\DateCasts;
use App\Traits\Author;
use Illuminate\Database\Eloquent\Factories\{HasFactory};
use Illuminate\Database\Eloquent\Relations\{MorphMany, MorphOne};
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, HasRoles, Author, SoftDeletes, Notifiable, CoreModel, IsActive;

    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'birthday',
        'phone_confirmed',
        'phone_confirmed_at',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public array $searchable = ['first_name',
                                'last_name',
                                'phone'];

    protected $casts = [
        'birthday'           => DateCasts::class . ':d-m-Y',
        'phone_confirmed_at' => DateCasts::class . ':d-m-Y',
        'phone_confirmed'    => 'bool',
    ];

    protected $dates = ['birthday', 'phone_confirmed_at'];

    public function avatar(): MorphOne
    {
        return $this->morphOne(Resource::class, 'resource')
            ->withDefault(['path_original' => 'images/default/avatar_original.png',
                           'path_1024'     => 'images/default/avatar_1024.png',
                           'path_512'      => 'images/default/avatar_512.png']);
    }

    //public function setPasswordAttribute($password)
    //{
    //    $this->attributes['password'] = Hash::make($password);
    //}

    protected function password(): Attribute
    {
        return Attribute::make(set: fn($value) => bcrypt($value),);
    }

    public function token(): MorphMany
    {
        return $this->morphMany(RefreshToken::class, 'user');
    }
}
