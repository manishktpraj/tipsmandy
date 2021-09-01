<?php

namespace App\Http\Requests\Admin\Tips;

use Illuminate\Foundation\Http\FormRequest;

class StoreTip extends FormRequest
{
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
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'segment' => 'required|string',
            'stock_name' => 'required|string|max:255',
            //'price' => 'required|numeric',
            //'buy_range' => 'required|numeric',
            //'buy_range' => 'required|numeric'
        ];
    }


    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'price.numeric' => 'The :attribute field is invalid.',
            'stock_name.unique' => 'Sorry, that plan name already taken. Try another?',
        ];
    }
}
