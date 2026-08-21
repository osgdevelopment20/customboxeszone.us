<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class PermissionsRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('manage-permissions');
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255|unique:permissions,name',
        ];

        if ($this->isMethod('POST') && $this->route('permission')) {
            $rules['name'] = 'required|string|max:255|unique:permissions,name,' . $this->route('permission')->id;
        }

        return $rules;
    }
}