<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Classes;
use App\Models\JaduiPitara;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JaduiController extends Controller
{
    //
    public $data = [];
    public function jaduiPitara()
    {
        return view('admin.jaduiPitara.jadui-pitara');
    }
   
    public function jaduiPitaraClassUpdate(Request $request)
    {
        try {
            JaduiPitara::truncate();

            foreach ($request->class as $id) {
                $classes = new JaduiPitara();
                $classes->jadui_pitara_classes_id = $id;
                $classes->save();
            }

            $success = config('constants.FLASH_REC_ADD_1');
            return redirect()->back()->with(['success' => $success]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }
    public function jaduiPitaraSeries(Request $request)
    {
        foreach ($request->class_id as $index => $class_id) {
            $existingData = JaduiPitara::where('jadui_pitara_classes_id', $class_id)->get();
            if ($existingData->isNotEmpty()) {
                JaduiPitara::where('jadui_pitara_classes_id', $class_id)->delete();
            }
            // if (!empty($request->series_id[$index])) {
            if (!empty(array_filter($request->series_id[$index]))) {
                foreach ($request->series_id[$index] as $seriesIndex => $series_id) {
                    // Ensure subjects exist for the selected series
                    $subjects = isset($request->subject[$index][$seriesIndex])
                        ? implode(',', (array) $request->subject[$index][$seriesIndex])
                        : null;

                    // Save data for each series under the same class
                    JaduiPitara::updateOrCreate(
                        [
                            'id'        => $request->id[$index][$seriesIndex] ?? null, // Ensure unique per series
                        ],
                        [
                            'jadui_pitara_classes_id'   => $class_id,
                            'class_id'   => $class_id,
                            'series_id'  => $series_id,
                            'subject_id' => $subjects,
                            'created_by' => Auth::id(),
                        ]
                    );
                }
            }
        }

        return redirect()->back()->with(['success' => config('constants.FLASH_REC_ADD_1')]);
    }
}
