<?php $__env->startSection('content'); ?>
    <div>
        <div class="pagetitle">
            <h1>Talent Box</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Talent Box</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body p-3">
                            <form method="GET" action="<?php echo e(route('course.complimentary.index')); ?>">
                                <div class="row">
                                    <div class="col-md-3">
                                        <input type="text" name="course_name" class="form-control"
                                            placeholder="Search by Course Name" value="<?php echo e(request('course_name')); ?>" />
                                    </div>
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
                                    <div class="col-md-2">
                                        <input type="hidden" class="form-control" placeholder="Search by Generated User"
                                            name="generated_by" value="<?php echo e(request('generated_by')); ?>">
                                        <button type="submit" class="btn btn-primary">Search</button>
                                        <a href="<?php echo e(route('course.complimentary.index', ['group' => 'academic-digital-content'])); ?>"
                                            class="btn btn-secondary">Clear</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title">All Talent Box</h5>
                                <div class="d-flex align-items-center">
                                    <label for="roles" class="me-2">Per Page Record:</label>
                                    <select id="paginationSelectOnpage" class="form-select form-select-sm"
                                        style="width: 80px;">
                                        <option value="" disabled <?php echo e(session('per_page_records') ? '' : 'selected'); ?>>
                                            --Select--</option>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [10, 20, 30, 40, 50]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($option); ?>"
                                                <?php echo e(session('per_page_records') == $option ? 'selected' : ''); ?>>
                                                <?php echo e($option); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </select>
                                </div>
                                <div id="assign-btn-container" style="display: none;">
                                    <a class="btn btn-sm statusBtn btn-primary" data-toggle="modal"
                                        data-target="#assignSchools">
                                        Assign To Schools
                                    </a>
                                </div>
                            </div>
                            <hr class="formdivider">
                            <div class="table-responsive tbleDiv ">
                                <table id="datatable" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="select-all"> All</th> <!-- Master Checkbox -->
                                            <th>S.No.</th>
                                            <th>Sub-Group </th>
                                            <th>Course Name</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td>
                                                    <input type="checkbox" name="course_ids[]" value="<?php echo e($course->id); ?>"
                                                        class="course-checkbox" id="course-<?php echo e($course->id); ?>">
                                                </td>
                                                <td><?php echo e($courses->firstItem() + $loop->index); ?>.</td>
                                                <td><span><?php echo e($course->subCategory->name ?? ' '); ?></span></td>
                                                <td class="d-flex justify-content-between align-items-center">
                                                    <span><?php echo e($course->course_name); ?></span>
                                                    <a class="btn btn-success btn-sm text-end ms-auto"
                                                        href="<?php echo e(route('course.add.chapter', $course->id)); ?>"
                                                        title="Edit">
                                                        Manage Chapters
                                                    </a>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('isPermission', 'course.edit')): ?>
                                                            <a class="btn btn-warning btn-sm me-2"
                                                                href="<?php echo e(route('course.edit', $course->id)); ?>" title="Edit">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('isPermission', 'course.activate')): ?>
                                                            <a class="btn btn-sm statusBtn <?php echo e($course->is_active == 1 ? 'btn-success' : 'btn-danger'); ?> me-2"
                                                                href="javascript:void(0);"
                                                                onclick="confirmStatus('<?php echo e(route('course.activate', $course->id)); ?>', <?php echo e($course->is_active); ?>)">
                                                                <?php echo e($course->is_active == 1 ? 'Active' : 'Inactive'); ?>

                                                            </a>
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                                        
                                                    </div>
                                                </td>
                                            </tr>

                                            <!-- Modal -->
                                            <div class="modal fade" id="assignModal-<?php echo e($course->id); ?>" tabindex="-1"
                                                role="dialog" aria-labelledby="assignModalLabel-<?php echo e($course->id); ?>"
                                                aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title"
                                                                id="assignModalLabel-<?php echo e($course->id); ?>">
                                                                Assign Complimentary Courses to School
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>

                                                        <?php echo Form::open(['route' => 'complimentary.course.store', 'method' => 'POST']); ?>

                                                        <?php echo csrf_field(); ?>

                                                        <div class="modal-body">
                                                            <?php echo Form::hidden('course_id', $course->id); ?>

                                                            <div class="row">
                                                                <div class=" col-md-6 form-group">
                                                                    <?php echo Form::label('state_id', 'Select State', ['class' => 'form-label required']); ?>

                                                                    <?php echo Form::select('state_id', $state ?? [], null, [
                                                                        'class' => 'form-select state-select',
                                                                        'id' => 'state-select',
                                                                        'placeholder' => 'Select State',
                                                                    ]); ?>

                                                                </div>
                                                                <div class="col-md-6 form-group">
                                                                    <?php echo Form::label('district', 'District', ['class' => 'form-label required']); ?>

                                                                    <?php echo e(Form::select('district', $cities ?? [], null, [
                                                                        'class' => 'form-select city-select',
                                                                        'placeholder' => 'Select',
                                                                        'id' => 'city-select',
                                                                    ])); ?>

                                                                </div>
                                                            </div>
                                                            <div class="form-group mt-3">
                                                                <?php echo Form::label('school_id', 'Select School', ['class' => 'form-label required']); ?>

                                                                <?php echo Form::select('school_id[]', [], null, [
                                                                    'class' => 'js-select2 form-select',
                                                                    'multiple' => 'multiple',
                                                                ]); ?>

                                                            </div>
                                                        </div>

                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary"
                                                                data-dismiss="modal">Close</button>
                                                            <button type="submit" class="btn btn-primary">Submit</button>
                                                        </div>
                                                        <?php echo Form::close(); ?>

                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </tbody>
                                </table>

                            </div>
                            <div class="d-flex justify-content-right text-right">
                                <?php echo $courses->links('pagination::bootstrap-4'); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal for Assigning Schools -->
            <div class="modal fade" id="assignSchools" tabindex="-1" role="dialog" aria-labelledby="assignSchools"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="assignSchools">
                                Assign Telant Box Complimentary Courses to Schools
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>

                        <?php echo Form::open(['route' => 'complimentary.course.store', 'method' => 'POST']); ?>

                        <?php echo csrf_field(); ?>

                        <div class="modal-body">
                            <?php echo Form::hidden('course_ids', '', ['id' => 'course-ids']); ?> <!-- Hidden input to store selected course IDs -->
                            <div class="row mt-2">
                                <div class=" col-md-6 form-group">
                                    <?php echo Form::label('state_id', 'State', ['class' => 'form-label required']); ?>

                                    <?php echo Form::select('state_id', $state ?? [], null, [
                                        'class' => 'form-select state-select',
                                        'id' => 'state-select',
                                        'placeholder' => 'Select State',
                                        'required',
                                    ]); ?>

                                </div>
                                <div class=" col-md-6 form-group">
                                    <?php echo Form::label('district', 'District', ['class' => 'form-label']); ?>

                                    <?php echo e(Form::select('district', $cities ?? [], null, [
                                        'class' => 'form-select city-select',
                                        'placeholder' => 'Select District',
                                        'id' => 'city-select',
                                    ])); ?>

                                </div>
                            </div>

                            <div class="form-group mt-4">
                                <?php echo Form::label('school_id', 'Select School', ['class' => 'form-label required']); ?>

                                <?php echo Form::select('school_id[]', [], null, [
                                    'class' => 'js-select2 form-select',
                                    'multiple' => 'multiple',
                                    'required',
                                ]); ?>

                            </div>
                        </div>

                        <div class="modal-footer mt-3 mb-2">
                            
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </div>
                        <?php echo Form::close(); ?>

                    </div>
                </div>
            </div>
        </section>
    </div>
    <script>
        $(".js-select2").select2({
            closeOnSelect: false,
            placeholder: "--Select--",
            allowClear: false,
            tags: true
        });
    </script>
    <script>
        $(document).ready(function() {
            // Handle state change and update cities dropdown
            $('.state-select').on('change', function() {
                var stateId = $(this).val();
                $('.city-select').html('<option value="">Select</option>'); // Reset city options
                if (stateId) {
                    var url = "<?php echo e(route('getCities', ':state')); ?>".replace(':state', stateId);
                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function(data) {
                            if (data && Object.keys(data).length > 0) {
                                $.each(data, function(id, name) {
                                    $('.city-select').append('<option value="' + id +
                                        '">' + name + '</option>');
                                });
                            } else {
                                $('.city-select').html(
                                    '<option value="">No cities available</option>');
                            }
                        },
                    });
                }
            });

            // Handle city change and update schools dropdown
            $('.city-select').on('change', function() {
                var cityId = $(this).val();
                var schoolDropdown = $('select[name="school_id[]"]');
                schoolDropdown.html(''); // Clear existing options
                if (cityId) {
                    var url = "<?php echo e(route('getSchools', ':city')); ?>".replace(':city', cityId);
                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function(data) {
                            if (data && Object.keys(data).length > 0) {
                                $.each(data, function(id, name) {
                                    schoolDropdown.append('<option value="' + id +
                                        '">' + name + '</option>');
                                });
                            } else {
                                schoolDropdown.html(
                                    '<option value="">No schools available</option>');
                            }
                        },
                    });
                }
            });

            // Handle "Select All" checkbox
            $('#select-all').on('change', function() {
                var isChecked = $(this).prop('checked');
                $('.course-checkbox').prop('checked', isChecked);
                updateSelectedCourses(); // Update selected courses
            });

            // Handle individual checkbox change
            $('.course-checkbox').on('change', function() {
                if (!$(this).prop('checked')) {
                    $('#select-all').prop('checked',
                        false); // Uncheck "Select All" if any individual checkbox is unchecked
                }
                updateSelectedCourses(); // Update selected courses
            });

            // Function to update the hidden input field with selected course IDs
            function updateSelectedCourses() {
                var selectedCourses = $('.course-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();

                // Update the hidden input field with selected course IDs as a JSON array string
                $('#course-ids').val(JSON.stringify(selectedCourses));

                // Show the modal button if any course is selected
                if (selectedCourses.length > 0) {
                    $('#assign-btn-container').show();
                } else {
                    $('#assign-btn-container').hide();
                }
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/admin/courses/complimentary-index.blade.php ENDPATH**/ ?>