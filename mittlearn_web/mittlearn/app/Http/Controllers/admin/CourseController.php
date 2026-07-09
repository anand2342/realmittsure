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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;
use Illuminate\Support\Facades\File;
use App\Exports\CourseContentExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;
use Maatwebsite\Excel\Excel as ExcelFormat;


class CourseController extends Controller
{
    public $data = [];

    public function index(Request $request, $group = null)
    {
        if ($group == 'others') {
            // 43 robotics catesugory sub on UAT 
            $this->data['category'] = Category::where('status', 1)->where('id', '>=', 45)->firstOrFail();
        } else {
            $this->data['category'] = Category::where('status', 1)->where('slug', $group)->firstOrFail();
        }
        // Load supporting filter data
        $this->data['bookSeries'] = BookSeries::whereNotIn('id', [21, 22, 23, 24])->where('is_active', 1)->pluck('name', 'id')->toArray();
        $this->data['classes'] = SchoolClass::where('is_active', 1)->pluck('name', 'id')->toArray();
        $this->data['subjects'] = Subject::where('is_active', 1)->pluck('name', 'id')->toArray();
        $this->data['subcategories'] = Category::where('status', 1)->where('parent_id', 2)->pluck('name', 'id')->toArray();

        if ($request->series_id) {

            $seriesId = $request->series_id;
            $seriesName = $this->data['bookSeries'][$seriesId] ?? '';
            if ($seriesId >= 29) {
                // 24 robotics series id on UAT 
                $group = 'others';
            } elseif (Str::contains(Str::lower($seriesName), 'olympiad')) {
                $group = 'olympiad';
            } elseif (Str::contains(Str::lower($seriesName), 'worksheet')) {
                $group = 'activity-worksheets';
            } elseif (Str::contains(Str::lower($seriesName), 'pitara')) {
                $group = 'jaadui-pitara-kit';
            } else {
                $group = 'academic-digital-content';
            }
        }
        // Base queries
        $acadQuery = Course::with('metadataValues', 'subCategory', 'category')
            ->where('category_id', 1)
            ->where('sub_category_id', $this->data['category']->id);

        $unAcadQuery = Course::with('metadataValues')
            ->where('category_id', 2);

        // Search filters
        if ($request->filled('course_name')) {
            $acadQuery->where('course_name', 'like', '%' . $request->course_name . '%');
            $unAcadQuery->where('course_name', 'like', '%' . $request->course_name . '%');
        }

        if ($request->filled('series_id')) {
            $acadQuery->whereHas('metadataValues', function ($query) use ($request) {
                $query->where('field_name', 'series')
                    ->where('field_value', $request->series_id);
            });
        }

        if ($request->filled('class_id')) {
            $acadQuery->whereHas('metadataValues', function ($query) use ($request) {
                $query->where('field_name', 'class')
                    ->where('field_value', $request->class_id);
            });
            $unAcadQuery->whereHas('metadataValues', function ($query) use ($request) {
                $query->where('field_name', 'class')
                    ->where('field_value', $request->class_id);
            });
        }

        if ($request->filled('sub_category_id')) {
            $unAcadQuery->where('sub_category_id', $request->sub_category_id);
        }

        if ($request->filled('subject_id')) {
            $acadQuery->whereHas('metadataValues', function ($query) use ($request) {
                $query->where('field_name', 'subject')
                    ->where('field_value', $request->subject_id);
            });
            $unAcadQuery->whereHas('metadataValues', function ($query) use ($request) {
                $query->where('field_name', 'subject')
                    ->where('field_value', $request->subject_id);
            });
        }
        $perPageRecords = Session::get('per_page_records', config('constants.PAGINATION.default'));
        $this->data['courses'] = $acadQuery->orderBy('id', 'DESC')->paginate($perPageRecords);

        if ($this->data['category']->id == 2) {
            $this->data['unAcadCourses'] = $unAcadQuery->orderBy('id', 'DESC')->paginate($perPageRecords);
        }


        $this->data['group'] = $group;

        return view('admin.courses.index', $this->data);
    }
    public function getClassesBySeries($seriesId)
    {
        $series = BookSeries::find($seriesId);
        if (!$series || empty($series->class_subjects)) {
            return Response::json(['classes' => []]);
        }

        $data = json_decode($series->class_subjects, true);
        $classIds = collect($data)->pluck('class_id')->toArray();

        $classes = SchoolClass::whereIn('id', $classIds)
            ->where('is_active', 1)
            ->pluck('name', 'id');

        return Response::json(['classes' => $classes]);
    }

