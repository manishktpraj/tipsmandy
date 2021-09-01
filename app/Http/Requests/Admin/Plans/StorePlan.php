<?php

namespace App\Http\Requests\Admin\Plans;

use Illuminate\Foundation\Http\FormRequest;

class StorePlan extends FormRequest
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
            'name' => 'required|string|max:255|unique:plans',
            'daily_tips_limit' => 'required',
            //'price' => 'required|numeric',
            //'content' => 'required|string',
            'plan_price.0' => 'nullable|numeric',
            'plan_price.1' => 'nullable|numeric',
            'plan_price.2' => 'nullable|numeric',
            'plan_price.3' => 'nullable|numeric',
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
            'name.unique' => 'Sorry, that plan name already taken. Try another?',
            'daily_tips_limit.numeric' => 'The :attribute field is invalid.',
        ];
    }
}
