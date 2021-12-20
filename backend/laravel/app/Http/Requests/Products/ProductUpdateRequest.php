<?php

namespace App\Http\Requests\Products;

use App\Http\Requests\FormRequest;


class ProductUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'name'          => 'sometimes|max:255',
            'description'   => 'sometimes|max:3000',
            'price'         => 'sometimes|numeric',
            'image'         => 'sometimes|image|mimes:png,jpg,jpeg|max:2048',
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
            'required'        => ':attribute is missing!',
            'description.max' => 'Description is too long',
            'name.max'        => 'Name is too long',
            'numeric'         => ':attribute is not a numeric value',
            'image'           => ':attribute should be an Image',
            'image.max'       => 'Image is too big. Maximum allow 2MB',
            'mimes'           => 'Image need to be [png,jpg,jpeg]'
        ];
    }
}
