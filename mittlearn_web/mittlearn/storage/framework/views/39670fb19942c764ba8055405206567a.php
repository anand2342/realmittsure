<?php $__env->startSection('content'); ?>
    <div>
        <div class="blogsliderSection">
            <div class="container">
                <div class="d-lg-flex">
                    <div class="sliderBlog">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $popular_blogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $image = \App\Models\MediaFiles::where('tbl_id', $blog->id)
                                    ->where('type', 'blog')
                                    ->first();
                            ?>
                            <div class="item">
                                <div class="blogSliderMain">
                                    <div class="blogsliderleft">
                                        <div class="blogProfile mb-3">
                                            <figure>
                                                <img src="<?php echo e(asset('frontend/images/blog-profile.jpg')); ?>" alt="">
                                            </figure>
                                            <strong><b class="m-0">Mittlearn</b> </strong>
                                        </div>
                                        <h3><?php echo e($blog->title); ?></h3>
                                        <?php
                                            $mainCategory = $blog->categories->firstWhere('parent_id', null);
                                            $subCategory = $blog->categories->firstWhere('parent_id', '!=', null);
                                        ?>

                                        <span class="techLine">
                                            <?php echo e($mainCategory?->name ?? 'Uncategorized'); ?>

                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($subCategory): ?>
                                                &rarr; <?php echo e($subCategory->name); ?>

                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </span> 
                                        <p><?php echo Str::limit($blog->body, 380, '...'); ?></p>

                                        <div class="d-flex flex-wrap gap-3 courseInfo px-2">
                                            <span><img src="<?php echo e(asset('frontend/images/icon-eye.svg')); ?>" alt=""
                                                    width="14"> <?php echo e($blog->views_count); ?></span>
                                            <span><img src="<?php echo e(asset('frontend/images/icon-calender.svg')); ?>" alt=""
                                                    width="14"><?php echo e(dateConvert($blog->published_at, 'd, M Y')); ?></span>
                                        </div>
                                        <a href="<?php echo e(route('blog.details', ['slug' => $blog->slug])); ?>"
                                            class="btn-primary btn-primary-gradient knowMorebtn"><i
                                                class="bi bi-arrow-down-right me-2"></i> Read More</a>
                                    </div>
                                    <div class="sliderRight">
                                        <img src="<?php echo e(Storage::url('uploads/blog/' . $image->attachment_file)); ?>"
                                            alt="" class="imgoneSlider">

                                        <span class="vrLine"></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>

                    <div class="blogRightSec">
                        <div class="blogTxt">
                            <strong>Blog.</strong>
                            <div class="">
                                <lottie-player src="<?php echo e(asset('frontend/images/arrow.json')); ?>" background="transparent"
                                    speed="1" style="width: 90px; height: 90px;" loop=""
                                    autoplay=""></lottie-player>
                            </div>
                        </div>
                        <div class="articalMain">
                            <h4>Popular Articles</h4>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $popular_blogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $image = \App\Models\MediaFiles::where('tbl_id', $blog->id)
                                        ->where('type', 'blog')
                                        ->first();
                                ?>
                                <div class="artificialBx z1">
                                    <figure class="m-0">
                                        <img src="<?php echo e(Storage::url('uploads/blog/' . $image->attachment_file)); ?>">
                                    </figure>
                                    <div>
                                        <h6><?php echo e($blog->title); ?></h6>
                                        <div class="d-flex justify-content-between">
                                            <div class="d-flex flex-wrap gap-3 courseInfo">
                                                <span><img src="<?php echo e(asset('frontend/images/blog-profile.jpg')); ?>"
                                                        alt="" width="14">
                                                    <?php echo e($blog->views_count); ?></span>
                                                
                                            </div>
                                            <a href="<?php echo e(route('blog.details', ['slug' => $blog->slug])); ?>"
                                                class="arrowGreen"><i class="bi bi-arrow-right"></i></a>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <div class="mouseLottie">
                                <lottie-player src="<?php echo e(asset('frontend/images/Scroll-down.json')); ?>"
                                    background="transparent" speed="1" style="width: 90px; height: 90px;"
                                    loop="" autoplay=""></lottie-player>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="exclusiveSection">
            <div class="container">
                <div class="section-heading d-flex justify-content-between w-100">
                    <h2 class="text-white"><span class="greenBorder"></span>
                        Exclusive Blog</h2>
                    <div class="exclusiveInput">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search Article">
                    </div>
                </div>
                <div class="row px-md-1" id="blogContainer">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $blogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $blog): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php
                            $image = \App\Models\MediaFiles::where('tbl_id', $blog->id)->where('type', 'blog')->first();
                        ?>

                        <div class="col-md-6 col-lg-4 col-xl-4 px-md-3 mb-4 blog-item" data-title="<?php echo e($blog->title); ?>"
                            data-meta-title="<?php echo e($blog->meta_title); ?>" data-meta-keywords="<?php echo e($blog->meta_keywords); ?>"
                            data-meta-description="<?php echo e($blog->meta_description); ?>" data-body="<?php echo e($blog->body); ?>">
                            <div class="exclusiveBox h-100">
                                <figure class="blogImg">
                                    <a href="<?php echo e(route('blog.details', ['slug' => $blog->slug])); ?>">
                                        <img src="<?php echo e(Storage::url('uploads/blog/' . $image->attachment_file)); ?>">
                                    </a>
                                </figure>
                                <?php
                                    $mainCategory = $blog->categories->firstWhere('parent_id', null);
                                    $subCategory = $blog->categories->firstWhere('parent_id', '!=', null);
                                ?>

                                <span class="techLine">
                                    <?php echo e($mainCategory?->name ?? 'Uncategorized'); ?>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($subCategory): ?>
                                        &rarr; <?php echo e($subCategory->name); ?>

                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </span>
                                <b><?php echo e($blog->title); ?></b>
                                <h4><a
                                        href="<?php echo e(route('blog.details', ['slug' => $blog->slug])); ?>"><?php echo e($blog->meta_title); ?></a>
                                </h4>
                                <p><?php echo e($blog->meta_description); ?></p>
                                <div class="blogProfile mb-3">
                                    <figure>
                                        <img src="<?php echo e(asset('frontend/images/blog-profile.jpg')); ?>" alt="">
                                    </figure>
                                    <strong class="m-0">Mittlearn</strong>
                                </div>
                                <div class="d-flex flex-wrap gap-3 courseInfo px-2">
                                    <span><img src="<?php echo e(asset('frontend/images/icon-eye.svg')); ?>" alt=""
                                            width="14"> <?php echo e($blog->views_count); ?></span>
                                    <span><img src="<?php echo e(asset('frontend/images/icon-calender.svg')); ?>" alt=""
                                            width="14"><?php echo e(dateConvert($blog->published_at, 'd, M Y')); ?> </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="customPagination mt-4">
                    <ul class="pagination">
                        <li class="page-item <?php echo e($blogs->onFirstPage() ? 'disabled' : ''); ?> previous-item">
                            <a class="page-link" href="<?php echo e($blogs->previousPageUrl()); ?>">
                                <span><img src="<?php echo e(asset('frontend/images/arrowprw.svg')); ?>" width="6"></span>
                            </a>
                        </li>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $blogs->getUrlRange(1, $blogs->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="page-item <?php echo e($page == $blogs->currentPage() ? 'active' : ''); ?>">
                                <a class="page-link" href="<?php echo e($url); ?>"><?php echo e($page); ?></a>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <li class="page-item <?php echo e($blogs->hasMorePages() ? '' : 'disabled'); ?> next-item">
                            <a class="page-link" href="<?php echo e($blogs->nextPageUrl()); ?>">
                                <span><img src="<?php echo e(asset('frontend/images/arrownxt.svg')); ?>" width="6"></span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/frontend/blogs.blade.php ENDPATH**/ ?>