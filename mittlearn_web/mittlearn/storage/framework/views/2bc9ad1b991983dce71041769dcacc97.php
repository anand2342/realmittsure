<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('admin.layouts.flash-messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="row px-lg-1">
        <div class="col-lg-12 px-lg-2 mb-3">
            <div class="cardBox adminBx h-100">
                <div class="">
                    <h5 class="fw-semibold">Download Mittlearn App </h5>
                    <p>
                        Get the best learning experience on your mobile device. Download the app from your preferred store
                        below.
                    </p>
                </div>
                <img src="<?php echo e(asset('frontend/images/admin-img.png')); ?>" alt="" width="200">
            </div>
        </div>
        <div class="row justify-content-center">
            <!-- Play Store Section -->
            <div class="col-md-6 mb-4 mt-2">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="headerTbl d-flex align-items-center gap-2">
                            <h6 class="m-0"><?php echo e($setting['play_heading'] ?? 'N/A'); ?></h6>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($setting['play_logo'])): ?>
                                <img src="<?php echo e(Storage::url('uploads/logo/' . $setting['play_logo'])); ?>" alt="Google Play"
                                    style="height: 50px;">
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <p class="mt-2"><?php echo e($setting['play_description'] ?? 'N/A'); ?></p>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($setting['play_image'])): ?>
                            <div class="mb-3">
                                <img src="<?php echo e(Storage::url('uploads/logo/' . $setting['play_image'])); ?>"
                                    alt="App Store QR Code" class="img-fluid" style="max-height: 150px;">
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <a href="<?php echo e($setting['play_link'] ?? 'N/A'); ?>" target="_blank" class="btn btn-success px-4">
                            Download on Play Store
                        </a>
                    </div>
                </div>
            </div>

            <!-- App Store Section -->
            <div class="col-md-6 mb-4 mt-2">
                <div class="card shadow-sm h-100">
                    <div class="card-body text-center">
                        <div class="headerTbl d-flex align-items-center gap-2">
                            <h6 class="m-0"><?php echo e($setting['app_heading'] ?? 'N/A'); ?></h6>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($setting['app_logo'])): ?>
                                <img src="<?php echo e(Storage::url('uploads/logo/' . $setting['app_logo'])); ?>" alt="Google Play"
                                    style="height: 50px;">
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                        <p class="mt-2"><?php echo e($setting['app_description'] ?? 'N/A'); ?></p>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($setting['app_image'])): ?>
                            <div class="mb-3">
                                <img src="<?php echo e(Storage::url('uploads/logo/' . $setting['app_image'])); ?>"
                                    alt="App Store QR Code" class="img-fluid" style="max-height: 150px;">
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <a href="<?php echo e($setting['app_link'] ?? 'N/A'); ?>" target="_blank" class="btn btn-primary px-4">
                            Download on App Store
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('schoolPortal.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/schoolPortal/download-app.blade.php ENDPATH**/ ?>