<?php $__env->startSection('content'); ?>
    <?php
        $isEditMode = 0;
        $heading = 'Add';
        if (isset($data_row) && !empty($data_row)) {
            $isEditMode = 1;
            $heading = 'Update';
        }
    ?>

    <div>
        <div class="pagetitle">
            <h1><?php echo e($heading); ?> Courses Bucket</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Users</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isEditMode == 1): ?>
                                <?php echo e(Form::model($data_row, ['url' => route('course-bucket.save'), 'id' => 'edit-plan-form', 'class' => 'row g-3'])); ?>

                                <?php echo e(Form::hidden('id', null)); ?>

                            <?php else: ?>
                                <?php echo e(Form::open(['url' => route('course-bucket.save'), 'id' => 'add-plan-form', 'class' => 'row g-3'])); ?>

                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <h5 class="card-title pb-0">Courses Bucket Info</h5>
                            <hr class="form-divider">

                            <!-- Plan Fields -->
                            <div class="col-md-6 col-sm-3 col-xs-12">
                                <?php echo Form::label('series', 'Book Series', ['class' => 'form-label']); ?>

                                <?php echo e(Form::select('series', $series, null, ['class' => 'form-select'])); ?>

                            </div>
                            <div class="col-md-6 col-sm-3 col-xs-12">
                                <?php echo Form::label('class', 'Classes', ['class' => 'form-label']); ?>

                                <?php echo e(Form::select('class', ['all' => 'All'], null, ['class' => 'form-select', 'placeholder' => '--select--'])); ?>

                            </div>
                            <div class="col-md-6 col-sm-3 col-xs-12">
                                <?php echo Form::label('subject', 'Subject', ['class' => 'form-label']); ?>

                                <?php echo e(Form::select('subject', ['all' => 'All'], null, ['class' => 'form-select', 'placeholder' => '--select--'])); ?>

                            </div>
                            <div class="col-md-6 col-sm-3 col-xs-12">
                                <?php echo Form::label('discount_type', 'Discount Type', ['class' => 'form-label']); ?>

                                <?php echo e(Form::select('discount_type', config('constants.DISCOUNT_TYPES'), null, ['class' => 'form-select', 'placeholder' => '--select--'])); ?>

                            </div>
                            <div class="col-md-6 col-sm-3 col-xs-12">
                                <?php echo Form::label('discount_value', 'Discount Value', ['class' => 'form-label']); ?>

                                <?php echo Form::number('discount_value', null, ['class' => 'form-control', 'placeholder' => 'Enter Discount Value']); ?>

                            </div>
                            <div class="col-md-6 col-sm-3 col-xs-12">
                                <?php echo Form::label('is_active', 'Status', ['class' => 'form-label']); ?>

                                <?php echo e(Form::select('is_active', config('constants.STATUS_LIST'), null, ['class' => 'form-select'])); ?>

                            </div>
                            <div class="text-right">
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
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/admin/coursesBucket/add_edit_plan.blade.php ENDPATH**/ ?>