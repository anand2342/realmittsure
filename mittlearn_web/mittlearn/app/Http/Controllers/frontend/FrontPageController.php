<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\AdditionalDataRow;
use App\Models\Blog;
use App\Models\BlogView;
use App\Models\Cart;
use App\Models\Category;
use App\Models\Classes;
use App\Models\CmsAboutUs;
use App\Models\CmsPage;
use App\Models\ContactInquiry;
use App\Models\Course;
use App\Models\CourseChapter;
use App\Models\ErrorLog;
use App\Models\Faq;
use App\Models\FrontendCoursesView;
use App\Models\HomePageContent;
use App\Models\MediaFiles;
use App\Models\MediaFolder;
use App\Models\Setting;
use App\Models\Subject;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionPurchase;
use App\Models\Testimonial;
use App\Models\User;
use App\Models\UserRole;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Jorenvh\Share\ShareFacade as Share;
use Matrix\Operators\Addition;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Intervention\Image\Facades\Image;

class FrontPageController extends Controller
{
    private array $data = [];

    public function accessDenied(Request $request)
    {
        return view('errors.access-denied-403', $this->data);
    }
    public function index(Request $request)
    {
        $userId    = auth()->check() ? auth()->id() : null;
        $sessionId = session('user_session_id');

        $this->data['plans']         = SubscriptionPlan::with(['subscriptionPlanFeature', 'subscriptionPlanPrice'])->orderBy('sort_order', 'asc')->where('status', '1')->get();
        $this->data['preSchool']     = Classes::where('is_active', 1)->whereIn('id', [19, 21, 23])->pluck('id')->toArray();
        $this->data['primarySchool'] = Classes::where('is_active', 1)->whereIn('id', [4, 5, 6, 7, 8])->pluck('id')->toArray();
        $this->data['middleSchool']  = Classes::where('is_active', 1)->whereIn('id', [9, 10, 11])->pluck('id')->toArray();
        $this->data['seniorSchool']  = Classes::where('is_active', 1)->whereIn('id', [12, 13, 14, 15])->pluck('id')->toArray();

        // return $this->data['$preSchool'];
        $seriesData = FrontendCoursesView::get();

        // Extract all unique series IDs and class IDs from FrontendCoursesView
        $seriesIds = $seriesData->pluck('series_id')->unique()->toArray();
        $classesIds = $seriesData->flatMap(function ($item) {
            return explode(',', $item->classes_ids);
        })->unique()->toArray();

        $this->data['acadCourses'] = Course::with(['metadataValues.subjectInfo'])
            ->where('category_id', 1)
            ->where('is_active', 1)
            ->whereHas('metadataValues', function ($query) use ($seriesIds) {
                $query->where('field_name', 'series')
                    ->whereIn('field_value', $seriesIds);
            })
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
            ->get();




        $this->data['acadCoursesLatest'] = Course::with(['metadataValues.subjectInfo'])
            ->where('category_id', 1)
            ->where('is_active', 1)
            ->whereHas('metadataValues', function ($query) use ($seriesIds) {
                $query->where('field_name', 'series')
                    ->whereIn('field_value', $seriesIds);
            })
            ->whereHas('metadataValues', function ($query) use ($classesIds) {
                $query->where('field_name', 'class')
                    ->whereIn('field_value', $classesIds);
            })
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        $this->data['nonAcadCourses'] = Course::with(['metadataValues.subjectInfo', 'getSubCategory', 'totalChapters'])
            ->withCount([
                'cartItems as in_cart'         => function ($query) use ($userId, $sessionId, $request) {
                    $query->where(function ($q) use ($userId, $sessionId, $request) {
                        if ($userId) {
                            $q->where('user_id', $userId)
                                ->where('item_id', null)
                                ->where('status', 'active');
                        } else {
                            $q->where('session_id', $sessionId)
                                ->where('item_id', null)
                                ->where('status', 'active');
                        }
                    });
                },
                'wishlistItems as in_wishlist' => function ($query) use ($sessionId) {
                    $query->where(function ($q) use ($sessionId) {
                        $q->where('session_id', $sessionId)->where('status', 'active');
                    });
                },
            ])
            ->where('category_id', 2)
            ->where('is_active', 1)
            ->whereHas('metadataValues', function ($query) {
                $query->where('field_name', 'available_for_complimentary_package')
                    ->whereIn('field_value', ['all', '0']);
            })
            ->get();
        $this->data['nonAcadCoursesLatest'] = Course::with(['metadataValues.subjectInfo', 'getSubCategory'])
            ->where('category_id', 2)
            ->where('is_active', 1)
            ->whereHas('metadataValues', function ($query) {
                $query->where('field_name', 'available_for_complimentary_package')
                    ->whereIn('field_value', ['all', '0']);
            })
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();
        $this->data['nonAcadSubCategory'] = Category::where('parent_id', 2)
            ->where('status', 1)
            ->get();
        // return    $this->data['nonAcadSubCategory'];
        // dd($this->data['nonAcadCourses']);
        $this->data['testimonial'] = Testimonial::where('status', 1)->orderBy('id', "DESC")->get();

        $subscription = SubscriptionPurchase::where('user_id', auth()->id())->first();

        if ($subscription) {
            $subscription->plan_json    = json_decode($subscription->plan_json, true);    // Decode JSON string
            $subscription->courses_json = json_decode($subscription->courses_json, true); // Decode JSON string
        }
        $this->data['firstBanner']           = HomePageContent::where('section_name', 'first_banner')->first();
        $this->data['firstBannerAdditional'] = AdditionalDataRow::with('categoriesFront')->where('type', 'first_banner')->get();
        $this->data['firstBannerAdditionalAcad'] = AdditionalDataRow::with('categoriesFront')->where('type', 'first_banner')->where('model_id', '1')->get();
        $this->data['firstBannerAdditionalNonAcad'] = AdditionalDataRow::with('categoriesFront')->where('type', 'first_banner')->where('model_id', '2')->get();
        // return   $this->data['firstBannerAdditional'];
        $this->data['coreFeatureBanner']              = HomePageContent::where('section_name', 'feature_banner')->first();
        $this->data['coreFeatureBannerAdditional']    = AdditionalDataRow::where('type', 'academic_feature_banner')->get();
        $this->data['coreNonFeatureBannerAdditional'] = AdditionalDataRow::where('type', 'non_academic_feature_banner')->get();
        $this->data['instructorBanner']               = HomePageContent::where('section_name', 'instructor_banner')->first();
        $this->data['testimonialBanner']              = HomePageContent::where('section_name', 'testimonial_banner')->first();
        // return  $this->data['testimonialBanner'];
        $this->data['subscription'] = $subscription;

        $this->data['isFreeTrialAvailable'] = SubscriptionPlan::where('status', 1)->where('is_free_trial', 1)->first();

        // Existing code to fetch className and nonAcademic courses
        $this->data['className'] = getClasses();

        $this->data['nonAcademic'] = Course::where('category_id', 2)
            ->where('is_active', 1)
            ->whereHas('metadataValues', function ($query) {
                $query->where('field_name', 'available_for_complimentary_package')
                    ->whereIn('field_value', ['all', '0']);
            })
            ->join('subscription_plan_courses', 'courses.id', '=', 'subscription_plan_courses.course_id')
            ->select('courses.*', 'subscription_plan_courses.plan_id', 'subscription_plan_courses.course_id')
            ->get();

        // return  $this->data['nonAcademicCategory'];
        $this->data['academicCategory']    = Category::where('status', 1)->where('id', '1')->first();
        $this->data['nonAcademicCategory'] = Category::where('status', 1)->where('id', '2')->first();

        $this->data['exclusiveBlogs'] = Blog::where('status', 'published')->with(['blogsMedia', 'categories'])->withCount('views')->orderBy('published_at', 'DESC')->take(2)->get();
        $this->data['exclusiveBlogList'] = Blog::where('status', 'published')->orderBy('published_at', 'DESC')->withCount('views')->take(4)->skip(2)->get();
        $this->data['instructorLatest']  = UserRole::where('role_slug', 'instructor')->with('user')->orderBy('id', 'DESC')->first();
        // return $this->data['instructorLatest'];
        $this->data['instructorList'] = UserRole::where('role_slug', 'instructor')->whereHas('user', function ($query) {
            $query->where('status', 1);
        })->with('user')->orderBy('id', 'DESC')->take(8)->get();

        return view('frontend.index', $this->data);
        // return view('frontend.index-old-design', $this->data);
    }
    public function courseAddToCart($courseId)
    {
        $userId                 = auth()->id();
        $sessionId              = session('user_session_id');
        $course                 = Course::find($courseId);
        $getExistingCartUserPlanId = Cart::where(function ($query) use ($userId, $sessionId) {
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
        })
            ->where('item_id', '!=', null)
            ->where('status', 'active')
            ->value('plan_id');
        $planId   = SubscriptionPlan::where('is_recommended', 1)->value('id');

        if ($getExistingCartUserPlanId) {
            $existingCartUserPlanId = $getExistingCartUserPlanId;
        } elseif ($planId) {
            $existingCartUserPlanId = $planId;
        } else {
            $existingCartUserPlanId = 3;
        }
        if (! $course) {
            return redirect()->back()->with('error', 'Course not found.');
        }
        $itemType = ($course->category_id == 1) ? 'academic_course' : 'nonacademic_course';

        // Calculate final price based on discount type
        $price    = $course->price;
        $discount = 0;

        if ($course->discount_type === 'percent') {
            $discount = ($course->discount_value / 100) * $price;
        } elseif ($course->discount_type === 'flat') {
            $discount = $course->discount_value;
        }

        $finalPrice = max(0, $price - $discount); // Ensure price does not go negative

        // Check if the course is already in the cart
        $existingCartItem = Cart::where(function ($query) use ($userId, $sessionId) {
            if ($userId) {
                $query->where('user_id', $userId);
            } else {
                $query->where('session_id', $sessionId);
            }
        })
            ->where('course_id', $courseId)
            ->where('status', 'active')
            ->first();

        if ($existingCartItem) {
            return redirect()->back()->with('info', 'Course is already in your cart.');
        }

        // Cart::where('session_id', $sessionId)->where('plan_id', '!=', $planId)->delete();

        // Add course to the cart
        Cart::create([
            'user_id'          => $userId,
            'session_id'       => $sessionId,
            'plan_id'          => $existingCartUserPlanId,
            'course_id'        => $courseId,
            'item_type'        => $itemType,
            'item_id'          => $existingCartUserPlanId,
            'added_at'         => now(),
            'quantity'         => 1,
            'full_price'       => $course->price,
            'price'            => $finalPrice,
            'discount'         => $discount,
            'coupon_code'      => null,
            'status'           => 'active',
            'created_by_admin' => 0,
            'created_at'       => now(),
        ]);

        $url = route('cart', ['user_id' => $userId ?? null, 'session_id' => $sessionId ?? null]);

        return redirect($url)->with('success', 'Course added to cart successfully.');
    }
    public function goToCart()
    {
        $userId    = Auth::id();
        $sessionId = session('user_session_id');

        $url = route('cart', ['user_id' => $userId ?? null, 'session_id' => $sessionId ?? null]);
        return redirect($url);
    }

