<div class="row g-3">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('message')): ?>
        <div class="alert alert-success">
            <?php echo e(session('message')); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <div wire:ignore.self>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $courseSets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $set): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="row mb-2" wire:key="course-set-<?php echo e($index); ?>">
                <?php echo Form::hidden("course_sets[$index][id]", $set['id'] ?? null); ?>


                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?php echo Form::label("course_sets[{$index}][series_id]", 'Series', ['class' => 'form-label required']); ?>


                    <select class="form-control" name="course_sets[<?php echo e($index); ?>][series_id]"
                        wire:model="courseSets.<?php echo e($index); ?>.series_id" x-data="{ index: <?php echo e($index); ?> }"
                        x-on:change="$wire.getSeriesId($event.target.value, index)">
                        <option value="">Select Series</option>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $series; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>"><?php echo e($value); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>
                </div>


                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?php echo Form::label("course_sets[$index][classes_ids]", 'Class', ['class' => 'form-label required']); ?>

                    <select name="course_sets[<?php echo e($index); ?>][classes_ids][]" class="js-select2 form-select"
                        multiple x-data x-init="initSelect2($el, window.Livewire.find('<?php echo e($_instance->getId()); ?>').entangle('courseSets.<?php echo e($index); ?>.classes_ids'))">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $classOptions[$index] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($id); ?>"
                                <?php echo e(in_array($id, $set['classes_ids'] ?? []) ? 'selected' : ''); ?>>
                                <?php echo e($name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>
                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($index > 0): ?>
                    <div class="col-md-12 mt-2 text-end">
                        <button type="button" class="btn btn-sm btn-danger remove-course-btn"
                            data-index="<?php echo e($index); ?>" wire:click="removeCourseSet(<?php echo e($index); ?>)">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        <div class="text-end">
            <button type="button" class="btn btn-sm btn-primary " wire:click="addCourseSet">Add More</button>
        </div>
    </div>


    <script>
        function initSelect2() {
            $(".js-select2").select2({
                closeOnSelect: false,
                placeholder: "Select",
                allowClear: false,
                tags: true
            });
        }

        document.addEventListener("change", function(event) {
            if (event.target.matches("[wire\\:model^='courseSets.'][wire\\:model$='.series_id']")) {
                setTimeout(initSelect2, 500); // Short delay to allow DOM updates
            }
        });
        document.addEventListener("click", function(event) {
            if (event.target.closest(".remove-course-btn")) {
                setTimeout(() => {
                    initSelect2(); // optional reinitialization
                }, 500);
            }
        });
    </script>



</div>
<?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/livewire/frontend-courses.blade.php ENDPATH**/ ?>