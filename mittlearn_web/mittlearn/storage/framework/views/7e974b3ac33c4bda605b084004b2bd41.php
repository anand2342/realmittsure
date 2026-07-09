    
    <?php $__env->startSection('content'); ?>
        <?php echo $__env->make('admin.layouts.flash-messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo e(route('sp.lesson.planner')); ?>">Lesson Plan</a></li>
                <li class="breadcrumb-item active" aria-current="page">Class Subjects</li>
            </ol>
        </nav>
        <div class="cardBox">
            <div class="d-md-flex justify-content-between align-items-center mb-3">
                <div class="headingList my-3 mb-4">
                    <figure class="m-0">
                        <img src="<?php echo e(asset('frontend/images/subject-list-icon.svg')); ?>" alt="" width="36">
                    </figure>
                    <div>
                        <h2 class="fs-6 fw-semibold m-0"><?php echo e($className); ?> - <?php echo e(count($subjects)); ?> Subject List</h2>
                        <p> Explore the subjects with detailed digital content, assignments, and interactive materials
                            to enhance your learning.</p>
                    </div>
                </div>
            </div>

            <div class="row px-md-1">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col-md-4 col-lg-3 mb-3 px-md-2">
                        <div class="languageBox subjectListDiv h-100 postion-relative pt-0">
                            <h6 class="dataName mb-3"><?php echo e($data->name); ?> </h6>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($data->book_cover_image || $data->thumbnail_image): ?>
                                <a href="#imagesModal-<?php echo e($index); ?>" data-bs-toggle="modal">
                                    <img src="<?php echo e(Storage::url($data->book_cover_image ? $data->book_cover_image : $data->thumbnail_image)); ?>"
                                        class="cornerImg">
                                </a>
                                <!--Megnify Image  Modal -->
                                <div class="modal fade imagesModal" id="imagesModal-<?php echo e($index); ?>">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                            <div class="modal-body">
                                                <img src="<?php echo e(Storage::url($data->book_cover_image ? $data->book_cover_image : $data->thumbnail_image)); ?>"
                                                    class="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <div class=""
                                    style="height: 225px;display:flex; justify-content:center;align-items:center;">
                                    <img src="<?php echo e(asset('images/mittlearn-favicon.png')); ?>" class="">
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <p style="min-height:20px" class="fw-semibold text-center mt-2"><?php echo e($data->course_name ?? ' '); ?>

                            </p> <!-- Course Name -->
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(config('COURSES_FILTER_BY_ACCESS_CODE') == 1): ?>
                                <span><b>Total Access Code</b><?php echo e(count($accessCodes)); ?> </span>
                                <span class="text-primary"><b>Total Book Purchased</b><?php echo e(count($accessCodes)); ?> </span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <div class="d-xxl-flex justify-content-between gap-2 align-items-center">
                                <a href="<?php echo e(route('sp.lesson.planner.course.listing', [$data->id, $classId])); ?>"
                                    class="btn btn-primary-gradient rounded-1 py-2 mt-2 w-100">Explore</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>


                
            </div>
        </div>
    <?php $__env->stopSection(); ?>

<?php echo $__env->make('schoolPortal.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/schoolPortal/lessonPlanner/class-subject.blade.php ENDPATH**/ ?>