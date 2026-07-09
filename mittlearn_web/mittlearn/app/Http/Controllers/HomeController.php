<?php

namespace App\Http\Controllers;

use App\Models\AccessCode;
use App\Models\AccessCodeLog;
use App\Models\Course;
use App\Models\CourseMetadataValue;
use App\Models\Schools;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionPurchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    public $data = [];
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {

        $isFreeTrialAvailable = SubscriptionPlan::where('status', 1)->where('is_free_trial', 1)->exists();

        $className = Course::where('category_id', 1)
            ->join('course_metadata_values', 'courses.id', '=', 'course_metadata_values.course_id')
            ->where('course_metadata_values.field_name', 'class')
            ->select('courses.id', 'courses.course_name', 'course_metadata_values.field_value')
            ->distinct('course_metadata_values.field_value')
            ->get()
            ->unique('field_value');

        $subscription = SubscriptionPurchase::where('user_id', auth()->id())->first();

        if ($subscription) {
            $subscription->plan_json = json_decode($subscription->plan_json, true); // Decode JSON string
            $subscription->courses_json = json_decode($subscription->courses_json, true); // Decode JSON string
        }

        $this->data['subscription'] = $subscription;

        $this->data['isFreeTrialAvailable'] = SubscriptionPlan::where('status', 1)->where('is_free_trial', 1)->first();

        // Existing code to fetch className and nonAcademic courses
        $this->data['className'] = getClasses();
        // return $this->data['className'];
        // Course::where('category_id', 1)
        //     ->join('course_metadata_values', 'courses.id', '=', 'course_metadata_values.course_id')
        //     ->where('course_metadata_values.field_name', 'class')
        //     ->select('course_metadata_values.field_value')
        //     ->distinct()
        //     ->get();

        $this->data['nonAcademic'] = Course::where('category_id', 2)
            ->join('subscription_plan_courses', 'courses.id', '=', 'subscription_plan_courses.course_id')
            ->select('courses.*', 'subscription_plan_courses.plan_id', 'subscription_plan_courses.course_id')
            ->get();
        $this->data['checkAlreadyAccessCodeUser'] = AccessCode::where('user_id', Auth::id())->first();

        $this->data['getSchool'] = Schools::pluck('name', 'id')->toArray();

        $this->data['checkAlreadyUser'] = AccessCode::where('user_id', auth()->id())->first();

        return view('user.my-courses', $this->data);
    }

    public function getClassCourses($class_id)
    {
        // dd($class_id);
        // Retrieve course IDs from course_metadata_values based on the class_id
        $courseIds = CourseMetadataValue::where('field_name', 'class')
            ->where('field_value', $class_id)
            ->pluck('course_id'); // This will get an array of course IDs

        // Use the retrieved course IDs to fetch course data from the courses table
        $courses = Course::whereIn('id', $courseIds)->get(['id', 'course_name']);
        // Return response as JSON
        return response()->json(['courses' => $courses]);
    }

    public function purchaseSubscription(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'plan_id' => 'required|integer',
            'academic_courses' => 'nullable', // Comma-separated string
            'non_academic_courses' => 'nullable|array',
        ]);

        // Check if the user already has a subscription for this plan
        $existingSubscription = SubscriptionPurchase::where('user_id', Auth::id())
            ->where('plan_id', $request->plan_id)
            ->first();

        if ($existingSubscription) {
            return redirect()->back()->with('error', 'You have already purchased this subscription plan.');
        }

        // Parse course IDs from request
        $academicCourseIds = explode(',', $request->academic_courses);
        $nonAcademicCourseIds = $request->non_academic_courses;

        // Fetch plan data from subscription_plans table
        $plan = SubscriptionPlan::findOrFail($request->plan_id);
        $planJson = [
            'plan_id' => $plan->id,
            'name' => $plan->name,
            'plan_type' => $plan->plan_type,
            'currency' => $plan->currency,
            'bg_color' => $plan->bg_color,
            'is_recommended' => $plan->is_recommended,
            'is_free_trial' => $plan->is_free_trial,
            'description' => $plan->description,
            'status' => $plan->status,
            'start_date' => now(),
            'end_date' => now()->addDays(15), // Example: Adjust duration as necessary
        ];

        // Fetch academic and non-academic courses with specific fields
        $academicCourses = Course::whereIn('id', $academicCourseIds)
            ->get(['id', 'category_id', 'sub_category_id', 'course_name', 'price', 'discount_type', 'discount_value', 'is_active']);
        $nonAcademicCourses = Course::whereIn('id', $nonAcademicCourseIds)
            ->get(['id', 'category_id', 'sub_category_id', 'course_name', 'price', 'discount_type', 'discount_value', 'is_active']);

        // Format courses for JSON storage
        $coursesJson = [
            'academic_courses' => $academicCourses->map(function ($course) {
                return [
                    'course_id' => $course->id,
                    'category_id' => $course->category_id,
                    'sub_category_id' => $course->sub_category_id,
                    'course_name' => $course->course_name,
                    'price' => $course->price,
                    'discount_type' => $course->discount_type,
                    'discount_value' => $course->discount_value,
                    'is_active' => $course->is_active,
                ];
            }),
            'non_academic_courses' => $nonAcademicCourses->map(function ($course) {
                return [
                    'course_id' => $course->id,
                    'category_id' => $course->category_id,
                    'sub_category_id' => $course->sub_category_id,
                    'course_name' => $course->course_name,
                    'price' => $course->price,
                    'discount_type' => $course->discount_type,
                    'discount_value' => $course->discount_value,
                    'is_active' => $course->is_active,
                ];
            }),
        ];

        // Save subscription data to the database
        SubscriptionPurchase::create([
            'user_id' => Auth::id(),
            'plan_id' => $plan->id,
            'start_date' => now(),
            'end_date' => now()->addDays(15),
            'plan_json' => json_encode($planJson),
            'courses_json' => json_encode($coursesJson),
            'transaction_id' => 'txn_' . uniqid(),
            'status' => 'active',
        ]);

        return redirect()->back()->with('success', 'Subscription successfully created.');
    }
    public function validateAccessCode(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'access_code' => 'required',
            ]);

            // Check if the user already has an active access code
            $checkAlreadyUser = AccessCode::where('user_id', auth()->id())->first();
            if ($checkAlreadyUser) {
                return response()->json([
                    'success' => false,
                    'message' => 'You are already using an Access Code.',
                ]);
            }

            // Check if the provided access code exists and is unassigned
            $accessCode = AccessCode::where('access_code', $request->access_code)->first();
            if ($accessCode && !$accessCode->user_id) {
                if (!$accessCode->school_id) {
                    $accessCode->school_id = $request->school_id;
                }
                $accessCode->user_id = auth()->id();
                $accessCode->status = 'active'; // Set status to active
                $accessCode->save();

                // Log the action in AccessCodeLog
                AccessCodeLog::create([
                    'user_id' => auth()->id(),
                    'title' => 'Access Code Activated',
                    'action_as' => 'user_access_code_active',
                    'action_by' => auth()->id(),
                    'json_data' => json_encode([$accessCode]),
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Access Code validated and activated successfully.',
                ]);
            }

            // Access code is either invalid or already used
            return response()->json([
                'success' => false,
                'message' => 'Invalid or already used Access Code.',
            ]);
        } catch (\Exception $e) {
            // Handle exceptions and return an error response
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while validating the Access Code.',
                'error' => $e->getMessage(),
            ]);
        }
    }

}
