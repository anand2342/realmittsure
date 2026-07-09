<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Http\Requests\ContentFolderStoreFileRequest;
use App\Http\Requests\GetAccessCodeRequest;
use App\Http\Requests\OnlineClassAddRequest;
use App\Http\Requests\OnlineClassStudyMaterialRequest;
use App\Http\Requests\SchoolDetailUpdateRequest;
use App\Http\Requests\StudentAddEditRequest;
use App\Http\Requests\TeacherAddEditRequest;
use App\Http\Requests\TeacherDetailUpdateRequest;
use App\Http\Requests\UserActiveInactiveRequest;
use App\Models\Section;
use App\Models\AccessCode;
use App\Models\AccessCodeEmbibe;
use App\Models\AccessCodeLog;
use App\Models\City;
use App\Models\Classes;
use App\Models\Course;
use App\Models\CourseChapter;
use App\Models\MediaFiles;
use App\Models\MediaFolder;
use App\Models\MediaGallery;
use App\Models\NotificationAlert;
use App\Models\OnlineClass;
use App\Models\Planner;
use App\Models\PlannerOff;
use App\Models\Role;
use App\Models\SchoolAssignedClass;
use App\Models\SchoolAssignedDigitalContent;
use App\Models\SchoolClass;
use App\Models\SchoolComplimentaryCourse;
use App\Models\SchoolPlannerVisibility;
use App\Models\Schools;
use App\Models\StudentDetails;
use App\Models\Subject;
use App\Models\User;
use App\Models\UserAdditionalDetail;
use App\Models\UserLog;
use App\Models\UserRole;

use Carbon\Carbon;
use DateTime;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Laravel\Sanctum\PersonalAccessToken;

