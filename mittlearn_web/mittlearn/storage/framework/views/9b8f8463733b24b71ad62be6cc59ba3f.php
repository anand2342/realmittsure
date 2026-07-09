<div>
    <!-- Class Assignment Form -->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-0">Assign Classes</h5>
            <hr class="form-divider">

            <?php echo e(Form::open(['url' => route('d2c-content.class.update'), 'id' => 'edit-plan-form', 'class' => 'row g-3', 'enctype' => 'multipart/form-data'])); ?>


            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12" wire:ignore>
                    <?php echo Form::label('medium_id', 'Medium', ['class' => 'form-label']); ?>

                    <?php echo Form::select('medium_id[]', $medium ?? [], $selectedMediumIds ?? [], [
                        'class' => 'js-select2 form-select',
                        'multiple' => 'multiple',
                    ]); ?>

                </div>


                <div class="col-md-6 col-sm-12 col-xs-12">
                    <div class="form-group bginput mb-3 multipleSel" wire:ignore>
                        <?php echo Form::label('class', 'Assign Classes', ['class' => 'form-label required']); ?>

                        <select name="class[]" class="js-select2 form-select" multiple="multiple" placeholder="Select">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $allClasses ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($id); ?>" <?php if(in_array($id, $selectedClassIds ?? [])): ?> selected <?php endif; ?>>
                                    <?php echo e($name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                        <?php echo Form::hidden('category_id', $category_id); ?>

                        <?php echo Form::hidden('parent_category_id', $parent_category_id); ?>

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

    <!-- Course Assignment Section -->
    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-0">Digital Content Assignment</h5>
                    <hr class="form-divider">

                    <?php echo e(Form::open(['url' => route('d2c-content.courses'), 'id' => 'add-plan-form', 'class' => 'row g-3'])); ?>

                    <table class="table table-striped table-bordered align-middle text-nowrap">

                        <thead>
                            <tr>
                                <th width="15%">Medium</th>
                                <th width="15%">Class</th>
                                <th width="20%">Courses</th>
                                
                                <th width="10%">QR Code</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $existingData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php
                                    $classId = $data['class_id'];
                                    $mediumId = $data['medium_id'];
                                ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($allClasses[$classId])): ?>
                                    <tr>
                                        
                                        <input type="hidden" name="category_id" value="<?php echo e($category_id); ?>">
                                        <input type="hidden" name="parent_category_id"
                                            value="<?php echo e($parent_category_id); ?>">

                                        
                                        <td>
                                            <input type="text" class="form-control"
                                                value="<?php echo e($medium[$mediumId] ?? 'N/A'); ?>" readonly>
                                            <input type="hidden" name="medium_id[<?php echo e($key); ?>]"
                                                value="<?php echo e($mediumId); ?>">
                                        </td>

                                        
                                        <td>
                                            <input type="text" class="form-control fw-bold"
                                                value="<?php echo e($allClasses[$classId] ?? 'N/A'); ?>" readonly>
                                            <input type="hidden" name="class_id[<?php echo e($key); ?>]"
                                                value="<?php echo e($classId); ?>">
                                        </td>

                                        
                                        <td class="multipleSel" wire:ignore>
                                            <select name="course_ids[<?php echo e($key); ?>][]"
                                                class="js-select2 form-select" multiple>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $categoryCoursesPerClass[$key] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $courseId => $courseName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($courseId); ?>"
                                                        <?php echo e(in_array($courseId, $data['course_ids'] ?? []) ? 'selected' : ''); ?>>
                                                        <?php echo e($courseName); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </select>
                                        </td>

                                        
                                        <td class="text-center">
                                            <?php
                                                $filePath = $data['qr_name'] ? 'qrcodes/' . $data['qr_name'] : null;
                                                $fileExists = $filePath
                                                    ? Storage::disk('public')->exists($filePath)
                                                    : false;
                                            ?>

                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$fileExists): ?>
                                                <button wire:click="generateQrCode('<?php echo e($key); ?>')"
                                                    wire:loading.attr="disabled"
                                                    wire:target="generateQrCode('<?php echo e($key); ?>')" type="button"
                                                    class="btn btn-sm btn-primary mb-2">
                                                    <span wire:loading.remove
                                                        wire:target="generateQrCode('<?php echo e($key); ?>')">
                                                        <i class="fa fa-qrcode me-1"></i>Generate QR
                                                    </span>
                                                    <span wire:loading
                                                        wire:target="generateQrCode('<?php echo e($key); ?>')">
                                                        <span class="spinner-border spinner-border-sm"></span>
                                                        Generating...
                                                    </span>
                                                </button>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fileExists && isset($data['qr_name']) && isset($data['qr_code_link'])): ?>
                                                <div>
                                                    <div class="mb-2">
                                                        <img src="<?php echo e(asset('storage/' . $filePath)); ?>" alt="QR Code"
                                                            style="max-width: 250px;">
                                                    </div>
                                                    <a href="<?php echo e(route('qr.download', ['filename' => $data['qr_name']])); ?>"
                                                        class="btn btn-sm btn-outline-secondary d-block">
                                                        <i class="fa fa-download me-1"></i>Download QR Code
                                                    </a>

                                                    <label for="qrLink" class="form-label fw-semibold mt-2">QR Code
                                                        Link:</label>
                                                    <div class="input-group mb-2">
                                                        <input type="text" class="form-control"
                                                            id="qrLink<?php echo e($data['qr_code_link']); ?>"
                                                            value="<?php echo e($data['qr_code_link']); ?>" readonly>
                                                        <button class="btn btn-outline-primary" type="button"
                                                            id="copyButton<?php echo e($data['qr_code_link']); ?>"
                                                            title="Copy to clipboard">
                                                            <i class="fa fa-copy"></i>
                                                        </button>
                                                    </div>
                                                </div>

                                                <input type="hidden" name="qr_name[<?php echo e($key); ?>]"
                                                    value="<?php echo e($data['qr_name']); ?>">
                                                <input type="hidden" name="qr_code_link[<?php echo e($key); ?>]"
                                                    value="<?php echo e($data['qr_code_link']); ?>">
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>


                        </tbody>
                    </table>

                    <hr class="form-divider">

                    <div class="col-sm-12 text-end">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-secondary" wire:click="resetForm">Reset</button>
                    </div>
                    <?php echo e(Form::close()); ?>

                </div>
            </div>
        </div>
    </div>

    <!-- Add Courses Modal -->
    <div x-data="{ open: <?php if ((object) ('showModal') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showModal'->value()); ?>')<?php echo e('showModal'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('showModal'); ?>')<?php endif; ?> }" x-show="open" @keydown.escape.window="open = false" style="display: none;"
        x-transition>
        <div class="modal-backdrop fade show" x-show="open"></div>

        <div class="modal fade show" style="display: block;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Courses</h5>
                        <button type="button" class="btn-close" @click="open = false" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form wire:submit.prevent="addCourses" class="row g-3">
                            <!-- Parent Category -->
                            <!-- Parent Category -->
                            <div class="col-md-6 mb-3">
                                <label for="parent_id" class="form-label ">Parent Category</label>
                                <select id="parent_id" class="form-control" wire:model="parent_id"
                                    wire:change="loadSubcategories($event.target.value)">
                                    <option value="">-- Select Parent Category --</option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $parentCategroy; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($id); ?>"><?php echo e($name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </select>
                                <input type="hidden" name="selectedModalCourses" id="selectedModalCoursesHidden">

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['parent_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <small class="text-danger"><?php echo e($message); ?></small>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <!-- Sub Category -->
                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label ">Sub Category</label>
                                <select id="category_id" class="form-control" wire:model="sub_category_id"
                                    wire:change="loadCourses($event.target.value)">
                                    <option value="">-- Select Sub Category --</option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $subCategory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($id); ?>"><?php echo e($name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </select>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <small class="text-danger"><?php echo e($message); ?></small>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <!-- Courses -->
                            <div wire:ignore.self>
                                <label for="courses" class="form-label ">Courses</label>
                                <select id="courses" class="form-control js-select2" multiple>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $subCategoryCourse; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($id); ?>"
                                            <?php echo e(in_array($id, $selectedCoursesId ?? []) ? 'selected' : ''); ?>>
                                            <?php echo e($name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </select>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['selectedModalCourses'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <small class="text-danger"><?php echo e($message); ?></small>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>


                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="open = false">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const copyButtons = document.querySelectorAll('[id^="copyButton"]');

            copyButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.id.replace('copyButton', '');
                    const input = document.getElementById('qrLink' + id);

                    if (input) {
                        input.select();
                        input.setSelectionRange(0, 99999); // For mobile

                        try {
                            const successful = document.execCommand('copy');
                            if (successful) {
                                const originalHTML = this.innerHTML;
                                this.innerHTML = '<i class="fa fa-check"></i>';

                                // Revert back after 2 seconds
                                setTimeout(() => {
                                    this.innerHTML = originalHTML;
                                }, 2000);
                            }
                        } catch (err) {
                            alert('Failed to copy link. Please copy manually.');
                        }
                        window.getSelection().removeAllRanges();
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener('livewire:load', function() {
            initSelect2();

            Livewire.hook('message.processed', (message, component) => {
                initSelect2();
            });
        });


        function initSelect2() {
            $(".js-select2").select2({
                closeOnSelect: false,
                placeholder: "Select",
                allowClear: false,
                tags: true
            });

        }
        document.addEventListener("DOMContentLoaded", function() {
            initSelect2();
        });
        $('.js-select2').on('change', function(e) {
            const data = $(this).val();
            Livewire.dispatch('updateCourses', {
                selected: data
            });
        });
        document.addEventListener("change", function(event) {
            if (event.target.matches("[wire\\:model^='rows'][wire\\:model*='.series_id']")) {
                setTimeout(initSelect2, 500);
            }
        });
        document.addEventListener("click", function(event) {
            if (event.target.closest("[wire\\:click^='generateQrCode']")) {
                setTimeout(initSelect2, 500);
            }
        });
        document.addEventListener("click", function(event) {
            if (event.target.closest("[wire\\:click^='openAddCoursesModal']")) {
                setTimeout(initSelect2, 500);
            }
        });
        document.addEventListener("click", function(event) {
            if (event.target.closest("[wire\\:click^='addOtherCategoryCourses']")) {
                setTimeout(initSelect2, 500);
            }
        });
        document.addEventListener("change", function(event) {
            if (event.target.closest("[wire\\:change^='loadSubcategories']")) {
                setTimeout(initSelect2, 500);
            }
        });
        document.addEventListener("change", function(event) {
            if (event.target.closest("[wire\\:change^='loadCourses']")) {
                setTimeout(initSelect2, 500);
            }
        });
        document.addEventListener("change", function(event) {
            if (event.target.matches("[wire\\:model='coursesIds']")) {
                setTimeout(initSelect2, 1000); // Small delay to allow Livewire to update the DOM
            }
        });
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/livewire/d2-c-digital-content.blade.php ENDPATH**/ ?>