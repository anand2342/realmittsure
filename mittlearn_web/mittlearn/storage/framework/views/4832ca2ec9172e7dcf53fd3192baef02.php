<?php $__env->startSection('content'); ?>
    <div class="pagetitle">
        <h1>Planners</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item active">Planners</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body p-3">
                                <form method="GET" action="<?php echo e(route('planner.index')); ?>">
                                    <div class="row">

                                        <div class="col mb-3">
                                            <select class="form-control" name="academic_session"
                                                id="academicSessionDropdown">
                                                <option value="" disabled selected>Select Academic Session</option>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $academicSession; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $session): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($session); ?>"
                                                        <?php echo e(request('academic_session') == $session ? 'selected' : ''); ?>>
                                                        <?php echo e($session); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </select>
                                        </div>

                                        <div class="col mb-3">
                                            <select class="form-control" name="batch" id="batchDropdown">
                                                <option value="" disabled selected>Select Batch</option>
                                            </select>
                                        </div>

                                        <div class="col mb-3">
                                            <select class="form-control" name="series">
                                                <option value="" disabled selected>Select Book Series</option>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $series; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($id); ?>"
                                                        <?php echo e(request('series') == $id ? 'selected' : ''); ?>><?php echo e($name); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </select>
                                        </div>
                                        <div class="col mb-3">
                                            <select class="form-control" name="class">
                                                <option value="" disabled selected>Select Class</option>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($id); ?>"
                                                        <?php echo e(request('class') == $id ? 'selected' : ''); ?>>
                                                        <?php echo e($name); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </select>
                                        </div>
                                        <div class="col mb-3">
                                            <select class="form-control" name="subject">
                                                <option value="" disabled selected>Select Subject</option>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $subject; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($id); ?>"
                                                        <?php echo e(request('subject') == $id ? 'selected' : ''); ?>>
                                                        <?php echo e($name); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </select>
                                        </div>

                                        <div class="col-md-3 mb-1">
                                            <input type="hidden" class="form-control"
                                                placeholder="Search by Generated User" name="generated_by"
                                                value="<?php echo e(request('generated_by')); ?>">
                                            <button type="submit" class="btn btn-primary">Search</button>
                                            <a href="<?php echo e(route('planner.index')); ?>" class="btn btn-secondary">Clear</a>
                                        </div>
                                    </div>
                                    
                                </form>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="col-sm-6">
                                        <div class="card-title">All Planners</div>
                                    </div>
                                    <div class="col-sm-6 text-end mt-3">
                                        <div class="d-flex justify-content-end align-items-center gap-2 flex-wrap">
                                            <div class="d-flex align-items-center">
                                                <label for="roles" class="me-2 mb-0">Per Page Records:</label>
                                                <select id="paginationSelectOnpage" class="form-select form-select-sm"
                                                    style="width: 80px;">
                                                    <option value="" disabled
                                                        <?php echo e(session('per_page_records') ? '' : 'selected'); ?>>
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
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('isPermission', 'planner.create')): ?>
                                                <a href="<?php echo e(route('planner.create')); ?>" class="btn btn-success">
                                                    Add New
                                                </a>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <hr class="formdivider">
                                <div class="table-responsive tbleDiv ">
                                    <table class="table table-striped table-bordered">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th><b>Chapter Title</b></th>
                                                <th><b>Batch Name</b></th>
                                                <th><b>Board</b></th>
                                                <th><b>Medium</b></th>
                                                <th>Series</th>
                                                <th>Class</th>
                                                <th>Subject</th>
                                                <th>Allotted Days</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $data; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e($data->currentPage() * $data->perPage() - $data->perPage() + $loop->iteration . '.'); ?>

                                                    </td>
                                                    <td><?php echo e(implode(', ', $item->chapter_names ?? [])); ?></td>
                                                    <td><?php echo e($item->batch->batch_name ?? 'N/A'); ?></td>
                                                    <td><?php echo e($item->board->name); ?></td>
                                                    <td><?php echo e($item->medium->name); ?></td>
                                                    <td><?php echo e($item->series->name); ?></td>
                                                    <td><?php echo e($item->class->name); ?></td>
                                                    <td><?php echo e($item->subject->name); ?></td>
                                                    <td><?php echo e($item->allotted_days); ?></td>

                                                    <td>
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('isPermission', 'planner.view')): ?>
                                                            <a class="btn btn-sm btn-info "
                                                                href="<?php echo e(route('planner.view', $item->id)); ?>">Edit & View</a>
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('isPermission', 'planner.delete')): ?>
                                                            <a class="btn btn-sm btn-danger"
                                                                onclick="confirmDelete('<?php echo e(route('planner.delete', $item->id)); ?>')">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-right text-right">
                                    <?php echo $data->appends(request()->query())->links('pagination::bootstrap-4'); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        const getBatchRouteTpl = "<?php echo e(route('academic-session.get-batch', ['name' => 'SESSION_NAME'])); ?>";

        function loadBatches(sessionName, selectedBatchId = null) {
            const url = getBatchRouteTpl.replace('SESSION_NAME', encodeURIComponent(sessionName));
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const batchDropdown = document.getElementById('batchDropdown');
                    batchDropdown.innerHTML = '<option value="" disabled selected>Select Batch</option>';

                    if (Array.isArray(data.batches)) {
                        data.batches.forEach(batch => {
                            const option = document.createElement('option');
                            option.value = batch.id;
                            option.textContent = batch.batch_name;

                            if (selectedBatchId && batch.id == selectedBatchId) {
                                option.selected = true;
                            }
                            batchDropdown.appendChild(option);
                        });
                    }
                })
                .catch(error => {
                    console.error('Error fetching batches:', error);
                });

        }
        document.addEventListener('DOMContentLoaded', function() {
            const academicSessionDropdown = document.getElementById('academicSessionDropdown');
            const selectedSession = academicSessionDropdown?.value;
            const selectedBatch = "<?php echo e(request('batch')); ?>";

            if (selectedSession) {
                loadBatches(selectedSession, selectedBatch);
            }

            academicSessionDropdown?.addEventListener('change', function() {
                loadBatches(this.value);
            });
        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/admin/plannerManagement/index.blade.php ENDPATH**/ ?>