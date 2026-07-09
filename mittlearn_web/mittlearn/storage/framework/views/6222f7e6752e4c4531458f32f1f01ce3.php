<?php $__env->startSection('content'); ?>
    <div>
        <div class="pagetitle">
            <h1>Digital Content Management</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item">Digital Content Management</li>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($group === 'academic-digital-content'): ?>
                        <li class="breadcrumb-item active">Books</li>
                    <?php elseif($group === 'talent-skills'): ?>
                        <li class="breadcrumb-item active">Talent Skills</li>
                    <?php elseif($group === 'olympiad'): ?>
                        <li class="breadcrumb-item active">Olympiad</li>
                    <?php elseif($group === 'jaadui-pitara-kit'): ?>
                        <li class="breadcrumb-item active">Jaadui Pitara Kit</li>
                    <?php elseif($group === 'activity-worksheets'): ?>
                        <li class="breadcrumb-item active">Activities / Worksheets</li>
                    <?php else: ?>
                        <li class="breadcrumb-item active">Books</li>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </ol>
            </nav>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body p-3">
                            <form method="GET" action="<?php echo e(route('course.index', ['group' => $group])); ?>">
                                <div class="row">
                                    <div class="col-md-3">
                                        <input type="text" name="course_name" class="form-control"
                                            placeholder="Search by Book Name" value="<?php echo e(request('course_name')); ?>" />
                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request()->segment(count(request()->segments())) !== 'talent-skills'): ?>
                                        <div class="col-md-3">
                                            <select name="series_id" id="series_idfilter" class="form-select">
                                                <option value="">Select Series</option>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $bookSeries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($id); ?>"
                                                        <?php echo e($id == request('series_id') ? 'selected' : ''); ?>>
                                                        <?php echo e($name); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <select name="class_id" id="class_id" class="form-select">
                                                <option value="">Select Class</option>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($id); ?>"
                                                        <?php echo e($id == request('class_id') ? 'selected' : ''); ?>>
                                                        <?php echo e($name); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </select>
                                        </div>

                                        <div class="col-md-2">
                                            <select name="subject_id" id="subject_id" class="form-select">
                                                <option value="">Select Subject</option>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($id); ?>"
                                                        <?php echo e($id == request('subject_id') ? 'selected' : ''); ?>>
                                                        <?php echo e($name); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </select>
                                        </div>
                                    <?php else: ?>
                                        <div class="col-md-3">
                                            <select name="sub_category_id" class="form-select">
                                                <option value="">Select Talent-Skill Category</option>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $subcategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($id); ?>"
                                                        <?php echo e($id == request('sub_category_id') ? 'selected' : ''); ?>>
                                                        <?php echo e($name); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </select>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <div class="col-md-2">
                                        <input type="hidden" class="form-control" placeholder="Search by Generated User"
                                            name="generated_by" value="<?php echo e(request('generated_by')); ?>">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                        <a href="<?php echo e(route('course.index', ['group' => 'academic-digital-content'])); ?>"
                                            class="btn btn-secondary">Clear</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-wrap justify-content-between align-items-center">
                                <h5 class="card-title mb-0">All Books</h5>

                                <div class="d-flex align-items-center gap-2 mt-2 mt-sm-0">
                                    <label for="paginationSelectOnpage" class="me-2 mb-0">Per Page Records:</label>
                                    <select id="paginationSelectOnpage" class="form-select form-select-sm"
                                        style="width: 80px;">
                                        <option value="" disabled
                                            <?php echo e(session('per_page_records') ? '' : 'selected'); ?>>--Select--</option>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [10, 20, 30, 40, 50]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($option); ?>"
                                                <?php echo e(session('per_page_records') == $option ? 'selected' : ''); ?>>
                                                <?php echo e($option); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </select>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('isPermission', 'course.create')): ?>
                                        <a class="btn btn-success btn-sm addnew" href="<?php echo e(route('course.create')); ?>">Add
                                            New</a>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <hr class="formdivider">
                        
                        <ul class="nav nav-tabs nav-tabs-bordered mb-3">
                            <li class="nav-item">
                                <a class="nav-link <?php echo e($group === 'academic-digital-content' ? 'active' : ''); ?>"
                                    href="<?php echo e(route('course.index', 'academic-digital-content')); ?>">Books</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo e($group === 'talent-skills' ? 'active' : ''); ?>"
                                    href="<?php echo e(route('course.index', 'talent-skills')); ?>">Talent Skills</a>
                            </li>
                            
                            <li class="nav-item">
                                <a class="nav-link <?php echo e($group === 'olympiad' ? 'active' : ''); ?>"
                                    href="<?php echo e(route('course.index', 'olympiad')); ?>">Olympiad</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo e($group === 'jaadui-pitara-kit' ? 'active' : ''); ?>"
                                    href="<?php echo e(route('course.index', 'jaadui-pitara-kit')); ?>">Jaadui Pitara Kit</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo e($group === 'activity-worksheets' ? 'active' : ''); ?>"
                                    href="<?php echo e(route('course.index', 'activity-worksheets')); ?>">Activities / Worksheets</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo e($group === 'others' ? 'active' : ''); ?>"
                                    href="<?php echo e(route('course.index', 'others')); ?>">Others</a>
                            </li>
                        </ul>

                        
                        <section class="section">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($group === 'academic-digital-content'): ?>
                                                <?php echo $__env->make('admin.courses.index-academic', [
                                                    'courses' => $courses,
                                                ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                            <?php elseif($group === 'talent-skills'): ?>
                                                <?php echo $__env->make('admin.courses.index-tallent', [
                                                    'courses' => $unAcadCourses,
                                                ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                            <?php elseif($group === 'academic_activities'): ?>
                                                <?php echo $__env->make('admin.courses.index-academic', [
                                                    'courses' => $courses,
                                                ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                            <?php elseif($group === 'olympiad'): ?>
                                                <?php echo $__env->make('admin.courses.index-academic', [
                                                    'courses' => $courses,
                                                ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                            <?php elseif($group === 'jaadui-pitara-kit'): ?>
                                                <?php echo $__env->make('admin.courses.index-academic', [
                                                    'courses' => $courses,
                                                ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                            <?php elseif($group === 'activity-worksheets'): ?>
                                                <?php echo $__env->make('admin.courses.index-academic', [
                                                    'courses' => $courses,
                                                ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                            <?php elseif($group === 'others'): ?>
                                                <?php echo $__env->make('admin.courses.index-academic', [
                                                    'courses' => $courses,
                                                ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                            <?php else: ?>
                                                <?php echo $__env->make('admin.courses.index-academic', [
                                                    'courses' => $courses,
                                                ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                                
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </section>

                    </div>
                </div>
            </div>
    </div>
    </section>
    </div>
    <script>
        $(document).ready(function() {

            // When Series changes
            $('#series_idfilter').on('change', function() {
                let seriesId = $(this).val();

                $('#class_id').html('<option value="">Select Class</option>');
                $('#subject_id').html('<option value="">Select Subject</option>');

                if (seriesId) {
                    $.ajax({
                        url: '<?php echo e(url('/courses/get-classes')); ?>/' + seriesId,
                        type: 'GET',
                        success: function(response) {
                            console.log(response.classes);

                            if (response.classes) {
                                $.each(response.classes, function(id, name) {
                                    $('#class_id').append(
                                        `<option value="${id}">${name}</option>`);
                                });
                            }
                        }
                    });
                }
            });

            // When Class changes (after Series is selected)
            $('#class_id').on('change', function() {
                let classId = $(this).val();
                let seriesId = $('#series_idfilter').val();

                $('#subject_id').html('<option value="">Select Subject</option>');

                if (seriesId && classId) {
                    $.ajax({
                        url: '<?php echo e(url('/courses/get-subjects')); ?>/' + seriesId + '/' +
                            classId,
                        type: 'GET',
                        success: function(response) {
                            if (response.subjects) {
                                $.each(response.subjects, function(id, name) {
                                    $('#subject_id').append(
                                        `<option value="${id}">${name}</option>`);
                                });
                            }
                        }
                    });
                }
            });

        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/admin/courses/index.blade.php ENDPATH**/ ?>