    public function aboutUs()
    {
        // Fetch the first record from cms_about_us table
        $aboutUs = CmsAboutUs::first();

        // Decode leadership JSON
        $atGlance       = json_decode($aboutUs->at_glance, true);
        $mittsureSec    = json_decode($aboutUs->mittsure_section, true);
        $leadershipData = json_decode($aboutUs->leadership, true);

        // Extract user IDs from leadership data
        $userIds = array_filter([
            $leadershipData['primary'] ?? null,
            $leadershipData['secondary'] ?? null,
            $leadershipData['third'] ?? null,
            $leadershipData['fourth'] ?? null,
            $leadershipData['fifth'] ?? null,
            $leadershipData['sixth'] ?? null,
        ]);

        // Fetch user and user detail data
        $users = User::whereIn('id', $userIds)->with('userAdditionalDetail')->get();
        // Replace IDs with user data
        foreach ($leadershipData as $key => $userId) {
            $user = $users->find($userId); // Get the user object
            if ($user !== null) {          // Check if the user exists
                $leadershipData[$key] = $user;
            }
        }

        // Decode programs JSON
        // $programs = json_decode($aboutUs->programs, true);
        $programs = AdditionalDataRow::where('type', 'our_program')->get();

        $testimonials      = Testimonial::where('status', 1)->orderBy('id', "DESC")->get();
        $activitiesGallary = MediaFolder::with('mediaFolderFiles')->where('parent_id', 612)->where('folder_name', 'About Us Our Activities')->first();
        // Pass data to the view
        // return $aboutUs;
        return view('frontend.about-us', [
            'aboutUs'           => $aboutUs,
            'atGlance'          => $atGlance,
            'mittsureSec'       => $mittsureSec,
            'leadership'        => $leadershipData,
            'programs'          => $programs,
            'testimonials'      => $testimonials,
            'activitiesGallary' => $activitiesGallary,
        ]);
    }

