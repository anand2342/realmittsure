<?php $__env->startSection('content'); ?>
    

    <?php
        $flag = 0;
        $heading = 'Add';
        if (isset($teacherData) && !empty($teacherData)) {
            $flag = 1;
            $heading = 'Edit';
        }
    ?>

    <!-- Card for Teacher Form -->
    <div class="cardBox teacherMain py-md-4  mb-3">
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="teacherLeft">
                    <h5 class="fw-semibold"><?php echo e($heading); ?> Teacher</h5>
                    <p>Easily add, edit, and bulk upload teacher information to keep your records accurate and up to date.
                    </p>


                </div>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($flag != 1): ?>
                <div class="col-md-6 mb-3">
                    <h6 class="">Bulk Upload Teachers</h6>
                    
                    <div class="col-md-12">
                        <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('school-bulk-upload', ['roles' => $roles, 'roleName' => 'school_teacher']);

$key = null;

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-960327509-0', null);

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
            <h5 class="mb-3"><?php echo e($heading); ?> Teacher</h5>

            <!-- Form Start -->
            <?php echo e(Form::open(['url' => route('sp.teacher.save'), 'id' => 'add-plan-form', 'class' => 'row g-3'])); ?>

            <?php echo e(Form::hidden('role', 'school_teacher')); ?>

            <?php echo e(Form::hidden('id', $teacherData->id ?? null, ['id' => 'teacher_id_field'])); ?>


            <!-- Name Field -->
            <div class="col-md-3">
                <div class="form-group bginput mb-2">
                    <?php echo Form::label('name', 'Name', ['class' => 'form-label']); ?> <b>*</b>
                    <?php echo Form::text('name', old('name', $teacherData->name ?? null), [
                        'class' => 'form-control' . ($errors->has('name') ? ' is-invalid' : ''),
                        'placeholder' => 'Enter here',
                    ]); ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <!-- Gender Field -->
            <div class="col-md-3">
                <div class="form-group bginput mb-2">
                    <?php echo Form::label('gender', 'Gender', ['class' => 'form-label']); ?>

                    <?php echo e(Form::select(
                        'gender',
                        config('constants.GENDER'),
                        old('gender', $teacherData->userAdditionalDetail->gender ?? null),
                        [
                            'class' => 'form-select' . ($errors->has('gender') ? ' is-invalid' : ''),
                            'placeholder' => 'Select',
                        ],
                    )); ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['gender'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
            <!-- DOB Field -->
            <div class="col-md-3">
                <div class="form-group mb-2">
                    <?php echo Form::label('dob', 'DOB', ['class' => 'form-label']); ?>

                    <?php echo Form::date(
                        'dob',
                        old(
                            'dob',
                            isset($teacherData->userAdditionalDetail->dob)
                                ? \Carbon\Carbon::parse($teacherData->userAdditionalDetail->dob)->format('Y-m-d')
                                : null,
                        ),
                        [
                            'class' => 'form-control dateInput' . ($errors->has('dob') ? ' is-invalid' : ''),
                            'placeholder' => 'DOB',
                        ],
                    ); ?>


                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['dob'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <!-- Email Field -->
            <div class="col-md-3">
                <div class="form-group bginput mb-2">
                    <?php echo Form::label('email', 'Enter Email', ['class' => 'form-label']); ?> <b>*</b>
                    <?php echo Form::text('email', old('email', $teacherData->email ?? null), [
                        'class' => 'form-control email' . ($errors->has('email') ? ' is-invalid' : ''),
                        'placeholder' => 'Enter here',
                    ]); ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <!-- Mobile Number Field -->
            <div class="col-md-3">
                <div class="form-group bginput mb-2">
                    <?php echo Form::label('mobile_no', 'Mobile No.', ['class' => 'form-label']); ?> <b>*</b>
                    <?php echo Form::number('mobile_no', old('mobile_no', $teacherData->mobile_no ?? null), [
                        'class' => 'form-control mobile' . ($errors->has('mobile_no') ? ' is-invalid' : ''),
                        'placeholder' => 'Enter here',
                    ]); ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['mobile_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <!-- Age Field -->
            <div class="col-md-3">
                <div class="form-group bginput mb-2">
                    <?php echo Form::label('age', 'Age', ['class' => 'form-label']); ?>

                    <?php echo Form::number('age', old('age', $teacherData->userAdditionalDetail->age ?? null), [
                        'class' => 'form-control' . ($errors->has('age') ? ' is-invalid' : ''),
                        'placeholder' => 'Enter here',
                    ]); ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['age'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <!-- Address Field -->
            <div class="col-md-3">
                <div class="form-group mb-2">
                    <?php echo Form::label('address', 'Address', ['class' => 'form-label']); ?>

                    <?php echo Form::textarea('address', old('address', $teacherData->userAdditionalDetail->address ?? null), [
                        'class' => 'form-control' . ($errors->has('address') ? ' is-invalid' : ''),
                        'placeholder' => 'Enter here',
                        'rows' => 1,
                    ]); ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <!-- State Field -->
            <div class="col-md-3">
                <div class="form-group bginput mb-2">
                    <?php echo Form::label('state', 'State', ['class' => 'form-label']); ?>

                    <?php echo e(Form::select('state', $states, old('state', $teacherData->userAdditionalDetail->state ?? null), [
                        'class' => 'form-select' . ($errors->has('state') ? ' is-invalid' : ''),
                        'placeholder' => 'Select',
                        'id' => 'state-select',
                    ])); ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['state'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
            <!-- City Field -->
            <div class="col-md-3">
                <div class="form-group bginput mb-2">
                    <?php echo Form::label('city', 'City', ['class' => 'form-label']); ?>

                    <?php echo e(Form::select('city', [], old('city', $teacherData->userAdditionalDetail->city ?? null), [
                        'class' => 'form-select' . ($errors->has('city') ? ' is-invalid' : ''),
                        'placeholder' => 'Select',
                        'id' => 'city-select',
                    ])); ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['city'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>

            <!-- Qualification Field -->
            <div class="col-md-3">
                <div class="form-group bginput mb-2">
                    <?php echo Form::label('qualification', 'Qualification', ['class' => 'form-label']); ?>

                    <?php echo Form::text(
                        'qualification',
                        old('qualification', $teacherData->userAdditionalDetail->qualification ?? null),
                        [
                            'class' => 'form-control qualification' . ($errors->has('qualification') ? ' is-invalid' : ''),
                            'placeholder' => 'Enter here',
                        ],
                    ); ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['qualification'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
            <!-- Experience Field -->
            <div class="col-md-3">
                <div class="form-group mb-2">
                    <?php echo Form::label('experience', 'Experience', ['class' => 'form-label']); ?>

                    <?php echo Form::text('experience', old('experience', $teacherData->userAdditionalDetail->experience ?? null), [
                        'class' => 'form-control experience' . ($errors->has('experience') ? ' is-invalid' : ''),
                        'placeholder' => 'Enter here',
                    ]); ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['experience'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
            <!-- Class Assignment Field -->
            <div class="col-md-4">
                <div class="form-group mb-2">
                    <label class ="form-label">Assign Class <b>*</b></label>
                    <ul class="typeCheckList">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>
                                <div class="typeCheck">
                                    <input type="checkbox" id="class_<?php echo e($key); ?>" name="class[]"
                                        value="<?php echo e($key); ?>" class="d-none"
                                        <?php echo e(in_array($key, old('class', explode(',', $teacherData->userAdditionalDetail->assigned_classes ?? ''))) ? 'checked' : ''); ?>>
                                    <label for="class_<?php echo e($key); ?>">
                                        <i class="bi bi-check-lg"></i><?php echo e($item); ?>

                                    </label>
                                </div>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </ul>
                    
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['class'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="text-danger d-block" style="font-size: 0.875em;"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
            <!-- Subject Assignment Field -->
            <div class="col-md-4">
                <div class="form-group bginput mb-2">
                    <?php echo Form::label('subject', 'Assign Subject', ['class' => 'form-label']); ?> <b>*</b>
                    <select name="subject[]" class="js-select2 form-select" multiple="multiple" placeholder="Select">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($id); ?>"
                                <?php echo e(in_array($id, old('subject', explode(',', $teacherData->userAdditionalDetail->assigned_subjects ?? ''))) ? 'selected' : ''); ?>>
                                <?php echo e($name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback d-block"><?php echo e($message); ?></div> 
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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

<?php echo $__env->make('schoolPortal.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/schoolPortal/user/teacher-add-edit.blade.php ENDPATH**/ ?>