<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class PopularUpdateProduct extends FormRequest
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
            'product_title' => 'required',
            'product_name' => 'required',
            'category_id' => 'required',
            

        ];
    }

    public function messages()
    {
        return
            [
                'product_title.required' => 'Product Title is Required',
                'product_name.required' => 'Product Name is Required',
                'category_id.required' => 'Product Category is Required',
               
            ];
    }
}
