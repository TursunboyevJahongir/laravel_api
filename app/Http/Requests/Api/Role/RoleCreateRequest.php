<?php

namespace App\Http\Requests\Api\Role;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class RoleCreateRequest extends FormRequest
{
    protected function prepareForValidation()
    {
        $this->merge(['name' => Str::snake(trim($this->name))]);
    }

    public function rules(): array
    {
        return ['title'                              => 'required|array',
                'title.' . config('app.main_locale') => 'required|string',
                'title.*'                            => 'nullable|string',
                'name'                               => 'required|string|unique:roles,name',
                'guard_name'                         => 'string'];
    }

    public function authorize(): bool
    {
        return true;
    }
}
