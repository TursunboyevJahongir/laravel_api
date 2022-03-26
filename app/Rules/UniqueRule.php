<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use function __;

class UniqueRule implements Rule
{
    /**
     * проверить уникальность json столбца
     *
     * @param string $attribute
     * @param mixed $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return DB::table($this->table)
                ->when($this->softDeletes, function ($query) {
                    $query->whereNull('deleted_at');
                })
                ->where($this->column, $value)
                ->when($this->pivotColumn && $this->pivotValue, function ($query) {
                    $query->where($this->pivotColumn, $this->pivotValue);
                })
                ->when($this->id, function ($query) {
                    $query->where('id', '!=', $this->id);
                })
                ->doesntExist();
    }

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(
            protected string $table,
            protected string $column,
            protected string|null $pivotColumn = null,
            protected string|null $pivotValue = null,
            protected int|null $id = null,
            protected bool $softDeletes = true
    ) {
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('validation.unique', ['attribute' => $this->column]);
    }
}
