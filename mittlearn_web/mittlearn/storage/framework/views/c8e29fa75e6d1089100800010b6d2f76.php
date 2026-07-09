<?php $__env->startSection('content'); ?>
    <div class="pagetitle">
        <h1>Contact Enquiries</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item active">Enquiries</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body p-3">
                        <form method="GET" action="<?php echo e(route('enquiries')); ?>">
                            <div class="row">
                                <div class="col mb-3">
                                    <input type="text" class="form-control" placeholder="Search by Name" name="name"
                                        value="<?php echo e(request('name')); ?>">
                                </div>
                                <div class="col mb-3">
                                    <input type="text" class="form-control" placeholder="Search by Email" name="email"
                                        value="<?php echo e(request('email')); ?>">
                                </div>
                                <div class="col mb-3">
                                    <input type="text" class="form-control" placeholder="Search by Mobile" name="mobile"
                                        value="<?php echo e(request('mobile')); ?>">
                                </div>
                                <div class="col mb-3">
                                    
                                    <select name="status" class="form-select">
                                        <option value="">Search by Status</option>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = config('constants.REPLIED_STATUS'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($status); ?>"
                                                <?php echo e($status == request('status') ? 'selected' : ''); ?>>
                                                <?php echo e($label); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </select>
                                </div>
                                <div class="col mb-3">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                    <a href="<?php echo e(route('enquiries')); ?>" class="btn btn-secondary">Clear</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <h5 class="card-title mb-0">All Enquiries</h5>

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
                            </div>
                        </div>

                        <hr class="form-divider">
                        <div class="table-responsive tbleDiv ">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th><b>Name</b></th>
                                        <th><b>Email</b></th>
                                        <th><b>Mobile</b></th>
                                        <th><b>Subject</b></th>
                                        <th><b>Submitted Date</b></th>
                                        <th><b>Status</b></th>
                                        <th><b>Replied Date</b></th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $enquiry; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        
                                        <tr>
                                            <td><?php echo e($enquiry->currentPage() * $enquiry->perPage() - $enquiry->perPage() + $loop->iteration . '.'); ?>

                                            </td>
                                            <td><?php echo e($item->name); ?></td>
                                            <td><?php echo e($item->email); ?></td>
                                            <td><?php echo e($item->mobile_no); ?></td>
                                            <td><?php echo e($item->subject); ?></td>
                                            <td><?php echo e(\Carbon\Carbon::parse($item->created_at)->format('d/m/Y') ?? 'N/A'); ?>

                                            </td>
                                            <td>
                                                <span class="badge <?php echo e($item->status ? 'text-success' : 'text-danger'); ?>">
                                                    <?php echo e(config('constants.REPLIED_STATUS')[$item->status] ?? 'Unknown Status'); ?>

                                                </span>
                                            </td>
                                            <td><?php echo e(isset($item->resolved_at) ? \Carbon\Carbon::parse($item->resolved_at)->format('d/m/Y') : ' '); ?>

                                            </td>
                                            <td>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('isPermission', 'enquiry.view')): ?>
                                                    <a class="btn btn-sm btn-info"
                                                        href="<?php echo e(route('enquiry.view', $item->id)); ?>"><i
                                                            class="fa fa-eye"></i></a>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-right text-right">
                            <?php echo $enquiry->links('pagination::bootstrap-4'); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/admin/enquiries/index.blade.php ENDPATH**/ ?>