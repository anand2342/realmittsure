<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Services\ApiResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function addToWishlist(Request $request)
    {
        try {

            // Get user_id if logged in, otherwise use session_id
            $userId    = $request->user_id ?? null;
            $sessionId = $request->guest_user_id;

            $conditions = [
                'course_id' => $request->course_id,
            ];

            // Add user_id to conditions if the user is logged in
            if ($userId) {
                $conditions['user_id']    = $userId;
                $conditions['session_id'] = $sessionId;
            } else {
                $conditions['session_id'] = $sessionId;
            }
            // Data to insert or update in the wishlist
            $wishlistData = [
                'item_type'        => $request->item_type,
                'added_at'         => now(),
                'status'           => $request->status,
                'created_by_admin' => false,
                'updated_at'       => now(),
            ];

            // Insert or update the wishlist item
            $wishlist = Wishlist::updateOrCreate($conditions, array_merge($wishlistData, [
                'user_id'    => $userId,
                'session_id' => $sessionId,
            ]));

            return ApiResponseService::success(config('constants.API_MSG.REC_ADD_SUCCESS'), [
                'wishlist' => $wishlist,
            ]);
        } catch (\Exception $e) {
            return ApiResponseService::error(500, 'Add to wishlist failed: ' . $e->getMessage());
        }
    }

    public function removeFromWishlist(Request $request)
    {
        // return $request;
        try {
            // Get user_id if logged in, otherwise use session_id
            $userId    = auth()->check() ? auth()->id() : null;
            $sessionId = $request->guest_user_id;

            // Set the conditions for finding the existing wishlist item
            $conditions = [
                'id'        => $request->wishlist_id,
                'course_id'   => $request->course_id,
            ];

            // Add user_id to conditions if logged in, else add session_id
            if ($userId) {
                $conditions['user_id']    = $userId;
                $conditions['session_id'] = $sessionId;
            } else {
                $conditions['session_id'] = $sessionId;
            }
            // Attempt to find and delete the wishlist item
            $wishlistItem = Wishlist::where($conditions)->first();

            if ($wishlistItem) {
                $wishlistItem->update(['status' => 'inactive', 'updated_at' => now()]);
                return ApiResponseService::success(config('constants.API_MSG.REC_REMOVE_SUCCESS'), [
                    'wishlist' => $wishlistItem,
                ]);
            } else {
                return ApiResponseService::error(404, config('constants.API_MSG.REC_NOT_FOUND'));
            }
        } catch (\Exception $e) {
            return ApiResponseService::error(500, 'Remove from wishlist failed: ' . $e->getMessage());
        }
    }

}
