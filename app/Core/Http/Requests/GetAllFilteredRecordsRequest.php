<?php

namespace App\Core\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetAllFilteredRecordsRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return ['list_type'    => 'in:pagination,collection',
                'columns'      => 'array',
                'relations'    => 'array',
                'appends'      => 'array',
                'limit'        => [function ($attribute, $value, $fail) {
                    is_numeric($value) || $value === 'all' ? : $fail("$attribute must be numeric or 'all'");
                }],
                'per_page'     => 'integer',
                'is_active'    => 'boolean',
                'search'       => 'nullable|string',
                'search_by'    => 'nullable|array',
                'conditions'      => 'array',
                'not_conditions'  => 'array',
                'or_conditions'   => 'array',
                'pluck'        => !is_array($this->get('pluck')) ? 'string' : "array|required_array_keys:column",
                'only_deleted' => ['bool',
                                   function ($attribute, $value, $fail) {
                                       if (!hasPermission('system')) {
                                           $fail(__('messages.you_havnt_permission'));
                                       }
                                   }],
                'order'        => 'string',
                'sort'         => 'in:desc,asc',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