    public function getSubjectsBySeriesAndClass($seriesId, $classId)
    {
        $series = BookSeries::find($seriesId);
        if (!$series || empty($series->class_subjects)) {
            return Response::json(['subjects' => []]);
        }

        $data = json_decode($series->class_subjects, true);

        $classData = collect($data)->firstWhere('class_id', (string)$classId);
        if (!$classData || empty($classData['subject_ids'])) {
            return Response::json(['subjects' => []]);
        }

        $subjectIds = $classData['subject_ids'];
        $subjects = Subject::whereIn('id', $subjectIds)
            ->where('is_active', 1)
            ->pluck('name', 'id');

        return Response::json(['subjects' => $subjects]);
    }
    public function create()
    {
        $this->data['category'] = Category::getAllCategories();
        //   return $this->data['category'];
        $this->setCourseFormVars();
        return view('admin.courses.add_edit', $this->data);
    }
    public function saveCourse(Request $request)
    {
        try {
            // dd($request->all());
            if ($request->id > 0) {
                $success = config('constants.FLASH_REC_UPDATE_1');
                $error   = config('constants.FLASH_REC_UPDATE_0');
            } else {
                $success = config('constants.FLASH_REC_ADD_1');
                $error   = config('constants.FLASH_REC_ADD_0');
            }
            $slug = generateUniqueSlug($request->course_name, Course::class, 'slug', $request->id);
            if ($request->group == 2) {
                $this->data['savedCourseGroup'] = Category::where('id', $request->group)->firstOrFail();
            } else {
                $this->data['savedCourseGroup'] = Category::where('id', $request->subgroup)->firstOrFail();
            }
            $dataArr = [
                "category_id"    => $request->group,
                "course_name"    => $request->course_name,
                "slug"           => $slug,
                "price_type"           => $request->price_type,
                "price"          => $request->price,
                "discount_type"  => $request->discount_type,
                "discount_value" => $request->discount_value,
                "is_active"      => true,
            ];
            $extFilePath = null;

            // Check inside course_attribute
            if ($request->has('course_attribute.ext_file')) {

                foreach ($request->file('course_attribute.ext_file', []) as $fieldId => $file) {

                    if ($file instanceof \Illuminate\Http\UploadedFile) {

                        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

                        Storage::disk('public')->put(
                            "uploads/course_files/{$filename}",
                            file_get_contents($file)
                        );

                        $extFilePath = "uploads/course_files/{$filename}";

                        break; // only one file needed
                    }
                }
            }
            if ($extFilePath) {
                $dataArr['ext_file'] = $extFilePath;
            }

            // Conditionally add 'category_id' and 'sub_category_id' to $dataArr
            if (! ($request->group == 2 && $request->subgroup == null)) {
                $dataArr["sub_category_id"] = $request->subgroup;
            }
            // Create or Update the course
            $course = Course::updateOrCreate(['id' => $request->id], $dataArr);
            // Save the product_id only if it's not already set
            if ($course && empty($course->product_id)) {
                $course->product_id = 'mitt_course_' . $course->id;
                $course->save();
            }
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
                return redirect()->route('course.index', ['group' => $this->data['savedCourseGroup']->slug])
                    ->with(['success' => $success]);
            }

            return redirect()->back()->with(['error' => $error]);
        } catch (\Throwable $e) {
            Log::error('Course Save Error: ' . $e->getMessage(), [
                'line' => $e->getLine(),
                'file' => $e->getFile(),
                'trace' => $e->getTraceAsString(),
                'request' => $request->all(),
            ]);

            return redirect()->back()->with(['error' => 'Something went wrong while saving the course. Please try again later.']);
        }
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

