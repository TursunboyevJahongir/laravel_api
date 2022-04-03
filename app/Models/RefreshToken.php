<?php

namespace App\Models;

use App\Core\Models\CoreModel;
use App\Helpers\RefreshTokenGenerator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class RefreshToken extends CoreModel
{
    use HasFactory;

    protected $fillable = [
        'token',
        'refresh_token',
        'expired_at',
        'refresh_expired_at',
        'user'
    ];

    protected $casts = [
//        'token' => 'encrypted',
        'expired_at' => 'datetime:Y.m.d H:i:s',
        'refresh_expired_at' => 'datetime:Y.m.d H:i:s',
    ];
    protected $with = ['user'];

    /**
     * to set creator_id
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($query) {
            $query->refresh_token = RefreshTokenGenerator::tokenGenerate();
            $query->expired_at = now()->addMinutes(config('sanctum.expiration'));
            $query->refresh_expired_at = now()->addMinutes(config('sanctum.refresh_expiration'));
        });
    }

    protected $hidden = ['user_id', 'user_type', 'created_at', 'updated_at'];

    public function getRefreshTokenAttribute(): string
    {
        return encrypt($this->attributes['refresh_token']);
    }

    /**
     * @return MorphTo
     */
    public function user(): MorphTo
    {
        return $this->morphTo();
    }
}
