<?php

namespace App\Http\Controllers\Api;

use App\Models\Board;
use App\Models\BookSeries;
use App\Models\Category;
use App\Models\City;
use App\Models\Classes;
use App\Models\CourseChapter;
use App\Models\CourseMetadataValue;
use App\Models\Language;
use App\Models\MediaFiles;
use App\Models\Medium;
use App\Models\SchoolAssignedClass;
use App\Models\SchoolClass;
use App\Models\State;
use App\Models\StudentDetails;
use App\Models\Subject;
use App\Models\User;
use App\Models\UserAdditionalDetail;
use App\Models\UserClass;
use App\Models\UserRole;
use App\Services\ApiResponseService;
use Carbon\Carbon;
use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends BaseController
{

    public function deleteUserAccount(Request $request)
    {
        try {

            $user = User::where('id', Auth::id())->first();

            if (! $user) {
                return response()->json(['message' => 'User not found'], 404);
            }
            $user->delete();
            $data = '';
            return ApiResponseService::success(200, 'Account deleted successfully', $data);
        } catch (\Exception $e) {
            return ApiResponseService::error(500, config('constants.API_MSG.CALL_FAILED') . $e->getMessage());
        }
    }

    public function getUsers(Request $request)
    {
        try {
            // Fetch users with related data
            $users = User::where('created_by', Auth::id())->with(['userRoles', 'userAdditionalDetail', 'studentDetails'])->where('status', '1')
                ->get();
            return ApiResponseService::success(config('constants.API_MSG.REC_FETCHED_SUCCESS'), $users);
        } catch (\Exception $e) {
            return ApiResponseService::error(500, config('constants.API_MSG.CALL_FAILED') . $e->getMessage());
        }
    }
    public function saveUser(Request $request)
    {
        try {
            // Determine the role
            $role = $request->role;
            if (! $role) {
                return ApiResponseService::error(400, 'Role is required.');
            }

            // Route to the appropriate save method based on role
            switch ($role) {
                case 'student':
                    return $this->handleSaveOperation([$this, 'saveStudent'], $request, 'Student');
                case 'teacher':
                    return $this->handleSaveOperation([$this, 'saveTeacehr'], $request, 'Teacher');
                default:
                    return ApiResponseService::error(400, 'Invalid role.');
            }
        } catch (\Exception $e) {
            return ApiResponseService::error(500, config('constants.API_MSG.CALL_FAILED') . $e->getMessage());
        }
    }

    private function handleSaveOperation(callable $saveMethod, Request $request, $role)
    {
        try {
            return call_user_func($saveMethod, $request);
        } catch (\Exception $e) {
            return ApiResponseService::error(500, "Error saving $role: " . $e->getMessage());
        }
    }

    private function saveStudent(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|max:255',
            'admission_no'     => 'required|numeric',
            'parent_mobile_no' => 'required|numeric',
            'class'            => 'required|numeric',
            'section'          => 'required|string',
            'admission_date'   => 'required|date',
            'dob'              => 'required|date',
        ]);

        $user = $this->createOrUpdateUser($request->id, $request->name, $request->parent_mobile_no);
        $this->assignRoleToUser($user->id, 'student');

        StudentDetails::updateOrCreate(
            ['user_id' => $user->id],
            [
                'doj'     => Carbon::parse($validated['admission_date'])->format('Y-m-d'),
                'dob'     => Carbon::parse($validated['dob'])->format('Y-m-d'),
                'class'   => $validated['class'],
                'section' => $validated['section'],
            ]
        );

        UserAdditionalDetail::updateOrCreate(
            ['user_id' => $user->id],
            [
                'role'         => 'Student',
                'admission_no' => $validated['admission_no'],
            ]
        );

        return ApiResponseService::success(config('constants.API_MSG.REC_ADD_SUCCESS'), $user);
    }

    private function saveTeacher(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|max:255',
            'last_name'     => 'required|max:255',
            'gender'        => 'required|string',
            'mobile_no'     => "required|numeric|unique:users,mobile_no,{$request->id}",
            'email'         => "required|email|unique:users,email,{$request->id}",
            'address'       => 'required|max:255',
            'city'          => 'required|string',
            'state'         => 'required|string',
            'country'       => 'required|max:255',
            'qualification' => 'required|string',
            'experience'    => 'required|numeric',
            'age'           => 'required|numeric',
            'dob'           => 'required|date',
            'class'         => 'required|array',
            'subject'       => 'required|array',
        ]);

        $user = $this->createOrUpdateUser($request->id, $validated['name'], $validated['mobile_no'], $validated['email']);
        $this->assignRoleToUser($user->id, 'teacher');

        UserAdditionalDetail::updateOrCreate(
            ['user_id' => $user->id],
            [
                'role'              => 'Teacher',
                'last_name'         => $validated['last_name'],
                'gender'            => $validated['gender'],
                'age'               => $validated['age'],
                'address'           => $validated['address'],
                'city'              => $validated['city'],
                'state'             => $validated['state'],
                'country'           => $validated['country'],
                'qualification'     => $validated['qualification'],
                'experience'        => $validated['experience'],
                'dob'               => Carbon::parse($validated['dob'])->format('Y-m-d'),
                'assigned_classes'  => implode(',', $validated['class']),
                'assigned_subjects' => implode(',', $validated['subject']),
            ]
        );

        return ApiResponseService::success(config('constants.API_MSG.REC_ADD_SUCCESS'), $user);
    }

    private function createOrUpdateUser($id, $name, $mobileNo, $email = null)
    {
        return User::updateOrCreate(
            ['id' => $id],
            [
                'name'       => $name,
                'mobile_no'  => $mobileNo,
                'email'      => $email,
                'created_by' => Auth::id(),
            ]
        );
    }

    private function assignRoleToUser($userId, $roleSlug)
    {
        UserRole::updateOrCreate(
            ['user_id' => $userId],
            ['role_slug' => $roleSlug]
        );
    }

    public function getSubjects(Request $request)
    {
        try {
            $subjects = Subject::get();
            if (! $subjects->isEmpty()) {
                return $this->sendSuccess(compact('subjects'), config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            } else {
                return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'), 406);
            }
        } catch (\Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }

    public function getClasses(Request $request)
    {
        try {
            $classes = SchoolAssignedClass::where('school_id', auth()->id())->with('class')->get();
            if (! $classes->isEmpty()) {
                return $this->sendSuccess(compact('classes'), config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            } else {
                return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'), 406);
            }
        } catch (\Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }
    public function getStates(Request $request)
    {
        try {
            $states = State::get();
            if (! $states->isEmpty()) {
                return $this->sendSuccess(compact('states'), config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            } else {
                return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'), 406);
            }
        } catch (\Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }
    public function getCities(Request $request)
    {
        try {
            $cities = City::where('state_id', $request->state_id)->get();
            if (! $cities->isEmpty()) {
                return $this->sendSuccess(compact('cities'), config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            } else {
                return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'), 406);
            }
        } catch (\Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }

    public function profileImageUpdate(Request $request)
    {
        try {
            $request->validate([
                'profile_image' => 'required',
            ]);
            $user = Auth::user();

            if ($user && $user->image && Storage::disk('public')->exists('uploads/user/profile_image/' . $user->image)) {
                Storage::disk('public')->delete('uploads/user/profile_image/' . $user->image);
            }

            $profileImage = $request->file('profile_image');
            $extension    = $profileImage->getClientOriginalExtension();
            $fileName     = time() . '.' . $extension;
            $filePath     = 'uploads/user/profile_image/' . $fileName;

            Storage::disk('public')->put($filePath, file_get_contents($profileImage));

            $user->image = $fileName;
            $user->save();
            return $this->sendSuccess($user, config('constants.API_MSG.REC_UPDATE_SUCCESS'));
        } catch (ValidationException $e) {
            return $this->sendError(config('constants.API_MSG.VALIDATION_ERROR'), $e->errors(), 422);
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }
    public function downloadVideoDetails(Request $request)
    {
        try {
            $request->validate([
                'video_id' => 'required',
            ]);

            $media = MediaFiles::find($request->video_id);

            if (!$media) {
                return response()->json(['message' => 'Video not found'], 404);
            }

            // Get CourseChapter
            $chapter = CourseChapter::find($media->tbl_id);
            if (!$chapter) {
                return response()->json(['message' => 'Course chapter not found'], 404);
            }

            // Get Course
            $course = $chapter->course;
            if (!$course) {
                return response()->json(['message' => 'Course not found'], 404);
            }

            // Get Class and Subject from metadatavalues
            $classId = CourseMetadataValue::where('course_id', $course->id)
                ->where('field_name', 'class')
                ->value('field_value');

            $subjectId = CourseMetadataValue::where('course_id', $course->id)
                ->where('field_name', 'subject')
                ->value('field_value');

            // Lookup names from classes and subjects tables
            $className = SchoolClass::find($classId)?->name ?? 'N/A';
            $subjectName = Subject::find($subjectId)?->name ?? 'N/A';
            return $this->sendSuccess([
                'video_id' => $media->id,
                'category_id' => $chapter->course->category_id,
                'course_name' => $chapter->course->course_name,
                'video_chapter_title' => $chapter->chapter_name ?? '',
                'class' => $className,
                'subject' => $subjectName,
            ], config('constants.API_MSG.REC_FETCHED_SUCCESS'));
        } catch (ValidationException $e) {
            return $this->sendError(config('constants.API_MSG.VALIDATION_ERROR'), $e->errors(), 422);
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }
}
