<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class RoleUpdateRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge(['name' => Str::snake(trim($this->name))]);
    }

    public function rules(): array
    {
        return ['title'                              => 'filled|array',
                'title.' . config('app.main_locale') => 'required_with:title|string',
                'title.*'                            => 'nullable|string',
                'name'                               => 'required|string|unique:roles,name,' . $this->route('role')->id,
                'guard_name'                         => 'string',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
