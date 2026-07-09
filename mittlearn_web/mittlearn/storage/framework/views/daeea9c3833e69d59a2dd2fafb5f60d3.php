<?php $__env->startSection('content'); ?>
    <div id="page-header" class="page-header">

        <div class="pagetitle">
            <h1>Profile</h1>
        </div>
        <!-- End Page Title -->

        <section class="section profile">
            <div class="row">
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-body profile-card pt-4 d-flex flex-column align-items-center">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(
                                (isset($data->image) && !empty($data->image))): ?>
                                <img src="<?php echo e(Storage::url('uploads/user/profile_image/' . $data->image)); ?>"
                                    alt="Profile Image" class="rounded-circle">
                            <?php else: ?>
                                <img src="<?php echo e(asset('frontend/images/default-image.jpg')); ?>" alt="Default Image"
                                    class="rounded-circle">
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <h2><?php echo e($data->name ?? 'N/A'); ?></h2>
                            <h3><?php echo e($data->userRole->role->role_name ?? 'N/A'); ?></h3>

                        </div>
                    </div>

                </div>

                <div class="col-xl-8">

                    <div class="card">
                        <div class="card-body pt-3">
                            <!-- Bordered Tabs -->
                            <ul class="nav nav-tabs nav-tabs-bordered">

                                <li class="nav-item">
                                    <button class="nav-link active" data-bs-toggle="tab"
                                        data-bs-target="#profile-overview">Overview</button>
                                </li>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(
                                    (isset($data->userRole->role_slug) && $data->userRole->role_slug === 'school_admin') ||
                                        $data->userRole->role_slug === 'school_teacher' ||
                                        $data->userRole->role_slug === 'school_student'): ?>
                                    <li class="nav-item">
                                        <button class="nav-link" data-bs-toggle="tab"
                                            data-bs-target="#Bank-additional-Details"> Additional Details</button>
                                    </li>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($data->userRole->role_slug) && $data->userRole->role_slug === 'school_admin'): ?>
                                    <li class="nav-item">
                                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#Bank-Details">Bank
                                            Details
                                        </button>
                                    </li>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(isset($data->userRole->role_slug) && $data->userRole->role_slug === 'b2c_student'): ?>
                                    <li class="nav-item">
                                        <button class="nav-link" data-bs-toggle="tab"
                                            data-bs-target="#Address-Details">Address
                                        </button>
                                    </li>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(
                                    (isset($data->userRole->role_slug) && $data->userRole->role_slug === 'leaders') ): ?>
                                    <li class="nav-item">
                                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#Social_media"> Social
                                            Media Links</button>
                                    </li>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </ul>
                            <div class="tab-content pt-2">
                                <div class="tab-pane fade show active profile-overview" id="profile-overview">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($data->userAdditionalDetail->about)): ?>
                                        <h5 class="card-title">About</h5>
                                        <p class="small fst-italic"><?php echo e($data->userAdditionalDetail->about ?? 'N/A'); ?></p>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                    <h5 class="card-title mt-2">Profile Details</h5>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label mt-2">Full Name</div>
                                        <div class="col-lg-9 col-md-8"><?php echo e($data->name ?? 'N/A'); ?></div>
                                    </div>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($data->userRole->role_slug === 'salesman'): ?>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-4 label">Employee ID</div>
                                            <div class="col-lg-9 col-md-8">
                                                <?php echo e($data->userAdditionalDetail->employee_id ?? 'N/A'); ?></div>
                                        </div>
                                    <?php elseif($data->userRole->role_slug === 'distributors'): ?>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-4 label">Distributor ID</div>
                                            <div class="col-lg-9 col-md-8">
                                                <?php echo e($data->userAdditionalDetail->distributor_id ?? 'N/A'); ?></div>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Role Name</div>
                                        <div class="col-lg-9 col-md-8"><?php echo e($data->userRole->role->role_name ?? 'N/A'); ?></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">
                                            <?php echo e($data->userRole->role_slug === 'school_student' ? 'Parent Contact Number' : 'Contact Number'); ?>

                                        </div>
                                        <div class="col-lg-9 col-md-8">
                                            <?php echo e($data->userRole->role->role_slug === 'school_admin'
                                                ? $data->userAdditionalDetail->decision_maker_mobile_no ?? 'N/A'
                                                : $data->mobile_no ?? 'N/A'); ?>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-3 col-md-4 label">Email</div>
                                        <div class="col-lg-9 col-md-8"><?php echo e($data->email ?? 'N/A'); ?></div>
                                    </div>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($data->userRole->role_slug != 'school_student' && $data->userRole->role_slug != 'b2c_student'): ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($data->userRole->role_slug != 'salesman' && $data->userRole->role_slug != 'instructor' && $data->userRole->role_slug != 'distributors'): ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($data->userRole->role_slug != 'school_admin'): ?>
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-4 label">Gender</div>
                                                    <div class="col-lg-9 col-md-8">
                                                        <?php echo e($data->userAdditionalDetail->gender ?? 'N/A'); ?>

                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-4 label">Age</div>
                                                    <div class="col-lg-9 col-md-8">
                                                        <?php echo e($data->userAdditionalDetail->age ?? 'N/A'); ?>

                                                    </div>
                                                </div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            <div class="row">
                                                <div class="col-lg-3 col-md-4 label">Designation</div>
                                                <div class="col-lg-9 col-md-8">
                                                    <?php echo e($data->userAdditionalDetail->designation ?? 'N/A'); ?>

                                                </div>
                                            </div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                        
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($data->userRole->role_slug != 'leaders'): ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($data->userRole->role_slug != 'salesman' && $data->userRole->role_slug != 'distributors'): ?>
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-4 label">Country</div>
                                                    <div class="col-lg-9 col-md-8">
                                                        <?php echo e(isset($data->userAdditionalDetail->country) ? ucwords($data->userAdditionalDetail->country) : null ?? 'India'); ?>

                                                    </div>
                                                </div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                            <div class="row">
                                                <div class="col-lg-3 col-md-4 label">State</div>
                                                <div class="col-lg-9 col-md-8">
                                                    <?php echo e($data->userAdditionalDetail->State->name ?? 'N/A'); ?>

                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-lg-3 col-md-4 label">City</div>
                                                <div class="col-lg-9 col-md-8">
                                                    <?php echo e($data->userAdditionalDetail->City->city ?? 'N/A'); ?>

                                                </div>
                                            </div>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(
                                                $data->userRole->role_slug != 'school_teacher' &&
                                                    $data->userRole->role_slug != 'salesman' && $data->userRole->role_slug != 'instructor' && 
                                                    $data->userRole->role_slug != 'distributors'): ?>
                                                <div class="row">
                                                    <div class="col-lg-3 col-md-4 label">Pin Code</div>
                                                    <div class="col-lg-9 col-md-8">
                                                        <?php echo e($data->schoolDetails->postal_code ?? 'N/A'); ?>

                                                    </div>
                                                </div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                            <div class="row">
                                                <div class="col-lg-3 col-md-4 label">Address</div>
                                                <div class="col-lg-9 col-md-8">
                                                    <?php echo e($data->userAdditionalDetail->address ?? 'N/A'); ?>

                                                </div>
                                            </div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                </div>

                                <div class="tab-pane fade Bank-additional-Details view pt-3" id="Bank-additional-Details">
                                    <h5 class="card-title">Additional Details</h5>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($data->userRole->role_slug) && $data->userRole->role_slug === 'school_admin'): ?>
                                        <div class="row mb-3">
                                            <label class="col-md-4 col-lg-3 col-form-label">Parent School Name</label>
                                            <div class="col-md-8 col-lg-9">
                                                <?php echo e($data->userAdditionalDetail->parent_school_name ?? 'N/A'); ?>

                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-md-4 col-lg-3 col-form-label">Website Name</label>
                                            <div class="col-md-8 col-lg-9">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($data->userAdditionalDetail->website)): ?>
                                                    <a href="<?php echo e($data->userAdditionalDetail->website); ?>" target="_blank"
                                                        rel="noopener noreferrer">
                                                        <?php echo e($data->userAdditionalDetail->website); ?>

                                                    </a>
                                                <?php else: ?>
                                                    <span>N/A</span>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-md-4 col-lg-3 col-form-label">Decision Maker Role</label>
                                            <div class="col-md-8 col-lg-9">
                                                <?php echo e($data->userAdditionalDetail->roleName->role_name ?? 'N/A'); ?>

                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-md-4 col-lg-3 col-form-label">Decision Mobile Number</label>
                                            <div class="col-md-8 col-lg-9">
                                                <?php echo e($data->userAdditionalDetail->decision_maker_mobile_no ?? 'N/A'); ?>

                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-md-4 col-lg-3 col-form-label">School Registration
                                                Number</label>
                                            <div class="col-md-8 col-lg-9">
                                                <?php echo e($data->userAdditionalDetail->school_registration_no ?? 'N/A'); ?>

                                            </div>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(isset($data->userRole->role_slug) && $data->userRole->role_slug === 'school_student'): ?>
                                        <div class="row mb-3">
                                            <label class="col-md-4 col-lg-3 col-form-label">Admission Number</label>
                                            <div class="col-md-8 col-lg-9">
                                                <?php echo e($data->userAdditionalDetail->admission_no ?? 'N/A'); ?>

                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-md-4 col-lg-3 col-form-label">Admission Date</label>
                                            <div class="col-md-8 col-lg-9">
                                                <?php echo e($data->studentDetails->doj ?? 'N/A'); ?>

                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-md-4 col-lg-3 col-form-label">Data of Birth</label>
                                            <div class="col-md-8 col-lg-9">
                                                <?php echo e($data->studentDetails->dob ?? 'N/A'); ?>

                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-md-4 col-lg-3 col-form-label">Class</label>
                                            <div class="col-md-8 col-lg-9">
                                                <?php echo e($data->studentDetails->studentClass->name ?? 'N/A'); ?>

                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-md-4 col-lg-3 col-form-label">Section</label>
                                            <div class="col-md-8 col-lg-9">
                                                <?php echo e($data->studentDetails->section ?? 'N/A'); ?>

                                            </div>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php if(isset($data->userRole->role_slug) && $data->userRole->role_slug === 'school_teacher'): ?>
                                        <div class="row mb-3">
                                            <label class="col-md-4 col-lg-3 col-form-label">Qualification</label>
                                            <div class="col-md-8 col-lg-9">
                                                <?php echo e($data->userAdditionalDetail->qualification ?? 'N/A'); ?>

                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label class="col-md-4 col-lg-3 col-form-label">Year of Experience</label>
                                            <div class="col-md-8 col-lg-9">
                                                <?php echo e($data->userAdditionalDetail->experience ?? 'N/A'); ?>

                                            </div>
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <div class="tab-pane fade Bank-Details view pt-3" id="Bank-Details">
                                    <h5 class="card-title">Bank Details</h5>

                                    <div class="row mb-3">
                                        <label class="col-md-4 col-lg-3 col-form-label">Bank Name</label>
                                        <div class="col-md-8 col-lg-9">
                                            <?php echo e($data->userAdditionalDetail->bank_name ?? 'N/A'); ?>

                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-md-4 col-lg-3 col-form-label">Bank Holder Name</label>
                                        <div class="col-md-8 col-lg-9">
                                            <?php echo e($data->userAdditionalDetail->acc_holder_name ?? 'N/A'); ?>

                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-md-4 col-lg-3 col-form-label">Bank Account Number</label>
                                        <div class="col-md-8 col-lg-9">
                                            <?php echo e($data->userAdditionalDetail->acc_no ?? 'N/A'); ?>

                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-md-4 col-lg-3 col-form-label">Bank IFSC code</label>
                                        <div class="col-md-8 col-lg-9">
                                            <?php echo e($data->userAdditionalDetail->ifsc_code ?? 'N/A'); ?>

                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade Address-Details view pt-3" id="Address-Details">
                                    <h5 class="card-title">Address Details</h5>

                                    <div class="row mb-3">
                                        <label class="col-md-4 col-lg-3 col-form-label">Pin code</label>
                                        <div class="col-md-8 col-lg-9">
                                            <?php echo e($data->userAdditionalDetail->postal_code ?? 'N/A'); ?>

                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-md-4 col-lg-3 col-form-label">State</label>
                                        <div class="col-md-8 col-lg-9">
                                            <?php echo e($data->userAdditionalDetail->state ?? 'N/A'); ?>

                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-md-4 col-lg-3 col-form-label">City</label>
                                        <div class="col-md-8 col-lg-9">
                                            <?php echo e($data->userAdditionalDetail->city ?? 'N/A'); ?>

                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-md-4 col-lg-3 col-form-label">Address</label>
                                        <div class="col-md-8 col-lg-9">
                                            <?php echo e($data->userAdditionalDetail->address ?? 'N/A'); ?>

                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade Social_media view pt-3" id="Social_media">
                                    <h5 class="card-title">Social Media Links Details</h5>

                                    <div class="row mb-3">
                                        <label class="col-md-4 col-lg-3 col-form-label">Twitter</label>
                                        <div class="col-md-8 col-lg-9">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($data->userAdditionalDetail->twitter)): ?>
                                                <a href="<?php echo e($data->userAdditionalDetail->twitter); ?>" target="_blank"
                                                    rel="noopener noreferrer">
                                                    <?php echo e($data->userAdditionalDetail->twitter); ?>

                                                </a>
                                            <?php else: ?>
                                                <span>N/A</span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-md-4 col-lg-3 col-form-label">Instagram</label>
                                        <div class="col-md-8 col-lg-9">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($data->userAdditionalDetail->instagram)): ?>
                                                <a href="<?php echo e($data->userAdditionalDetail->instagram); ?>" target="_blank"
                                                    rel="noopener noreferrer">
                                                    <?php echo e($data->userAdditionalDetail->instagram); ?>

                                                </a>
                                            <?php else: ?>
                                                <span>N/A</span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-md-4 col-lg-3 col-form-label">Facebook</label>
                                        <div class="col-md-8 col-lg-9">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($data->userAdditionalDetail->facebook)): ?>
                                                <a href="<?php echo e($data->userAdditionalDetail->facebook); ?>" target="_blank"
                                                    rel="noopener noreferrer">
                                                    <?php echo e($data->userAdditionalDetail->facebook); ?>

                                                </a>
                                            <?php else: ?>
                                                <span>N/A</span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label class="col-md-4 col-lg-3 col-form-label">LinkedIn</label>
                                        <div class="col-md-8 col-lg-9">
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($data->userAdditionalDetail->linkedin)): ?>
                                                <a href="<?php echo e($data->userAdditionalDetail->linkedin); ?>" target="_blank"
                                                    rel="noopener noreferrer">
                                                    <?php echo e($data->userAdditionalDetail->linkedin); ?>

                                                </a>
                                            <?php else: ?>
                                                <span>N/A</span>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                            </div><!-- End Bordered Tabs -->

                        </div>
                    </div>

                </div>
            </div>
        </section>

    </div>
    <!-- End #div -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/admin/user/user-profile.blade.php ENDPATH**/ ?>