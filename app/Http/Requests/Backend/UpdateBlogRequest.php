<?php

namespace App\Http\Requests\Backend;

use App\Models\BlogPost;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateBlogRequest extends FormRequest
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
            'title' => 'required|unique:blogs,title,' . $this->blog->id,
            'meta_title' => 'required',
            'meta_description' => 'required',
            'author_name' => 'required',
            'blog_ldis' => 'required',
            'slug' => 'required',
            'blog_image' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,avif|max:10240',
        ];
    }

    public function messages()
    {
        return
            [
                'title.required' => 'Blog Title is Required',
                'author_name.required' => 'Author Name is Required',
                'meta_title.required' => 'Meta title is Required',
                'meta_description.required' => 'Meta description is Required',
                'blog_ldis.required' => 'Blog Content is Required',
                'slug.required' => 'Slug is Required',
            ];
    }

}
