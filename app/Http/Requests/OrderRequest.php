<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'invoice_no' => 'required|min:5|max:255|unique:orders',
            'orders' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'email'
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
            'orders.required' => 'You need to have atleast 1 order',
            'invoice_no.unique' => 'Please refresh the page to generate new Invoice Number',
            'first_name.required' => 'first name is required',
            'last_name.required' => 'last name is required',
            'email.email' => 'please enter a valid email address'
        ];
    }
}
