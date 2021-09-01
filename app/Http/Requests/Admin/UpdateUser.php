<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUser extends FormRequest
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$this->id,
            'gender' => 'nullable|string|in:1,2',
            'phone_no' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|unique:users,phone_no,'.$this->id,
            'address' => 'nullable|string',
            'profile_image' => 'nullable|image|mimes:jpeg,jpg,png',
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
            'email.unique' => 'Sorry, that email already taken. Try another?',
            'phone_no.unique' => 'Sorry, that phone no already taken. Try another?',
        ];
    }
}
