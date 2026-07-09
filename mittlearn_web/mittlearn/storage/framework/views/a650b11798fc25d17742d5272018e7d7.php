<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <?php
                $heading =
                    $role == 'salesman'
                        ? 'Relationship Manager(RM)'
                        : ($role == 'distributors'
                            ? 'Distributor'
                            : 'Instructor');
            ?>
            <h5 class="card-title pb-0"><?php echo e($heading); ?> Details</h5>
            <hr class="form-divider">

            <div class="row g-3">

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?php echo Form::label('name', 'Name', ['class' => 'form-label required ']); ?>

                    <?php echo Form::text('name', $userData->name ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Name',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]); ?>

                </div>

                
                

                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($role == 'salesman'): ?>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php echo Form::label('employee_id', 'Employee ID', ['class' => 'form-label ']); ?>

                        <?php echo Form::text('employee_id', $userData->userAdditionalDetail->employee_id ?? null, [
                            'class' => 'form-control',
                            'placeholder' => 'Enter Employee ID',
                            'disabled' => $viewOnly ? 'disabled' : null,
                        ]); ?>

                    </div>
                <?php elseif($role == 'distributors'): ?>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php echo Form::label('distributor_id', 'Distributor ID', ['class' => 'form-label ']); ?>

                        <?php echo Form::text('distributor_id', $userData->userAdditionalDetail->distributor_id ?? null, [
                            'class' => 'form-control',
                            'placeholder' => 'Enter Distributor ID',
                            'disabled' => $viewOnly ? 'disabled' : null,
                        ]); ?>

                    </div>
                <?php elseif($role == 'instructor'): ?>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php echo Form::label('about', 'About Instructor', ['class' => 'form-label ']); ?>

                        <?php echo Form::text('about', $userData->userAdditionalDetail->about ?? null, [
                            'class' => 'form-control',
                            'placeholder' => 'Enter About Instructor',
                            'disabled' => $viewOnly ? 'disabled' : null,
                        ]); ?>

                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php echo Form::label('designation', 'Instructor Post/Designation', ['class' => 'form-label ']); ?>

                        <?php echo Form::text('designation', $userData->userAdditionalDetail->designation ?? null, [
                            'class' => 'form-control',
                            'placeholder' => 'Enter Instructor Post/Designation',
                            'disabled' => $viewOnly ? 'disabled' : null,
                        ]); ?>

                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>


                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($role == 'instructor'): ?>
                        <?php echo Form::label('email', 'Email', ['class' => 'form-label']); ?>

                    <?php else: ?>
                        <?php echo Form::label('email', 'Email', ['class' => 'form-label required']); ?>

                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php echo Form::text('email', $userData->email ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Email',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]); ?>

                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($role == 'instructor'): ?>
                        <?php echo Form::label('mobile_no', 'Mobile No.', ['class' => 'form-label ']); ?>

                    <?php else: ?>
                        <?php echo Form::label('mobile_no', 'Mobile No.', ['class' => 'form-label required']); ?>

                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php echo Form::text('mobile_no', $userData->mobile_no ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Mobile No.',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]); ?>

                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?php echo Form::label('address', 'Address', ['class' => 'form-label']); ?>

                    <?php echo Form::text('address', $userData->userAdditionalDetail->address ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Address',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]); ?>

                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($role == 'instructor'): ?>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php echo Form::label('image', 'Profile Image', ['class' => 'form-label required']); ?>

                        <?php echo Form::file('image', ['class' => 'form-control', 'disabled' => $viewOnly ? 'disabled' : null]); ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($flag === 1): ?>
                            <img src="<?php echo e(Storage::url('uploads/user/profile_image/' . $userData->image)); ?>"
                                alt="image" width="200" height="100">
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?php echo Form::label('state', 'State', ['class' => 'form-label']); ?>


                    <?php echo e(Form::select('state', $states, old('state', $userData->userAdditionalDetail->state ?? null), [
                        'class' => 'form-select' . ($errors->has('state') ? ' is-invalid' : ''),
                        'placeholder' => 'Select',
                        'wire:model' => 'selectedState',
                        'wire:change' => 'stateChanged($event.target.value)',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ])); ?>

                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?php echo Form::label('city', 'City', ['class' => 'form-label']); ?>


                    <?php echo e(Form::select('city', $cities, old('city', null), [
                        'class' => 'form-select',
                        'placeholder' => 'Select',
                        'wire:model' => 'city', // Livewire binding
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ])); ?>

                </div>

                

                

                <div class="col-sm-12 text-end">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="reset" class="btn btn-secondary" onclick="window.location.reload();">Reset</button>
                </div>
            </div>
        </div>
    </div>
</section>
<?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/admin/user/instructor-form.blade.php ENDPATH**/ ?>