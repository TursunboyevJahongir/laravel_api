<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

/**
 * Class RoleCreateRequest
 * @package App\Http\Requests
 */
class RoleCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::user()->can('create role');
    }

    /**
     * Prepare the data for validation.
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'name' => Str::slug(trim($this->name), '_'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|unique:roles,name',
            'permissions.*' => 'nullable|exists:permissions,name',
        ];
    }
}
