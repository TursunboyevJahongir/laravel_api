<?php

namespace App\Http\Requests\Api\Role;

use App\Rules\PermissionRule;
use Illuminate\Foundation\Http\FormRequest;

class SyncPermissionsRequest extends FormRequest
{
    public function rules(): array
    {
        return ['permissions'   => 'required|array',
                'permissions.*' => ['required', new PermissionRule()]];
    }

    public function authorize(): bool
    {
        return true;
    }
}
