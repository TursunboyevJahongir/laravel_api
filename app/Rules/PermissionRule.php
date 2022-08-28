<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionRule implements Rule
{
    private $message;

    /**
     * @param string $attribute
     * @param mixed $value
     *
     * @return bool
     */
    public function passes($attribute, $value): bool
    {
        if (is_numeric($value)) {
            $permission = Permission::firstById($value);
        } elseif (is_string($value)) {
            $permission = Permission::firstByName($value);
        } else {
            $this->message = __('messages.error_type');
        }
        if (!$permission) {
            $this->message = __('messages.not_found');
        }
        if (!hasRole() && !hasPermission($permission)) {
            $this->message = __('messages.not_access');
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
