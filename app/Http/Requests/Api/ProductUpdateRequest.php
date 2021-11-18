<?php

namespace App\Http\Requests\Api;

use App\Models\Product;
use App\Rules\PhoneRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ProductUpdateRequest extends FormRequest
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
            'id' => ['required','exists:products,id',function ($attribute, $value, $fail) {
                $product = Product::query()->findOrFail($value);
                if ($product->user_id !== Auth::id()) {
                    $fail(__('messages.not_your_product'));
                }
            }],
            'category_id' => 'nullable|exists:categories,id',
            'name' => 'nullable|string',
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
