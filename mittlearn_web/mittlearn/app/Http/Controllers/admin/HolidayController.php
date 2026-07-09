<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Holiday;
use App\Models\State;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HolidayController extends Controller
{
    public $data = [];
    //
    public function index()
    {
        try {
            $perPageRecords = Session::get('per_page_records', config('constants.PAGINATION.default'));
            $this->data['holidays'] = Holiday::orderBy('created_at', 'DESC')->paginate($perPageRecords);
            return view('admin.holidayManagement.index', $this->data);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }
    public function add()
    {
        try {

            $this->data['state'] = State::pluck('name', 'id')->toArray();
            $this->data['selectedStates'] = array_keys($this->data['state']); // Select all states by default
            return view('admin.holidayManagement.add', $this->data);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }
    public function edit($id)
    {
        try {
            $this->data['holiday'] = Holiday::find($id);
            $this->data['selectedStates'] = explode(',', $this->data['holiday']->state_id);

            $this->data['state'] = State::pluck('name', 'id')->toArray();
            return view('admin.holidayManagement.add', $this->data);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }
    public function save(request $request)
    {
        $request->validate([
            'holiday_name' => 'required|string',
            'from_date' => 'required|date',
            'to_date' => 'required|date',
            'state_id' => 'required|array',
            'day' => 'required|string',
            'is_active' => 'required'
        ]);
        try {
            if ($request->id > 0) {
                $success = config('constants.FLASH_REC_UPDATE_1');
                $error = config('constants.FLASH_REC_UPDATE_0');
            } else {
                $success = config('constants.FLASH_REC_ADD_1');
                $error = config('constants.FLASH_REC_ADD_0');
            }
            $state = State::all()->pluck('name', 'id')->toArray();

            if (in_array('all', $request->state_id)) {
                $states = array_keys($state);
            } else {
                $states = $request->state_id;
            }
            $data = $request->except(['_token', 'state_id']);
            $data['state_id'] = implode(',', $states);
            $holiday = Holiday::updateOrCreate(
                ['id' => $request->id],
                $data
            );
            if ($holiday) {
                if ($request->id) {
                    return redirect()->route('index.holiday')->with(['success' => $success]);
                }
                return redirect()->back()->with(['success' => $success]);
            } else {
                return redirect()->back()->with(['error' => $error]);
            }
        } catch (\Exception $e) {

            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }
    public function delete($id)
    {
        $data = Holiday::find($id);

        if ($data) {
            $data->delete();
            return redirect()->back()->with(['success' => config('constants.FLASH_REC_DELETE_1')]);
        } else {
            return redirect()->back()->with(['error' => 'Holiday not found.']);
        }
    }
}
