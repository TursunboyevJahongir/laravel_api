<?php

namespace App\Core\Traits;

use App\Helpers\DateCasts;
use Illuminate\Support\Facades\Cache;

trait CoreModel
{
    public function initializeCoreModel(): void
    {
        $this->casts['is_active']  = 'bool';
        $this->casts['position']   = 'int';
        $this->casts['created_at'] = DateCasts::class;
        $this->casts['updated_at'] = DateCasts::class;
        $this->casts['deleted_at'] = DateCasts::class;
    }

    /**
     * Get the Translatable json attributes for the model.
     * @method getTranslatableColumns()
     */
    public function getTranslatableColumns(): array
    {
        return Cache::remember($this->getModel()->getTable() . 'getTranslatableColumns', 86400,
            function () {//60 * 60 * 24=day
                $keys = collect($this->getModel()->getCasts())
                    ->filter(function ($value, $key) {
                        if (str_contains($value, 'TranslatableJson')) {
                            return $key;
                        }
                    });

                return $keys->keys()->toArray();
            });
    }

    /**
     * Get the searchable attributes for the model.
     * @method getSearchable()
     */
    public function getSearchable()
    {
        return $this->searchable ?? [];
    }

    public function getFilePath(): string
    {
        return $this->filePath ?? 'files';
    }

    public function inFillable(string $field): bool
    {
        return in_array($field, $this->model->getFillable(), true);
    }

    /**
     * Append attributes to query when building a query.
     *
     * @param array|string $attributes
     */
    public function appends($attributes = [])
    {
        $attributes = request(config('laravel_api.request.appends', 'appends'), $attributes);

        if (!is_array($attributes) && !is_string($attributes)) {
            throw new \Exception('appends must be an array or a string');
        }
        if (is_string($attributes)) {
            $attributes = explode(';', $attributes);
        }

        return $this->append($attributes);
    }
}
