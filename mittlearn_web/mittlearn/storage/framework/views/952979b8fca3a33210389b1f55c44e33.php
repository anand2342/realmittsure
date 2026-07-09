<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('admin.layouts.flash-messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="cardBox teacherMain py-md-4  mb-3">
        <div class="row">
            <div class="col-md-8 mb-3">
                <div class="teacherLeft">
                    <h5 class="fw-semibold">Teacher Manager</h5>
                    <p>Streamline teacher management with tools for scheduling, performance tracking, and collaboration.
                        Simplify workflows effectively.</p>
                    <a href="<?php echo e(route('sp.teacher.add-edit')); ?>" class="btn btn-primary-gradient rounded-1 addBtn ">Add
                        Teacher</a>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(getUserRoles() == 'school_admin'): ?>
                        <a href="<?php echo e(route('sp.check.teacher.access')); ?>"
                            class="btn btn-primary-gradient rounded-1 addBtn ">Login Access Details</a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="teacherRighr position-relative">
                    <img src="<?php echo e(asset('frontend/images/teacher-manager-img.svg')); ?>" alt="" class="teacherImg">
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="teacherTable">
                <div class="headerTbl">
                    <h6 class="m-0">Teacher Manager</h6>
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
                                
                                <li><a class="dropdown-item" href="#" id="activeTeachers">Active Teachers</a></li>
                                <li><a class="dropdown-item" href="#" id="inactiveTeachers">Inactive Teachers</a>
                                <li><a class="dropdown-item" href="<?php echo e(route('sp.teacher.manager')); ?>">All Teachers</a>
                                </li>
                            </ul>
                        </div>
                        <a href="<?php echo e(route('teachers.export')); ?>" class="bg-transparent border-0 p-0">
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
                    <div class="table-responsive tbleDiv ">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    
                                    <th>Email</th>
                                    <th>Mobile</th>
                                    <th>Status</th>
                                    <th>Address</th>
                                    <th>Hire Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody id="teacherTableBody">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    
                                    <tr data-id="<?php echo e($teacher->id); ?>" data-name="<?php echo e($teacher->name); ?>" 
                                        data-gender="<?php echo e($teacher->userAdditionalDetail->gender); ?>"
                                        data-dob="<?php echo e(\Carbon\Carbon::parse($teacher->userAdditionalDetail->dob)->format('Y-m-d')); ?>"
                                        data-email="<?php echo e($teacher->email); ?>"
                                        data-age="<?php echo e($teacher->userAdditionalDetail->age); ?>"
                                        data-mobile_no="<?php echo e($teacher->mobile_no); ?>"
                                        data-address="<?php echo e($teacher->userAdditionalDetail->address); ?>" 
                                        data-state="<?php echo e($teacher->userAdditionalDetail->state); ?>"
                                        data-city="<?php echo e($teacher->userAdditionalDetail->city); ?>"
                                        data-qualification="<?php echo e($teacher->userAdditionalDetail->qualification); ?>"
                                        data-subject="<?php echo e($teacher->userAdditionalDetail->assigned_subjects); ?>"
                                        data-class="<?php echo e($teacher->userAdditionalDetail->assigned_classes); ?>"
                                        data-experience="<?php echo e($teacher->userAdditionalDetail->experience); ?>">
                                        <td>
                                            <span class="nameTbl"> <img
                                                    src="<?php echo e($teacher->image ? Storage::url('uploads/user/profile_image/' . $teacher->image) : asset('frontend/images/default-image.jpg')); ?>"
                                                    alt=""><?php echo e($teacher->name); ?>

                                            </span>
                                        </td>
                                        
                                        <td><span data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                data-bs-title="<?php echo e($teacher->email); ?>"><?php echo e($teacher->email); ?></span>
                                        </td>
                                        <td><?php echo e($teacher->mobile_no); ?></td>
                                        <td>
                                            <span class="<?php echo e($teacher->status == 1 ? 'activeTxt' : 'deactiveTxt'); ?>">
                                                <?php echo e($teacher->status == 1 ? 'Active' : 'Inactive'); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <span data-bs-toggle="tooltip" data-bs-placement="bottom"
                                                data-bs-title="<?php echo e($teacher->address); ?>">
                                                <?php echo e(Str::limit($teacher->userAdditionalDetail->address, 40, '...')); ?>

                                            </span>
                                        </td>

                                        <td><?php echo e(\Carbon\Carbon::parse($teacher->created_at)->format('d/m/Y')); ?></td>

                                        <td>
                                            <div class="dropdown">
                                                <button class="bg-transparent border-0 p-0" type="button"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <img src="<?php echo e(asset('frontend/images/action-icon.svg')); ?>"
                                                        alt="" width="28">
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item viewEditTeacher edit-teacher-btn"
                                                            href="<?php echo e(route('sp.teacher.edit', $teacher->id)); ?>">Edit
                                                            Details</a></li>
                                                    <li><a class="dropdown-item" href="#statusMdl" id="changeStatus"
                                                            data-id="<?php echo e($teacher->id); ?>"data-status="<?php echo e($teacher->status); ?>"
                                                            data-name="<?php echo e($teacher->name); ?>"
                                                            data-bs-toggle="modal">Active/Inactive/Teacher</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="#logsView"
                                                            data-id="<?php echo e($teacher->id); ?>" data-bs-toggle="offcanvas">View
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
                            <li class="page-item <?php echo e($teachers->onFirstPage() ? 'disabled' : ''); ?> previous-item">
                                <a class="page-link" href="<?php echo e($teachers->previousPageUrl()); ?>">
                                    <span><img src="<?php echo e(asset('frontend/images/arrowprw.svg')); ?>" width="6"></span>
                                </a>
                            </li>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $teachers->getUrlRange(1, $teachers->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="page-item <?php echo e($page == $teachers->currentPage() ? 'active' : ''); ?>">
                                    <a class="page-link" href="<?php echo e($url); ?>"><?php echo e($page); ?></a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <li class="page-item <?php echo e($teachers->hasMorePages() ? '' : 'disabled'); ?> next-item">
                                <a class="page-link" href="<?php echo e($teachers->nextPageUrl()); ?>">
                                    <span><img src="<?php echo e(asset('frontend/images/arrownxt.svg')); ?>" width="6"></span>
                                </a>
                            </li>
                        </ul>
                    </div>




                </div>
            </div>
        </div>
    </div>

    <div class="offcanvas offcanvas-end <?php echo e($errors->any() ? 'show' : ''); ?>" id="addTeacher" tabindex="-1">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-heading fs-6 fw-semibold">Add Teacher</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body ">
            <h6 class="">Bulk upload</h6>
            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('school-bulk-upload', ['roles' => $roles, 'roleName' => 'school_teacher']);

$key = null;

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-1543962034-0', null);

$__html = app('livewire')->mount($__name, $__params, $key);

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
            <hr class="form-divider">


            <?php echo e(Form::open(['url' => route('sp.teacher.save'), 'id' => 'add-plan-form', 'class' => 'row g-3'])); ?>

            <?php echo e(Form::hidden('role', 'school_teacher')); ?>

            
            <?php echo e(Form::hidden('id', '', ['id' => 'teacher_id_field'])); ?>



            <div class="formPanel">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group bginput mb-3">
                            <?php echo Form::label('name', 'Name'); ?> <b>*</b>
                            <?php echo Form::text('name', old('name'), [
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
                    

                    <div class="col-md-6">
                        <div class="form-group bginput mb-3">
                            <?php echo Form::label('gender', 'Gender'); ?> <b>*</b>
                            <?php echo e(Form::select(
                                'gender',
                                config('constants.GENDER'),
                                old('gender', $userData->userAdditionalDetail->gender ?? null),
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

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <?php echo Form::label('dob', 'DOB'); ?> <b>*</b>
                            <?php echo Form::date('dob', old('dob', $userData->userAdditionalDetail->dob ?? null), [
                                'class' => 'form-control dateInput' . ($errors->has('dob') ? ' is-invalid' : ''),
                                'placeholder' => 'DOB',
                                'id' => 'date-input',
                            ]); ?>

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

                    <div class="col-md-6">
                        <div class="form-group bginput mb-3">
                            <?php echo Form::label('email', 'Enter Email'); ?> <b>*</b>
                            <?php echo Form::text('email', old('email'), [
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

                    <div class="col-md-6">
                        <div class="form-group bginput mb-3">
                            <?php echo Form::label('mobile_no', 'Mobile No.'); ?> <b>*</b>
                            <?php echo Form::number('mobile_no', old('mobile_no'), [
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

                    <div class="col-md-6">
                        <div class="form-group bginput mb-3">
                            <?php echo Form::label('age', 'Age'); ?> <b>*</b>
                            <?php echo Form::number('age', old('age'), [
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

                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <?php echo Form::label('address', 'Address'); ?> <b>*</b>
                            <?php echo Form::textarea('address', old('address'), [
                                'class' => 'form-control' . ($errors->has('address') ? ' is-invalid' : ''),
                                'placeholder' => 'Enter here',
                                'rows' => '1',
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

                    

                    <div class="col-md-6">
                        <div class="form-group bginput mb-3">
                            <?php echo Form::label('state', 'State'); ?> <b>*</b>
                            <?php echo e(Form::select(
                                'state',
                                $states, // Dynamic states array
                                old('state', $userData->userAdditionalDetail->state ?? null), // Pre-fill value or old input
                                [
                                    'class' => 'form-select' . ($errors->has('state') ? ' is-invalid' : ''),
                                    'placeholder' => 'Select',
                                    'id' => 'state-select',
                                ],
                            )); ?>

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

                    <div class="col-md-6">
                        <div class="form-group bginput mb-3">
                            <?php echo Form::label('city', 'City'); ?> <b>*</b>
                            <?php echo e(Form::select(
                                'city',
                                [], // This should be populated dynamically based on selected state
                                old('city', $userData->userAdditionalDetail->city ?? null), // Pre-fill value or retain old input
                                [
                                    'class' => 'form-select' . ($errors->has('city') ? ' is-invalid' : ''),
                                    'placeholder' => 'Select',
                                    'id' => 'city-select',
                                ],
                            )); ?>

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
                    <div class="col-md-6">
                        <div class="form-group bginput mb-3">
                            <?php echo Form::label('qualification', 'Qualification'); ?> <b>*</b>
                            <?php echo Form::text('qualification', old('qualification'), [
                                'class' => 'form-control qualification' . ($errors->has('qualification') ? ' is-invalid' : ''),
                                'placeholder' => 'Enter here',
                            ]); ?>

                        </div>
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

                    <div class="col-md-12">
                        <div class="form-group bginput mb-3">
                            <?php echo Form::label('subject', 'Assign Subject'); ?> <b>*</b>
                            <select name="subject[]" class="js-select2 form-select" multiple="multiple"
                                placeholder="Select">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($id); ?>" data-badge=""><?php echo e($name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </select>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['subject'];
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

                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label>Assign Class <b>*</b></label>
                            <ul class="typeCheckList">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li>
                                        <div class="typeCheck">
                                            <input type="checkbox" id="class_<?php echo e($key); ?>" name="class[]"
                                                value="<?php echo e($key); ?>" class="d-none">
                                            <label for="class_<?php echo e($key); ?>">
                                                <i class="bi bi-check-lg"></i><?php echo e($item); ?>

                                            </label>
                                        </div>
                                    </li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </ul>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['class'];
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
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <?php echo Form::label('experience', 'Experience'); ?> <b>*</b>
                            <?php echo Form::text('experience', null, [
                                'class' => 'form-control experience' . ($errors->has('experience') ? ' is-invalid' : ''),
                                'placeholder' => 'Enter here',
                            ]); ?>

                        </div>
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


    <div class="modal fade" id="statusToggle">
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
                        <p>Do you want to update the teacher's status from Inactivate to Activate ?</p>
                        <button type="button" class="btn btn-primary-gradient rounded-1">Yes</button>
                        <div>
                            <button type="button" class="btn btnNo">No</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="logsView">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0 align-items-baseline">
                    <div>
                        <h6 class="modal-title fw-semibold">Inactive Teacher</h6>
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
                        `Do you want to update the teacher status of ${name} from ${fromStatus} to ${toStatus}?`;
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


        document.addEventListener('DOMContentLoaded', function() {
            const editButtons = document.querySelectorAll('.edit-teacher-btn');
            const addButton = document.querySelector('.addBtn');
            const offcanvasTitle = document.querySelector('.offcanvas-heading');
            const form = document.getElementById('add-plan-form');
            const searchInput = document.getElementById('searchInput');
            const tableRows = document.querySelectorAll('#teacherTableBody tr');

            editButtons.forEach(function(btn) {
                btn.addEventListener('click', function(event) {
                    const teacherRow = event.target.closest('tr');
                    const teacherId = teacherRow.getAttribute('data-id');

                    offcanvasTitle.textContent = 'View/Edit Teacher';

                    document.getElementById('teacher_id_field').value = teacherId;
                    document.querySelector('input[name="name"]').value = teacherRow.dataset.name ||
                        '';
                    
                    document.querySelector('select[name="gender"]').value = teacherRow.dataset
                        .gender || '';
                    document.querySelector('input[name="dob"]').value = teacherRow.dataset.dob ||
                        '';
                    document.querySelector('input[name="email"]').value = teacherRow.dataset
                        .email || '';
                    document.querySelector('input[name="age"]').value = teacherRow.dataset.age ||
                        '';
                    document.querySelector('input[name="mobile_no"]').value = teacherRow.dataset
                        .mobile_no || '';
                    document.querySelector('textarea[name="address"]').value = teacherRow.dataset
                        .address || '';
                    // document.querySelector('select[name="country"]').value = teacherRow.dataset
                    //     .country || '';
                    document.querySelector('select[name="state"]').value = teacherRow.dataset
                        .state || '';
                    document.querySelector('select[name="city"]').value = teacherRow.dataset.city ||
                        '';
                    document.querySelector('input[name="qualification"]').value = teacherRow.dataset
                        .qualification || '';
                    document.querySelector('input[name="experience"]').value = teacherRow.dataset
                        .experience || '';

                    const subjectValues = teacherRow.dataset.subject ? teacherRow.dataset.subject
                        .split(',') : [];

                    const classValues = teacherRow.dataset.class ? teacherRow.dataset.class.split(
                        ',') : [];

                    const subjectSelect = document.querySelector('select[name="subject[]"]');
                    const classCheckboxes = document.querySelectorAll('input[name="class[]"]');

                    if (subjectSelect) {
                        [...subjectSelect.options].forEach(option => {
                            option.selected = subjectValues.includes(option.value);
                        });
                    }

                    classCheckboxes.forEach(checkbox => {
                        if (classValues.includes(checkbox.value)) {
                            checkbox.checked = true;
                        }
                    });

                    if (window.jQuery) {
                        $(subjectSelect).trigger('change');
                        $(classCheckboxes).trigger('change');
                    }

                });

                if (addButton) {
                    addButton.addEventListener('click', function() {
                        offcanvasTitle.textContent = 'Add Teacher';
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
        });
    </script>
    <script>
        function filterTeachers(status) {
            const url = new URL(window.location.href);

            url.searchParams.set('status', status);
            window.location.href = url.toString();
        }

        document.addEventListener("DOMContentLoaded", function() {
            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get("status");

            if (status === 1) {
                document.getElementById("activeTeachers").classList.add('active');
            } else if (status === 0) {
                document.getElementById("inactiveTeachers").classList.add('active');
            }

            document.getElementById('activeTeachers').addEventListener('click', function() {
                filterTeachers(1);
            });

            document.getElementById('inactiveTeachers').addEventListener('click', function() {
                filterTeachers(0);
            });
        });
    </script>
    <script>
        function sortTeachers(order) {
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
                sortTeachers('asc');
            });

            document.getElementById('sortDesc').addEventListener('click', function(event) {
                event.preventDefault();
                sortTeachers('desc');
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#edit-teacher-btn').on('click', function() {
                var teacherId = $(this).data('id');
                var stateId = $(this).data('state');
                var cityId = $(this).data('city');
                $('#state-select').val(stateId);
                loadCities(stateId, cityId);
            });

            $('#state-select').on('change', function() {
                var stateId = $(this).val();
                if (stateId) {
                    loadCities(stateId, null);
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
                        console.log(data);
                        $('#city-select').html('<option value="">Select</option>');

                        if (data && Object.keys(data).length > 0) {
                            $.each(data, function(id, name) {
                                var isSelected = (id == preSelectedCity) ? 'selected' : '';
                                $('#city-select').append('<option value="' + id + '" ' +
                                    isSelected + '>' + name + '</option>');
                            });
                        } else {
                            $('#city-select').html('<option value="">No cities available</option>');
                        }
                    },
                    error: function(xhr, status, error) {
                        // console.log("Error loading cities:", error);
                    }
                });
            }
            var initialStateId = $('#state-select').val();
            if (initialStateId) {
                loadCities(initialStateId, null);
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('schoolPortal.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/schoolPortal/user/teacher_manager.blade.php ENDPATH**/ ?>