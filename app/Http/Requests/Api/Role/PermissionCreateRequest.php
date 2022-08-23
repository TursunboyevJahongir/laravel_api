<?php

namespace App\Http\Requests\Api\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class PermissionCreateRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge(['name' => Str::kebab(trim($this->name))]);
    }

    public function rules(): array
    {
        return ['name'       => 'required|string|unique:permissions,name',
                'guard_name' => 'string'];
    }

    public function authorize(): bool
    {
        return true;
    }
}
