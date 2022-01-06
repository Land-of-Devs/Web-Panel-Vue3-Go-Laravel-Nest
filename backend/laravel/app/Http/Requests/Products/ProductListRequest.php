<?php

namespace App\Http\Requests\Products;

use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rule;


class ProductListRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'page'         => 'sometimes|min:1',
            'status'       => ['sometimes', Rule::in(array_values(config('enums.item_status')))]
        ];
        return $rules;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * @return array
     * Custom validation message
     */
    public function messages()
    {
        return [
            'required'      => ':attribute is missing!',
            'in'            => ':attribute needs to be one of these: (:values)!'
        ];
    }
}
