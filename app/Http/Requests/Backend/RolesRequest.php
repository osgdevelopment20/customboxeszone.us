<?php

namespace App\Http\Requests\Backend;

use Illuminate\Foundation\Http\FormRequest;

class RolesRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->can('manage-roles');
    }

    public function rules()
    {
        $rules = [
            'name' => 'required|string|max:255|unique:roles,name',
            'permission' => 'nullable|array',
            'permission.*' => 'exists:permissions,name'
        ];

        if ($this->isMethod('POST') && $this->route('role')) {
            $rules['name'] = 'required|string|max:255|unique:roles,name,' . $this->route('role')->id;
        }

        return $rules;
    }
}