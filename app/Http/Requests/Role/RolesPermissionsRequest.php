<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class  RolesPermissionsRequest extends FormRequest
{
    public function rules(): array
    {
        return ["roles"   => "required|array",
                "roles.*" => "required|distinct|exists:roles,id",
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
