<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <h5 class="card-title pb-0">Teacher Details</h5>
            <hr class="form-divider">
            <div class="row g-3">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?php echo Form::label('school_id', 'School Name', ['class' => 'form-label required']); ?>

                    <?php echo Form::select('school_id', $schools, $userData->userAdditionalDetail->school_id ?? null, [
                        'class' => 'form-select',
                        'wire:model' => 'selectedSchool',
                        'wire:change' => 'schoolChanged($event.target.value)',
                        'disabled' => $viewOnly ? 'disabled' : null,
                        'placeholder' => '--Select--',
                    ]); ?>

                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?php echo Form::label('name', 'Name', ['class' => 'form-label required ']); ?>

                    <?php echo Form::text('name', $data->name ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter First Name',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]); ?>

                </div>

                
                

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?php echo Form::label('gender', 'Gender', ['class' => 'form-label ']); ?>

                    <?php echo e(Form::select('gender', config('constants.GENDER'), $userData->userAdditionalDetail->gender ?? null, ['class' => 'form-select', 'placeholder' => '--Select--', 'disabled' => $viewOnly ? 'disabled' : null])); ?>

                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?php echo Form::label('age', 'Age', ['class' => 'form-label  ']); ?>

                    <?php echo Form::text('age', $userData->userAdditionalDetail->age ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Age',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]); ?>

                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?php echo Form::label('email', 'Email', ['class' => 'form-label required']); ?>

                    <?php echo Form::text('email', $data->email ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Email',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]); ?>

                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?php echo Form::label('mobile_no', 'Mobile No.', ['class' => 'form-label required']); ?>

                    <?php echo Form::text('mobile_no', $data->mobile_no ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Mobile No.',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]); ?>

                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?php echo Form::label('address', 'Address', ['class' => 'form-label ']); ?>

                    <?php echo Form::text('address', $userData->userAdditionalDetail->address ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Address',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]); ?>

                </div>

                


                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?php echo Form::label('state', 'State', ['class' => 'form-label ']); ?>


                    <?php echo e(Form::select('state', $states, old('state', $userData->userAdditionalDetail->state ?? null), [
                        'class' => 'form-select' . ($errors->has('state') ? ' is-invalid' : ''),
                        'placeholder' => 'Select',
                        'id' => 'state-select',
                        'wire:model' => 'selectedState', // Livewire binding
                        'wire:change' => 'stateChanged($event.target.value)',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ])); ?>

                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?php echo Form::label('city', 'City', ['class' => 'form-label ']); ?>


                    <?php echo e(Form::select('city', $cities, old('city', null), [
                        'class' => 'form-select',
                        'placeholder' => 'Select',
                        'id' => 'city-select',
                        'wire:model' => 'city', // Livewire binding
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ])); ?>

                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?php echo Form::label('qualification', 'Qualification', ['class' => 'form-label ']); ?>

                    <?php echo Form::text('qualification', $userData->userAdditionalDetail->qualification ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Qualification',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]); ?>

                </div>
                
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?php echo Form::label('dob', 'Dob', ['class' => 'form-label ']); ?>

                    <?php echo Form::date('dob', $userData->userAdditionalDetail->dob ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter dob',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]); ?>

                </div>


                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group bginput mb-3">
                        <?php echo Form::label('class', 'Assign Classes', ['class' => 'form-label required']); ?>

                        <select name="class[]" class="js-select2 form-select" ,
                            <?php if($viewOnly): ?> disabled <?php endif; ?> , multiple="multiple"
                            placeholder="Select">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($loadClasses)): ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $loadClasses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($id); ?>"
                                        <?php if(in_array($id, $selectedTeacherClasses)): ?> selected <?php endif; ?>>
                                        <?php echo e($name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group bginput mb-3">
                        <?php echo Form::label('subject', 'Assign Subjects', ['class' => 'form-label required']); ?>

                        <select name="subject[]" class="js-select2 form-select" ,
                            <?php if($viewOnly): ?> disabled <?php endif; ?> , multiple="multiple"
                            placeholder="Select">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($id); ?>" <?php if(in_array($id, $selectedTeacherSubjects)): ?> selected <?php endif; ?>>
                                    <?php echo e($name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?php echo Form::label('experience', 'Experience', ['class' => 'form-label ']); ?>

                    <?php echo Form::text('experience', $userData->userAdditionalDetail->experience ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Experience',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]); ?>

                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?php echo Form::label('password', 'Password', ['class' => 'form-label required ']); ?>

                    <?php echo Form::text('password', $userData->validate_string ?? 'Mitt@123', [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Password',
                    ]); ?>

                </div>

            </div>
            <div class="col-sm-12 text-end">
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="reset" class="btn btn-secondary" onclick="window.location.reload();">Reset</button>
            </div>
        </div>
    </div>
</section>
<?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/admin/user/teacher-form.blade.php ENDPATH**/ ?>