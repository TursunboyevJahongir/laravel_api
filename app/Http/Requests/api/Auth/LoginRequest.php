<?php

namespace App\Http\Requests\api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone' => 'required|regex:/^998[0-9]{9}/',
            'password' => 'required'
        ];
    }
}
