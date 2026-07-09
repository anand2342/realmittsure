<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PermissionRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $permissionId = $this->route('permission');

        return [
            'slug' => 'required|unique:permissions,slug,' . $permissionId,
            'category' => 'required',
            'permission_type' => 'required|in:route,menu',
            'accessable_for' => 'required|in:web,app',
            'title' => 'required|string',
        ];
    }
}
