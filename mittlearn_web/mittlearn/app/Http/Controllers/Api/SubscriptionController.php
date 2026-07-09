<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\User;
use App\Services\ApiResponseService;


class SubscriptionController extends Controller
{
    public function redirectToSubscriptionPage(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
        ]);

        $user = User::find($request->user_id);

        if (!$user) {
            return ApiResponseService::error(0, 'User not found');
        }
        $encodedUserId = base64_encode($user->id);

        $redirectUrl = url('/select-subscription-plan?user_id=' . $encodedUserId);

        return ApiResponseService::success('User logged in successfully. Redirect URL is provided in the data.', $redirectUrl);
    }


    public function addToCart(Request $request)
    {
        try {
            // Validate incoming request
            $request->validate([
                'item_type' => 'required',
                'item_id' => 'nullable',
                'price' => 'required',
                'discount' => 'nullable',
                'coupon_code' => 'nullable',
            ]);

            // Get user_id if logged in, otherwise use session_id
            $userId = $request->user_id ?? null;
            $sessionId = $request->guest_user_id;

            // Get the plan data (assuming checkDiscount function provides the counts)
            $packData = checkDiscount($request->item_id, $sessionId);
            $freeAcademicCoursesAllowed = $packData['free_academic_courses'] ?? 0;
            $freeNonAcademicCoursesAllowed = $packData['free_nonacademic_courses'] ?? 0;
            // Count existing free academic and nonacademic_course courses in the cart
            $existingFreeAcademicCourses = Cart::where('session_id', $sessionId)
                ->where('type', 'free')
                ->where('item_type', 'academic_course')
                ->where('status', 'active')
                ->count();

            $existingFreeNonAcademicCourses = Cart::where('session_id', $sessionId)
                ->where('type', 'free')
                ->where('item_type', 'nonacademic_course')
                ->where('status', 'active')
                ->count();

            // Check if the user has exceeded the limit for free courses
            if (
                $request->price == 0 &&
                $request->item_type === 'academic_course' &&
                $existingFreeAcademicCourses >= $freeAcademicCoursesAllowed
            ) {
                return ApiResponseService::error(403, 'You have already added the maximum number of free academic courses.');
            }

            if (
                $request->price == 0 &&
                $request->item_type === 'nonacademic_course' &&
                $existingFreeNonAcademicCourses >= $freeNonAcademicCoursesAllowed
            ) {
                return ApiResponseService::error(403, 'You have already added the maximum number of free nonacademic_course courses.');
            }
            // Remove all old plan items from the cart for this user or session
            Cart::where('session_id', $sessionId)->where('item_id', '!=', $request->item_id)->delete();

            $conditions = [
                'item_id' => $request->item_id,
                'course_id' => $request->course_id,
            ];

            // Add user_id to conditions if the user is logged in
            if ($userId) {
                $conditions['user_id'] = $userId;
                $conditions['session_id'] = $sessionId;
            } else {
                $conditions['session_id'] = $sessionId;
            }

            // Determine the cart type based on price
            $cartType = $request->price == 0 ? 'free' : 'paid';
            // Data to insert or update in the cart
            $cartData = [
                'item_type' => $request->item_type,
                'plan_id' => $request->item_id,
                'quantity' => 1,
                'full_price' => $request->course_full_price,
                'price' => $request->price,
                'discount' => $request->discount,
                'coupon_code' => $request->coupon_code,
                'type' => $cartType,
                'added_at' => now(),
                'status' => $request->status,
                'created_by_admin' => false,
                'updated_at' => now(),
            ];

            // Insert or update the cart item
            $cart = Cart::updateOrCreate($conditions, array_merge($cartData, [
                'user_id' => $userId,
                'session_id' => $sessionId,
            ]));

            return ApiResponseService::success(config('constants.API_MSG.REC_ADD_SUCCESS'), [
                'cart' => $cart,
                'plan_packs' => $packData,
            ]);
        } catch (\Exception $e) {
            // dd($e->getMessage());
            return ApiResponseService::error(500, 'Add to cart failed: ' . $e->getMessage());
        }
    }


    public function removeFromCart(Request $request)
    {
        // return $request;
        try {
            // Validate incoming request
            $request->validate([
                'item_type' => 'required',
                'item_id' => 'nullable',
            ]);

            // Get user_id if logged in, otherwise use session_id
            $userId = auth()->check() ? auth()->id() : null;
            $sessionId = $request->guest_user_id;

            // Set the conditions for finding the existing cart item
            $conditions = [
                'id' => $request->cart_id,
                'item_type' => $request->item_type,
                'item_id' => $request->item_id,
            ];

            // Add user_id to conditions if logged in, else add session_id
            if ($userId) {
                $conditions['user_id'] = $userId;
                $conditions['session_id'] = $sessionId;
            } else {
                $conditions['session_id'] = $sessionId;
            }
            // Attempt to find and delete the cart item
            $cartItem = Cart::where($conditions)->first();

            if ($cartItem) {
                $cartItem->update(['status' => 'cancelled', 'updated_at' => now()]);
                return ApiResponseService::success(config('constants.API_MSG.REC_REMOVE_SUCCESS'), [
                    'cart' => $cartItem,
                ]);
            } else {
                return ApiResponseService::error(404, config('constants.API_MSG.REC_NOT_FOUND'));
            }
        } catch (\Exception $e) {
            return ApiResponseService::error(500, 'Remove from cart failed: ' . $e->getMessage());
        }
    }

    public function getCartItems(Request $request)
    {
        try {
            // Determine user ID or guest session ID
            $userId = auth()->check() ? auth()->id() : null;
            $guestUserId = $userId ? null : $request->input('guest_user_id');

            // Set conditions based on user login status
            $conditions = [
                'status' => 'active',
            ];

            if ($userId) {
                $conditions['user_id'] = $userId;
            } else {
                $conditions['guest_user_id'] = $guestUserId;
            }

            // Retrieve cart items based on conditions
            $cartItems = Cart::where($conditions)->get();
            if ($cartItems->isEmpty()) {
                return response()->json([
                    'status' => 'error',
                    'message' => config('constants.API_MSG.REC_NOT_FOUND'),
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $cartItems,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve cart items: ' . $e->getMessage(),
            ], 500);
        }
    }
    public function updateCartQuantity(Request $request)
    {
        try {
            // Validate the incoming request
            $validated = $request->validate([
                'item_id' => 'required|exists:carts,item_id', // Make sure item exists in cart
                'quantity' => 'required|integer|min:1', // Ensure valid quantity (min 1)
            ]);

            // Get the authenticated user or guest user ID
            $userId = auth()->check() ? auth()->id() : null;
            $guestUserId = $userId ? null : $request->input('guest_user_id');

            // Set conditions to identify the cart item
            $conditions = [
                'item_id' => $validated['item_id'],
                'status' => 'active',
            ];

            // Add user ID or guest session ID to the conditions
            if ($userId) {
                $conditions['user_id'] = $userId;
            } else {
                $conditions['guest_user_id'] = $guestUserId;
            }

            // Retrieve the cart item
            $cartItem = Cart::where($conditions)->first();

            if (!$cartItem) {
                return response()->json([
                    'status' => 'error',
                    'message' => config('constants.API_MSG.REC_NOT_FOUND'),
                ], 404);
            }

            // Update the quantity
            $cartItem->quantity = $validated['quantity'];
            $cartItem->save();

            return response()->json([
                'status' => 'success',
                'message' => config('constants.API_MSG.REC_UPDATE_SUCCESS'),
                'data' => $cartItem,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => config('constants.API_MSG.REC_UPDATE_FAILED') . $e->getMessage(),
            ], 500);
        }
    }
}
