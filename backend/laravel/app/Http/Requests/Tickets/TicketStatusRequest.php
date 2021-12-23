<?php

namespace App\Http\Requests\Tickets;

use App\Http\Requests\FormRequest;
use Illuminate\Validation\Rule;

class TicketStatusRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'tickets'           => 'required|array',
            'tickets.*.id'      => 'required|numeric',
            'tickets.*.status'  => ['required',Rule::in(array_values(config('enums.item_status')))],
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
            'array'         => ':attribute needs to be an array',
            'numeric'       => ':attribute is missing!',
            'in'            => ':attribute needs to be one of these: (:values)!'
        ];
    }
}
