<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Category;
use App\Models\User;
use App\Models\Course;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Auth;
use Carbon\Carbon;

class CouponController extends Controller
{   

    public $data = [];
    
    public function index()
    {
        $this->data['couponsData'] = Coupon::where('is_active',1)->paginate(config('constants.PAGINATION.default'));

        return view('admin.coupons.index', $this->data);
    }


    public function create()
    {
        $coupon = null;

        // Options for "Applicable For"
        $applicableForOptions = [
             'all_users' => 'All Users',
            'new_users' => 'New Users',
            'existing_users' => 'Existing Users',
            'category' => 'Category',
            'courses' => 'Courses',
            'course_bundle' => 'Course Bundle',
            'cart' => 'Cart',
        ];

        $applicable_for_ids = [];

        $categories = Category::where('status', 1)->where('parent_id', null)->pluck('name', 'id');
        $users = User::pluck('name', 'id');
        $courses = Course::pluck('course_name', 'id');

        return view('admin.coupons.add_edit_coupons', compact('coupon', 'applicableForOptions', 'applicable_for_ids', 'categories', 'users', 'courses'));
    }


   
    public function edit($id)
    {
        $coupon = Coupon::findOrFail($id);

        $coupon->start_date = $coupon->start_date ? Carbon::parse($coupon->start_date)->format('Y-m-d') : null;
        $coupon->end_date = $coupon->end_date ? Carbon::parse($coupon->end_date)->format('Y-m-d') : null;

        $applicable_for_ids = [];
        if ($coupon->applicable_for == 'category' && !empty($coupon->applicable_for_ids)) {
            $applicable_for_ids = explode(',', $coupon->applicable_for_ids); 
        } elseif ($coupon->applicable_for == 'existing_users' && !empty($coupon->applicable_for_ids)) {
            $applicable_for_ids = explode(',', $coupon->applicable_for_ids);
        } elseif ($coupon->applicable_for == 'courses' && !empty($coupon->applicable_for_ids)) {
            $applicable_for_ids = explode(',', $coupon->applicable_for_ids);
        }


        // Default options for applicable_for
        $applicableForOptions = [
            'all_users' => 'All Users',
            'new_users' => 'New Users',
            'existing_users' => 'Existing Users',
            'category' => 'Category',
            'courses' => 'Courses',
            'course_bundle' => 'Course Bundle',
            'cart' => 'Cart',
        ];


        $categories = Category::where('status', 1)->where('parent_id', null)->pluck('name', 'id');
        $users = User::pluck('name', 'id');
        $courses = Course::pluck('course_name', 'id');

        return view('admin.coupons.add_edit_coupons', compact('coupon', 'applicableForOptions', 'applicable_for_ids', 'categories', 'users', 'courses'));
    }


