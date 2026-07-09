<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicSession;
use App\Models\Board;
use App\Models\BookSeries;
use App\Models\Medium;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AcademicSessionController extends Controller
{
    public $data = [];
    // public function academicSessionShow()
    // {
    //     $data = AcademicSession::orderByRaw('is_active DESC')->orderBy('id', 'DESC')->paginate(config('constants.PAGINATION.default'));
    //     dd($data);
    //     return view('admin.academicSession.index', ['data' => $data]);
    // }

    public function academicSessionShow()
    {
        $data = AcademicSession::select('name', DB::raw('MAX(is_active) as is_active'), DB::raw('MAX(id) as id'))
            ->groupBy('name')
            ->orderBy('is_active', 'DESC')
            ->orderBy('id', 'DESC')
            ->paginate(config('constants.PAGINATION.default'));
        return view('admin.academicSession.index', ['data' => $data]);
    }

    public function editAcademicSession($id)
    {
        $sessionName = AcademicSession::where('id', $id)->value('name');
        $sessions  = AcademicSession::where('name', $sessionName)->get();
        $data = [
            'id' => $sessions->first()->id,
            'name' => $sessionName,
            'is_active' => $sessions->first()->is_active,
            'batches' => $sessions->map(function ($session) {
                return [
                    'batch_name' => $session->batch_name,
                    'start_date' => $session->start_date,
                    'end_date' => $session->end_date
                ];
            })->toArray()
        ];
        return view('admin.academicSession.add', ['data' => $data]);
    }

    public function createAcademicSession()
    {
        return view('admin.academicSession.add');
    }

    // public function academicSessionSave(Request $request)
    // {
    //     dd($request->all());
    //     $request->validate([
    //         'batches.*.start_date' => 'required|date',
    //         'batches.*.end_date' => 'required|date|after_or_equal:batches.*.start_date',
    //         'is_active' => 'required|boolean',
    //     ], ['is_active.required' => 'Status field is required']);

    //     // if ($request->is_active == 1 && AcademicSession::where('is_active', 1)->exists()) {
    //     //     return back()->withErrors(['is_active' => 'A session is already active. Please deactivate it first.'])->withInput();
    //     // }
    //     if ($request->id > 0) {
    //         $success = config('constants.FLASH_REC_UPDATE_1');
    //         $error = config('constants.FLASH_REC_UPDATE_0');
    //     } else {
    //         $success = config('constants.FLASH_REC_ADD_1');
    //         $error = config('constants.FLASH_REC_ADD_0');
    //     }
    //     foreach ($request->batches as $batch) {
    //         $res = AcademicSession::updateOrCreate(['id' => $request->id],   ['name' => $request->name, 'is_active' => $request->is_active, 'batch_name' => $batch['batch_name'], 'start_date' => $batch['start_date'], 'end_date' => $batch['end_date']]);
    //     }
    //     if ($res) {
    //         return redirect()->route('academic.session.index')->with(['success' => $success]);
    //     }
    //     return redirect()->back()->with(['error' => $error]);
    // }

    public function academicSessionSave(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'is_active' => 'required|boolean',
            'batches' => 'required|array|min:1',
            'batches.*.batch_name' => 'required',
            'batches.*.start_date' => 'required|date',
            'batches.*.end_date' => 'required|date|after_or_equal:batches.*.start_date',
        ], [
            'is_active.required' => 'Status field is required',
            'batches.*.batch_name.required' => 'Batch name is required',
        ]);

        // Check for active session conflict (excluding current session if editing)
        // if ($request->is_active == 1 && AcademicSession::where('is_active', 1)
        //     ->when($request->id, function ($query, $id) {
        //         return $query->where('id', '!=', $id);
        //     })
        //     ->exists()
        // ) {
        //     return back()->withErrors(['is_active' => 'A session is already active. Please deactivate it first.'])->withInput();
        // }

        DB::beginTransaction();
        try {
            $existingBatchIds = [];
            if ($request->id) {
                $existingBatchIds = AcademicSession::where('name', $request->name)
                    ->where('id', '!=', $request->id)
                    ->pluck('id')
                    ->toArray();
            }

            $createdRecords = [];
            foreach ($request->batches as $batchData) {
                if (isset($batchData['id']) && in_array($batchData['id'], $existingBatchIds)) {
                    $record = AcademicSession::find($batchData['id']);
                    $record->update([
                        'batch_name' => $batchData['batch_name'],
                        'start_date' => $batchData['start_date'],
                        'end_date' => $batchData['end_date'],
                        'is_active' => $request->is_active
                    ]);
                    $createdRecords[] = $record->id;
                } else {
                    $record = AcademicSession::create([
                        'name' => $request->name,
                        'batch_name' => $batchData['batch_name'],
                        'start_date' => $batchData['start_date'],
                        'end_date' => $batchData['end_date'],
                        'is_active' => $request->is_active
                    ]);
                    $createdRecords[] = $record->id;
                }
            }

            // If editing, delete batches that weren't included in the request
            if ($request->id) {
                AcademicSession::where('name', $request->name)
                    ->whereNotIn('id', $createdRecords)
                    ->delete();
            }

            DB::commit();

            $message = $request->id
                ? config('constants.FLASH_REC_UPDATE_1')
                : config('constants.FLASH_REC_ADD_1');

            return redirect()->route('academic.session.index')->with('success', $message);
        } catch (\Exception $e) {
            DB::rollBack();
            $message = $request->id
                ? config('constants.FLASH_REC_UPDATE_0')
                : config('constants.FLASH_REC_ADD_0');

            return back()->with('error', $message)->withInput();
        }
    }

    public function academicSessionDelete($id)
    {
        $data = AcademicSession::where('id', $id)->first();
        $data->delete();
        return redirect()->route('academic.session.index')->with(['success' => config('constants.FLASH_REC_DELETE_1')]);
    }
}
