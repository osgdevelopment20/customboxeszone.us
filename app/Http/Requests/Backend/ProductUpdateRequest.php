<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductUpdateRequest extends FormRequest
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
            'product_category_id' => 'required',
            'product_description' => 'required',
            'meta_title' => 'required',
            'meta_description' => 'required',
            'slug' => 'required',

        ];
    }

    public function messages()
    {
        return
            [
                'product_title.required' => 'Product Title is Required',
                'product_name.required' => 'Product Name is Required',
                'slug.required' => 'Slug is Required',
                'product_category_id.required' => 'Product Category is Required',
                'product_description.required' => 'Product Description is Required',
                'meta_title.required' => 'Meta title is Required',
                'meta_description.required' => 'Meta Description is Required',
            ];
    }
}
