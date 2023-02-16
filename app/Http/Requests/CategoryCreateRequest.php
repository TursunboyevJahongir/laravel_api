<?php

namespace App\Http\Requests;

use App\Rules\checkActiveRule;
use App\Rules\UniqueJsonRule;
use Illuminate\Foundation\Http\FormRequest;

class CategoryCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return ['name'                                             => 'required|array',
                'name.' . config('laravel_api.main_locale')        => ['required',
                                                                       'string',
                                                                       new UniqueJsonRule('categories',
                                                                                          'name')],
                'name.*'                                           => ['nullable',
                                                                       'string',
                                                                       new UniqueJsonRule('categories',
                                                                                          'name')],
                'description'                                      => 'nullable|array',
                'description.' . config('laravel_api.main_locale') => 'required_with:description|string',
                'description.*'                                    => 'nullable|string',
                'position'                                         => 'nullable|numeric',
                'ico'                                              => 'nullable|image',
                'parent_id'                                        => ['nullable',
                                                                       new checkActiveRule('categories', request('parent_id'),
                                                                                           'category')],
        ];
    }
}
