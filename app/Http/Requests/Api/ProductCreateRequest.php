<?php

namespace App\Http\Requests\Api;

use App\Rules\PhoneRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProductCreateRequest extends FormRequest
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
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'position' => 'nullable|integer',
            'tag' => 'nullable|string',
            'main_image' => 'nullable|image|max:10000',
            'images.*' => 'nullable|image|max:10000',
            'video' => 'nullable|mimetypes:video/*|max:20000',
        ];
    }
}
