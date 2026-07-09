<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\BookSeries;
use App\Models\CourseMetadataValue;
use App\Models\Medium;
use App\Models\Planner;
use App\Models\SchoolAssignedDigitalContent;
use App\Models\SchoolClass;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookSeriesController extends Controller
{
    public $data = [];
    public function bookSeriesShow()
    {
        // $data = BookSeries::paginate(10);
        $data = BookSeries::with(['board', 'medium'])->orderBy('id', 'DESC')->paginate(config('constants.PAGINATION.default'));
        return view('admin.bookSeries.index', ['data' => $data]);
    }

    public function editBookSeries($id)
    {
        $data = BookSeries::with(['board', 'medium'])->where('id', $id)->first();

        $boards   = Board::where('is_active', '1')->pluck('name', 'id')->toArray();
        $mediums  = Medium::where('is_active', '1')->pluck('name', 'id')->toArray();
        $classes  = SchoolClass::where('is_active', '1')->pluck('name', 'id')->toArray();
        $subjects = Subject::where('is_active', '1')->pluck('name', 'id')->toArray();

        // Decode class-subject relationships
        $classSubjects = !empty($data->class_subjects) ? json_decode($data->class_subjects, true) : [];

        return view('admin.bookSeries.add', [
            'data'          => $data,
            'boards'        => $boards,
            'classes'       => $classes,
            'subjects'      => $subjects,
            'mediums'       => $mediums,
            'classSubjects' => $classSubjects, // Pass the structured data
        ]);
    }

    public function createBookSeries()
    {
        $this->data['boards']   = Board::where('is_active', '1')->pluck('name', 'id')->toArray();
        $this->data['mediums']  = Medium::where('is_active', '1')->pluck('name', 'id')->toArray();
        $this->data['classes']  = SchoolClass::where('is_active', '1')->pluck('name', 'id')->toArray();
        $this->data['subjects'] = Subject::where('is_active', '1')->pluck('name', 'id')->toArray();
        return view('admin.bookSeries.add', $this->data);
    }
    public function bookSeriesSave(Request $request)
    {
        $request->validate([
            'name'      => 'required',
            'image'     => 'nullable|file|mimes:gif,json', // Allow only GIF and JSON
            'is_active' => 'required|boolean',
        ], ['is_active.required' => 'Status field is required']);

        $success = $request->id > 0 ? config('constants.FLASH_REC_UPDATE_1') : config('constants.FLASH_REC_ADD_1');
        $error   = $request->id > 0 ? config('constants.FLASH_REC_UPDATE_0') : config('constants.FLASH_REC_ADD_0');

        $slug = generateUniqueSlug($request->name, BookSeries::class, 'slug', $request->id);

        // Retrieve existing book series
        $existingBookSeries = BookSeries::find($request->id);
        $dataImage               = $existingBookSeries->image ?? null; // Keep old image if not updated

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($existingBookSeries && Storage::disk('public')->exists('uploads/book-series/' . $existingBookSeries->image)) {
                Storage::disk('public')->delete('uploads/book-series/' . $existingBookSeries->image);
            }
            // Upload new image
            $bookSeriesImage = $request->file('image');
            $filename        = time() . '.' . $bookSeriesImage->getClientOriginalExtension();
            Storage::disk('public')->put('uploads/book-series/' . $filename, file_get_contents($bookSeriesImage));
            $dataImage = $filename; // Update with new filename
        }

        // Process class and subject selection
        // $classSubjectData = [];
        // if (! empty($request->class) && ! empty($request->subject)) {
        //     foreach ($request->class as $index => $classId) {
        //         if (! empty($classId) && isset($request->subject[$index])) {
        //             $classSubjectData[] = [
        //                 'class_id'    => $classId,
        //                 'subject_ids' => $request->subject[$index], 
        //             ];
        //         }
        //     }
        // }

        $classSubjectData = [];

        if (!empty($request->class) && !empty($request->subject)) {
            foreach ($request->class as $index => $classId) {
                if (!empty($classId) && isset($request->subject[$index])) {
                    $currentSubjects = (array) $request->subject[$index]; // Force subject to be an array

                    $existingClassIndex = null;
                    foreach ($classSubjectData as $key => $data) {
                        if ($data['class_id'] == $classId) {
                            $existingClassIndex = $key;
                            break;
                        }
                    }

                    if ($existingClassIndex !== null) {
                        $existingSubjects = $classSubjectData[$existingClassIndex]['subject_ids'];
                        $existingSubjects = (array) $existingSubjects;
                        $mergedSubjects = array_merge($existingSubjects, $currentSubjects);
                        $classSubjectData[$existingClassIndex]['subject_ids'] = array_values(array_unique($mergedSubjects)); // array_unique to remove duplicates
                    } else {
                        $classSubjectData[] = [
                            'class_id'    => $classId,
                            'subject_ids' => array_values(array_unique($currentSubjects)), 
                        ];
                    }
                }
            }
        }

        $checkDataString = json_encode($classSubjectData);
        if (!is_string($checkDataString)) {
            return redirect()->back()->with(['error' => $error]);
        }


        $res = BookSeries::updateOrCreate(
            ['id' => $request->id],
            [
                'name'           => $request->name,
                'is_active'      => $request->is_active,
                'board_id'       => $request->board_id,
                'medium_id'      => $request->medium_id,
                'class_subjects' => json_encode($classSubjectData), // Save JSON structure
                'short_code'     => $request->short_code,
                'slug'           => $slug,
                'image'          => $dataImage, // Keep old image if no new file uploaded
            ]
        );

        return $res
            ? redirect()->route('book.series.index')->with(['success' => $success])
            : redirect()->back()->with(['error' => $error]);
    }

    // public function bookSeriesSave(Request $request)
    // {
    //     $request->validate([
    //         'name'      => 'required',
    //         'image'     => 'nullable|file|mimes:gif,json', // Allow only GIF and JSON
    //         'is_active' => 'required|boolean',
    //     ], ['is_active.required' => 'Status field is required']);

    //     $success = $request->id > 0 ? config('constants.FLASH_REC_UPDATE_1') : config('constants.FLASH_REC_ADD_1');
    //     $error   = $request->id > 0 ? config('constants.FLASH_REC_UPDATE_0') : config('constants.FLASH_REC_ADD_0');

    //     $slug = generateUniqueSlug($request->name, BookSeries::class, 'slug', $request->id);

    //     // Retrieve existing book series
    //     $existingBookSeries = BookSeries::find($request->id);
    //     $data               = $existingBookSeries->image ?? null; // Keep old image if not updated

    //     if ($request->hasFile('image')) {
    //         // Delete old image if exists
    //         if ($existingBookSeries && Storage::disk('public')->exists('uploads/book-series/' . $existingBookSeries->image)) {
    //             Storage::disk('public')->delete('uploads/book-series/' . $existingBookSeries->image);
    //         }
    //         // Upload new image
    //         $bookSeriesImage = $request->file('image');
    //         $filename        = time() . '.' . $bookSeriesImage->getClientOriginalExtension();
    //         Storage::disk('public')->put('uploads/book-series/' . $filename, file_get_contents($bookSeriesImage));
    //         $data = $filename; // Update with new filename
    //     }

    //     $class_ids   = implode(',', $request->class ?? []);
    //     $subject_ids = implode(',', $request->subject ?? []);

    //     $res = BookSeries::updateOrCreate(
    //         ['id' => $request->id],
    //         [
    //             'name'        => $request->name,
    //             'is_active'   => $request->is_active,
    //             'board_id'    => $request->board_id,
    //             'medium_id'   => $request->medium_id,
    //             'class_ids'   => $class_ids,
    //             'subject_ids' => $subject_ids,
    //             'short_code'  => $request->short_code,
    //             'slug'        => $slug,
    //             'image'       => $data, // Keep old image if no new file uploaded
    //         ]
    //     );

    //     return $res
    //     ? redirect()->route('book.series.index')->with(['success' => $success])
    //     : redirect()->back()->with(['error' => $error]);
    // }

    public function bookSeriesDelete($id)
    {
        $seriesId = $id;
        $data = BookSeries::where('id', $seriesId)->first();
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Book Series not found.'
            ], 404);
        }
        $plannerCount = Planner::where('series_id', $seriesId)->count();
        $digitalContentAssgin = SchoolAssignedDigitalContent::where('series_id', $seriesId)->count();
        $metaDataCount = CourseMetadataValue::where('field_name', 'series')
            ->where('field_value', $seriesId)
            ->count();
        if ($plannerCount > 0 || $metaDataCount > 0 || $digitalContentAssgin > 0) {
            return response()->json([
                'success' => false,
                'message' => "Cannot delete this Book Series. It has ($plannerCount) Associated Planners , ($metaDataCount) Associated Courses and ($digitalContentAssgin) Associated Digital Content."
            ]);
        }
        $data->delete();
        return response()->json([
            'success' => true,
            'message' => "{$data->name} has been deleted successfully."
        ]);
    }
}
