<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionPlanCourse;
use App\Models\SubscriptionPlanFeature;
use App\Models\SubscriptionPlanPack;
use App\Models\SubscriptionPlanPrice;
use App\Models\TransactionLog;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public $data = [];

    public function index()
    {
        $this->data['datalist'] = SubscriptionPlan::paginate(config('constants.PAGINATION.default'));
        return view('admin.plans.list_plan', $this->data);
    }

    public function ViewPlan($id)
    {
        $this->data['plan_data'] = SubscriptionPlan::findOrFail($id);
        if (!$this->data['plan_data']) {
            return redirect()->back()->with(['error' => config('constants.FLASH_REC_NOT_FOUND')]);
        }
        return view('plans.view_plan', $this->data);
    }

    public function addPlan()
    {
        return view('admin.plans.add_edit_plan');
    }

    public function editPlan($id)
    {
        $this->data['data_row'] = SubscriptionPlan::with('subscriptionPlanFeature', 'subscriptionPlanPrice', 'subscriptionPlanPack')->whereid($id)->first();
        if (!$this->data['data_row']) {
            return redirect()->back()->with(['error' => config('constants.FLASH_REC_NOT_FOUND')]);
        }

        return view('admin.plans.add_edit_plan', $this->data);
    }

    public function savePlan(Request $request)
    {
        // dd($request);
        if ($request->id > 0) {
            $success = config('constants.FLASH_REC_UPDATE_1');
            $error = config('constants.FLASH_REC_UPDATE_0');
        } else {
            $success = config('constants.FLASH_REC_ADD_1');
            $error = config('constants.FLASH_REC_ADD_0');
        }

        $dataArr = [
            "name" => $request->name,
            "plan_type" => $request->plan_type,
            "currency" => $request->currency,
            "description" => $request->description,
            "bg_color" => $request->bg_color,
            "sort_order" => $request->sort_order,
            "status" => $request->status,
            "is_free_trial" => $request->is_free_trial,
            "is_recommended" => $request->is_recomanded,
        ];

        // set all free-plans 0
        if ($dataArr['is_free_trial'] == 1) {
            SubscriptionPlan::where('id', '>', 0)->update(['is_free_trial' => 0]);
        }
        // set all recomanded-plans 0
        if ($dataArr['is_recommended'] == 1) {
            SubscriptionPlan::where('id', '>', 0)->update(['is_recommended' => 0]);
        }

        $res = SubscriptionPlan::updateOrCreate(['id' => $request->id], $dataArr);

        if ($res) {

            // Handle prices
            SubscriptionPlanPrice::wherePlanId($res->id)->delete();
            if ($request->has('price_row')) {
                $priceDataArr = [];
                foreach ($request->price_row as $priceRow) {
                    $filteredDurationDays = findInArray(config('constants.DURATION_TYPES'), 'value', $priceRow['duration_type']);
                    $priceRow['final_price'] = calculatePlanFinalPrice($priceRow);
                    $priceDataArr[] = [
                        "id" => $priceRow['id'],
                        "plan_id" => $res->id,
                        "duration_type" => $priceRow['duration_type'],
                        "duration_days" => $filteredDurationDays ? $filteredDurationDays['days'] : 0,
                        "price" => $priceRow['price'],
                        "discount_type" => $priceRow['discount_type'],
                        "discount_value" => $priceRow['discount_value'],
                        "final_price" => $priceRow['final_price'],
                    ];
                }
                SubscriptionPlanPrice::insert($priceDataArr);
            }

            // Handle Features
            SubscriptionPlanFeature::wherePlanId($res->id)->delete();
            if ($request->has('feature_row')) {
                $featureDataArr = [];
                foreach ($request->feature_row as $featureRow) {
                    $featureDataArr[] = [
                        "id" => $featureRow['id'],
                        "plan_id" => $res->id,
                        "title" => $featureRow['title'],
                    ];
                }
                SubscriptionPlanFeature::insert($featureDataArr);
            }

            // Handle Pack
            SubscriptionPlanPack::wherePlanId($res->id)->delete();
            if ($request->has('pack_rows')) {
                $packRowsDataArr = [];
                foreach ($request->pack_rows as $packRow) {
                    $packRowsDataArr[] = [
                        "plan_id" => $res->id,
                        "pack_type" => $request->pack_type,
                        "set_of_courses" => $packRow['set_of_courses'] ?? null,
                        "discount_type" => $packRow['discount_type'],
                        "discount_value" => $packRow['discount_value'],
                        "free_course_academic" => $packRow['free_course_academic'] ?? null,
                        "free_course_non_academic" => $packRow['free_course_non_academic'] ?? null,
                    ];
                }
                SubscriptionPlanPack::insert($packRowsDataArr);
            }
            // Handle Features
            SubscriptionPlanCourse::wherePlanId($res->id)->delete();
            if ($request->has('course_ids')) {
                $courseIdsDataArr = [];
                foreach ($request->course_ids as $courseRow) {
                    $courseIdsDataArr[] = [
                        "plan_id" => $res->id,
                        "course_id" => $courseRow,
                    ];
                }
                SubscriptionPlanCourse::insert($courseIdsDataArr);
            }
            return redirect()->back()->with(['success' => $success]);
        }
        return redirect()->back()->with(['error' => $error]);
    }

    public function destroy($id)
    {

        // Find the plan by ID
        $plan = SubscriptionPlan::findOrFail($id);
        if ($plan) {
            // Check if there are any subscriptions for this plan
            if ($plan->purchases()->exists()) {
                // Soft delete the plan
                $plan->delete();
                return redirect()->back()->with(['success' => config('constants.FLASH_REC_DELETE_1')]);
            } else {
                // Force delete the plan since no subscriptions exist
                $plan->forceDelete();
                return redirect()->back()->with(['success' => config('constants.FLASH_REC_DELETE_1')]);
            }
        }
        return redirect()->back()->with(['error' => config('constants.FLASH_REC_DELETE_0')]);
    }
    public function purchaseReport()
    {
        $this->data['datalist'] = TransactionLog::where('payment_state', 'success')->with('userDetail')->paginate(config('constants.PAGINATION.default'));
    $this->data['totalAmountAll'] = TransactionLog::where('payment_state', 'success')->sum('total_amount');
        return view('admin.plans.purchase_report', $this->data);
    }
}