        return redirect()->back()->with('success', 'Course deleted successfully!');
    }
    public function createChapter(Request $request, $id)
    {
        $this->data['course_id'] = $id;
        $this->data['course'] = Course::with('metadataValues')->find($id);
        $this->data['courseName'] = $this->data['course']->course_name;
        $this->data['courseCategory'] = $this->data['course']->category_id;
        $chaptersQuery = CourseChapter::where('course_id', $id)->orderBy('sort_order', 'ASC');
        $perPageRecords = Session::get('per_page_records', config('constants.PAGINATION.default'));



        if ($request->filled('chapter_name')) {
            $chaptersQuery->where('chapter_name', 'like', '%' . $request->chapter_name . '%');
        }
        $this->data['chapters'] =  $chaptersQuery->paginate($perPageRecords);
        $this->data['folder_list'] = MediaFolder::where('parent_id', Auth::id())->pluck('folder_name', 'id');

        return view('admin.courses.add_edit_chapter', $this->data);
    }
    public function saveChapter(Request $request)
    {
        $request->validate([
            'course_id'            => 'required',
            'chapter_title'       => 'required|max:255',
            'chapter_description' => 'required',
            'sort_order'          => 'required|integer',
            'supporting_folder_id' => 'nullable',
            // 'chapter_file.*'      => 'nullable|mimes:pdf,docx,xlsx,jpeg,jpg,png,mp4,avi,mov',
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
            $chapterTitle = preg_replace('/\s+/', ' ', $request->chapter_title);

            // Update or Create the chapter
            $chapter = CourseChapter::updateOrCreate(
                [
                    'id' => $request->id,
                ],
                [
                    'course_id'            => $request->course_id,
                    'chapter_name'         => $chapterTitle,
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
            // dd($request->all());

            if ($request->hasFile('chapter_file')) {
                foreach ($request->file('chapter_file') as $index => $file) {
                    $customFileName = $request->file_name[$index] ?? null;
                    $videoSortOrder = $request->video_sort_order[$index];
                    $videoLanguage = $request->language[$index] ?? 'bilingual';
                    $videoType = $request->video_view_type[$index] ?? 'both';
                    $fileExtension  = $file->getClientOriginalExtension();

                    // if ($fileExtension === 'zip') {
                    //     // Call ZIP handler
                    //     $this->handleZipUpload($file, $chapter, $customFileName, $videoSortOrder);
                    // } else {

                    $slug = Str::slug($chapterTitle, '-');
                    $slug = Str::limit($slug, 10, '');
                    $uniqueNumber = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);  // Pads number to 4 digits
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
                        'sort_order'      => $videoSortOrder,
                        'language'        => $videoLanguage,
                        'video_view_type' => $videoType,
                        'file_name'       => $customFileName,
                        'file_size'       => $fileSize,
                        'mime_type'       => $mimeType,
                        'uploaded_by'     => auth()->id(),
                        'video_duration'  => $videoDuration,
                    ]);
                    // }
                }
            }

            if ($request->link_url) {
                foreach ($request->link_url as $index => $file) {
                    if (empty($file)) {
                        continue;
                    }
                    $customLinkName = $request->link_name[$index] ?? null;
                    $linkSortOrder = $request->link_sort_order[$index] ?? null;
                    $linkUrl = $request->link_url[$index] ?? null;
                    $uniqueNumber = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);  // Pads number to 4 digits
                    $LinkName = time() . '-' . $index . '-' . $uniqueNumber;
                    $originalName  = 'AW-' . $uniqueNumber;
                    MediaFiles::create([
                        'tbl_id'          => $chapter->id,
                        'type'            => 'activity_worksheet_link',
                        'attachment_file' => $LinkName,
                        'original_name'   => $originalName,
                        'link_url'   => $linkUrl,
                        'sort_order'     => $linkSortOrder,
                        'file_name'  => $customLinkName,
                        'uploaded_by'     => auth()->id(),
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
    private function handleZipUpload($file, $chapter, $customFileName = null, $sortOrder = 1)
    {
        $fileExtension = $file->getClientOriginalExtension();
        $fileSize      = $file->getSize();
        $mimeType      = $file->getMimeType();
        $slug          = Str::limit(Str::slug($chapter->chapter_name, '-'), 10, '');
        $uniqueNumber  = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $fileName      = time() . '-' . $sortOrder . '-' . $uniqueNumber . '.' . $fileExtension;
        $originalName  = 'DC-' . $slug . '-' . $uniqueNumber . '.' . $fileExtension;

        // Save the zip file to storage (same as others)
        Storage::disk('public')->put("uploads/course_chapter_files/{$fileName}", file_get_contents($file));

        // Define extraction path
        $extractFolder    = "uploads/course_chapter_extracted/{$chapter->id}/{$uniqueNumber}";
        $fullExtractPath  = public_path($extractFolder);
        File::makeDirectory($fullExtractPath, 0755, true, true);

        // Move zip temporarily to extract
        $tempZipPath = storage_path("app/temp-{$fileName}");
        file_put_contents($tempZipPath, file_get_contents($file));

        $zip = new ZipArchive;
        if ($zip->open($tempZipPath) === true) {
            $zip->extractTo($fullExtractPath);
            $zip->close();
            unlink($tempZipPath);

            MediaFiles::create([
                'tbl_id'          => $chapter->id,
                'type'            => 'course_chapter_zip',
                'attachment_file' => $fileName, // points to saved zip
                'original_name'   => $originalName,
                'file_extension'  => $fileExtension,
                'file_size'       => $fileSize,
                'mime_type'       => $mimeType,
                'sort_order'      => $sortOrder,
                'file_name'       => $customFileName ?? 'ZIP HTML Content',
                'uploaded_by'     => auth()->id(),
                'video_duration'  => null, // Optional: or extract from inside if needed
            ]);
        } else {
            throw new \Exception("Failed to open ZIP file.");
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
            $chapterTitle = preg_replace('/\s+/', ' ', $request->chapter_title);


            // Update or Create the chapter
            $chapter = CourseChapter::updateOrCreate(
                [
                    'id' => $request->id,
                ],
                [
                    'course_id'            => $request->course_id,
                    'chapter_name'         => $chapterTitle,
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
        $this->data['chapter_id'] = $id;
        $this->data['chapter']         = CourseChapter::where('id', $id)->first();
        $this->data['courseName'] = Course::where('id', $this->data['chapter']->course_id)->value('course_name');
        $this->data['chapter_content'] = MediaFiles::where('tbl_id', $id)->where('type', 'course_chapter')->orderBy('sort_order', 'asc')->get();
        $this->data['activity_worksheet_content'] = MediaFiles::where('tbl_id', $id)->where('type', 'activity_worksheet_link')->orderBy('sort_order', 'asc')->get();
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
            // 'chapter_file.*'       => 'nullable|mimes:pdf,docx,xlsx,jpeg,jpg,png,mp4,avi,mov', // Validate file extensions
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

            $chapterTitle = preg_replace('/\s+/', ' ', $request->chapter_title);

            // Update the chapter
            $chapter->update([
                'chapter_name'         => $chapterTitle,
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
                        $slug = Str::slug($chapterTitle, '-');
                        $slug = Str::limit($slug, 10, '');
                        $videoSortOrder = $request->video_sort_order[$index];
                        $videoLanguage = $request->language[$index] ?? 'bilingual';
                        $videoType = $request->video_view_type[$index] ?? 'both';
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
                            'language'        => $videoLanguage,
                            'video_view_type' => $videoType,
                            'file_name'       => $customFileName,
                            'file_size'       => $fileSize,
                            'mime_type'       => $mimeType,
                            'uploaded_by'     => auth()->id(),
                            'video_duration'  => $videoDuration,
                        ]);
                    }
                }
            }

            if ($request->link_url) {
                foreach ($request->link_url as $index => $file) {
                    if (empty($file)) {
                        continue;
                    }
                    $customLinkName = $request->link_name[$index] ?? null;
                    $linkSortOrder = $request->link_sort_order[$index] ?? null;
                    $linkUrl = $request->link_url[$index] ?? null;
                    $uniqueNumber = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);  // Pads number to 4 digits
                    $LinkName = time() . '-' . $index . '-' . $uniqueNumber;
                    $originalName  = 'AW-' . '-' . $uniqueNumber;
                    MediaFiles::create([
                        'tbl_id'          => $chapter->id,
                        'type'            => 'activity_worksheet_link',
                        'attachment_file' => $LinkName,
                        'original_name'   => $originalName,
                        'link_url'   => $linkUrl,
                        'sort_order'     => $linkSortOrder,
                        'file_name'  => $customLinkName,
                        'uploaded_by'     => auth()->id(),
                    ]);
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
                if (Storage::disk('public')->exists('uploads/course_chapter_files/' . $value->attachment_file)) {
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

    public function complimentaryIndex(Request $request)
    {
        $perPageRecords = Session::get('per_page_records', config('constants.PAGINATION.default'));
        $unAcadQuery = Course::with('metadataValues')->whereHas('metadataValues', function ($query) {
            $query->where('field_name', 'available_for_complimentary_package')
                ->whereIn('field_value', ['all', '1']);
        });
        if ($request->filled('course_name')) {
            $unAcadQuery->where('course_name', 'like', '%' . $request->course_name . '%');
        }
        if ($request->filled('sub_category_id')) {
            $unAcadQuery->where('sub_category_id', $request->sub_category_id);
        }
        $this->data['courses'] = $unAcadQuery->orderBy('id', 'DESC')->paginate($perPageRecords);

        $this->data['subcategories'] = Category::where('status', 1)->where('parent_id', 2)->pluck('name', 'id')->toArray();


        $this->data['schools'] = Schools::pluck('name', 'id');
        $this->data['state'] = State::pluck('name', 'id')->toArray();
        return view('admin.courses.complimentary-index', $this->data);
    }
    public function academicActivitiesIndex(Request $request)
    {
        $this->data['bookSeries'] = BookSeries::pluck('name', 'id')->toArray();
        $this->data['classes'] = SchoolClass::pluck('name', 'id')->toArray();
        $this->data['subjects'] = Subject::pluck('name', 'id')->toArray();
        $perPageRecords = Session::get('per_page_records', config('constants.PAGINATION.default'));
        $AcadActivityQuery = Course::with('metadataValues')->where('category_id', 1)->where('sub_category_id', 37)->orderBy('id', 'DESC');
        if ($request->filled('course_name')) {
            $AcadActivityQuery->where('course_name', 'like', '%' . $request->course_name . '%');
        }

        if ($request->filled('series_id')) {
            $AcadActivityQuery->whereHas('metadataValues', function ($query) use ($request) {
                $query->where('field_name', 'series')
                    ->where('field_value', $request->series_id);
            });
        }

        if ($request->filled('class_id')) {
            $AcadActivityQuery->whereHas('metadataValues', function ($query) use ($request) {
                $query->where('field_name', 'class')
                    ->where('field_value', $request->class_id);
            });
        }

        if ($request->filled('subject_id')) {
            $AcadActivityQuery->whereHas('metadataValues', function ($query) use ($request) {
                $query->where('field_name', 'subject')
                    ->where('field_value', $request->subject_id);
            });
        }
        // $this->data['courses'] = $unAcadQuery->orderBy('id', 'DESC')->paginate($perPageRecords);
        $this->data['courses'] = $AcadActivityQuery->paginate($perPageRecords);

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

    // old function Before D@C User CR - indexOldBeforeD2C
    public function indexOldBeforeD2C(Request $request)
    {
        $activeTab = $request->input('active_tab', 'academic_digital_content');
        $this->data['activeTab'] = $activeTab;

        // $this->data['categoryId'] = Category::where('status', 1)->where('parent_id', null)->get(['id', 'name']);
        $this->data['categories'] = Category::where('status', 1)
            ->where('parent_id', 1)
            ->get(['id', 'name', 'slug']);
        $this->data['bookSeries'] = BookSeries::pluck('name', 'id')->toArray();
        $this->data['classes'] = SchoolClass::pluck('name', 'id')->toArray();
        $this->data['subjects'] = Subject::pluck('name', 'id')->toArray();

        $acadQuery = Course::with('metadataValues', 'subCategory', 'category')->where('category_id', 1)->where('sub_category_id', 6);

        $unAcadQuery = Course::with('metadataValues')->where('category_id', 2);
        $acadActivitesQuery = Course::with('metadataValues')->where('category_id', 1)->where('sub_category_id', 7);

        if ($request->filled('course_name')) {
            $acadQuery->where('course_name', 'like', '%' . $request->course_name . '%');
            $unAcadQuery->where('course_name', 'like', '%' . $request->course_name . '%');
            $acadActivitesQuery->where('course_name', 'like', '%' . $request->course_name . '%');
        }

        if ($request->filled('category_id')) {
            $acadQuery->where('category_id', $request->category_id);
            $unAcadQuery->where('category_id', $request->category_id);
        }
        if ($request->filled('series_id')) {
            $acadQuery->whereHas('metadataValues', function ($query) use ($request) {
                $query->where('field_name', 'series')
                    ->where('field_value', $request->series_id);
            });
            $acadActivitesQuery->whereHas('metadataValues', function ($query) use ($request) {
                $query->where('field_name', 'series')
                    ->where('field_value', $request->series_id);
            });
        }

        if ($request->filled('class_id')) {
            $acadQuery->whereHas('metadataValues', function ($query) use ($request) {
                $query->where('field_name', 'class')
                    ->where('field_value', $request->class_id);
            });
            $acadActivitesQuery->whereHas('metadataValues', function ($query) use ($request) {
                $query->where('field_name', 'class')
                    ->where('field_value', $request->class_id);
            });
            $unAcadQuery->whereHas('metadataValues', function ($query) use ($request) {
                $query->where('field_name', 'class')
                    ->where('field_value', $request->class_id);
            });
        }

        if ($request->filled('subject_id')) {
            $acadQuery->whereHas('metadataValues', function ($query) use ($request) {
                $query->where('field_name', 'subject')
                    ->where('field_value', $request->subject_id);
            });
            $acadActivitesQuery->whereHas('metadataValues', function ($query) use ($request) {
                $query->where('field_name', 'subject')
                    ->where('field_value', $request->subject_id);
            });
            $unAcadQuery->whereHas('metadataValues', function ($query) use ($request) {
                $query->where('field_name', 'subject')
                    ->where('field_value', $request->subject_id);
            });
        }

        $this->data['acadCourses'] = $acadQuery->orderBy('id', 'DESC')->paginate(
            config('constants.PAGINATION.default')
        );

        $this->data['unAcadCourses'] = $unAcadQuery->orderBy('id', 'DESC')->paginate(
            config('constants.PAGINATION.default')
        );
        $this->data['acadActivitesQuery'] = $acadActivitesQuery->orderBy('id', 'DESC')->paginate(
            config('constants.PAGINATION.default')
        );

        return view('admin.courses.index-before-d2c', $this->data);
    }



    public function exportCoursesToExcel()
    {
        $file = Excel::raw(new CourseContentExport(), ExcelFormat::XLSX);

        return Response::make($file, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="courses_content_report.xlsx"',
        ]);
    }



    // course merege funtions





    public function mergeCourse(Request $request)
    {
        $this->data['bookSeries'] = BookSeries::pluck('name', 'id')->toArray();
        $this->data['classes'] = SchoolClass::pluck('name', 'id')->toArray();
        $this->data['subjects'] = Subject::pluck('name', 'id')->toArray();
        $this->data['pairedCourses'] = [];

        // Only run the query if any filter is present
        if ($request->filled('series_id') || $request->filled('class_id') || $request->filled('subject_id')) {
            $AcadActivityQuery = Course::with('metadataValues')
                ->where('category_id', 1)
                ->orderBy('id', 'DESC');

            if ($request->filled('series_id')) {
                $AcadActivityQuery->whereHas('metadataValues', function ($query) use ($request) {
                    $query->where('field_name', 'series')
                        ->where('field_value', $request->series_id);
                });
            }

            if ($request->filled('class_id')) {
                $AcadActivityQuery->whereHas('metadataValues', function ($query) use ($request) {
                    $query->where('field_name', 'class')
                        ->where('field_value', $request->class_id);
                });
            }

            $subjectIds = $request->get('subject_id', []);
            if (!empty($subjectIds)) {
                $AcadActivityQuery->whereHas('metadataValues', function ($query) use ($subjectIds) {
                    $query->where('field_name', 'subject')
                        ->whereIn('field_value', $subjectIds);
                });
            }

            $courses = $AcadActivityQuery->get();

            $pairedCourses = [];
            $temp = [];

            foreach ($courses as $course) {
                $subjectMeta = $course->metadataValues->firstWhere('field_name', 'subject');
                if (!$subjectMeta) continue;

                $subjectName = Subject::find($subjectMeta->field_value)?->name;

                if (str_ends_with($subjectName, '(EL)')) {
                    $baseName = trim(str_replace('(EL)', '', $subjectName));
                    $temp[$baseName]['el'] = $course;
                } else {
                    $temp[$subjectName]['base'] = $course;
                }
            }

            foreach ($temp as $subject => $pair) {
                if (isset($pair['base']) && isset($pair['el'])) {
                    $pair['base']->display_name = $pair['base']->course_name . ' - ' . $subject;
                    $pair['el']->display_name = $pair['el']->course_name . ' - ' . $subject . '(EL)';

                    $pairedCourses[] = [
                        'course1' => $pair['base'],
                        'course2' => $pair['el'],
                    ];
                }
            }

            $this->data['pairedCourses'] = $pairedCourses;
        }

        return view('admin.merge-courses', $this->data);
    }



    public function mergeCourseSubmit(Request $request)
    {
        $course1 = Course::findOrFail($request->course1_id);
        $course2 = Course::findOrFail($request->course2_id);

        $chapters1 = $course1->totalChapters;
        $chapters2 = $course2->totalChapters;

        // Step 1: Mark all course1 media bilingual
        foreach ($chapters1 as $chapter1) {
            MediaFiles::where('tbl_id', $chapter1->id)
                ->where('type', 'course_chapter')
                ->update(['language' => 'bilingual']);
        }

        // Helper: normalize by trimming + collapsing spaces + lowercasing
        $normalize = function ($name) {
            return strtolower(preg_replace('/\s+/', ' ', trim($name)));
        };

        // Step 2: Match course2 chapters against course1
        foreach ($chapters2 as $chapter2) {
            $matchingChapter1 = $chapters1->first(function ($ch1) use ($chapter2, $normalize) {
                // First check: strict trim + case-insensitive
                if (strcasecmp(trim($ch1->chapter_name), trim($chapter2->chapter_name)) === 0) {
                    return true;
                }
                // Second check: normalized (collapse spaces + lowercase)
                return $normalize($ch1->chapter_name) === $normalize($chapter2->chapter_name);
            });

            if ($matchingChapter1) {
                // Merge into existing chapter
                MediaFiles::where('tbl_id', $chapter2->id)
                    ->where('type', 'course_chapter')
                    ->update([
                        'tbl_id'   => $matchingChapter1->id,
                        'language' => 'english',
                    ]);
            } else {
                // Keep as separate English chapter
                MediaFiles::where('tbl_id', $chapter2->id)
                    ->where('type', 'course_chapter')
                    ->update([
                        'language' => 'english',
                    ]);
            }
        }

        // Step 3: Mark both courses as merged
        $course1->is_merged = 1;
        $course1->save();

        $course2->is_merged = 1;
        $course2->save();

        return back()->with('success', 'Courses merged successfully');
    }
}
