<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait IsActive
{
    public function initializeIsActive(): void
    {
        $this->fillable[]         = 'is_active';
        $this->casts['is_active'] = 'bool';
    }


    public function scopeActive(Builder $query)
    {
        return $query->whereIsActive(true);
    }

}
