<?php

namespace App\Http\Requests\Api\Role;

use Illuminate\Foundation\Http\FormRequest;

class PermissionRequest extends FormRequest
{
    public function rules(): array
    {
        return (new CheckPermissionsRequest())->rules();
    }

    public function authorize(): bool
    {
        return true;
    }
}
