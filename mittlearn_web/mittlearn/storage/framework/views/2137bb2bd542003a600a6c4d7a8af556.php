<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('admin.layouts.flash-messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php
        $flag = 0;
        $heading = 'Add Student';
        if (isset($data) && !empty($data)) {
            $flag = 1;
            $heading = 'View/Edit Student Details';
        }
    ?>
    <div class="cardBox teacherMain py-md-4  mb-3">
        <div class="row">
            <div class="col-md-8 mb-3">
                <div class="teacherLeft">
                    <h5 class="fw-semibold">Student Manager</h5>
                    <p>Manage students efficiently with our intuitive tools for tracking progress and performance.
                        Simplify administrative tasks seamlessly.</p>
                    <a href="<?php echo e(route('sp.student.add')); ?>" class="btn btn-primary-gradient rounded-1 addBtn">Add
                        Student</a>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(getUserRoles() == 'school_admin'): ?>
                        <a href="<?php echo e(route('sp.check.student.access')); ?>"
                            class="btn btn-primary-gradient rounded-1 addBtn">Login Access Details</a>
                        <a href="<?php echo e(route('sp.un-verfired.student')); ?>"
                            class="btn btn-primary-gradient rounded-1 addBtn">Un-Verfied Students</a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="teacherRighr position-relative">
                    <img src="<?php echo e(asset('frontend/images/student-manager-img.svg')); ?>" alt=""
                        class="teacherImg studentImg">
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="teacherTable">
                <div class="headerTbl">
                    <h6 class="m-0">Student Manager</h6>
                    <div class="teacherrightTable">
                        <div class="tableSearch">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search by Name">
                        </div>
                        <div class="dropdown">
                            <button class="bg-transparent border-0 p-0" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <span data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Sort">
                                    <img src="<?php echo e(asset('frontend/images/sort-icon.svg')); ?>" alt="">
                                </span>
                            </button>
                            <ul class="dropdown-menu" id="sortDropdown">
                                <li><a class="dropdown-item" href="#" id="sortAsc">Sort A to Z</a></li>
                                <li><a class="dropdown-item" href="#" id="sortDesc">Sort Z to A</a></li>
                            </ul>
                        </div>
                        <div class="dropdown">
                            <button class="bg-transparent border-0 p-0" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <span data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Filter">
                                    <img src="<?php echo e(asset('frontend/images/filter-icon.svg')); ?>" alt="">
                                </span>
                            </button>
                            <ul class="dropdown-menu">
                                
                                <li><a class="dropdown-item" href="#" id="activeStudents">Active Students</a></li>
                                <li><a class="dropdown-item" href="#" id="inactiveStudents">Inactive Students</a>
                                <li><a class="dropdown-item" href="<?php echo e(route('sp.student.manager')); ?>">All Students</a>
                                </li>
                            </ul>
                        </div>
                        <a href="<?php echo e(route('export.students')); ?>" class="bg-transparent border-0 p-0">
                            <button class="bg-transparent border-0 p-0" type="button">
                                <span>
                                    <img src="<?php echo e(asset('frontend/images/download-icon.svg')); ?>" alt="Download"
                                        title="Download">
                                </span>
                            </button>
                        </a>
                    </div>
                </div>
                <div class="px-3 py-2">
                    <div class="table-responsive tbleDiv">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Admission No.</th>
                                    <th>Name</th>
                                    <th>Admission Date</th>
                                    <th>Class</th>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(config('COURSES_FILTER_BY_ACCESS_CODE') == 1): ?>
                                        <th>Access Code</th>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <th>Parent's Mob. No.</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="studentTableBody">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr data-id="<?php echo e($student->id); ?>"
                                        data-admission_no="<?php echo e($student->userAdditionalDetail->admission_no); ?>"
                                        data-name="<?php echo e($student->name); ?>" data-email="<?php echo e($student->email); ?>"
                                        data-parent_name="<?php echo e($student->studentDetails->parent_name ?? null); ?>"
                                        data-admission_date="<?php echo e(\Carbon\Carbon::parse($student->studentDetails->doj ?? null)->format('Y-m-d')); ?>"
                                        data-class="<?php echo e($student->studentDetails->class ?? null); ?>"
                                        data-section="<?php echo e($student->studentDetails->section ?? null); ?>"
                                        data-parent_mobile_no="<?php echo e($student->mobile_no); ?>"
                                        data-status="<?php echo e($student->status == 1 ? 'active' : 'inactive'); ?>"
                                        data-dob="<?php echo e($student->studentDetails->dob ?? null); ?>">
                                        <td><?php echo e($student->userAdditionalDetail->admission_no ?? null); ?></td>
                                        <td>
                                            <span class="nameTbl student-name"> <img
                                                    src="<?php echo e($student->image ? Storage::url('uploads/user/profile_image/' . $student->image) : asset('frontend/images/default-image.jpg')); ?>"
                                                    alt=""><?php echo e($student->name); ?>

                                            </span>
                                        </td>
                                        <td><?php echo e(\Carbon\Carbon::parse($student->studentDetails->doj ?? null)->format('d/m/Y')); ?>

                                        </td>
                                        <td><?php echo e(App\Models\SchoolClass::where('id', $student->studentDetails->class ?? null)->value('name')); ?>

                                        </td>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(config('COURSES_FILTER_BY_ACCESS_CODE') == 1): ?>
                                            <td> <?php echo e($student->userAccessCode->access_code ?? ''); ?></td>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <td><?php echo e($student->mobile_no); ?></td>
                                        <td>
                                            <span class="<?php echo e($student->status == 1 ? 'activeTxt' : 'deactiveTxt'); ?>">
                                                <?php echo e($student->status == 1 ? 'Active' : 'Inactive'); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="bg-transparent border-0 p-0" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <img src="<?php echo e(asset('frontend/images/action-icon.svg')); ?>"
                                                        alt="" width="28">
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item viewEditStudent edit-student-btn"
                                                            id="edit-student-btn"
                                                            href="<?php echo e(route('sp.student.edit', $student->id)); ?>">View/Edit
                                                            Student
                                                            Details</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="#statusMdl" id="changeStatus"
                                                            data-bs-toggle="modal"
                                                            data-id="<?php echo e($student->id); ?>"data-status="<?php echo e($student->status); ?>"
                                                            data-name="<?php echo e($student->name); ?>">
                                                            Active/Inactive Student
                                                        </a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="#logsView"
                                                            data-id="<?php echo e($student->id); ?>" data-bs-toggle="offcanvas">View
                                                            Active/Inactive Logs</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="customPagination mt-4">
                        <ul class="pagination">
                            <li class="page-item <?php echo e($students->onFirstPage() ? 'disabled' : ''); ?> previous-item">
                                <a class="page-link" href="<?php echo e($students->previousPageUrl()); ?>">
                                    <span><img src="<?php echo e(asset('frontend/images/arrowprw.svg')); ?>" width="6"></span>
                                </a>
                            </li>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $students->getUrlRange(1, $students->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="page-item <?php echo e($page == $students->currentPage() ? 'active' : ''); ?>">
                                    <a class="page-link" href="<?php echo e($url); ?>"><?php echo e($page); ?></a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <li class="page-item <?php echo e($students->hasMorePages() ? '' : 'disabled'); ?> next-item">
                                <a class="page-link" href="<?php echo e($students->nextPageUrl()); ?>">
                                    <span><img src="<?php echo e(asset('frontend/images/arrownxt.svg')); ?>" width="6"></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-end" id="logsView">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title fs-6 fw-semibold">View Active/Inactive Logs</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="logsUl" id="logsList">
                <!-- Logs will be dynamically added here -->
            </ul>
        </div>
        <div class="offcanvas-footer">
            <div class="d-flex align-items-center justify-content-end gap-4">
                <button type="button" class="btn btn-secondary px-5 rounded-1" data-bs-dismiss="offcanvas">Back</button>
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-end <?php echo e($errors->any() ? 'show' : ''); ?>" id="addStudent" tabindex="-1">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-heading fs-6 fw-semibold">Add Student</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body ">
            <h6 class="">Bulk upload</h6>
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('school-bulk-upload', ['roles' => $roles, 'roleName' => 'school_student']);

$key = null;

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-261102759-0', null);

$__html = app('livewire')->mount($__name, $__params, $key);

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
            <hr class="form-divider">

            <?php echo e(Form::open(['url' => route('sp.student.save'), 'id' => 'add-plan-form', 'class' => 'row g-3'])); ?>

            <?php echo e(Form::hidden('role', 'school_student')); ?>

            <?php echo e(Form::hidden('id', '', ['id' => 'student_id_field'])); ?>


            <div class="formPanel">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group bginput mb-3">
                            <?php echo Form::label('admission_no', 'Admission No.'); ?>

                            <?php echo Form::text('admission_no', old('admission_no', $userData->userAdditionalDetail->admission_no ?? null), [
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
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <?php echo Form::label('admission_date', 'Admission Date'); ?>

                            <?php echo Form::date('admission_date', $userData->studentDetails->admission_date ?? null, [
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
                    <div class="col-md-6">
                        <div class="form-group bginput mb-3">
                            <?php echo Form::label('name', 'Name'); ?> <b>*</b>
                            <?php echo Form::text('name', old('name', $userData->name ?? null), [
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
                    <div class="col-md-6">
                        <div class="form-group bginput mb-3">
                            <?php echo Form::label('parent_name', 'Parent Name'); ?>

                            <?php echo Form::text('parent_name', old('parent_name', $userData->studentDetails->parent_name ?? null), [
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
                    <div class="col-md-6">
                        <div class="form-group bginput mb-3">
                            <?php echo Form::label('email', 'Email'); ?>

                            <?php echo Form::text('email', old('email', $userData->email ?? null), [
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
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <?php echo Form::label('dob', 'DOB'); ?>

                            <?php echo Form::date('dob', old('dob', $userData->studentDetails->dob ?? null), [
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
                    <?php if(getUserRoles() == 'school_teacher'): ?>
                        <div class="col-md-6">
                            <div class="form-group bginput mb-3">
                                <?php echo Form::label('class', 'Select Class'); ?> <b>*</b>
                                <?php echo Form::select('class', $teacherClasses, old('class', $userData->studentDetails->class ?? null), [
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
                        <div class="col-md-6">
                            <div class="form-group bginput mb-3">
                                <?php echo Form::label('class', 'Select Class'); ?> <b>*</b>
                                <?php echo Form::select('class', $classes, old('class', $userData->studentDetails->class ?? null), [
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
                    <div class="col-md-6">
                        <div class="form-group bginput mb-3">
                            <?php echo Form::label('section', 'Select Section'); ?>

                            <?php echo Form::select('section', $sections, old('section', $userData->studentDetails->section ?? null), [
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
                    <div class="col-md-6">
                        <div class="form-group bginput mb-3">
                            <?php echo Form::label('parent_mobile_no', 'Parent/Guardian Mobile No.'); ?> <b>*</b>
                            <?php echo Form::text(
                                'parent_mobile_no',
                                old('parent_mobile_no', $userData->studentDetails->emergency_contact_phone ?? null),
                                [
                                    'class' => 'form-control mobile ' . ($errors->has('parent_mobile_no') ? 'is-invalid' : ''),
                                    'placeholder' => 'Enter here',
                                ],
                            ); ?>

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
        </div>
        <div class="offcanvas-footer">
            <div class="d-flex align-items-center justify-content-end gap-4">
                <button type="button" class="btn backbtn" data-bs-dismiss="offcanvas">Back</button>
                <button type="submit" class="btn btn-primary-gradient rounded-1">Submit</button>
            </div>
        </div>
        <?php echo e(Form::close()); ?>

    </div>

    <div class="modal fade" id="statusMdl">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="text-center">
                        <lottie-player src="<?php echo e(asset('frontend/images/study-idea.json')); ?>" loop=""
                            autoplay="" style="width: 130px;height: 130px;margin: auto;"
                            background="transparent"></lottie-player>
                        <h6 class="fw-semibold">Are you sure !</h6>
                        <p id="statusText"></p>
                        <button type="button" class="btn btn-primary-gradient rounded-1"
                            id="confirmChangeStatus">Yes</button>
                        <div>
                            <button type="button" class="btn btnNo" data-bs-dismiss="modal">No</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="studentInactive">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0 align-items-baseline">
                    <div>
                        <h6 class="modal-title fw-semibold">Inactive Student</h6>
                        <p>Enter inactive date for changing the status of student from active to Inactive.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="">
                        <div class="formPanel">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group bginput mb-3">
                                        <label>Enter Date</label>
                                        <input type="text" class="form-control dateBirth" value="Select date">
                                    </div>
                                </div>
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-primary-gradient rounded-1">Submit</button>
                                <div>
                                    <button type="button" class="btn btnNo">Back</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusModal = document.getElementById('statusMdl');
            const statusText = document.getElementById('statusText');
            let studentId = null;

            // Handle opening modal and setting data
            document.querySelectorAll('[data-bs-toggle="modal"]').forEach(button => {
                button.addEventListener('click', function() {
                    studentId = this.getAttribute('data-id');
                    const status = this.getAttribute('data-status');
                    const name = this.getAttribute('data-name');
                    const fromStatus = status == 1 ? 'Activate' : 'Inactivate';
                    const toStatus = status == 1 ? 'Inactivate' : 'Activate';

                    statusText.textContent =
                        `Do you want to update the student status of ${name} from ${fromStatus} to ${toStatus}?`;
                });
            });

            // Handle status change confirmation
            document.getElementById('confirmChangeStatus').addEventListener('click', function() {
                if (studentId) {
                    var url = '<?php echo e(route('user.toggle.status', ':id')); ?>'.replace(':id', studentId);
                    $.ajax({
                        url: url,
                        method: 'POST',
                        data: {
                            _token: '<?php echo e(csrf_token()); ?>',
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                location.reload();
                            } else {
                                alert('Failed to update status. Please try again.');
                            }
                        },
                        error: function() {
                            alert('Error occurred while updating status.');
                        }
                    });
                }
            });
        });

        // To display active/inactive logs for a user from the UserLog table
        document.addEventListener('DOMContentLoaded', function() {
            const logsList = document.getElementById('logsList');

            document.addEventListener('click', function(event) {
                if (event.target && event.target.matches('[data-bs-toggle="offcanvas"]')) {
                    const userId = event.target.getAttribute('data-id');

                    if (!userId) {
                        console.error('User ID is missing!');
                        return;
                    }

                    // Clear existing logs and show loading state
                    logsList.innerHTML = '<li>Loading logs...</li>';

                    // Fetch logs from the backend
                    fetch(`/school-portal/user/logs/${userId}`)
                        .then(response => response.json())
                        .then(data => {
                            // Clear the loading message
                            logsList.innerHTML = '';

                            if (!Array.isArray(data) || data.length === 0) {
                                logsList.innerHTML = '<li>No logs found for this user.</li>';
                                return;
                            }

                            // Render logs dynamically
                            data.forEach(log => {
                                const logType = log.action_as === 'user_active' ? 'activated' :
                                    'deactivated';
                                const logIcon = log.action_as === 'user_active' ?
                                    '<?php echo e(asset('frontend/images/activated-icon.svg')); ?>' :
                                    '<?php echo e(asset('frontend/images/deactivated-icon.svg')); ?>';

                                logsList.innerHTML += `
                            <li>
                                <div class="logsInner ${logType}">
                                    <figure class="m-0">
                                        <img src="${logIcon}" alt="" width="36">
                                    </figure>
                                    <div>
                                        <span>${log.title}</span>
                                        <strong>
                                            <img src="<?php echo e(asset('frontend/images/time-date-icon.svg')); ?>" alt="">
                                            ${new Date(log.log_date).toLocaleDateString()} 
                                            <b class="fw">${new Date(log.log_date).toLocaleTimeString()}</b>
                                        </strong>
                                    </div>
                                </div>
                            </li>
                        `;
                            });
                        })
                        .catch(error => {
                            console.error('Error fetching logs:', error);
                            logsList.innerHTML = '<li>Error loading logs. Please try again later.</li>';
                        });
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('.edit-student-btn');
            const addButton = document.querySelector('.addBtn');
            const offcanvasTitle = document.querySelector('.offcanvas-heading');
            const form = document.getElementById('add-plan-form');
            const searchInput = document.getElementById('searchInput');
            const tableRows = document.querySelectorAll('#studentTableBody tr');

            editButtons.forEach(function(btn) {
                btn.addEventListener('click', function(event) {
                    const studentRow = event.target.closest('tr');
                    const studentId = studentRow.getAttribute('data-id');

                    offcanvasTitle.textContent = 'View/Edit Student';

                    document.getElementById('student_id_field').value = studentId;
                    document.querySelector('input[name="admission_no"]').value = studentRow.dataset
                        .admission_no || '';
                    document.querySelector('input[name="name"]').value = studentRow.dataset.name ||
                        '';
                    document.querySelector('input[name="email"]').value = studentRow.dataset
                        .email ||
                        '';
                    document.querySelector('input[name="parent_name"]').value = studentRow.dataset
                        .parent_name ||
                        '';
                    document.querySelector('input[name="admission_date"]').value = studentRow
                        .dataset.admission_date || '';
                    document.querySelector('input[name="dob"]').value = studentRow.dataset.dob ||
                        '';
                    document.querySelector('select[name="class"]').value = studentRow.dataset
                        .class || '';
                    document.querySelector('select[name="section"]').value = studentRow.dataset
                        .section || '';
                    document.querySelector('input[name="parent_mobile_no"]').value = studentRow
                        .dataset.parent_mobile_no || '';

                    document.querySelector('input[name="student_id"]').value = studentRow.dataset
                        .id || '';
                });
            });

            if (addButton) {
                addButton.addEventListener('click', function() {
                    offcanvasTitle.textContent = 'Add Student';
                    form.reset();
                    document.getElementById('student_id_field').value = '';
                });
            }

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const query = this.value.toLowerCase();

                    tableRows.forEach(row => {
                        const title = row.getAttribute('data-name').toLowerCase();

                        if (title.includes(query)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }
        });
    </script>


    <script>
        function filterStudents(status) {
            const url = new URL(window.location.href);

            url.searchParams.set('status', status);
            window.location.href = url.toString();
        }

        document.addEventListener("DOMContentLoaded", function() {
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get("status");

            if (status === 1) {
                document.getElementById("activeStudents").classList.add('active');
            } else if (status === 0) {
                document.getElementById("inactiveStudents").classList.add('active');
            }

            document.getElementById('activeStudents').addEventListener('click', function() {
                filterStudents(1);
            });

            document.getElementById('inactiveStudents').addEventListener('click', function() {
                filterStudents(0);
            });
        });
    </script>
    <script>
        function sortStudents(order) {
            const url = new URL(window.location.href);
            url.searchParams.set('sort', order);
            window.location.href = url.toString();
        }

        document.addEventListener("DOMContentLoaded", function() {
            const urlParams = new URLSearchParams(window.location.search);
            const sortOrder = urlParams.get("sort");

            if (sortOrder === 'asc') {
                document.getElementById("sortAsc").classList.add('active');
            } else if (sortOrder === 'desc') {
                document.getElementById("sortDesc").classList.add('active');
            }

            document.getElementById('sortAsc').addEventListener('click', function(event) {
                event.preventDefault();
                sortStudents('asc');
            });

            document.getElementById('sortDesc').addEventListener('click', function(event) {
                event.preventDefault();
                sortStudents('desc');
            });
        });
    </script>
    
<?php $__env->stopSection(); ?>

<?php echo $__env->make('schoolPortal.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/schoolPortal/user/student_manager.blade.php ENDPATH**/ ?>