<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRoleRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:roles,name,'.$this->role->id,
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id'
        ];
    }

    public function messages()
    {
        return [
            'permissions.required' => 'Please select at least one permission',
            'permissions.*.exists' => 'Invalid permission selected'
        ];
    }
}