<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest
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

           'email' => 'required',
           'product_name' => 'required'

        ];
    }

    public function messages()
    {
        return [

            'email.required' => 'Enter Your Email',
            'product_name.required' => 'Select the product',

        ];

    }
}
