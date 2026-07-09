<div>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-0">School Assigned Classes</h5>
            <hr class="form-divider">

            <?php echo e(Form::model($id, ['url' => route('school.assigned.class.update'), 'id' => 'edit-plan-form', 'class' => 'row g-3', 'enctype' => 'multipart/form-data'])); ?>

            <?php echo e(Form::hidden('id', $id)); ?>


            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group bginput mb-3">
                        <?php echo Form::label('class', 'Assign Classes', ['class' => 'form-label required']); ?>

                        <select name="class[]" class="js-select2 form-select" multiple="multiple" placeholder="Select">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $allClasses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $iD => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($iD); ?>" <?php if(in_array($iD, $assignedClasses)): ?> selected <?php endif; ?>>
                                    <?php echo e($name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12 col-md-12 col-xs-12">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>

            <?php echo e(Form::close()); ?>

        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <?php echo e(Form::open(['url' => route('school.assign.digital.content.save'), 'id' => 'add-plan-form', 'class' => 'row g-3'])); ?>

            <?php echo e(Form::hidden('school_id', $id)); ?>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-0">Digital Content Assignment</h5>
                    <hr class="form-divider">

                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th width="25%">Class</th>
                                <th width="22%">BookSeries</th>
                                <th width="48%">Subject</th>
                                <th width="5%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $classId => $className): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php echo e(Form::hidden('id[' . $classId . ']', $existingData[$classId]['id'] ?? '')); ?>

                                <tr>
                                    <td width="25%">
                                        <?php echo e(Form::text('class_name[' . $classId . ']', $className ?? 'N/A', ['class' => 'form-control', 'readonly' => 'readonly'])); ?>

                                        <?php echo e(Form::hidden('class_id[' . $classId . ']', $classId)); ?>

                                    </td>

                                    <td colspan="3">
                                        <table class="table">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $rows[$classId]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr wire:key="row-<?php echo e($classId); ?>-<?php echo e($index); ?>">
                                                    <td width="25%">
                                                        <?php echo e(Form::select("series_id[$classId][$index]", $bookSeries, $row['series_id'], [
                                                            'class' => 'form-select ',
                                                            'placeholder' => '--Select--',
                                                            'wire:model' => "rows.$classId.$index.series_id",
                                                            'wire:change' => "fetchSubjects($classId, \$event.target.value, $index)", // Pass index here
                                                        ])); ?>

                                                    </td>
                                                    <td width="45%">
                                                        <select x-data="select2" class="js-select2 form-select"
                                                            multiple="multiple" placeholder="Select"
                                                            name="subject[<?php echo e($classId); ?>][<?php echo e($index); ?>][]"
                                                            wire:model="rows.<?php echo e($classId); ?>.<?php echo e($index); ?>.subject_ids">
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $subjects[$classId][$index] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <option value="<?php echo e($id); ?>"
                                                                    <?php echo e(in_array($id, $row['subject_ids']) ? 'selected' : ''); ?>>
                                                                    <?php echo e($name); ?>

                                                                </option>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        </select>
                                                    </td>

                                                    <td width="5%">
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($index != 0): ?>
                                                            <button type="button"
                                                                wire:click="removeRow(<?php echo e($classId); ?>, <?php echo e($index); ?>)"
                                                                class="btn btn-danger btn-sm">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                        </table>

                                        <div class="text-end">
                                            <button type="button" wire:click="addRow(<?php echo e($classId); ?>)"
                                                class="btn btn-primary btn-sm">
                                                Add More
                                            </button>
                                        </div>
                                    </td>

                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </tbody>
                    </table>

                    <hr class="form-divider">

                    <div class="col-sm-12 text-end">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="reset" class="btn btn-secondary"
                            onclick="window.location.reload();">Reset</button>
                    </div>
                </div>
            </div>

            <?php echo e(Form::close()); ?>

        </div>
    </div>

</div>
<?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/livewire/school-digital-content.blade.php ENDPATH**/ ?>