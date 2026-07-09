<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\BookSeries;
use App\Models\Category;
use App\Models\Course;
use App\Models\CourseChapter;
use App\Models\FrontendCoursesView;
use App\Models\SubscriptionPurchase;
use Illuminate\Http\Request;
use Jorenvh\Share\ShareFacade as Share;


class DemoCoursesController extends Controller
{
    private array $data = [];

    public function accessDenied(Request $request)
    {
        return view('errors.access-denied-403', $this->data);
    }
    public function demoCoursesContentView(Request $request, $series_name)
    {
        $bookSeries = BookSeries::where('is_active', 1)
            ->where('slug', $series_name)
            ->first();

        if (!$bookSeries) {
            return view('errors.404');
        }

        $bookSeriesId = $bookSeries->id;

        $selectedLanguage = $request->get('language', 'bilingual'); // default = bilingual

        $olympiadCourses = Course::with(['metadataValues.subjectInfo', 'metadataValues.classInfo'])
            ->where('courses.category_id', 1)
            ->where('courses.is_active', 1)
            ->whereHas('metadataValues', function ($query) use ($bookSeriesId) {
                $query->where('field_name', 'series')
                    ->where('field_value', $bookSeriesId);
            })
            ->get();

        $groupedCourses = [];

        foreach ($olympiadCourses as $course) {
            $classMeta = $course->metadataValues->where('field_name', 'class')->first();
            $subjectMeta = $course->metadataValues->where('field_name', 'subject')->first();
            $bookCoverImage = $course->metadataValues->where('field_name', 'book_cover_image')->first();
            $thumbnailImage = $course->metadataValues->where('field_name', 'thumbnail_image')->first();

            $classId = $classMeta->field_value ?? null;
            $classObj = $classMeta->classInfo ?? null;
            $subjectObj = $subjectMeta->subjectInfo ?? null;

            if ($classId && $classObj && $subjectObj) {
                $courseChapters = CourseChapter::with('chapterListing')
                    ->where('course_id', $course->id)
                    ->orderBy('sort_order')
                    ->get();

                // --- Apply video view type + language preference ---
                $courseChapters->each(function ($chapter) use ($selectedLanguage) {
                    $videos = $chapter->chapterListing
                        ->whereIn('file_extension', [
                            'mp4',
                            'avi',
                            'mov',
                            'm4v',
                            'm4p',
                            'mpg',
                            'mp2',
                            'mpeg',
                            'mpe',
                            'mpv',
                            'm2v',
                            'wmv',
                            'flv',
                            'mkv',
                            'webm',
                            '3gp',
                            'm2ts',
                            'ogv',
                            'ts',
                            'mxf'
                        ]);


                    // ✅ Filter by selected language or include if NULL (no language set)
                    $videos = $videos->filter(function ($video) use ($selectedLanguage) {
                        return $video->language === null || $video->language === $selectedLanguage;
                    });


                    // Step 1: Prefer demo
                    $video = $videos->firstWhere('video_view_type', 'demo');

                    if (!$video) {
                        $video = $videos->firstWhere('video_view_type', 'both');
                    }

                    if (!$video) {
                        $video = $videos->firstWhere('video_view_type', 'product');
                    }

                    if (!$video) {
                        $video = $videos->firstWhere('video_view_type', null);
                    }

                    $chapter->filtered_video = $video;
                });

                $videoLessons = $courseChapters->take(3)->filter(fn($lesson) => !empty($lesson->filtered_video));

                if ($videoLessons->isNotEmpty()) {
                    if (!isset($groupedCourses[$classId])) {
                        $groupedCourses[$classId] = [
                            'class_id' => $classId,
                            'class' => $classObj,
                            'subjects' => [],
                        ];
                    }

                    $groupedCourses[$classId]['subjects'][] = (object)[
                        'id' => $subjectObj->id,
                        'slug' => $course->slug,
                        'name' => $subjectObj->name,
                        'course_id' => $course->id,
                        'course_name' => $course->course_name,
                        'book_cover_image' => $bookCoverImage->field_value ?? null,
                        'thumbnail_image' => $thumbnailImage->field_value ?? null,
                        'lessons' => $videoLessons,
                    ];
                }
            }
        }

        $classOrder = [
            'Nursery' => 1,
            'NURSERY' => 1,
            'LKG' => 2,
            'UKG' => 3,
            'Class 1' => 4,
            'Class 2' => 5,
            'Class 3' => 6,
            'Class 4' => 7,
            'Class 5' => 8,
            'Class 6' => 9,
            'Class 7' => 10,
            'Class 8' => 11,
            'Class 9' => 12,
            'Class 10' => 13,
            'Class 11' => 14,
            'Class 12' => 15,
        ];

        $classCourses = collect($groupedCourses)
            ->sortBy(fn($item) => $classOrder[trim($item['class']->name ?? '')] ?? 999)
            ->map(fn($item) => (object) $item)
            ->values();

        return view('frontend.demo-courses-index', compact('classCourses', 'bookSeries', 'selectedLanguage'));
    }


