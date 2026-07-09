<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\BookSeries;
use App\Models\FrontendCoursesView;
use App\Models\Role;
use App\Models\Setting;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public $data = [];


    public function add()
    {
        $this->data['settings'] = Setting::pluck('field_value', 'field_name')->toArray();
        $this->data['roles'] = Role::where('is_active', 1)->pluck('role_slug', 'role_name')->toArray();
        // $this->data['series'] = 
        return view('admin.settings.add', $this->data);
    }

    public function save(Request $request)
    {
        $settingsData = $request->except(['_token', '_method']);

        // Define all image fields and their settings key names
        $imageFields = [
            'site_logo' => 'uploads/logo/',
            'play_logo' => 'uploads/logo/',
            'play_image' => 'uploads/logo/',
            'app_logo' => 'uploads/logo/',
            'app_image' => 'uploads/logo/'
        ];

        foreach ($imageFields as $fieldName => $storagePath) {
            if ($request->hasFile($fieldName)) {
                $existingSetting = Setting::where('field_name', $fieldName)->first();

                // Delete old file if exists
                if ($existingSetting && Storage::disk('public')->exists($storagePath . $existingSetting->field_value)) {
                    Storage::disk('public')->delete($storagePath . $existingSetting->field_value);
                }

                // Process new file
                $file = $request->file($fieldName);
                $extension = $file->getClientOriginalExtension();
                $filename = $fieldName . '_' . time() . '_' . uniqid() . '.' . $extension;

                Storage::disk('public')->put($storagePath . $filename, file_get_contents($file));
                $settingsData[$fieldName] = $filename;
            }
        }

        // Save all settings
        foreach ($settingsData as $fieldName => $fieldValue) {
            Setting::updateOrInsert(
                ['field_name' => $fieldName],
                ['field_value' => $fieldValue]
            );
        }

        return redirect()->route('setting.add')->with(['success' => config('constants.FLASH_REC_ADD_1')]);
    }
    public function seriesSave(Request $request)
    {
        // Basic Laravel validation
        $request->validate([
            'course_sets.*.series_id' => 'required',
            'course_sets.*.classes_ids' => 'required|array|min:1',
            'course_sets.*.classes_ids.*' => 'required',
        ], [
            'course_sets.*.series_id.required' => 'Each series must be selected.',
            'course_sets.*.classes_ids.required' => 'Each course set must include at least one class.',
            'course_sets.*.classes_ids.*.required' => 'Each class must be selected.',
        ]);


        // Flatten all class IDs from all course sets
        $allClassIds = [];
        foreach ($request->course_sets as $courseSetData) {
            $classIds = $courseSetData['classes_ids'] ?? [];
            $allClassIds = array_merge($allClassIds, $classIds);
        }

        // Check for duplicates
        if (count($allClassIds) !== count(array_unique($allClassIds))) {
            return redirect()->back()->with([
                'error' => 'Please ensure classes are unique across the complete series sets.'
            ]);
        }

        // Save data
        foreach ($request->course_sets as $courseSetData) {
            $classesIds = $courseSetData['classes_ids'] ?? [];

            $data = [
                'series_id' => $courseSetData['series_id'],
                'classes_ids' => implode(',', $classesIds),
            ];

            if (!empty($courseSetData['id'])) {
                $courseSet = FrontendCoursesView::findOrFail($courseSetData['id']);
                $courseSet->update($data);
            } else {
                FrontendCoursesView::create($data);
            }
        }

        return redirect()->back()->with(['success' => config('constants.FLASH_REC_ADD_1')]);
    }
}
