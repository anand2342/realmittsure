<?php $__env->startSection('content'); ?>
    <?php
        $flag = 0;
        $heading = 'Add';
        if (isset($data) && !empty($data)) {
            $flag = 1;
            $heading = 'Update';
        }
    ?>
    <div>
        <div class="pagetitle">
            <h1><?php echo e($heading); ?> Alert Notifications</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Alert Notifications</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title pb-0">Alert Notification Info</h5>
                            <hr class="form-divider">

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($flag == 1): ?>
                                <?php echo e(Form::model($data, ['url' => route('flash.notification.alerts.save'), 'id' => 'edit-plan-form', 'class' => 'row g-3', 'files' => true])); ?>

                                <?php echo e(Form::hidden('id', null)); ?>

                            <?php else: ?>
                                <?php echo e(Form::open(['url' => route('flash.notification.alerts.save'), 'id' => 'add-plan-form', 'class' => 'row g-3', 'files' => true])); ?>

                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo Form::label('image', 'Marketing Banner', ['class' => 'form-label required']); ?>

                                <small>
                                    (Allowed formats: PNG, JPG, JPEG, SVG , GIF and Videos. dimensions: 300x250 pixels)
                                </small>
                                <?php echo Form::file('image', [
                                    'class' => 'form-control',
                                ]); ?>


                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($flag === 1 && isset($data->marketing_banner)): ?>
                                    <?php
                                        $file = $data->marketing_banner;
                                        $extension = pathinfo($file, PATHINFO_EXTENSION);
                                        $videoExtensions = [
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
                                            '3gp',
                                            'm2ts',
                                            'ogv',
                                            'ts',
                                            'mxf',
                                            'ogg',
                                        ];
                                    ?>

                                    <div class="mt-2">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array(strtolower($extension), $videoExtensions)): ?>
                                            <video width="200" height="100" autoplay loop muted playsinline
                                                class="img-thumbnail">
                                                <source src="<?php echo e(Storage::url('uploads/marketing_banner/' . $file)); ?>"
                                                    type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        <?php else: ?>
                                            <img src="<?php echo e(Storage::url('uploads/marketing_banner/' . $file)); ?>"
                                                alt="Marketing Banner" class="img-thumbnail" width="200" height="100">
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo Form::label('message', 'Alert Message', ['class' => 'form-label required']); ?>

                                <?php echo Form::text('message', $data->message ?? null, [
                                    'class' => 'form-control required',
                                    'placeholder' => 'Enter Alert Message',
                                    'id' => 'vallidateName',
                                    'required',
                                    'maxlength' => '200',
                                ]); ?>

                                <small id="vallidateNameError" class="form-text text-danger mt-1"
                                    style="display:none;"></small>
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo Form::label('redirection_url', 'Redirection URL', ['class' => 'form-label ']); ?>

                                <?php echo Form::text('redirection_url', $data->redirection_url ?? null, [
                                    'class' => 'form-control required',
                                    'placeholder' => 'Enter URL',
                                ]); ?>

                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo Form::label('role_visibility', 'Alert Visibility To', ['class' => 'form-label required']); ?>

                                <?php echo Form::select('role_visibility[]', $roles, explode(',', $data['role_visibility'] ?? ''), [
                                    'placeholder' => '--Select Roles--',
                                    'class' => 'js-select2 form-select',
                                    'multiple' => 'multiple',
                                    'required',
                                ]); ?>

                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo Form::label('is_active', 'Status', ['class' => 'form-label required ']); ?>

                                <?php echo Form::select('is_active', config('constants.STATUS_LIST'), null, [
                                    'class' => 'form-control form-select fs-8 ',
                                    'placeholder' => '--Select--',
                                    'required',
                                ]); ?>

                            </div>

                            <div class="col-sm-12 text-end">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="reset" class="btn btn-secondary"
                                    onclick="window.location.reload();">Reset</button>
                            </div>

                            <?php echo e(Form::close()); ?>


                        </div>
                    </div>
                </div>
            </div>
        </section>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const messageInput = document.getElementById('vallidateName');
                const errorMessage = document.getElementById('vallidateNameError');
                const maxLength = 200;

                messageInput.addEventListener('input', function() {
                    const currentLength = messageInput.value.length;
                    if (currentLength > maxLength) {
                        errorMessage.textContent = 'You cannot write more than 200 characters.';
                        errorMessage.style.display = 'block';
                        messageInput.value = messageInput.value.substring(0,
                            maxLength);
                    } else {
                        errorMessage.style.display = 'none';
                    }
                });
            });

            $(document).ready(function() {
                $(".js-select2").select2({
                    closeOnSelect: false,
                    placeholder: "Select",
                    allowClear: false,
                    tags: true
                });

            });
        </script>
    <?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/admin/notificationFlashAlerts/add.blade.php ENDPATH**/ ?>