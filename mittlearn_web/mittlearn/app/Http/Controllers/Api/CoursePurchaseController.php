<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseController;
use App\Models\Board;
use App\Models\BookSeries;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Medium;
use App\Models\Role;
use App\Models\SchoolClass;
use App\Models\StudentDetails;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionPurchase;
use App\Models\TransactionLog;
use App\Models\User;
use App\Models\UserAdditionalDetail;
use App\Models\UserRole;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class CoursePurchaseController extends BaseController
{
    private array $data = [];

    public function courseFilter(Request $request)
    {
        try {
            $this->data['group'] = getParentCategories();

            if ($request->group_id == 1) {
                $this->data['boards']     = Board::where('is_active', 1)->pluck('name', 'id');
                $this->data['mediums']    = Medium::where('is_active', 1)->pluck('name', 'id');
                $this->data['bookSeries'] = BookSeries::where('is_active', 1)->pluck('name', 'id');
                $this->data['classes']    = SchoolClass::where('is_active', 1)->whereBetween('id', [1, 23])->pluck('name', 'id');
            } else {
                $this->data['sub_groups'] = Category::where('status', 1)->where('parent_id', 2)->pluck('name', 'id')->toArray();
            }

            return $this->sendSuccess($this->data, config('constants.API_MSG.REC_FETCHED_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 500);
        }
    }
    public function continueAsGuest(Request $request)
    {
        try {
            $request->validate([
                'you_are_here_for' => 'required',
            ]);

            // Create a new guest user
            $user = new User;
            $user->name = 'Guest User';
            $user->email = 'guest_' . time() . '@guest.com';
            $user->mobile_no = time();
            $user->password = Hash::make('Mitt@123');
            $user->validate_string = 'Mitt@123';
            $user->user_type = 'guest_user';
            $user->is_verified = 1;
            $user->status = 1;
            $user->source = 'guest';
            $user->save();
            $this->data['user'] = $user;
            // Assign role
            $roleSlug = Role::where('role_slug', 'b2c_student')->first();
            if ($roleSlug) {
                UserRole::create([
                    'user_id'   => $user->id,
                    'role_slug' => $roleSlug->role_slug,
                ]);
            }

            // Create StudentDetails record
            StudentDetails::create([
                'user_id'  => $user->id,
                'class'    => $request->class_id,
            ]);

            // Create UserAdditionalDetail
            UserAdditionalDetail::create([
                'user_id' => $user->id,
                'role'    => 'b2c_student',
            ]);

            // Login user and generate token
            Auth::login($user);
            $this->data['token'] = $user->createToken($user->name . 'AuthToken')->plainTextToken;

            $this->data['landingUi'] = getUserClassLandingUi() ?? null;
            $this->data['userRole']  = $user->userRole->role_slug ?? 'b2c_student';

            return $this->sendSuccess($this->data, config('constants.API_MSG.REC_FETCHED_SUCCESS'));
        } catch (ValidationException $e) {
            return $this->sendError(config('constants.API_MSG.VALIDATION_ERROR'), $e->errors(), 422);
        } catch (\Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 500);
        }
    }
    public function allCourses()
    {
        try {
            // Fetch Academic Courses (category_id = 1)
            $acadCourses = Course::where('category_id', 1)
                ->where('is_active', 1)
                ->with(['metadataValues' => function ($query) {
                    $query->whereIn('field_name', ['class', 'subject', 'series', 'description']);
                }])
                ->get();

            // Fetch Non-Academic / Talent Skill Courses (category_id = 2)
            $nonAcadCourses = Course::where('category_id', 2)
                ->where('is_active', 1)
                ->with(['metadataValues' => function ($query) {
                    $query->whereIn('field_name', ['class', 'subject', 'series', 'description']);
                }])
                ->get();

            // Format function for each type
            $formatCourse = function ($courseList) {
                return $courseList->map(function ($course) {
                    $originalPrice = (float) $course->price;
                    $discountValue = (float) $course->discount_value;
                    $finalPrice = $originalPrice;

                    if ($course->discount_type === 'flat') {
                        $finalPrice = $originalPrice - $discountValue;
                    } elseif ($course->discount_type === 'percentage') {
                        $finalPrice = $originalPrice - ($originalPrice * $discountValue / 100);
                    }

                    $response = [
                        'id'             => $course->id,
                        'product_id'     => $course->product_id,
                        'category_id'    => $course->category_id,
                        'sub_category_id' => $course->sub_category_id,
                        'slug'           => $course->slug,
                        'course_name'    => $course->course_name,
                        'price'          => $course->price,
                        'discount_type'  => $course->discount_type,
                        'discount_value' => $course->discount_value,
                        'final_price'    => round($finalPrice, 2),
                        'is_active'      => $course->is_active,
                        'created_at'     => $course->created_at,
                    ];

                    $descriptionSet = false;

                    foreach ($course->metadata as $meta) {
                        if (!empty($meta->field_value)) {
                            switch ($meta->field_name) {
                                case 'description':
                                    $response['description'] = strip_tags($meta->field_value);
                                    $descriptionSet = true;
                                    break;
                                case 'course_overview':
                                    if (!$descriptionSet) {
                                        $response['description'] = strip_tags($meta->field_value);
                                    }
                                    break;
                                case 'class':
                                    $response['class'] = $meta->field_value;
                                    break;
                                case 'subject':
                                    $response['subject'] = $meta->field_value;
                                    break;
                                case 'series':
                                    $response['series'] = $meta->field_value;
                                    break;
                            }
                        }
                    }

                    return $response;
                });
            };

            // Apply formatter
            $response = [
                'nonAcadCourses'  => $formatCourse($nonAcadCourses),
                'acadCourses'     => $formatCourse($acadCourses),
            ];

            return $this->sendSuccess($response, config('constants.API_MSG.REC_FETCHED_SUCCESS'));
        } catch (\Exception $e) {
            return $this->sendError(config('constants.API_MSG.SERVER_ERROR'), $e->getMessage(), 500);
        }
    }


    public function storeInAppPurchase(Request $request)
    {
        try {
            $request->validate([
                'user_id'    => 'required|exists:users,id',
                'product_id' => 'required|exists:courses,product_id',
                'transaction_id' => 'nullable',
            ]);

            $user = User::findOrFail($request->user_id);

            $course = Course::where('product_id', $request->product_id)->firstOrFail();
            $planId = SubscriptionPLan::where('is_recommended', 1)->value('id');
            $existingSubscription = SubscriptionPurchase::where('user_id', $user->id)
                ->where(function ($query) use ($course) {
                    $query->whereJsonContains('courses_json->academic_courses', ['id' => $course->id])
                        ->orWhereJsonContains('courses_json->non_academic_courses', ['id' => $course->id]);
                })
                ->whereDate('end_date', '>=', now())
                ->first();
            if ($existingSubscription) {
                return $this->sendError('User already owns this course.',  406);
            }

            // Fake Plan
            $planJson = [
                'plan_id'        => $planId,
                'name'           => 'In-App Purchase',
                'plan_type'      => 'one_time',
                'currency'       => 'INR',
                'description'    => 'Purchased via App Store',
                'start_date'     => now(),
                'end_date'       => now()->addYear(),
                'status'         => 1
            ];

            $coursesJson = $course->category_id == 1
                ? ['academic_courses' => [$course], 'non_academic_courses' => []]
                : ['academic_courses' => [], 'non_academic_courses' => [$course]];


            // Save transaction log
            $transactionLog = TransactionLog::create([
                'plan_id'              => $planId,
                'user_id'              => $user->id,
                'txn_id'               => 'ios-in-app-purchase',
                'payment_gateway'      => 'ios_in_app',
                'payment_id'           => 'app-store-payment',
                'cart'                 => json_encode([$course->id]),
                'total_amount'         => $request->total_amount ?? ($course->price ?? 0),
                'currency'             => 'INR',
                'quantity'             => 1,
                'transaction_for'      => 'In-App Course Purchase',
                'payment_details'      => json_encode($request->all()),
                'payment_state'        => 'success',
                'payer_payment_method' => 'apple_in_app',
                'payer_status'         => 'completed',
            ]);

            $subscription = SubscriptionPurchase::create([
                'user_id'        => $user->id,
                'plan_id'        => $planId,
                'start_date'     => now(),
                'end_date'       => now()->addYear(),
                'plan_json'      => json_encode($planJson),
                'courses_json'   => json_encode($coursesJson),
                'transaction_id' => $request->transaction_id ?? 'ios-in-app',
                'status'         => 'active',
            ]);
            return $this->sendSuccess($subscription, 'Course purchased and saved successfully.');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage(), 500);
        }
    }
}
