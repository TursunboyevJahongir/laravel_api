<?php

namespace App\Http\Requests;

use App\Rules\checkActiveRule;
use Illuminate\Foundation\Http\FormRequest;

class ProductUpdateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'category_id'                              => ['filled',
                                                           new checkActiveRule('categories', $this->category_id, 'category')],
            'name'                                     => 'filled|array',
            'name.' . config('app.main_locale')        => 'filled|string',
            'name.*'                                   => 'nullable|string',
            'description'                              => 'nullable|array',
            'description.' . config('app.main_locale') => 'required_with:description|string',
            'description.*'                            => 'nullable|string',
            'position'                                 => 'integer',
            'main_image'                               => 'nullable|image|max:10000',
            'images.*'                                 => 'nullable|image|max:10000',
            'video'                                    => 'nullable|mimetypes:video/*|max:20000',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}