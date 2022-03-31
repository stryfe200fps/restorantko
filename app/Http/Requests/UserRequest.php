<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|min:5|max:40',
            'email' => 'email',
            'password' => 'required|min:5|max:20'
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            //
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            //
                'name.min' => 'First name should be 5 or more characters',
               'name.max' => 'First name should be less than 255 characters',
               'name.required' => 'name is required',

                'email.min' => 'First name should be 5 or more characters',
               'email.max' => 'First name should be less than 255 characters',
               'email.email' => 'Please enter a valid email address',

                'password.min' => 'First name should be 5 or more characters',
               'password.max' => 'First name should be less than 255 characters',
               'password.required' => 'password is required',
 
        ];
    }
}
