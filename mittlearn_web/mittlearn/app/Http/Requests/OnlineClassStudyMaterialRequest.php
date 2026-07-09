<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;

class OnlineClassStudyMaterialRequest extends FormRequest
{

    public function rules(): array
    {
        $maxFileSize = config('constants.MAX_FILE_SIZE');
        return [
            'file' => "required|file|mimes:jpg,jpeg,png,bmp,gif,svg,doc,docx,xls,xlsx,pdf,mp3,wav,mp4,mov,avi|max:$maxFileSize",
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
