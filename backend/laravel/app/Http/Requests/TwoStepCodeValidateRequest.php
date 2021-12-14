<?php

namespace App\Http\Requests;


class TwoStepCodeValidateRequest extends FormRequest
{
    public function rules()
    {
        return [
            'uuid' => 'required|uuid',
            'code' => 'required|numeric',
        ];
    }

    public function authorize()
    {
        return true;
    }

    public function messages()
    {
        return [
            'uuid.required'    => 'UUID is required',
            'uuid.uuid'        => 'UUID is not valid',
            'code.required'    => 'Verification code is required',
            'code.numeric'     => 'Product price is not a numeric value',
        ];
    }
}
