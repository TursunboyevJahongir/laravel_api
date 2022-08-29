<?php

namespace App\Http\Requests;

use App\Rules\checkActiveRule;
use App\Rules\UniqueJsonRule;
use Illuminate\Foundation\Http\FormRequest;

class CategoryCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return ['name'                                     => 'required|array',
                'name.' . config('app.main_locale')        => ['required',
                                                               'string',
                                                               new UniqueJsonRule('categories',
                                                                                  'name')],
                'name.*'                                   => ['nullable',
                                                               'string',
                                                               new UniqueJsonRule('categories',
                                                                                  'name')],
                'description'                              => 'nullable|array',
                'description.' . config('app.main_locale') => 'required_with:description|string',
                'description.*'                            => 'nullable|string',
                'position'                                 => 'nullable|numeric',
                'ico'                                      => 'nullable|image',
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
