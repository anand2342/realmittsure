<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <h5 class="card-title pb-0">User Details</h5>
            <hr class="form-divider">


            <div class="row g-3">

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?php echo Form::label('name', 'Student Name', ['class' => 'form-label required']); ?>

                    <?php echo Form::text('name', $userData->name ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Student Name',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]); ?>

                </div>


                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?php echo Form::label('email', 'Email ID', ['class' => 'form-label ', 'disabled' => $viewOnly ?? false]); ?>

                    <?php echo Form::text('email', $userData->email ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Email',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]); ?>

                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?php echo Form::label('mobile_no', 'Mobile No.', ['class' => 'form-label required', 'disabled' => $viewOnly ?? false]); ?>

                    <?php echo Form::text('mobile_no', $userData->mobile_no ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Mobile No.',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]); ?>

                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?php echo Form::label('class', 'Select Class', ['class' => 'form-label ']); ?>

                    <?php echo e(Form::select('class', $classes, $userData->studentDetails->class ?? null, [
                        'class' => 'form-select',
                        'placeholder' => '--Select--',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ])); ?>

                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?php echo Form::label('category', 'Select Category', ['class' => 'form-label ']); ?>

                    <?php echo e(Form::select('category', $categories, null, [
                        'class' => 'form-select',
                        'wire:model' => 'selectedCategory',
                        'wire:change' => 'getSubCategories($event.target.value)',
                        'placeholder' => '--Select--',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ])); ?>

                </div>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedCategory == '2'): ?>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php echo Form::label('subcategory', 'Select Sub-Category', ['class' => 'form-label ']); ?>

                        <?php echo e(Form::select('subcategory', $subCategories, null, [
                            'class' => 'form-select',
                            'wire:model' => 'selectedSubCategory',
                            'wire:change' => 'getTalentCourses($event.target.value)',
                            'placeholder' => '--Select--',
                            'disabled' => $viewOnly ? 'disabled' : null,
                        ])); ?>

                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedCategory == '1' || $selectedSubCategory): ?>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <?php echo Form::label('course_id', 'Select Course', ['class' => 'form-label ']); ?>

                        <select name="course_id[]" class="js-select2 form-select" ,
                            <?php if($viewOnly): ?> disabled <?php endif; ?> , multiple="multiple"
                            placeholder="Select">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($id); ?>">
                                    <?php echo e($name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <?php echo Form::label('password', 'Password', ['class' => 'form-label required ']); ?>

                    <?php echo Form::text('password', $userData->validate_string ?? 'Mitt@123', [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Password',
                    ]); ?>

                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($courseData->isNotEmpty()): ?>
                    <div class="col-md-12 col-sm-6 col-xs-12">
                        <h5>Courses</h5>
                        <div class="courses-container mt-2">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $courseData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="assigned-course-badge">
                                    <?php echo e($course['name']); ?>

                                    <a
                                     
                                        onclick="confirmCourseDelete('<?php echo e(route('delete.course', [
                                            'course_id' => $course['id'],
                                            'user_id' => $userData->id ?? null,
                                        ])); ?>')"
                                        class="delete-course-link">
                                        <i class="bi bi-x"></i>
                                    </a>
                                </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>


                <div class="col-sm-12 text-end">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="reset" class="btn btn-secondary" onclick="window.location.reload();">Reset</button>
                </div>
            </div>

        </div>
    </div>
    </div>
</section>
<?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/admin/user/user-form.blade.php ENDPATH**/ ?>