<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\BookSeries;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Classes;
use App\Models\Course;
use App\Models\FrontendCoursesView;
use App\Models\Medium;
use App\Models\SchoolClass;
use App\Models\Setting;
use App\Models\Subject;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionPlanPack;
use App\Models\SubscriptionPurchase;
use App\Models\TransactionLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Support\Str;

class PlanSubscriptionController extends Controller
{
    private array $data = [];
    public function planDetails(Request $request, $id)
    {
        $userId = auth()->check() ? auth()->id() : null;
        $planId = base64_decode($id);
        $type   = $request->query('type'); // Get the 'type' query parameter

        $this->data['plan_id']    = $planId;
        $this->data['categories'] = getParentCategories();
        $this->data['boards']     = Board::where('is_active', 1)->pluck('name', 'id');
        $this->data['mediums']    = Medium::where('is_active', 1)->pluck('name', 'id');
        $this->data['bookSeries'] = BookSeries::where('is_active', 1)->pluck('name', 'id');
        $this->data['classes']    = SchoolClass::where('is_active', 1)->whereBetween('id', [1, 23])->pluck('name', 'id');
        $this->data['plan']       = SubscriptionPlan::with('subscriptionPlanFeature', 'subscriptionPlanPrice', 'subscriptionPlanPack')->whereid($planId)->first();
        $sessionId                = $userId ? null : $request->input('session_id');
        $conditions               = [
            'status'  => 'active',
            'item_id' => $planId,
        ];
        if ($userId) {
            $conditions['user_id'] = $userId;
        } else {
            $conditions['session_id'] = $sessionId;
        }
        // Retrieve cart items based on conditions
        $this->data['cart'] = Cart::where($conditions)->get();
        $this->data['type'] = $type; // Pass type to the view

        // return $this->data['cart'];
        return view('frontend.plan-details', $this->data);
    }
    public function getBookSeries(Request $request)
    {
        $bookSeries = BookSeries::where('is_active', 1)->where('board_id', $request->board_id)
            ->where('medium_id', $request->medium_id)
            ->pluck('name', 'id');
        return response()->json($bookSeries);
    }
    public function getSubcategories($categoryId)
    {
        $subcategories = Category::where('status', 1)->where('parent_id', $categoryId)->pluck('name', 'id');
        return response()->json($subcategories);
    }
    // For get Academic Courses
    public function getCoursesByCategory(Request $request, $id)
    {
        try {
            $userId    = auth()->check() ? auth()->id() : null;
            $sessionId = $userId ? null : $request->input('session_id');
            $type      = $request->input('type');
            $classId   = $request->input('class_id');
            $boardId   = $request->input('board_id');
            $mediumId  = $request->input('medium_id');
            // Get all FrontendCoursesView entries where class_ids contains the requested classId
            $seriesData = FrontendCoursesView::get();

            // Extract series IDs where the classId exists in the comma-separated class_ids
            $validSeriesIds = $seriesData->filter(function ($item) use ($classId) {
                $classIds = explode(',', $item->classes_ids);
                return in_array($classId, $classIds);
            })->pluck('series_id')->unique()->toArray();

            // If no valid series found — return empty
            if (empty($validSeriesIds)) {
                return response()->json([]); // no courses for this class
            }

            // Check if the request is for free courses and validate the plan
            if ($type === 'free-courses') {
                $planPack = SubscriptionPlanPack::where('id', $request->item_id)->first();
                if (! $planPack || $planPack->free_academic_courses <= 0) {
                    return response()->json(['error' => 'No free academic courses available for this plan.'], 403);
                }
            }
            // Get class name from class table
            $className = Classes::where('is_active', 1)->where('id', $classId)->value('name') ?? 'Unknown Class';

            $query = Course::where('category_id', $id);

            // Add board condition only if board_id is provided and not 0
            if (!empty($boardId) && $boardId != 0) {
                $query->whereHas('metadataValues', function ($query) use ($boardId) {
                    $query->where('field_name', 'board')
                        ->where('field_value', $boardId);
                });
            }

            // Add medium condition only if medium_id is provided and not 0
            if (!empty($mediumId) && $mediumId != 0) {
                $query->whereHas('metadataValues', function ($query) use ($mediumId) {
                    $query->where('field_name', 'medium')
                        ->where('field_value', $mediumId);
                });
            }

            // Always apply series and class conditions
            $query->whereHas('metadataValues', function ($query) use ($validSeriesIds) {
                $query->where('field_name', 'series')
                    ->whereIn('field_value', $validSeriesIds);
            })
                ->whereHas('metadataValues', function ($query) use ($classId) {
                    $query->where('field_name', 'class')
                        ->where('field_value', $classId);
                });

            // Eager load specific metadata values including subject
            $query->with(['metadataValues' => function ($query) {
                $query->whereIn('field_name', ['thumbnail_image', 'banner_image', 'book_cover_image', 'subject']);
            }]);

            $courses = $query->withCount([
                'cartItems as in_cart' => function ($query) use ($userId, $sessionId, $request) {
                    $query->where(function ($q) use ($userId, $sessionId, $request) {
                        if ($userId) {
                            $q->where('user_id', $userId)
                                ->where('item_id', $request->item_id)
                                ->where('status', 'active');
                        } else {
                            $q->where('session_id', $sessionId)
                                ->where('item_id', $request->item_id)
                                ->where('status', 'active');
                        }
                    });
                },
                'wishlistItems as in_wishlist' => function ($query) use ($userId, $sessionId, $request) {
                    $query->where(function ($q) use ($userId, $sessionId, $request) {
                        if ($userId) {
                            $q->where('user_id', $userId)->where('status', 'active');
                        } else {
                            $q->where('session_id', $sessionId)->where('status', 'active');
                        }
                    });
                },
            ])->get();

            // Transform the courses to include additional data
            $courses = $courses->map(function ($course) use ($className) {
                // Initialize metadata properties
                $course->thumbnail_image = null;
                $course->banner_image = null;
                $course->book_cover_image = null;
                $course->subject_name = null;
                $course->class_name = $className;

                // Extract metadata values
                foreach ($course->metadataValues as $metadata) {
                    if ($metadata->field_name === 'thumbnail_image') {
                        $course->thumbnail_image = $metadata->field_value;
                    } elseif ($metadata->field_name === 'banner_image') {
                        $course->banner_image = $metadata->field_value;
                    } elseif ($metadata->field_name === 'book_cover_image') {
                        $course->book_cover_image = $metadata->field_value;
                    } elseif ($metadata->field_name === 'subject') {
                        // Get subject name from subject table using the subject ID
                        $subject = Subject::find($metadata->field_value);
                        $course->subject_name = $subject ? $subject->name : 'Unknown Subject';
                    }
                }

                // Remove the metadataValues relation to clean up the response
                unset($course->metadataValues);

                return $course;
            });

            // Apply 'free-courses' logic if type is provided
            if ($type === 'free-courses') {
                $courses = $courses->map(function ($course) {
                    $course->price          = 0;
                    $course->discount_type  = 'flat';
                    $course->discount_value = 0;
                    return $course;
                })->filter(function ($course) {
                    return $course->in_cart === 0;
                });
            }

            // Re-index the collection
            $courses = $courses->values();

            return response()->json($courses);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }
    // For get Tallent Skills (Non-Academic) Courses
    public function getCoursesBySubCategory(Request $request, $id)
    {
        try {
            $userId    = auth()->check() ? auth()->id() : null;
            $sessionId = $userId ? null : $request->input('session_id');
            $type      = $request->input('type'); // Get the type from the request

            // Check if the request is for free courses and validate the plan
            if ($type === 'free-courses') {
                $planPack = SubscriptionPlanPack::where('plan_id', $request->item_id)->first();
                // Ensure the plan pack has non_academic_free_courses available
                if (! $planPack || $planPack->non_academic_free_courses <= 0) {
                    return response()->json(['error' => 'No free non-academic courses available for this plan.'], 403);
                }
            }

            $category     = Category::find($id);
            $coursesQuery = null;

            if ($category && $category->subcategories()->exists()) {
                $coursesQuery = Course::where('category_id', 2)
                    ->whereIn('sub_category_id', $category->subcategories->pluck('id'));
            } else {
                $coursesQuery = Course::where('category_id', 2)
                    ->where('sub_category_id', $id);
            }

            // Filter courses that have the complimentary metadata value
            $coursesQuery->whereHas('metadataValues', function ($query) {
                $query->where('field_name', 'available_for_complimentary_package')
                    ->whereIn('field_value', ['all', '0']);
            });
            // Eager load specific metadata values
            $coursesQuery->with(['metadataValues' => function ($query) {
                $query->whereIn('field_name', ['thumbnail_image', 'banner_image', 'book_cover_image']);
            }]);
            // Add counts for cart and wishlist
            $courses = $coursesQuery->withCount([
                'cartItems as in_cart'         => function ($query) use ($userId, $sessionId, $request) {
                    $query->where(function ($q) use ($userId, $sessionId, $request) {
                        if ($userId) {
                            $q->where('user_id', $userId)
                                ->where('item_id', $request->item_id)
                                ->where('status', 'active');
                        } else {
                            $q->where('session_id', $sessionId)
                                ->where('item_id', $request->item_id)
                                ->where('status', 'active');
                        }
                    });
                },
                'wishlistItems as in_wishlist' => function ($query) use ($userId, $sessionId, $request) {
                    $query->where(function ($q) use ($userId, $sessionId, $request) {
                        if ($userId) {
                            $q->where('user_id', $userId)
                                ->where('status', 'active');
                        } else {
                            $q->where('session_id', $sessionId)
                                ->where('status', 'active');
                        }
                    });
                },
            ])->get();

            $courses = $courses->map(function ($course) {
                // Initialize metadata properties
                $course->thumbnail_image = null;
                $course->banner_image = null;
                $course->book_cover_image = null;

                // Extract metadata values
                foreach ($course->metadataValues as $metadata) {
                    if ($metadata->field_name === 'thumbnail_image') {
                        $course->thumbnail_image = $metadata->field_value;
                    } elseif ($metadata->field_name === 'banner_image') {
                        $course->banner_image = $metadata->field_value;
                    } elseif ($metadata->field_name === 'book_cover_image') {
                        $course->book_cover_image = $metadata->field_value;
                    }
                }

                // Remove the metadataValues relation to clean up the response
                unset($course->metadataValues);

                return $course;
            });
            // Apply 'free-courses' logic if type is provided
            if ($type === 'free-courses') {
                $courses = $courses->map(function ($course) {
                    $course->price          = 0;
                    $course->discount_type  = 'flat';
                    $course->discount_value = 0;
                    return $course;
                })->filter(function ($course) {
                    return $course->in_cart === 0; // Exclude courses already in the cart
                });
            }

            // Re-index the collection to maintain consistent response format
            $courses = $courses->values();

            return response()->json($courses);
        } catch (\Exception $e) {
            // dd($e);
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }
    public function showCart(Request $request)
    {
        $userId    = $request->query('user_id');
        $sessionId = session('user_session_id');
        if (Auth::check()) {
            $userId     = base64_decode($userId); // Decode user ID if encrypted
            $this->data = $this->getCartData(['user_id' => Auth::id()]);
            $this->data['hasOnlyFreeCourses'] = $this->data['hasOnlyFreeCourses'] ?? false;
        } elseif ($sessionId) {
            $this->data = $this->getCartData(['session_id' => $sessionId]);
        }
        // Check if cartItems exist and are not empty
        $itemId = $this->data['cartItems'][0]->item_id ?? null;
        // Call checkDiscount only if itemId is not null
        if ($itemId !== null) {
            $this->data['plan_packs'] = checkDiscount($itemId, $sessionId);
            $this->data['plan']       = SubscriptionPlan::where('id', $itemId)->first();
            // Safely access free_academic_courses and free_nonacademic_courses
            $freeAcademicCourses = isset($this->data['plan_packs']['free_academic_courses'])
                ? (int) $this->data['plan_packs']['free_academic_courses']
                : 0;

            $freeNonAcademicCourses = isset($this->data['plan_packs']['free_nonacademic_courses'])
                ? (int) $this->data['plan_packs']['free_nonacademic_courses']
                : 0;
            // Query the cart table to count the number of active academic and non-academic courses
            $academicCount = Cart::where('item_id', $itemId)->where('item_type', 'academic_course')
                ->where('type', 'free')->where('status', 'active')
                ->where(function ($query) use ($userId, $sessionId) {
                    if ($userId) {
                        $query->where('user_id', $userId);
                    } else {
                        $query->where('session_id', $sessionId);
                    }
                })->count();

            $nonAcademicCount = Cart::where('item_id', $itemId)->where('item_type', 'nonacademic_course')
                ->where('type', 'free')->where('status', 'active')
                ->where(function ($query) use ($userId, $sessionId) {
                    if ($userId) {
                        $query->where('user_id', $userId);
                    } else {
                        $query->where('session_id', $sessionId);
                    }
                })->count();

            // Check if the counts match the allowed free courses
            if ($academicCount >= $freeAcademicCourses) {
                $this->data['plan_packs']['free_academic_courses'] = 0; // Mark as consumed
            }
            if ($nonAcademicCount >= $freeNonAcademicCourses) {
                $this->data['plan_packs']['free_nonacademic_courses'] = 0; // Mark as consumed
            }                                                          // Check if the plan is a free trial
            $this->data['isFreeTrial'] = $this->data['plan']->is_free_trial == 1;
            // If free trial, adjust discount and grandTotal
            if ($this->data['isFreeTrial']) {
                $this->data['totalDiscount'] = $this->data['totalAmount']; // Discount is the total amount
                $this->data['grandTotal']    = 0;                          // Grand total is 0
            }
        }
        return view('frontend.cart', $this->data);
    }

    private function getCartData(array $filters)
    {
        $cartItems = Cart::with('getCourses')->where($filters)->where('status', 'active')->get();

        // Check if all courses are free
        $hasOnlyFreeCourses = $cartItems->every(function ($item) {
            return optional($item->getCourses)->price_type === 'free';
        });

        $itemsCount = $cartItems->count();
        $totalAmount = $cartItems->sum('price');
        $totalDiscount = $cartItems->sum('discount');

        return [
            'cartItems' => $cartItems,
            'itemsCount' => $itemsCount,
            'totalAmount' => $totalAmount,
            'totalDiscount' => $totalDiscount,
            'grandTotal' => $totalAmount - $totalDiscount,
            'hasOnlyFreeCourses' => $hasOnlyFreeCourses, // Add this flag
        ];
    }
    public function deleteItemFromCart(Request $request)
    {
        $cartId = $request->input('cart_id');
        // Perform the delete action
        $cartItem = Cart::find($cartId);
        if ($cartItem) {
            $cartItem->update(['status' => 'cancelled']);
        }
        // Recalculate the cart data
        // $cartItems     = Cart::where('item_id', $cartItem->item_id)->where('session_id', $request->input('session_id'))->where('status', 'active')->with('getCourses')->get();

        $cartItemsData = Cart::where('item_id', $cartItem->item_id)
            ->where('session_id', $request->input('session_id'))
            ->where('status', 'active')
            ->with(['getCourses', 'getCourses.metadata'])
            ->get();

        // Map image fields from metadata
        $cartItems = $cartItemsData->map(function ($item) {
            $course = $item->getCourses;
            $metadata = collect($course->metadata);

            $bnrImage = $metadata->where('field_name', 'banner_image')->first()?->field_value;
            $bkCoverImage = $metadata->where('field_name', 'book_cover_image')->first()?->field_value;
            $thumImage = $metadata->where('field_name', 'thumbnail_image')->first()?->field_value;

            return [
                'id' => $item->id,
                'price' => $item->price,
                'full_price' => $item->full_price,
                'quantity' => $item->quantity,
                'get_courses' => [
                    'course_name' => $course->course_name,
                    'get_category_course' => [
                        'name' => optional($course->getCategoryCourse)->name,
                    ],
                    'bnr_image' => $bnrImage ? Storage::url($bnrImage) : null,
                    'bk_cover_image' => $bkCoverImage ? Storage::url($bkCoverImage) : null,
                    'thumbnail_image' => $thumImage ? Storage::url($thumImage) : null,
                ]
            ];
        });

        $itemsCount    = $cartItems->count();
        $totalAmount   = $cartItems->sum('price');
        // return $cartItems;
        $planId     = $cartItem->first()->item_id;
        $plan_packs = checkDiscount($planId, $request->input('session_id'));
        // $this->data['plan'] = SubscriptionPlan::where('id', $planId)->first();
        return response()->json([
            'status'  => 'success',
            'message' => 'Item removed successfully',
            'data'    => [
                'cartItems'   => $cartItems,
                'itemsCount'  => $itemsCount,
                'totalAmount' => $totalAmount,
                'plan_packs'  => $plan_packs,
            ],
        ]);
    }
    public function OLDONEdeleteItemFromCart(Request $request)
    {
        $cartId = $request->input('cart_id');
        // Perform the delete action
        $cartItem = Cart::find($cartId);
        if ($cartItem) {
            $cartItem->update(['status' => 'cancelled']);
        }
        // Recalculate the cart data
        $cartItems     = Cart::where('item_id', $cartItem->item_id)->where('session_id', $request->input('session_id'))->where('status', 'active')->with('getCourses')->get();
        $itemsCount    = $cartItems->count();
        $totalAmount   = $cartItems->sum('price');
        $totalDiscount = $cartItems->sum('discount');
        // return $cartItems;
        $planId     = $cartItem->first()->item_id;
        $plan_packs = checkDiscount($planId, $request->input('session_id'));
        // $this->data['plan'] = SubscriptionPlan::where('id', $planId)->first();
        return response()->json([
            'status'  => 'success',
            'message' => 'Item removed successfully',
            'data'    => [
                'cartItems'   => $cartItems,
                'itemsCount'  => $itemsCount,
                'totalAmount' => $totalAmount,
                'plan_packs'  => $plan_packs,
            ],
        ]);
    }
    public function processCheckout(Request $request)
    {
        if (! Auth::check()) {
            // Store cart data in session
            session(['cart_data' => $request->all()]);
            return redirect()->route('login')->with('message', 'Please login to proceed with checkout.');
        }
        $userId               = auth()->id();
        $planId               = $request->plan_id;
        $plan                 = SubscriptionPlan::findOrFail($planId);
        $existingSubscription = SubscriptionPurchase::where('user_id', $userId)
            ->where('plan_id', $planId)
            ->first();
        if ($existingSubscription) {
            return redirect()->back()->with('error', 'You have already purchased this subscription plan. Please upgrade your plan from the dashboard.');
        }
        // Prepare the plan details and courses JSON
        $planJson = [
            'plan_id'        => $plan->id,
            'name'           => $plan->name,
            'plan_type'      => $plan->plan_type,
            'currency'       => $plan->currency,
            'bg_color'       => $plan->bg_color,
            'is_recommended' => $plan->is_recommended,
            'is_free_trial'  => $plan->is_free_trial,
            'description'    => $plan->description,
            'status'         => $plan->status,
            'start_date'     => now(),
            'end_date'       => now()->addDays(15),
        ];
        $academicCourseIds = $request->cart_items;
        $academicCourses   = Course::whereIn('id', $academicCourseIds)
            ->where('category_id', 1)
            ->get(['id', 'category_id', 'sub_category_id', 'course_name', 'price', 'discount_type', 'discount_value', 'is_active']);
        $nonAcademicCourses = Course::whereIn('id', $academicCourseIds)
            ->where('category_id', 2)
            ->get(['id', 'category_id', 'sub_category_id', 'course_name', 'price', 'discount_type', 'discount_value', 'is_active']);
        $coursesJson = [
            'academic_courses'     => $academicCourses->map(fn($course) => $course->toArray())->toArray(),
            'non_academic_courses' => $nonAcademicCourses->map(fn($course) => $course->toArray())->toArray(),
        ];
        // Handle free trial
        if ($request->is_free_trial) {
            SubscriptionPurchase::create([
                'user_id'        => $userId,
                'plan_id'        => $planId,
                'start_date'     => now(),
                'end_date'       => now()->addDays(15),
                'plan_json'      => json_encode($planJson),
                'courses_json'   => json_encode($coursesJson),
                'transaction_id' => 'freetrial-plan-not-have-transaction-log-id',
                'status'         => 'active',
            ]);
            Cart::where('user_id', $userId)->delete();
            return redirect()->route('up.dashboard')->with('message', 'Free trial for 15 days added.');
        }
        if ($request->has('free_checkout')) {
            SubscriptionPurchase::create([
                'user_id' => $userId,
                'plan_id' => $planId,
                'start_date' => now(),
                'end_date' => now()->addYears(1), // 1 year access for free courses
                'plan_json' => json_encode($planJson),
                'courses_json' => json_encode($coursesJson),
                'transaction_id' => 'free--plan-not-have-transaction-log-id',
                'status' => 'active'
            ]);

            Cart::where('user_id', $userId)->delete();
            return redirect()->route('up.dashboard')->with('success', 'Free courses activated for 1 year!');
        }

        // Validate incoming request
        $request->validate([
            'razorpay_payment_id' => 'required|string',
            'total_amount'        => 'required|numeric',
            'plan_id'             => 'required|integer',
            'cart_items'          => 'required|array',
        ]);
        try {
            // Save TransactionLog
            $transactionLog = TransactionLog::create([
                'plan_id'              => $planId,
                'user_id'              => $userId,
                'txn_id'               => $request->razorpay_payment_id,
                'coupon_id'            => null,
                'payment_gateway'      => 'razorpay',
                'payment_id'           => $request->razorpay_payment_id,
                'cart'                 => json_encode($request->cart_items),
                'total_amount'         => $request->total_amount,
                'currency'             => 'INR',
                'quantity'             => count($request->cart_items),
                'transaction_for'      => 'Subscription Purchase',
                'payment_details'      => json_encode($request->all()),
                'payment_state'        => 'success',
                'payer_payment_method' => $request->payer_payment_method ?? 'unknown',
                'payer_status'         => 'completed',
            ]);
            // Save SubscriptionPurchase
            SubscriptionPurchase::create([
                'user_id'        => $userId,
                'plan_id'        => $planId,
                'start_date'     => now(),
                'end_date'       => now()->addYear(),
                'plan_json'      => json_encode($planJson),
                'courses_json'   => json_encode($coursesJson),
                'transaction_id' => $transactionLog->id,
                'status'         => 'active',
            ]);
            // Clear the user's cart
            Cart::where('user_id', $userId)->delete();
            // $this->sendmail(auth()->user());

            return response()->json(['message' => 'Payment successful, subscription activated.']);
        } catch (\Exception $e) {
            // Save failed transaction in TransactionLog
            TransactionLog::create([
                'plan_id'              => $planId,
                'user_id'              => $userId,
                'txn_id'               => $request->razorpay_payment_id ?? 'failed-txn-id',
                'coupon_id'            => null,
                'payment_gateway'      => 'razorpay',
                'payment_id'           => $request->razorpay_payment_id ?? null,
                'cart'                 => json_encode($request->cart_items),
                'total_amount'         => $request->total_amount,
                'currency'             => 'INR',
                'quantity'             => count($request->cart_items),
                'transaction_for'      => 'Subscription Purchase',
                'payment_details'      => json_encode($request->all()),
                'payment_state'        => 'failed',
                'payer_payment_method' => $request->input('payer_payment_method', 'unknown'),
                'payer_status'         => 'failed',
            ]);
            return response()->json(['message' => 'Payment failed, please try again.', 'error' => $e->getMessage()], 500);
        }
    }
    public function sendmail($user)
    {
        if ($user) {
            $user = User::find($user->id);
            $templateId = 30;
            $data = [
                'NAME' => $user->name,
                'EMAIL' => $user->email,
                'MOBILE_NUMBER' => $user->mobile_no,
            ];
            if ($user) {
                sendEmail($templateId, $user->email, $data);
            }
        }
    }
}
