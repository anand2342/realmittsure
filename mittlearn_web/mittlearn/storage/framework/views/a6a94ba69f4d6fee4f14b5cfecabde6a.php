<?php $__env->startSection('content'); ?>
    <div>
        <input type="hidden" id="session_id" name="session_id" value="">
        <section class="frontend-main-section">
            <div class="mainBanner homeBanner" id="homeBannerAcademic" style="display: none;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
                    <div class="alert alert-success small" style="margin-left: 105px;"><?php echo e(session('success')); ?></div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <div class="container">
                    <div class="bannerSlide bannerSlide1">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($firstBannerAdditionalAcad)): ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $firstBannerAdditionalAcad; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div>
                                    <div class="d-flex flex-wrap">
                                        <div class="bannerTxt">
                                            <h1><?php echo e($firstBanner->heading ?? ''); ?>

                                                <b> <?php echo e($data->title ?? ''); ?>

                                                </b>
                                            </h1>
                                        </div>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($data->image)): ?>
                                            <div class="bannerImages">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($data->description)): ?>
                                                    <a href="<?php echo e($data->description); ?>" target="_blank"> <img
                                                            src="<?php echo e(Storage::url('uploads/website-pages/academic/' . $data->image)); ?>"
                                                            alt="mittlearn-image"></a>
                                                <?php else: ?>
                                                    <img src="<?php echo e(Storage::url('uploads/website-pages/academic/' . $data->image)); ?>"
                                                        alt="mittlearn-image">
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <img src="<?php echo e(asset('frontend/images/default-image.jpg')); ?>" alt="Default Image">
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="mainBanner homeBanner" id="homeBannerNonAcademic" style="display: none;">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
                    <div class="alert alert-success small" style="margin-left: 105px;"><?php echo e(session('success')); ?></div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <div class="container">
                    <div class="bannerSlide bannerSlideNonAcadmic">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($firstBannerAdditionalNonAcad)): ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $firstBannerAdditionalNonAcad; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div>
                                    <div class="d-flex flex-wrap">
                                        <div class="bannerTxt">
                                            <h1><?php echo e($firstBanner->heading ?? ''); ?>

                                                <b> <?php echo e($data->title ?? ''); ?>

                                                </b>
                                            </h1>
                                        </div>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($data->image)): ?>
                                            <div class="bannerImages">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($data->description)): ?>
                                                    <a href="<?php echo e($data->description); ?>" target="_blank"> <img
                                                            src="<?php echo e(Storage::url('uploads/website-pages/academic/' . $data->image)); ?>"
                                                            alt="mittlearn-image"></a>
                                                <?php else: ?>
                                                    <img src="<?php echo e(Storage::url('uploads/website-pages/academic/' . $data->image)); ?>"
                                                        alt="mittlearn-image">
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                        <?php else: ?>
                                            <img src="<?php echo e(asset('frontend/images/default-image.jpg')); ?>"
                                                alt="Default Image">
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="pageTab nav nav-tabs" id="myTab" role="tablist">
                
                <style>
                    .pageTab .tabLink {
                        width: 100% !important;
                    }
                </style>
                <button type="button"
                    class="tabLink nav-link active rounded-0  d-flex align-items-center justify-content-center flex-wrap"
                    id="nonacademic-tab" data-bs-toggle="tab" data-bs-target="#nonacademic-tab-pane" role="tab"
                    aria-controls="nonacademic-tab-pane"data-tab="nonacademic" aria-selected="false">Talent / Skill <span
                        class="explore-btn btn btn-sm mt-md-0 mt-1">Explore<lottie-player
                            src="<?php echo e(asset('frontend/images/right-blue.json')); ?>" loop="" autoplay=""
                            style="width: 20px;height: 20px; display:inline-block;vertical-align: middle;margin-left:3px"
                            background="transparent"></lottie-player></span>
                </button>
            </div>
            <div class="tab-content">
                <div class="tab-pane fade academic-page" id="academic-tab-pane" role="tabpanel"
                    aria-labelledby="academic-tab">
                    <div class="academic-page">
                        <div class="courseSection">
                            <div class="container">
                                <div class="section-heading">
                                    <h2><span class="greenBorder"></span>
                                        Comprehensive Academic Courses: Strengthen Your Child’s Foundation with Expert Video
                                        Lessons</h2>
                                    <p>Top-Rated Curriculum-Aligned Lessons for Every Grade<br>
                                        Explore our structured academic platform offering engaging video lectures from
                                        Pre-Primary to Class 10—covering every subject, designed to simplify concepts and
                                        boost performance.</p>

                                    <a href="<?php echo e(route('courses.listing', ['category_slug' => $academicCategory->slug])); ?>"
                                        class="btn btn-primary-gradient mt-3 fs-6">View All Courses</a>
                                </div>
                                <div class="mainCourseTab">
                                    <ul class="nav nav-tabs coursesTabs p-0 flex-wrap gap-1 gap-md-0">
                                        <li class="nav-item">
                                            <a class="nav-link active" href="#preSchool" data-bs-toggle="tab">
                                                <i> <img src="<?php echo e(asset('frontend/images/pre-school.svg')); ?>"> <img
                                                        class="hoverImg"
                                                        src="<?php echo e(asset('frontend/images/pre-school-white.svg')); ?>">
                                                </i> Pre - school</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#primarySchool" data-bs-toggle="tab"><i>
                                                    <img src="<?php echo e(asset('frontend/images/primary-school.svg')); ?>">
                                                    <img class="hoverImg"
                                                        src="<?php echo e(asset('frontend/images/primary-school-white.svg')); ?>">
                                                </i> Primary School</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" href="#middleSchool" data-bs-toggle="tab"><i>
                                                    <img src="<?php echo e(asset('frontend/images/middle-school.svg')); ?>">
                                                    <img class="hoverImg"
                                                        src="<?php echo e(asset('frontend/images/middle-school-white.svg')); ?>">
                                                </i> Middle School</a>
                                        </li>
                                        
                                    </ul>
                                </div>

                                <div class="tab-content">
                                    <?php
                                        $categories = [
                                            'preSchool' => $preSchool,
                                            'primarySchool' => $primarySchool,
                                            'middleSchool' => $middleSchool,
                                            'seniorSchool' => $seniorSchool,
                                        ];
                                    ?>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tabId => $classGroup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="tab-pane fade <?php echo e($loop->first ? 'show active' : ''); ?>"
                                            id="<?php echo e($tabId); ?>">
                                            <div class="row px-md-1">
                                                <?php
                                                    $noCoursesAvailable = true;
                                                ?>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($acadCourses) && $acadCourses->isNotEmpty()): ?>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $acadCourses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php
                                                            $hasClass = false;
                                                            if (isset($course->metadataValues)) {
                                                                foreach ($course->metadataValues as $metadataValue) {
                                                                    if (
                                                                        $metadataValue->field_name == 'class' &&
                                                                        isset($metadataValue->classInfo)
                                                                    ) {
                                                                        if (
                                                                            in_array(
                                                                                $metadataValue->classInfo->id,
                                                                                $classGroup,
                                                                            )
                                                                        ) {
                                                                            $hasClass = true;
                                                                            break;
                                                                        }
                                                                    }
                                                                }
                                                            }
                                                        ?>

                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasClass): ?>
                                                            <?php
                                                                $noCoursesAvailable = false;
                                                            ?>
                                                            <div class="col-md-6 col-lg-4 col-xl-3 px-md-2 mb-3">
                                                                <div class="coursesBox">
                                                                    <figure class="position-relative">
                                                                        <?php
                                                                            $bookCoverImage = $course->metadataValues
                                                                                ->Where(
                                                                                    'field_name',
                                                                                    'book_cover_image',
                                                                                )
                                                                                ->value('field_value');
                                                                            $thumbnailImage = $course->metadataValues
                                                                                ->Where('field_name', 'thumbnail_image')
                                                                                ->value('field_value');
                                                                            $originalPrice = $course->price;

                                                                            // Discount calculation
                                                                            if ($course->discount_type == 'percent') {
                                                                                // Calculate the price after discount for percent type
                                                                                $discountedPrice =
                                                                                    $originalPrice -
                                                                                    $originalPrice *
                                                                                        ($course->discount_value / 100);
                                                                            } elseif (
                                                                                $course->discount_type == 'flat'
                                                                            ) {
                                                                                // Calculate the price after discount for flat type
                                                                                $discountedPrice =
                                                                                    $originalPrice -
                                                                                    $course->discount_value;
                                                                            } else {
                                                                                // If no discount type, keep the original price
                                                                                $discountedPrice = $originalPrice;
                                                                            }
                                                                        ?>
                                                                        <a
                                                                            href="<?php echo e(route('about-acadcourse', $course->slug)); ?>">
                                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($thumbnailImage || $bookCoverImage): ?>
                                                                                <img src="<?php echo e($thumbnailImage ? Storage::url($thumbnailImage) : Storage::url($bookCoverImage)); ?>"
                                                                                    alt="course image">
                                                                            <?php else: ?>
                                                                                <img src="<?php echo e(asset('frontend/images/default-image.jpg')); ?>"
                                                                                    alt="Default Image">
                                                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                        </a>
                                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($course->in_wishlist == 0): ?>
                                                                            <button type="button"
                                                                                class=" bg-transparent border-0 p-0 wishlistButton"
                                                                                data-course-id="<?php echo e($course->id); ?>"
                                                                                data-item-id="<?php echo e($course->id); ?>"
                                                                                data-item-type="academic_course">
                                                                                <img src="<?php echo e(asset('frontend/images/heart-icon.svg')); ?>"
                                                                                    class="wishlist-icon-<?php echo e($course->id); ?>"
                                                                                    alt="Wishlist Icon" width="18">
                                                                            </button>
                                                                        <?php else: ?>
                                                                            <button type="button"
                                                                                class=" bg-transparent border-0 p-0 wishlistButton"
                                                                                data-course-id="<?php echo e($course->id); ?>"
                                                                                data-item-id="<?php echo e($course->id); ?>"
                                                                                data-item-type="academic_course">
                                                                                <img src="<?php echo e(asset('frontend/images/red-heart-icon.svg')); ?>"
                                                                                    class="wishlist-icon-<?php echo e($course->id); ?>"
                                                                                    alt="Wishlist Icon" width="18">
                                                                            </button>
                                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                    </figure>

                                                                    <div class="d-flex gap-2 justify-content-between px-2">
                                                                        <b>Mittlearn</b>
                                                                        
                                                                    </div>

                                                                    <a
                                                                        href="<?php echo e(route('about-acadcourse', $course->slug)); ?>">
                                                                        <h3 class="px-2">
                                                                            <?php echo e(limit_words($course->course_name ?? 'No Course Name', 6)); ?>

                                                                        </h3>
                                                                    </a>

                                                                    <div class="d-flex flex-wrap gap-3 courseInfo px-2">
                                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $course->metadataValues ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $metadataValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($metadataValue->field_name == 'subject' && $metadataValue->subjectInfo): ?>
                                                                                <span>
                                                                                    <img src="<?php echo e(asset('frontend/images/student-icon.svg')); ?>"
                                                                                        alt="mittlearn-image"
                                                                                        width="14">
                                                                                    Sub:
                                                                                    <?php echo e($metadataValue->subjectInfo->name ?? ''); ?>

                                                                                </span>
                                                                            <?php elseif($metadataValue->field_name == 'class' && $metadataValue->classInfo): ?>
                                                                                <span>
                                                                                    <img src="<?php echo e(asset('frontend/images/student-icon.svg')); ?>"
                                                                                        alt="mittlearn-image"
                                                                                        width="14">
                                                                                    <?php echo e($metadataValue->classInfo->name ?? ''); ?>

                                                                                </span>
                                                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                    </div>

                                                                    <hr>
                                                                    <div
                                                                        class="d-flex gap-2 align-items-center pb-2 justify-content-between px-2">
                                                                        <div class="pricetag">
                                                                            <span>₹
                                                                                <?php echo e(number_format($course->price) ?? ''); ?></span>
                                                                            <?php
                                                                                $finalPrice = $course->price;
                                                                                if ($course->discount_type == 'flat') {
                                                                                    $finalPrice -=
                                                                                        $course->discount_value;
                                                                                } elseif (
                                                                                    $course->discount_type ==
                                                                                    'percentage'
                                                                                ) {
                                                                                    $finalPrice -=
                                                                                        ($course->discount_value /
                                                                                            100) *
                                                                                        $course->price;
                                                                                }
                                                                            ?>
                                                                            ₹ <?php echo e(number_format($finalPrice) ?? ''); ?>

                                                                        </div>
                                                                        <a href="<?php echo e(route('about-acadcourse', $course->slug)); ?>"
                                                                            class="btn btn-primary-gradient rounded-1">
                                                                            Know more
                                                                        </a>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($noCoursesAvailable): ?>
                                                    <div class="col-12">
                                                        <p class="text-center text-muted">Oops! There are no courses
                                                            available for the selected category yet. But don’t
                                                            worry—exciting courses are coming your way soon! </p>
                                                    </div>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                            </div>
                        </div>
                        

                        

                        <div class="studentSayAbout mb-0">
                            <div class="container">
                                <div class="section-heading">
                                    <h2 class="text-white"><span class="greenBorder"></span>
                                        <?php echo e($testimonialBanner->heading_1 ?? ''); ?></h2>
                                    <p class="text-white"><?php echo e($testimonialBanner->sub_heading_1 ?? ''); ?></p>
                                </div>
                                <div class="aboutSliderSec position-relative">
                                    <div class="topImg">
                                        <lottie-player src="<?php echo e(asset('frontend/images/customer-response.json')); ?>"
                                            background="transparent" speed="1" style="width: 250px; height: 250px;"
                                            loop autoplay></lottie-player>
                                    </div>
                                    <div class="sayAboutSlider">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($testimonial)): ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $testimonial; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="item">
                                                    <div class="sayAbout">
                                                        <p><?php echo e($data->comment); ?></p>
                                                        <div class="sayProfile">
                                                            <figure>
                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($data->image)): ?>
                                                                    <img src="<?php echo e(Storage::url('uploads/testimonial-profile/' . $data->image)); ?>"
                                                                        alt="Profile Image">
                                                                <?php else: ?>
                                                                    <img src="<?php echo e(asset('frontend/images/default-image.jpg')); ?>"
                                                                        alt="Default Image">
                                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                            </figure>
                                                            <strong><b><?php echo e($data->name ?? ''); ?></b>
                                                                <?php echo e($data->designation ?? ''); ?></strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="advantagesSection">
                            <div class="container">
                                <div class="section-heading">
                                    <h2><span class="greenBorder"></span>
                                        Our Association brings Advantages to Schools, Students, Parents, and
                                        Individuals
                                    </h2>
                                </div>

                                <div class="row flex-row-reverse">
                                    <div class="col-md-6 position-relative mb-4 mb-md-0">
                                        <ul class="nav nav-tabs benefitsTab">
                                            <li class="nav-item">
                                                <button class="nav-link active" data-bs-toggle="tab"
                                                    data-bs-target="#schoolTab1" type="button">
                                                    <figure>
                                                        <img src="<?php echo e(asset('frontend/images/schools-icon.svg')); ?>"
                                                            alt="mittlearn-image" width="25" height="25">
                                                    </figure>
                                                    Schools
                                                </button>
                                            </li>
                                            <li class="nav-item">
                                                <button class="nav-link" data-bs-toggle="tab"
                                                    data-bs-target="#studentTab1" type="button">
                                                    <figure>
                                                        <img src="<?php echo e(asset('frontend/images/student-icon1.svg')); ?>"
                                                            alt="mittlearn-image" width="25" height="25">
                                                    </figure>Students
                                                </button>
                                            </li>
                                            <li class="nav-item">
                                                <button class="nav-link" data-bs-toggle="tab"
                                                    data-bs-target="#parentTab1" type="button">
                                                    <figure>
                                                        <img src="<?php echo e(asset('frontend/images/parents-icon.svg')); ?>"
                                                            alt="mittlearn-image" width="40" height="25">
                                                    </figure>Parents
                                                </button>
                                            </li>
                                            <li class="nav-item">
                                                <button class="nav-link" data-bs-toggle="tab"
                                                    data-bs-target="#individualsTab1" type="button">
                                                    <figure>
                                                        <img src="<?php echo e(asset('frontend/images/individuals-icon.svg')); ?>"
                                                            alt="mittlearn-image" width="25" height="25">
                                                    </figure>Individuals
                                                </button>
                                            </li>
                                        </ul>
                                        <div class="arrowImg">
                                            <lottie-player src="<?php echo e(asset('frontend/images/arrow.json')); ?>"
                                                background="transparent" speed="1"
                                                style="width: 90px; height: 90px;" loop autoplay></lottie-player>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="tab-content">
                                            <div class="tab-pane fade show active" id="schoolTab1">
                                                <div class="benefitContent">
                                                    <h4>Benefits for Schools</h4>
                                                    <ul>
                                                        <li>
                                                            <strong>Expanded Curriculum :</strong>
                                                            <p>Schools can diversify their offerings by
                                                                incorporating
                                                                supplementary
                                                                courses,
                                                                enriching the educational experience and catering to
                                                                a
                                                                broader
                                                                range of
                                                                student interests</p>
                                                        </li>
                                                        <li>
                                                            <strong>Enhanced Reputation:</strong>
                                                            <p>Providing students with opportunities to learn
                                                                additional
                                                                skills
                                                                and earn
                                                                certifications can enhance the school's reputation
                                                                and
                                                                attract a
                                                                more
                                                                diverse student body.</p>
                                                        </li>
                                                        <li>
                                                            <strong>Cost-Efficient Training:</strong>
                                                            <p>Schools can leverage existing talents within their
                                                                faculty or
                                                                tap
                                                                into
                                                                external expertise to provide specialized training
                                                                without
                                                                significant
                                                                additional costs.</p>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="studentTab1">
                                                <div class="benefitContent">
                                                    <h4>Benefits for Students</h4>
                                                    <ul>
                                                        <li>
                                                            <strong>Flexible Learning Environment:</strong>
                                                            <p>Students can explore and learn new talents from the
                                                                comfort
                                                                of
                                                                their
                                                                homes,
                                                                adapting their study schedule to their preferences.
                                                            </p>
                                                        </li>
                                                        <li>
                                                            <strong>Skill Enhancement:</strong>
                                                            <p>Existing skills can be taken to the next level,
                                                                allowing
                                                                students
                                                                to
                                                                continually improve and excel in their chosen areas
                                                                of
                                                                interest.
                                                            </p>
                                                        </li>
                                                        <li>
                                                            <strong>Certification and Recognition:</strong>
                                                            <p>Completion of courses with add-on quizzes and
                                                                interactive
                                                                worksheets
                                                                leads to
                                                                valuable certifications, showcasing their expertise
                                                                to
                                                                potential
                                                                employers
                                                                or educational institutions.</p>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="parentTab1">
                                                <div class="benefitContent">
                                                    <h4>Benefits for Parents</h4>
                                                    <ul>
                                                        <li>
                                                            <strong>Cost-Effective:</strong>
                                                            <p>Parents can save on commuting, material, and possibly
                                                                tuition
                                                                fees by
                                                                opting
                                                                for online courses, making quality education more
                                                                affordable.
                                                            </p>
                                                        </li>
                                                        <li>
                                                            <strong>Supervised Learning:</strong>
                                                            <p>Parents can monitor their child's progress and
                                                                engagement
                                                                in
                                                                the
                                                                courses,
                                                                ensuring a productive learning experience and
                                                                providing
                                                                support
                                                                as
                                                                needed.
                                                            </p>
                                                        </li>
                                                        <li>
                                                            <strong>Diverse Learning Opportunities:</strong>
                                                            <p>Online courses offer a wider range of subjects and
                                                                skills,
                                                                enabling
                                                                parents
                                                                to help their children explore various interests and
                                                                aptitudes
                                                                beyond
                                                                the
                                                                conventional school curriculum.</p>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="individualsTab1">
                                                <div class="benefitContent">
                                                    <h4>Benefits for Individuals</h4>
                                                    <ul>
                                                        <li>
                                                            <strong>Holistic Skill Development:</strong>
                                                            <p>Individuals can broaden their skill set by accessing
                                                                courses
                                                                that
                                                                are not
                                                                part of their formal education, enabling
                                                                well-rounded
                                                                personal
                                                                and
                                                                professional growth.</p>
                                                        </li>
                                                        <li>
                                                            <strong>Tailored Learning:</strong>
                                                            <p>Online courses allow individuals to focus on specific
                                                                areas
                                                                of
                                                                interest
                                                                or
                                                                skills they want to acquire, tailoring their
                                                                learning
                                                                journey to
                                                                match
                                                                their
                                                                unique aspirations.</p>
                                                        </li>
                                                        <li>
                                                            <strong>Career Advancement:</strong>
                                                            <p>Earning certifications from supplementary courses can
                                                                enhance
                                                                an
                                                                individual's
                                                                resume and open doors to new career opportunities
                                                                for
                                                                advancement within
                                                                their current field.</p>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        
                        
                        
                        
                        
                        <style>
                            .featureContent .featureTxtbox {
                                width: 100% !important;
                            }
                        </style>
                        <div class="featuresSection mt-2">
                            <div class="container">
                                <div class="section-heading">
                                    <h2 class="text-white"><span class="greenBorder"></span>
                                        <?php echo e($coreFeatureBanner->core_title); ?></h2>
                                    <p class="text-white"><?php echo e($coreFeatureBanner->core_heading); ?></p>
                                </div>
                                <div class="CircleLottie">
                                    <lottie-player src="<?php echo e(asset('frontend/images/Loader-animation.json')); ?>"
                                        background="transparent" speed="1"
                                        style="width: 250px; height: 250px;margin: auto;opacity: .2;" loop
                                        autoplay></lottie-player>
                                </div>
                                <div class="shapeLottie">
                                    <lottie-player src="<?php echo e(asset('frontend/images/data.json')); ?>"
                                        background="transparent" speed="1"
                                        style="width: 550px; height: 550px;margin: auto;" loop autoplay></lottie-player>
                                </div>
                                <div class="featureGroup">
                                    <div class="row">
                                        <div class="col-md-7 pe-md-0 mb-4 mb-md-0">
                                            <div class="featureContent featuresecContent slick-carousel">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($coreFeatureBannerAdditional)): ?>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $coreFeatureBannerAdditional->chunk(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chunk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <div class="d-flex gap-2 mb-2">
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $chunk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <div class="featureTxtbox">
                                                                    <h3 class="d-flex align-items-center gap-2 mb-3">
                                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($data->image)): ?>
                                                                            <img src="<?php echo e(Storage::url('uploads/website-pages/core_icon_image/' . $data->image)); ?>"
                                                                                alt="Icon Image">
                                                                        <?php else: ?>
                                                                            <img src="<?php echo e(asset('frontend/images/default-image.jpg')); ?>"
                                                                                alt="Default Image">
                                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                        <?php echo e($data->title ?? ''); ?>

                                                                    </h3>
                                                                    <p><?php echo e($data->description ?? ''); ?></p>
                                                                </div>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="featureImg">
                                                <div class="row px-1">
                                                    <div class="col-4 px-2">
                                                        <figure class="portraitImg mb-0">
                                                            <img src="<?php echo e(asset('frontend/images/feature-img1.jpg')); ?>"
                                                                alt="mittlearn-image">
                                                        </figure>
                                                    </div>
                                                    <div class="col-4 px-2">
                                                        <figure class="landscapeImg">
                                                            <img src="<?php echo e(asset('frontend/images/feature-img2.jpg')); ?>"
                                                                alt="mittlearn-image">
                                                        </figure>
                                                        <figure class="landscapeImg mb-0">
                                                            <img src="<?php echo e(asset('frontend/images/feature-img3.jpg')); ?>"
                                                                alt="mittlearn-image">
                                                        </figure>
                                                    </div>
                                                    <div class="col-4 px-2">
                                                        <figure class="portraitImg mb-0">
                                                            <img src="<?php echo e(asset('frontend/images/feature-img4.jpg')); ?>"
                                                                alt="mittlearn-image">
                                                        </figure>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="exclusiveBlog">
                            <div class="container">
                                <div class="section-heading">
                                    <h2><span class="greenBorder"></span>
                                        Exclusive Blog</h2>
                                    <p>Get to know what's trending</p>
                                </div>
                                <div class="row">
                                    <div class="col-md-7 pe-md-4">
                                        <div class="row">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($exclusiveBlogs)): ?>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $exclusiveBlogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php
                                                        $mainCategory = $data->categories->firstWhere(
                                                            'parent_id',
                                                            null,
                                                        );
                                                        $subCategory = $data->categories->firstWhere(
                                                            'parent_id',
                                                            '!=',
                                                            null,
                                                        );
                                                    ?>

                                                    <div class="col-md-6 mb-3 mb-md-0">
                                                        <div class="blogContent h-100">
                                                            <figure class="blogImg">
                                                                <a
                                                                    href="<?php echo e(route('blog.details', ['slug' => $data->slug])); ?>">
                                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($data->blogsMedia->attachment_file)): ?>
                                                                        <img src="<?php echo e(Storage::url('uploads/blog/' . $data->blogsMedia->attachment_file)); ?>"
                                                                            alt="mittlearn-image">
                                                                    <?php else: ?>
                                                                        <img src="<?php echo e(asset('frontend/images/default-image.jpg')); ?>"
                                                                            alt="Default Image">
                                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                </a>
                                                            </figure>

                                                            <span>
                                                                <?php echo e($mainCategory?->name ?? 'Uncategorized'); ?>

                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($subCategory): ?>
                                                                    &rarr; <?php echo e($subCategory->name); ?>

                                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                            </span>

                                                            <h4>
                                                                <a
                                                                    href="<?php echo e(route('blog.details', ['slug' => $data->slug])); ?>">
                                                                    <?php echo e($data->title); ?>

                                                                </a>
                                                            </h4>

                                                            <p><?php echo $data->meta_description ?? ''; ?></p>

                                                            <div class="blogProfile">
                                                                <figure>
                                                                    <img src="<?php echo e(asset('frontend/images/blog-profile.jpg')); ?>"
                                                                        alt="mittlearn-image">
                                                                </figure>
                                                                <strong>
                                                                    <b>Mittlearn</b> <?php echo e($data->formatted_date ?? ''); ?>

                                                                </strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                        </div>
                                    </div>
                                    <div class="col-md-5 border-start ps-md-4">
                                        <ul class="recentBlogList">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($exclusiveBlogList)): ?>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $exclusiveBlogList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li>
                                                        <strong><?php echo e($data->title ?? ''); ?></strong>
                                                        <a href="<?php echo e(route('blog.details', ['slug' => $data->slug])); ?>">Learn
                                                            More</a>
                                                    </li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <div class="text-end">
                                                <a href="<?php echo e(route('blogs')); ?>" class="btn btn-success rounded-1 fs-7">
                                                    View All
                                                </a>
                                            </div>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    
                </div>
                <div class="tab-pane fade show active  nonacademic-page" id="nonacademic-tab-pane" role="tabpanel"
                    aria-labelledby="nonacademic-tab">
                    <div class="nonacademic-page">
                        
                        <div class="courseSection">
                            <div class="container">
                                <div class="section-heading">
                                    <h2><span class="greenBorder"></span>
                                        Skill-Based Talent Courses: Unleash Creativity and Explore Hidden Potential
                                    </h2>
                                    <p>Top-Rated Programs to Inspire and Engage Young Minds<br>Discover our engaging
                                        talent-based courses—from dance and music to coding and
                                        storytelling—crafted to build confidence and creativity in every child.</p>

                                    <a href="<?php echo e(route('courses.listing', ['category_slug' => $nonAcademicCategory->slug])); ?>"
                                        class="btn btn-primary-gradient mt-3 fs-6">View All Courses</a>

                                    <!-- <a href="<?php echo e(route('courses.listing', ['category_slug' => $academicCategory->slug])); ?>" class="btn btn-primary-gradient mt-3 fs-6">View All Courses</a> -->


                                </div>


                                <div class="mainCourseTab ">
                                    <ul
                                        class="nav nav-tabs coursesTabs pb-0 flex-wrap gap-md-2 gap-1 justify-content-md-center justify-content-start">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $nonAcadSubCategory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li class="nav-item">
                                                <a class="nav-link <?php echo e($index == 0 ? 'active' : ''); ?>"
                                                    href="#<?php echo e(Str::slug($category->name)); ?>-<?php echo e($category->id); ?>"
                                                    data-bs-toggle="tab">
                                                    <i>
                                                        <img src="<?php echo e($category->icon ? Storage::url('uploads/categories/icon/' . $category->icon) : asset('frontend/images/dance-white.svg')); ?>"
                                                            alt="icon">
                                                        <img class="hoverImg"
                                                            src="<?php echo e($category->icon ? Storage::url('uploads/categories/icon/' . $category->icon) : asset('frontend/images/dance-white.svg')); ?>"
                                                            alt="icon">
                                                    </i>
                                                    <?php echo e($category->name); ?>

                                                </a>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </ul>
                                </div>
                                <div class="tab-content">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $nonAcadSubCategory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="tab-pane fade <?php echo e($loop->first ? 'show active' : ''); ?>"
                                            id="<?php echo e(Str::slug($category->name)); ?>-<?php echo e($category->id); ?>">
                                            <div class="row px-md-1">
                                                <?php
                                                    $hasCourses = false; // Flag to check if there are courses for this category
                                                ?>

                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $nonAcadCourses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($category->name === optional($course->getSubCategory)->name): ?>
                                                        <?php $hasCourses = true; ?>
                                                        <div class="col-md-6 col-lg-4 col-xl-3 px-md-2 mb-3">
                                                            <div class="coursesBox nonacadBx">
                                                                <figure class="position-relative">
                                                                    <?php
                                                                        $bannerImage = $course->metadataValues->firstWhere(
                                                                            'field_name',
                                                                            'banner_image',
                                                                        );
                                                                        $originalPrice = $course->price;
                                                                        // Discount calculation
                                                                        if ($course->discount_type == 'percent') {
                                                                            // Calculate the price after discount for percent type
                                                                            $discountedPrice =
                                                                                $originalPrice -
                                                                                $originalPrice *
                                                                                    ($course->discount_value / 100);
                                                                        } elseif ($course->discount_type == 'flat') {
                                                                            // Calculate the price after discount for flat type
                                                                            $discountedPrice =
                                                                                $originalPrice -
                                                                                $course->discount_value;
                                                                        } else {
                                                                            // If no discount type, keep the original price
                                                                            $discountedPrice = $originalPrice;
                                                                        }
                                                                    ?>
                                                                    <a
                                                                        href="<?php echo e(route('about-nonacadcourse', $course->slug)); ?>">
                                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($bannerImage): ?>
                                                                            <img src="<?php echo e(Storage::url($bannerImage->field_value)); ?>"
                                                                                alt="Banner Image">
                                                                        <?php else: ?>
                                                                            <img src="<?php echo e(asset('frontend/images/default-image.jpg')); ?>"
                                                                                alt="Default Image">
                                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                    </a>
                                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($course->in_wishlist == 0): ?>
                                                                        <button type="button"
                                                                            class=" bg-transparent border-0 p-0 wishlistButton"
                                                                            data-course-id="<?php echo e($course->id); ?>"
                                                                            data-item-id="<?php echo e($course->id); ?>"
                                                                            data-item-type="academic_course">
                                                                            <img src="<?php echo e(asset('frontend/images/heart-icon.svg')); ?>"
                                                                                class="wishlist-icon-<?php echo e($course->id); ?>"
                                                                                alt="Wishlist Icon" width="18">
                                                                        </button>
                                                                    <?php else: ?>
                                                                        <button type="button"
                                                                            class=" bg-transparent border-0 p-0 wishlistButton"
                                                                            data-course-id="<?php echo e($course->id); ?>"
                                                                            data-item-id="<?php echo e($course->id); ?>"
                                                                            data-item-type="academic_course">
                                                                            <img src="<?php echo e(asset('frontend/images/red-heart-icon.svg')); ?>"
                                                                                class="wishlist-icon-<?php echo e($course->id); ?>"
                                                                                alt="Wishlist Icon" width="18">
                                                                        </button>
                                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                </figure>

                                                                <div class="d-flex gap-2 justify-content-between px-2">
                                                                    <b>Mittlearn</b>
                                                                    
                                                                </div>
                                                                <a
                                                                    href="<?php echo e(route('about-nonacadcourse', $course->slug)); ?>">
                                                                    <h3 class="px-2">
                                                                        <?php echo e(limit_words($course->course_name ?? 'No Course Name', 6)); ?>

                                                                    </h3>
                                                                </a>
                                                                <div class="d-flex flex-wrap gap-3 courseInfo px-2">
                                                                    <span><img
                                                                            src="<?php echo e(asset('frontend/images/lessons-icon.svg')); ?>"
                                                                            alt="mittlearn-image" width="14">
                                                                        <?php echo e($course->totalChapters->count()); ?>

                                                                        Lessons</span>
                                                                    <span><img
                                                                            src="<?php echo e(asset('frontend/images/student-icon.svg')); ?>"
                                                                            alt="mittlearn-image" width="14">
                                                                        <?php echo e($course->getSubCategory->name); ?></span>
                                                                </div>
                                                                <hr>
                                                                <div
                                                                    class="d-flex gap-2 align-items-center pb-2 justify-content-between px-2">
                                                                    <div class="pricetag">
                                                                        <span>₹
                                                                            <?php echo e(number_format($course->price) ?? ''); ?></span>
                                                                        <?php
                                                                            if ($course->discount_type == 'flat') {
                                                                                $finalPrice =
                                                                                    $course->price -
                                                                                    $course->discount_value;
                                                                            } elseif (
                                                                                $course->discount_type == 'percent'
                                                                            ) {
                                                                                $finalPrice =
                                                                                    $course->price -
                                                                                    ($course->discount_value / 100) *
                                                                                        $course->price;
                                                                            } else {
                                                                                $finalPrice = $course->price;
                                                                            }
                                                                        ?>
                                                                        ₹ <?php echo e(number_format($finalPrice) ?? ''); ?>

                                                                    </div>
                                                                    <a href="<?php echo e(route('about-nonacadcourse', $course->slug)); ?>"
                                                                        class="btn btn-primary-gradient rounded-1">Know
                                                                        more</a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$hasCourses): ?>
                                                    <div class="col-12">
                                                        <p class="text-center text-muted">
                                                            Oops! There are no courses available for the selected category
                                                            yet.
                                                            But don’t worry—exciting courses are coming your way soon! </p>
                                                    </div>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="launchedSection py-5">
                            <div class="exclusiveTag">
                                <lottie-player src="<?php echo e(asset('frontend/images/exclusive-tag-red.json')); ?>"
                                    background="transparent" speed="1"
                                    style="width: 130px; height: 130px;margin: auto;" loop autoplay></lottie-player>
                            </div>
                            <div class="container">
                                <div class="section-heading">
                                    <h2 class=""><span class="greenBorder"></span>
                                        Newly Launched</h2>
                                    <p>Discover What's New at Mittlearn!</p>
                                    <p>Learn from expert instructors who make learning exciting with creativity and hands-on
                                        teaching.</p>
                                </div>
                                <div class="exploreMain">
                                    <div class="slider slider-explore">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($nonAcadCoursesLatest) && $nonAcadCoursesLatest->isNotEmpty()): ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $nonAcadCoursesLatest; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $description = $course->metadataValues
                                                        ->where('field_name', 'course_overview')
                                                        ->value('field_value');
                                                    $bannerImage = $course->metadataValues
                                                        ->where('field_name', 'banner_image')
                                                        ->value('field_value');

                                                ?>
                                                <a href="<?php echo e(route('about-nonacadcourse', $course->slug)); ?>"
                                                    class="text-black">
                                                    <div class="sliderContent">
                                                        <div class="sliderImgtxt">
                                                            <h3><?php echo e($course->course_name); ?></h3>

                                                            <p><?php echo e(Str::limit(strip_tags($description), 500, '...')); ?></p>
                                                        </div>
                                                        <div class="sliderImg">
                                                            <figure>
                                                                <img src="<?php echo e($bannerImage ? Storage::url($bannerImage) : asset('frontend/images/default-image.jpg')); ?>"
                                                                    alt="course image">
                                                            </figure>
                                                        </div>
                                                    </div>
                                                </a>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php else: ?>
                                            <p>No courses available for this category.</p>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                    <div class="slider slider-explore-thumb" style="height: 211.38;">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $nonAcadCoursesLatest; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php

                                                $bannerImage = $course->metadataValues
                                                    ->where('field_name', 'banner_image')
                                                    ->value('field_value');
                                            ?>
                                            <div>
                                                <a href="<?php echo e(route('about-nonacadcourse', $course->slug)); ?>">
                                                    <div class="exploreconTent">
                                                        <figure>
                                                            <img src="<?php echo e($bannerImage ? Storage::url($bannerImage) : asset('frontend/images/default-image.jpg')); ?>"
                                                                alt="course image">
                                                        </figure>
                                                        <div class="d-none d-md-flex justify-content-between px-2 pb-3">
                                                            <span><?php echo e($course->course_name); ?></span>

                                                            <figure>
                                                                <img src="<?php echo e(asset('frontend/images/greenArrow.png')); ?>"
                                                                    alt="mittlearn-image" width="15">
                                                            </figure>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        

                        <div class="studentSayAbout mb-0">
                            <div class="container">
                                <div class="section-heading">
                                    <h2 class="text-white"><span class="greenBorder"></span>
                                        <?php echo e($testimonialBanner->heading_1 ?? ''); ?></h2>
                                    <p class="text-white"><?php echo e($testimonialBanner->sub_heading_1 ?? ''); ?>

                                    </p>
                                </div>
                                <div class="aboutSliderSec position-relative">
                                    <div class="topImg">
                                        <lottie-player src="<?php echo e(asset('frontend/images/customer-response.json')); ?>"
                                            background="transparent" speed="1" style="width: 250px; height: 250px;"
                                            loop autoplay></lottie-player>
                                    </div>
                                    <div class="sayAboutSlider">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($testimonial)): ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $testimonial; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="item">
                                                    <div class="sayAbout">
                                                        <p><?php echo e($data->comment ?? ''); ?></p>
                                                        <div class="sayProfile">
                                                            <figure>
                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($data->image)): ?>
                                                                    <img src="<?php echo e(Storage::url('uploads/testimonial-profile/' . $data->image)); ?>"
                                                                        alt="Profile Image">
                                                                <?php else: ?>
                                                                    <img src="<?php echo e(asset('frontend/images/default-image.jpg')); ?>"
                                                                        alt="Default Image">
                                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                            </figure>
                                                            <strong><b><?php echo e($data->name ?? ''); ?></b>
                                                                <?php echo e($data->designation ?? ''); ?></strong>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="meetSection py-5">
                            <div class="meetLottie">
                                <lottie-player src="<?php echo e(asset('frontend/images/double-lines.json')); ?>"
                                    background="transparent" speed="1"
                                    style="width: 250px; height: 250px;margin: auto;opacity: .7;" loop
                                    autoplay></lottie-player>
                            </div>
                            <div class="container">
                                <div class="section-heading">
                                    <h2><span class="greenBorder"></span>
                                        <?php echo e($instructorBanner->instructor_title ?? ''); ?></h2>
                                    <p> <?php echo e($instructorBanner->instructor_description ?? ''); ?></p>
                                </div>
                                <div class="meetMain">
                                    <div class="slider meetSlider">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $instructorList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div>
                                                <div class="meetSliderContent">
                                                    <div class="meetslidertxt">
                                                        <div class="d-md-flex flex-wrap gap-3">
                                                            <div>
                                                                <span><?php echo e($data->user->name ?? ''); ?></span>
                                                                <b><?php echo e($data->user->userAdditionalDetail->designation ?? ''); ?></b>
                                                            </div>
                                                        </div>
                                                        <p><?php echo e($data->user->userAdditionalDetail->about ?? ''); ?>

                                                        </p>

                                                        
                                                    </div>
                                                    <div class="meetprofileImg">
                                                        <figure>
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($data->user->image)): ?>
                                                                <img src="<?php echo e(Storage::url('uploads/user/profile_image/' . $data->user->image)); ?>"
                                                                    alt="Instructor Image">
                                                            <?php else: ?>
                                                                <img src="<?php echo e(asset('frontend/images/default-image.jpg')); ?>"
                                                                    alt="Default Image">
                                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        </figure>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>

                                    <div class="slider meetSliderThumb">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $instructorList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div>
                                                <div class="meetContent">
                                                    <figure>
                                                        <img src="<?php echo e(!empty($data->user->image) ? Storage::url('uploads/user/profile_image/' . $data->user->image) : asset('frontend/images/default-image.jpg')); ?>"
                                                            alt="Instructor Image">
                                                    </figure>
                                                    <span><?php echo e($data->user->name ?? ''); ?></span>
                                                    <b><?php echo e($data->user->userAdditionalDetail->designation ?? ''); ?></b>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>


                            </div>
                        </div>
                        
                        
                        
                        <div class="featuresSection mt-2">
                            <div class="container">
                                <div class="section-heading">
                                    <h2 class="text-white"><span class="greenBorder"></span>
                                        <?php echo e($coreFeatureBanner->core_title ?? ''); ?></h2>
                                    <p class="text-white"><?php echo e($coreFeatureBanner->core_heading ?? ''); ?></p>
                                </div>
                                <div class="CircleLottie">
                                    <lottie-player src="<?php echo e(asset('frontend/images/Loader-animation.json')); ?>"
                                        background="transparent" speed="1"
                                        style="width: 250px; height: 250px;margin: auto;opacity: .2;" loop
                                        autoplay></lottie-player>
                                </div>
                                <div class="shapeLottie">
                                    <lottie-player src="<?php echo e(asset('frontend/images/data.json')); ?>"
                                        background="transparent" speed="1"
                                        style="width: 550px; height: 550px;margin: auto;" loop autoplay></lottie-player>
                                </div>
                                <div class="featureGroup">
                                    <div class="row">
                                        <div class="col-md-7 pe-md-0 mb-4 mb-md-0">
                                            <div class="featureContent featuresecContent slick-carousel">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($coreNonFeatureBannerAdditional)): ?>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $coreNonFeatureBannerAdditional->chunk(2); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chunk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <div class="d-flex gap-2 mb-2">
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $chunk; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <div class="featureTxtbox">
                                                                    <h3 class="d-flex align-items-center gap-2 mb-3">
                                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($data->image)): ?>
                                                                            <img src="<?php echo e(Storage::url('uploads/website-pages/non_academic_core_icon_image/' . $data->image)); ?>"
                                                                                alt="Icon Image">
                                                                        <?php else: ?>
                                                                            <img src="<?php echo e(asset('frontend/images/default-image.jpg')); ?>"
                                                                                alt="Default Image">
                                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                        <?php echo e($data->title ?? ''); ?>

                                                                    </h3>
                                                                    <p><?php echo e($data->description ?? ''); ?></p>
                                                                </div>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="featureImg">
                                                <div class="row px-1">
                                                    <div class="col-4 px-2">
                                                        <figure class="portraitImg mb-0">
                                                            <img src="<?php echo e(asset('frontend/images/slider-differentImg3.png')); ?>"
                                                                alt="mittlearn-image">
                                                        </figure>
                                                    </div>
                                                    <div class="col-4 px-2">
                                                        <figure class="landscapeImg">
                                                            <img src="<?php echo e(asset('frontend/images/courseSecondimg.png')); ?>"
                                                                alt="mittlearn-image">
                                                        </figure>
                                                        <figure class="landscapeImg mb-0">
                                                            <img src="<?php echo e(asset('frontend/images/courseThreeimg.png')); ?>"
                                                                alt="mittlearn-image">
                                                        </figure>
                                                    </div>
                                                    <div class="col-4 px-2">
                                                        <figure class="portraitImg mb-0">
                                                            <img src="<?php echo e(asset('frontend/images/slider-differentImg4.png')); ?>"
                                                                alt="mittlearn-image">
                                                        </figure>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="advantagesSection">
                            <div class="container">
                                <div class="section-heading">
                                    <h2><span class="greenBorder"></span>
                                        Our Association brings Advantages to Schools, Students, Parents, and
                                        Individuals
                                    </h2>
                                </div>

                                <div class="row flex-row-reverse">
                                    <div class="col-md-6 position-relative mb-4 mb-md-0">
                                        <ul class="nav nav-tabs benefitsTab">
                                            <li class="nav-item">
                                                <button class="nav-link active" data-bs-toggle="tab"
                                                    data-bs-target="#schoolTab" type="button">
                                                    <figure>
                                                        <img src="<?php echo e(asset('frontend/images/schools-icon.svg')); ?>"
                                                            alt="mittlearn-image" width="25" height="25">
                                                    </figure>
                                                    Schools
                                                </button>
                                            </li>
                                            <li class="nav-item">
                                                <button class="nav-link" data-bs-toggle="tab"
                                                    data-bs-target="#studentTab" type="button">
                                                    <figure>
                                                        <img src="<?php echo e(asset('frontend/images/student-icon1.svg')); ?>"
                                                            alt="mittlearn-image" width="25" height="25">
                                                    </figure>Students
                                                </button>
                                            </li>
                                            <li class="nav-item">
                                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#parentTab"
                                                    type="button">
                                                    <figure>
                                                        <img src="<?php echo e(asset('frontend/images/parents-icon.svg')); ?>"
                                                            alt="mittlearn-image" width="40" height="25">
                                                    </figure>Parents
                                                </button>
                                            </li>
                                            <li class="nav-item">
                                                <button class="nav-link" data-bs-toggle="tab"
                                                    data-bs-target="#individualsTab" type="button">
                                                    <figure>
                                                        <img src="<?php echo e(asset('frontend/images/individuals-icon.svg')); ?>"
                                                            alt="mittlearn-image" width="25" height="25">
                                                    </figure>Individuals
                                                </button>
                                            </li>
                                        </ul>
                                        <div class="arrowImg">
                                            <lottie-player src="<?php echo e(asset('frontend/images/arrow.json')); ?>"
                                                background="transparent" speed="1"
                                                style="width: 90px; height: 90px;" loop autoplay></lottie-player>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="tab-content">
                                            <div class="tab-pane fade show active" id="schoolTab">
                                                <div class="benefitContent">
                                                    <h4>Benefits for Schools</h4>
                                                    <ul>
                                                        <li>
                                                            <strong>Expanded Curriculum :</strong>
                                                            <p>Schools can diversify their offerings by
                                                                incorporating
                                                                supplementary courses,
                                                                enriching the educational experience and
                                                                catering to
                                                                a
                                                                broader
                                                                range of
                                                                student interests</p>
                                                        </li>
                                                        <li>
                                                            <strong>Enhanced Reputation:</strong>
                                                            <p>Providing students with opportunities to
                                                                learn
                                                                additional
                                                                skills
                                                                and earn
                                                                certifications can enhance the school's
                                                                reputation
                                                                and
                                                                attract a
                                                                more
                                                                diverse student body.</p>
                                                        </li>
                                                        <li>
                                                            <strong>Cost-Efficient Training:</strong>
                                                            <p>Schools can leverage existing talents within
                                                                their
                                                                faculty or
                                                                tap
                                                                into
                                                                external expertise to provide specialized
                                                                training
                                                                without
                                                                significant
                                                                additional costs.</p>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="studentTab">
                                                <div class="benefitContent">
                                                    <h4>Benefits for Students</h4>
                                                    <ul>
                                                        <li>
                                                            <strong>Flexible Learning Environment:</strong>
                                                            <p>Students can explore and learn new talents
                                                                from
                                                                the
                                                                comfort
                                                                of
                                                                their homes,
                                                                adapting their study schedule to their
                                                                preferences.
                                                            </p>
                                                        </li>
                                                        <li>
                                                            <strong>Skill Enhancement:</strong>
                                                            <p>Existing skills can be taken to the next
                                                                level,
                                                                allowing
                                                                students
                                                                to
                                                                continually improve and excel in their
                                                                chosen
                                                                areas
                                                                of
                                                                interest.
                                                            </p>
                                                        </li>
                                                        <li>
                                                            <strong>Certification and Recognition:</strong>
                                                            <p>Completion of courses with add-on quizzes and
                                                                interactive
                                                                worksheets leads to
                                                                valuable certifications, showcasing their
                                                                expertise
                                                                to
                                                                potential
                                                                employers
                                                                or educational institutions.</p>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="parentTab">
                                                <div class="benefitContent">
                                                    <h4>Benefits for Parents</h4>
                                                    <ul>
                                                        <li>
                                                            <strong>Cost-Effective:</strong>
                                                            <p>Parents can save on commuting, material, and
                                                                possibly
                                                                tuition
                                                                fees by opting
                                                                for online courses, making quality education
                                                                more
                                                                affordable.
                                                            </p>
                                                        </li>
                                                        <li>
                                                            <strong>Supervised Learning:</strong>
                                                            <p>Parents can monitor their child's progress
                                                                and
                                                                engagement
                                                                in
                                                                the
                                                                courses,
                                                                ensuring a productive learning experience
                                                                and
                                                                providing
                                                                support
                                                                as needed.
                                                            </p>
                                                        </li>
                                                        <li>
                                                            <strong>Diverse Learning Opportunities:</strong>
                                                            <p>Online courses offer a wider range of
                                                                subjects
                                                                and
                                                                skills,
                                                                enabling parents
                                                                to help their children explore various
                                                                interests
                                                                and
                                                                aptitudes
                                                                beyond the
                                                                conventional school curriculum.</p>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="individualsTab">
                                                <div class="benefitContent">
                                                    <h4>Benefits for Individuals</h4>
                                                    <ul>
                                                        <li>
                                                            <strong>Holistic Skill Development:</strong>
                                                            <p>Individuals can broaden their skill set by
                                                                accessing
                                                                courses
                                                                that
                                                                are not
                                                                part of their formal education, enabling
                                                                well-rounded
                                                                personal
                                                                and
                                                                professional growth.</p>
                                                        </li>
                                                        <li>
                                                            <strong>Tailored Learning:</strong>
                                                            <p>Online courses allow individuals to focus on
                                                                specific
                                                                areas
                                                                of
                                                                interest or
                                                                skills they want to acquire, tailoring their
                                                                learning
                                                                journey to
                                                                match their
                                                                unique aspirations.</p>
                                                        </li>
                                                        <li>
                                                            <strong>Career Advancement:</strong>
                                                            <p>Earning certifications from supplementary
                                                                courses
                                                                can
                                                                enhance
                                                                an
                                                                individual's
                                                                resume and open doors to new career
                                                                opportunities
                                                                for
                                                                advancement within
                                                                their current field.</p>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="exclusiveBlog">
                            <div class="container">
                                <div class="section-heading">
                                    <h2><span class="greenBorder"></span>
                                        Exclusive Blog</h2>
                                    <p>Get to know what's trending</p>
                                </div>
                                <div class="row">
                                    <div class="col-md-7 pe-md-4">
                                        <div class="row">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($exclusiveBlogs)): ?>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $exclusiveBlogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <?php
                                                        $mainCategory = $data->categories->firstWhere(
                                                            'parent_id',
                                                            null,
                                                        );
                                                        $subCategory = $data->categories->firstWhere(
                                                            'parent_id',
                                                            '!=',
                                                            null,
                                                        );
                                                    ?>

                                                    <div class="col-md-6 mb-3 mb-md-0">
                                                        <div class="blogContent h-100">
                                                            <figure class="blogImg">
                                                                <a
                                                                    href="<?php echo e(route('blog.details', ['slug' => $data->slug])); ?>">
                                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($data->blogsMedia->attachment_file)): ?>
                                                                        <img src="<?php echo e(Storage::url('uploads/blog/' . $data->blogsMedia->attachment_file)); ?>"
                                                                            alt="mittlearn-image">
                                                                    <?php else: ?>
                                                                        <img src="<?php echo e(asset('frontend/images/default-image.jpg')); ?>"
                                                                            alt="Default Image">
                                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                </a>
                                                            </figure>

                                                            <span>
                                                                <?php echo e($mainCategory?->name ?? 'Uncategorized'); ?>

                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($subCategory): ?>
                                                                    &rarr; <?php echo e($subCategory->name); ?>

                                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                            </span>

                                                            <h4>
                                                                <a
                                                                    href="<?php echo e(route('blog.details', ['slug' => $data->slug])); ?>">
                                                                    <?php echo e($data->title); ?>

                                                                </a>
                                                            </h4>

                                                            <p><?php echo $data->meta_description ?? ''; ?></p>

                                                            <div class="blogProfile">
                                                                <figure>
                                                                    <img src="<?php echo e(asset('frontend/images/blog-profile.jpg')); ?>"
                                                                        alt="mittlearn-image">
                                                                </figure>
                                                                <strong>
                                                                    <b>Mittlearn</b> <?php echo e($data->formatted_date ?? ''); ?>

                                                                </strong>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                        </div>
                                    </div>
                                    <div class="col-md-5 border-start ps-md-4">
                                        <ul class="recentBlogList">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($exclusiveBlogList)): ?>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $exclusiveBlogList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <li>
                                                        <strong><?php echo e($data->title ?? ''); ?></strong>
                                                        <a href="<?php echo e(route('blog.details', ['slug' => $data->slug])); ?>">Learn
                                                            More</a>
                                                    </li>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                            <div class="text-end">
                                                <a href="<?php echo e(route('blogs')); ?>" class="btn btn-success rounded-1 fs-7">
                                                    View All
                                                </a>
                                            </div>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </section>
    </div>
    <script>
        // Show bottom toggle on 20% scroll
        $(window).on("scroll", function() {
            let scrollTop = $(window).scrollTop();
            let docHeight = $(document).height();
            let windowHeight = $(window).height();
            let scrollPercent = (scrollTop / (docHeight - windowHeight)) * 100;

            if (scrollPercent > 10) {
                $(".bottomToggleBtn").fadeIn(); // Show toggle
            } else {
                $(".bottomToggleBtn").fadeOut(); // Hide toggle
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/frontend/index.blade.php ENDPATH**/ ?>