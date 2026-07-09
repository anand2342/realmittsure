<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'role_name' => 'required|unique:roles,role_name,' . $this->role,
            'description' => 'nullable|string',
        ];
    }

}
