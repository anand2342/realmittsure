<?php $__env->startSection('content'); ?>
    <div>
        <div class="aboutMain">
            <div class="">
                <div class="item">
                    <img src="<?php echo e(asset('frontend/images/sliderOne.png')); ?>" alt="">
                </div>
            </div>
            <div class="container">
                <div class="bannerTxt">
                    <div class="sliderTxt">
                        <h3><?php echo e($terms->title); ?></h3>
                        <p><?php echo e($terms->meta_title); ?></p>
                    </div>
                </div>
            </div>

        </div>
        <div class="technoSection">
            <div class="container">
                <div class="section-heading">
                    <h2><span class="greenBorder"></span>
                </div>
                <div class="row align-items-center">
                    <?php echo $terms->description; ?>

                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/frontend/terms-and-conditions.blade.php ENDPATH**/ ?>