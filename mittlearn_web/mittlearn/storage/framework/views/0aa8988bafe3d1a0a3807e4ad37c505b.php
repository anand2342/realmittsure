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
            <h1><?php echo e($heading); ?> Subscription Plan</h1>
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
                                <?php echo e(Form::model($data_row, ['url' => route('plans.save'), 'id' => 'edit-plan-form', 'class' => 'row g-3'])); ?>

                                <?php echo e(Form::hidden('id', null)); ?>

                            <?php else: ?>
                                <?php echo e(Form::open(['url' => route('plans.save'), 'id' => 'add-plan-form', 'class' => 'row g-3'])); ?>

                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <h5 class="card-title pb-0">Plan Info</h5>
                            <hr class="form-divider">

                            <!-- Plan Fields -->
                            <div class="col-md-4 col-sm-3 col-xs-12">
                                <?php echo Form::label('name', 'Plan Name', ['class' => 'form-label required']); ?>

                                <?php echo Form::text('name', null, [
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter plan name',
                                    'required',
                                    'id' => 'vallidateName',
                                ]); ?>

                                <small id="vallidateNameError" class="form-text text-danger mt-1"
                                    style="display:none;"></small>
                            </div>

                            

                            

                            <div class="col-md-4 col-sm-3 col-xs-12 d-none">
                                <?php echo Form::label('currency', 'Currency', ['class' => 'form-label']); ?>

                                <?php echo e(Form::select('currency', getCurrencyList(), null, ['class' => 'form-select'])); ?>

                            </div>

                            <div class="col-md-4 col-sm-3 col-xs-12">
                                <?php echo Form::label('description', 'Description', ['class' => 'form-label']); ?>

                                <?php echo Form::textarea('description', null, [
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter plan description',
                                    'rows' => 1,
                                ]); ?>

                            </div>

                            <div class="col-md-4 col-sm-3 col-xs-12">
                                <?php echo Form::label('sort_order', 'Sort Order', ['class' => 'form-label']); ?>

                                <?php echo Form::number('sort_order', null, ['class' => 'form-control', 'placeholder' => 'Enter sort order']); ?>

                            </div>

                            <div class="col-md-4 col-sm-3 col-xs-12">
                                <?php echo Form::label('status', 'Status', ['class' => 'form-label']); ?>

                                <?php echo e(Form::select('status', config('constants.STATUS_LIST'), null, ['class' => 'form-select'])); ?>

                            </div>

                            

                            <div class="col-md-12 col-sm-12 col-xs-12"></div>

                            <div class="col-md-4 col-sm-3 col-xs-12">
                                <?php echo Form::checkbox('is_free_trial', 1, $isEditMode && $data_row->is_free_trial ? true : false); ?>

                                <?php echo Form::label('is_free_trial', 'Is Free Trial', ['class' => 'form-label']); ?>

                            </div>

                            <div class="col-md-4 col-sm-3 col-xs-12">
                                <?php echo Form::checkbox('is_recomanded', 1, $isEditMode && $data_row->is_recomanded ? true : false); ?>

                                <?php echo Form::label('is_recomanded', 'Is Recommended', ['class' => 'form-label']); ?>

                            </div>

                            <hr />

                            <!-- Plan Benefits -->
                            

                            <!-- Plan Benefits -->
                            <h4>Features</h4>
                            <hr class="form-divider">
                            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('subscription-plan-features-form', ['plan_data' => $isEditMode ? $data_row : null]);

$key = null;

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-1156356667-0', null);

$__html = app('livewire')->mount($__name, $__params, $key);

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

                            <!-- Plan Packs -->
                            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('subscription-plan-pack-form', ['plan_data' => $isEditMode ? $data_row : null]);

$key = null;

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-1156356667-1', null);

$__html = app('livewire')->mount($__name, $__params, $key);

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

                            <!-- Plan Benefits -->
                            
                            

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

<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/admin/plans/add_edit_plan.blade.php ENDPATH**/ ?>