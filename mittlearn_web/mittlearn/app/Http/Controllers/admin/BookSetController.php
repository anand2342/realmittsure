<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\BookSeries;
use App\Models\BookSet;
use App\Models\Classes;
use App\Models\Medium;
use App\Models\Subject;
use Illuminate\Http\Request;

class BookSetController extends Controller
{
    public $data = [];
    public function createBookSet(Request $request,)
    {
        try {
            $this->data['board'] = Board::where('is_active', 1)->pluck('name', 'id')->toArray();
            $this->data['medium'] = Medium::where('is_active', 1)->pluck('name', 'id')->toArray();
            // $this->data['bookSeries'] = BookSeries::whereNotIn('id', [21, 22, 23, 24])->where('is_active', 1)->pluck('name', 'id')->toArray();
            $this->data['bookSeries'] = BookSeries::where('is_active', 1)->pluck('name', 'id')->toArray();
            $this->data['class'] = Classes::where('is_active', 1)->pluck('name', 'id')->toArray();
            $this->data['subject'] = Subject::where('is_active', 1)->pluck('name', 'id')->toArray();
            return view('admin.bookSet.add', $this->data);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }

    public function editBookSet($id)
    {
        try {
            $this->data['board'] = Board::where('is_active', 1)->pluck('name', 'id')->toArray();
            $this->data['medium'] = Medium::where('is_active', 1)->pluck('name', 'id')->toArray();
            $this->data['bookSeries'] = BookSeries::where('is_active', 1)->pluck('name', 'id')->toArray();
            $this->data['class'] = Classes::where('is_active', 1)->pluck('name', 'id')->toArray();
            $this->data['subject'] = Subject::where('is_active', 1)->pluck('name', 'id')->toArray();
            $this->data['data'] = BookSet::where('id', $id)->first();
            $this->data['selectedSubject'] = explode(',', $this->data['data']->subject_id);
            return view('admin.bookSet.add', $this->data);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }
    public function bookSetShow()
    {
        try {
            $this->data['data'] = BookSet::with('series','class')->orderBy('id', 'DESC')->paginate(config('constants.PAGINATION.default'));
            return view('admin.bookSet.index', $this->data);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }

    public function bookSetSave(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => "required|string|max:255|unique:book_sets,name,{$request->id}",
            'sku_code' => "required|string|max:255|unique:book_sets,sku_code,{$request->id}",
            'board_id' => 'required',
            'class_id' => 'required',
            'medium_id' => 'required',
            'series_id' => 'required',
            'subject_id' => 'required|array',
            'is_active' => 'required|boolean',
        ], ['is_active.required' => 'Status field is required']);
        try {
            if ($request->id > 0) {
                $success = config('constants.FLASH_REC_UPDATE_1');
                $error = config('constants.FLASH_REC_UPDATE_0');
            } else {
                $success = config('constants.FLASH_REC_ADD_1');
                $error = config('constants.FLASH_REC_ADD_0');
            }

            $subject = implode(',', $request->subject_id);
            $bookSet = BookSet::where('board_id', $request->board_id)
                ->where('name', $request->name)
                ->where('sku_code', $request->sku_code)
                ->where('medium_id', $request->medium_id)
                ->where('series_id', $request->series_id)
                ->where('class_id', $request->class_id)
                ->where('subject_id', $subject)
                ->where('is_active', $request->is_active)
                ->first();
            if ($bookSet) {
                return redirect()->back()->with(['error' => 'This Book set already exists']);
            }
            $data = $request->except(['_token']);
            $data['subject_id'] = $subject;
            $res = BookSet::updateOrCreate(
                ['id' => $request->id],
                $data
            );
            if ($res) {
                return redirect()->route('bookset.index')->with(['success' => $success]);
            } else {
                return redirect()->back()->with(['error' => $error]);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }

    public function bookSetDelete($id)
    {
        try {
            $data = BookSet::where('id', $id)->first();
            $data->delete();
            return redirect()->route('bookset.index')->with(['success' => config('constants.FLASH_REC_DELETE_1')]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => config('constants.FLASH_TRY_CATCH')]);
        }
    }
}
