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
            'name' => 'required|max:40',
            'email' => 'email|unique:users',
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
               'name.max' => 'First name should be less than 255 characters',
               'name.required' => 'name is required',

               'email.max' => 'First name should be less than 255 characters',
               'email.email' => 'Please enter a valid email address',
               'email.unique' => 'This email is already taken',

                'password.min' => 'First name should be 5 or more characters',
               'password.max' => 'First name should be less than 255 characters',
               'password.required' => 'password is required',
 
        ];
    }
}
