<?php

namespace App\Http\Requests\Api;

use App\Rules\PhoneRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CategoryUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'id' => 'required|exists:categories,id',
            'name' => 'nullable|string|unique:categories,name,' . $this->id . ',id',
            'position' => 'nullable|numeric',
            'ico' => 'nullable|image',
            'active' => 'nullable|boolean',
        ];
    }
}
