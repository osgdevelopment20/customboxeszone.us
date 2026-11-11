<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class ProductCategoryUpdateRequest extends FormRequest
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

            'title' => 'required',
            'name' => 'required',
            'category_img' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,avif|max:10240',
            'homepage_img' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,avif|max:10240',
        ];
    }

    public function messages()
    {
        return [

            'title.required' => 'Category title is required ',
            'name.required' => 'Category name is required ',

        ];
    }
}
