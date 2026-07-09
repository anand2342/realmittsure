<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
class UserActiveInactiveRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'user_id' => 'required',
            'status' => 'required',
            'date' => 'required',
                
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
