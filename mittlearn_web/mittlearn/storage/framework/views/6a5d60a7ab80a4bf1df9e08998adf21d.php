<?php $__env->startSection('content'); ?>
    <?php
        $isEditMode = isset($course) && !empty($course);
        $heading = $isEditMode ? 'Update' : 'Add';
    ?>
    <div id="page-header" class="page-header">
        <section class="section">
            <div class="pagetitle">
                <div class="pagetitle">
                    <h1><?php echo e($heading); ?> Book/Course</h1>
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">Home</li>
                            <li class="breadcrumb-item active">Book/Course</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="row">

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h4 class="card-title"><?php echo e($heading); ?> Book/Course Info</h4>
                                </div>
                                <div class="col-sm-6 text-end mt-3">
                                    
                                    <a href="<?php echo e(route('courses.bulk-upload')); ?>" class="btn btn-success">
                                        Book/Course Bulk Upload
                                    </a>
                                    
                                </div>
                            </div>
                            <hr class="form-divider">
                            <?php echo e(Form::model($course ?? null, ['url' => route('course.store'), 'id' => $isEditMode ? 'edit-course-form' : 'add-course-form', 'class' => 'row g-3', 'files' => true])); ?>

                            <?php echo e(Form::hidden('id', $isEditMode ? $course->id : null)); ?>

                            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('courses-form', [
                                'category' => $category,
                                'modelsData' => $modelsData,
                                'course' => isset($course) ? $course : null,
                                'metadataFieldValues' => $metadataFieldValues ?? [],
                            ]);

$key = null;

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-712711853-0', null);

$__html = app('livewire')->mount($__name, $__params, $key);

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                            <?php echo Form::close(); ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('message')): ?>
                                <div class="alert alert-success mt-3">
                                    <?php echo e(session('message')); ?>

                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/admin/courses/add_edit.blade.php ENDPATH**/ ?>