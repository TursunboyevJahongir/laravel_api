<?php

namespace App\Core\Traits;

use App\Helpers\DateCasts;

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
}
