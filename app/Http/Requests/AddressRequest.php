<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
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
            'first_name' => 'required|min:5|max:255',
            'last_name' => 'required|min:5|max:255',
            'phone_number' => 'required|min:5|max:255',
            'address' => 'required',
            'street' => 'required',
            'zip_code' => 'required',
            'country' => 'required'
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
               'first_name.min' => 'First name should be 5 or more characters',
               'first_name.max' => 'First name should be less than 255 characters',
               'first_name.required' => 'First name should be 5 or mor characters',

                'last_name.min' => 'Last name should be 5 or more characters',
               'last_name.max' => 'Last name should be less than 255 characters',
               'last_name.required' => 'Last name should be 5 or mor characters',
                'phone_number.required' => 'Phone number is required',
                'address.required' => 'address is required',
                'street.required' => 'street is required',
                'zip_code.required' => 'zip code is required',
                'country.required' => 'country is required',
        ];
    }
}
