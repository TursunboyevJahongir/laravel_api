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
     * Get the json attributes for the model.
     * @method getJsonColumns()
     * @return array
     */
    public function getJsonColumns()
    {
        return $this->json ?? [];
    }

    /**
     * Get the searchable attributes for the model.
     * @method getSearchable()
     * @return array
     */
    public function getSearchable()
    {
        return $this->searchable ?? [];
    }

    public function isJson(string $field): bool
    {
        return in_array($field, $this->getJsonColumns(), true);
    }

    public function getFilePath(): string
    {
        return $this->filePath ?? 'files';
    }

    public function isSearchable(string $field): bool
    {
        return method_exists($this, 'getSearchable') &&
            in_array($field, $this->getSearchable() ?? [], true);
    }

    public function inDates(string $field)
    {
        $dates = Cache::remember($this->getTable(), 60 * 60 * 24, function () {
            $keys = collect($this->getCasts())
                ->filter(function ($value, $key) {
                    if (str_contains($value, 'DateCasts')
                        || str_contains($value, 'datetime')
                        || str_contains($value, 'date')) {
                        return $key;
                    }
                });

            return $keys->keys();
        })->toArray();

        return in_array($field, $dates);
    }
}
