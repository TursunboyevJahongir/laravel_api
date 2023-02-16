<?php

namespace App\Models;

use App\Helpers\RefreshTokenGenerator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class RefreshToken extends Model
{
    protected $fillable = [
        'token',
        'refresh_token',
        'expired_at',
        'refresh_expired_at',
        'user',
    ];

    protected $casts = [
        'expired_at'         => 'datetime:Y.m.d H:i:s',
        'refresh_expired_at' => 'datetime:Y.m.d H:i:s',
    ];
    protected $with  = ['user'];

    /**
     * to set creator_id
     */
    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($query) {
            $query->refresh_token      = RefreshTokenGenerator::tokenGenerate();
            $query->expired_at         = now()->addMinutes(config('sanctum.expiration'));
            $query->refresh_expired_at = now()->addMinutes(config('sanctum.refresh_expiration'));
        });
    }

    protected $hidden = ['user_id', 'user_type', 'created_at', 'updated_at'];

    public function getRefreshTokenAttribute(): string
    {
        return encrypt($this->attributes['refresh_token']);
    }

    public function user(): MorphTo
    {
        return $this->morphTo();
    }
}
