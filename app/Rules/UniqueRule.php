<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

use function __;

class UniqueRule implements Rule
{
    /**
     * Create a new rule instance.
     */
    public function __construct(
        protected string $table,
        protected string $column,
        protected int|null $id = null,
        protected bool $softDeletes = true
    )
    {
    }

    public function passes($attribute, $value): bool
    {
        return DB::table($this->table)
            ->when($this->softDeletes, function ($query) {
                $query->whereNull('deleted_at');
            })
            ->where($this->column, $value)
            ->when($this->id, fn($query) => $query->where('id', '!=', $this->id))
            ->doesntExist();
    }

    public function message()
    {
        return __('validation.unique', ['attribute' => $this->column]);
    }
}
