<?php $__env->startSection('content'); ?>

    <div>
        <div class="pagetitle">
            <h1> Blogs</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Blogs</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                                <h5 class="card-title mb-0">All Blogs</h5>

                                <div class="d-flex align-items-center gap-2">
                                    <label for="paginationSelectOnpage" class="mb-0">Per Page Records:</label>
                                    <select id="paginationSelectOnpage" class="form-select form-select-sm"
                                        style="width: 80px;">
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

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('isPermission', 'blog.create')): ?>
                                        <a class="btn btn-success" href="<?php echo e(route('blog.create')); ?>">Create</a>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>

                            <hr class="formdivider">
                            <div class="table-responsive tbleDiv">
                                <table class="table table-striped table-bordered mt-4">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Title</th>
                                            <th>
                                                <b>Slug</b>
                                            </th>
                                            <th>Status</th>
                                            <th>Meta-Title</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($datas): ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $datas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e($datas->currentPage() * $datas->perPage() - $datas->perPage() + $loop->iteration . '.'); ?>

                                                    <td><?php echo e($data->title); ?></td>
                                                    <td><?php echo e($data->slug); ?></td>
                                                    <td><?php echo e($data->status); ?></td>
                                                    <td><?php echo e($data->meta_title); ?></td>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('isPermission', 'blog.edit')): ?>
                                                                <a class="btn btn-sm btn-warning "
                                                                    href="<?php echo e(route('blog.edit', $data->id)); ?>"><i
                                                                        class="fa fa-pencil"></i></a>
                                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('isPermission', 'blog.delete')): ?>
                                                                <a class="btn btn-danger btn-sm me-2" href="javascript:void(0);"
                                                                    onclick="confirmDelete('<?php echo e(route('blog.delete', $data->id)); ?>')"
                                                                    title="Delete">
                                                                    <i class="fa fa-trash"></i></a>
                                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php else: ?>
                                            <td>There is no data</td>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-right text-right">
                                <?php echo $datas->links('pagination::bootstrap-4'); ?>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>


<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/admin/blog/index.blade.php ENDPATH**/ ?>