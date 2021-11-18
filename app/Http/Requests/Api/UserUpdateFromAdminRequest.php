<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserUpdateFromAdminRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::user()->can('update user');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => [
                'required',
                'exists:users',
                function ($attribute, $value, $fail) {
                    if ($value === Auth::id() || $value === 1) {//o'zini va asosiy adminni o'zgartirolmaydi,o'zi uchun updateprofile bor
                        $fail(__('messages.fail'), 403);
                    }
                }
            ],
            'full_name' => 'nullable|string',
            'phone' => ['required', 'unique:users,phone,' . $this->id . 'id'],
            'password' => 'nullable|min:6',
            'roles.*' => ['nullable', 'exists:roles,name'],
        ];
    }
}