    public function storeAndUpdate(Request $request, $id = null)
    {

        $sanitizedData = $request->all();

        $validator = Validator::make($sanitizedData, [
            'code' => [
                'required',
                'string',
                'unique:coupons,code,' . ($id ? $id : 'NULL') . ',id',
                'regex:/^[A-Z0-9]+$/',
            ],
            'discount_type' => 'required|in:flat,percent',
            'discount_value' => 'required|numeric',  
           
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $userId = Auth::user()->id;

        if ($id) {
            $couponData = Coupon::findOrFail($id);
        } else {
            $couponData = new Coupon;
        }

        $couponData->code = strtoupper($request->code);
        $couponData->discount_type = $request->discount_type;
        $couponData->discount_value = $request->discount_value;
        $couponData->min_cart_value = $request->min_cart_value;
        $couponData->max_cart_value = $request->max_cart_value;
        $couponData->upto_discount = $request->upto_discount;
        $couponData->applicable_for = $request->applicable_for;

        if (in_array($request->applicable_for, ['category', 'existing_users', 'courses'])) {
            if ($request->has('applicable_for_ids') && is_array($request->applicable_for_ids)) {
                $newApplicableForIds = array_unique($request->applicable_for_ids);

                $newApplicableForIdsString = implode(',', $newApplicableForIds);

                if ($couponData->applicable_for_ids !== $newApplicableForIdsString) {
                    $couponData->applicable_for_ids = $newApplicableForIdsString;
                }
            } else {
                $couponData->applicable_for_ids = null;
            }
        } else {
            $couponData->applicable_for_ids = null;
        }

        $couponData->usage_limit = $request->usage_limit;
        $couponData->per_user_limit = $request->per_user_limit;
        // $couponData->uses_frequency = $request->uses_frequency;
        $couponData->start_date = Carbon::parse($request->start_date)->format('Y-m-d');
        $couponData->end_date = Carbon::parse($request->end_date)->format('Y-m-d');
        $couponData->is_active = $request->is_active;
        $couponData->is_clubable = $request->is_clubable;
        $couponData->added_by = $userId;

        //echo "<pre>"; print_r($couponData); die();

        if ($couponData->save()) {
            return redirect()->route('coupon.index')->with('success', $id ? 'Coupon updated successfully' : 'Coupon created successfully');
        } else {
            return redirect()->route('coupon.index')->with('error', 'Something went wrong.');
        }
    }





    //V Old okay code for bakcup-->
    // public function storeAndUpdate(Request $request, $id = null)
    // {

    //     $sanitizedData = $request->all();

    //     $validator = Validator::make($sanitizedData, [
    //         'code' => 'required|string|unique:coupons,code,' . ($id ? $id : 'NULL') . ',id',
    //         'discount_value' => 'required|numeric',
    //         'applicable_for' => 'required|string',
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect()->back()->withErrors($validator)->withInput();
    //     }

    //     $userId = Auth::user()->id;

    //     if ($id) {
    //         $couponData = Coupon::findOrFail($id);
    //     } else {
    //         $couponData = new Coupon;
    //     }

    //     $couponData->code = $request->code;
    //     $couponData->discount_type = $request->discount_type;
    //     $couponData->discount_value = $request->discount_value;
    //     $couponData->min_cart_value = $request->min_cart_value;
    //     $couponData->max_cart_value = $request->max_cart_value;
    //     $couponData->upto_discount = $request->upto_discount;
    //     $couponData->applicable_for = $request->applicable_for;

    //     if (in_array($request->applicable_for, ['category', 'existing_users', 'courses'])) {
    //         if ($request->has('applicable_for_ids') && is_array($request->applicable_for_ids)) {
    //             $newApplicableForIds = array_unique($request->applicable_for_ids);

    //             $newApplicableForIdsString = implode(',', $newApplicableForIds);

    //             if ($couponData->applicable_for_ids !== $newApplicableForIdsString) {
    //                 $couponData->applicable_for_ids = $newApplicableForIdsString;
    //             }
    //         } else {
    //             $couponData->applicable_for_ids = null;
    //         }
    //     } else {
    //         $couponData->applicable_for_ids = null;
    //     }

    //     $couponData->usage_limit = $request->usage_limit;
    //     $couponData->per_user_limit = $request->per_user_limit;
    //     $couponData->uses_frequency = $request->uses_frequency;
    //     $couponData->start_date = Carbon::parse($request->start_date)->format('Y-m-d');
    //     $couponData->end_date = Carbon::parse($request->end_date)->format('Y-m-d');
    //     $couponData->is_active = $request->is_active;
    //     $couponData->is_clubable = $request->is_clubable;
    //     $couponData->added_by = $userId;

    //     //echo "<pre>"; print_r($couponData); die();

    //     if ($couponData->save()) {
    //         return redirect()->route('coupon.index')->with('success', $id ? 'Coupon updated successfully' : 'Coupon created successfully');
    //     } else {
    //         return redirect()->route('coupon.index')->with('error', 'Something went wrong.');
    //     }
    // }





    public function destroyCoupon($id)
    {
        try {
            $coupon = Coupon::findOrFail($id);
            $coupon->delete();

            return redirect()->route('coupon.index')->with('success', 'Coupon deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('coupon.index')->with('error', 'An error occurred while deleting the coupon: ' . $e->getMessage());
        }
    }




  
}
