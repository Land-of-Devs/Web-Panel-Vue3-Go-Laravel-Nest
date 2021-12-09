<?php

namespace App\Http\Requests;


class ProductRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title'         => 'required|max:255',
            'description'   => 'nullable|max:3000',
            'price'         => 'required|numeric',
            'image'         => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ];
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
            'title.required'    => 'Product title is missing!',
            'title.max'         => 'Product title is too long!',
            'description.max'   => 'Description is too long!',
            'price.required'    => 'Product price is missing',
            'price.numeric'     => 'Product price is not a numeric value',
            'image.image'       => 'File should be an Image',
            'image.max'         => 'Product image is too big. Maximum allow 2MB',
        ];
    }
}
