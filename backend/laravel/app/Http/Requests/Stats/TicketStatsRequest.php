<?php 

namespace App\Http\Requests\Stats;

use App\Http\Requests\FormRequest;


class TicketStatsRequest extends FormRequest {
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $rules = [
            'from_date' => 'required|date',
            'to_date'   => 'required|date'
        ];
        return $rules;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize() {
        return true;
    }

    /**
     * @return array
     * Custom validation message
     */
    public function messages() {
        return [
            'required' => ':attribute is missing!',
            'date'     => ':attribute is not a valid date',
            'string'   => ':attribute is not a string value'
        ];
    }
}