    public function index(Request $request)
    {
        $category = Category::where('status', 1)->where('slug', 'olympiad')->first();

        if ($category) {
            $seriesId = 22;
            $subjectId = 67;
            // $subjectId = 51; // local system id

            $olympiadCourses = Course::with(['metadataValues.subjectInfo', 'metadataValues.classInfo'])
                ->where('courses.category_id', 1)
                ->where('courses.sub_category_id', 35)
                ->where('courses.is_active', 1)
                ->whereHas('metadataValues', function ($query) use ($seriesId) {
                    $query->where('field_name', 'series')
                        ->where('field_value', $seriesId);
                })
                ->whereHas('metadataValues', function ($query) use ($subjectId) {
                    $query->where('field_name', 'subject')
                        ->where('field_value', $subjectId);
                })
                ->get();

            // Group by class_id
            $groupedCourses = [];


            foreach ($olympiadCourses as $course) {
                $classMeta = $course->metadataValues->where('field_name', 'class')->first();
                $subjectMeta = $course->metadataValues->where('field_name', 'subject')->first();
                $bookCoverImage = $course->metadataValues->where('field_name', 'book_cover_image')->first();
                $thumbnailImage = $course->metadataValues->where('field_name', 'thumbnail_image')->first();

                $classId = $classMeta->field_value ?? null;
                $classObj = $classMeta->classInfo ?? null;
                $subjectObj = $subjectMeta->subjectInfo ?? null;

                if ($classId && $classObj && $subjectObj) {
                    // Fetch chapters for this course
                    $courseChapters = CourseChapter::with('chapterListing')
                        ->where('course_id', $course->id)
                        ->orderBy('sort_order')
                        ->get();

                    $courseChapters->each(function ($chapter) {
                        $chapter->filtered_video = $chapter->chapterListing
                            ->whereIn('file_extension', [
                                'mp4',
                                'avi',
                                'mov',
                                'm4v',
                                'm4p',
                                'mpg',
                                'mp2',
                                'mpeg',
                                'mpe',
                                'mpv',
                                'm2v',
                                'wmv',
                                'flv',
                                'mkv',
                                'webm',
                                '3gp',
                                'm2ts',
                                'ogv',
                                'ts',
                                'mxf'
                            ])
                            ->first();
                    });

                    $videoLessons = $courseChapters->take(3); // Only first 3 lessons (optional)

                    if (!isset($groupedCourses[$classId])) {
                        $groupedCourses[$classId] = [
                            'class_id' => $classId,
                            'class' => $classObj,
                            'subjects' => [],
                        ];
                    }

                    $groupedCourses[$classId]['subjects'][] = (object)[
                        'id' => $subjectObj->id,
                        'slug' => $course->slug,
                        'name' => $subjectObj->name,
                        'course_id' => $course->id,
                        'course_name' => $course->course_name,
                        'book_cover_image' => $bookCoverImage->field_value ?? null,
                        'thumbnail_image' => $thumbnailImage->field_value ?? null,
                        'lessons' => $videoLessons,
                    ];
                }
            }

            // $classCourses = collect($groupedCourses)->values(); // Convert to collection
            $classCourses = collect($groupedCourses)->map(function ($item) {
                return (object) $item;
            })->values();
            return view('frontend.olympiad-courses-index', compact('classCourses'));
        }
    }

    //V Academic Course Details Page
    public function aboutOlympiadCourse(Request $request, $slug)
    {
        $userId    = auth()->check() ? auth()->id() : null;
        $sessionId = session('user_session_id');
        $course    = Course::where('slug', $slug)->with(['metadataValues.subjectInfo'])->first();
        $seriesData = FrontendCoursesView::get();

        // Extract all unique series IDs and class IDs
        $seriesIds = $seriesData->pluck('series_id')->unique()->toArray();
        $classesIds = $seriesData->flatMap(function ($item) {
            return explode(',', $item->classes_ids);
        })->unique()->toArray();

        $acadCourses = Course::with(['metadataValues.subjectInfo'])
            ->where('category_id', 1)
            ->where('is_active', 1)

            // ✅ Must match series
            ->whereHas('metadataValues', function ($query) use ($seriesIds) {
                $query->where('field_name', 'series')
                    ->whereIn('field_value', $seriesIds);
            })

            // ✅ Must also match class
            ->whereHas('metadataValues', function ($query) use ($classesIds) {
                $query->where('field_name', 'class')
                    ->whereIn('field_value', $classesIds);
            })

            ->withCount([
                'cartItems as in_cart' => function ($query) use ($userId, $sessionId) {
                    $query->where(function ($q) use ($userId, $sessionId) {
                        if ($userId) {
                            $q->where('user_id', $userId)
                                ->whereNull('item_id')
                                ->where('status', 'active');
                        } else {
                            $q->where('session_id', $sessionId)
                                ->whereNull('item_id')
                                ->where('status', 'active');
                        }
                    });
                },
                'wishlistItems as in_wishlist' => function ($query) use ($sessionId) {
                    $query->where('session_id', $sessionId)->where('status', 'active');
                },
            ])
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();




        $courseChapters = CourseChapter::with('chapterListing')
            ->where('course_id', $course->id)
            ->orderBy('sort_order')
            ->get();

        $courseChapters->each(function ($chapter) {
            $chapter->filtered_video = $chapter->chapterListing
                ->whereIn('file_extension', ['mp4', 'avi', 'mov', 'm4v', 'm4p', 'mpg', 'mp2', 'mpeg', 'mpe', 'mpv', 'm2v', 'wmv', 'flv', 'mkv', 'webm', '3gp', '3gp', 'm2ts', 'ogv', 'ts', 'mxf'])
                ->first();
        });

        // Calculate total video duration
        $videoDuration = $courseChapters->sum(function ($chapter) {
            return $chapter->chapterListing->first()->video_duration ?? 0; // Get only the first video's duration
        });

        $hours   = floor($videoDuration / 3600);
        $minutes = floor(($videoDuration % 3600) / 60);
        $seconds = $videoDuration % 60;

        $formattedDuration = "{$hours}h {$minutes}m {$seconds}s";

        $shareButtons = Share::page(url('about-academic-course/' . $course->slug), $course->course_name)
            ->facebook()
            ->twitter()
            ->linkedin()
            ->whatsapp();

        return view('frontend.about-olympiad-course', compact('course', 'acadCourses', 'courseChapters', 'formattedDuration', 'shareButtons'));
    }
}
