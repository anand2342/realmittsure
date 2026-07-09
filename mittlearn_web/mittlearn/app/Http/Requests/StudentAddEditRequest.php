<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class StudentAddEditRequest extends FormRequest
{

    public function rules(): array
    {

        $id = $this->id ?? null;
        return [
            'name' => 'required',
            // 'admission_no' => 'required',
            'parent_mobile_no' => "required|numeric|unique:users,mobile_no,{$id},id|regex:/^[0-9]{10,15}$/",
            'email' => "nullable|unique:users,email,{$id}",
            'class' => 'required',
            // 'section' => 'required',
            // 'admission_date' => 'required|date',
            // 'dob' => 'required|date',
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
