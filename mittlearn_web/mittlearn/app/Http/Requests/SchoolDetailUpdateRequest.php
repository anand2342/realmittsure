<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class SchoolDetailUpdateRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            // 'decision_maker' => 'required|string|max:255',
            // 'decision_maker_mobile_no' => 'required|numeric|digits:10',
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
