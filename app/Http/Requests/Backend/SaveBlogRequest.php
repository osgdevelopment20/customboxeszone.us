<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveBlogRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => ['required', Rule::unique('blogs')],
            'author_name' => 'required',
            'blog_ldis' => 'required',
            'blog_image' => 'required|file|mimes:jpg,jpeg,png,gif,webp,avif|max:10240',
            'meta_title' => ['required', 'max:120'],
            'meta_description' => ['required', 'max:160'],
        ];
    }

    public function messages()
    {
        return
            [
                'title.required' => 'Blog Title is Required',
                'author_name.required' => 'Author Name is Required',
                'blog_ldis.required' => 'Blog Content is Required',
                'blog_image.required' => 'Blog thumbnail is Required',
                'meta_title.required' => 'Meta title is Required',
                'meta_description.required' => 'Meta description is Required',
            ];
    }
}
