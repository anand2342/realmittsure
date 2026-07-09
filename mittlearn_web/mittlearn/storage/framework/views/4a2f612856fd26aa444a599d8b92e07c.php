<?php $__env->startSection('content'); ?>
    <div class="pagetitle">
        <h1> Flash Alert Notifications</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item active">Flash Alert Notifications</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <h5 class="card-title mb-0">All Flash Alert Notifications</h5>

                            <div class="d-flex align-items-center gap-2">
                                <label for="paginationSelectOnpage" class="mb-0">Per Page Records:</label>
                                <select id="paginationSelectOnpage" class="form-select form-select-sm" style="width: 80px;">
                                    <option value="" disabled <?php echo e(session('per_page_records') ? '' : 'selected'); ?>>
                                        --Select--
                                    </option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [10, 20, 30, 40, 50]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($option); ?>"
                                            <?php echo e(session('per_page_records') == $option ? 'selected' : ''); ?>>
                                            <?php echo e($option); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </select>

                                
                                <a href="<?php echo e(route('flash.notification.alerts.add')); ?>" class="btn btn-success">
                                    Add New
                                </a>
                                
                            </div>
                        </div>

                        <hr class="formdivider">
                        <div class="table-responsive tbleDiv ">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th><b>Notification Message</b></th>
                                        <th><b>Visible To</b></th>
                                        <th><b>Created Date</b></th>
                                        <th><b>Status</b></th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($data->currentPage() * $data->perPage() - $data->perPage() + $loop->iteration . '.'); ?>

                                            </td>
                                            <td><?php echo e($item->message); ?></td>
                                            
                                            <td><?php echo e(implode(', ', $item->visible_role_names)); ?></td>
                                            <td><?php echo e($item->created_at->format('d/m/Y')); ?></td>
                                            <td>
                                                <span class="badge <?php echo e($item->is_active ? 'text-success' : 'text-danger'); ?>">
                                                    <?php echo e(config('constants.STATUS_LIST')[$item->is_active] ?? 'Unknown Status'); ?>

                                                </span>
                                            </td>
                                            <td>
                                                
                                                <a class="btn btn-sm btn-warning"
                                                    href="<?php echo e(route('flash.notification.alerts.edit', $item->id)); ?>"><i
                                                        class="fa fa-pencil"></i></a>
                                                
                                                
                                                <a class="btn btn-danger btn-sm me-2" href="javascript:void(0);"
                                                    onclick="confirmDelete('<?php echo e(route('flash.notification.alerts.delete', $item->id)); ?>')">
                                                    <i class="fa fa-trash"></i></a>
                                                
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-right text-right">
                            <?php echo $data->links('pagination::bootstrap-4'); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/admin/notificationFlashAlerts/index.blade.php ENDPATH**/ ?>