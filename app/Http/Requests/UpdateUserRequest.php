<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$this->user->id,
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'roles' => 'required|array',
            'roles.*' => 'exists:roles,id'
        ];
    }

    public function messages()
    {
        return [
            'roles.required' => 'Please select at least one role',
            'roles.*.exists' => 'Invalid role selected'
        ];
    }
}