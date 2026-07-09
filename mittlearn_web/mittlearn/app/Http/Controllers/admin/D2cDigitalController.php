<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Classes;
use App\Models\Course;
use App\Models\D2cDigitalContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;

class D2cDigitalController extends Controller
{
    //
    public $data = [];
    public function d2cCategoryIndex()
    {
        // $cat = ['academic_activities', 'academic-digital-content'];
        $cat = ['academic_activities', 'academic-digital-content', 'olympiad'];
        $this->data['d2ccategory'] = Category::where('status', 1)
            ->where('parent_id', 1)
            ->whereNotIn('slug', $cat)
            ->paginate(config('constants.PAGINATION.default'));
        $this->data['categories'] = Category::getCategories();


        return view('admin.d2cDigitalContent.d2c-category-index', $this->data);
    }
    public function d2cDigitalContent($id)
    {
        $this->data['category_id'] = $id;
        $this->data['categoryName'] = Category::where('status', 1)->where('id', $id)->value('name');
        return view('admin.d2cDigitalContent.d2c-assignment', $this->data);
    }
    public function talentDigitalContent($id)
    {
        $this->data['category_id'] = 2;
        $this->data['sub_category_id'] = $id;
        $this->data['categoryName'] = Category::where('status', 1)->where('id', $id)->value('name');
        $this->data['courses'] = Course::where('category_id',2)->where('sub_category_id', $id)
            ->where('is_active', 1)
            ->whereNull('deleted_at')
            ->get();
        return view('admin.d2cDigitalContent.talent-skill-assignment', $this->data);
    }

    // public function d2cClassUpdate(Request $request)
    // {
    //     try {
    //         $categoryId = $request->category_id;
    //         $parentCategoryId = $request->parent_category_id ?? null;
    //         $submittedClassIds = $request->class ?? [];
    //         // Delete classes NOT in the submitted list, but only for this category
    //         D2cDigitalContent::where('category_id', $parentCategoryId)
    //             ->where('sub_category_id', $categoryId)
    //             ->whereNotIn('class_id', $submittedClassIds)
    //             ->delete();

    //         // Insert new class entries if not already existing for this category
    //         foreach ($submittedClassIds as $classId) {
    //             $exists = D2cDigitalContent::where('sub_category_id', $categoryId)
    //                 ->where('class_id', $classId)
    //                 ->exists();

    //             if (!$exists) {
    //                 D2cDigitalContent::create([
    //                     'category_id' => $parentCategoryId,
    //                     'sub_category_id' => $categoryId,
    //                     'class_id'    => $classId,
    //                     'created_by'  => Auth::id(),
    //                 ]);
    //             }
    //         }

