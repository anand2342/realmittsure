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
            <h1><?php echo e($heading); ?> User Manual</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">User Manual</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title pb-0">User Manual Info</h5>
                            <hr class="form-divider">

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($flag == 1): ?>
                                <?php echo e(Form::model($data, ['url' => route('user-manual.save'), 'id' => 'edit-plan-form', 'class' => 'row g-3', 'files' => true])); ?>

                                <?php echo e(Form::hidden('id', null)); ?>

                            <?php else: ?>
                                <?php echo e(Form::open(['url' => route('user-manual.save'), 'id' => 'add-plan-form', 'class' => 'row g-3', 'files' => true])); ?>

                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo Form::label('title', 'Manual Title', ['class' => 'form-label required']); ?>

                                <?php echo Form::text('title', null, [
                                    'class' => 'form-control required',
                                    'placeholder' => 'Enter Title',
                                    'required',
                                ]); ?>

                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo Form::label('description', 'Manual Description', ['class' => 'form-label required']); ?>

                                <?php echo Form::text('description', null, [
                                    'class' => 'form-control required',
                                    'placeholder' => 'Enter description',
                                    'required',
                                ]); ?>

                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo Form::label('pdf_path', 'Manual PDF', ['class' => 'form-label required']); ?>

                                <small>(Allowed format: PDF only)</small>

                                <?php echo Form::file('pdf_path', ['class' => 'form-control', 'accept' => 'application/pdf']); ?>


                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($flag === 1 && isset($data->pdf_path)): ?>
                                    <div class="mt-2">
                                        <a href="<?php echo e(Storage::url('uploads/user_manuals/' . $data->pdf_path)); ?>"
                                            target="_blank" class="btn btn-outline-primary btn-sm">
                                            <i class="fa fa-file-pdf-o"></i> View PDF
                                        </a>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo Form::label('video_path', 'Manual Video', ['class' => 'form-label']); ?>

                                <small>(Allowed format: Videos only)</small>

                                <?php echo Form::file('video_path', ['class' => 'form-control', 'accept' => 'application/pdf']); ?>


                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($flag === 1 && isset($data->video_path)): ?>
                                    <div class="mt-2">
                                        <a href="<?php echo e(Storage::url('uploads/user_manuals/' . $data->video_path)); ?>"
                                            target="_blank" class="btn btn-outline-primary btn-sm">
                                            <i class="fa fa-file-video-o"></i> View Video
                                        </a>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo Form::label('visible_to_roles', 'Manual For', ['class' => 'form-label required']); ?>

                                <?php echo Form::select('visible_to_roles[]', $roles, explode(',', $data['visible_to_roles'] ?? ''), [
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

<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/admin/userManual/add.blade.php ENDPATH**/ ?>