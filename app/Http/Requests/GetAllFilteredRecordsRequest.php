<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetAllFilteredRecordsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'list_type'   => 'in:pagination,collection',
            'columns'     => 'array',
            'relations'   => 'array',
            'appends'     => 'array',
            'limit'       => [function ($attribute, $value, $fail) {
                is_numeric($value) || $value === 'all' ? : $fail("$attribute must be numeric or 'all'");
            }],
            'per_page'    => 'integer',
            'status'      => 'boolean',
            'search'      => 'nullable|string',
            'filters'     => 'array',
            'or_filters'  => 'array',
            'not_filters' => 'array',
            'order'    => 'string',
            'sort'       => 'in:desc,asc,DESC,ASC',
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