class SchoolPortalApiController extends BaseController
{
    public $data = [];
    public function getConstants()
    {
        try {
            $this->data['MAX_FILE_SIZE']                 = config('constants.MAX_FILE_SIZE');
            $this->data['COURSES_FILTER_BY_ACCESS_CODE'] = config('constants.COURSES_FILTER_BY_ACCESS_CODE');
            $this->data['PAGINATION']                    = config('constants.PAGINATION');
            $this->data['GENDER']                        = config('constants.GENDER');
            $this->data['ONLINE_CLASS_STATUS']           = config('constants.ONLINE_CLASS_STATUS');
            $this->data['STATUS_LIST']                   = config('constants.STATUS_LIST');
            $this->data['REPLIED_STATUS']                = config('constants.REPLIED_STATUS');
            $this->data['SECTION']                       = config('constants.SECTION');
            if ($this->data) {
                return $this->sendSuccess([$this->data], config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            }
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }
    public function changePassword(Request $request)
    {
        try {
            $request->validate([
                'password'    => 'required|string|min:8',           // Current password
                'newpassword' => 'required|string|min:8|confirmed', // New password with confirmation
            ]);

            // Get the bearer token from the request
            $token = $request->bearerToken();
            if (! PersonalAccessToken::findToken($token)) {
                return $this->sendError('Token Not found', 406);
            }

            $user = Auth::user();

            if (! Hash::check($request->password, $user->password)) {
                return $this->sendError('Old Passwords do NOT match', 406);
            }

            $user->update(['password' => Hash::make($request->newpassword), 'validate_string' => $request->newpassword]);
            return $this->sendSuccess(compact('user'), 'Your password has been changed.');
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }
    public function getUserPermission()
    {
        try {
            $userId                   = Auth::id();
            $type                     = 'app_menu';
            $this->data['permission'] = getPermissions($type);
            if ($this->data['permission']) {
                return $this->sendSuccess([$this->data['permission']], config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            }
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }
    public function dashboard(Request $request)
    {
        try {
            $role     = getUserRoles();
            $parentId = Auth::id();
            $this->data['classes']        = getUserSchoolClasses(Auth::id());
            $board = getUserBoard();
            $medium = getUserMedium();
            $schoolClasses = SchoolAssignedClass::where('school_id', Auth::id())->pluck('class_id');

            // If the role is "school_teacher", use school_id from UserAdditionalDetail
            if ($role == "school_teacher") {
                $parentId = Auth::user()->userAdditionalDetail->school_id;
            }
            $this->data['students'] = User::with(['userAdditionalDetail'])
                ->whereHas('userAdditionalDetail', function ($query) use ($parentId) {
                    $query->where('role', 'school_student')
                        ->where('school_id', $parentId);
                })->count();

            $this->data['teachers'] = User::with('userAdditionalDetail')
                ->whereHas('userAdditionalDetail', function ($query) use ($parentId) {
                    $query->where('role', 'school_teacher')
                        ->where('school_id', $parentId);
                })->count();
            $this->data['classes']        = SchoolClass::whereIn('id', $schoolClasses)->get();

            $schoolAssignedSeries = SchoolAssignedDigitalContent::where('school_id', Auth::id())->where('series_id', '!=', null)->whereIn('class_id', $schoolClasses)->distinct('series_id')->pluck('series_id')->toArray();
            $courses = Course::whereHas('metadataValues', function ($query) use ($board) {
                $query->where('field_name', 'board')
                    ->where('field_value', $board);
            })
                ->whereHas('metadataValues', function ($query) use ($medium) {
                    $query->where('field_name', 'medium')
                        ->where('field_value', $medium);
                })->whereHas('metadataValues', function ($query) use ($schoolAssignedSeries) {
                    $query->where('field_name', 'series')
                        ->whereIn('field_value', $schoolAssignedSeries);
                })->get();
            $courseIds = $courses->pluck('id')->toArray(); // Extract the course IDs

            // Actual video count query
            $videoExtensions = ['mp4', 'avi', 'mov', 'm4v', 'm4p', 'mpg', 'mp2', 'mpeg', 'mpe', 'mpv', 'm2v', 'wmv', 'flv', 'mkv', 'webm', '3gp', 'm2ts', 'ogv', 'ts', 'mxf'];
            $videoCount = MediaFiles::where('media_files.type', 'course_chapter')
                ->whereIn('media_files.file_extension', $videoExtensions)
                ->join('course_chapters', 'media_files.tbl_id', '=', 'course_chapters.id')
                ->join('courses', 'course_chapters.course_id', '=', 'courses.id')
                ->whereIn('courses.id', $courseIds)
                ->count();

            $this->data['digitalContent'] = $videoCount;
            // Available access codes
            $this->data['availableAccessCodes'] = AccessCode::where('school_id', $parentId)
                ->whereNull('user_id')
                ->count();
            $lastMonth                             = now()->subMonth();
            $this->data['studentChangePercentage'] = $this->calculateChangePercentage(
                $this->data['students'],
                User::where('created_by', $parentId)
                    ->with(['userAdditionalDetail'])
                    ->whereHas('userAdditionalDetail', function ($query) {
                        $query->where('role', 'user');
                    })
                    ->whereMonth('created_at', $lastMonth->month)
                    ->count()
            );

            $this->data['availableAccessCodesTeachlite'] = AccessCodeEmbibe::where('school_id', $parentId)->where('type', 'teachlite')
                ->count();
            $this->data['availableAccessCodesMittlense'] = AccessCodeEmbibe::where('school_id', $parentId)->where('type', 'mittlense')
                ->count();

            $this->data['teacherChangePercentage'] = $this->calculateChangePercentage(
                $this->data['teachers'],
                User::where('created_by', $parentId)
                    ->with('userAdditionalDetail')
                    ->whereHas('userAdditionalDetail', function ($query) {
                        $query->where('role', 'teacher');
                    })
                    ->whereMonth('created_at', $lastMonth->month)
                    ->count()
            );
            $this->data['plannedClasses'] = OnlineClass::where('parent_id', $parentId)
                ->with(['class', 'instructor', 'subject'])
                ->get();

            $this->data['accessCodesClasses'] = AccessCode::where('school_id', $parentId)
                ->with('class')
                ->select('class_id')
                ->selectRaw('count(*) as total_codes')
                ->selectRaw('count(case when user_id is not null then 1 end) as used_codes')
                ->selectRaw('count(case when user_id is null then 1 end) as unused_codes')
                ->groupBy('class_id')
                ->orderBy('class_id', 'asc')
                ->get();

            $this->data['chartData'] = User::selectRaw('MONTH(created_at) as month')
                ->selectRaw('COUNT(*) as count')
                ->whereHas('userAdditionalDetail', function ($query) use ($parentId) {
                    $query->where('role', 'school_student')
                        ->where('school_id', $parentId);
                })
                ->whereYear('created_at', date('Y')) // Filter for current year only
                ->where('is_verified', 1)
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->get()
                ->mapWithKeys(function ($item) {
                    return [(int)$item->month => $item->count];
                });

            return $this->sendSuccess($this->data, config('constants.API_MSG.REC_FETCHED_SUCCESS'));
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }

    public function studentAddEdit(StudentAddEditRequest $request)
    {
        try {
            // dd($request->all());
            $role     = getUserRoles();
            $parentId = Auth::id();

            // If the role is "school_teacher", use school_id from UserAdditionalDetail
            if ($role == "school_teacher") {
                $parentId = Auth::user()->userAdditionalDetail->school_id;
            }
            $user = User::updateOrCreate(
                [
                    'id' => $request->id,
                ],
                [
                    'name'       => $request->name,
                    'email'            => $request->email ?? null,
                    'mobile_no'  => $request->parent_mobile_no,
                    'password'         => Hash::make('Mitt@123'),
                    'validate_string' => 'Mitt@123',
                    'created_by' => Auth::id(),
                    'is_verified'       => 1,
                    'source'       => 'app',
                ]
            );

            if (! $user) {
                if ($request->id) {
                    return $this->sendError(config('constants.API_MSG.REC_UPDATE_FAILED'));
                } else {
                    return $this->sendError(config('constants.API_MSG.REC_ADD_FAILED'));
                }
            }

            $userRole = UserRole::updateOrCreate(
                ['user_id' => $user->id],
                ['role_slug' => 'school_student']
            );

            if (! $userRole) {
                if ($request->id) {
                    return $this->sendError(config('constants.API_MSG.REC_UPDATE_FAILED'));
                } else {
                    return $this->sendError(config('constants.API_MSG.REC_ADD_FAILED'));
                }
            }

            $admissionDate = Carbon::parse($request->admission_date)->format('Y-m-d');
            $dob           = Carbon::parse($request->dob)->format('Y-m-d');

            $studentDetail = StudentDetails::updateOrCreate(
                [
                    'user_id' => $request->id,
                ],
                [
                    'user_id'     => $user->id,
                    'parent_id'   => $parentId,
                    'school_id'   => $parentId,
                    'doj'     => $admissionDate,
                    'dob'     => $dob,
                    'class'   => $request->class,
                    'parent_name' => $request->parent_name ?? null,
                    'section' => $request->section,
                ]
            );

            if (! $studentDetail) {
                if ($request->id) {
                    return $this->sendError(config('constants.API_MSG.REC_UPDATE_FAILED'));
                } else {
                    return $this->sendError(config('constants.API_MSG.REC_ADD_FAILED'));
                }
            }

            $userAddtionalDetail = UserAdditionalDetail::updateOrCreate(
                [
                    'user_id' => $request->id,
                ],
                [
                    'role'         => 'school_student',
                    'school_id'    => $parentId,
                    'user_id'      => $user->id,
                    'admission_no' => $request->admission_no,
                ]
            );

            if (! $userAddtionalDetail) {
                if ($request->id) {
                    return $this->sendError(config('constants.API_MSG.REC_UPDATE_FAILED'));
                } else {
                    return $this->sendError(config('constants.API_MSG.REC_ADD_FAILED'));
                }
            }
            if ($request->id) {
                return $this->sendSuccess([], config('constants.API_MSG.REC_UPDATE_SUCCESS'));
            } else {
                $sent = sendSms($user->mobile_no, '', $user);
                return $this->sendSuccess([], config('constants.API_MSG.REC_ADD_SUCCESS'));
            }
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }

    public function teacherAddEdit(TeacherAddEditRequest $request)
    {
        try {
            $role     = getUserRoles();
            $parentId = Auth::id();

            // If the role is "school_teacher", use school_id from UserAdditionalDetail
            if ($role == "school_teacher") {
                $parentId = Auth::user()->userAdditionalDetail->school_id;
            }
            $user = User::updateOrCreate(
                [
                    'id' => $request->id,
                ],
                [
                    'name'       => $request->name,
                    'mobile_no'  => $request->mobile_no,
                    'email'      => $request->email,
                    'password'         => Hash::make('Mitt@123'),
                    'validate_string' => 'Mitt@123',
                    'created_by' => Auth::id(),
                    'source'       => 'app',
                ]
            );

            if (! $user) {
                if ($request->id) {
                    return $this->sendError(config('constants.API_MSG.REC_UPDATE_FAILED'));
                } else {
                    return $this->sendError(config('constants.API_MSG.REC_ADD_FAILED'));
                }
            }

            $userRole = UserRole::updateOrCreate(
                ['user_id' => $user->id],
                ['role_slug' => 'school_teacher']
            );

            if (! $userRole) {
                if ($request->id) {
                    return $this->sendError(config('constants.API_MSG.REC_UPDATE_FAILED'));
                } else {
                    return $this->sendError(config('constants.API_MSG.REC_ADD_FAILED'));
                }
            }

            $dob = Carbon::parse($request->dob)->format('Y-m-d');

            $userAddtionalDetail = UserAdditionalDetail::updateOrCreate(
                [
                    'user_id' => $request->id,
                ],
                [
                    'role'              => 'school_teacher',
                    'school_id'         => $parentId,
                    'user_id'           => $user->id,
                    'last_name'         => $request->last_name ?? null,
                    'gender'            => $request->gender ?? null,
                    'age'               => $request->age ?? null,
                    'address'           => $request->address ?? null,
                    'city'              => $request->city ?? null,
                    'state'             => $request->state ?? null,
                    'country'           => $request->country ?? null,
                    'qualification'     => $request->qualification ?? null,
                    'class_assigned'    => $request->classes_assigned,
                    'experience'        => $request->experience ?? null,
                    'dob'               => $dob ?? null,
                    'assigned_classes'  => $request->class ? implode(',', $request->class) : 'null',
                    'assigned_subjects' => $request->subject ? implode(',', $request->subject) : 'null',
                ]
            );

            if (! $userAddtionalDetail) {
                if ($request->id) {
                    return $this->sendError(config('constants.API_MSG.REC_UPDATE_FAILED'));
                } else {
                    return $this->sendError(config('constants.API_MSG.REC_ADD_FAILED'));
                }
            }

            if ($request->id) {
                return $this->sendSuccess([], config('constants.API_MSG.REC_UPDATE_SUCCESS'));
            } else {
                $sent = sendSms($user->mobile_no, '', $user);
                return $this->sendSuccess([], config('constants.API_MSG.REC_ADD_SUCCESS'));
            }
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }

    public function studentManager(Request $request)
    {
        try {
            $role                   = getUserRoles();
            $parentId               = Auth::id();
            $teacherAssignedClasses = [];

            // If the role is "school_teacher", use school_id from UserAdditionalDetail
            if ($role == "school_teacher") {
                $parentId = Auth::user()->userAdditionalDetail->school_id;

                // Get the teacher's assigned classes
                $teacherAssignedClasses = getTeacherAssignedClasses();
            }

            $this->data['classes']        = getUserSchoolClasses($parentId);
            $this->data['sections'] = Section::where('is_active', 1)->pluck('section_name', 'id');

            $this->data['teacherClasses'] = SchoolClass::whereIn('id', $teacherAssignedClasses)->pluck('name', 'id');

            $query = User::where('is_verified', 1)->with(['userAdditionalDetail', 'studentDetails', 'userAccessCode'])
                ->whereHas('userAdditionalDetail', function ($query) use ($parentId) {
                    $query->where('role', 'school_student')
                        ->where('school_id', $parentId);
                })
                ->when($role === 'school_teacher', function ($query) use ($teacherAssignedClasses) {
                    $query->whereHas('studentDetails', function ($subQuery) use ($teacherAssignedClasses) {
                        $subQuery->whereIn('class', $teacherAssignedClasses);
                    });
                });
            if ($request->filled('status')) {
                $query->where('status', 'like', '%' . $request->status . '%');
            }
            if ($request->filled('sort')) {
                $sortOrder = $request->sort === 'asc' ? 'ASC' : 'DESC';
                $query->orderBy('name', $sortOrder);
            } else {
                $query->orderBy('created_at', 'DESC'); // Default sorting
            }

            $this->data['students'] = $query->paginate(config('constants.PAGINATION.default'));
            // dd($this->data['students']);
            return $this->sendSuccess($this->data, config('constants.API_MSG.REC_FETCHED_SUCCESS'));
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }
    public function studentSearch(Request $request)
    {
        try {
            $request->validate([
                'search_term' => 'nullable|string|max:255',
            ]);

            $role = getUserRoles();
            $parentId = Auth::id();
            $teacherAssignedClasses = [];

            if ($role == "school_teacher") {
                $parentId = Auth::user()->userAdditionalDetail->school_id;
                $teacherAssignedClasses = getTeacherAssignedClasses();
            }

            $studentsQuery = User::with(['userAdditionalDetail', 'studentDetails'])
                ->whereHas('userAdditionalDetail', function ($query) use ($parentId) {
                    $query->where('role', 'school_student')
                        ->where('school_id', $parentId);
                })
                ->when($role === 'school_teacher', function ($query) use ($teacherAssignedClasses) {
                    $query->whereHas('studentDetails', function ($subQuery) use ($teacherAssignedClasses) {
                        $subQuery->whereIn('class', $teacherAssignedClasses);
                    });
                });

            // Apply search term if provided
            if ($request->has('search_term') && !empty($request->search_term)) {
                $searchTerm = $request->search_term;
                $studentsQuery->where(function ($query) use ($searchTerm) {
                    $query->where('name', 'like', "%{$searchTerm}%")
                        ->orWhere('email', 'like', "%{$searchTerm}%")
                        ->orWhere('mobile_no', 'like', "%{$searchTerm}%")
                        ->orWhereHas('studentDetails', function ($q) use ($searchTerm) {
                            $q->where('roll_number', 'like', "%{$searchTerm}%");
                        });
                });
            }

            $students = $studentsQuery->get();

            return $this->sendSuccess(['students' => $students], config('constants.API_MSG.REC_FETCHED_SUCCESS'));
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }
    public function teacherManager(Request $request)
    {
        try {
            $teachersQuery = User::where('created_by', Auth::id())->with('userAdditionalDetail')
                ->whereHas('userAdditionalDetail', function ($query) {
                    $query->where('role', 'school_teacher');
                })->orderBy('created_at', 'DESC');

            if ($request->status == 'active') {
                $teachersQuery->where('status', 1);
            } else if ($request->status == 'inactive') {
                $teachersQuery->where('status', 0)->orderBy('created_at', 'DESC')->paginate(config('constants.PAGINATION.api'));
            }
            $teachers = $teachersQuery->paginate(config('constants.PAGINATION.api'));

            foreach ($teachers as $teacher) {
                $userAdditionalDetail                    = $teacher->userAdditionalDetail;
                $assignedClassIds                        = explode(',', $userAdditionalDetail->assigned_classes);
                $assignedSubjectIds                      = explode(',', $userAdditionalDetail->assigned_subjects);
                $assignedClasses                         = Classes::whereIn('id', $assignedClassIds)->get();
                $assignedSubjects                        = Subject::whereIn('id', $assignedSubjectIds)->get();
                $userAdditionalDetail->assigned_classes  = $assignedClasses;
                $userAdditionalDetail->assigned_subjects = $assignedSubjects;
            }

            return $this->sendSuccess(compact('teachers'), config('constants.API_MSG.REC_FETCHED_SUCCESS'));
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }
    public function teacherSearch(Request $request)
    {
        try {
            $request->validate([
                'search_term' => 'nullable|string|max:255',
            ]);

            $teachersQuery = User::where('created_by', Auth::id())
                ->with('userAdditionalDetail')
                ->whereHas('userAdditionalDetail', function ($query) {
                    $query->where('role', 'school_teacher');
                });

            // Apply search term if provided
            if ($request->has('search_term') && !empty($request->search_term)) {
                $searchTerm = $request->search_term;
                $teachersQuery->where(function ($query) use ($searchTerm) {
                    $query->where('name', 'like', "%{$searchTerm}%")
                        ->orWhere('email', 'like', "%{$searchTerm}%")
                        ->orWhere('mobile_no', 'like', "%{$searchTerm}%")
                        ->orWhereHas('userAdditionalDetail', function ($q) use ($searchTerm) {
                            $q->where('qualification', 'like', "%{$searchTerm}%");
                        });
                });
            }

            $teachers = $teachersQuery->get();

            // Load assigned classes and subjects for each teacher
            foreach ($teachers as $teacher) {
                $userAdditionalDetail = $teacher->userAdditionalDetail;
                if ($userAdditionalDetail->assigned_classes) {
                    $assignedClassIds = explode(',', $userAdditionalDetail->assigned_classes);
                    $assignedClasses = Classes::whereIn('id', $assignedClassIds)->get();
                    $userAdditionalDetail->assigned_classes = $assignedClasses;
                }
                if ($userAdditionalDetail->assigned_subjects) {
                    $assignedSubjectIds = explode(',', $userAdditionalDetail->assigned_subjects);
                    $assignedSubjects = Subject::whereIn('id', $assignedSubjectIds)->get();
                    $userAdditionalDetail->assigned_subjects = $assignedSubjects;
                }
            }

            return $this->sendSuccess(['teachers' => $teachers], config('constants.API_MSG.REC_FETCHED_SUCCESS'));
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }

    public function userActiveInactive(UserActiveInactiveRequest $request)
    {
        try {
            $action_date = Carbon::parse($request->date)->format('Y-m-d');
            $user        = User::where('id', $request->user_id)->first();
            if ($user) {
                $userJson = $user->toJson();
                $userlog  = UserLog::create([
                    'user_id'     => $user->id,
                    'updated_by'  => Auth::id(),
                    'title'       => ($user->status == 1) ? 'User Inactived' : 'User Actived',
                    'uri'         => $request->getBaseUrl(),
                    'action_as'   => ($user->status == 1) ? 'user_inactive' : 'user_active',
                    'action_date' => $action_date,
                    'json_data'   => $userJson,
                    'log_type'    => 'user_update',
                    'log_date'    => now(),
                ]);
                $user->status = ($user->status == 1) ? 0 : 1;
                $user->save(['status']);
                if ($userlog) {
                    return $this->sendSuccess([], config('constants.API_MSG.REC_UPDATE_SUCCESS'));
                }
            } else {
                return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'), 406);
            }
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }
    public function userActiveInactiveLog(Request $request)
    {
        try {
            $userlogs = UserLog::where('user_id', $request->user_id)->get();
            if (! $userlogs->isEmpty()) {
                return $this->sendSuccess(compact('userlogs'), config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            } else {
                return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'), 406);
            }
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }

    public function UnVerfiredStudent(Request $request)
    {
        try {
            $parentId               = Auth::id();
            $this->data['classes']        = getUserSchoolClasses($parentId);

            $query = User::where('is_verified', 0)->with(['userAdditionalDetail', 'studentDetails', 'userAccessCode'])
                ->whereHas('userAdditionalDetail', function ($query) use ($parentId) {
                    $query->where('role', 'school_student')
                        ->where('school_id', $parentId);
                });

            $this->data['students'] = $query->paginate(config('constants.PAGINATION.default'));
            if ($this->data['students']) {
                return $this->sendSuccess($this->data, config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            } else {
                return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'), 406);
            }
            // $this->data['roles'] = Role::get();
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }
    public function schoolDetails(Request $request)
    {
        try {
            $user            = User::where('id', Auth::id())->get();
            $school          = Schools::where('user_id', Auth::id())->get();
            $additional_data = UserAdditionalDetail::where('user_id', Auth::id())->with(['schoolBoard', 'schoolMedium'])->get();
            if (! $user->isEmpty()) {
                return $this->sendSuccess(compact('school', 'additional_data', 'user'), config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            } else {
                return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'), 406);
            }
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }
    public function schoolDetailsUpdate(SchoolDetailUpdateRequest $request)
    {
        try {
            $user = auth()->user();
            //    if ($user->image) {

            //         if ($user && $user->image && Storage::disk('public')->exists('uploads/user/profile_image/' . $user->image)) {
            //             Storage::disk('public')->delete('uploads/user/profile_image/' . $user->image);
            //         }

            //         $profileImage = $request->file('profile_image');
            //         $extension    = $profileImage->getClientOriginalExtension();
            //         $fileName     = time() . '.' . $extension;
            //         $filePath     = 'uploads/user/profile_image/' . $fileName;
            //         Storage::disk('public')->put($filePath, file_get_contents($profileImage));
            //         $user->image = $fileName;
            //         $user->save();
            //     }
            $schoolDetails      = UserAdditionalDetail::where('user_id', $user->id)->first();
            $schoolTableDetails = Schools::where('user_id', $user->id)->first();
            if (! $schoolDetails) {
                return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'), 406);
            }
            if (! $schoolTableDetails) {
                return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'), 406);
            }
            if ($schoolDetails) {
                $schoolDetails->website                  = $request->website;
                $schoolDetails->decision_maker           = $request->decision_maker;
                $schoolDetails->decision_maker_mobile_no = $request->decision_maker_mobile_no;
                $schoolDetails->school_medium            = $request->school_medium;
                $schoolDetails->state       = $request->state;
                $schoolDetails->city        = $request->city;
                $schoolDetails->strength                 = $request->strength;
                $schoolDetails->save();

                $user->mobile_no =  $request->decision_maker_mobile_no;
                $user->save();
            }
            if ($schoolTableDetails) {
                $schoolTableDetails->postal_code = $request->postal_code;
                $schoolTableDetails->state       = $request->state;
                $schoolTableDetails->city        = $request->city;
                $schoolTableDetails->address     = $request->address;
                $schoolTableDetails->save();
            }
            return $this->sendSuccess(['filePath' => $schoolDetails], config('constants.API_MSG.REC_UPDATE_SUCCESS'));
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }

    public function getAccessCodes(GetAccessCodeRequest $request)
    {
        try {
            $userId = auth()->id();
            $query  = AccessCode::with('user')->where('school_id', $userId);

            if ($request->board_id) {
                $query = $query->where('board_id', $request->board_id);
            }

            if ($request->medium_id) {
                $query = $query->where('medium_id', $request->medium_id);
            }

            if (isset($request->class_id) && $request->class_id) {
                $query = $query->where('class_id', $request->class_id);
            }

            $this->data['accessCode'] = $query->orderBy('created_at', 'DESC')->paginate(config('PAGINATION.api'));

            if (! $this->data['accessCode']->isEmpty()) {
                return $this->sendSuccess($this->data, config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            } else {
                return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'), 406);
            }
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }

    public function myCourses(Request $request)
    {
        try {
            $role                   = getUserRoles();
            $parentId               = Auth::id();
            $teacherAssignedClasses = [];

            // If the role is "school_teacher", use school_id from UserAdditionalDetail
            if ($role == "school_teacher") {
                $parentId               = Auth::user()->userAdditionalDetail->school_id;
                $teacherAssignedClasses = getTeacherAssignedClasses();
            }

            if (config('COURSES_FILTER_BY_ACCESS_CODE') == 1) {
                $query = AccessCode::with('class')
                    ->select('class_id')
                    ->where('school_id', $parentId)
                    ->groupBy('class_id');

                // Filter by teacher's assigned classes if the role is "school_teacher"
                if ($role === 'school_teacher' && ! empty($teacherAssignedClasses)) {
                    $query->whereIn('class_id', $teacherAssignedClasses);
                }
            } else {
                $query = SchoolAssignedClass::with('class')->where('school_id', $parentId)->select('class_id')->groupBy('class_id');

                // Filter by teacher's assigned classes if the role is "school_teacher"
                if ($role === 'school_teacher' && ! empty($teacherAssignedClasses)) {
                    $query->whereIn('class_id', $teacherAssignedClasses);
                }
            }

            $this->data['classCourses']        = $query->get();
            $this->data['complimentaryCourse'] = SchoolComplimentaryCourse::where('school_id', $parentId)->with('courses')->get();

            if (! $this->data['classCourses']->isEmpty()) {
                return $this->sendSuccess($this->data, config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            } else {
                return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'), 406);
            }
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }

    public function classSubject(Request $request)
    {
        try {
            $role                    = getUserRoles();
            $parentId                = Auth::id();
            $teacherAssignedSubjects = [];
            $classId               = $request->class_id;

            // If the role is "school_teacher", get school_id and assigned subjects
            if ($role == "school_teacher") {
                $parentId                = Auth::user()->userAdditionalDetail->school_id;
                $teacherAssignedSubjects = getTeacherAssignedSubjects();
            }
            $schoolAssignedDigitalContent = SchoolAssignedDigitalContent::where('school_id', $parentId)->where('class_id', $classId)->get();
            $allSubjectIds = [];

            // Loop through each row of digital content and extract the subject ids
            foreach ($schoolAssignedDigitalContent as $digitalContent) {
                // Merge the subject ids (comma separated) into the array
                $allSubjectIds = array_merge($allSubjectIds, explode(',', $digitalContent->subject_id));
            }

            // Get unique subject ids (removes duplicates)
            $uniqueSubjectIds = array_unique($allSubjectIds);

            // Return the unique subject ids (without indexed array)
            $schoolAssignedSubjects = array_values($uniqueSubjectIds);

            $this->data['classId'] = $request->class_id;

            if (config('COURSES_FILTER_BY_ACCESS_CODE') == 1) {
                $this->data['accessCodes'] = AccessCode::where('school_id', $parentId)->where('class_id', $classId)->get();
                $subjectIds                = [];

                foreach ($this->data['accessCodes'] as $code) {
                    $subjectIds = array_merge($subjectIds, explode(',', $code->subject_id));
                }

                // Filter subjects based on the teacher's assigned subjects if the role is "school_teacher"
                $subjectQuery = Subject::whereIn('id', $subjectIds);

                if ($role == "school_teacher" && ! empty($teacherAssignedSubjects)) {
                    $subjectQuery->whereIn('id', $teacherAssignedSubjects);
                }

                $this->data['subjects']             = $subjectQuery->get();
                $this->data['totalAccessCodes']     = AccessCode::where('school_id', $parentId)->where('class_id', $classId)->count();
                $this->data['unUsedAccessCodes']    = AccessCode::where('school_id', $parentId)->where('class_id', $classId)->where('user_id', null)->count();
                $this->data['occcupiedAccessCodes'] = AccessCode::where('school_id', $parentId)->where('class_id', $classId)->whereNotNull('user_id')->count();
                $this->data['remainingAccessCodes'] = $this->data['totalAccessCodes'] - $this->data['occcupiedAccessCodes'];
                $this->data['redeemedAccessCode']   = AccessCode::where('school_id', $parentId)->where('class_id', $classId)->whereNotNull('user_id')->with('usedAccessCodes', 'accessCodeLog')->get();
                $this->data['unRedeemedAccessCode'] = AccessCode::where('school_id', $parentId)->where('class_id', $classId)->where('user_id', null)->with('usedAccessCodes', 'accessCodeLog')->get();

                $this->data['users'] = User::with(['userAdditionalDetail', 'studentDetails'])
                    ->whereHas('userAdditionalDetail', function ($query) use ($parentId) {
                        $query->where('role', 'school_student')
                            ->where('school_id', $parentId);
                    })->whereHas('studentDetails', function ($query) use ($classId) {
                        $query->where('class', $classId);
                    })->whereDoesntHave('accessCodes')->get();
            } else {
                $subjectIds = Course::where('category_id', 1)
                    ->whereHas('metadataValues', function ($query) use ($classId) {
                        $query->where('field_name', 'class')->where('field_value', $classId);
                    })
                    ->with(['metadataValues' => function ($query) {
                        $query->where('field_name', 'subject');
                    }])
                    ->get()
                    ->pluck('metadataValues')
                    ->flatten()
                    ->where('field_name', 'subject')
                    ->pluck('field_value')
                    ->unique()
                    ->values();

                if (! empty($schoolAssignedSubjects)) {
                    $subjectQuery = Subject::query();
                    if ($role == "school_admin") {
                        $subjectQuery->whereIn('id', $schoolAssignedSubjects);
                    } elseif ($role == "school_teacher" && ! empty($teacherAssignedSubjects)) {
                        $commonAssignedSubjects = array_intersect($schoolAssignedSubjects, $teacherAssignedSubjects);
                        $subjectQuery->whereIn('id', $commonAssignedSubjects);
                    }
                    $this->data['subjects'] = $subjectQuery->get();
                } else {
                    $this->data['subjects'] = collect([]);
                }
            }
            // dd($this->data);
            if (! $this->data['subjects']->isEmpty()) {
                return $this->sendSuccess($this->data, config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            } else {
                return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'), 406);
            }
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }

    public function courseListing(Request $request)
    {
        try {
            $role                               = getUserRoles();
            $board                              = getUserBoard();
            $medium                             = getUserMedium();
            $subject_id                         = $request->subject_id;
            $class_id                           = $request->class_id;
            $this->data['totalAccessCodes']     = AccessCode::where('school_id', auth()->id())->where('class_id', $class_id)->count();
            $this->data['unUsedAccessCodes']    = AccessCode::where('school_id', auth()->id())->where('class_id', $class_id)->where('user_id', null)->count();
            $this->data['occcupiedAccessCodes'] = AccessCode::where('school_id', auth()->id())->where('class_id', $class_id)->whereNotNull('user_id')->count();
            $this->data['remainingAccessCodes'] = $this->data['totalAccessCodes'] - $this->data['occcupiedAccessCodes'];
            $parentId                           = Auth::id();

            if ($role == "school_teacher") {
                $parentId = Auth::user()->userAdditionalDetail->school_id;
            }
            if ($subject_id) {
                $schoolAssignedSeries = SchoolAssignedDigitalContent::where('school_id', $parentId)
                    ->where('class_id', $class_id)
                    ->whereRaw("FIND_IN_SET(?, subject_id)", [$subject_id])
                    ->pluck('series_id') // get all matching series
                    ->toArray(); // convert to array
                // dd(Auth::id(), $parentId, $schoolAssignedSeries, $subject_id, $class_id);

                $query = Course::where('is_active', 1)->withCount('totalChapters')->with([
                    'category',
                    'metadataValues' => function ($query) {
                        $query->select('course_id', 'field_name', 'field_value');
                    },
                    'metadataValues.classInfo',
                ])
                    ->where('category_id', 1)
                    ->whereHas('metadataValues', function ($query) use ($schoolAssignedSeries) {
                        $query->where('field_name', 'series')
                            ->whereIn('field_value', $schoolAssignedSeries); // use whereIn instead of where

                    })
                    ->whereHas('metadataValues', function ($query) use ($subject_id) {
                        $query->where('field_name', 'subject')
                            ->where('field_value', $subject_id);
                    })
                    ->whereHas('metadataValues', function ($query) use ($class_id) {
                        $query->where('field_name', 'class')
                            ->where('field_value', $class_id);
                    });
                // // Apply board filter only if board is not 0
                // if ($board != 0) {
                //     $query->whereHas('metadataValues', function ($query) use ($board) {
                //         $query->where('field_name', 'board')->where('field_value', $board);
                //     });
                // }

                // // Apply medium filter only if medium is not 0
                // if ($medium != 0) {
                //     $query->whereHas('metadataValues', function ($query) use ($medium) {
                //         $query->where('field_name', 'medium')->where('field_value', $medium);
                //     });
                // }

                $this->data['courseListing'] = $query->get();
            } else {

                // $schoolAssignedDigitalContent = SchoolAssignedDigitalContent::where('school_id', $parentId)->where('class_id', $class_id)->get();
                // $allSubjectIds = [];
                // foreach ($schoolAssignedDigitalContent as $digitalContent) {
                //     $allSubjectIds = array_merge($allSubjectIds, explode(',', $digitalContent->subject_id));
                // }
                // $uniqueSubjectIds = array_unique($allSubjectIds);
                // $schoolAssignedSubjects = array_values($uniqueSubjectIds);

                // $schoolAssignedSeries = SchoolAssignedDigitalContent::where('school_id', $parentId)
                //     ->where('class_id', $class_id)
                //     ->pluck('series_id')
                //     ->first();
                // // dd(Auth::id(), $parentId, $schoolAssignedSeries, $subject_id, $class_id);

                // $query = Course::where('is_active', 1)->withCount('totalChapters')->with([
                //     'category',
                //     'metadataValues' => function ($query) {
                //         $query->select('course_id', 'field_name', 'field_value');
                //     },
                //     'metadataValues.classInfo',
                // ])
                //     ->where('category_id', 1)
                //     ->whereHas('metadataValues', function ($query) use ($schoolAssignedSeries) {
                //         $query->where('field_name', 'series')
                //             ->where('field_value', $schoolAssignedSeries);
                //     })
                //     ->whereHas('metadataValues', function ($query) use ($schoolAssignedSubjects) {
                //         $query->where('field_name', 'subject')
                //             ->whereIn('field_value', $schoolAssignedSubjects);
                //     })
                //     ->whereHas('metadataValues', function ($query) use ($class_id) {
                //         $query->where('field_name', 'class')
                //             ->where('field_value', $class_id);
                //     });
                // // if ($board != 0) {
                // //     $query->whereHas('metadataValues', function ($query) use ($board) {
                // //         $query->where('field_name', 'board')->where('field_value', $board);
                // //     });
                // // }

                // // // Apply medium filter only if medium is not 0
                // // if ($medium != 0) {
                // //     $query->whereHas('metadataValues', function ($query) use ($medium) {
                // //         $query->where('field_name', 'medium')->where('field_value', $medium);
                // //     });
                // // }

                // $this->data['courseListing'] = $query->get();



                // 1) Get every (series, subjects CSV) row for this school+class
                $assigned = SchoolAssignedDigitalContent::query()
                    ->where('school_id', $parentId)
                    ->where('class_id', $class_id)
                    ->get(['series_id', 'subject_id']);

                // 2) Build: series => [subject_ids...]
                $seriesSubjects = [];
                foreach ($assigned as $row) {
                    $seriesId = (string) $row->series_id;

                    // subject_id is CSV in your table
                    $subjects = array_filter(array_map('trim', explode(',', (string) $row->subject_id)));

                    if (!isset($seriesSubjects[$seriesId])) {
                        $seriesSubjects[$seriesId] = [];
                    }
                    // merge & dedupe
                    $seriesSubjects[$seriesId] = array_values(array_unique(array_merge($seriesSubjects[$seriesId], $subjects)));
                }

                // If nothing assigned, return empty collection early
                if (empty($seriesSubjects)) {
                    $this->data['courseListing'] = collect();
                    // optionally: return/view early
                } else {
                    // 3) Build the Course query
                    $query = Course::query()
                        ->where('is_active', 1)
                        ->where('category_id', 1)
                        ->withCount('totalChapters')
                        ->with([
                            'category',
                            'metadataValues' => function ($q) {
                                $q->select('course_id', 'field_name', 'field_value');
                            },
                            'metadataValues.classInfo',
                        ])

                        // class must match
                        ->whereHas('metadataValues', function ($q) use ($class_id) {
                            $q->where('field_name', 'class')
                                ->where('field_value', (string) $class_id);
                        })

                        // (series=S AND subject in S's subjects) for ANY S
                        ->where(function ($outer) use ($seriesSubjects) {
                            foreach ($seriesSubjects as $seriesId => $subjectIds) {
                                if (empty($subjectIds)) {
                                    continue;
                                }

                                $outer->orWhere(function ($pair) use ($seriesId, $subjectIds) {
                                    $pair
                                        ->whereHas('metadataValues', function ($q) use ($seriesId) {
                                            $q->where('field_name', 'series')
                                                ->where('field_value', (string) $seriesId);
                                        })
                                        ->whereHas('metadataValues', function ($q) use ($subjectIds) {
                                            $q->where('field_name', 'subject')
                                                ->whereIn('field_value', array_map('strval', $subjectIds));
                                        });
                                });
                            }
                        });

                    // Optional extra filters (uncomment if needed)
                    // if ($board != 0) {
                    //     $query->whereHas('metadataValues', function ($q) use ($board) {
                    //         $q->where('field_name', 'board')->where('field_value', (string) $board);
                    //     });
                    // }
                    // if ($medium != 0) {
                    //     $query->whereHas('metadataValues', function ($q) use ($medium) {
                    //         $q->where('field_name', 'medium')->where('field_value', (string) $medium);
                    //     });
                    // }

                    $this->data['courseListing'] = $query->get();
                }
            }
            $this->data['redeemedAccessCode'] = AccessCode::where('school_id', auth()->id())->with('usedAccessCodes')->where('class_id', $class_id)->get();

            if (! $this->data['courseListing']->isEmpty()) {
                return $this->sendSuccess($this->data, config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            } else {
                return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'), 406);
            }
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }

    public function chapterListing(Request $request)
    {
        try {
            $language = $request->input('language', 'bilingual'); // default

            $chapters = CourseChapter::with([
                'chapterListing' => function ($query) use ($language) {
                    $query->where('language', $language);
                },
                'folder',
                'documents',
                'resources'
            ])
                ->where('course_id', $request->course_id)
                ->orderBy('sort_order', 'asc')
                ->get();
                // dd($chapters);

            if ($chapters->isEmpty()) {
                return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'), 406);
            }

            return $this->sendSuccess(['chapters' => $chapters], config('constants.API_MSG.REC_FETCHED_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }


    public function assiginAccessCode(Request $request)
    {
        try {
            $this->data['accessCode'] = $request->access_code;
            $this->data['userId']     = $request->user_id;
            $accessCode               = AccessCode::where('school_id', auth()->id())->where('access_code', $request->access_code)->first();
            if ($accessCode) {
                $statusUpdated = $accessCode->update([
                    'user_id' => $request->user_id,
                    'status'  => 'active',
                ]);
                AccessCodeLog::create([
                    'user_id'   => $request->user_id,
                    'title'     => 'Access Code Activated',
                    'action_as' => 'user_access_code_active',
                    'action_by' => auth()->id(),
                    'json_data' => json_encode([
                        $accessCode,
                    ]),
                ]);
            } else {
                return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'), 406);
            }

            if ($statusUpdated) {
                return $this->sendSuccess($this->data, config('constants.API_MSG.REC_UPDATE_SUCCESS'));
            } else {
                return $this->sendError(config('constants.API_MSG.REC_UPDATE_FAILED'), 406);
            }
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }

    public function schoolContentFolderListing(Request $request)
    {
        try {
            $role     = getUserRoles();
            $parentId = Auth::id();

            // If the role is "school_teacher", adjust parentId and use instructor_id for fetching classes
            if ($role == "school_teacher") {
                $parentId = Auth::user()->userAdditionalDetail->school_id;
            }

            $this->data['folderListing'] = MediaFolder::where('parent_id', $parentId)->where('class_id', null)->withCount('fileCount')->get();
            $this->data['teacherFolderListing'] = MediaFolder::where('parent_id', $parentId)->whereNotNull('class_id')->withCount('fileCount')->get();

            $this->data['classCourses']  = AccessCode::with('class')
                ->select('class_id')
                ->where('school_id', Auth::id())
                ->groupBy('class_id')
                ->get();

            if ($this->data['folderListing']) {
                return $this->sendSuccess([$this->data], config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            } else {
                return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'), 406);
            }
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }

    public function createFolder(Request $request)
    {
        try {
            $role     = getUserRoles();
            $parentId = Auth::id();

            // If the role is "school_teacher", adjust parentId and use instructor_id for fetching classes
            if ($role == "school_teacher") {
                $parentId = Auth::user()->userAdditionalDetail->school_id;
            }
            $folder               = new MediaFolder;
            $folder->folder_name  = $request->folder_name;
            $folder->folder_color = $request->folder_color;
            $folder->class_id     = $request->class_id;
            $folder->subject_id   = $request->subject_id;
            $folder->parent_id    = $parentId;
            $folder->folder_icon  = 'frontend/images/folder-yellow.svg';
            $folder->save();
            if ($folder) {
                return $this->sendSuccess([], config('constants.API_MSG.REC_ADD_SUCCESS'));
            } else {
                return $this->sendError(config('constants.API_MSG.REC_ADD_FAILED'), 406);
            }
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }

    public function schoolFolderContentView(Request $request)
    {
        try {
            $this->data['folder']            = MediaFolder::find($request->folder_id);
            $this->data['contentFolderView'] = MediaFiles::where('type', 'content_upload')->where('tbl_id', $request->folder_id)->get();
            if ($this->data['contentFolderView']) {
                return $this->sendSuccess([$this->data], config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            }
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }

    public function storeFile(ContentFolderStoreFileRequest $request)
    {
        try {
            $file = $request->file('file');
            // dd($file);
            $extension      = $file->getClientOriginalExtension();
            $imageMimeTypes = ['jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'];
            if (in_array($extension, $imageMimeTypes)) {
                $compressedImagePath = storage_path('app/public/uploads/media-files/' . time() . '.' . $extension);
                compressImage($file, $compressedImagePath);
                $fileName = basename($compressedImagePath);
                $path     = 'uploads/media-files/' . $fileName;
            } else {
                $fileName = time() . '.' . $extension;
                $path     = Storage::disk('public')->put('uploads/media-files/' . $fileName, file_get_contents($file));
            }

            if ($path) {
                $mediaFile                  = new MediaFiles();
                $mediaFile->tbl_id          = $request->folder_id;
                $mediaFile->type            = 'content_upload';
                $mediaFile->attachment_file = $fileName;
                $mediaFile->original_name   = $file->getClientOriginalName();
                $mediaFile->file_extension  = $extension;
                $mediaFile->file_size       = $file->getSize();
                $mediaFile->mime_type       = $file->getMimeType();
                $mediaFile->uploaded_by     = Auth::id();
                $mediaFile->save();

                return $this->sendSuccess([], config('constants.API_MSG.REC_ADD_SUCCESS'));
            } else {
                return $this->sendError(config('constants.API_MSG.REC_ADD_FAILED'), 406);
            }
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }

    public function contentFolderDelete(Request $request)
    {
        try {
            $folder = MediaFolder::find($request->folder_id);
            if (! $folder) {
                return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'), 406);
            }

            $fileName = MediaFiles::where('tbl_id', $request->folder_id)->where('type', 'content_upload')->first();
            if ($fileName) {
                if (Storage::disk('public')->exists('uploads/media-files/' . $fileName->attachment_file)) {
                    Storage::disk('public')->delete('uploads/media-files/' . $fileName->attachment_file);
                }
                $fileName->delete();
            }
            $folder->delete();
            return $this->sendSuccess([$this->data], config('constants.API_MSG.REC_DELETE_SUCCESS'));
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }

    public function fileDelete(Request $request)
    {
        try {
            $file = MediaFiles::where('id', $request->file_id)->where('type', 'content_upload')->first();

            if ($file) {
                if (Storage::disk('public')->exists('uploads/media-files/' . $file->attachment_file)) {
                    Storage::disk('public')->delete('uploads/media-files/' . $file->attachment_file);
                }
                $file->delete();

                return $this->sendSuccess([], config('constants.API_MSG.REC_DELETE_SUCCESS'));
            } else {
                return $this->sendError(config('constants.API_MSG.REC_REMOVE_FAILED'), 406);
            }
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }

    public function showOnlineClasses(Request $request)
    {
        try {

            $role     = getUserRoles();
            $parentId = Auth::id();

            // If the role is "school_teacher", adjust parentId and use instructor_id for fetching classes
            if ($role == "school_teacher") {
                $parentId = Auth::user()->userAdditionalDetail->school_id;

                // Fetch assigned subjects and classes for the teacher
                $teacherAssignedSubjects = getTeacherAssignedSubjects(); // Returns an array of subject IDs
                $teacherAssignedClasses  = getTeacherAssignedClasses();  // Returns an array of class IDs

                // Fetch online classes based on instructor_id
                $this->data['ongoingClasses'] = OnlineClass::where('instructor_id', Auth::id())
                    ->where('status', 'ongoing')
                    ->with(['instructor', 'class', 'subject'])
                    ->get();

                $this->data['pastClasses'] = OnlineClass::where('instructor_id', Auth::id())
                    ->where('status', 'past')
                    ->with(['instructor', 'class', 'subject'])
                    ->get();

                $this->data['upcomingClasses'] = OnlineClass::where('instructor_id', Auth::id())
                    ->where('status', 'upcoming')
                    ->with(['instructor', 'class', 'subject'])
                    ->get();

                // Fetch classes and subjects assigned to the teacher
                $this->data['classes']  = SchoolClass::whereIn('id', $teacherAssignedClasses)->get();
                $this->data['subjects'] = Subject::whereIn('id', $teacherAssignedSubjects)->get();
            } else {
                // Fetch online classes based on parent_id for other roles
                $this->data['ongoingClasses'] = OnlineClass::where('parent_id', $parentId)
                    ->where('status', 'ongoing')
                    ->with(['instructor', 'class', 'subject'])
                    ->get();

                $this->data['pastClasses'] = OnlineClass::where('parent_id', $parentId)
                    ->where('status', 'past')
                    ->with(['instructor', 'class', 'subject'])
                    ->get();

                $this->data['upcomingClasses'] = OnlineClass::where('parent_id', $parentId)
                    ->where('status', 'upcoming')
                    ->with(['instructor', 'class', 'subject'])
                    ->get();

                // Fetch all classes and subjects for other roles
                $this->data['classes']  = SchoolClass::get();
                $this->data['subjects'] = Subject::get();
            }

            $this->data['teachers'] = User::where('created_by', Auth::id())->with('userAdditionalDetail')
                ->whereHas('userAdditionalDetail', function ($query) {
                    $query->where('role', 'school_teacher')
                        ->where('school_id', Auth::id());
                })->orderBy('created_at', 'DESC')->get();

            if ($this->data['ongoingClasses'] || $this->data['pastClasses'] || $this->data['upcomingClasses']) {
                return $this->sendSuccess([$this->data], config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            } else {
                return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'), 406);
            }
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }

    public function storeOnlineClass(OnlineClassAddRequest $request)
    {
        try {
            $role     = getUserRoles();
            $parentId = Auth::id();

            if ($role == "school_teacher") {
                $parentId = Auth::user()->userAdditionalDetail->school_id;
            }

            $classDate   = Carbon::parse($request->class_date)->format('Y-m-d');
            $onlineClass = OnlineClass::create([
                'parent_id'     => $parentId,
                'title'         => $request->title,
                'class_date'    => $classDate,
                'class_id'      => $request->class_id,
                'subject_id'    => $request->subject_id,
                'instructor_id' => $request->instructor_id ?? Auth::id(),
                'start_time'    => $request->start_time,
                'end_time'      => $request->end_time,
                'join_link'     => $request->join_link,
                'agenda'        => $request->agenda,
            ]);

            if ($onlineClass) {
                $this->sendNotification($request);
                return $this->sendSuccess([], config('constants.API_MSG.REC_ADD_SUCCESS'));
            } else {
                return $this->sendError(config('constants.API_MSG.REC_ADD_FAILED'), 406);
            }
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }
    public function sendNotification(Request $request)
    {
        $users = User::select('users.id', 'user_additional_details.school_id', 'student_details.class')
            ->join('user_additional_details', 'user_additional_details.user_id', '=', 'users.id')
            ->join('student_details', 'student_details.user_id', '=', 'users.id')
            ->where('users.status', 1)                               // Ensure the status is 1
            ->where('user_additional_details.school_id', Auth::id()) // Ensure the school_id matches Auth::id()
            ->where('student_details.class', $request->class_id)     // Ensure the class matches the requested class_id
            ->get();

        $notifications = [];
        foreach ($users as $user) {
            $notifications[] = [
                'type'            => 'online_class',
                'notifiable_type' => 'App\Models\User',
                'user_id'         => $user->id,
                'from_id'         => Auth::id(),
                'data'            => json_encode([
                    'title'      => $request->title,
                    'class_id'   => $request->class_id,
                    'class_date' => $request->class_date,
                    'start_time' => $request->start_time,
                    'end_time'   => $request->end_time,
                    'join_link'  => $request->join_link,
                ]),
                'created_at'      => now(),
                'updated_at'      => now(),
            ];
        }

        // Batch insert notifications
        DB::table('notifications')->insert($notifications);
    }
    public function onlineClassDetails(Request $request)
    {
        try {
            $this->data['data']          = OnlineClass::where('id', $request->online_class_id)->where('status', 'past')->with(['instructor', 'class', 'subject'])->get();
            $this->data['studyMaterial'] = MediaFiles::where('tbl_id', $request->online_class_id)->where('type', 'online_class_study_material')->get();

            if ($this->data['data'] || $this->data['studyMaterial']) {
                return $this->sendSuccess([$this->data], config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            } else {
                return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'), 406);
            }
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }

    public function storeOnlineClassStudyMaterial(OnlineClassStudyMaterialRequest $request)
    {
        try {

            $file           = $request->file('file');
            $extension      = $file->getClientOriginalExtension();
            $imageMimeTypes = ['jpg', 'jpeg', 'png', 'bmp', 'gif', 'svg'];
            if (in_array($extension, $imageMimeTypes)) {
                $compressedImagePath = storage_path('app/public/uploads/media-files/' . time() . '.' . $extension);
                // compressImage($file, $compressedImagePath);
                $fileName = basename($compressedImagePath);
                $path     = 'uploads/media-files/' . $fileName;
            } else {
                $fileName = time() . '.' . $extension;
                $path     = Storage::disk('public')->put('uploads/media-files/' . $fileName, file_get_contents($file));
            }

            if ($path) {
                $mediaFile                  = new MediaFiles();
                $mediaFile->tbl_id          = $request->online_class_id;
                $mediaFile->type            = 'online_class_study_material';
                $mediaFile->attachment_file = $fileName;
                $mediaFile->original_name   = $file->getClientOriginalName();
                $mediaFile->file_extension  = $extension;
                $mediaFile->file_size       = $file->getSize();
                $mediaFile->mime_type       = $file->getMimeType();
                $mediaFile->uploaded_by     = Auth::id();
                $mediaFile->save();

                return $this->sendSuccess([], config('constants.API_MSG.REC_ADD_SUCCESS'));
            } else {
                return $this->sendError(config('constants.API_MSG.REC_ADD_FAILED'));
            }
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }
    public function getPlanner(Request $request)
    {
        try {
            $parentId                = Auth::id();
            $role                    = getUserRoles();
            $schoolId                = Auth::id();
            $teacherAssignedClasses  = [];
            $teacherAssignedSubjects = [];
            $userBoard               = getUserBoard();
            $userMedium              = getUserMedium();

            // If the role is "school_teacher", set school_id and fetch assigned classes and subjects
            if ($role === "school_teacher") {
                $schoolId                = Auth::user()->userAdditionalDetail->school_id;
                $teacherAssignedClasses  = getTeacherAssignedClasses();
                $teacherAssignedSubjects = getTeacherAssignedSubjects();
            }

            // Get school assigned classes and digital content
            $schoolAssignedClasses = SchoolAssignedClass::where('school_id', $schoolId)->pluck('class_id')->toArray();
            //this is for the planner visibilty
            $existingPlannerVisibilty = SchoolPlannerVisibility::where('school_id', Auth::id())->get();


            // Get first to check if we have class_id in request
            $firstQuery = Planner::when($userBoard != 0, function ($query) use ($userBoard) {
                return $query->where('board_id', $userBoard);
            })
                ->when($userMedium != 0, function ($query) use ($userMedium) {
                    return $query->where('medium_id', $userMedium);
                })
                ->with(['class', 'subject', 'chapter'])
                ->when($role === "school_teacher", function ($query) use ($teacherAssignedClasses) {
                    if (!empty($teacherAssignedClasses)) {
                        $query->whereIn('class_id', $teacherAssignedClasses);
                    }
                })
                ->when($role === "school_admin", function ($query) use ($schoolAssignedClasses) {
                    $query->whereIn('class_id', $schoolAssignedClasses);
                })
                ->when($request->query('type') && $request->query('type') !== 'all', function ($query) use ($request) {
                    $query->where('type', $request->query('type'));
                })
                ->orderBy('class_id');

            // Get all classes that have planners (with filters applied)
            $classes = $firstQuery->get();
            if ($classes->isEmpty()) {
                return $this->sendSuccess([], config('constants.API_MSG.NO_RECORDS'));
            }

            $firstclass = $classes->first();
            $classId    = $request->query('class_id') ?? $firstclass->class_id;

            // Get school assigned digital content for this class
            $schoolAssignedDigitalContent = SchoolAssignedDigitalContent::where('school_id', $schoolId)
                ->where('class_id', $classId)
                ->first();

            $schoolAssignedDigitalContentAll = SchoolAssignedDigitalContent::where('school_id', $schoolId)
                ->whereNotNull('subject_id')
                ->get();
            // Initialize arrays
            $assignedSeriesClassSubjects = [];

            foreach ($schoolAssignedDigitalContentAll as $content) {
                $subjects = explode(',', $content->subject_id); // handle multiple subjects

                foreach ($subjects as $subjectId) {
                    $assignedSeriesClassSubjects[] = [
                        'class_id' => $content->class_id,
                        'series_id' => $content->series_id,
                        'subject_id' => (int) trim($subjectId),
                    ];
                }
            }
            $schoolAssignedDigitalContentSub = SchoolAssignedDigitalContent::where('school_id', $schoolId)
                ->where('class_id', $classId)
                ->get();
            $schoolAssignedSubjects = [];
            $schoolAssignedSeries = [];

            if ($schoolAssignedDigitalContent) {
                $schoolAssignedSeries = explode(',', $schoolAssignedDigitalContent->series_id);
            }
            // Get all unique subject IDs from all rows
            if ($schoolAssignedDigitalContentSub) {
                foreach ($schoolAssignedDigitalContentSub as $content) {
                    if (!empty($content->subject_id)) {
                        $subjectsInRow = explode(',', $content->subject_id);
                        $schoolAssignedSubjects = array_merge($schoolAssignedSubjects, $subjectsInRow);
                    }
                }
            }
            // Remove duplicates and re-index the array
            $schoolAssignedSubjects = array_values(array_unique($schoolAssignedSubjects));
            // Base query for planner type detection
            $getPlannerTypeQuery = Planner::when($userBoard != 0, function ($query) use ($userBoard) {
                return $query->where('board_id', $userBoard);
            })
                ->when($userMedium != 0, function ($query) use ($userMedium) {
                    return $query->where('medium_id', $userMedium);
                })
                ->where('class_id', $classId)
                ->when($request->query('type') && $request->query('type') !== 'all', function ($query) use ($request) {
                    $query->where('type', $request->query('type'));
                });

            $getPlannerType = $getPlannerTypeQuery->first();
            $plannerType = $getPlannerType ? $getPlannerType->type : null;

            if ($plannerType == null) {
                return $this->sendSuccess([], config('constants.API_MSG.NO_RECORDS'));
            }
            // View Daily Planner
            if ($plannerType == 'daily') {
                $plannerDatesQuery = Planner::where(function ($query) use ($schoolId, $role, $teacherAssignedClasses, $teacherAssignedSubjects, $schoolAssignedClasses, $schoolAssignedSubjects, $schoolAssignedSeries) {
                    // School-specific planners
                    $query->where('school_id', $schoolId);

                    // Or universal planners that match school's assigned subjects and series
                    if (!empty($schoolAssignedSubjects)) {
                        $query->orWhere(function ($q) use ($schoolAssignedSubjects, $schoolAssignedSeries) {
                            $q->whereNull('school_id')
                                ->whereIn('subject_id', $schoolAssignedSubjects);

                            if (!empty($schoolAssignedSeries)) {
                                $q->whereIn('series_id', $schoolAssignedSeries);
                            }
                        });
                    }

                    // If teacher, filter by assigned classes and subjects
                    if ($role === "school_teacher") {
                        if (!empty($teacherAssignedClasses)) {
                            $query->whereIn('class_id', $teacherAssignedClasses);
                        }
                        if (!empty($teacherAssignedSubjects)) {
                            $query->whereIn('subject_id', $teacherAssignedSubjects);
                        }
                    }
                    if ($role === "school_admin") {
                        if (!empty($schoolAssignedClasses)) {
                            $query->whereIn('class_id', $schoolAssignedClasses);
                        }
                        if (!empty($schoolAssignedSubjects)) {
                            $query->whereIn('subject_id', $schoolAssignedSubjects);
                        }
                    }
                })
                    ->where('class_id', $classId)
                    ->when($userBoard != 0, function ($query) use ($userBoard) {
                        return $query->where('board_id', $userBoard);
                    })
                    ->when($userMedium != 0, function ($query) use ($userMedium) {
                        return $query->where('medium_id', $userMedium);
                    });

                $plannerDates = $plannerDatesQuery->selectRaw('MIN(start_date) as start_date, MAX(completion_date) as completion_date')
                    ->first();

                // If no planner dates are found, fallback to the current month's start and end date
                $startDate = $plannerDates ? $plannerDates->start_date : now()->startOfMonth()->format('Y-m-d');
                $endDate   = $plannerDates ? $plannerDates->completion_date : now()->endOfMonth()->format('Y-m-d');

                $totalPlannerDays = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate));
                $currentDate      = now();

                // Weekday names for the header
                $weekDays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

                // Get all dates between start and end date, excluding Sundays
                $allDates = [];
                $current  = new DateTime($startDate);
                $end      = new DateTime($endDate);

                while ($current <= $end) {
                    if ($current->format('w') != 0) { // Exclude Sunday (0 corresponds to Sunday)
                        $allDates[] = clone $current;     // Add non-Sunday date
                    }
                    $current->modify('+1 day');
                }

                $totalDays = count($allDates); // Total number of non-Sunday days

                // Fetch planner data with school-specific filters
                $plannerDataQuery = Planner::with(['class', 'subject', 'chapter'])
                    ->where(function ($query) use ($schoolId, $role, $teacherAssignedClasses, $teacherAssignedSubjects, $schoolAssignedClasses, $schoolAssignedSubjects, $schoolAssignedSeries) {
                        // School-specific planners
                        $query->where('school_id', $schoolId);

                        // Or universal planners that match school's assigned subjects and series
                        if (!empty($schoolAssignedSubjects)) {
                            $query->orWhere(function ($q) use ($schoolAssignedSubjects, $schoolAssignedSeries) {
                                $q->whereNull('school_id')
                                    ->whereIn('subject_id', $schoolAssignedSubjects);

                                if (!empty($schoolAssignedSeries)) {
                                    $q->whereIn('series_id', $schoolAssignedSeries);
                                }
                            });
                        }

                        // If teacher, filter by assigned classes and subjects
                        if ($role === "school_teacher") {
                            if (!empty($teacherAssignedClasses)) {
                                $query->whereIn('class_id', $teacherAssignedClasses);
                            }
                            if (!empty($teacherAssignedSubjects)) {
                                $query->whereIn('subject_id', $teacherAssignedSubjects);
                            }
                        }
                        if ($role === "school_admin") {
                            if (!empty($schoolAssignedClasses)) {
                                $query->whereIn('class_id', $schoolAssignedClasses);
                            }
                            if (!empty($schoolAssignedSubjects)) {
                                $query->whereIn('subject_id', $schoolAssignedSubjects);
                            }
                        }
                    })
                    ->where('class_id', $classId)
                    ->when($userBoard != 0, function ($query) use ($userBoard) {
                        return $query->where('board_id', $userBoard);
                    })
                    ->when($userMedium != 0, function ($query) use ($userMedium) {
                        return $query->where('medium_id', $userMedium);
                    })
                    ->whereBetween('start_date', [$startDate, $endDate]);

                $plannerData = $plannerDataQuery->get();

                // Fetch planner_offs data for the authenticated school
                $plannerOffs = PlannerOff::whereHas('planner', function ($query) use ($schoolId, $role, $teacherAssignedClasses, $schoolAssignedClasses) {
                    $query->where('school_id', $schoolId)
                        ->orWhereNull('school_id'); // Include universal planners' holidays if needed

                    // If teacher, filter by assigned classes
                    if ($role === "school_teacher" && !empty($teacherAssignedClasses)) {
                        $query->whereIn('class_id', $teacherAssignedClasses);
                    }
                    if ($role === "school_admin" && !empty($schoolAssignedClasses)) {
                        $query->whereIn('class_id', $schoolAssignedClasses);
                    }
                })->pluck('date')->toArray();

                // Process planner data into day-wise structure (same as before)
                $dayWiseData = [];
                foreach ($plannerData as $item) {
                    $plannedDate    = new DateTime($item->start_date);
                    $completionDate = new DateTime($item->completion_date);

                    for ($i = 0; $i < $item->allotted_days; $i++) {
                        $boxDate          = (clone $plannedDate)->modify("+$i days");
                        $boxDateFormatted = $boxDate->format('Y-m-d');

                        if ($boxDate->format('w') != 0 && !in_array($boxDateFormatted, $plannerOffs)) { // Exclude Sunday and holiday dates
                            $day = array_search($boxDateFormatted, array_map(fn($date) => $date->format('Y-m-d'), $allDates));

                            if ($day !== false) {
                                $day += 1; // Convert to 1-based index for Blade usage

                                $boxClass = 'shiftBox';
                                if ($currentDate >= $boxDate && $currentDate <= $completionDate) {
                                    $boxClass .= ' lightgreen';
                                } elseif ($currentDate < $boxDate) {
                                    $boxClass .= ' lightorange';
                                } else {
                                    $boxClass .= ' lightred';
                                }

                                $existingChapters = $dayWiseData[$day][$item->subject_id] ?? [];
                                $chapterExists    = collect($existingChapters)->contains('chapter_id', $item->chapter_id);
                                $title            = $item->chapter->chapter_name ?? 'No Chapter Name';

                                if (!$chapterExists) {
                                    $dayWiseData[$day]['planner_list'][] = [
                                        'class_id'   => $item->class_id,
                                        'subject_id' => $item->subject_id,
                                        'chapter_id' => $item->chapter_id,
                                        'title'      => $title,
                                        'class'      => $boxClass,
                                    ];
                                }
                            }
                        }
                    }
                }

                // Fetch subjects with school-specific filters
                $subjectsQuery = Planner::select('class_id', 'subject_id')
                    ->with('subject')->distinct()
                    ->where(function ($query) use ($schoolId, $role, $teacherAssignedClasses, $teacherAssignedSubjects, $schoolAssignedClasses, $schoolAssignedSubjects, $schoolAssignedSeries) {
                        // School-specific planners
                        $query->where('school_id', $schoolId);

                        // Or universal planners that match school's assigned subjects and series
                        if (!empty($schoolAssignedSubjects)) {
                            $query->orWhere(function ($q) use ($schoolAssignedSubjects, $schoolAssignedSeries) {
                                $q->whereNull('school_id')
                                    ->whereIn('subject_id', $schoolAssignedSubjects);

                                if (!empty($schoolAssignedSeries)) {
                                    $q->whereIn('series_id', $schoolAssignedSeries);
                                }
                            });
                        }

                        // If teacher, filter by assigned classes and subjects
                        if ($role === "school_teacher") {
                            if (!empty($teacherAssignedClasses)) {
                                $query->whereIn('class_id', $teacherAssignedClasses);
                            }
                            if (!empty($teacherAssignedSubjects)) {
                                $query->whereIn('subject_id', $teacherAssignedSubjects);
                            }
                        }
                        if ($role === "school_admin") {
                            if (!empty($schoolAssignedClasses)) {
                                $query->whereIn('class_id', $schoolAssignedClasses);
                            }
                            if (!empty($schoolAssignedSubjects)) {
                                $query->whereIn('subject_id', $schoolAssignedSubjects);
                            }
                        }
                    })
                    ->when($userBoard != 0, function ($query) use ($userBoard) {
                        return $query->where('board_id', $userBoard);
                    })
                    ->when($userMedium != 0, function ($query) use ($userMedium) {
                        return $query->where('medium_id', $userMedium);
                    })
                    ->where('class_id', $classId);

                $subjects = $subjectsQuery->get();

                return $this->sendSuccess(compact('plannerType', 'schoolId', 'classes', 'totalPlannerDays', 'dayWiseData', 'subjects', 'weekDays', 'allDates', 'totalDays', 'startDate', 'endDate'), config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            } elseif ($plannerType == 'weekly') {
                // Get planner data grouped by week with school-specific filters
                $plannerDatesQuery = Planner::when($userBoard != 0, function ($query) use ($userBoard) {
                    return $query->where('board_id', $userBoard);
                })
                    ->when($userMedium != 0, function ($query) use ($userMedium) {
                        return $query->where('medium_id', $userMedium);
                    })
                    ->where(function ($query) use ($schoolId, $role, $teacherAssignedClasses, $teacherAssignedSubjects, $schoolAssignedClasses, $schoolAssignedSubjects, $schoolAssignedSeries) {
                        // School-specific planners
                        $query->where('school_id', $schoolId);

                        // Or universal planners that match school's assigned subjects and series
                        if (!empty($schoolAssignedSubjects)) {
                            $query->orWhere(function ($q) use ($schoolAssignedSubjects, $schoolAssignedSeries) {
                                $q->whereNull('school_id')
                                    ->whereIn('subject_id', $schoolAssignedSubjects);

                                if (!empty($schoolAssignedSeries)) {
                                    $q->whereIn('series_id', $schoolAssignedSeries);
                                }
                            });
                        }

                        // If teacher, filter by assigned classes and subjects
                        if ($role === "school_teacher") {
                            if (!empty($teacherAssignedClasses)) {
                                $query->whereIn('class_id', $teacherAssignedClasses);
                            }
                            if (!empty($teacherAssignedSubjects)) {
                                $query->whereIn('subject_id', $teacherAssignedSubjects);
                            }
                        }
                        if ($role === "school_admin") {
                            if (!empty($schoolAssignedClasses)) {
                                $query->whereIn('class_id', $schoolAssignedClasses);
                            }
                            if (!empty($schoolAssignedSubjects)) {
                                $query->whereIn('subject_id', $schoolAssignedSubjects);
                            }
                        }
                    })
                    ->where('class_id', $classId);

                $plannerDates = $plannerDatesQuery->selectRaw('MIN(start_date) as start_date, MAX(completion_date) as completion_date')
                    ->first();

                // Define start and end dates
                $startDate = $plannerDates ? $plannerDates->start_date : now()->startOfMonth()->format('Y-m-d');
                $endDate   = $plannerDates ? $plannerDates->completion_date : now()->endOfMonth()->format('Y-m-d');

                // Get weekly breakdown
                $weeks       = [];
                $currentDate = Carbon::parse($startDate);
                while ($currentDate <= Carbon::parse($endDate)) {
                    $weekNumber = $currentDate->copy()->startOfWeek()->format('W');
                    if (!isset($weeks[$weekNumber])) {
                        $weeks[$weekNumber] = [
                            'start' => $currentDate->copy()->startOfWeek()->format('Y-m-d'),
                            'end'   => $currentDate->copy()->endOfWeek()->format('Y-m-d'),
                        ];
                    }
                    $currentDate->addWeek();
                }

                // Fetch planner data with school-specific filters
                $plannerDataQuery = Planner::with(['class', 'subject', 'chapter'])
                    ->where('class_id', $classId)
                    ->when($userBoard != 0, function ($query) use ($userBoard) {
                        return $query->where('board_id', $userBoard);
                    })
                    ->when($userMedium != 0, function ($query) use ($userMedium) {
                        return $query->where('medium_id', $userMedium);
                    })
                    ->where(function ($query) use ($schoolId, $role, $teacherAssignedClasses, $teacherAssignedSubjects, $schoolAssignedClasses, $schoolAssignedSubjects, $schoolAssignedSeries) {
                        // School-specific planners
                        $query->where('school_id', $schoolId);

                        // Or universal planners that match school's assigned subjects and series
                        if (!empty($schoolAssignedSubjects)) {
                            $query->orWhere(function ($q) use ($schoolAssignedSubjects, $schoolAssignedSeries) {
                                $q->whereNull('school_id')
                                    ->whereIn('subject_id', $schoolAssignedSubjects);

                                if (!empty($schoolAssignedSeries)) {
                                    $q->whereIn('series_id', $schoolAssignedSeries);
                                }
                            });
                        }

                        // If teacher, filter by assigned classes and subjects
                        if ($role === "school_teacher") {
                            if (!empty($teacherAssignedClasses)) {
                                $query->whereIn('class_id', $teacherAssignedClasses);
                            }
                            if (!empty($teacherAssignedSubjects)) {
                                $query->whereIn('subject_id', $teacherAssignedSubjects);
                            }
                        }
                        if ($role === "school_admin") {
                            if (!empty($schoolAssignedClasses)) {
                                $query->whereIn('class_id', $schoolAssignedClasses);
                            }
                            if (!empty($schoolAssignedSubjects)) {
                                $query->whereIn('subject_id', $schoolAssignedSubjects);
                            }
                        }
                    })
                    ->whereBetween('start_date', [$startDate, $endDate]);

                $plannerData = $plannerDataQuery->get();

                // Organize data into weeks
                $weekWiseData = [];
                foreach ($plannerData as $item) {
                    $chapterIds = explode(',', $item->chapter_id);                                                   // Convert string to array
                    $chapters   = CourseChapter::whereIn('id', $chapterIds)->pluck('chapter_name', 'id')->toArray(); // Get all chapter names

                    $startOfWeek = Carbon::parse($item->start_date)->startOfWeek()->format('W');
                    $subjectName = $item->subject->name ?? 'No Subject';

                    $weekWiseData[$startOfWeek][$item->subject_id][] = [
                        'palnner_id' => $item->palnner_id,
                        'subject_id' => $item->subject_id,
                        'subject'    => $subjectName,
                        'chapter_id' => $chapterIds, // Store array of IDs
                        'titles'     => $chapters,   // Store an array of chapter names
                        'class'      => 'shiftBox',
                    ];
                }

                // Fetch subjects with school-specific filters
                $subjectsQuery = Planner::select('class_id', 'subject_id')
                    ->with('subject')->distinct()
                    ->where('class_id', $classId)
                    ->when($userBoard != 0, function ($query) use ($userBoard) {
                        return $query->where('board_id', $userBoard);
                    })
                    ->when($userMedium != 0, function ($query) use ($userMedium) {
                        return $query->where('medium_id', $userMedium);
                    })
                    ->where(function ($query) use ($schoolId, $role, $teacherAssignedClasses, $teacherAssignedSubjects, $schoolAssignedClasses, $schoolAssignedSubjects, $schoolAssignedSeries) {
                        // School-specific planners
                        $query->where('school_id', $schoolId);

                        // Or universal planners that match school's assigned subjects and series
                        if (!empty($schoolAssignedSubjects)) {
                            $query->orWhere(function ($q) use ($schoolAssignedSubjects, $schoolAssignedSeries) {
                                $q->whereNull('school_id')
                                    ->whereIn('subject_id', $schoolAssignedSubjects);

                                if (!empty($schoolAssignedSeries)) {
                                    $q->whereIn('series_id', $schoolAssignedSeries);
                                }
                            });
                        }

                        // If teacher, filter by assigned classes and subjects
                        if ($role === "school_teacher") {
                            if (!empty($teacherAssignedClasses)) {
                                $query->whereIn('class_id', $teacherAssignedClasses);
                            }
                            if (!empty($teacherAssignedSubjects)) {
                                $query->whereIn('subject_id', $teacherAssignedSubjects);
                            }
                        }
                        if ($role === "school_admin") {
                            if (!empty($schoolAssignedClasses)) {
                                $query->whereIn('class_id', $schoolAssignedClasses);
                            }
                            if (!empty($schoolAssignedSubjects)) {
                                $query->whereIn('subject_id', $schoolAssignedSubjects);
                            }
                        }
                    });

                $subjects = $subjectsQuery->get();

                return $this->sendSuccess(compact('plannerType', 'schoolId', 'classes', 'weeks', 'weekWiseData', 'subjects'), config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            } elseif ($plannerType == 'monthly') {
                $startOfMonth = now()->startOfMonth()->format('Y-m-d');
                $endOfMonth   = now()->endOfMonth()->format('Y-m-d');

                // Fetch all classes with monthly planners with school-specific filters
                $classesWithMonthlyPlannersQuery = Planner::with(['class', 'subject', 'chapter'])
                    ->when($userBoard != 0, function ($query) use ($userBoard) {
                        return $query->where('board_id', $userBoard);
                    })
                    ->when($userMedium != 0, function ($query) use ($userMedium) {
                        return $query->where('medium_id', $userMedium);
                    })
                    ->where(function ($query) use ($startOfMonth, $endOfMonth) {
                        $query->whereBetween('start_date', [$startOfMonth, $endOfMonth])
                            ->orWhereBetween('completion_date', [$startOfMonth, $endOfMonth])
                            ->orWhere(function ($query) use ($startOfMonth, $endOfMonth) {
                                $query->where('start_date', '<', $startOfMonth)
                                    ->where('completion_date', '>', $endOfMonth);
                            });
                    })
                    ->where('type', 'monthly')
                    ->where(function ($query) use ($assignedSeriesClassSubjects) {
                        foreach ($assignedSeriesClassSubjects as $assigned) {
                            $query->orWhere(function ($subQuery) use ($assigned) {
                                $subQuery->where('class_id', $assigned['class_id'])
                                    ->where('series_id', $assigned['series_id'])
                                    ->where('subject_id', $assigned['subject_id']);
                            });
                        }
                    });

                $classesWithMonthlyPlanners = $classesWithMonthlyPlannersQuery->get();

                // Group the data by class
                $classPlannerData = [];
                foreach ($classesWithMonthlyPlanners as $planner) {
                    $classId     = $planner->class_id;
                    $subjectName = $planner->subject->name ?? 'No Subject';
                    $chapterIds  = explode(',', $planner->chapter_id);                                                // Convert string to array
                    $chapters    = CourseChapter::whereIn('id', $chapterIds)->pluck('chapter_name', 'id')->toArray(); // Get all chapter names

                    // Structure the data for display
                    $classPlannerData[$classId][] = [
                        'subject_id'      => $planner->subject_id,
                        'subject'         => $subjectName,
                        'chapter_id'      => $chapterIds, // Store array of IDs
                        'titles'          => $chapters,   // Store an array of chapter names
                        'planner_id'      => $planner->id,
                        'start_date'      => $planner->start_date,
                        'completion_date' => $planner->completion_date,
                    ];
                }
                return $this->sendSuccess(compact('plannerType', 'classes', 'classPlannerData', 'startOfMonth', 'endOfMonth'), config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            }
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }
    public function getPlannerOLD(Request $request)
    {
        try {
            $role                    = getUserRoles();
            $schoolId                = Auth::id();
            $teacherAssignedClasses  = [];
            $teacherAssignedSubjects = [];
            $userBoard               = getUserBoard();
            $userMedium              = getUserMedium();

            // If the role is "school_teacher", set school_id and fetch assigned classes and subjects
            if ($role === "school_teacher") {
                $schoolId                = Auth::user()->userAdditionalDetail->school_id;
                $teacherAssignedClasses  = getTeacherAssignedClasses();
                $teacherAssignedSubjects = getTeacherAssignedSubjects();
            }

            $schoolAssignedClasses = SchoolAssignedClass::where('school_id', $schoolId)->pluck('class_id')->toArray();

            // Get school assigned digital content for class filtering
            $schoolAssignedDigitalContent = SchoolAssignedDigitalContent::where('school_id', $schoolId)->first();
            $schoolAssignedSubjects = [];
            $schoolAssignedSeries = [];

            if ($schoolAssignedDigitalContent) {
                $schoolAssignedSubjects = explode(',', $schoolAssignedDigitalContent->subject_id);
                $schoolAssignedSeries = explode(',', $schoolAssignedDigitalContent->series_id);
            }

            $classes = Planner::when($userBoard != 0, function ($query) use ($userBoard) {
                return $query->where('board_id', $userBoard);
            })
                ->when($userMedium != 0, function ($query) use ($userMedium) {
                    return $query->where('medium_id', $userMedium);
                })->with(['class', 'subject', 'chapter'])
                ->when($role === "school_teacher", function ($query) use ($teacherAssignedClasses) {
                    if (! empty($teacherAssignedClasses)) {
                        $query->whereIn('class_id', $teacherAssignedClasses);
                    }
                })
                ->when($role === "school_admin", function ($query) use ($schoolAssignedClasses) {
                    $query->whereIn('class_id', $schoolAssignedClasses);
                })->when($request->query('type') && $request->query('type') !== 'all', function ($query) use ($request) {
                    $query->where('type', $request->query('type'));
                })->orderBy('class_id')->get();

            if ($classes->isEmpty()) {
                return $this->sendSuccess([], config('constants.API_MSG.NO_RECORDS'));
            }

            $firstclass = $classes->first();
            $classId    = $request->class_id ?? $firstclass->class_id;

            // Get specific digital content for the selected class
            $classDigitalContent = SchoolAssignedDigitalContent::where('school_id', $schoolId)
                ->where('class_id', $classId)
                ->first();

            $classAssignedSubjects = [];
            $classAssignedSeries = [];

            if ($classDigitalContent) {
                $classAssignedSubjects = explode(',', $classDigitalContent->subject_id);
                $classAssignedSeries = explode(',', $classDigitalContent->series_id);
            }

            // Fetch the first planner and filter by type if provided
            $getPlannerTypeQuery = Planner::when($userBoard != 0, function ($query) use ($userBoard) {
                return $query->where('board_id', $userBoard);
            })
                ->when($userMedium != 0, function ($query) use ($userMedium) {
                    return $query->where('medium_id', $userMedium);
                })
                ->where('class_id', $classId)
                ->where(function ($query) use ($schoolId, $classAssignedSubjects, $classAssignedSeries) {
                    // School-specific planners
                    $query->where('school_id', $schoolId);

                    // Or universal planners that match school's assigned subjects and series
                    if (!empty($classAssignedSubjects)) {
                        $query->orWhere(function ($q) use ($classAssignedSubjects, $classAssignedSeries) {
                            $q->whereNull('school_id')
                                ->whereIn('subject_id', $classAssignedSubjects);

                            if (!empty($classAssignedSeries)) {
                                $q->whereIn('series_id', $classAssignedSeries);
                            }
                        });
                    }
                })
                ->when($request->query('type') && $request->query('type') !== 'all', function ($query) use ($request) {
                    $query->where('type', $request->query('type'));
                });

            $getPlannerType = $getPlannerTypeQuery->first();
            $plannerType = $getPlannerType ? $getPlannerType->type : null;

            if ($plannerType == null) {
                return $this->sendSuccess([], config('constants.API_MSG.NO_RECORDS'));
            }
            // View Daily Planner
            if ($plannerType == 'daily') {
                $plannerDatesQuery = Planner::where(function ($query) use ($schoolId, $role, $teacherAssignedClasses, $schoolAssignedClasses, $classAssignedSubjects, $classAssignedSeries) {
                    // School-specific planners
                    $query->where('school_id', $schoolId);

                    // Or universal planners that match school's assigned subjects and series
                    if (!empty($classAssignedSubjects)) {
                        $query->orWhere(function ($q) use ($classAssignedSubjects, $classAssignedSeries) {
                            $q->whereNull('school_id')
                                ->whereIn('subject_id', $classAssignedSubjects);

                            if (!empty($classAssignedSeries)) {
                                $q->whereIn('series_id', $classAssignedSeries);
                            }
                        });
                    }

                    // Role-based filtering
                    if ($role === "school_teacher" && !empty($teacherAssignedClasses)) {
                        $query->whereIn('class_id', $teacherAssignedClasses);
                    }
                    if ($role === "school_admin" && !empty($schoolAssignedClasses)) {
                        $query->whereIn('class_id', $schoolAssignedClasses);
                    }
                })
                    ->where('class_id', $classId)
                    ->when($userBoard != 0, function ($query) use ($userBoard) {
                        return $query->where('board_id', $userBoard);
                    })
                    ->when($userMedium != 0, function ($query) use ($userMedium) {
                        return $query->where('medium_id', $userMedium);
                    });

                $plannerDates = $plannerDatesQuery->selectRaw('MIN(start_date) as start_date, MAX(completion_date) as completion_date')
                    ->first();

                // If no planner dates are found, fallback to the current month's start and end date
                $startDate = $plannerDates ? $plannerDates->start_date : now()->startOfMonth()->format('Y-m-d');
                $endDate   = $plannerDates ? $plannerDates->completion_date : now()->endOfMonth()->format('Y-m-d');

                $totalPlannerDays = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate));
                $currentDate      = now();

                // Weekday names for the header
                $weekDays = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

                // Get all dates between start and end date, excluding Sundays
                $allDates = [];
                $current  = new DateTime($startDate);
                $end      = new DateTime($endDate);

                while ($current <= $end) {
                    if ($current->format('w') != 0) { // Exclude Sunday (0 corresponds to Sunday)
                        $allDates[] = clone $current;     // Add non-Sunday date
                    }
                    $current->modify('+1 day');
                }

                $totalDays = count($allDates); // Total number of non-Sunday days

                // Fetch planner data with school-specific filters
                $plannerDataQuery = Planner::with(['class', 'subject', 'chapter'])
                    ->where(function ($query) use ($schoolId, $role, $teacherAssignedClasses, $teacherAssignedSubjects, $schoolAssignedClasses, $classAssignedSubjects, $classAssignedSeries) {
                        // School-specific planners
                        $query->where('school_id', $schoolId);

                        // Or universal planners that match school's assigned subjects and series
                        if (!empty($classAssignedSubjects)) {
                            $query->orWhere(function ($q) use ($classAssignedSubjects, $classAssignedSeries) {
                                $q->whereNull('school_id')
                                    ->whereIn('subject_id', $classAssignedSubjects);

                                if (!empty($classAssignedSeries)) {
                                    $q->whereIn('series_id', $classAssignedSeries);
                                }
                            });
                        }

                        // Role-based filtering
                        if ($role === "school_teacher") {
                            if (!empty($teacherAssignedClasses)) {
                                $query->whereIn('class_id', $teacherAssignedClasses);
                            }
                            if (!empty($teacherAssignedSubjects)) {
                                $query->whereIn('subject_id', $teacherAssignedSubjects);
                            }
                        }
                        if ($role === "school_admin") {
                            if (!empty($schoolAssignedClasses)) {
                                $query->whereIn('class_id', $schoolAssignedClasses);
                            }
                            if (!empty($classAssignedSubjects)) {
                                $query->whereIn('subject_id', $classAssignedSubjects);
                            }
                        }
                    })
                    ->where('class_id', $classId)
                    ->when($userBoard != 0, function ($query) use ($userBoard) {
                        return $query->where('board_id', $userBoard);
                    })
                    ->when($userMedium != 0, function ($query) use ($userMedium) {
                        return $query->where('medium_id', $userMedium);
                    })
                    ->whereBetween('start_date', [$startDate, $endDate]);

                $plannerData = $plannerDataQuery->get();

                // Fetch planner_offs data for the authenticated school
                $plannerOffs = PlannerOff::whereHas('planner', function ($query) use ($schoolId, $role, $teacherAssignedClasses, $schoolAssignedClasses) {
                    $query->where('school_id', $schoolId)
                        ->orWhereNull('school_id'); // Include universal planners' holidays if needed

                    // Role-based filtering
                    if ($role === "school_teacher" && !empty($teacherAssignedClasses)) {
                        $query->whereIn('class_id', $teacherAssignedClasses);
                    }
                    if ($role === "school_admin" && !empty($schoolAssignedClasses)) {
                        $query->whereIn('class_id', $schoolAssignedClasses);
                    }
                })->pluck('date')->toArray();

                // Process planner data into day-wise structure
                $dayWiseData = [];
                foreach ($plannerData as $item) {
                    $plannedDate    = new DateTime($item->start_date);
                    $completionDate = new DateTime($item->completion_date);

                    for ($i = 0; $i < $item->allotted_days; $i++) {
                        $boxDate          = (clone $plannedDate)->modify("+$i days");
                        $boxDateFormatted = $boxDate->format('Y-m-d');

                        if ($boxDate->format('w') != 0 && !in_array($boxDateFormatted, $plannerOffs)) { // Exclude Sunday and holiday dates
                            $day = array_search($boxDateFormatted, array_map(fn($date) => $date->format('Y-m-d'), $allDates));

                            if ($day !== false) {
                                $day += 1; // Convert to 1-based index for Blade usage

                                $boxClass = 'shiftBox';
                                if ($currentDate >= $boxDate && $currentDate <= $completionDate) {
                                    $boxClass .= ' lightgreen';
                                } elseif ($currentDate < $boxDate) {
                                    $boxClass .= ' lightorange';
                                } else {
                                    $boxClass .= ' lightred';
                                }

                                $existingChapters = $dayWiseData[$day][$item->subject_id] ?? [];
                                $chapterExists    = collect($existingChapters)->contains('chapter_id', $item->chapter_id);
                                $title            = $item->chapter->chapter_name ?? 'No Chapter Name';

                                if (!$chapterExists) {
                                    $dayWiseData[$day]['planner_list'][] = [
                                        'class_id'   => $item->class_id,
                                        'subject_id' => $item->subject_id,
                                        'chapter_id' => $item->chapter_id,
                                        'title'      => $title,
                                        'class'      => $boxClass,
                                    ];
                                }
                            }
                        }
                    }
                }

                // Fetch subjects with school-specific filters
                $subjectsQuery = Planner::select('class_id', 'subject_id')
                    ->with('subject')->distinct()
                    ->where(function ($query) use ($schoolId, $role, $teacherAssignedClasses, $teacherAssignedSubjects, $schoolAssignedClasses, $classAssignedSubjects, $classAssignedSeries) {
                        // School-specific planners
                        $query->where('school_id', $schoolId);

                        // Or universal planners that match school's assigned subjects and series
                        if (!empty($classAssignedSubjects)) {
                            $query->orWhere(function ($q) use ($classAssignedSubjects, $classAssignedSeries) {
                                $q->whereNull('school_id')
                                    ->whereIn('subject_id', $classAssignedSubjects);

                                if (!empty($classAssignedSeries)) {
                                    $q->whereIn('series_id', $classAssignedSeries);
                                }
                            });
                        }

                        // Role-based filtering
                        if ($role === "school_teacher") {
                            if (!empty($teacherAssignedClasses)) {
                                $query->whereIn('class_id', $teacherAssignedClasses);
                            }
                            if (!empty($teacherAssignedSubjects)) {
                                $query->whereIn('subject_id', $teacherAssignedSubjects);
                            }
                        }
                        if ($role === "school_admin") {
                            if (!empty($schoolAssignedClasses)) {
                                $query->whereIn('class_id', $schoolAssignedClasses);
                            }
                            if (!empty($classAssignedSubjects)) {
                                $query->whereIn('subject_id', $classAssignedSubjects);
                            }
                        }
                    })
                    ->when($userBoard != 0, function ($query) use ($userBoard) {
                        return $query->where('board_id', $userBoard);
                    })
                    ->when($userMedium != 0, function ($query) use ($userMedium) {
                        return $query->where('medium_id', $userMedium);
                    })
                    ->where('class_id', $classId);

                $subjects = $subjectsQuery->get();

                return $this->sendSuccess(compact('plannerType', 'schoolId', 'classes', 'totalPlannerDays', 'dayWiseData', 'subjects', 'weekDays', 'allDates', 'totalDays', 'startDate', 'endDate'), config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            } elseif ($plannerType == 'weekly') {
                // Get planner data grouped by week with school-specific filters
                $plannerDatesQuery = Planner::when($userBoard != 0, function ($query) use ($userBoard) {
                    return $query->where('board_id', $userBoard);
                })
                    ->when($userMedium != 0, function ($query) use ($userMedium) {
                        return $query->where('medium_id', $userMedium);
                    })
                    ->where(function ($query) use ($schoolId, $classAssignedSubjects, $classAssignedSeries) {
                        // School-specific planners
                        $query->where('school_id', $schoolId);

                        // Or universal planners that match school's assigned subjects and series
                        if (!empty($classAssignedSubjects)) {
                            $query->orWhere(function ($q) use ($classAssignedSubjects, $classAssignedSeries) {
                                $q->whereNull('school_id')
                                    ->whereIn('subject_id', $classAssignedSubjects);

                                if (!empty($classAssignedSeries)) {
                                    $q->whereIn('series_id', $classAssignedSeries);
                                }
                            });
                        }
                    })
                    ->where('class_id', $classId);

                $plannerDates = $plannerDatesQuery->selectRaw('MIN(start_date) as start_date, MAX(completion_date) as completion_date')
                    ->first();

                // Define start and end dates
                $startDate = $plannerDates ? $plannerDates->start_date : now()->startOfMonth()->format('Y-m-d');
                $endDate   = $plannerDates ? $plannerDates->completion_date : now()->endOfMonth()->format('Y-m-d');

                // Get weekly breakdown
                $weeks       = [];
                $currentDate = Carbon::parse($startDate);
                while ($currentDate <= Carbon::parse($endDate)) {
                    $weekNumber = $currentDate->copy()->startOfWeek()->format('W');
                    if (!isset($weeks[$weekNumber])) {
                        $weeks[$weekNumber] = [
                            'start' => $currentDate->copy()->startOfWeek()->format('Y-m-d'),
                            'end'   => $currentDate->copy()->endOfWeek()->format('Y-m-d'),
                        ];
                    }
                    $currentDate->addWeek();
                }

                // Fetch planner data with school-specific filters
                $plannerDataQuery = Planner::with(['class', 'subject', 'chapter'])
                    ->where('class_id', $classId)
                    ->when($userBoard != 0, function ($query) use ($userBoard) {
                        return $query->where('board_id', $userBoard);
                    })
                    ->when($userMedium != 0, function ($query) use ($userMedium) {
                        return $query->where('medium_id', $userMedium);
                    })
                    ->where(function ($query) use ($schoolId, $role, $teacherAssignedClasses, $teacherAssignedSubjects, $schoolAssignedClasses, $classAssignedSubjects, $classAssignedSeries) {
                        // School-specific planners
                        $query->where('school_id', $schoolId);

                        // Or universal planners that match school's assigned subjects and series
                        if (!empty($classAssignedSubjects)) {
                            $query->orWhere(function ($q) use ($classAssignedSubjects, $classAssignedSeries) {
                                $q->whereNull('school_id')
                                    ->whereIn('subject_id', $classAssignedSubjects);

                                if (!empty($classAssignedSeries)) {
                                    $q->whereIn('series_id', $classAssignedSeries);
                                }
                            });
                        }

                        // Role-based filtering
                        if ($role === "school_teacher") {
                            if (!empty($teacherAssignedClasses)) {
                                $query->whereIn('class_id', $teacherAssignedClasses);
                            }
                            if (!empty($teacherAssignedSubjects)) {
                                $query->whereIn('subject_id', $teacherAssignedSubjects);
                            }
                        }
                        if ($role === "school_admin") {
                            if (!empty($schoolAssignedClasses)) {
                                $query->whereIn('class_id', $schoolAssignedClasses);
                            }
                            if (!empty($classAssignedSubjects)) {
                                $query->whereIn('subject_id', $classAssignedSubjects);
                            }
                        }
                    })
                    ->whereBetween('start_date', [$startDate, $endDate]);

                $plannerData = $plannerDataQuery->get();

                // Organize data into weeks
                $weekWiseData = [];
                foreach ($plannerData as $item) {
                    $chapterIds = explode(',', $item->chapter_id);                                                   // Convert string to array
                    $chapters   = CourseChapter::whereIn('id', $chapterIds)->pluck('chapter_name', 'id')->toArray(); // Get all chapter names

                    $startOfWeek = Carbon::parse($item->start_date)->startOfWeek()->format('W');
                    $subjectName = $item->subject->name ?? 'No Subject';

                    $weekWiseData[$startOfWeek][$item->subject_id][] = [
                        'palnner_id' => $item->palnner_id,
                        'subject_id' => $item->subject_id,
                        'subject'    => $subjectName,
                        'chapter_id' => $chapterIds, // Store array of IDs
                        'titles'     => $chapters,   // Store an array of chapter names
                        'class'      => 'shiftBox',
                    ];
                }

                // Fetch subjects with school-specific filters
                $subjectsQuery = Planner::select('class_id', 'subject_id')
                    ->with('subject')->distinct()
                    ->where('class_id', $classId)
                    ->when($userBoard != 0, function ($query) use ($userBoard) {
                        return $query->where('board_id', $userBoard);
                    })
                    ->when($userMedium != 0, function ($query) use ($userMedium) {
                        return $query->where('medium_id', $userMedium);
                    })
                    ->where(function ($query) use ($schoolId, $role, $teacherAssignedClasses, $teacherAssignedSubjects, $schoolAssignedClasses, $classAssignedSubjects, $classAssignedSeries) {
                        // School-specific planners
                        $query->where('school_id', $schoolId);

                        // Or universal planners that match school's assigned subjects and series
                        if (!empty($classAssignedSubjects)) {
                            $query->orWhere(function ($q) use ($classAssignedSubjects, $classAssignedSeries) {
                                $q->whereNull('school_id')
                                    ->whereIn('subject_id', $classAssignedSubjects);

                                if (!empty($classAssignedSeries)) {
                                    $q->whereIn('series_id', $classAssignedSeries);
                                }
                            });
                        }

                        // Role-based filtering
                        if ($role === "school_teacher") {
                            if (!empty($teacherAssignedClasses)) {
                                $query->whereIn('class_id', $teacherAssignedClasses);
                            }
                            if (!empty($teacherAssignedSubjects)) {
                                $query->whereIn('subject_id', $teacherAssignedSubjects);
                            }
                        }
                        if ($role === "school_admin") {
                            if (!empty($schoolAssignedClasses)) {
                                $query->whereIn('class_id', $schoolAssignedClasses);
                            }
                            if (!empty($classAssignedSubjects)) {
                                $query->whereIn('subject_id', $classAssignedSubjects);
                            }
                        }
                    });

                $subjects = $subjectsQuery->get();

                return $this->sendSuccess(compact('plannerType', 'schoolId', 'classes', 'weeks', 'weekWiseData', 'subjects'), config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            } elseif ($plannerType == 'monthly') {
                $startOfMonth = now()->startOfMonth()->format('Y-m-d');
                $endOfMonth   = now()->endOfMonth()->format('Y-m-d');

                // Fetch all classes with monthly planners with school-specific filters
                $classesWithMonthlyPlannersQuery = Planner::with(['class', 'subject', 'chapter'])
                    ->where(function ($query) use ($schoolId, $role, $teacherAssignedClasses, $schoolAssignedClasses, $classAssignedSubjects, $classAssignedSeries) {
                        // School-specific planners
                        $query->where('school_id', $schoolId);

                        // Or universal planners that match school's assigned subjects and series
                        if (!empty($classAssignedSubjects)) {
                            $query->orWhere(function ($q) use ($classAssignedSubjects, $classAssignedSeries) {
                                $q->whereNull('school_id')
                                    ->whereIn('subject_id', $classAssignedSubjects);

                                if (!empty($classAssignedSeries)) {
                                    $q->whereIn('series_id', $classAssignedSeries);
                                }
                            });
                        }

                        // Role-based filtering
                        if ($role === "school_teacher" && !empty($teacherAssignedClasses)) {
                            $query->whereIn('class_id', $teacherAssignedClasses);
                        }
                        if ($role === "school_admin" && !empty($schoolAssignedClasses)) {
                            $query->whereIn('class_id', $schoolAssignedClasses);
                        }
                    })
                    ->where('type', 'monthly') // Filter only monthly planners
                    ->when($userBoard != 0, function ($query) use ($userBoard) {
                        return $query->where('board_id', $userBoard);
                    })
                    ->when($userMedium != 0, function ($query) use ($userMedium) {
                        return $query->where('medium_id', $userMedium);
                    })
                    ->where(function ($query) use ($startOfMonth, $endOfMonth) {
                        $query->whereBetween('start_date', [$startOfMonth, $endOfMonth])  // Starts in current month
                            ->orWhereBetween('completion_date', [$startOfMonth, $endOfMonth]) // Completes in current month
                            ->orWhere(function ($query) use ($startOfMonth, $endOfMonth) {
                                // Spans across the month (started before and ends after)
                                $query->where('start_date', '<', $startOfMonth)
                                    ->where('completion_date', '>', $endOfMonth);
                            });
                    });

                $classesWithMonthlyPlanners = $classesWithMonthlyPlannersQuery->get();

                // Group the data by class
                $classPlannerData = [];
                foreach ($classesWithMonthlyPlanners as $planner) {
                    $classId     = $planner->class_id;
                    $subjectName = $planner->subject->name ?? 'No Subject';
                    $chapterIds  = explode(',', $planner->chapter_id);                                                // Convert string to array
                    $chapters    = CourseChapter::whereIn('id', $chapterIds)->pluck('chapter_name', 'id')->toArray(); // Get all chapter names

                    // Structure the data for display
                    $classPlannerData[$classId][] = [
                        'subject_id'      => $planner->subject_id,
                        'subject'         => $subjectName,
                        'chapter_id'      => $chapterIds, // Store array of IDs
                        'titles'          => $chapters,   // Store an array of chapter names
                        'planner_id'      => $planner->id,
                        'start_date'      => $planner->start_date,
                        'completion_date' => $planner->completion_date,
                    ];
                }
                return $this->sendSuccess(compact('plannerType', 'classes', 'classPlannerData', 'startOfMonth', 'endOfMonth'), config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            }
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }

    public function markHoliday(Request $request)
    {
        try {
            $holidayDate = $request->get('holiday_date');
            $schoolId    = Auth::id();

            // $dayIndex = $request->get('day_index');

            $planners = Planner::whereNull('school_id')->get();
            // Step 3: Create new planner rows for each `school_id`
            foreach ($planners as $planner) {
                // Adjust the planner's start and end dates to exclude the holiday
                if ($planner->start_date <= $holidayDate && $planner->completion_date >= $holidayDate) {
                    // Calculate new start and end dates after skipping the holiday
                    $newStartDate = Carbon::parse($planner->start_date)->addDay()->format('Y-m-d');
                    $newEndDate   = Carbon::parse($planner->completion_date)->addDay()->format('Y-m-d');

                    // Create a new planner record for the specific school
                    $newPlanner                  = new Planner();
                    $newPlanner->school_id       = $schoolId; // Assign the new school_id
                    $newPlanner->board_id        = $planner->board_id;
                    $newPlanner->medium_id       = $planner->medium_id;
                    $newPlanner->series_id       = $planner->series_id;
                    $newPlanner->class_id        = $planner->class_id;
                    $newPlanner->subject_id      = $planner->subject_id;
                    $newPlanner->chapter_id      = $planner->chapter_id;
                    $newPlanner->allotted_days   = $planner->allotted_days;
                    $newPlanner->start_date      = $newStartDate;
                    $newPlanner->completion_date = $newEndDate;
                    $newPlanner->total_periods   = $planner->total_periods;

                    // Save the new planner row
                    $newPlanner->save();

                    $plannerOff             = new PlannerOff();
                    $plannerOff->planner_id = $newPlanner->id; // Reference the new planner ID
                    $plannerOff->date       = $holidayDate;
                    $plannerOff->save();
                }
            }

            if ($plannerOff) {
                return $this->sendSuccess([], config('constants.API_MSG.REC_UPDATE_SUCCESS'));
            } else {
                return $this->sendError(config('constants.API_MSG.REC_UPDATE_FAILED'));
            }
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }

    public function chapterDetails(Request $request)
    {
        try {
            $plannerLesson = Planner::whereRaw("FIND_IN_SET(?, chapter_id)", [$request->chapter_id])->with('details', 'class', 'subject', 'board', 'medium', 'series')->first();

            // Group details by type
            $groupedDetails = $plannerLesson->details->groupBy('type');

            $this->data['groupedDetails'] = $groupedDetails;
            $this->data['plannerLesson']  = $plannerLesson;
            $this->data['digitalContent'] = CourseChapter::with('chapters', 'folder', 'documents')->where('id', $request->chapter_id)->first();
            $this->data['supportingFiles'] = MediaFiles::where('tbl_id', $request->chapter_id)
                ->where('type', 'course_chapter_extra')
                ->get()
                ->map(function ($file) {
                    $file->file_url = asset('storage/uploads/course_chapter_files/' . $file->attachment_file);
                    return $file;
                });

            if ($this->data) {
                return $this->sendSuccess($this->data, config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            } else {
                return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'));
            }
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }

    public function lessonPlanner()
    {
        try {
            $role                   = getUserRoles();
            $parentId               = Auth::id();
            $teacherAssignedClasses = [];

            if ($role == "school_teacher") {
                $parentId               = Auth::user()->userAdditionalDetail->school_id;
                $teacherAssignedClasses = getTeacherAssignedClasses();
            }

            if (config('COURSES_FILTER_BY_ACCESS_CODE') == 1) {
                $query = AccessCode::with('class')
                    ->select('class_id')
                    ->where('school_id', $parentId)
                    ->groupBy('class_id');

                // Filter by teacher's assigned classes if the role is "school_teacher"
                if ($role === 'school_teacher' && ! empty($teacherAssignedClasses)) {
                    $query->whereIn('class_id', $teacherAssignedClasses);
                }
            } else {
                $query = SchoolAssignedClass::with('class')->where('school_id', $parentId)->select('class_id')->groupBy('class_id');

                // Filter by teacher's assigned classes if the role is "school_teacher"
                if ($role === 'school_teacher' && ! empty($teacherAssignedClasses)) {
                    $query->whereIn('class_id', $teacherAssignedClasses);
                }
            }

            $this->data['classCourses'] = $query->get();
            if ($this->data) {
                return $this->sendSuccess($this->data, config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            } else {
                return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'));
            }
        } catch (\Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }



    public function lessonPlannerClassSubject(Request $request)
    {

        try {
            $role                    = getUserRoles();
            $parentId                = Auth::id();
            $teacherAssignedSubjects = [];
            $classId               = $request->class_id;

            // If the role is "school_teacher", get school_id and assigned subjects
            if ($role == "school_teacher") {
                $parentId                = Auth::user()->userAdditionalDetail->school_id;
                $teacherAssignedSubjects = getTeacherAssignedSubjects();
            }
            $schoolAssignedDigitalContent = SchoolAssignedDigitalContent::where('school_id', $parentId)->where('class_id', $classId)->get();
            $allSubjectIds = [];

            // Loop through each row of digital content and extract the subject ids
            foreach ($schoolAssignedDigitalContent as $digitalContent) {
                // Merge the subject ids (comma separated) into the array
                $allSubjectIds = array_merge($allSubjectIds, explode(',', $digitalContent->subject_id));
            }

            // Get unique subject ids (removes duplicates)
            $uniqueSubjectIds = array_unique($allSubjectIds);

            // Return the unique subject ids (without indexed array)
            $schoolAssignedSubjects = array_values($uniqueSubjectIds);

            $this->data['classId'] = $request->class_id;

            if (config('COURSES_FILTER_BY_ACCESS_CODE') == 1) {
                $this->data['accessCodes'] = AccessCode::where('school_id', $parentId)->where('class_id', $classId)->get();
                $subjectIds                = [];

                foreach ($this->data['accessCodes'] as $code) {
                    $subjectIds = array_merge($subjectIds, explode(',', $code->subject_id));
                }

                // Filter subjects based on the teacher's assigned subjects if the role is "school_teacher"
                $subjectQuery = Subject::whereIn('id', $subjectIds);

                if ($role == "school_teacher" && ! empty($teacherAssignedSubjects)) {
                    $subjectQuery->whereIn('id', $teacherAssignedSubjects);
                }

                $this->data['subjects']             = $subjectQuery->get();
                $this->data['totalAccessCodes']     = AccessCode::where('school_id', $parentId)->where('class_id', $classId)->count();
                $this->data['unUsedAccessCodes']    = AccessCode::where('school_id', $parentId)->where('class_id', $classId)->where('user_id', null)->count();
                $this->data['occcupiedAccessCodes'] = AccessCode::where('school_id', $parentId)->where('class_id', $classId)->whereNotNull('user_id')->count();
                $this->data['remainingAccessCodes'] = $this->data['totalAccessCodes'] - $this->data['occcupiedAccessCodes'];
                $this->data['redeemedAccessCode']   = AccessCode::where('school_id', $parentId)->where('class_id', $classId)->whereNotNull('user_id')->with('usedAccessCodes', 'accessCodeLog')->get();
                $this->data['unRedeemedAccessCode'] = AccessCode::where('school_id', $parentId)->where('class_id', $classId)->where('user_id', null)->with('usedAccessCodes', 'accessCodeLog')->get();

                $this->data['users'] = User::with(['userAdditionalDetail', 'studentDetails'])
                    ->whereHas('userAdditionalDetail', function ($query) use ($parentId) {
                        $query->where('role', 'school_student')
                            ->where('school_id', $parentId);
                    })->whereHas('studentDetails', function ($query) use ($classId) {
                        $query->where('class', $classId);
                    })->whereDoesntHave('accessCodes')->get();
            } else {
                $subjectIds = Course::where('category_id', 1)
                    ->whereHas('metadataValues', function ($query) use ($classId) {
                        $query->where('field_name', 'class')->where('field_value', $classId);
                    })
                    ->with(['metadataValues' => function ($query) {
                        $query->where('field_name', 'subject');
                    }])
                    ->get()
                    ->pluck('metadataValues')
                    ->flatten()
                    ->where('field_name', 'subject')
                    ->pluck('field_value')
                    ->unique()
                    ->values();

                if (! empty($schoolAssignedSubjects)) {
                    $subjectQuery = Subject::query();
                    if ($role == "school_admin") {
                        $subjectQuery->whereIn('id', $schoolAssignedSubjects);
                    } elseif ($role == "school_teacher" && ! empty($teacherAssignedSubjects)) {
                        $commonAssignedSubjects = array_intersect($schoolAssignedSubjects, $teacherAssignedSubjects);
                        $subjectQuery->whereIn('id', $commonAssignedSubjects);
                    }
                    $this->data['subjects'] = $subjectQuery->get();
                } else {
                    $this->data['subjects'] = collect([]);
                }
            }
            // dd($this->data);
            if (! $this->data['subjects']->isEmpty()) {
                return $this->sendSuccess($this->data, config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            } else {
                return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'), 406);
            }
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }
    public function lessonPlannerCourseListing(Request $request)
    {
        try {
            $class_id  = $request->class_id;
            $subjectId = $request->subject_id;

            $role     = getUserRoles();
            $board    = getUserBoard();
            $medium   = getUserMedium();
            $parentId = Auth::id();

            if ($role == "school_teacher") {
                $parentId = Auth::user()->userAdditionalDetail->school_id;
            }
            $assignedContent = SchoolAssignedDigitalContent::where('school_id', $parentId)
                ->where('class_id', $class_id)
                ->get(['series_id', 'subject_id']);

            $seriesSubjectPairs = [];

            foreach ($assignedContent as $item) {
                $seriesId = $item->series_id;
                $subjects = explode(',', $item->subject_id);

                foreach ($subjects as $subject) {
                    $subject = trim($subject);
                    if ($subject !== '') {
                        $seriesSubjectPairs[] = [
                            'series_id' => $seriesId,
                            'subject_id' => $subject,
                        ];
                    }
                }
            }
            // Base course query
            $query = Course::where('is_active', 1)
                ->with([
                    'totalChapters',
                    'metadataValues' => function ($query) {
                        $query->select('course_id', 'field_name', 'field_value');
                    },
                    'metadataValues.classInfo',
                ])
                ->where('category_id', 1)
                ->whereHas('metadataValues', function ($q) use ($class_id) {
                    $q->where('field_name', 'class')->where('field_value', $class_id);
                });

            // Apply subject filter if subject_id is passed
            // if ($subjectId) {
            //     $query->whereHas('metadataValues', function ($q) use ($subjectId) {
            //         $q->where('field_name', 'subject')->where('field_value', $subjectId);
            //     });
            // }
            if ($subjectId) {
                $seriesId = SchoolAssignedDigitalContent::where('school_id', $parentId)
                    ->where('class_id', $class_id)
                    ->whereRaw("FIND_IN_SET(?, subject_id)", [$subjectId])
                    ->pluck('series_id')
                    ->first();

                $query->whereHas('metadataValues', function ($q) use ($subjectId) {
                    $q->where('field_name', 'subject')->where('field_value', $subjectId);
                })->whereHas('metadataValues', function ($q) use ($seriesId) {
                    $q->where('field_name', 'series')->where('field_value', $seriesId);
                });
            } else {
                // If subject is not passed, filter by each (series, subject) pair
                $query->where(function ($subQuery) use ($seriesSubjectPairs) {
                    foreach ($seriesSubjectPairs as $pair) {
                        $subQuery->orWhere(function ($innerQuery) use ($pair) {
                            $innerQuery->whereHas('metadataValues', function ($q) use ($pair) {
                                $q->where('field_name', 'series')->where('field_value', $pair['series_id']);
                            })->whereHas('metadataValues', function ($q) use ($pair) {
                                $q->where('field_name', 'subject')->where('field_value', $pair['subject_id']);
                            });
                        });
                    }
                });
            }
            // Optional board filter
            // if ($board != 0) {
            //     $query->whereHas('metadataValues', function ($query) use ($board) {
            //         $query->where('field_name', 'board')->where('field_value', $board);
            //     });
            // }

            // // Optional medium filter
            // if ($medium != 0) {
            //     $query->whereHas('metadataValues', function ($query) use ($medium) {
            //         $query->where('field_name', 'medium')->where('field_value', $medium);
            //     });
            // }

            $this->data['courseListing'] = $query->get();

            // Count Access Codes using subjectId only if it exists
            if ($subjectId) {
                $this->data['totalAccessCodes']  = AccessCode::where('school_id', Auth::id())->where('class_id', $subjectId)->count();
                $this->data['unUsedAccessCodes'] = AccessCode::where('school_id', Auth::id())->where('class_id', $subjectId)->whereNull('user_id')->count();
            } else {
                $this->data['totalAccessCodes'] = 0;
                $this->data['unUsedAccessCodes'] = 0;
            }

            $this->data['id']       = $subjectId;
            $this->data['class_id'] = $class_id;
            $this->data['subjectName'] = $subjectId ? Subject::where('id', $subjectId)->where('is_active', 1)->value('name') : 'All Subjects';
            $this->data['className']   = SchoolClass::where('id', $class_id)->where('is_active', 1)->value('name');

            if ($this->data) {
                return $this->sendSuccess($this->data, config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            } else {
                return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'));
            }
        } catch (\Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }

    public function lessonPlannerCourseListingWithSubjectSpecificOLD(Request $request)
    {
        try {
            $class_id   = $request->class_id;
            $subjectId = $request->subject_id;

            $role     = getUserRoles();
            $board    = getUserBoard();
            $medium   = getUserMedium();
            $parentId = Auth::id();

            if ($role == "school_teacher") {
                $parentId = Auth::user()->userAdditionalDetail->school_id;
            }

            // $schoolAssignedSeries = SchoolAssignedDigitalContent::where('school_id', $parentId)
            //     ->where('class_id', $class_id)
            //     ->value('series_id');
            $schoolAssignedSeries = SchoolAssignedDigitalContent::where('school_id', $parentId)
                ->where('class_id', $class_id)
                ->whereRaw("FIND_IN_SET(?, subject_id)", [$subjectId])
                ->pluck('series_id')
                ->first();

            $query = Course::where('is_active', 1)
                ->with([
                    'totalChapters',
                    'metadataValues' => function ($query) {
                        $query->select('course_id', 'field_name', 'field_value');
                    },
                    'metadataValues.classInfo',
                ])
                ->where('category_id', 1)
                ->whereHas('metadataValues', function ($query) use ($schoolAssignedSeries) {
                    $query->where('field_name', 'series')->where('field_value', $schoolAssignedSeries);
                })
                ->whereHas('metadataValues', function ($query) use ($subjectId) {
                    $query->where('field_name', 'subject')->where('field_value', $subjectId);
                })
                ->whereHas('metadataValues', function ($query) use ($class_id) {
                    $query->where('field_name', 'class')->where('field_value', $class_id);
                });

            // Apply board filter only if board is not 0
            // if ($board != 0) {
            //     $query->whereHas('metadataValues', function ($query) use ($board) {
            //         $query->where('field_name', 'board')->where('field_value', $board);
            //     });
            // }

            // // Apply medium filter only if medium is not 0
            // if ($medium != 0) {
            //     $query->whereHas('metadataValues', function ($query) use ($medium) {
            //         $query->where('field_name', 'medium')->where('field_value', $medium);
            //     });
            // }

            $this->data['courseListing'] = $query->get();

            $this->data['totalAccessCodes']  = AccessCode::where('school_id', Auth::id())->where('class_id', $subjectId)->count();
            $this->data['unUsedAccessCodes'] = AccessCode::where('school_id', Auth::id())->where('class_id', $subjectId)->where('user_id', null)->count();
            $this->data['id']                = $subjectId;
            $this->data['class_id']          = $class_id;
            $this->data['subjectName'] = Subject::where('id', $subjectId)->where('is_active', 1)->value('name');
            $this->data['className'] = SchoolClass::where('id', $class_id)->where('is_active', 1)->value('name');


            if ($this->data) {
                return $this->sendSuccess($this->data, config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            } else {
                return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'));
            }
        } catch (\Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }

    public function lessonPlannerChapterDetails(Request $request)
    {
        try {
            $plannerLesson = Planner::where('chapter_id', $request->chapter_id)->with('details', 'class', 'subject', 'board', 'medium', 'series')->first();

            $groupedDetails               = $plannerLesson->details->groupBy('type');
            $this->data['groupedDetails'] = $groupedDetails;
            $this->data['plannerLesson']  = $plannerLesson;
            if ($this->data['plannerLesson']->isEmpty()) {
                return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'));
            }
            $this->data['digitalContent']  = CourseChapter::with('chapters', 'folder', 'documents')->where('id', $request->chapter_id)->first();
            $this->data['supportingFiles'] = MediaFiles::where('tbl_id', $request->chapter_id)->where('type', 'course_chapter_extra')->get();

            if ($this->data) {
                return $this->sendSuccess($this->data, config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            } else {
                return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'));
            }
        } catch (\Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }

    private function calculateChangePercentage($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        return (($current - $previous) / $previous) * 100;
    }

    public function teacherAssignedClasses()
    {
        try {
            $classId               = getTeacherAssignedClasses();
            $this->data['classes'] = Classes::whereIn('id', $classId)->get();
            if ($this->data) {
                return $this->sendSuccess($this->data, config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            } else {
                return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'));
            }
        } catch (\Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }

    public function teacherAssignedSubjects()
    {
        try {
            $subjectIds             = getTeacherAssignedSubjects();
            $this->data['subjects'] = Subject::whereIn('id', $subjectIds)->get();
            if ($this->data) {
                return $this->sendSuccess($this->data, config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            } else {
                return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'));
            }
        } catch (\Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }

    public function teacherDetails(Request $request)
    {
        try {
            $user            = User::where('id', Auth::id())->get();
            $additional_data = UserAdditionalDetail::where('user_id', Auth::id())->get();
            if (! $user->isEmpty()) {
                return $this->sendSuccess(compact('additional_data', 'user'), config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            } else {
                return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'), 406);
            }
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }

    public function teacherDetailsUpdate(TeacherDetailUpdateRequest $request)
    {
        try {
            $user = auth()->user();
            $filePath = null; // Initialize to avoid "undefined" error

            if ($request->hasFile('profile_image')) {
                // Delete old image if exists
                if ($user && $user->image && Storage::disk('public')->exists('uploads/user/profile_image/' . $user->image)) {
                    Storage::disk('public')->delete('uploads/user/profile_image/' . $user->image);
                }

                $profileImage = $request->file('profile_image');
                $extension    = $profileImage->getClientOriginalExtension();
                $fileName     = time() . '.' . $extension;
                $filePath     = 'uploads/user/profile_image/' . $fileName;
                Storage::disk('public')->put($filePath, file_get_contents($profileImage));
                $user->image = $fileName;
            }

            $user->save();

            $teacherDetails   = UserAdditionalDetail::where('user_id', $user->id)->first();
            $userTableDetails = User::where('id', $user->id)->first();
            if (! $teacherDetails) {
                return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'), 406);
            }
            if (! $userTableDetails) {
                return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'), 406);
            }
            if ($teacherDetails) {
                $teacherDetails->last_name  = $request->last_name;
                $teacherDetails->age        = $request->age;
                $teacherDetails->experience = $request->experience;
                $teacherDetails->state      = $request->state;
                $teacherDetails->city       = $request->city;
                $teacherDetails->address    = $request->address;
                $teacherDetails->save();
            }
            if ($userTableDetails) {
                $userTableDetails->name = $request->name;
                $userTableDetails->save();
            }

            return $this->sendSuccess(['filePath' => asset('storage/' . $filePath)], config('constants.API_MSG.REC_UPDATE_SUCCESS'));
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }

    public function teacherDashboard(Request $request)
    {
        try {
            $role = getUserRoles();
            // $parentId = Auth::id();
            // if ($role == "school_teacher") {
            $parentId = Auth::user()->userAdditionalDetail->school_id;
            // }
            $this->data['students'] = User::with(['userAdditionalDetail'])
                ->whereHas('userAdditionalDetail', function ($query) use ($parentId) {
                    $query->where('role', 'school_student')
                        ->where('school_id', $parentId);
                })->count();

            $this->data['plannedClasses'] = OnlineClass::where('parent_id', $parentId)
                ->with(['class', 'instructor', 'subject'])
                ->get();

            $teacherAssignedClasses  = getTeacherAssignedClasses();
            $teacherAssignedSubjects = getTeacherAssignedSubjects();

            // For get daily planner
            $plannerDates = Planner::whereIn('class_id', $teacherAssignedClasses)
                ->whereIn('subject_id', $teacherAssignedSubjects)
                ->where('school_id', $parentId)
                ->orWhereNull('school_id') // Include universal planners if needed
                ->selectRaw('MIN(start_date) as start_date, MAX(completion_date) as completion_date')
                ->first();

            // If no planner dates are found, fallback to the current month's start and end date
            $startDate = $plannerDates ? $plannerDates->start_date : now()->startOfMonth()->format('Y-m-d');
            $endDate   = $plannerDates ? $plannerDates->completion_date : now()->endOfMonth()->format('Y-m-d');

            $this->data['totalPlannerDays'] = Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate));

            $currentDate = now();

            // Weekday names for the header
            $this->data['weekDays'] = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

            // Get all dates between start and end date, excluding Sundays
            $this->data['allDates'] = [];
            $current                = new DateTime($startDate);
            $end                    = new DateTime($endDate);

            while ($current <= $end) {
                if ($current->format('w') != 0) {           // Exclude Sunday (0 corresponds to Sunday)
                    $this->data['allDates'][] = clone $current; // Add non-Sunday date
                }
                $current->modify('+1 day');
            }

            $this->data['totalDays'] = count($this->data['allDates']); // Total number of non-Sunday days

            // Fetch planner data
            $plannerData = Planner::with(['class', 'subject', 'chapter'])
                ->whereIn('class_id', $teacherAssignedClasses)
                ->whereIn('subject_id', $teacherAssignedSubjects)
                ->where(function ($query) use ($parentId) {
                    $query->where('school_id', $parentId)
                        ->orWhereNull('school_id'); // Include universal planners if no school-specific data
                })
                ->whereBetween('start_date', [$startDate, $endDate])
                ->get();

            // Fetch planner_offs data for the authenticated school
            $plannerOffs = PlannerOff::whereHas('planner', function ($query) use ($parentId, $teacherAssignedClasses, $teacherAssignedSubjects) {
                $query->whereIn('class_id', $teacherAssignedClasses)
                    ->whereIn('subject_id', $teacherAssignedSubjects)
                    ->where('school_id', $parentId)
                    ->orWhereNull('school_id'); // Include universal planners' holidays if needed
            })->pluck('date')->toArray();

            // Process planner data into day-wise structure
            $dayWiseData = [];
            foreach ($plannerData as $item) {
                $plannedDate    = new DateTime($item->start_date);
                $completionDate = new DateTime($item->completion_date);

                for ($i = 0; $i < $item->allotted_days; $i++) {
                    $boxDate          = (clone $plannedDate)->modify("+$i days");
                    $boxDateFormatted = $boxDate->format('Y-m-d');

                    if ($boxDate->format('w') != 0 && ! in_array($boxDateFormatted, $plannerOffs)) { // Exclude Sunday and holiday dates
                        // Map boxDate to its day index in allDates
                        $day = array_search($boxDateFormatted, array_map(fn($date) => $date->format('Y-m-d'), $this->data['allDates']));

                        if ($day !== false) { // Ensure the date is valid
                            $day += 1;            // Convert to 1-based index for Blade usage

                            // Determine class for the shiftBox
                            $boxClass = 'shiftBox';
                            if ($currentDate >= $boxDate && $currentDate <= $completionDate) {
                                $boxClass .= ' lightgreen';
                            } elseif ($currentDate < $boxDate) {
                                $boxClass .= ' lightorange';
                            } else {
                                $boxClass .= ' lightred'; // Overdue
                            }

                            // Ensure that the chapter doesn't already exist for this day and subject
                            $existingChapters = $dayWiseData[$day][$item->subject_id] ?? [];
                            $duplicate        = false;

                            foreach ($existingChapters as $existingChapter) {
                                if ($existingChapter['chapter_id'] == $item->chapter_id) {
                                    $duplicate = true; // Found duplicate, break the loop
                                    break;
                                }
                            }

                            // Only add if no duplicate found
                            if (! $duplicate) {
                                $title = $item->chapter->chapter_name ?? 'No Chapter Name';

                                // Add chapter to the dayWiseData
                                $dayWiseData[$day][$item->subject_id][] = [
                                    'chapter_id' => $item->chapter_id,
                                    'title'      => $title,
                                    'class'      => $boxClass,
                                ];
                            }
                        }
                    }
                }
            }

            // Fetch subjects
            $this->data['subjects'] = Planner::select('class_id', 'subject_id')
                ->with('subject')
                ->whereIn('class_id', $teacherAssignedClasses)
                ->whereIn('subject_id', $teacherAssignedSubjects)
                ->distinct()
                ->get();

            $this->data['classes'] = Planner::with(['class', 'subject', 'chapter'])
                ->whereIn('class_id', $teacherAssignedClasses)
                ->whereIn('subject_id', $teacherAssignedSubjects)
                ->get();

            $this->data['chartData'] = $this->getClassWiseStudentCountChartData(); // Get the chart data

            return $this->sendSuccess($this->data, config('constants.API_MSG.REC_FETCHED_SUCCESS'));
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }
    public function getClassWiseStudentCountChartData()
    {
        $parentId               = Auth::user()->userAdditionalDetail->school_id;
        $teacherAssignedClasses = getTeacherAssignedClasses();

        $studentCountsByClass = DB::table('student_details')
            ->select('student_details.class', 'classes.name as class_name', DB::raw('COUNT(*) as count'))
            ->join('user_additional_details', 'student_details.user_id', '=', 'user_additional_details.user_id') // Join with user_additional_details
            ->join('classes', 'student_details.class', '=', 'classes.id')
            ->where('user_additional_details.school_id', $parentId)
            ->whereIn('student_details.class', $teacherAssignedClasses)
            ->groupBy('student_details.class', 'classes.name')
            ->get();

        $chartData = $studentCountsByClass->map(function ($studentCount) {
            $color = in_array($studentCount->class, getTeacherAssignedClasses()) ? '#61F51D' : '#EC7172';
            return [
                'class_name'    => $studentCount->class_name,
                'student_count' => (int) $studentCount->count,
            ];
        });

        return $chartData->toArray();
    }

    public function getAlertAndMarketingBanner()
    {
        try {
            $userRole = Auth::user()->role->role_slug;

            $notificationAlerts = NotificationAlert::where('is_active', 1)
                ->where(function ($query) use ($userRole) {
                    $query->where('role_visibility', 'LIKE', "%{$userRole}%");
                })
                ->first();
            if ($notificationAlerts) {
                return $this->sendSuccess(compact('notificationAlerts'), config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            }
            return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'),  406);
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }


    public function createMediaGallery(Request $request)
    {
        try {
            $request->validate([
                'gallery_name' => 'required',
                'available_to_users' => 'required',
                'event_name' => 'required',
                'event_name' => 'required',
            ]);

            $maxFileSize = config('constants.MAX_FILE_SIZE');

            $request->validate([
                'media_file' => "required|file|mimes:jpg,jpeg,png,svg,doc,docx,xls,xlsx,pdf,mp3,wav,mp4,mov,avi|max:$maxFileSize",
            ]);
            $maxFileSize = config('constants.MAX_FILE_SIZE');

            $res = MediaGallery::updateOrCreate(['id' => $request->id], ['parent_id' => Auth::id(), 'gallery_name' => $request->gallery_name, 'available_to_users' => $request->available_to_users, 'event_name' => $request->event_name, 'media_link' => $request->media_link, 'description' => $request->description, 'validity_date' => $request->validity_date]);

            if ($request->hasFile('media_file')) {

                $file           = $request->file('media_file');
                $extension      = $file->getClientOriginalExtension();
                $fileName = time() . '.' . $extension;
                $path     = Storage::disk('public')->put('uploads/media-gallery/' . $fileName, file_get_contents($file));

                if ($path) {
                    $mediaFile                  = new MediaFiles();
                    $mediaFile->tbl_id          = $res->id;
                    $mediaFile->type            = 'school_media_gallery';
                    $mediaFile->attachment_file = $fileName;
                    $mediaFile->original_name   = $file->getClientOriginalName();
                    $mediaFile->file_extension  = $extension;
                    $mediaFile->file_size       = $file->getSize();
                    $mediaFile->mime_type       = $file->getMimeType();
                    $mediaFile->uploaded_by     = Auth::id();
                    $mediaFile->save();
                }
            }
            if ($mediaFile) {
                return $this->sendSuccess([], config('constants.API_MSG.REC_ADD_SUCCESS'));
            }
            return $this->sendError(config('constants.API_MSG.REC_ADD_FAILED'), 406);
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }

    public function mediaGalleryList(Request $request)
    {
        try {
            $role                   = getUserRoles();
            $parentId               = Auth::id();

            if ($role == "school_teacher") {
                $parentId               = Auth::user()->userAdditionalDetail->school_id;
                $this->data['mediaGallery'] = MediaGallery::where('parent_id', $parentId)->whereIn('available_to_users', ['all', 'teachers'])->get();
            } else {
                $this->data['mediaGallery'] = MediaGallery::where('parent_id', $parentId)->get();
            }
            if ($this->data['mediaGallery']) {
                return $this->sendSuccess($this->data, config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            }
            return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'), 406);
        } catch (Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }

    public function mediaGalleryView(Request $request)
    {
        try {
            $this->data['role']                   = getUserRoles();

            $this->data['mediaGallery'] = MediaGallery::find($request->id);
            $query                = MediaFiles::where('type', 'school_media_gallery')->where('tbl_id', $request->id);

            $type                 = $request->query('type');

            if ($type === 'image') {
                $query->whereIn('file_extension', ['jpg', 'jpeg', 'svg', 'png', 'gif', 'webp']);
            }
            if ($type === 'video') {
                $query->whereIn('file_extension', ['mp4', 'avi', 'mov', 'm4v', 'm4p', 'mpg', 'mp2', 'mpeg', 'mpe', 'mpv', 'm2v', 'wmv', 'flv', 'mkv', 'webm', '3gp', '3gp', 'm2ts', 'ogv', 'ts', 'mxf']);
            }
            if ($type === 'document') {
                $query->whereIn('file_extension', ['pdf', 'docx', 'xlsx', 'txt', 'pptx', 'csv']);
            }

            $this->data['mediaGalleryView'] = $query->get();
            if ($this->data['mediaGalleryView']) {
                return $this->sendSuccess($this->data, config('constants.API_MSG.REC_FETCHED_SUCCESS'));
            }
            return $this->sendError(config('constants.API_MSG.REC_NOT_FOUND'), 406);
        } catch (\Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }

    public function mediaGalleryFileDelete(Request $request)
    {
        try {
            $file = MediaFiles::where('id', $request->id)->where('type', 'school_media_gallery')->first();

            if ($file) {
                if (Storage::disk('public')->exists('uploads/media-files/' . $file->attachment_file)) {
                    Storage::disk('public')->delete('uploads/media-files/' . $file->attachment_file);
                }
                $file->delete();
                return $this->sendSuccess($this->data, config('constants.API_MSG.REC_DELETE_SUCCESS'));
            } else {
                return $this->sendError(config('constants.API_MSG.REC_DELETE_FAILED'), 406);
            }
        } catch (\Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }


    public function mediaGalleryStoreFile(Request $request)
    {
        $maxFileSize = config('constants.MAX_FILE_SIZE');

        try {
            $request->validate([
                'file' => "required|file|mimes:jpg,jpeg,png,svg,doc,docx,xls,xlsx,pdf,mp3,wav,mp4,mov,avi|max:$maxFileSize",
            ]);
            if ($request->hasFile('file')) {
                $file           = $request->file('file');
                $extension      = $file->getClientOriginalExtension();
                $fileName = time() . '.' . $extension;
                $path     = Storage::disk('public')->put('uploads/media-gallery/' . $fileName, file_get_contents($file));

                if ($path) {
                    $mediaFile                  = new MediaFiles();
                    $mediaFile->tbl_id          = $request->id;
                    $mediaFile->type            = 'school_media_gallery';
                    $mediaFile->attachment_file = $fileName;
                    $mediaFile->original_name   = $file->getClientOriginalName();
                    $mediaFile->file_extension  = $extension;
                    $mediaFile->file_size       = $file->getSize();
                    $mediaFile->mime_type       = $file->getMimeType();
                    $mediaFile->uploaded_by     = Auth::id();
                    $mediaFile->save();

                    return $this->sendSuccess([], config('constants.API_MSG.REC_ADD_SUCCESS'));
                } else {
                    return $this->sendError(config('constants.API_MSG.REC_ADD_FAILED'), 406);
                }
            }
        } catch (\Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 406);
        }
    }
}
