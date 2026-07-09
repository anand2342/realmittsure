<?php $__env->startSection('meta'); ?>
    <?php
        $courseTitle = $course->course_name ?? 'Course Title';
        $courseDescription =
            $course->metadataValues->where('field_name', 'description')->value('field_value') ?? 'Explore this course.';
        $courseUrl = request()->fullUrl();

        $thumbnailImage = $course->metadataValues->where('field_name', 'thumbnail_image')->value('field_value');
        $bookCoverImage = $course->metadataValues->where('field_name', 'book_cover_image')->value('field_value');
        $courseImage = $thumbnailImage
            ? Storage::url($thumbnailImage)
            : ($bookCoverImage
                ? Storage::url($bookCoverImage)
                : asset('frontend/images/mittlearn-logo.svg'));
    ?>
    <meta name="description" content="<?php echo e($courseDescription); ?>">
    <!-- Open Graph Meta -->
    <meta property="og:title" content="<?php echo e($courseTitle); ?>">
    <meta property="og:description" content="<?php echo e($courseDescription); ?>">
    <meta property="og:image" content="<?php echo e(asset($courseImage)); ?>">
    <meta property="og:url" content="<?php echo e($courseUrl); ?>">
    <meta property="og:type" content="website">

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php echo e($courseTitle); ?>">
    <meta name="twitter:description" content="<?php echo e($courseDescription); ?>">
    <meta name="twitter:image" content="<?php echo e(asset($courseImage)); ?>">
    <meta name="twitter:url" content="<?php echo e($courseUrl); ?>">
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
    <div>
        <div class="aboutMain">
            <div class="sliderAbout">
                <div class="item">
                    <img src="<?php echo e(asset('frontend/images/sliderOne.png')); ?>" alt="">
                </div>
                <div class="item">
                    <img src="<?php echo e(asset('frontend/images/sliderTwo.png')); ?>" alt="">
                </div>

            </div>
            <div class="container">
                <div class="bannerTxt">
                    <div class="sliderTxt">
                        <h4>About Academic Course</h4>
                        <p>A revolutionary digital platform in field of education, committed on social empowerment and
                            enhancing learning capabilities</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="aboutCourses">
            <div class="container">
                <?php
                    $classMeta = $course->metadataValues->firstWhere('field_name', 'class');
                    $subjectMeta = $course->metadataValues->firstWhere('field_name', 'subject');
                ?>
                <div class="row reverseRow">
                    <div class="col-xl-9 col-lg-7">
                        <nav aria-label="breadcrumb ">
                            <ol class="breadcrumb mb-4">
                                <li class="breadcrumb-item"><a href="<?php echo e(route('/')); ?>">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Academic</li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    <?php echo e($classMeta && $classMeta->classInfo ? $classMeta->classInfo->name : ''); ?>

                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    <?php echo e($subjectMeta && $subjectMeta->subjectInfo ? $subjectMeta->subjectInfo->name : ''); ?>

                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    <?php echo e($course->course_name ?? 'N/A'); ?></li>
                            </ol>
                        </nav>
                        <div class="d-flex justify-content-between align-items-center flex-wrap mb-3">
                            <!-- Tabs -->
                            <ul class="nav nav-tabs ViewTabs mb-0">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#curriculumTab"
                                        type="button">Course Content</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#overviewTab"
                                        type="button">Overview</button>
                                </li>
                            </ul>

                            <!-- Filter Form -->
                            <div class="viewCategory">
                                <form method="GET" action="<?php echo e(request()->url()); ?>"
                                    class="d-flex flex-wrap gap-2 align-items-center mb-0">
                                    <div class="d-flex flex-column">
                                        <label class="small mb-1">Content Language</label>
                                        <select class="form-control form-select" name="language"
                                            onchange="this.form.submit()" style="min-width: 150px;">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = config('constants.CONTENT_LANGUAGE'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($key); ?>"
                                                    <?php echo e(request('language') == $key ? 'selected' : 'bilingual'); ?>>
                                                    <?php echo e($label); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </select>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="tab-content">
                            <div class="tab-pane fade  show active" id="curriculumTab">
                                <div class="urriculumMain">
                                    <div class="headingsections">
                                        <span><?php echo e($courseChapters->count()); ?> lessons</span>
                                        
                                    </div>

                                    <div class="accordion curriculumAcrdn" id="accordionclm">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $courseChapters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $chapter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $uniqueId = 'clmTwo_' . $index; // Unique ID for accordion
                                                $video = $chapter->filtered_video; // Get the first video from the chapter
                                                $otherDoc = $chapter->otherDoc; // Get the first video from the chapter
                                                $activityLink = \App\Models\MediaFiles::where(
                                                    'type',
                                                    'activity_worksheet_link',
                                                )
                                                    ->where('tbl_id', $chapter->id)
                                                    ->get();
                                            ?>

                                            <div class="accordion-item">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#<?php echo e($uniqueId); ?>"
                                                    aria-expanded="false" aria-controls="<?php echo e($uniqueId); ?>">
                                                    Lesson <?php echo e($chapter->sort_order); ?>

                                                    <span></span>
                                                </button>

                                                <div id="<?php echo e($uniqueId); ?>" class="accordion-collapse collapse"
                                                    data-bs-parent="#accordionclm">
                                                    <div class="accordion-body">
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($video): ?>
                                                            <?php
                                                                $canViewAllVideos =
                                                                    auth()->check() && auth()->user()->is_admin == 1;
                                                            ?>

                                                            <div class="accordianInner mx-3">
                                                                <button type="button"
                                                                    class="border-0 bg-transparent p-0 text-start w-100 d-flex align-items-center gap-3"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="<?php echo e($canViewAllVideos || $index < 3 ? '#coursePreview-' . $video->id : '#coursePurchage'); ?>">

                                                                    <span><strong><?php echo e($chapter->chapter_name); ?></strong></span>

                                                                    <span class="play-lock-btn">
                                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($canViewAllVideos || $index < 3): ?>
                                                                            <i class="bi bi-play-fill fs-3"></i>
                                                                        <?php else: ?>
                                                                            <i class="bi bi-lock-fill fs-3"></i>
                                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                    </span>
                                                                </button>

                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($canViewAllVideos || $index < 3): ?>
                                                                    <div class="modal fade previewVdo"
                                                                        id="coursePreview-<?php echo e($video->id); ?>"
                                                                        tabindex="-1"
                                                                        aria-labelledby="coursePreviewLabel-<?php echo e($video->id); ?>"
                                                                        aria-hidden="true">
                                                                        <div class="modal-dialog modal-dialog-centered">
                                                                            <div class="modal-content rounded-0 border-0"
                                                                                style="background: rgba(0, 0, 0, .5); color: #fff;">

                                                                                <div class="modal-header border-0">
                                                                                    <h1 class="modal-title fs-5 fw-normal"
                                                                                        id="coursePreviewLabel-<?php echo e($video->id); ?>">
                                                                                        Course Preview
                                                                                    </h1>
                                                                                    <button type="button"
                                                                                        class="btn-close"
                                                                                        data-bs-dismiss="modal"
                                                                                        aria-label="Close"></button>
                                                                                </div>

                                                                                <div class="modal-body p-0">
                                                                                    <p class="py-2 px-3 fs-8 mb-0">
                                                                                        <?php echo e($chapter->chapter_name); ?>

                                                                                    </p>
                                                                                    <video width="100%" height="240"
                                                                                        controls controlsList="nodownload"
                                                                                        oncontextmenu="return false">
                                                                                        <source
                                                                                            src="<?php echo e(Storage::url('uploads/course_chapter_files/' . $video->attachment_file)); ?>"
                                                                                            type="video/mp4">
                                                                                    </video>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                            </div>
                                                        <?php elseif($otherDoc): ?>
                                                            <div class="accordianInner mx-3">
                                                                <a target="_blank"
                                                                    href="<?php echo e(Storage::url('uploads/course_chapter_files/' . $otherDoc->attachment_file)); ?>"
                                                                    class="d-flex align-items-center justify-content-between w-100 text-decoration-none text-dark py-2">
                                                                    <span><strong><?php echo e($chapter->chapter_name); ?></strong></span>
                                                                    <span class="play-lock-btn">
                                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($index < 3): ?>
                                                                            <i
                                                                                class="bi bi-file-earmark-text-fill fs-3"></i>
                                                                        <?php else: ?>
                                                                            <i class="bi bi-lock-fill fs-3"></i>
                                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                    </span>
                                                                </a>
                                                            </div>
                                                        <?php elseif(!empty($activityLink)): ?>
                                                            <div class="game-links">
                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $activityLink; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <a href="<?php echo e($item->link_url); ?>" target="_blank"
                                                                        class="game-card d-block mb-3 text-decoration-none">
                                                                        <div
                                                                            class="game-content rounded-2 position-relative overflow-hidden">
                                                                            <div class="d-flex align-items-center">
                                                                                <div class="game-icon me-3"
                                                                                    style="background-color: rgba(0, 190, 85, 0.1);">
                                                                                    <i class="bi bi-joystick"
                                                                                        style="color: #00BE55;"></i>
                                                                                </div>
                                                                                <div class="flex-grow-1">
                                                                                    <h5 class="game-title mb-0"
                                                                                        style="color: #000; font-size: 12px">
                                                                                        <?php echo e($item->file_name ?? $item->original_name); ?>

                                                                                    </h5>
                                                                                </div>
                                                                                <div
                                                                                    class="game-cta d-flex align-items-center">

                                                                                    <div class="game-arrow"
                                                                                        style="color: #00438C;">
                                                                                        <i
                                                                                            class="bi bi-arrow-right-short"></i>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </a>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                            </div>

                                                            <style>
                                                                /* Main game card styling */
                                                                .game-card {
                                                                    transition: all 0.3s ease;
                                                                }

                                                                .game-content {
                                                                    background-color: white;
                                                                    border: 1px solid #e0e0e0;
                                                                    transition: all 0.3s ease;
                                                                }

                                                                .game-card:hover .game-content {
                                                                    transform: translateY(-3px);
                                                                    box-shadow: 0 5px 15px rgba(0, 67, 140, 0.1);
                                                                    border-color: #00438C;
                                                                }

                                                                /* Game icon styling */
                                                                .game-icon {
                                                                    width: 48px;
                                                                    height: 48px;
                                                                    border-radius: 8px;
                                                                    display: flex;
                                                                    align-items: center;
                                                                    justify-content: center;
                                                                    font-size: 1.5rem;
                                                                }

                                                                /* Title styling */
                                                                .game-title {
                                                                    font-weight: 600;
                                                                    transition: color 0.3s ease;
                                                                }

                                                                /* CTA animation */
                                                                .cta-text {
                                                                    opacity: 0;
                                                                    transform: translateX(-10px);
                                                                    transition: all 0.3s ease;
                                                                    font-weight: 500;
                                                                }

                                                                .game-arrow {
                                                                    font-size: 1.5rem;
                                                                    transition: all 0.3s ease;
                                                                }

                                                                .game-card:hover .cta-text {
                                                                    opacity: 1;
                                                                    transform: translateX(0);
                                                                    color: #00BE55;
                                                                }

                                                                .game-card:hover .game-arrow {
                                                                    transform: translateX(5px);
                                                                    color: #00BE55;
                                                                }

                                                                /* Hover effect */
                                                                .game-hover-effect {
                                                                    position: absolute;
                                                                    bottom: 0;
                                                                    left: 0;
                                                                    width: 0;
                                                                    height: 3px;
                                                                    background-color: #00BE55;
                                                                    transition: width 0.3s ease;
                                                                }

                                                                .game-card:hover .game-hover-effect {
                                                                    width: 100%;
                                                                }
                                                            </style>
                                                        <?php else: ?>
                                                            <p class="text-muted">Oops! No videos are available for
                                                                this
                                                                lesson. Try exploring another lesson for more exciting
                                                                content!</p>
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>

                                </div>
                            </div>
                            <div class="tab-pane fade" id="overviewTab">
                                <div class="aboutLeft position-relative">
                                    <div class="section-heading m-0 text-start pb-2">
                                        <h2 class="text-black"><span class="greenBorder"></span>
                                            About Course</h2>
                                    </div>
                                    <?php
                                        $description = $course->metadataValues
                                            ->where('field_name', 'description')
                                            ->value('field_value');
                                    ?>
                                    <p><?php echo e($course->course_name ?? 'N/A'); ?>, <?php echo $description; ?></p>

                                    <div class="lottieCourse">
                                        <lottie-player src="<?php echo e(asset('frontend/images/wave-lines.json')); ?>"
                                            autoplay="" loop="" style="width: 180px; height: 70px;"
                                            background="transparent"></lottie-player>
                                    </div>
                                </div>

                                

                            </div>


                        </div>

                        <div class="needOnly">
                            <div class="container">
                                <div class="section-heading m-0 text-start pb-2">
                                    <h2 class="text-black"><span class="greenBorder"></span>
                                        You only need</h2>
                                </div>
                                <div class="row">
                                    <div class="col-xl-3 col-md-6 mb-3">
                                        <div class="needBox h-100">
                                            <figure>
                                                <img src="<?php echo e(asset('frontend/images/desktop-icon.svg')); ?>" alt=""
                                                    width="48">
                                            </figure>
                                            <span>Desktop/Laptop/Mobile access for an hour</span>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6 mb-3">
                                        <div class="needBox h-100">
                                            <figure>
                                                <img src="<?php echo e(asset('frontend/images/broadband-icon.svg')); ?>"
                                                    alt="" width="60">
                                            </figure>
                                            <span>Broadband internet connection</span>
                                        </div>
                                    </div>
                                    <div class="col-xl-3 col-md-6 mb-3">
                                        <div class="needBox h-100">
                                            <figure>
                                                <img src="<?php echo e(asset('frontend/images/headset-icon.svg')); ?>" alt=""
                                                    width="55">
                                            </figure>
                                            <span>Headset(Not Mandatory)</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-5">
                        <div class="cartsImag">
                            <?php
                                if ($course->discount_type == 'flat') {
                                    $finalPrice = $course->price - $course->discount_value;
                                } elseif ($course->discount_type == 'percentage') {
                                    $finalPrice = $course->price - ($course->discount_value / 100) * $course->price;
                                } else {
                                    $finalPrice = $course->price;
                                }
                                $bookCoverImage = $course->metadataValues
                                    ->Where('field_name', 'book_cover_image')
                                    ->value('field_value');
                                $thumbnailImage = $course->metadataValues
                                    ->Where('field_name', 'thumbnail_image')
                                    ->value('field_value');
                            ?>
                            
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($thumbnailImage || $bookCoverImage): ?>
                                <img src="<?php echo e($thumbnailImage ? Storage::url($thumbnailImage) : Storage::url($bookCoverImage)); ?>"
                                    alt="course image">
                            <?php else: ?>
                                <img src="<?php echo e(asset('frontend/images/default-image.jpg')); ?>" alt="Default Image">
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            
                            
                            <div class="cartContent">
                                <span>


                                    <b class="lineThr mb-my-3 me-2"><?php echo e(number_format($course->price)); ?></b>
                                    ₹<?php echo e(number_format($finalPrice)); ?>


                                </span>
                                
                                

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($course->in_cart == 0): ?>
                                    <a href="<?php echo e(route('course.add-to-cart', $course->id)); ?>"
                                        class="btn btn-primary w-100 rounded-0 fw-semibold my-3">Add to cart</a>
                                <?php else: ?>
                                    <a href="<?php echo e(route('course.go-to-cart')); ?>"
                                        class="btn btn-primary w-100 rounded-0 fw-semibold my-3">Go to cart</a>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <div class="shareCourse">
                                    <span class="fw-semibold d-block mb-2">Share this course</span>

                                    <div class="form-group position-relative">
                                        <input type="text" class="form-control" id="shareUrl"
                                            value="<?php echo e(url()->current()); ?>" readonly>
                                        <button onclick="copyToClipboard()" class="btn btnCopy">Copy</button>
                                    </div>
                                    <span id="copyMessage" style="display: none; color: green;">URL copied!</span>

                                    <ul class="socialCart m-3">
                                        <?php echo $shareButtons; ?>

                                    </ul>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="">
            <div class="container">
                <div class="section-heading m-0 text-start pb-2">
                    <h2 class="text-black"><span class="greenBorder"></span>
                        Relevant Academic Courses</h2>
                </div>
                <div class="row px-md-1">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $acadCourses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-6 col-lg-4 col-xl-3 px-md-2 mb-3">
                            <div class="coursesBox">
                                <figure class="position-relative">
                                    <?php
                                        $bookCoverImage = $course->metadataValues
                                            ->Where('field_name', 'book_cover_image')
                                            ->value('field_value');
                                        $thumbnailImage = $course->metadataValues
                                            ->Where('field_name', 'thumbnail_image')
                                            ->value('field_value');
                                        $originalPrice = $course->price;
                                        // Discount calculation
                                        if ($course->discount_type == 'percent') {
                                            // Calculate the price after discount for percent type
                                            $discountedPrice =
                                                $originalPrice - $originalPrice * ($course->discount_value / 100);
                                        } elseif ($course->discount_type == 'flat') {
                                            // Calculate the price after discount for flat type
                                            $discountedPrice = $originalPrice - $course->discount_value;
                                        } else {
                                            // If no discount type, keep the original price
                                            $discountedPrice = $originalPrice;
                                        }
                                    ?>
                                    <a href="<?php echo e(route('about-acadcourse', $course->slug)); ?>">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($thumbnailImage || $bookCoverImage): ?>
                                            <img src="<?php echo e($thumbnailImage ? Storage::url($thumbnailImage) : Storage::url($bookCoverImage)); ?>"
                                                alt="course image">
                                        <?php else: ?>
                                            <img src="<?php echo e(asset('frontend/images/default-image.jpg')); ?>"
                                                alt="Default Image">
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </a>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($course->in_wishlist == 0): ?>
                                        <button type="button" class=" bg-transparent border-0 p-0 wishlistButton"
                                            data-course-id="<?php echo e($course->id); ?>" data-item-id="<?php echo e($course->id); ?>"
                                            data-item-type="academic_course">
                                            <img src="<?php echo e(asset('frontend/images/heart-icon.svg')); ?>"
                                                class="wishlist-icon-<?php echo e($course->id); ?>" alt="Wishlist Icon"
                                                width="18">
                                        </button>
                                    <?php else: ?>
                                        <button type="button" class=" bg-transparent border-0 p-0 wishlistButton"
                                            data-course-id="<?php echo e($course->id); ?>" data-item-id="<?php echo e($course->id); ?>"
                                            data-item-type="academic_course">
                                            <img src="<?php echo e(asset('frontend/images/red-heart-icon.svg')); ?>"
                                                class="wishlist-icon-<?php echo e($course->id); ?>" alt="Wishlist Icon"
                                                width="18">
                                        </button>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </figure>

                                <div class="d-flex gap-2 justify-content-between px-2">
                                    <div class="blogProfile">
                                        <strong>Mittlearn</strong>
                                    </div>
                                    
                                </div>

                                <a href="<?php echo e(route('about-acadcourse', $course->slug)); ?>">
                                    <h3 class="px-2">
                                        <?php echo e(limit_words($course->course_name ?? 'No Course Name', 3)); ?>

                                    </h3>
                                </a>
                                <div class="d-flex flex-wrap gap-3 courseInfo px-2">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $course->metadataValues ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $metadataValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($metadataValue->field_name == 'subject' && $metadataValue->subjectInfo): ?>
                                            <span>
                                                <img src="<?php echo e(asset('frontend/images/student-icon.svg')); ?>"
                                                    alt="mittlearn-image" width="14">
                                                Sub:
                                                <?php echo e($metadataValue->subjectInfo->name ?? ''); ?>

                                            </span>
                                        <?php elseif($metadataValue->field_name == 'class' && $metadataValue->classInfo): ?>
                                            <span>
                                                <img src="<?php echo e(asset('frontend/images/student-icon.svg')); ?>"
                                                    alt="mittlearn-image" width="14">
                                                <?php echo e($metadataValue->classInfo->name ?? ''); ?>

                                            </span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <hr>
                                <div class="d-flex gap-2 align-items-center pb-2 justify-content-between px-2">
                                    <div class="pricetag">
                                        <span>₹ <?php echo e(number_format($course->price)); ?></span>
                                        <?php
                                            if ($course->discount_type == 'flat') {
                                                $finalPrice = $course->price - $course->discount_value;
                                            } elseif ($course->discount_type == 'percent') {
                                                $finalPrice =
                                                    $course->price - ($course->discount_value / 100) * $course->price;
                                            } else {
                                                $finalPrice = $course->price;
                                            }
                                        ?>
                                        ₹ <?php echo e(number_format($finalPrice)); ?>

                                    </div>
                                    <a href="<?php echo e(route('about-acadcourse', $course->slug)); ?>"
                                        class="btn btn-primary-gradient rounded-1">Know more</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
        </div>





    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script>
        $('.sliderAbout').slick({
            dots: false,
            infinite: false,
            speed: 300,
            slidesToShow: 1,
            autoplay: true,
            arrows: false,
            slidesToScroll: 1,
            responsive: [{
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        infinite: false,
                        dots: false
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1
                    }
                }

            ]
        });
        $('.studentSaySlider').slick({
            slidesToShow: 2,
            slidesToScroll: 1,
            arrows: true,
            infinite: true,
            arrows: true,
            prevArrow: '<button type="button" data-role="none" class="slick-prev" aria-label="Previous" tabindex="0" role="button"><i class="bi bi-arrow-left"></i></button>',
            nextArrow: '<button type="button" data-role="none" class="slick-next" aria-label="Next" tabindex="0" role="button"><i class="bi bi-arrow-right"></i></button>',
            responsive: [{
                    breakpoint: 991,
                },
                {
                    breakpoint: 767,
                    settings: {
                        slidesToShow: 1,
                    }
                },
                {
                    breakpoint: 480,
                    settings: {
                        slidesToShow: 1,
                    }
                }
                // You can unslick at a given breakpoint now by adding:
                // settings: "unslick"
                // instead of a settings object
            ]
        });

        $('.activitieSlider').slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: true,
            infinite: true,
            speed: 300,
            centerMode: true,
            arrows: true,
            centerPadding: '550px',
            prevArrow: '<button type="button" data-role="none" class="slick-prev" aria-label="Previous" tabindex="0" role="button"><i class="bi bi-arrow-left"></i></button>',
            nextArrow: '<button type="button" data-role="none" class="slick-next" aria-label="Next" tabindex="0" role="button"><i class="bi bi-arrow-right"></i></button>',
            responsive: [{
                    breakpoint: 1200,
                    settings: {
                        centerPadding: '450px',
                    }
                },
                {
                    breakpoint: 991,
                    settings: {
                        centerPadding: '250px',
                    }
                },
                {
                    breakpoint: 767,
                    settings: {
                        centerPadding: '0',
                    }
                },

                // You can unslick at a given breakpoint now by adding:
                // settings: "unslick"
                // instead of a settings object
            ]
        });
        const horizontalAccordions = $(".accordion.programAccordion");

        horizontalAccordions.each((index, element) => {
            const accordion = $(element);
            const collapse = accordion.find(".collapse");
            const bodies = collapse.find("> *");
            accordion.height(accordion.height());
            bodies.width(bodies.eq(0).width());
            collapse.not(".show").each((index, element) => {
                $(element).parent().find("[data-bs-toggle='collapse']").addClass("collddapsed");
            });
        });
    </script>

    <!-- V Added For Copy URL Start --------------->
    <script type="text/javascript">
        function copyToClipboard() {
            var copyText = document.getElementById("shareUrl");
            copyText.select();
            document.execCommand("copy");

            var copyMessage = document.getElementById("copyMessage");
            copyMessage.style.display = "inline";

            setTimeout(() => {
                copyMessage.style.display = "none";
            }, 2000);
        }
        document.addEventListener("DOMContentLoaded", function() {
            document.querySelectorAll(".socialCart a").forEach(function(link) {
                link.addEventListener("click", function(event) {
                    event.preventDefault();
                    var url = this.href;
                    window.open(url, '_blank', 'width=800,height=600,top=100,left=200');
                });
            });
        });
    </script>
    <!-- V Added For Copy URL End --------------->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/frontend/about-academic-course.blade.php ENDPATH**/ ?>