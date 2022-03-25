<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use function __;

class UniqueJsonRule implements Rule
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
        $lang = substr($attribute, -2);

        return DB::table($this->table)->where($this->column . '->' . $lang, '=', $value)
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
    public function __construct(protected string $table, protected string $column, protected int|null $id = null)
    {
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
