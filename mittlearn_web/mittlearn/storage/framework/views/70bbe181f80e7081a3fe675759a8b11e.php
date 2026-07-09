<?php $__env->startSection('content'); ?>
<?php echo $__env->make('admin.layouts.flash-messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="cardBox">
        <div class="d-md-flex justify-content-between align-items-center mb-3">
            <h2 class="fs-6 fw-semibold mb-3">Lesson Plan</h2>
            
        </div>
        <div class="row px-md-1">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $classCourses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-4 col-lg-3 mb-3 px-md-2">
                    <div class="exploreBox h-100">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <figure class="m-0 position-relative">
                                <span
                                    class="rounded-circle d-inline-flex justify-content-center align-items-center first-circle">
                                    <span class="rounded-circle second-circle">
                                        <?php echo e(substr($data->class->name ?? 'N/A', 0, 1)); ?>

                                    </span>
                                </span>
                            </figure>

                            <span><?php echo e($data->class->name ?? 'N/A'); ?></span>
                        </div>
                        <a href="<?php echo e(route('sp.lesson.planner.subjects', $data->class_id)); ?>" class="btn-explore">Explore
                            <lottie-player src="<?php echo e(asset('frontend/images/right-blue.json')); ?>" loop autoplay
                                style="width: 20px;height: 20px;"></lottie-player></a>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
    <script>
        document.getElementById('mediumFilter').addEventListener('change', function() {
            var selectedValue = this.value;

            // Check if the selected value is empty, if so remove the query parameter from URL
            var url = new URL(window.location.href);
            if (selectedValue) {
                url.searchParams.set('medium', selectedValue); // Add or update the 'medium' query parameter
            } else {
                url.searchParams.delete('medium'); // Remove the 'medium' query parameter if empty
            }

            // Redirect to the new URL with the selected filter
            window.location.href = url.toString();
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('schoolPortal.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/schoolPortal/lessonPlanner/index.blade.php ENDPATH**/ ?>