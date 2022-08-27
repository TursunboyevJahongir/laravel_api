<?php

namespace App\Http\Requests\Role;

use App\Rules\PermissionRule;
use Illuminate\Foundation\Http\FormRequest;

class CheckPermissionsRequest extends FormRequest
{
    public function rules(): array
    {
        return ['permission.*' => ['required', new PermissionRule()]];
    }

    public function authorize(): bool
    {
        return true;
    }
}
