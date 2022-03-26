<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use function __;

class ExistsRule implements Rule
{
    /**
     * проверить уникальность json столбца
     *
     * @param string $attribute
     * @param mixed $value
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        return DB::table($this->table)
                ->when($this->softDeletes, function ($query) {
                    $query->whereNull('deleted_at');
                })
                ->when($this->pivotColumn && $this->pivotValue, function ($query) {
                    $query->where($this->pivotColumn, $this->pivotValue);
                })
                ->where($this->column, $value)
                ->exists();
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
            protected bool $softDeletes = true
    ) {
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(): string
    {
        return __('validation.exists', ['attribute' => $this->column]);
    }
}
