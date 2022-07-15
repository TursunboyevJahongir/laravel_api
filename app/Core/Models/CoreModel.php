<?php

namespace App\Core\Models;

use App\Helpers\DateCasts;
use Illuminate\Database\Eloquent\{Builder, Model};
use Illuminate\Support\Str;

abstract class CoreModel extends Model
{
    protected array        $json       = [];
    protected array        $searchable = [];
    protected static array $modelCasts = ['id'         => 'int',
                                          'is_active'  => 'bool',
                                          'position'   => 'int',
                                          'created_at' => DateCasts::class,
                                          'updated_at' => DateCasts::class,
                                          'deleted_at' => DateCasts::class];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->casts = self::$modelCasts + $this->casts;
    }

    /**
     * Get the json attributes for the model.
     * @method getJsonColumns()
     * @return array
     */
    public function getJsonColumns()
    {
        return $this->json;
    }

    /**
     * Get the searchable attributes for the model.
     * @method getSearchable()
     * @return array
     */
    public function getSearchable()
    {
        return $this->searchable;
    }

    public function isJson(string $field): bool
    {
        return in_array($field, $this->getJsonColumns(), true);
    }
}
