<?php

namespace App\Http\Requests\Products;

use App\Http\Requests\FormRequest;


class ProductCreateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name'          => 'required|string|max:255',
            'description'   => 'required|string|max:3000',
            'price'         => 'required|numeric',
            'image'         => 'required|image|mimes:png,jpg,jpeg|max:2048',
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
            'max'           => ':attribute is too long',
            'numeric'       => ':attribute is not a numeric value',
            'image'         => ':attribute should be an Image',
            'image.max'     => ':attribute is too big. Maximum allow :max',
            'string'        => ':attribute is not a string value'
        ];
    }
}
