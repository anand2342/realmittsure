<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class OnlineClassAddRequest extends FormRequest
{

    public function rules(): array
    {
        $role = $this->role ?? null;
        return [
            'title' => 'required|string|max:255',
            'class_date' => 'required|date',
            'class_id' => 'required|integer',
            'subject_id' => 'required|integer',
            'instructor_id' => $role === 'school_teacher' ? 'nullable' : 'required|integer',
            'start_time' => 'required',
            'end_time' => 'required|after:start_time',
            'join_link' => 'required|url',
            'agenda' => 'required|string|max:200',
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
