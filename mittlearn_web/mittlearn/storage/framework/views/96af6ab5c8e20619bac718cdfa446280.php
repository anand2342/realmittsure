<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e($links['site_page_title']); ?></title>
    <!-- Favicons -->
    <link href="<?php echo e(asset('images/mittlearn-favicon.png')); ?>" rel="icon">
    <link href="<?php echo e(asset('images/mittlearn-favicon.png')); ?>" rel="apple-touch-icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="<?php echo e(asset('frontend/css/bootstrap.min.css')); ?>" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.14.0/themes/base/jquery-ui.css">
    <link href="<?php echo e(asset('admin/vendor/quill/quill.snow.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('admin/vendor/quill/quill.bubble.css')); ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">

    <!-- Include in your layout file -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="<?php echo e(asset('admin/vendor/sweetalert2-7.0.0/sweetalert2.css')); ?>" rel="stylesheet">


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <link href="<?php echo e(asset('frontend/css/style.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('frontend/css/custom.css')); ?>" rel="stylesheet">
    <script type="text/javascript" src="<?php echo e(asset('frontend/js/init.js')); ?>"></script>
    <script>
        var base_url = "<?php echo e(url('/') . '/'); ?>";
        var csrf_token = "<?php echo e(csrf_token()); ?>";
    </script>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

</head>

