<?php

namespace App\Http\Requests;

use App\Rules\checkActiveRule;
use Illuminate\Foundation\Http\FormRequest;

class ProductCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'category_id'                                      => ['required',
                                                                   new checkActiveRule('categories', request('category_id'), 'category')],
            'name'                                             => 'required|array',
            'name.' . config('laravel_api.main_locale')        => 'required|string',
            'name.*'                                           => 'nullable|string',
            'description'                                      => 'nullable|array',
            'description.' . config('laravel_api.main_locale') => 'required_with:description|string',
            'description.*'                                    => 'nullable|string',
            'position'                                         => 'integer',
            'main_image'                                       => 'nullable|image|max:10000',
            'images.*'                                         => 'nullable|image|max:10000',
            'video'                                            => 'nullable|mimetypes:video/*|max:20000',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
