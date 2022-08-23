<?php

namespace App\Http\Requests\Api\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class PermissionUpdateRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge(['name' => Str::kebab($this->name)]);
    }

    public function rules(): array
    {
        return ['name'       => 'required|string|unique:permissions,name,' . $this->route('permission')->id,
                'guard_name' => 'string',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
