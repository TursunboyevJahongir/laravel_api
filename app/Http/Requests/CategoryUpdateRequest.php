<?php

namespace App\Http\Requests;

use App\Rules\checkActiveRule;
use App\Rules\UniqueJsonRule;
use Illuminate\Foundation\Http\FormRequest;

class CategoryUpdateRequest extends FormRequest
{
    public function rules()
    {
        return ['name'                                     => 'filled|array',
                'name.' . config('laravel_api.main_locale')        => ['required_with:name',
                                                               'string',
                                                               new UniqueJsonRule('categories',
                                                                                  'name', $this->route()
                                                                                      ->originalParameter('category'))],
                'name.*'                                   => ['nullable',
                                                               'string',
                                                               new UniqueJsonRule('categories',
                                                                                  'name', $this->route()
                                                                                      ->originalParameter('category'))],
                'description'                              => 'nullable|array',
                'description.' . config('laravel_api.main_locale') => 'required_with:description|string',
                'description.*'                            => 'nullable|string',
                'position'                                 => 'nullable|numeric',
                'ico'                                      => 'nullable|image',
                'is_active'                                => 'filled|bool',
                'parent_id'                                => ['nullable',
                                                               new checkActiveRule('categories', $this->parent_id, 'category')],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }
}
