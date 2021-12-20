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
            'name'          => 'required|max:255',
            'description'   => 'required|max:3000',
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
            'name.required'         => 'Name is missing!',
            'name.max'              => 'Name is too long',
            'description.required'  => 'Description is missing!',
            'description.max'       => 'Description is too long!',
            'price.required'        => 'Price is missing',
            'price.numeric'         => 'Price is not a numeric value',
            'image.required'        => 'Image is missing',
            'image.image'           => 'File should be an Image',
            'image.max'             => 'Image is too big. Maximum allow 2MB',
        ];
    }
}
