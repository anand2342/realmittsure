<?php $__env->startSection('content'); ?>
    <div class="pagetitle">
        <h1> Testimonial</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item active">Testimonial</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
                            <h4 class="card-title mb-0">All Testimonials</h4>

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

                                <a href="<?php echo e(route('testimonial.page-content.add')); ?>" class="btn btn-success">Add
                                    Testimonial</a>
                            </div>
                        </div>


                        <!-- Table with stripped rows -->
                        <div class="table-responsive tbleDiv ">
                            <table id="datatable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>S no.</th>
                                        <th>
                                            User <b>N</b>ame
                                        </th>
                                        <th>Designation</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$data): ?>
                                        <td class="text-center">No Entries Found</td>
                                    <?php else: ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $testimonial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($data->currentPage() * $data->perPage() - $data->perPage() + $loop->iteration . '.'); ?>

                                                </td>
                                                <td><?php echo e($testimonial->name); ?></td>
                                                <td><?php echo e($testimonial->designation); ?></td>
                                                <td>
                                                    <a class="btn btn-sm btn-warning"
                                                        href="<?php echo e(route('testimonial.page-content.edit', $testimonial->id)); ?>"><i
                                                            class="fa fa-pencil"></i></a>
                                                    <button class="btn btn-sm btn-danger"
                                                        onclick="confirmDelete('<?php echo e(route('testimonial.page-content.delete', $testimonial->id)); ?>')">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center">
                            <?php echo $data->links('vendor.pagination.bootstrap-4'); ?>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/admin/websitePages/testimonial/index.blade.php ENDPATH**/ ?>