<body style="background-color: #F9F9F9;">

    <?php echo $__env->make('schoolPortal.layouts.header', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php echo $__env->make('schoolPortal.layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


    <main id="main" class="main">
        <div class="dashboardMain">
            <div class="alertsSec cardBox mb-3 d-lg-none">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($notificationAlerts): ?>
                    <h3 class="fs-6 fw-regular d-flex align-items-center gap-1 mb-0"><img
                            src="<?php echo e(asset('frontend/images/alert.svg')); ?>" alt="" width="15">Alerts</h3>
                    <div class="alertList">
                        <a href="javascript:void(0);"><?php echo e($notificationAlerts->message); ?></a>
                        <a href="javascript:void(0);"><?php echo e($notificationAlerts->message); ?></a>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            
            
            
            <?php echo $__env->yieldContent('content'); ?>
        </div>
        <div class="footerBottom">
            <ul class="footerLeft">
                <li><strong><?php echo e($links['company_name'] ?? ''); ?></strong>
                </li>
                <li><img src="<?php echo e(asset('frontend/images/call-icon.svg')); ?>" alt="" width="13">
                    <?php echo e($links['user_contact_number'] ?? ''); ?></li>
                <li><img src="<?php echo e(asset('frontend/images/mail-icon.svg')); ?>" alt="" width="18">
                    <?php echo e($links['user_email'] ?? ''); ?> </li>
                <li><img src="<?php echo e(asset('frontend/images/location-icon.svg')); ?>" alt=""
                        width="12"><?php echo e($links['user_address'] ?? ''); ?>

                </li>

            </ul>
            <ul class="footerright">
                <li><a target="_blank" href=<?php echo e($links['user_facebook'] ?? ''); ?>><img
                            src="<?php echo e(asset('frontend/images/facebook.svg')); ?>" width="25" height="18"></a>
                </li>
                <li><a target="_blank" href=<?php echo e($links['user_instagram'] ?? ''); ?>><img
                            src="<?php echo e(asset('frontend/images/instagram.svg')); ?>" width="25" height="18"></a></li>
                <li><a target="_blank" href=<?php echo e($links['user_twitter'] ?? ''); ?>><img
                            src="<?php echo e(asset('frontend/images/twitter.svg')); ?>" width="25" height="18"></a>
                </li>
                <li><a target="_blank" href=<?php echo e($links['user_linkedin'] ?? ''); ?>><img
                            src="<?php echo e(asset('frontend/images/linkedin.svg')); ?>" width="25" height="18"></a></li>
                <li><a target="_blank" href=<?php echo e($links['user_youtube'] ?? ''); ?>><img
                            src="<?php echo e(asset('frontend/images/youtube.svg')); ?>" width="25" height="18"></a>
                </li>
            </ul>
            </ul>
        </div>
    </main>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(getUserRoles() !== 'school_teacher'): ?>
        <div class="modal fade SchoolProfile" id="profile">
            <div class="modal-dialog modal-dialog-centered ">
                <div class="modal-content border-0 rounded-1">
                    <div class="modal-body p-0">
                        <div class="profileMain">
                            <div class="profileSidebar ">
                                <div class="profileUpload">
                                    <figure class="position-relative m-0">
                                        <img id="profileImage"
                                            src="<?php echo e(Auth::user()->image ? Storage::url('uploads/user/profile_image/' . Auth::user()->image) : asset('frontend/images/default-image.jpg')); ?>"
                                            alt="Profile Image">
                                        <label for="profileHeader" class="contentprf">
                                            <div class="text-white">
                                                <img src="<?php echo e(asset('frontend/images/edit-upload.svg')); ?>"
                                                    class="d-block mx-auto mb-3" alt="" width="14">
                                                Click to change profile <br> Image
                                            </div>
                                            <input type="file" name="image" id="profileHeader" class="d-none"
                                                accept="image/*">
                                        </label>
                                    </figure>
                                </div>
                                <ul class="nav nav-pills flex-column mb-3 profileTabs">
                                    <li class="nav-item">
                                        <button class="nav-link active" data-bs-toggle="pill"
                                            data-bs-target="#schoolDetails" type="button"><img
                                                src="<?php echo e(asset('frontend/images/school-details-icon.svg')); ?>"
                                                alt="" width="14" class="me-3">School Details</button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link" data-bs-toggle="pill"
                                            data-bs-target="#addressDetails" type="button"><img
                                                src="<?php echo e(asset('frontend/images/address-details-icon.svg')); ?>"
                                                alt="" width="12" class="me-3">Address Details</button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link" data-bs-toggle="pill"
                                            data-bs-target="#passwordChange" type="button"><img
                                                src="<?php echo e(asset('frontend/images/password-change-icon.svg')); ?>"
                                                alt="" width="16" class="me-3">Password Change</button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link text-danger" type="button" data-bs-target="#logOut"
                                            data-bs-toggle="modal"><img
                                                src="<?php echo e(asset('frontend/images/logout-icon.svg')); ?>" alt=""
                                                width="14" class="me-3">Log
                                            out</button>
                                    </li>
                                </ul>
                            </div>
                            <div class="profileRight">
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="schoolDetails">
                                        <h1 class="modal-title fs-4 fw-semibold">School Details</h1>
                                        <div class="formPanel">

                                            <?php echo Form::open(['url' => route('sp.update.profile.details'), 'method' => 'post']); ?>

                                            <?php echo csrf_field(); ?>
                                            <?php echo Form::hidden('id', $currentUser->id ?? ''); ?>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <?php echo Form::label('school_name', 'School Name'); ?>

                                                        <?php echo Form::text('school_name', $currentUser->schoolDetails->name ?? '', [
                                                            'class' => 'form-control readonly',
                                                            'readonly',
                                                        ]); ?>

                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <?php echo Form::label('parent_school_name', 'Parent School Name'); ?>

                                                        <?php echo Form::text('parent_school_name', $currentUser->userAdditionalDetail->parentSchoolName->name ?? '', [
                                                            'class' => 'form-control readonly',
                                                            'readonly',
                                                        ]); ?>

                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <?php echo Form::label('email', 'Email'); ?>

                                                        <?php echo Form::text('email', $currentUser->email ?? '', ['class' => 'form-control readonly', 'readonly']); ?>

                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <?php echo Form::label('website', 'Website'); ?>

                                                        <?php echo Form::text('website', $currentUser->userAdditionalDetail->website ?? '', [
                                                            'class' => 'form-control',
                                                        ]); ?>

                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <?php echo Form::label('decision_maker', 'Decision Maker Name'); ?>

                                                        <?php echo Form::text('decision_maker', $currentUser->userAdditionalDetail->decision_maker ?? '', [
                                                            'class' => 'form-control ',
                                                        ]); ?>

                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <?php echo Form::label('decision_maker_mobile_no', 'Decision Maker Mobile No'); ?>

                                                        <?php echo Form::text('decision_maker_mobile_no', $currentUser->mobile_no ?? '', [
                                                            'class' => 'form-control',
                                                        ]); ?>

                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <?php echo Form::label('decision_maker_role', 'Decision Maker Role'); ?>

                                                        <?php echo Form::text('decision_maker_role', $currentUser->userAdditionalDetail->decisionMakerRole->role_name ?? '', [
                                                            'class' => 'form-control readonly',
                                                            'readonly' => true,
                                                        ]); ?>

                                                    </div>

                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <?php echo Form::label('strength', 'Strength'); ?>

                                                        <?php echo Form::text('strength', $currentUser->userAdditionalDetail->strength ?? '', [
                                                            'class' => 'form-control',
                                                        ]); ?>

                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <?php echo Form::label('school_affiliation_no', 'School Affiliation Number/PAN Number'); ?>

                                                        <?php echo Form::text('school_affiliation_no', $currentUser->userAdditionalDetail->school_affiliation_no ?? '', [
                                                            'class' => 'form-control readonly',
                                                            'readonly',
                                                        ]); ?>

                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <?php echo Form::label('school_registration_no', 'School Registration Number'); ?>

                                                        <?php echo Form::text('school_registration_no', $currentUser->userAdditionalDetail->school_registration_no ?? '', [
                                                            'class' => 'form-control readonly',
                                                            'readonly',
                                                        ]); ?>

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 text-center my-2 mt-4">
                                                <button type="submit"
                                                    class="btn btn-primary-gradient fs-7 rounded-2 w-75">Update</button>
                                            </div>
                                            <?php echo Form::close(); ?>

                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="addressDetails">
                                        <h1 class="modal-title fs-4 fw-semibold">Address Details</h1>
                                        <div class="formPanel">

                                            <?php echo Form::open(['url' => route('sp.update.profile.address'), 'method' => 'post']); ?>

                                            <?php echo csrf_field(); ?>
                                            <?php echo Form::hidden('id', $currentUser->id ?? ''); ?>

                                            <?php
                                                $cities = App\Models\City::pluck('city', 'id');
                                                $states = App\Models\State::pluck('name', 'id');
                                            ?>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <?php echo Form::label('postal_code', 'Pin Code'); ?>

                                                        <?php echo Form::text('postal_code', $currentUser->schoolDetails->postal_code ?? '', [
                                                            'class' => 'form-control',
                                                        ]); ?>

                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <?php echo Form::label('state', 'State'); ?>

                                                        <?php echo e(Form::select('state', $states, old('state', $currentUser->schoolDetails->state ?? null), [
                                                            'class' => 'form-select',
                                                            'placeholder' => 'Select',
                                                            'id' => 'admin-state-select',
                                                        ])); ?>

                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <?php echo Form::label('city', 'District'); ?>

                                                        <?php echo e(Form::select('city', $cities, old('city', $currentUser->schoolDetails->city ?? null), [
                                                            'class' => 'form-select',
                                                            'placeholder' => 'Select',
                                                            'id' => 'admin-city-select',
                                                        ])); ?>

                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <?php echo Form::label('address', 'Address'); ?>

                                                        <?php echo Form::text('address', $currentUser->schoolDetails->address ?? '', [
                                                            'class' => 'form-control',
                                                        ]); ?>

                                                    </div>
                                                </div>
                                                <div class="col-md-12 text-center my-2 mt-4">
                                                    <button type="submit"
                                                        class="btn btn-primary-gradient fs-7 rounded-2 w-75">Update</button>
                                                </div>
                                            </div>
                                            <?php echo Form::close(); ?>


                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="passwordChange">
                                        <h1 class="modal-title fs-4 fw-semibold">Change Password</h1>
                                        <div class="formPanel">
                                            <form method="post" action="<?php echo e(route('sp.change.password')); ?>">
                                                <?php echo csrf_field(); ?>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group mb-3">
                                                            <label>Current Password</label>
                                                            <div class="position-relative">
                                                                <input type="password"
                                                                    class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                    id="password" name="password"
                                                                    placeholder="Enter current Password">
                                                                <span class="eyeInput eye_icon" data-id="password">
                                                                    <i class="bi bi-eye-slash"></i>
                                                                </span>
                                                            </div>
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password'];
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
                                                    <div class="col-md-12">
                                                        <div class="form-group mb-3">
                                                            <label>Enter New Password</label>
                                                            <div class="position-relative">
                                                                <input
                                                                    class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                    type="password" name="newpassword"
                                                                    id="newpassword" required
                                                                    placeholder="Enter New Password">
                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
                                                                    <span>
                                                                        <label
                                                                            class="error"><?php echo e(session('error')); ?></label>
                                                                    </span>
                                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                <span class="eyeInput eye_icon" data-id="newpassword">
                                                                    <i class="bi bi-eye-slash"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group mb-3">
                                                            <label>Confirm New Password</label>
                                                            <div class="position-relative">
                                                                <input class="form-control" type="password"
                                                                    name="newpassword_confirmation"
                                                                    id="newpassword_confirmation" required
                                                                    placeholder="Confirm New Password">
                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
                                                                    <span>
                                                                        <label
                                                                            class="error"><?php echo e(session('error')); ?></label>
                                                                    </span>
                                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                <span class="eyeInput eye_icon"
                                                                    data-id="newpassword_confirmation">
                                                                    <i class="bi bi-eye-slash"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 text-center my-2 mt-4">
                                                        <button type="submit"
                                                            class="btn btn-primary-gradient fs-7 rounded-2 w-75">Update</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(getUserRoles() == 'school_teacher'): ?>
        <div class="modal fade" id="teacherProfile">
            <div class="modal-dialog modal-dialog-centered ">
                <div class="modal-content border-0 rounded-1">
                    <div class="modal-body p-0">
                        <div class="profileMain">
                            <div class="profileSidebar ">
                                <div class="profileUpload">
                                    <figure class="position-relative m-0">
                                        <img id="profileImage"
                                            src="<?php echo e(Auth::user()->image ? Storage::url('uploads/user/profile_image/' . Auth::user()->image) : asset('frontend/images/default-image.jpg')); ?>"
                                            alt="Profile Image">
                                        <label for="profileHeader" class="contentprf">
                                            <div class="text-white">
                                                <img src="<?php echo e(asset('frontend/images/edit-upload.svg')); ?>"
                                                    class="d-block mx-auto mb-3" alt="" width="14">
                                                Click to change profile <br> Image
                                            </div>
                                            <input type="file" name="image" id="profileHeader" class="d-none"
                                                accept="image/*">
                                        </label>
                                    </figure>
                                </div>
                                <ul class="nav nav-pills flex-column mb-3 profileTabs">
                                    <li class="nav-item">
                                        <button class="nav-link active" data-bs-toggle="pill"
                                            data-bs-target="#teacherDetails" type="button"><img
                                                src="<?php echo e(asset('frontend/images/school-details-icon.svg')); ?>"
                                                alt="" width="14" class="me-3">User Details</button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link" data-bs-toggle="pill"
                                            data-bs-target="#teacherAddressDetails" type="button"><img
                                                src="<?php echo e(asset('frontend/images/address-details-icon.svg')); ?>"
                                                alt="" width="12" class="me-3">Address Details</button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link" data-bs-toggle="pill"
                                            data-bs-target="#teacherPasswordChange" type="button"><img
                                                src="<?php echo e(asset('frontend/images/password-change-icon.svg')); ?>"
                                                alt="" width="16" class="me-3">Password Change</button>
                                    </li>
                                    <li class="nav-item">
                                        <button class="nav-link text-danger" type="button" data-bs-target="#logOut"
                                            data-bs-toggle="modal"><img
                                                src="<?php echo e(asset('frontend/images/logout-icon.svg')); ?>" alt=""
                                                width="14" class="me-3">Log
                                            out</button>
                                    </li>
                                </ul>
                            </div>
                            <div class="profileRight">
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                                <div class="tab-content">
                                    <div class="tab-pane fade show active" id="teacherDetails">
                                        <h1 class="modal-title fs-4 fw-semibold">User Details</h1>
                                        <div class="formPanel">
                                            <?php echo Form::open(['url' => route('sp.update.profile.details'), 'method' => 'post']); ?>

                                            <?php echo csrf_field(); ?>
                                            <?php echo Form::hidden('id', $currentUser->id ?? ''); ?>

                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <?php echo Form::label('name', 'Full Name'); ?>

                                                        <?php echo Form::text('name', $currentUser->name ?? '', [
                                                            'class' => 'form-control',
                                                        ]); ?>

                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <?php echo Form::label('gender', 'Gender'); ?>

                                                        <?php echo e(Form::select(
                                                            'gender',
                                                            config('constants.GENDER'),
                                                            old('gender', $currentUser->userAdditionalDetail->gender ?? null),
                                                            [
                                                                'class' => 'form-select' . ($errors->has('gender') ? ' is-invalid' : ''),
                                                                'placeholder' => 'Select',
                                                            ],
                                                        )); ?>

                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <?php echo Form::label('email', 'Email'); ?>

                                                        <?php echo Form::text('email', $currentUser->email ?? '', ['class' => 'form-control readonly', 'readonly']); ?>

                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <?php echo Form::label('mobile_number', 'Mobile Number'); ?>

                                                        <?php echo Form::text('mobile_number', $currentUser->mobile_no ?? '', [
                                                            'class' => 'form-control readonly',
                                                            'readonly',
                                                        ]); ?>

                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <?php echo Form::label('experience', 'Experience'); ?>

                                                        <?php echo Form::text('experience', $currentUser->userAdditionalDetail->experience ?? '', [
                                                            'class' => 'form-control',
                                                        ]); ?>

                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <?php echo Form::label('qualification', 'Qualification'); ?>

                                                        <?php echo Form::text('qualification', $currentUser->userAdditionalDetail->qualification ?? '', [
                                                            'class' => 'form-control',
                                                        ]); ?>

                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <?php echo Form::label('dob', 'DOB'); ?>

                                                        <?php echo Form::date(
                                                            'dob',
                                                            isset($currentUser->userAdditionalDetail->dob)
                                                                ? \Carbon\Carbon::parse($currentUser->userAdditionalDetail->dob)->format('Y-m-d')
                                                                : '',
                                                            [
                                                                'class' => 'form-control',
                                                            ],
                                                        ); ?>

                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <?php echo Form::label('age', 'Age'); ?>

                                                        <?php echo Form::text('age', $currentUser->userAdditionalDetail->age ?? '', [
                                                            'class' => 'form-control',
                                                        ]); ?>

                                                    </div>
                                                </div>
                                                <?php
                                                    $teacherClasses = App\Models\Classes::where('is_active', 1)
                                                        ->whereIn('id', getTeacherAssignedClasses())
                                                        ->pluck('name');
                                                    $teacherSubjects = App\Models\Subject::where('is_active', 1)
                                                        ->whereIn('id', getTeacherAssignedSubjects())
                                                        ->pluck('name');
                                                ?>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <?php echo Form::label('Assigned Classes', 'Assigned Classes'); ?>

                                                        <div class="teacher-chip-container">
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $teacherClasses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <span class="teacher-chip"><?php echo e($class); ?></span>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <?php echo Form::label('Assigned Subjects', 'Assigned Subjects'); ?>

                                                        <div class="teacher-chip-container">
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $teacherSubjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <span class="teacher-chip"><?php echo e($subject); ?></span>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-md-12 text-center my-2 mt-4">
                                                <button type="submit"
                                                    class="btn btn-primary-gradient fs-7 rounded-2 w-75">Update</button>
                                            </div>
                                            <?php echo Form::close(); ?>

                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="teacherAddressDetails">
                                        <h1 class="modal-title fs-4 fw-semibold">Address Details</h1>
                                        <div class="formPanel">
                                            <?php echo Form::open(['url' => route('sp.update.profile.address'), 'method' => 'post']); ?>

                                            <?php echo csrf_field(); ?>
                                            <?php echo Form::hidden('id', $currentUser->id ?? ''); ?>

                                            <?php
                                                $cities = App\Models\City::pluck('city', 'id');
                                                $states = App\Models\State::pluck('name', 'id');
                                            ?>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <?php echo Form::label('address', 'Address'); ?>

                                                        <?php echo Form::textarea('address', $currentUser->userAdditionalDetail->address ?? null, [
                                                            'class' => 'form-control' . ($errors->has('address') ? ' is-invalid' : ''),
                                                            'placeholder' => 'Enter here',
                                                            'rows' => '1',
                                                        ]); ?>

                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <?php echo Form::label('country', 'Country'); ?>

                                                        <?php echo e(Form::select(
                                                            'country',
                                                            ['india' => 'India'], // Options list
                                                            old('country', $currentUser->userAdditionalDetail->country ?? null), // Default value or previously selected value
                                                            [
                                                                'class' => 'form-select' . ($errors->has('country') ? ' is-invalid' : ''),
                                                                'placeholder' => 'Select',
                                                            ],
                                                        )); ?>

                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <?php echo Form::label('state', 'State'); ?>

                                                        <?php echo e(Form::select(
                                                            'state',
                                                            $states, // Dynamic states array
                                                            old('state', $currentUser->userAdditionalDetail->state ?? null), // Pre-fill value or old input
                                                            [
                                                                'class' => 'form-select' . ($errors->has('state') ? ' is-invalid' : ''),
                                                                'placeholder' => 'Select',
                                                                'id' => 'teacher-state-select',
                                                            ],
                                                        )); ?>

                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group mb-3">
                                                        <?php echo Form::label('city', 'City'); ?>

                                                        <?php echo e(Form::select(
                                                            'city',
                                                            $cities, // This should be populated dynamically based on selected state
                                                            old('city', $currentUser->userAdditionalDetail->city ?? null), // Pre-fill value or retain old input
                                                            [
                                                                'class' => 'form-select' . ($errors->has('city') ? ' is-invalid' : ''),
                                                                'placeholder' => 'Select',
                                                                'id' => 'teacher-city-select',
                                                            ],
                                                        )); ?>

                                                    </div>
                                                </div>
                                                <div class="col-md-12 text-center my-2 mt-4">
                                                    <button type="submit"
                                                        class="btn btn-primary-gradient fs-7 rounded-2 w-75">Update</button>
                                                </div>
                                            </div>
                                            <?php echo Form::close(); ?>

                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="teacherPasswordChange">
                                        <h1 class="modal-title fs-4 fw-semibold">Change Password</h1>
                                        <div class="formPanel">
                                            <form method="post" action="<?php echo e(route('sp.change.password')); ?>">
                                                <?php echo csrf_field(); ?>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group mb-3">
                                                            <label>Current Password</label>
                                                            <div class="position-relative">
                                                                <input type="password"
                                                                    class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                    id="password" name="password"
                                                                    placeholder="Enter current Password">
                                                                <span class="eyeInput eye_icon" data-id="password">
                                                                    <i class="bi bi-eye-slash"></i>
                                                                </span>
                                                            </div>
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password'];
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
                                                    <div class="col-md-12">
                                                        <div class="form-group mb-3">
                                                            <label>Enter New Password</label>
                                                            <div class="position-relative">
                                                                <input
                                                                    class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                                    type="password" name="newpassword"
                                                                    id="newpassword" required
                                                                    placeholder="Enter New Password">
                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
                                                                    <span>
                                                                        <label
                                                                            class="error"><?php echo e(session('error')); ?></label>
                                                                    </span>
                                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                <span class="eyeInput eye_icon" data-id="newpassword">
                                                                    <i class="bi bi-eye-slash"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group mb-3">
                                                            <label>Confirm New Password</label>
                                                            <div class="position-relative">
                                                                <input class="form-control" type="password"
                                                                    name="newpassword_confirmation"
                                                                    id="newpassword_confirmation" required
                                                                    placeholder="Confirm New Password">
                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
                                                                    <span>
                                                                        <label
                                                                            class="error"><?php echo e(session('error')); ?></label>
                                                                    </span>
                                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                <span class="eyeInput eye_icon"
                                                                    data-id="newpassword_confirmation">
                                                                    <i class="bi bi-eye-slash"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 text-center my-2 mt-4">
                                                        <button type="submit"
                                                            class="btn btn-primary-gradient fs-7 rounded-2 w-75">Update</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>


    <div class="modal fade" id="imageCropModal" tabindex="-1" aria-labelledby="imageCropModalLabel"
        aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageCropModalLabel">Crop Profile Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="img-container">
                        <img id="imageToCrop" src="" alt="Profile Image to Crop" style="max-width: 100%;">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="cropImageBtn">Crop & Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="logOut">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header justify-content-end align-items-start border-0">
                    <a href="javascript:void(0);" onclick="location.reload();"><button type="button"
                            class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button></a>
                </div>
                <div class="modal-body pt-0">
                    <form>
                        <lottie-player src="<?php echo e(asset('frontend/images/logout.json')); ?>" background="transparent"
                            speed="1" style="width: 140px; height: 140px;margin: auto;" loop=""
                            autoplay=""></lottie-player>
                        <h6 class="text-center fw-semibold">Logout Account</h6>
                        <p class="text-center fs-8">Are you sure you want to logout? Once you logout you need to
                            login
                            again.
                        </p>
                        <div class="d-flex align-items-center justify-content-end flex-column mt-4">
                            <a href="<?php echo e(route('logout')); ?>"class="btn btn-primary-gradient fs-7 rounded-2 w-50 mb-2">Yes
                            </a>
                            <a href="javascript:void(0);" onclick="location.reload();">
                                <button type="button" class="btn backbtn fw-regular my-2">Back</button>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo e(asset('frontend/js/script.js')); ?>"></script>
    <script src="<?php echo e(asset('admin/vendor/sweetalert2-7.0.0/sweetalert2.min.js')); ?>"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>


    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.14.0/jquery-ui.js"></script>
    <script src="<?php echo e(asset('admin/vendor/quill/quill.min.js')); ?>"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script>
        $(function() {
            $("#datepicker").datepicker();
            $("#datepicker1").datepicker();
        });
    </script>
    <script>
        $(function() {
            $('#multiSelect').select2({
                placeholder: "--Select--",
                allowClear: true
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            // Target all modals with the 'coursePrv' class
            const modals = document.querySelectorAll('.coursePrv');

            modals.forEach(modal => {
                modal.addEventListener('hidden.bs.modal', function() {
                    // Pause all videos inside the modal
                    const videos = modal.querySelectorAll('video');
                    videos.forEach(video => {
                        video.pause();
                        video.currentTime = 0; // Optional: Reset video to start
                    });
                });
            });
        });
    </script>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>


    <script>
        let cropper;
        let profileImageInput = document.getElementById('profileHeader');
        let profileImagePreview = document.getElementById('profileImage');

        // When a new image is selected
        profileImageInput.addEventListener('change', function(e) {
            const files = e.target.files;

            if (files && files.length > 0) {
                const file = files[0];

                if (!file.type.match('image.*')) {
                    alert('Please select an image file (jpeg, png, jpg, gif)');
                    return;
                }

                const imageURL = URL.createObjectURL(file);
                const imageToCrop = document.getElementById('imageToCrop');
                imageToCrop.src = imageURL;

                // Destroy previous cropper instance if it exists
                if (cropper) {
                    cropper.destroy();
                }

                // Initialize the modal without the event listener that causes recursion
                const cropModal = new bootstrap.Modal(document.getElementById('imageCropModal'));
                cropModal.show();

                // Initialize cropper after a small delay to ensure modal is fully shown
                setTimeout(() => {
                    cropper = new Cropper(imageToCrop, {
                        aspectRatio: 1,
                        viewMode: 1,
                        autoCropArea: 0.8,
                        responsive: true
                    });
                }, 200);
            }
        });

        // Handle the crop button click
        document.getElementById('cropImageBtn').addEventListener('click', function() {
            if (!cropper) return;

            const canvas = cropper.getCroppedCanvas({
                width: 500,
                height: 500,
                minWidth: 256,
                minHeight: 256,
                fillColor: '#fff',
                imageSmoothingQuality: 'high',
            });

            if (canvas) {
                canvas.toBlob(function(blob) {
                    const file = new File([blob], 'profile-image.jpg', {
                        type: 'image/jpeg'
                    });
                    const formData = new FormData();
                    formData.append('profile_image', file);

                    profileImagePreview.src = URL.createObjectURL(blob);

                    // Hide the crop modal properly
                    bootstrap.Modal.getInstance(document.getElementById('imageCropModal')).hide();

                    uploadProfileImage(formData);
                }, 'image/jpeg', 0.9);
            }
        });

        // Clean up when modal is hidden
        document.getElementById('imageCropModal').addEventListener('hidden.bs.modal', function() {
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
        });

        function uploadProfileImage(formData) {
            fetch('<?php echo e(route('sp.upload.profile.image')); ?>', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                        'Accept': 'application/json',
                    },
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const timestamp = new Date().getTime();
                        profileImagePreview.src = data.filePath + '?t=' + timestamp;
                        alert('Profile image updated successfully!');
                    } else {
                        throw new Error(data.message || 'Failed to upload profile image');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error uploading image: ' + error.message);
                });
        }
    </script>

    

    <script>
        // Disable right-click
        document.addEventListener("contextmenu", function(event) {
            event.preventDefault();
        });

        // // Disable specific keyboard shortcuts
        // document.addEventListener("keydown", function(event) {
        //     if (event.ctrlKey && (event.key === "u" || event.key === "U" ||
        //             event.key === "s" || event.key === "S" ||
        //             event.key === "h" || event.key === "H" ||
        //             event.key === "j" || event.key === "J" ||
        //             event.key === "i" || event.key === "I" ||
        //             event.key === "c" || event.key === "C")) {
        //         event.preventDefault();
        //         alert("This function is disabled!");
        //     }

        //     if (event.key === "F12") {
        //         event.preventDefault();
        //         alert("This function is disabled!");
        //     }
        // });

        // Disable Developer Tools (Inspect Element)
        // setInterval(function() {
        //     if (window.outerHeight - window.innerHeight > 200 || window.outerWidth - window.innerWidth > 200) {
        //         document.body.innerHTML = "<h3>Developer Tools are disabled!</h3>";
        //     }
        // }, 1000);
    </script>>
    <script>
        $(function() {
            $("#datepicker").datepicker();
        });
        $(".js-select2").select2({
            closeOnSelect: false,
            placeholder: "Select",
            allowClear: false,
            tags: true
        });


        $('.toggleBtn').click(function() {
            $('body').toggleClass("open-sidebar");
        });


        $('.alertList').slick({
            autoplay: true,
            slidesToShow: 1,
            arrows: false,
            dots: false,
            autoplaySpeed: 0,
            speed: 30000,
            cssEase: 'linear',
            variableWidth: true,
            pauseOnHover: true
        });

        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))
    </script>
    <script>
        $(document).ready(function() {
            $('#admin-state-select').on('change', function() {
                var stateId = $(this).val();
                $('#admin-city-select').html('<option value="">Select</option>');
                if (stateId) {
                    var url = "<?php echo e(route('sp.getCities', ':state')); ?>".replace(':state', stateId);
                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function(data) {
                            if (data && Object.keys(data).length > 0) {
                                $.each(data, function(id, name) {
                                    $('#admin-city-select').append('<option value="' +
                                        id +
                                        '">' + name + '</option>');
                                });
                            } else {
                                $('#admin-city-select').html(
                                    '<option value="">No cities available</option>');
                            }
                        },
                    });
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#teacher-state-select').on('change', function() {
                var stateId = $(this).val();
                $('#teacher-city-select').html('<option value="">Select</option>');
                if (stateId) {
                    var url = "<?php echo e(route('sp.getCities', ':state')); ?>".replace(':state', stateId);
                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function(data) {
                            if (data && Object.keys(data).length > 0) {
                                $.each(data, function(id, name) {
                                    $('#teacher-city-select').append('<option value="' +
                                        id +
                                        '">' + name + '</option>');
                                });
                            } else {
                                $('#teacher-city-select').html(
                                    '<option value="">No cities available</option>');
                            }
                        },
                    });
                }
            });
        });


        const dateInputs = document.querySelectorAll('.dateInput');
        // Loop through all date input elements and add the event listener
        dateInputs.forEach(function(input) {
            input.addEventListener('click', function() {
                this.showPicker(); // Show the date picker when the input is clicked
            });
        });
    </script>
</body>

</html>
<?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/schoolPortal/layouts/master.blade.php ENDPATH**/ ?>