<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class TeacherDetailUpdateRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'name' => 'required',
            // 'last_name' => 'required',
            'experience' => 'required|numeric',
            // 'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',

        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errors = $validator->errors();
        throw new ValidationException($validator, response()->json([
            'success' => false,
            'message' => config('constants.API_MSG.VALIDATION_ERROR'),
            'data' => $errors,
        ], 401));
    }
}