    //V Academic Course Details Page
    public function aboutAcademicCourse(Request $request, $slug)
    {
        $userId    = auth()->check() ? auth()->id() : null;
        $sessionId = session('user_session_id');
        $course    = Course::where('slug', $slug)->with(['metadataValues.subjectInfo'])->withCount([
            'cartItems as in_cart' => function ($query) use ($userId, $sessionId) {
                $query->where(function ($q) use ($userId, $sessionId) {
                    if ($userId) {
                        $q->where('user_id', $userId)
                            ->where('status', 'active');
                    } else {
                        $q->where('session_id', $sessionId)
                            ->where('status', 'active');
                    }
                });
            },
        ])->first();
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
            $chapter->otherDoc = $chapter->chapterListing
                ->whereIn('file_extension', ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'png', 'jpeg', 'svg'])
                ->first();
        });

        if (!empty(request('language'))) {
            $courseChapters->each(function ($chapter) {
                $chapter->filtered_video = $chapter->chapterListing
                    ->where('language', request('language'))
                    ->first();
            });
        } else {
            $courseChapters->each(function ($chapter) {
                $chapter->filtered_video = $chapter->chapterListing
                    ->where('language', 'bilingual')
                    ->first();
            });
        }
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

        return view('frontend.about-academic-course', compact('course', 'acadCourses', 'courseChapters', 'formattedDuration', 'shareButtons'));
    }

    //V Non-Academic Course Details Page
    public function aboutNonAcadCourse(Request $request, $slug)
    {
        $userId    = auth()->check() ? auth()->id() : null;
        $sessionId = session('user_session_id');
        $course    = Course::where('slug', $slug)->with(['metadataValues.subjectInfo'])->withCount([
            'cartItems as in_cart' => function ($query) use ($userId, $sessionId) {
                $query->where(function ($q) use ($userId, $sessionId) {
                    if ($userId) {
                        $q->where('user_id', $userId)
                            ->where('status', 'active');
                    } else {
                        $q->where('session_id', $sessionId)
                            ->where('status', 'active');
                    }
                });
            },
        ])->where('is_active', 1)->firstOrFail();

        $nonAcadCourses = Course::with(['metadataValues.subjectInfo'])
            ->withCount([
                'cartItems as in_cart'         => function ($query) use ($userId, $sessionId, $request) {
                    $query->where(function ($q) use ($userId, $sessionId, $request) {
                        if ($userId) {
                            $q->where('user_id', $userId)
                                ->where('item_id', null)
                                ->where('status', 'active');
                        } else {
                            $q->where('session_id', $sessionId)
                                ->where('item_id', null)
                                ->where('status', 'active');
                        }
                    });
                },
                'wishlistItems as in_wishlist' => function ($query) use ($sessionId) {
                    $query->where(function ($q) use ($sessionId) {
                        $q->where('session_id', $sessionId)->where('status', 'active');
                    });
                },
            ])
            ->where('category_id', 2)
            ->whereHas('metadataValues', function ($query) {
                $query->where('field_name', 'available_for_complimentary_package')
                    ->whereIn('field_value', ['all', '0']);
            })
            ->where('is_active', 1)
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
            $chapter->otherDoc = $chapter->chapterListing
                ->whereIn('file_extension', ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'png', 'jpeg', 'svg'])
                ->first();
        });
        $shareButtons = Share::page(url('about-talent-skill-course/' . $course->slug), $course->course_name)
            ->facebook()
            ->twitter()
            ->linkedin()
            ->whatsapp();

        return view('frontend.about-non-academic-course', compact('course', 'nonAcadCourses', 'courseChapters', 'shareButtons'));
    }

    // Academic and Talent & Skill Course Listing
    public function showCoursesListing($category_slug, Request $request)
    {
        $category                = Category::where('status', 1)->where('slug', $category_slug)->first();
        $subCategories           = collect();
        $acadCoursesList         = collect();
        $nonAcadCoursesList      = collect();
        $nonAcadCoursesListMusic = collect();
        $selectedSubCategory     = $request->input('sub_category_id') ?? null;
        $userId                  = auth()->check() ? auth()->id() : null;
        $sessionId               = session('user_session_id');

        if ($category) {
            if ($category->id == 1) {


                $selectedClass     = $request->input('class_id') ?? null;
                $selectedSubject     = $request->input('subject_id') ?? null;
                $seriesData = FrontendCoursesView::get();

                // Extract all unique series IDs and class IDs
                $seriesIds = $seriesData->pluck('series_id')->unique()->toArray();
                $classesIds = $seriesData->flatMap(function ($item) {
                    return explode(',', $item->classes_ids);
                })->unique()->toArray();
                $classes = Classes::whereIn('id', $classesIds)->where('is_active', 1)->get(['id', 'name']);
                $subjects = Subject::where('is_active', 1)->get(['id', 'name']);
                $acadCoursesList = Course::with(['metadataValues.subjectInfo', 'category', 'subCategory'])
                    ->where('courses.category_id', 1)
                    ->where('courses.is_active', 1)

                    // ✅ Must have matching series
                    ->whereHas('metadataValues', function ($query) use ($seriesIds) {
                        $query->where('field_name', 'series')
                            ->whereIn('field_value', $seriesIds);
                    })

                    // ✅ And must also have matching class
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
                    ]);

                // ✅ Optional: Filter by subcategory
                if ($selectedSubCategory) {
                    $acadCoursesList->where('courses.sub_category_id', $selectedSubCategory);
                }
                if ($selectedClass) {
                    $acadCoursesList->whereHas('metadataValues', function ($query) use ($selectedClass) {
                        $query->where('field_name', 'class')
                            ->where('field_value', $selectedClass);
                    });
                }

                if ($selectedSubject) {
                    $acadCoursesList->whereHas('metadataValues', function ($query) use ($selectedSubject) {
                        $query->where('field_name', 'subject')
                            ->where('field_value', $selectedSubject);
                    });
                }


                $acadCoursesList = $acadCoursesList->orderBy('created_at', 'DESC')->get();

                // for recently added courses
                $recentCourses = $acadCoursesList
                    ->sortByDesc('created_at')
                    ->take(5);
                $recentCourseIds = $recentCourses->pluck('id')->toArray();

                // get popular courses
                $academicSubscriptions = SubscriptionPurchase::whereJsonLength('courses_json->academic_courses', '>', 0)
                    ->pluck('courses_json');

                $academicCourseCounts = [];
                foreach ($academicSubscriptions as $json) {
                    $courses = json_decode($json, true);
                    foreach ($courses['academic_courses'] ?? [] as $course) {
                        $id = $course['id'] ?? null;
                        if ($id) {
                            $academicCourseCounts[$id] = ($academicCourseCounts[$id] ?? 0) + 1;
                        }
                    }
                }

                arsort($academicCourseCounts);

                // Allow top 10 to ensure we can backfill to 5 if needed
                $topAcademicCourseIds = array_slice(array_keys($academicCourseCounts), 0, 10);

                // Filter out recent
                $filteredTopAcademicCourseIds = array_values(array_filter($topAcademicCourseIds, function ($id) use ($recentCourseIds) {
                    return !in_array($id, $recentCourseIds);
                }));

                // Get course models for popular
                $popularCourses = $acadCoursesList->filter(function ($course) use ($filteredTopAcademicCourseIds) {
                    return in_array($course->id, $filteredTopAcademicCourseIds);
                });

                // Get remaining courses
                $excludedIds = array_merge($recentCourseIds, $filteredTopAcademicCourseIds);
                $remainingCourses = $acadCoursesList->filter(function ($course) use ($excludedIds) {
                    return !in_array($course->id, $excludedIds);
                });

                // Merge final course list
                $acadCoursesList = $recentCourses
                    ->merge($popularCourses)
                    ->merge($remainingCourses);


                // Get subcategories for filter
                $subCategories = getAcademicCategoriesWithChild()->filter(function ($category) {
                    return in_array($category->slug, [
                        'academic-digital-content',
                        'academic_activities'
                    ]);
                });

                //V for academic filter view
                return view('frontend.academic-courses-list', compact('acadCoursesList', 'recentCourseIds', 'filteredTopAcademicCourseIds', 'category', 'subCategories', 'selectedSubCategory', 'classes', 'subjects'));
            } elseif ($category->id == 2) {
                $nonAcadCoursesList = Course::with(['metadataValues.subjectInfo', 'category', 'subCategory', 'totalChapters'])
                    ->withCount([
                        'cartItems as in_cart'         => function ($query) use ($userId, $sessionId, $request) {
                            $query->where(function ($q) use ($userId, $sessionId, $request) {
                                if ($userId) {
                                    $q->where('user_id', $userId)
                                        ->where('item_id', null)
                                        ->where('status', 'active');
                                } else {
                                    $q->where('session_id', $sessionId)
                                        ->where('item_id', null)
                                        ->where('status', 'active');
                                }
                            });
                        },
                        'wishlistItems as in_wishlist' => function ($query) use ($sessionId) {
                            $query->where(function ($q) use ($sessionId) {
                                $q->where('session_id', $sessionId)->where('status', 'active');
                            });
                        },
                    ])
                    ->where('courses.category_id', 2)
                    ->whereHas('metadataValues', function ($query) {
                        $query->where('field_name', 'available_for_complimentary_package')
                            ->whereIn('field_value', ['all', '0']);
                    })
                    ->where('courses.is_active', 1);

                // V Apply filter if selected for non Academy (subcategory )
                if ($selectedSubCategory) {
                    $nonAcadCoursesList = $nonAcadCoursesList->where('courses.sub_category_id', $selectedSubCategory);
                }

                $nonAcadCoursesList = $nonAcadCoursesList->orderBy('created_at', 'DESC')->get();

                $recentCourses = $nonAcadCoursesList
                    ->sortByDesc('created_at')
                    ->take(5);
                $recentCourseIds = $recentCourses->pluck('id')->toArray();

                $nonAcademicSubscriptions = SubscriptionPurchase::whereJsonLength('courses_json->non_academic_courses', '>', 0)
                    ->pluck('courses_json');

                $nonAcademicCourseCounts = [];
                foreach ($nonAcademicSubscriptions as $json) {
                    $courses = json_decode($json, true);
                    foreach ($courses['non_academic_courses'] ?? [] as $course) {
                        $id = $course['id'] ?? null;
                        if ($id) {
                            $nonAcademicCourseCounts[$id] = ($nonAcademicCourseCounts[$id] ?? 0) + 1;
                        }
                    }
                }
                arsort($nonAcademicCourseCounts);
                $topNonAcademicCourseIds = array_slice(array_keys($nonAcademicCourseCounts), 0, 10); // take 10 for fallback

                // Exclude recent course IDs from popular course IDs
                $filteredTopNonAcademicCourseIds = array_values(array_filter($topNonAcademicCourseIds, function ($id) use ($recentCourseIds) {
                    return !in_array($id, $recentCourseIds);
                }));

                $popularCourses = $nonAcadCoursesList->filter(function ($course) use ($filteredTopNonAcademicCourseIds) {
                    return in_array($course->id, $filteredTopNonAcademicCourseIds);
                });

                // Remaining courses (exclude recent + popular)
                $excludedIds = array_merge($recentCourseIds, $filteredTopNonAcademicCourseIds);
                $remainingCourses = $nonAcadCoursesList->filter(function ($course) use ($excludedIds) {
                    return !in_array($course->id, $excludedIds);
                });

                // Merge final course list exactly: first 5 recent, then 5 popular, then the rest
                $nonAcadCoursesList = $recentCourses
                    ->merge($popularCourses)
                    ->merge($remainingCourses);

                $subCategories = getCategoriesWithChild();
                // dd($selectedSubCategory); exit();

                return view('frontend.nonacademic-courses-list', compact('nonAcadCoursesList', 'recentCourseIds', 'filteredTopNonAcademicCourseIds', 'category', 'subCategories', 'selectedSubCategory'));
            }
        }

        return view('frontend.category-list-not-found');
    }

    public function blogs(Request $request): View
    {
        $page                = $request->get('page', 1);
        $this->data['blogs'] = Blog::where('status', 'published')->with('categories')->orderBy('published_at', 'DESC')->withCount('views')->paginate(6);
        foreach ($this->data['blogs'] as $blog) {
            $blog->formatted_date = Carbon::parse($blog->published_at)->format('d M Y');
        }
        $this->data['popular_blogs'] = Blog::where('status', 'published')->withCount('views')
            ->orderBy('views_count', 'desc')
            ->take(3)
            ->get();

        return view('frontend.blogs', $this->data)->with('i', ($page - 1) * 6);
    }

    public function blogDetails($slug)
    {
        $this->data['blog']  = Blog::where('slug', $slug)->withCount('views')->first();
        $this->data['image'] = MediaFiles::where('tbl_id', $this->data['blog']->id)->first();
        if (! $this->data['blog']) {
            return redirect()->route('blog.index')->with('error', 'Blog not found.');
        }

        $this->data['blog']->formatted_date = Carbon::parse($this->data['blog']->published_at)->format('d M Y');

        BlogView::create([
            'blog_id'   => $this->data['blog']->id,
            'user_ip'   => request()->ip(),
            'viewed_at' => now(),
        ]);

        $this->data['popular_blogs'] = Blog::where('status', 'published')->withCount('views')
            ->orderBy('views_count', 'desc')
            ->take(4)
            ->get();

        return view('frontend.blog-detail', $this->data);
    }

    public function contactUs()
    {
        $this->data['getSetting'] = Setting::pluck('field_value', 'field_name')->toArray();
        $this->data['getFaqs']    = Faq::where('is_active', 1)->orderBy('sort_order', 'ASC')->get();

        return view('frontend.contact-us', $this->data);
    }
    public function refreshCaptcha()
    {
        return response()->json(['captcha' => captcha_img()]);
    }
    public function contactUsSave(Request $request)
    {
        // Validate request data
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|max:255',
            'message'   => 'required|string',
            'mobile_no' => 'required|string|regex:/^\d{10}$/',
            'captcha' => 'required|captcha',
            // 'g-recaptcha-response' => 'required' // Ensure reCAPTCHA response is present
        ], ['captcha.captcha' => 'Invalid captcha code.']);
        // // Validate reCAPTCHA
        // $captchaResponse = $request->input('g-recaptcha-response');
        // if (!$this->validateRecaptcha($captchaResponse)) {
        //     return redirect()->back()->withErrors(['captcha' => 'reCAPTCHA verification failed. Please try again.']);
        // }
        ContactInquiry::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'mobile_no' => $request->mobile_no,
            'subject'   => $request->subject,
            'message'   => $request->message,
            'ip'        => $request->ip(),
        ]);

        return redirect()->back()->with('success', config('constants.FLASH_CONTACT_US_1'));
    }

    private function validateRecaptcha($captchaResponse)
    {
        $secretKey = env('CAPTCHA_SECRET_KEY');
        $url       = "https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$captchaResponse}";
        $response  = json_decode(file_get_contents($url));

        return $response->success ?? false;
    }
    public function privacypolicy()
    {
        $this->data['policy'] = CmsPage::where('slug', 'privacy-policy')->first();
        // return $this->data;
        return view('frontend.privacy-policy', $this->data);
    }
    public function termsCondition()
    {
        $this->data['terms'] = CmsPage::where('slug', 'terms-and-conditions')->first();
        // return $this->data;
        return view('frontend.terms-and-conditions', $this->data);
    }
    // public function socialMediaLinks()
    // {
    //     $this->data['links'] =  Setting::pluck('field_value', 'field_name')->toArray();
    //     // return $this->data;
    //     return view('frontend.layouts.master', $this->data);
    // }
    public function ErrorLog()
    {
        $this->data['errorLog'] =  ErrorLog::pluck('field_value', 'field_name')->toArray();
        // return $this->data;
        return view('frontend.layouts.master', $this->data);
    }
    public function ourOfferings()
    {
        $this->data['offerings'] =  AdditionalDataRow::where('type', 'our_offerings')->orderBy('sort_order', 'asc')->paginate(12);;
        return view('frontend.our-offerings', $this->data);
    }
    public function downloadApp(Request $request)
    {
        $this->data['setting'] = Setting::pluck('field_value', 'field_name')->toArray();
        return view('frontend.download-app', $this->data);
    }
}
