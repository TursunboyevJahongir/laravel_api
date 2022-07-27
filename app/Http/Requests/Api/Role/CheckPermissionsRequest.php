<?php

namespace App\Http\Requests\Api\Role;

use App\Rules\PermissionRule;
use Illuminate\Foundation\Http\FormRequest;
use Spatie\Permission\Models\Permission;

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
