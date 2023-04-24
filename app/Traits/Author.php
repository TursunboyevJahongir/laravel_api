<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\{BelongsTo};
use App\Models\User;

trait Author
{
    protected static function bootAuthor(): void
    {
        static::creating(function ($query) {
            $query->author_id = $query->author_id ?? auth()->id();
        });
    }

    public function initializeAuthor(): void
    {
        $this->fillable[]         = 'author_id';
        $this->casts['author_id'] = 'int';
    }

    public function isMine($id): bool
    {
        return $id === auth()->id();
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }
}
