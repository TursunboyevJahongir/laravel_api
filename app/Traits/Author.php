<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

trait Author
{
    /**
     * adding author_id
     * @return void
     */
    protected static function bootAuthor(): void
    {
        static::creating(function ($query) {
            $query->author_id = $query->author_id ?? auth()->id();
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
