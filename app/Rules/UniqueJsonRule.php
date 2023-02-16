<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

use function __;

class UniqueJsonRule implements Rule
{
    /**
     * проверить уникальность json столбца
     */
    public function passes($attribute, $value): bool
    {
        $lang = substr($attribute, -2);

        return DB::table($this->table)->where($this->column . '->' . $lang, '=', $value)
            ->when($this->id, function ($query) {
                $query->where('id', '!=', $this->id);
            })
            ->doesntExist();
    }

    /**
     * Create a new rule instance.
     */
    public function __construct(protected string $table, protected string $column, protected int|null $id = null)
    {
    }

    /**
     * Get the validation error message.
     */
    public function message(): string
    {
        return __('validation.unique', ['attribute' => $this->column]);
    }
}
