<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;

use App\Models\BookSeries;
use App\Models\Classes;
use App\Models\PlanCourseBucket;
use Illuminate\Http\Request;

class CourseBucketController extends Controller
{
    public $data = [];

    public function index()
    {
        $this->data['datalist'] = PlanCourseBucket::with('bookSeries')->paginate(config('constants.PAGINATION.default'));
        return view('admin.coursesBucket.list_plan', $this->data);
    }

    public function ViewPlan($id)
    {
        $this->data['plan_data'] = PlanCourseBucket::findOrFail($id);
        if (!$this->data['plan_data']) {
            return redirect()->back()->with(['error' => config('constants.FLASH_REC_NOT_FOUND')]);
        }
        return view('coursesBucket.view_plan', $this->data);
    }

    public function addPlan()
    {
        $this->data['series'] = BookSeries::where('id', 19)->pluck('name', 'id')->toArray();
        $this->data['class'] = Classes::pluck('name', 'id')->toArray();
        return view('admin.coursesBucket.add_edit_plan', $this->data);
    }

    public function editPlan($id)
    {
        $this->data['data_row'] = PlanCourseBucket::whereid($id)->first();
        $this->data['series'] = BookSeries::where('id', 19)->pluck('name', 'id')->toArray();

        if (!$this->data['data_row']) {
            return redirect()->back()->with(['error' => config('constants.FLASH_REC_NOT_FOUND')]);
        }

        return view('admin.coursesBucket.add_edit_plan', $this->data);
    }

    public function savePlan(Request $request)
    {
        $messages = [
            'add' => [
                'success' => config('constants.FLASH_REC_ADD_1'),
                'error' => config('constants.FLASH_REC_ADD_0'),
            ],
            'update' => [
                'success' => config('constants.FLASH_REC_UPDATE_1'),
                'error' => config('constants.FLASH_REC_UPDATE_0'),
            ],
        ];

        $isUpdate = $request->id > 0;
        $messageSet = $isUpdate ? $messages['update'] : $messages['add'];

        $dataArr = $request->only([
            'series',
            'class',
            'subject',
            'discount_type',
            'discount_value',
            'is_active',
        ]);

        $res = PlanCourseBucket::updateOrCreate(['id' => $request->id], $dataArr);

        return $res
            ? redirect()->route('course-bucket.index')->with('success', $messageSet['success'])
            : redirect()->back()->with('error', $messageSet['error']);
    }

    public function destroy($id)
    {

        // Find the plan by ID
        $plan = PlanCourseBucket::findOrFail($id);
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
}
