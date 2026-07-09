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
                        <h3>About Us</h3>
                        <p><?php echo e($aboutUs->banner_description ?? ''); ?></p>
                    </div>
                </div>
            </div>

        </div>
        <div class="technoSection">
            <div class="container">
                <div class="section-heading">
                    <h2><span class="greenBorder"></span>
                        Mittsure Technologies</h2>
                    <p class="fw-semibold">At a Glance</p>
                </div>
                <div class="row align-items-center">
                    <div class="col-lg-5 mb-4 mb-lg-0">
                        <figure class="m-0">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($atGlance['glance_image'])): ?>
                                <img src="<?php echo e(Storage::url($atGlance['glance_image'])); ?>" alt="">
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </figure>
                    </div>
                    <div class="col-lg-7">
                        <div class="glanceRight">
                            <p><?php echo e($atGlance['mittsure_at_glance_description'] ?? ''); ?></p>
                            <a href="<?php echo e(route('contact-us')); ?>"
                                class="btn btn-primary-gradient rounded-5"><?php echo e($atGlance['button'] ?? ''); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="moreSection">
            <div class="mittsureLottie">
                <lottie-player src="<?php echo e(asset('frontend/images/master-loading.json')); ?>" background="transparent"
                    speed="1" style="width: 260px; height: 260px;" loop="" autoplay=""></lottie-player>
            </div>
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-7">
                        <div class="mittsureLeft">
                            <span>Mittlearn</span>
                            
                            <p><?php echo e($mittsureSec['mittsure_section_description'] ?? ''); ?></p>
                            
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="mittsureright">
                            <figure class="text-center mainRight">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($mittsureSec['mittsure_section_image'])): ?>
                                    <img src="<?php echo e(Storage::url($mittsureSec['mittsure_section_image'])); ?>" alt="">
                                <?php else: ?>
                                    <img src="<?php echo e(asset('frontend/images/default-image.jpg')); ?>" alt="Default Image">
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <img src="<?php echo e(asset('frontend/images/rocketImg.png')); ?>" alt="" class="rocketImg"
                                    width="45">
                                <img src="<?php echo e(asset('frontend/images/threeImg.png')); ?>" alt="" class="threeImg"
                                    width="45">
                                <img src="<?php echo e(asset('frontend/images/paintImg.png')); ?>" alt="" class="paintImg"
                                    width="45">
                            </figure>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="activities">
            <div class="section-heading">
                <h2><span class="greenBorder"></span>
                    Versatile Activities</h2>
                <p><?php echo e($aboutUs->versatile_activities_description); ?></p>
            </div>
            <ul class="listingStar">
                <?php
                    use App\Models\Category;

                    $categoryList = [];

                    if ($aboutUs->versatile_activities === 'random') {
                        $categoryList = Category::where('status', 1)
                            ->where('parent_id', 2)
                            ->inRandomOrder()
                            ->take(20)
                            ->pluck('name')
                            ->toArray(); // Get 20 random categories
                    } else {
                        $ids = explode(',', $aboutUs->versatile_activities); // e.g., "1,3,5"
                        $categoryList = Category::where('status', 1)->whereIn('id', $ids)->pluck('name')->toArray();
                    }
                ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $categoryList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categoryName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($categoryName); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </ul>
        </div>
        <div class="leadership">
            <div class="container">
                <div class="section-heading">
                    <h2><span class="greenBorder"></span>
                        Our Leadership</h2>
                    <p><?php echo e($leadership['our_leadership_description'] ?? ''); ?></p>
                </div>
                <div class="leadershipContent">
                    <div class="leaderImg">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($leadership['primary']->userAdditionalDetail) && isset($leadership['primary']->image)): ?>
                            <img src="<?php echo e(Storage::url('uploads/user/profile_image/' . $leadership['primary']->image)); ?>"
                                alt="">
                        <?php else: ?>
                            <img src="<?php echo e(asset('frontend/images/default-image.jpg')); ?>" alt="Default Image">
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="leaderInfo">
                        <strong><?php echo e($leadership['primary']->userAdditionalDetail->designation ?? ''); ?></strong>
                        <h3><span class="greenBorder"></span><?php echo e($leadership['primary']->name ?? ''); ?></h3>
                        <p class="mb-1"><?php echo e($leadership['primary']->userAdditionalDetail->about ?? ''); ?></p>
                    </div>
                </div>
                <div class="galaxyLoader">
                    <lottie-player src="<?php echo e(asset('frontend/images/galaxy-loader.json')); ?>" background="transparent"
                        speed="1" style="width: 200px; height: 200px;" loop autoplay></lottie-player>
                </div>
            </div>
        </div>
        <div class="leaders">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 mb-3">
                        <div class="leadertxt">
                            <div class="leadeImage">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($leadership['secondary']->userAdditionalDetail) && isset($leadership['secondary']->image)): ?>
                                    <img src="<?php echo e(Storage::url('uploads/user/profile_image/' . $leadership['secondary']->image)); ?>"
                                        alt="">
                                <?php else: ?>
                                    <img src="<?php echo e(asset('frontend/images/default-image.jpg')); ?>" alt="Default Image">
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div class="leaderDetail">
                                <span><?php echo e($leadership['secondary']->userAdditionalDetail->designation ?? ''); ?>

                                    <b><?php echo e($leadership['secondary']->name ?? ''); ?></b></span>
                                <div class="detailTxt">
                                    <p><?php echo e($leadership['secondary']->userAdditionalDetail->about ?? ''); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-3">
                        <div class="leadertxt">
                            <div class="leadeImage">

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($leadership['third']->userAdditionalDetail) && isset($leadership['third']->image)): ?>
                                    <img src="<?php echo e(Storage::url('uploads/user/profile_image/' . $leadership['third']->image)); ?>"
                                        alt="">
                                <?php else: ?>
                                    <img src="<?php echo e(asset('frontend/images/default-image.jpg')); ?>" alt="Default Image">
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div class="leaderDetail">
                                <span><?php echo e($leadership['third']->userAdditionalDetail->designation ?? ''); ?>

                                    <b><?php echo e($leadership['third']->name ?? ''); ?></b></span>
                                <div class="detailTxt">
                                    <p><?php echo e($leadership['third']->userAdditionalDetail->about ?? ''); ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>

        <div class="visionSection">
            <div class="visionLottie">
                <lottie-player src="<?php echo e(asset('frontend/images/shapes.json')); ?>" background="transparent" speed="1"
                    style="width: 240px; height: 240px;" loop="" autoplay=""></lottie-player>
            </div>
            <div class="container">
                <div class="section-heading">
                    <h2><span class="greenBorder"></span>
                        Our Vision</h2>
                    <p class="fw-semibold"><?php echo e($aboutUs->vision_description ?? ''); ?></p>
                </div>
                <div class="row align-items-center">
                    <div class="col-lg-5 mb-4 mb-lg-0">
                        <figure class="m-0">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($aboutUs) && isset($aboutUs->vision_image)): ?>
                                <img src="<?php echo e(Storage::url($aboutUs->vision_image)); ?>" alt="">
                            <?php else: ?>
                                <img src="<?php echo e(asset('frontend/images/default-image.jpg')); ?>" alt="Default Image">
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </figure>
                    </div>
                    <div class="col-lg-7">
                        <div class="glanceRight">
                            <p class="mb-2"><?php echo $aboutUs->about_vision ?? ''; ?></p>

                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="ourProagram">
            <div class="container">
                <div class="section-heading">
                    <h2><span class="greenBorder"></span>
                        Our Programs</h2>
                    <p><?php echo e($aboutUs->program_description); ?></p>
                </div>
                <div class="accordion programAccordion" id="accordionHorizontalExample">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($programs)): ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $programs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $program): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="card">
                                <div class="card-header d-flex justify-content-between gap-2 align-items-center"
                                    data-bs-toggle="collapse" data-bs-target="#collapse<?php echo e($index); ?>"
                                    aria-expanded="<?php echo e($loop->first ? 'true' : 'false'); ?>">
                                    <?php echo e($program['title'] ?? ''); ?>

                                    <span><?php echo e(str_pad($index + 1, 2, '0', STR_PAD_LEFT)); ?></span>
                                </div>

                                <div id="collapse<?php echo e($index); ?>"
                                    class="collapseProgram collapse <?php echo e($loop->first ? 'show' : ''); ?>"
                                    data-bs-parent="#accordionHorizontalExample">
                                    <div class="card-body programContent">
                                        <div class="row align-items-center">
                                            <div class="col-md-8">
                                                <h3> <?php echo e($program['title'] ?? ''); ?> </h3>
                                                <p><?php echo e($program['description'] ?? ''); ?></p>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($program['url_redirection'])): ?>
                                                    <a href="<?php echo e($program['url_redirection'] ?? ''); ?>"
                                                        class="btn btn-primary-gradient"
                                                        <?php if($loop->remaining < 2): ?> target="_blank" <?php endif; ?>>
                                                        Know More
                                                    </a>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                            <div class="col-md-4">
                                                <figure>
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($program['image'])): ?>
                                                        <img src="<?php echo e(Storage::url('uploads/cms-about-us/our-program/' . $program['image'])); ?>"
                                                            alt=" Image">
                                                    <?php else: ?>
                                                        <img src="<?php echo e(asset('frontend/images/default-image.jpg')); ?>"
                                                            alt="Default Image">
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </figure>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="skillsSection visionMain">
                    <div class="container">
                        <div class="row">
                            <div class="col-6 col-md-3 mb-3 mb-md-0">
                                <span class="skillsBox skillsBox1">
                                    <figure>
                                        <lottie-player src="<?php echo e(asset('frontend/images/independent-learning.json')); ?>"
                                            background="transparent" speed="1"
                                            style="width: 130px; height: 130px;margin: auto;" loop
                                            autoplay></lottie-player>
                                    </figure>
                                    <h3>Independent Learning</h3>
                                </span>
                            </div>
                            <div class="col-6 col-md-3 mb-3 mb-md-0">
                                <span class="skillsBox skillsBox2">
                                    <figure>
                                        <lottie-player src="<?php echo e(asset('frontend/images/interactive-learning.json')); ?>"
                                            background="transparent" speed="1"
                                            style="width: 130px; height: 130px;margin: auto;" loop
                                            autoplay></lottie-player>
                                    </figure>
                                    <h3>Interactive Learning</h3>
                                </span>
                            </div>
                            <div class="col-6 col-md-3 mb-3 mb-md-0">
                                <span class="skillsBox skillsBox3">
                                    <figure>
                                        <lottie-player src="<?php echo e(asset('frontend/images/woman-marketplace.json')); ?>"
                                            background="transparent" speed="1"
                                            style="width: 130px; height: 130px;margin: auto;" loop
                                            autoplay></lottie-player>
                                    </figure>
                                    <h3>Digital Activities</h3>
                                </span>
                            </div>
                            <div class="col-6 col-md-3 mb-3 mb-md-0">
                                <span class="skillsBox skillsBox4">
                                    <figure>
                                        <lottie-player src="<?php echo e(asset('frontend/images/distance-education.json')); ?>"
                                            background="transparent" speed="1"
                                            style="width: 130px; height: 130px;margin: auto;" loop
                                            autoplay></lottie-player>
                                    </figure>
                                    <h3>Curriculum Books</h3>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="activitieSection">
            <div class="container">
                <div class="section-heading">
                    <h2><span class="greenBorder"></span>
                        Our Activities</h2>
                    <p>Explore the various activities and events that make learning exciting</p>
                </div>
                <strong class="d-block fs-8 fw-medium ps-2 mb-3">Gallery</strong>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($activitiesGallary && optional($activitiesGallary->mediaFolderFiles)->isNotEmpty()): ?>
                    <div class="activitieSlider">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $activitiesGallary->mediaFolderFiles->chunk(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chunk): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div>
                                <div class="row px-1">
                                    <div class="col-6 px-2">
                                        <figure class="galleryBigImg">
                                            <img src="<?php echo e(Storage::url('uploads/media-files/' . $chunk->first()->attachment_file)); ?>"
                                                alt="Gallery Image">
                                        </figure>
                                    </div>
                                    <div class="col-6 px-2">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $chunk->slice(1); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <figure class="gallerySmallImg">
                                                <img src="<?php echo e(Storage::url('uploads/media-files/' . $image->attachment_file)); ?>"
                                                    alt="Gallery Image">
                                            </figure>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php else: ?>
                    <p>No images available for Our Activities.</p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            </div>
        </div>
        <div class="studentSay">
            <div class="dotsImg">
                <img src="<?php echo e(asset('frontend/images/dots.svg')); ?>" alt="img" width="67">
            </div>
            <div class="container">
                <div class="section-heading">
                    <h2><span class="greenBorder"></span>
                        What Our Students Say About Mittlearn</h2>
                    <p>Real voices, real journeys — hear how Mittlearn is making learning meaningful and fun!</p>
                </div>
                <div class="studentSaySlider">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($testimonials)): ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div>
                                <div class="sayContent">
                                    <div class="quoteImg">
                                        <img src="<?php echo e(asset('frontend/images/quote.svg')); ?>" alt=""
                                            width="27">
                                    </div>
                                    <p><?php echo e($data->comment ?? ''); ?></p>
                                    <div class="sayProfile">
                                        <figure>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($data->image)): ?>
                                                <img src="<?php echo e(Storage::url('uploads/testimonial-profile/' . $data->image)); ?>"
                                                    alt="">
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </figure>
                                        <strong><b><?php echo e($data->name ?? ''); ?></b> <?php echo e($data->designation ?? ''); ?></strong>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/frontend/about-us.blade.php ENDPATH**/ ?>