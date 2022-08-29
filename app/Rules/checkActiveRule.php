<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;

class checkActiveRule implements Rule
{
    public function __construct(
        protected string $table,
        protected int|null $id,
        protected string $attribute,
        protected string $column = 'id',
    ) {
    }

    private $message;

    /**
     * @param string $attribute
     * @param mixed $value
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        $user = DB::table($this->table)->where($this->column, $value)->first();
        if (!$user) {
            $this->message = __('messages.attribute_not_found', ['attribute' => __('attributes.' . $this->attribute)]);

            return false;
        }
        if ($user->is_active === false || $user->deleted_at) {
            $this->message = __('messages.inactive', ['attribute' => __('attributes.' . $this->attribute)]);

            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function message(): string
    {
        return $this->message;
    }
}
