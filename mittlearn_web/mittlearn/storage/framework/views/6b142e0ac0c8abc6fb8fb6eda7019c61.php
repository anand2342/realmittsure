<?php $__env->startSection('content'); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <div class="pagetitle">
        <h1>Assign Permissions</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Permissions</li>
                <li class="breadcrumb-item active">Assign</li>
            </ol>
        </nav>
    </div>
<?php $__env->stopSection(); ?>

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('assign-permission-form', ['permissions' => $permissions]);

$key = null;

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-3151996969-0', null);

$__html = app('livewire')->mount($__name, $__params, $key);

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/admin/permissions/assign.blade.php ENDPATH**/ ?>