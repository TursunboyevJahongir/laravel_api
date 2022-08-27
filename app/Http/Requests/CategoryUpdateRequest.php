<?php

namespace App\Http\Requests;

use App\Rules\UniqueJsonRule;
use Illuminate\Foundation\Http\FormRequest;

class CategoryUpdateRequest extends FormRequest
{
    public function rules()
    {
        return ['name'                                     => 'filled|array',
                'name.' . config('app.main_locale')        => ['required_with:name',
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
                'description.' . config('app.main_locale') => 'required_with:description|string',
                'description.*'                            => 'nullable|string',
                'position'                                 => 'nullable|numeric',
                'ico'                                      => 'nullable|image',
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
