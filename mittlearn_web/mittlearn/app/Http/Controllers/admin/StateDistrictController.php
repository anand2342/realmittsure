<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\BookSeries;
use App\Models\City;
use App\Models\CourseMetadataValue;
use App\Models\Medium;
use App\Models\Planner;
use App\Models\SchoolAssignedDigitalContent;
use App\Models\SchoolClass;
use App\Models\State;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class StateDistrictController extends Controller
{
    public $data = [];
    public function stateShow(Request $request)
    {
        // $data = BookSeries::paginate(10);
        $query = State::query();
        $perPageRecords = Session::get('per_page_records', config('constants.PAGINATION.default'));

        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $data = $query->paginate($perPageRecords);

        return view('admin.stateDistrict.index', ['data' => $data]);
    }

    public function editState($id)
    {
        $data = State::where('id', $id)->first();

        return view('admin.stateDistrict.add', ['data' => $data]);
    }

    public function createState()
    {
        return view('admin.stateDistrict.add');
    }
    public function stateSave(Request $request)
    {
        $request->validate([
            'name'      => 'required',
        ]);

        if ($request->id > 0) {
            $success = config('constants.FLASH_REC_UPDATE_1');
            $error = config('constants.FLASH_REC_UPDATE_0');
        } else {
            $success = config('constants.FLASH_REC_ADD_1');
            $error = config('constants.FLASH_REC_ADD_0');
        }
        $res = State::updateOrCreate(['id' => $request->id],   ['name' => $request->name, 'country_id' => 105]);
        if ($res) {
            return redirect()->route('state.district.index')->with(['success' => $success]);
        }
        return redirect()->back()->with(['error' => $error]);
    }




    public function districtShow(Request $request, $id)
    {
        $state = State::findOrFail($id);
        $perPageRecords = Session::get('per_page_records', config('constants.PAGINATION.default'));

        $query = City::where('state_id', $id);

        if ($request->filled('name')) {
            $query->where('city', 'like', '%' . $request->name . '%');
        }

        $data = $query->paginate($perPageRecords);

        return view('admin.stateDistrict.district-index', [
            'data' => $data,
            'state' => $state,
        ]);
    }


    public function editDistrict($id)
    {
        $data = City::where('id', $id)->with('state')->first();
        return view('admin.stateDistrict.district-add', ['data' => $data]);
    }

    public function createDistrict($id)
    {
        $states = State::pluck('name', 'id');
        $stateName = State::where('id', $id)->first();

        return view('admin.stateDistrict.district-add', ['states' => $states, 'stateName' => $stateName]);
    }
    public function districtSave(Request $request)
    {
        $request->validate([
            'city'      => 'required',
            'state_id'      => 'required',
        ]);

        if ($request->id > 0) {
            $success = config('constants.FLASH_REC_UPDATE_1');
            $error = config('constants.FLASH_REC_UPDATE_0');
        } else {
            $success = config('constants.FLASH_REC_ADD_1');
            $error = config('constants.FLASH_REC_ADD_0');
        }
        $res = City::updateOrCreate(['id' => $request->id],   ['city' => $request->city, 'state_id' =>  $request->state_id]);
        if ($res) {
            return redirect()->route('district.index', $request->state_id)->with(['success' => $success]);
        }
        return redirect()->back()->with(['error' => $error]);
    }
}
