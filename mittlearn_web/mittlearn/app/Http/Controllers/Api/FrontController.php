<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\ContactEnquiriesRequest;
use App\Models\Category;
use App\Models\ContactInquiry;
use App\Models\Course;
use App\Models\NotificationAlert;
use Exception;
use Illuminate\Support\Facades\Auth;

class FrontController extends BaseController
{
    public function getCourses(Request $request)
    {
        try {
            $request->validate([
                'category_id' => 'required',
            ]);
            if ($request->category_id == '1') {
                $courses = Course::where('category_id', 1)->where('is_active', 1)->get();
                return $this->sendSuccess(compact('courses'), config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            } elseif ($request->category_id == '2') {
                $courses = Course::where('category_id', 2)->where('is_active', 1)->get();
                return $this->sendSuccess(compact('courses'), config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            } else {
                return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'),  406);
            }
        } catch (ValidationException $e) {
            return $this->sendError(config('constants.API_MSG.VALIDATION_ERROR'), $e->errors(), 422);
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }
    public function getAcademicCourses(Request $request)
    {
        try {
            $request->validate([
                'board_id' => 'required',
                'medium_id' => 'required',
                'book_series_id' => 'required',
                'class_id' => 'required',
            ]);
            $courses = Course::where('category_id', 1)
                ->whereHas('metadataValues', function ($query) use ($request) {
                    $query->where('field_name', 'board')
                        ->where('field_value', $request->board_id);
                })
                ->whereHas('metadataValues', function ($query) use ($request) {
                    $query->where('field_name', 'medium')
                        ->where('field_value', $request->medium_id);
                })
                ->whereHas('metadataValues', function ($query) use ($request) {
                    $query->where('field_name', 'series')
                        ->where('field_value', $request->book_series_id);
                })
                ->whereHas('metadataValues', function ($query) use ($request) {
                    $query->where('field_name', 'class')
                        ->where('field_value', $request->class_id);
                })->where('is_active', 1)->get();

            // dd($courses);
            if (!$courses->isEmpty()) {
                return $this->sendSuccess(compact('courses'), config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            }
            return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'),  406);
        } catch (ValidationException $e) {
            return $this->sendError(config('constants.API_MSG.VALIDATION_ERROR'), $e->errors(), 422);
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }
    public function getNonAcademicCourses(Request $request)
    {
        try {
            $request->validate([
                'sub_category_id' => 'required',
            ]);
            $courses = Course::where('category_id', 2)
                ->where('sub_category_id', $request->sub_category_id)->where('is_active', 1)->get();

            // dd($courses);
            if (!$courses->isEmpty()) {
                return $this->sendSuccess(compact('courses'), config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            }
            return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'),  406);
        } catch (ValidationException $e) {
            return $this->sendError(config('constants.API_MSG.VALIDATION_ERROR'), $e->errors(), 422);
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }
    public function saveContactEnquiries(ContactEnquiriesRequest $request)
    {
        try {
            $enquiry = ContactInquiry::create([
                'name' => $request->name,
                'email' => $request->email,
                'mobile_no' => $request->mobile_no,
                'subject' => $request->subject,
                'message' => $request->message,
                'ip' => $request->ip(),
            ]);
            if ($enquiry) {
                return $this->sendSuccess([], config('constants.FLASH_CONTACT_US_1'));
            }
            return $this->sendError(config('constants.API_MSG.REC_ADD_FAILED'),  406);
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }

}
