<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
class TeacherAddEditRequest extends FormRequest
{

    public function rules(): array
    {

        $id = $this->id ?? null ;
        return [
            'name' => 'required',
                // 'last_name' => 'required',
                // 'gender' => 'required',
                'mobile_no' => "required|numeric|unique:users,mobile_no,{$id}",
                'email' => "required|email|unique:users,email,{$id}",
                // 'address' => 'required',
                // 'city' => 'required',
                // 'state' => 'required',
                // 'country' => 'required',
                // 'qualification' => 'required',
                // 'experience' => 'required',
                // 'age' => 'required|numeric',
                'subject' => 'required',
                'class' => 'required',
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
