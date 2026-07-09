<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ChangePasswordRequest extends FormRequest
{
    public function rules()
    {
        return [
            'password' => 'required',  // Current password
            'newpassword' => 'required|min:8|confirmed',  // New password with confirmation
        ];
    }

    public function messages()
    {
        return [
            'password.required' => 'Current password is required.',
            'newpassword.required' => 'New password is required.',
            'newpassword.min' => 'New password must be at least 8 characters long.',
            'newpassword.confirmed' => 'New password confirmation does not match.',
        ];
    }
}
