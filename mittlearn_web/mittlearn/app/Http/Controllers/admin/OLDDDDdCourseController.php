<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\BookSeries;
use App\Models\Category;
use App\Models\City;
use App\Models\Classes;
use App\Models\Course;
use App\Models\CourseChapter;
use App\Models\CourseLevel;
use App\Models\CourseMetadataValue;
use App\Models\Language;
use App\Models\LessonNumber;
use App\Models\MediaFiles;
use App\Models\MediaFolder;
use App\Models\Medium;
use App\Models\SchoolClass;
use App\Models\SchoolComplimentaryCourse;
use App\Models\Schools;
use App\Models\State;
use App\Models\Subject;
use App\Models\UserAdditionalDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CourseController extends Controller
{
    public $data = [];
    public function index(Request $request)
    {
        $activeTab = $request->input('active_tab', 'academic_digital_content');
        $this->data['activeTab'] = $activeTab;

        // Get all parent categories for dynamic tabs
        $this->data['categories'] = Category::where('status', 1)
            ->where('parent_id', 1)
            ->get(['id', 'name', 'slug']);

        $this->data['bookSeries'] = BookSeries::pluck('name', 'id')->toArray();
        $this->data['classes'] = SchoolClass::pluck('name', 'id')->toArray();
        $this->data['subjects'] = Subject::pluck('name', 'id')->toArray();

        // Fixed queries for the three main tabs
        $acadQuery = Course::with('metadataValues', 'subCategory', 'category')
            ->where('category_id', 1)
            ->where('sub_category_id', 6);

        $unAcadQuery = Course::with('metadataValues')->where('category_id', 2);
        $acadActivitesQuery = Course::with('metadataValues')
            ->where('category_id', 1)
            ->where('sub_category_id', 7);

        // Dynamic queries for category tabs
        $dynamicResults = [];
        foreach ($this->data['categories'] as $category) {
            $slug = $category->slug;
            if (!in_array($slug, ['academic-digital-content', 'talent-skills', 'academic_activities'])) {
                $query = Course::with('metadataValues', 'subCategory', 'category')
                    ->where('category_id', $category->id);

                // Apply all your existing filters to dynamic queries
                if ($request->filled('course_name')) {
                    $query->where('course_name', 'like', '%' . $request->course_name . '%');
                }
                if ($request->filled('series_id')) {
                    $query->whereHas('metadataValues', function ($q) use ($request) {
                        $q->where('field_name', 'series')
                            ->where('field_value', $request->series_id);
                    });
                }
                if ($request->filled('class_id')) {
                    $query->whereHas('metadataValues', function ($q) use ($request) {
                        $q->where('field_name', 'class')
                            ->where('field_value', $request->class_id);
                    });
                }
                if ($request->filled('subject_id')) {
                    $query->whereHas('metadataValues', function ($q) use ($request) {
                        $q->where('field_name', 'subject')
                            ->where('field_value', $request->subject_id);
                    });
                }

                $dynamicResults[$slug] = $query->orderBy('id', 'DESC')
                    ->paginate(config('constants.PAGINATION.default'));
            }
        }

        // Fixed tab results
        $this->data['acadCourses'] = $acadQuery->orderBy('id', 'DESC')
            ->paginate(config('constants.PAGINATION.default'));

        $this->data['unAcadCourses'] = $unAcadQuery->orderBy('id', 'DESC')
            ->paginate(config('constants.PAGINATION.default'));

        $this->data['acadActivitesQuery'] = $acadActivitesQuery->orderBy('id', 'DESC')
            ->paginate(config('constants.PAGINATION.default'));

        // Merge dynamic results with data
        $this->data = array_merge($this->data, $dynamicResults);

        return view('admin.courses.index', $this->data);
    }

    public function create()
    {
        // $this->data['category'] = Category::where('parent_id', null)
        //     ->where('is_default', 1)
        //     ->with('children')
        //     ->pluck('name', 'id');
        $this->data['category'] = Category::getAllCategories();
        //   return $this->data['category'];
        $this->setCourseFormVars();
        return view('admin.courses.add_edit', $this->data);
    }
    public function saveCourse(Request $request)
    {
        // dd($request->all());
        if ($request->id > 0) {
            $success = config('constants.FLASH_REC_UPDATE_1');
            $error   = config('constants.FLASH_REC_UPDATE_0');
        } else {
            $success = config('constants.FLASH_REC_ADD_1');
            $error   = config('constants.FLASH_REC_ADD_0');
        }
        $slug = generateUniqueSlug($request->course_name, Course::class, 'slug', $request->id);

        $dataArr = [
            "category_id"    => $request->group,
            "course_name"    => $request->course_name,
            "slug"           => $slug,
            "price"          => $request->price,
            "discount_type"  => $request->discount_type,
            "discount_value" => $request->discount_value,
            "is_active"      => true,
        ];
        // Conditionally add 'category_id' and 'sub_category_id' to $dataArr
        if (! ($request->group == 2 && $request->subgroup == null)) {
            $dataArr["sub_category_id"] = $request->subgroup;
        }
        // Create or Update the course
        $course = Course::updateOrCreate(['id' => $request->id], $dataArr);

        if ($course) {
            // Handle course metadata values
            CourseMetadataValue::whereCourseId($course->id)->delete();
            if ($request->has('course_attribute')) {
                $metadataDataArr = [];
                foreach ($request->course_attribute as $fieldName => $values) {
                    foreach ($values as $fieldId => $fieldValue) {

                        // Check if the field value is a file
                        if ($request->hasFile("course_attribute.$fieldName.$fieldId")) {
                            $file       = $request->file("course_attribute.$fieldName.$fieldId");
                            $filename   = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                            $path       = Storage::disk('public')->put("uploads/course_files/{$filename}", file_get_contents($file));
                            $fieldValue = "uploads/course_files/{$filename}";
                        }
                        // Prepare the metadata data array for insertion
                        $metadataDataArr[] = [
                            "course_id"   => $course->id,
                            "field_id"    => $fieldId,
                            "field_name"  => $fieldName,
                            "field_value" => is_array($fieldValue) ? json_encode($fieldValue) : $fieldValue,
                        ];
                    }
                }
                // Insert the metadata data into the database
                CourseMetadataValue::insert($metadataDataArr);
            }

            return redirect()->route('course.index')->with(['success' => $success]);
        }

        return redirect()->back()->with(['error' => $error]);
    }
    public function courseActivate($id)
    {
        $course                                       = Course::find($id);
        $course->is_active === 1 ? $course->is_active = 0 : $course->is_active = 1;
        $course->save();

        return redirect()->back()->with(['success' => config('constants.FLASH_STATUS')]);
    }

    public function edit($id)
    {
        $this->data['course'] = Course::with('metadataValues')->findOrFail($id);
        // Prepare course metadata fields with values
        $metadataFieldValues = [];
        foreach ($this->data['course']->metadataValues as $metadataValue) {
            $metadataFieldValues[$metadataValue->field_name] = $metadataValue->field_value;
        }
        $this->data['metadataFieldValues'] = $metadataFieldValues;
        $this->data['category'] = Category::where('status', 1)->whereNotNull('parent_id')
            ->with('children')
            ->pluck('name', 'id');

        $this->setCourseFormVars();
        return view('admin.courses.add_edit', $this->data);
    }

    public function delete($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        return redirect()->route('course.index')->with('success', 'Course deleted successfully!');
    }
    public function createChapter($id)
    {
        $this->data['courseName'] = Course::where('id', $id)->value('course_name');
        $this->data['courseCategory'] = Course::where('id', $id)->value('category_id');
        $this->data['chapters'] = CourseChapter::where('course_id', $id)->orderBy('sort_order', 'ASC') // Order by sort_order in ascending order
            ->paginate(config('constants.PAGINATION.default'));
        $this->data['folder_list'] = MediaFolder::where('parent_id', Auth::id())->pluck('folder_name', 'id');

        return view('admin.courses.add_edit_chapter', $this->data);
    }
    public function saveChapter(Request $request)
    {
        // Validate the request
        $request->validate([
            'course_id'            => 'required',
            'chapter_title'       => 'required|max:255',
            'chapter_description' => 'required',
            'sort_order'          => 'required|integer',
            'supporting_folder_id' => 'nullable',
            'chapter_file.*'      => 'nullable|mimes:pdf,docx,xlsx,jpeg,jpg,png,mp4,avi,mov',
        ], [
            'chapter_file.*.mimes' => 'Only pdf, docx, xlsx, jpeg, jpg, png, mp4, avi, and mov file types are allowed.',
            'chapter_file.*.max'   => 'Each file may not be greater than 10MB.',
        ]);


        DB::beginTransaction();

        try {

            // Determine a unique sort_order
            $sortOrder = $request->sort_order ?? 1; // Default to 1 if not provided
            while (CourseChapter::where('course_id', $request->course_id)->where('sort_order', $sortOrder)->exists()) {
                $sortOrder++;
            }

            // Update or Create the chapter
            $chapter = CourseChapter::updateOrCreate(
                [
                    'id' => $request->id,
                ],
                [
                    'course_id'            => $request->course_id,
                    'chapter_name'         => $request->chapter_title,
                    'chapter_description'  => $request->chapter_description,
                    'topic_covered'  => $request->topic_covered,
                    'sort_order'           => $sortOrder,
                    'content_creation_date'  => $request->content_creation_date,
                    'supporting_folder_id' => $request->supporting_folder_id,
                    'created_by'           => auth()->id(),
                    'created_date'         => now(),
                    'is_approved'          => 0,
                ]
            );

            if ($request->hasFile('chapter_file')) {
                foreach ($request->file('chapter_file') as $index => $file) {
                    $customFileName = $request->file_name[$index] ?? null;
                    $slug = Str::slug($request->chapter_title, '-');
                    $slug = Str::limit($slug, 10, '');
                    $videoSortOrder = $request->video_sort_order[$index];
                    $uniqueNumber = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);  // Pads number to 4 digits


                    $fileExtension = $file->getClientOriginalExtension();
                    $fileSize      = $file->getSize();
                    $mimeType      = $file->getMimeType();
                    $fileName = time() . '-' . $index . '-' . $uniqueNumber . '.' . $fileExtension;
                    $originalName  = 'DC-' . $slug . '-' . $uniqueNumber . '.' .  $fileExtension;

                    Storage::disk('public')->put('uploads/course_chapter_files/' . $fileName, file_get_contents($file));

                    $videoDuration = $request->video_duration[$index] ?? null;

                    MediaFiles::create([
                        'tbl_id'          => $chapter->id,
                        'type'            => 'course_chapter',
                        'attachment_file' => $fileName,
                        'original_name'   => $originalName,
                        'file_extension'  => $fileExtension,
                        'sort_order'     => $videoSortOrder,
                        'file_name'  => $customFileName,
                        'file_size'       => $fileSize,
                        'mime_type'       => $mimeType,
                        'uploaded_by'     => auth()->id(),
                        'video_duration'  => $videoDuration,
                    ]);
                }
            }

            function uploadFile($file, $chapterId, $type, $uploadedFileName)
            {
                $originalName  = $file->getClientOriginalName();
                $fileExtension = $file->getClientOriginalExtension();
                $fileSize      = $file->getSize();
                $mimeType      = $file->getMimeType();
                $fileName      = $uploadedFileName . '-' . time() . '.' . $fileExtension;

                Storage::disk('public')->put('uploads/course_chapter_files/' . $fileName, file_get_contents($file));

                MediaFiles::create([
                    'tbl_id'          => $chapterId,
                    'type'            => $type,
                    'attachment_file' => $fileName,
                    'original_name'   => $fileName,
                    'file_extension'  => $fileExtension,
                    'file_size'       => $fileSize,
                    'mime_type'       => $mimeType,
                    'uploaded_by'     => auth()->id(),
                ]);
            }

            if ($request->hasFile('teaching_manuals')) {
                uploadFile($request->file('teaching_manuals'), $chapter->id, 'course_chapter_extra', 'TeachingManual');
            }

            if ($request->hasFile('question_bank')) {
                uploadFile($request->file('question_bank'), $chapter->id, 'course_chapter_extra', 'QuestionBank');
            }

            if ($request->hasFile('lesson_planner')) {
                uploadFile($request->file('lesson_planner'), $chapter->id, 'course_chapter_extra', 'LessonPlanner');
            }

            //for talent courses
            if ($request->hasFile('worksheet')) {
                uploadFile($request->file('worksheet'), $chapter->id, 'talent_course_chapter_extra', 'Worksheet/Practice Sheet');
            }

            if ($request->hasFile('answer_sheet')) {
                uploadFile($request->file('answer_sheet'), $chapter->id, 'talent_course_chapter_extra', 'Answer Sheet');
            }

            if ($request->hasFile('other_pdf')) {
                uploadFile($request->file('other_pdf'), $chapter->id, 'talent_course_chapter_extra', 'Other PDF');
            }


            DB::commit();

            // Redirect back with success message
            return redirect()->back()->with('success', $request->id ? config('constants.FLASH_REC_UPDATE_1') : config('constants.FLASH_REC_ADD_1'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to save or update chapter: ' . $e->getMessage());
        }
    }

    public function saveChapterOld(Request $request)
    {
        // Validate the request
        $request->validate([
            'course_id'            => 'required',
            'chapter_title'        => 'required|max:255',
            'chapter_description'  => 'required',
            'sort_order'           => 'required|integer',
            'supporting_folder_id' => 'nullable',
        ]);

        DB::beginTransaction();

        try {
            // Determine a unique sort_order
            $sortOrder = $request->sort_order ?? 1; // Default to 1 if not provided
            while (CourseChapter::where('course_id', $request->course_id)->where('sort_order', $sortOrder)->exists()) {
                $sortOrder++;
            }

            // Update or Create the chapter
            $chapter = CourseChapter::updateOrCreate(
                [
                    'id' => $request->id,
                ],
                [
                    'course_id'            => $request->course_id,
                    'chapter_name'         => $request->chapter_title,
                    'chapter_description'  => $request->chapter_description,
                    'sort_order'           => $sortOrder,
                    'supporting_folder_id' => $request->supporting_folder_id,
                    'created_by'           => auth()->id(),
                    'created_date'         => now(),
                    'is_approved'          => 0,
                ]
            );

            // If there are files, save them
            if ($request->hasFile('chapter_file')) {
                foreach ($request->file('chapter_file') as $index => $file) {
                    $uniqueNumber = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);  // Pads number to 4 digits

                    $originalName  = $file->getClientOriginalName();
                    $fileExtension = $file->getClientOriginalExtension();
                    $fileSize      = $file->getSize();
                    $mimeType      = $file->getMimeType();
                    $fileName = time() . '-' . $index . '-' . $uniqueNumber . '.' . $fileExtension;

                    Storage::disk('public')->put('uploads/course_chapter_files/' . $fileName, file_get_contents($file));

                    // Get the corresponding video duration if available
                    $videoDuration = $request->video_duration[$index] ?? null;

                    MediaFiles::create([
                        'tbl_id'          => $chapter->id,
                        'type'            => 'course_chapter',
                        'attachment_file' => $fileName,
                        'original_name'   => $originalName,
                        'file_extension'  => $fileExtension,
                        'file_size'       => $fileSize,
                        'mime_type'       => $mimeType,
                        'uploaded_by'     => auth()->id(),
                        'video_duration'  => $videoDuration,

                    ]);
                }
            }

            DB::commit();

            // Redirect back with success message
            return redirect()->back()->with('success', $request->id ? config('constants.FLASH_REC_UPDATE_1') : config('constants.FLASH_REC_ADD_1'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to save or update chapter: ' . $e->getMessage());
        }
    }

    public function editChapter($id)
    {
        $this->data['chapter']         = CourseChapter::where('id', $id)->first();
        $this->data['chapter_content'] = MediaFiles::where('tbl_id', $id)->where('type', 'course_chapter')->orderBy('sort_order', 'asc')->get();
        $this->data['chapter_content_extra'] = MediaFiles::where('tbl_id', $id)->where('type', 'course_chapter_extra')->get();
        $this->data['folder_list']     = MediaFolder::pluck('folder_name', 'id');

        return view('admin.courses.edit_chapter', $this->data);
    }
    public function updateChapter(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'chapter_title'        => 'required|max:255',
            'chapter_description'  => 'nullable',
            'sort_order'           => 'required|integer',
            'supporting_folder_id' => 'nullable|exists:media_folders,id',
            'chapter_file.*'       => 'nullable|mimes:pdf,docx,xlsx,jpeg,jpg,png,mp4,avi,mov', // Validate file extensions
        ], [
            'chapter_file.*.mimes' => 'Only pdf, docx, xlsx, jpeg, jpg, png, mp4, avi, and mov file types are allowed.',
            'chapter_file.*.max'   => 'Each file may not be greater than 10MB.',
        ]);

        DB::beginTransaction();

        try {
            // Find the chapter
            $chapter = CourseChapter::findOrFail($id);

            // Determine a unique sort_order
            $sortOrder = $request->sort_order ?? $chapter->sort_order; // Use existing sort_order if not provided
            if ($request->sort_order !== null && $request->sort_order != $chapter->sort_order) {
                while (CourseChapter::where('course_id', $chapter->course_id)
                    ->where('sort_order', $sortOrder)
                    ->where('id', '!=', $chapter->id)
                    ->exists()
                ) {
                    $sortOrder++;
                }
            }

            // Update the chapter
            $chapter->update([
                'chapter_name'         => $request->chapter_title,
                'chapter_description'  => $request->chapter_description,
                'topic_covered'  => $request->topic_covered,
                'content_creation_date'  => $request->content_creation_date,
                'sort_order'           => $sortOrder,
                'supporting_folder_id' => $request->supporting_folder_id,
            ]);

            // Add New Files
            if ($request->hasFile('chapter_file')) {
                foreach ($request->file('chapter_file')  as $index => $file) {
                    if ($file) {
                        $customFileName = $request->file_name[$index] ?? null;
                        $slug = Str::slug($request->chapter_title, '-');
                        $slug = Str::limit($slug, 10, '');
                        $videoSortOrder = $request->video_sort_order[$index];
                        $uniqueNumber = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
                        $fileExtension = $file->getClientOriginalExtension();
                        $fileSize      = $file->getSize();
                        $mimeType      = $file->getMimeType();
                        $fileName = time() . '-' . $index . '-' . $uniqueNumber . '.' . $fileExtension;
                        $originalName  = 'DC-' . $slug . '-' . $uniqueNumber . '.' .  $fileExtension;

                        Storage::disk('public')->put('uploads/course_chapter_files/' . $fileName, file_get_contents($file));
                        // Get the corresponding video duration if available
                        $videoDuration = $request->video_duration[$index] ?? null;
                        MediaFiles::create([
                            'tbl_id'          => $chapter->id,
                            'type'            => 'course_chapter',
                            'attachment_file' => $fileName,
                            'original_name'   => $originalName,
                            'file_extension'  => $fileExtension,
                            'sort_order'      => $videoSortOrder,
                            'file_name'       => $customFileName,
                            'file_size'       => $fileSize,
                            'mime_type'       => $mimeType,
                            'uploaded_by'     => auth()->id(),
                            'video_duration'  => $videoDuration,
                        ]);
                    }
                }
            }

            function uploadedFile($file, $chapterId, $type, $uploadedFileName)
            {
                $originalName  = $file->getClientOriginalName();
                $fileExtension = $file->getClientOriginalExtension();
                $fileSize      = $file->getSize();
                $mimeType      = $file->getMimeType();
                $fileName      = $uploadedFileName . '-' . time() . '.' . $fileExtension;

                Storage::disk('public')->put('uploads/course_chapter_files/' . $fileName, file_get_contents($file));

                MediaFiles::create([
                    'tbl_id'          => $chapterId,
                    'type'            => $type,
                    'attachment_file' => $fileName,
                    'original_name'   => $originalName,
                    'file_extension'  => $fileExtension,
                    'file_size'       => $fileSize,
                    'mime_type'       => $mimeType,
                    'uploaded_by'     => auth()->id(),
                ]);
            }
            if ($request->hasFile('teaching_manuals')) {
                uploadedFile($request->file('teaching_manuals'), $chapter->id, 'course_chapter_extra', 'TeachingManual');
            }
            if ($request->hasFile('question_bank')) {
                uploadedFile($request->file('question_bank'), $chapter->id, 'course_chapter_extra', 'QuestionBank');
            }
            if ($request->hasFile('lesson_planner')) {
                uploadedFile($request->file('lesson_planner'), $chapter->id, 'course_chapter_extra', 'LessonPlanner');
            }

            DB::commit();

            return redirect()->back()->with('success', config('constants.FLASH_REC_UPDATE_1'));
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update chapter: ' . $e->getMessage());
        }
    }

    public function deleteChapter($id)
    {
        $courseChapter = CourseChapter::where('id', $id)->first();
        $file          = MediaFiles::where('tbl_id', $id)->where('type', 'course_chapter')->get();

        if ($file) {
            foreach ($file as $key => $value) {
                if (Storage::disk('public')->exists('uploads/course_chapter_files-files/' . $value->attachment_file)) {
                    Storage::disk('public')->delete('uploads/media-ficourse_chapter_filesles/' . $value->attachment_file);
                }
                $value->delete();
            }
        }
        // $file->delete();
        $courseChapter->delete();
        return redirect()->back()->with(['success' => config('constants.FLASH_REC_DELETE_1')]);
    }

    public function deleteChapterFiles($id)
    {
        $chapterFile = MediaFiles::where('id', $id)->first();

        if ($chapterFile) {
            $filePath = 'uploads/course_chapter_files/' . $chapterFile->attachment_file;
            if (Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }

            // Delete the record from the MediaFiles table
            $chapterFile->delete();

            // Redirect with success message
            return redirect()->back()->with('success', config('constants.FLASH_REC_DELETE_1'));
        } else {
            return redirect()->back()->with('error', 'File not found.');
        }
    }

    public function setCourseFormVars()
    {
        $modelsData                                        = [];
        $modelsData['boards']                              = Board::where('is_active', 1)->pluck('name', 'id');
        $modelsData['mediums']                             = Medium::where('is_active', 1)->pluck('name', 'id');
        $modelsData['book_series']                         = BookSeries::where('is_active', 1)->pluck('name', 'id');
        $modelsData['classes']                             = Classes::where('is_active', 1)->pluck('name', 'id');
        $modelsData['subjects']                            = Subject::where('is_active', 1)->pluck('name', 'id');
        $modelsData['content_language']                    = Language::where('is_active', 1)->pluck('name', 'id');
        $modelsData['lesson_numbers']                      = LessonNumber::where('is_active', 1)->pluck('number', 'id');
        $modelsData['levels']                              = CourseLevel::where('is_active', 1)->pluck('name', 'id');
        $modelsData['channel_to_push']                     = config('constants.CHANNEL_TO_PUSH');
        $modelsData['status']                              = config('constants.STATUS_LIST');
        $modelsData['certification_status']                = config('constants.CERTIFICATION_STATUS');
        $modelsData['available_for_complimentary_package'] = config('constants.COM_PACKAGE');

        $this->data['modelsData'] = $modelsData;
    }

    public function complimentaryIndex()
    {
        $this->data['courses'] = Course::with('metadataValues')->whereHas('metadataValues', function ($query) {
            $query->where('field_name', 'available_for_complimentary_package')
                ->whereIn('field_value', ['all', '1']);
        })->orderBy('id', 'DESC')->paginate(config('constants.PAGINATION.default'));

        $this->data['schools'] = Schools::pluck('name', 'id');
        $this->data['state'] = State::pluck('name', 'id')->toArray();
        return view('admin.courses.complimentary-index', $this->data);
    }
    public function academicActivitiesIndex()
    {

        $this->data['courses'] = Course::with('metadataValues')->where('category_id', 1)->where('sub_category_id', 7)->orderBy('id', 'DESC')
            ->paginate(config('constants.PAGINATION.default'));

        $this->data['schools'] = Schools::pluck('name', 'id');
        $this->data['state'] = State::pluck('name', 'id')->toArray();
        return view('admin.courses.acad-activities-index', $this->data);
    }
    public function saveComplimentaryCourse(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'school_id' => 'required',
        ]);
        foreach ($request->school_id as $schoolId) {
            if ($request->course_ids) {
                $courseIds = json_decode($request->course_ids, true);
                foreach ($courseIds as $courseId) {
                    $categoryId = Course::where('id', $courseId)->value('category_id');
                    $existingSchool = SchoolComplimentaryCourse::where('school_id', $schoolId)
                        ->where('course_id', $courseId)
                        ->exists();

                    if ($existingSchool) {
                        continue;
                    }

                    SchoolComplimentaryCourse::create([
                        'category_id' => $categoryId,
                        'school_id' => $schoolId,
                        'course_id' => $courseId,
                    ]);
                }
            } else {
                $existingSchool = SchoolComplimentaryCourse::where('school_id', $schoolId)
                    ->where('course_id', $request->course_id)
                    ->exists();
                $categoryId = Course::where('id', $request->course_id)->value('category_id');

                if ($existingSchool) {
                    continue;
                }

                SchoolComplimentaryCourse::create([
                    'category_id' => $categoryId,
                    'school_id' => $schoolId,
                    'course_id' => $request->course_id,
                ]);
            }
        }
        return redirect()->back()->with('success', config('constants.FLASH_REC_ADD_1'));
    }
    public function bulkUpload(Request $request)
    {
        return view('admin.courses.bulk-add', $this->data);
    }
    public function chapterBulkUpload($id)
    {
        $this->data['courseId'] = $id;
        return view('admin.courses.chapter-bulk-add', $this->data);
    }
    public function getCities($state)
    {
        $cities = City::where('state_id', $state)->pluck('city', 'id');
        return response()->json($cities);
    }
    public function getSchools($cityId)
    {
        $schools = Schools::where('city', $cityId)->join('users', 'schools.user_id', '=', 'users.id')
            ->select('schools.unique_id', 'users.name', 'users.id', 'schools.postal_code')
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->id => "{$item->unique_id} : {$item->name} ({$item->postal_code})"];
            });
        return response()->json($schools);
    }
}
