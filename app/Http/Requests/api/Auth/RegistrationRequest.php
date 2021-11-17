<?php

namespace App\Http\Requests\api\Auth;

use App\Rules\PhoneRule;
use Illuminate\Foundation\Http\FormRequest;

class RegistrationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone' => [
                'required',
                'unique:users,phone',
                new PhoneRule()
            ],
            'full_name' => 'required|string',
            'password' => 'required|confirmed|min:6'
        ];
    }
}
