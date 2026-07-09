<?php $__env->startSection('content'); ?>
    <div>
        <div class="pagetitle">
            <h1>Subscription Plans</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Subscription Plans</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h5 class="card-title">All Plans</h5>
                                </div>
                                <div class="col-sm-6 text-end mt-3">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('isPermission', 'plans.add')): ?>
                                        <a href="<?php echo e(route('plans.add')); ?>" class="btn btn-success">
                                            Add New
                                        </a>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                            <hr class="fromdivider">
                            <div class="table-responsive tbleDiv ">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Book Series</th>
                                            <th>Class</th>
                                            <th>Subject</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $datalist; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($datalist->currentPage() * $datalist->perPage() - $datalist->perPage() + $loop->iteration . '.'); ?>

                                                </td>
                                                <td><?php echo e($val->bookSeries->name); ?></td>
                                                <td><?php echo e($val->class); ?></td>
                                                <td><?php echo e($val->subject); ?></td>
                                                <td> <span
                                                        class="badge <?php echo e($val->is_active ? 'text-success' : 'text-danger'); ?>">
                                                        <?php echo e(config('constants.STATUS_LIST')[$val->is_active] ?? 'Unknown Status'); ?>

                                                    </span></td>
                                                <td>

                                                    
                                                        <a class="btn btn-sm btn-warning"
                                                            href="<?php echo e(route('course-bucket.edit', [$val->id])); ?>"><i
                                                                class="fa fa-pencil"></i></a>
                                                    
                                                    
                                                        <button class="btn btn-danger btn-sm delete_btn"
                                                            data-url="<?php echo e(route('course-bucket.delete', [$val->id])); ?>"
                                                            title="<?php echo e('Delete'); ?>"><i class="fa fa-trash"></i></button>
                                                    
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-right text-right">
                                <?php echo $datalist->links('pagination::bootstrap-4'); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/admin/coursesBucket/list_plan.blade.php ENDPATH**/ ?>