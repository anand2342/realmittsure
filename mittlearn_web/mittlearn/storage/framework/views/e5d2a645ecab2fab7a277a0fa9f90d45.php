<?php $__env->startSection('content'); ?>
    <?php
        $flag = 0;
        $heading = 'Add';
        if (isset($data) && !empty($data)) {
            $flag = 1;
            $heading = 'Edit';
        }
    ?>
    <div>
        <div class="pagetitle">
            <h1><?php echo e($heading); ?> Testimonial</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Testimonial</li>
                </ol>
            </nav>
        </div>
        <div class="row">
            <div class="text-end mb-2">
                <a href="<?php echo e(route('testimonial.index')); ?>" class="btn btn-primary"><i class="ri-arrow-left-line"></i></a>
            </div>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title"><?php echo e($heading); ?> Testimonial Content</h4>
                        <hr class="form-divider">

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($flag == 1): ?>
                            <?php echo e(Form::model($data, ['url' => route('testimonial.page-content.save'), 'id' => 'edit-plan-form', 'class' => 'row g-3', 'files' => true])); ?>

                            <?php echo e(Form::hidden('id', null)); ?>

                        <?php else: ?>
                            <?php echo e(Form::open(['url' => route('testimonial.page-content.save'), 'id' => 'add-plan-form', 'class' => 'row g-3', 'files' => true])); ?>

                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>


                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo Form::label('name', ' Name ', ['class' => 'form-label required']); ?>

                            <?php echo Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Enter Name']); ?>

                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo Form::label('image', ' Image ', ['class' => 'form-label']); ?>

                            <small class="form-text text-muted">(Allowed formats: PNG, PDF, SVG. Image dimensions: 50x50 pixels)</small>
                            <?php echo Form::file('image', ['class' => 'form-control']); ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($flag == 1): ?>
                                <img src="<?php echo e(Storage::url('uploads/testimonial-profile/' . $data->image)); ?>"
                                    alt="Profile Image" width="200" height="100">
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo Form::label('designation', 'Desgination', ['class' => 'form-label required']); ?>

                            <?php echo Form::text('designation', null, [
                                'class' => 'form-control',
                                'placeholder' => 'Enter Desgination',
                            ]); ?>

                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <?php echo Form::label('comment', 'Comment', ['class' => 'form-label required']); ?>

                            <?php echo Form::textarea('comment', null, [
                                'class' => 'form-control',
                                'placeholder' => 'Enter Comment',
                                'rows' => '1',
                            ]); ?>

                        </div>
                        <div class="text-end">
                            <?php echo Form::submit($flag == 1 ? 'Update' : 'Submit', ['class' => 'btn btn-primary']); ?>

                            <?php echo Form::reset('Reset', ['class' => 'btn btn-secondary']); ?>

                        </div>
                    </div>

                    <?php echo Form::close(); ?>

                </div>
            </div>
        </div>
        </section>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/admin/websitePages/testimonial/add.blade.php ENDPATH**/ ?>