    //         return redirect()->back()->with(['success' => config('constants.FLASH_REC_ADD_1')]);
    //     } catch (\Exception $e) {
    //         return redirect()->back()->with(['error' => $e->getMessage()]);
    //     }
    // }
    public function d2cClassUpdate(Request $request)
    {
        try {
            $categoryId = $request->category_id;
            $parentCategoryId = $request->parent_category_id ?? null;
            $classIds = $request->class ?? [];

            // Handle medium_ids: if none are provided or are 0, treat as null
            $mediumInput = $request->medium_id ?? [];

            $mediumIds = empty($mediumInput)
                ? [null] // No medium selected — use null
                : collect($mediumInput)
                ->map(fn($id) => $id == 0 ? null : $id)
                ->toArray();

            // Generate all combinations of class_id and medium_id
            $submittedPairs = collect($classIds)
                ->crossJoin($mediumIds)
                ->map(fn($pair) => [
                    'class_id' => $pair[0],
                    'medium_id' => $pair[1],
                ]);

            // Fetch existing records for comparison
            $existingRecords = D2cDigitalContent::where('category_id', $parentCategoryId)
                ->where('sub_category_id', $categoryId)
                ->get(['id', 'class_id', 'medium_id']);

            // Delete records that no longer exist in the new list
            foreach ($existingRecords as $record) {
                $found = $submittedPairs->contains(function ($pair) use ($record) {
                    return $pair['class_id'] == $record->class_id &&
                        $pair['medium_id'] == $record->medium_id;
                });

                if (!$found) {
                    $record->delete();
                }
            }

            // Insert new records that don't already exist
            foreach ($submittedPairs as $pair) {
                $exists = D2cDigitalContent::where('category_id', $parentCategoryId)
                    ->where('sub_category_id', $categoryId)
                    ->where('class_id', $pair['class_id'])
                    ->where(function ($query) use ($pair) {
                        if ($pair['medium_id'] === null) {
                            $query->whereNull('medium_id');
                        } else {
                            $query->where('medium_id', $pair['medium_id']);
                        }
                    })
                    ->exists();

                if (!$exists) {
                    D2cDigitalContent::create([
                        'category_id'     => $parentCategoryId,
                        'sub_category_id' => $categoryId,
                        'class_id'        => $pair['class_id'],
                        'medium_id'       => $pair['medium_id'], // Can be null
                        'created_by'      => Auth::id(),
                    ]);
                }
            }

            return redirect()->back()->with(['success' => config('constants.FLASH_REC_ADD_1')]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }
    public function d2cClassContentUpdate(Request $request)
    {
        try {
            $categoryId = $request->category_id;
            $parentCategoryId = $request->parent_category_id ?? null;
            $classIds = $request->class ?? [];

            // Handle medium_ids: if none are provided or are 0, treat as null
            $mediumInput = $request->medium_id ?? [];

            $mediumIds = empty($mediumInput)
                ? [null] // No medium selected — use null
                : collect($mediumInput)
                ->map(fn($id) => $id == 0 ? null : $id)
                ->toArray();

            // Generate all combinations of class_id and medium_id
            $submittedPairs = collect($classIds)
                ->crossJoin($mediumIds)
                ->map(fn($pair) => [
                    'class_id' => $pair[0],
                    'medium_id' => $pair[1],
                ]);

            // Fetch existing records for comparison
            $existingRecords = D2cDigitalContent::where('category_id', $parentCategoryId)
                ->where('sub_category_id', $categoryId)
                ->get(['id', 'class_id', 'medium_id']);

            // Delete records that no longer exist in the new list
            foreach ($existingRecords as $record) {
                $found = $submittedPairs->contains(function ($pair) use ($record) {
                    return $pair['class_id'] == $record->class_id &&
                        $pair['medium_id'] == $record->medium_id;
                });

                if (!$found) {
                    $record->delete();
                }
            }

            // Insert new records that don't already exist
            foreach ($submittedPairs as $pair) {
                $exists = D2cDigitalContent::where('category_id', $parentCategoryId)
                    ->where('sub_category_id', $categoryId)
                    ->where('class_id', $pair['class_id'])
                    ->where(function ($query) use ($pair) {
                        if ($pair['medium_id'] === null) {
                            $query->whereNull('medium_id');
                        } else {
                            $query->where('medium_id', $pair['medium_id']);
                        }
                    })
                    ->exists();

                if (!$exists) {
                    D2cDigitalContent::create([
                        'category_id'     => $parentCategoryId,
                        'sub_category_id' => $categoryId,
                        'class_id'        => $pair['class_id'],
                        'medium_id'       => $pair['medium_id'], // Can be null
                        'created_by'      => Auth::id(),
                    ]);
                }
            }

            return redirect()->back()->with(['success' => config('constants.FLASH_REC_ADD_1')]);
        } catch (\Exception $e) {
            return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function d2cCourses(Request $request)
    {
        $categoryId = $request->category_id ?? null;
        $parentCategoryId = $request->parent_category_id ?? null;

        foreach ($request->class_id as $classId => $actualClassId) {
            $mediumId = $request->medium_id[$classId] ?? null;
            $sn = $request->sn[$classId] ?? null;
            $courseIdsArray = $request->course_ids[$classId] ?? [];
            $commaSeparatedCourses = implode(',', $courseIdsArray);
            $qrName = $request->qr_name[$classId] ?? null;
            $qrCodeLink = $request->qr_code_link[$classId] ?? null;

            // Delete existing record
            D2cDigitalContent::where('category_id', $parentCategoryId)
                ->where('sub_category_id', $categoryId)
                ->where('class_id', $actualClassId)
                ->where('medium_id', $mediumId)
                ->where('sn', $sn)
                ->delete();

            // Create new record
            D2cDigitalContent::create([
                'category_id'     => $parentCategoryId,
                'sub_category_id' => $categoryId,
                'class_id'        => $actualClassId,
                'medium_id'       => $mediumId,
                'sn'              => $sn,
                'course_id'       => $commaSeparatedCourses,
                'qr_name'         => $qrName,
                'qr_code_link'    => $qrCodeLink,
                'created_by'      => Auth::id(),
            ]);
        }

        return redirect()->back()->with(['success' => config('constants.FLASH_REC_ADD_1')]);
    }

    public function download($filename)
    {
        $filePath = 'qrcodes/' . $filename;

        if (!Storage::disk('public')->exists($filePath)) {
            abort(404);
        }

        $file = Storage::disk('public')->get($filePath);
        $mime = Storage::disk('public')->mimeType($filePath);

        return response($file, 200)
            ->header('Content-Type', $mime)
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
