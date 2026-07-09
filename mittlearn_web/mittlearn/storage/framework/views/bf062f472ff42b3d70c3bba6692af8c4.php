<?php $__env->startSection('content'); ?>
    

    <?php
        $flag = 0;
        $heading = 'Add';
        if (isset($studentData) && !empty($studentData)) {
            $flag = 1;
            $heading = 'Edit';
        }
    ?>

    <!-- Card for Teacher Form -->
    <div class="cardBox teacherMain py-md-4  mb-3">
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="teacherLeft">
                    <h5 class="fw-semibold"><?php echo e($heading); ?> Student</h5>
                    <p>Easily add, edit, and bulk upload Student information to keep your records accurate and up to date.
                    </p>


                </div>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($flag != 1): ?>
                <div class="col-md-6 mb-3">
                    <h6 class="">Bulk Upload Student</h6>
                    
                    <div class="col-md-12">
                        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('school-bulk-upload', ['roles' => $roles, 'roleName' => 'school_student']);

$key = null;

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-1421024607-0', null);

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
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>


    <!-- Card for Add Teacher Form -->
    <div class="cardBox teacherMain py-md-4 mb-3">
        <div class="formPanel">
            <h5 class="mb-3"><?php echo e($heading); ?> Student</h5>

            <!-- Form Start -->
            <?php echo e(Form::open(['url' => route('sp.student.save'), 'id' => 'add-plan-form', 'class' => 'row g-3'])); ?>

            <?php echo e(Form::hidden('role', 'school_student')); ?>

            <?php echo e(Form::hidden('id', $studentData->id ?? null, ['id' => 'student_id_field'])); ?>


            <div class="formPanel">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group bginput mb-3">
                            <?php echo Form::label('admission_no', 'Admission No.'); ?>

                            <?php echo Form::text('admission_no', old('admission_no', $studentData->userAdditionalDetail->admission_no ?? null), [
                                'class' => 'form-control qualification ' . ($errors->has('admission_no') ? 'is-invalid' : ''),
                                'placeholder' => 'Enter here',
                            ]); ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['admission_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <?php echo Form::label('admission_date', 'Admission Date'); ?>

                            <?php echo Form::date('admission_date', $studentData->studentDetails->doj ?? null, [
                                'class' => 'form-control  dateInput' . ($errors->has('admission_date') ? 'is-invalid' : ''),
                            ]); ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['admission_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group bginput mb-3">
                            <?php echo Form::label('name', 'Name'); ?> <b>*</b>
                            <?php echo Form::text('name', old('name', $studentData->name ?? null), [
                                'class' => 'form-control ' . ($errors->has('name') ? 'is-invalid' : ''),
                                'placeholder' => 'Enter here',
                            ]); ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group bginput mb-3">
                            <?php echo Form::label('parent_name', 'Parent Name'); ?>

                            <?php echo Form::text('parent_name', old('parent_name', $studentData->studentDetails->parent_name ?? null), [
                                'class' => 'form-control ' . ($errors->has('parent_name') ? 'is-invalid' : ''),
                                'placeholder' => 'Enter here',
                            ]); ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['parent_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group bginput mb-3">
                            <?php echo Form::label('email', 'Email'); ?>

                            <?php echo Form::text('email', old('email', $studentData->email ?? null), [
                                'class' => 'form-control ' . ($errors->has('email') ? 'is-invalid' : ''),
                                'placeholder' => 'Enter here',
                            ]); ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <?php echo Form::label('dob', 'DOB'); ?>

                            <?php echo Form::date('dob', old('dob', $studentData->studentDetails->dob ?? null), [
                                'class' => 'form-control dateInput ' . ($errors->has('dob') ? 'is-invalid' : ''),
                                'id' => 'date-input',
                                'placeholder' => 'Select date',
                            ]); ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['dob'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(getUserRoles() == 'school_teacher'): ?>
                        <div class="col-md-4">
                            <div class="form-group bginput mb-3">
                                <?php echo Form::label('class', 'Select Class'); ?> <b>*</b>
                                <?php echo Form::select('class', $teacherClasses, old('class', $studentData->studentDetails->class ?? null), [
                                    'class' => 'form-select ' . ($errors->has('class') ? 'is-invalid' : ''),
                                    'placeholder' => 'Select',
                                ]); ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['class'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="col-md-4">
                            <div class="form-group bginput mb-3">
                                <?php echo Form::label('class', 'Select Class'); ?> <b>*</b>
                                <?php echo Form::select('class', $classes, old('class', $studentData->studentDetails->class ?? null), [
                                    'class' => 'form-select ' . ($errors->has('class') ? 'is-invalid' : ''),
                                    'placeholder' => 'Select',
                                ]); ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['class'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <span class="invalid-feedback"><?php echo e($message); ?></span>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <div class="col-md-4">
                        <div class="form-group bginput mb-3">
                            <?php echo Form::label('section', 'Select Section'); ?>

                            <?php echo Form::select('section', $sections, old('section', $studentData->studentDetails->section ?? null), [
                                'class' => 'form-select ' . ($errors->has('section') ? 'is-invalid' : ''),
                                'placeholder' => 'Select',
                            ]); ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['section'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group bginput mb-3">
                            <?php echo Form::label('parent_mobile_no', 'Parent/Guardian Mobile No.'); ?> <b>*</b>
                            <?php echo Form::text('parent_mobile_no', old('parent_mobile_no', $studentData->mobile_no ?? null), [
                                'class' => 'form-control mobile ' . ($errors->has('parent_mobile_no') ? 'is-invalid' : ''),
                                'placeholder' => 'Enter here',
                            ]); ?>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['parent_mobile_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="invalid-feedback"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="offcanvas-footer">
                <div class="d-flex align-items-center justify-content-end gap-4">
                    <a href="<?php echo e(url()->previous()); ?>" class="btn backbtn">Back</a>
                    <button type="Submit" class="btn btn-primary-gradient rounded-1">Submit</button>
                </div>
            </div>

            <?php echo e(Form::close()); ?>

        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#edit-teacher-btn').on('click', function() {
                var teacherId = $(this).data('id');
                var stateId = $(this).data('state');
                var cityId = $(this).data('city');

                $('#state-select').val(stateId);
                loadCities(stateId, cityId); // Pass cityId properly
            });

            $('#state-select').on('change', function() {
                var stateId = $(this).val();
                if (stateId) {
                    loadCities(stateId, null); // No pre-selected city on state change
                } else {
                    $('#city-select').html('<option value="">Select</option>');
                }
            });

            function loadCities(stateId, preSelectedCity) {
                if (!stateId) {
                    $('#city-select').html('<option value="">Select</option>');
                    return;
                }

                var url = "<?php echo e(route('sp.getCities', ':state')); ?>".replace(':state', stateId);

                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(data) {
                        $('#city-select').html('<option value="">Select</option>');

                        if (data && Object.keys(data).length > 0) {
                            $.each(data, function(id, name) {
                                var isSelected = (parseInt(id) === parseInt(preSelectedCity)) ?
                                    'selected' : '';
                                $('#city-select').append('<option value="' + id + '" ' +
                                    isSelected + '>' + name + '</option>');
                            });

                            // After appending, if preSelectedCity exists but not matched exactly by ID comparison,
                            // force set the selected value
                            if (preSelectedCity) {
                                $('#city-select').val(preSelectedCity);
                            }
                        } else {
                            $('#city-select').html('<option value="">No cities available</option>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log("Error loading cities:", error);
                    }
                });
            }

            // Automatically load cities if state already selected on page load
            var initialStateId = $('#state-select').val();
            var initialCityId = "<?php echo e(old('city', $teacherData->userAdditionalDetail->city ?? null)); ?>";

            if (initialStateId) {
                loadCities(initialStateId, initialCityId);
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('schoolPortal.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/schoolPortal/user/student-add-edit.blade.php ENDPATH**/ ?>