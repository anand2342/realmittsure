<div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$flag): ?>
        <div class="d-md-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">User Info</h5>
        </div>
        <hr class="form-divider">
        <div class="col-md-4 col-sm-3 col-xs-12 mb-4">
            <?php echo Form::label('role', 'Role', ['class' => 'form-label required']); ?>

            <select wire:model="selectedRole" name="role" wire:change="roleChanged" class="form-control form-select fs-8"
                <?php echo e($flag ? 'disabled' : ''); ?>>
                <option value="">--Select--</option>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roleKey => $roleValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($roleKey); ?>"
                        <?php echo e(isset($selectedRole) && $roleKey == $selectedRole ? 'selected' : ''); ?>>
                        <?php echo e($roleValue); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </select>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($flag): ?>
                <?php echo e(Form::hidden('selectedRole', isset($selectedRole) ? $selectedRole->role_slug : '')); ?>

            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(
        ($selectedRole && $selectedRole === 'super_admin') ||
            (isset($selectedRole->role_slug) && $selectedRole->role_slug === 'super_admin')): ?>
        <?php echo $__env->make('admin.user.admin-form', ['viewOnly' => $viewOnly], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php elseif(
        ($selectedRole && $selectedRole === 'parent') ||
            (isset($selectedRole->role_slug) && $selectedRole->role_slug === 'parent')): ?>
        <?php echo $__env->make('admin.user.parent-form', ['viewOnly' => $viewOnly], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php elseif(
        ($selectedRole && $selectedRole === 'school_admin') ||
            (isset($selectedRole->role_slug) && $selectedRole->role_slug === 'school_admin')): ?>
        <?php echo $__env->make('admin.user.schoolAdmin-form', [
            'viewOnly' => $viewOnly,
            'classes' => $classes,
            'schoolList' => $schoolList,
            'uniqueId' => $uniqueId,
            'verify' => $verify ?? null,
            // 'academicSessions' => $academicSessions,
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php elseif(
        ($selectedRole && $selectedRole === 'school_student') ||
            (isset($selectedRole->role_slug) && $selectedRole->role_slug === 'school_student')): ?>
        <?php echo $__env->make('admin.user.student-form', [
            'viewOnly' => $viewOnly,
            'schoolList' => $schoolList,
            'sections' => $sections,
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php elseif(
        ($selectedRole && $selectedRole === 'school_teacher') ||
            (isset($selectedRole->role_slug) && $selectedRole->role_slug === 'school_teacher')): ?>
        <?php echo $__env->make('admin.user.teacher-form', [
            'viewOnly' => $viewOnly,
            'schoolList' => $schoolList,
            'data' => $userData,
        ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php elseif(
        ($selectedRole && $selectedRole === 'instructor') ||
            (isset($selectedRole->role_slug) && $selectedRole->role_slug === 'instructor')): ?>
        <?php echo $__env->make('admin.user.instructor-form', ['viewOnly' => $viewOnly, 'role' => 'instructor'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php elseif(
        ($selectedRole && in_array($selectedRole, ['leader', 'leaders'])) ||
            (isset($selectedRole->role_slug) && in_array($selectedRole->role_slug, ['leader', 'leaders']))): ?>
        <?php echo $__env->make('admin.user.leaders-form', ['viewOnly' => $viewOnly], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php elseif(
        ($selectedRole && $selectedRole === 'b2c_student') ||
            (isset($selectedRole->role_slug) && $selectedRole->role_slug === 'b2c_student')): ?>
        <?php echo $__env->make('admin.user.user-form', ['viewOnly' => $viewOnly, 'courseData' => $courseData], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php elseif(
        ($selectedRole && $selectedRole === 'salesman') ||
            (isset($selectedRole->role_slug) && $selectedRole->role_slug === 'salesman')): ?>
        <?php echo $__env->make('admin.user.instructor-form', ['viewOnly' => $viewOnly, 'role' => 'salesman'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php elseif(
        ($selectedRole && $selectedRole === 'distributors') ||
            (isset($selectedRole->role_slug) && $selectedRole->role_slug === 'distributors')): ?>
        <?php echo $__env->make('admin.user.instructor-form', ['viewOnly' => $viewOnly, 'role' => 'distributors'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php elseif(
        ($selectedRole && $selectedRole === 'd2c_user') ||
            (isset($selectedRole->role_slug) && $selectedRole->role_slug === 'd2c_user')): ?>
        <?php echo $__env->make('admin.user.d2c-user-form', ['viewOnly' => $viewOnly, 'role' => 'd2c_user'], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php elseif(isset($selectedRole) &&
            !in_array($selectedRole, [
                'super_admin',
                'parent',
                'school_admin',
                'school_student',
                'school_teacher',
                'instructor',
                'leader',
                'leaders',
                'b2c_student',
                'salesman',
            ])): ?>
        <?php echo $__env->make('admin.user.new-user-form', ['viewOnly' => $viewOnly], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

</div>
<?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/livewire/role-form.blade.php ENDPATH**/ ?>