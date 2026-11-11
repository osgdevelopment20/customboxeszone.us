<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductStoreRequest extends FormRequest
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
            'product_title' => ['required', Rule::unique('products')],
            'product_name' => ['required', Rule::unique('products')],
            'product_category_id.*' => 'required',
            'product_front_image' => 'required|file|mimes:jpg,jpeg,png,gif,webp,avif|max:10240',
            'product_back_image' => 'required|file|mimes:jpg,jpeg,png,gif,webp,avif|max:10240',
            'product_multiple_images' => 'required',
            'product_multiple_images.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,avif|max:10240',
            'client_story.*.image' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,avif|max:10240',
            'product_description' => 'required',
            'meta_title' => 'required',
            'meta_description' => 'required',

        ];
    }

    public function messages()
    {
        return
        [
            'product_title.required' => 'Product Title is Required',
            'product_name.required' => 'Product Name is Required',
            'product_category_id.required' => 'Product Category is Required',
            'product_front_image.required' => 'Product Front Image is Required',
            'product_back_image.required' => 'Product Back Image is Required',
            'product_multiple_images.required' => 'Product Multiple Image is Required',
            'product_description.required' => 'Product Description is Required',
            'meta_title.required' => 'Meta title is Required',
            'meta_description.required' => 'Meta description is Required',
        ];
    }
}
