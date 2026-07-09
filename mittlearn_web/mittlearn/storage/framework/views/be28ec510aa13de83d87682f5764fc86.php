<?php $__env->startSection('content'); ?>
    <div class="loginMain">
        <div class="loginMain">
            <style>
                /* Add border and square look */
                .custom-check-olympiad {
                    height: 15px !important;
                    width: 15px !important;
                    appearance: none;
                    /* Reset default browser styles */
                    -webkit-appearance: none;
                    -moz-appearance: none;
                    background-color: #fff;
                    /* White background */
                    cursor: pointer;
                    display: inline-block;
                    position: relative;
                }

                /* Add checkmark when checked */
                .custom-check-olympiad:checked {
                    background-color: #044783;
                    /* Bootstrap blue */
                    border-color: #044783;
                }

                .custom-check-olympiad:checked::after {
                    content: "✔";
                    color: #fff;
                    font-size: 12px;
                    position: absolute;
                    top: -1px;
                    left: 2px;
                }
            </style>
            <div class="loginSec registerPage">
                <div class="pb-3 text-center">
                    <a href="<?php echo e(route('/')); ?>"><img src="<?php echo e(asset(config('constants.SITE_LOGO'))); ?>" alt=""
                            width="200" /></a>
                </div>
                <div class="loginFormBox">
                    <h3>Registration</h3>
                    <p class=" mb-4">Hey, Enter your details to get your account register</p>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
                        <span>
                            <label class="error"><?php echo e(session('error')); ?></label>
                        </span>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <form method="POST" action="<?php echo e(route('register.store')); ?>" id="register-password-form">
                        <?php echo csrf_field(); ?>
                        <div class="row px-md-1">
                            <div class="col-md-6 px-md-2">
                                <div class="form-group mb-4">
                                    <label class="form-label required">Name</label>
                                    <input class="form-control w-100 <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="text"
                                        placeholder="" name="name" value="<?php echo e($data->name ?? old('name')); ?>"
                                        autocomplete="name" autofocus>
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

                            <div class="col-md-6 px-md-2">
                                <div class="form-group mb-4">
                                    <label class="form-label">Email</label>
                                    <div class="position-relative">
                                        <input class="form-control w-100 pe-5 <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="email" type="email" placeholder="" name="email"
                                            value="<?php echo e($data->email ?? old('email')); ?>" autocomplete="email">
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
                            </div>

                            <div class="col-md-6 px-md-2">
                                <div class="form-group mb-4">
                                    <label class="form-label required">Mobile Number</label>
                                    <div class="position-relative">
                                        <input class="form-control w-100 pe-5 <?php $__errorArgs = ['mobile'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            type="text" placeholder="" id="mobile" name="mobile"
                                            value="<?php echo e($data->mobile_no ?? old('mobile')); ?>" autocomplete="mobile" autofocus>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['mobile'];
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


                            <div class="col-md-6 px-md-2">
                                <div class="form-group mb-4">
                                    <label for="userType" class="mb-2 required">User Type</label>
                                    <select name="userType" id="userType"
                                        class="form-control fs-8 <?php $__errorArgs = ['userType'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                        <option value="">Select User Type</option>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = config('constants.USER_TYPES'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($key); ?>"
                                                <?php echo e(old('userType') == $key ? 'selected' : ''); ?>><?php echo e($value); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </select>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['userType'];
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
                            <div class="col-md-12 px-md-2 olympiad-check" style="display:none;">
                                <div class="form-group mb-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input custom-check-olympiad"
                                            id="isOlympiadUser" name="isOlympiadUser" value="1">
                                        <label class="form-check-label" for="isOlympiadUser">Are you registering for MOM (Olympiad)?</label>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12 px-md-2 access-code-field" style="display:none;">
                                <div class="form-group mb-4">
                                    <label for="accessCode" class="form-label required">Access Code</label>
                                    <input type="text" name="access_code" id="accessCode"
                                        class="form-control fs-8 <?php $__errorArgs = ['accessCode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        placeholder="Enter Access Code">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['access_code'];
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

                            <div class="col-md-6 px-md-2 user-come-for" style="display: none;">
                                <div class="form-group mb-4 ">
                                    <label for="userComeFor" class="mb-2 required">You Are Here For</label>
                                    <select name="userComeFor" id="userComeFor"
                                        class="form-control fs-8 <?php $__errorArgs = ['userComeFor'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                        <option value="">--Select--</option>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = config('constants.USER_COME_FOR'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($key); ?>"><?php echo e($value); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </select>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['userComeFor'];
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

                            <div class="col-md-6 px-md-2 school-field" style="display: none;">
                                <div class="form-group mb-4">
                                    <label for="schoolName" class="mb-2">School Name</label>
                                    <div id="schools-data" data-schools='<?php echo json_encode($schools, 15, 512) ?>'
                                        style="display: none;"></div>

                                    <input type="text" name="schoolNameSearch" id="schoolNameSearch"
                                        class="form-control fs-8 <?php $__errorArgs = ['schoolName'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                        placeholder="Search for a school" autocomplete="off">

                                    <select name="schoolName" id="schoolName" style="display: none;">
                                        <option value="" selected>Select School</option>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($school->id); ?>"
                                                data-classes="<?php echo e(json_encode($school->classes ?? [])); ?>">
                                                <?php echo e($school->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </select>

                                    <div id="schoolSearchResults" class="list-group mt-2"
                                        style="display: none; position: absolute; z-index:30">
                                    </div>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['schoolName'];
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

                            <div id="classSelectWrapper2" class="col-md-6 px-md-2 form-group mb-3"
                                style="display: none;">
                                <label for="className" class="mb-2 required">Class</label>
                                <select name="className" id="className2" required
                                    class="form-control form-select fs-8 <?php $__errorArgs = ['className'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                                    <option value="" selected>Select Class</option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($id); ?>"><?php echo e($name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </select>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['className'];
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

                            <div class="col-md-6 px-md-2">
                                <div class="form-group mb-4">
                                    <label class="form-label required">Password</label>
                                    <div class="position-relative">
                                        <input class="form-control w-100 pe-5 <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                            id="password" type="password" placeholder="" name="password"
                                            autocomplete="new-password">
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
                                        <span class="eyeInput eye_icon" data-id="password">
                                            <i class="bi bi-eye-slash"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 px-md-2">
                                <div class="form-group mb-4">
                                    <label class="form-label required">Confirm Password</label>
                                    <div class="position-relative">
                                        <input class="form-control w-100 pe-5"type="password" id="password_confirmation"
                                            placeholder="" name="password_confirmation" autocomplete="new-password">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['password_confirmation'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <span class="eyeInput eye_icon" data-id="password_confirmation">
                                            <i class="bi bi-eye-slash"></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group<?php echo e($errors->has('captcha') ? ' has-error' : ''); ?>">
                                <label for="captcha" class="col-md-4 mb-2 control-label required">Captcha</label>
                                <div class="col-md-12">
                                    <div class="captcha">
                                        <div class="d-flex align-items-center gap-2 mb-4">
                                            <span><?php echo captcha_img(); ?></span>
                                            <button type="button" class="bg-transparent border-0 btn-refresh">
                                                <i class="fa fa-refresh"></i>
                                            </button>
                                            <div style="flex-grow: 1;"> <!-- This ensures it takes available width -->
                                                <input id="captcha" type="text" class="form-control"
                                                    placeholder="Enter Captcha" name="captcha">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->has('captcha')): ?>
                                                    <div class="text-danger mt-1">
                                                        <small><?php echo e($errors->first('captcha')); ?></small>
                                                    </div>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="loginbtm mt-2">
                            <div class="cstmCheckbox">
                                <input type="checkbox" id="termsCheck" checked name="terms_accepted">
                                <label for="termsCheck">By Clicking you are indicating that you have read and agreed to
                                    the
                                    <a href="<?php echo e(route('terms.condition')); ?>">terms of use</a> & <a
                                        href="<?php echo e(route('privacy.policy')); ?>">Privacy
                                        policy</a></label>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['terms_accepted'];
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
                        <div class="text-center my-2 mt-4">
                            <button type="submit" class="btn btn-primary-gradient fs-7 rounded-2 w-75">Register</button>
                        </div>

                        <strong class="signupTxt pb-0">Already have an account?
                            <a href="<?php echo e(route('login')); ?>">Login</a>
                        </strong>

                    </form>
                </div>
            </div>
            <div class="mainBanner p-0">
                <span class="bgIcons1"><img src="<?php echo e(asset('frontend/images/bgIcon1.svg')); ?>" width="30"></span>
                <span class="bgIcons2"><img src="<?php echo e(asset('frontend/images/bgIcon2.png ')); ?>" width="50"></span>
                <span class="bgIcons3"><img src="<?php echo e(asset('frontend/images/bgIcon3.png ')); ?>" width="50"></span>
                <span class="bgIcons4"><img src="<?php echo e(asset('frontend/images/bgIcon4.png ')); ?>" width="50"></span>
                <span class="bgIcons5"><img src="<?php echo e(asset('frontend/images/bgIcon5.png ')); ?>" width="60"></span>
                <span class="bgIcons6"><img src="<?php echo e(asset('frontend/images/bgIcon6.png ')); ?>" width="40"></span>
                <span class="bgIcons7"><img src="<?php echo e(asset('frontend/images/bgIcon7.png ')); ?>" width="40"></span>
                <span class="bgIcons8"><img src="<?php echo e(asset('frontend/images/bgIcon8.png ')); ?>" width="55"></span>
                <span class="bgIcons9"><img src="<?php echo e(asset('frontend/images/bgIcon9.png ')); ?>" width="60"></span>
                <span class="bgIcons10"><img src="<?php echo e(asset('frontend/images/bgIcon10.png ')); ?>" width="55"></span>
                <span class="bgIcons11"><img src="<?php echo e(asset('frontend/images/bgIcon11.png ')); ?>" width="50"></span>
                <span class="bgIcons12"><img src="<?php echo e(asset('frontend/images/bgIcon12.png ')); ?>" width="50"></span>
                <span class="bgIcons13"><img src="<?php echo e(asset('frontend/images/bgIcon13.png ')); ?>" width="60"></span>
            </div>
        </div>



        <div class="modal fade" id="ragister" aria-hidden="true" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header border-0">
                        <h1 class="modal-title fs-5" id="exampleModalToggleLabel">Account Create</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-0">
                        <lottie-player src="<?php echo e(asset('frontend/images/advertising.json')); ?>" background="transparent"
                            speed="1" style="width: 180px; height: 180px;margin: auto;" loop
                            autoplay></lottie-player>
                        <h6 class="text-center">Congratulations!!</h6>
                        <p class="text-center">Your account has been successfully created.</p>
                        <div class="text-center my-2 mt-4">
                            <a href="<?php echo e(route('login')); ?>"
                                class="btn btn-primary-gradient fs-7 rounded-2 w-50">Continue</a>
                        </div>

                    </div>

                </div>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const olympiadCheckbox = document.getElementById('isOlympiadUser');
                const accessCodeField = document.querySelector('.access-code-field');

                // Sections to hide when Olympiad checkbox is checked
                const hideSections = [
                    document.getElementById('password').closest('.col-md-6'), // Password
                    document.getElementById('password_confirmation').closest('.col-md-6'), // Confirm password
                    document.querySelector('.form-group.has-error, .form-group[for="captcha"]') || document
                    .querySelector('[name="captcha"]').closest('.form-group'), // Captcha
                ];

                olympiadCheckbox.addEventListener('change', function() {
                    if (this.checked) {
                        // Show only Access Code
                        accessCodeField.style.display = 'block';
                        hideSections.forEach(sec => sec ? sec.style.display = 'none' : null);
                    } else {
                        // Restore everything
                        accessCodeField.style.display = 'none';
                        hideSections.forEach(sec => sec ? sec.style.display = '' : null);
                    }
                });
            });
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const userTypeSelect = document.getElementById('userType');
                const schoolField = document.querySelector('.school-field');
                const classSelectWrapper = document.getElementById('classSelectWrapper2');
                const olympiadCheck = document.querySelector('.olympiad-check');
                const olympiadCheckbox = document.getElementById('isOlympiadUser');
                const accessCodeField = document.querySelector('.access-code-field');

                const schoolOnlyTypes = ['school_admin', 'school_teacher'];
                const studentType = 'school_student';

                function resetOlympiad() {
                    olympiadCheckbox.checked = false;
                    accessCodeField.style.display = 'none';
                }

                userTypeSelect.addEventListener('change', function() {
                    const selectedUserType = this.value;

                    // Reset Olympiad logic
                    resetOlympiad();
                    olympiadCheck.style.display = 'none';
                    schoolField.style.display = 'none';
                    classSelectWrapper.style.display = 'none';

                    if (selectedUserType === studentType) {
                        // Show school + class + olympiad checkbox
                        schoolField.style.display = 'block';
                        classSelectWrapper.style.display = 'block';
                        olympiadCheck.style.display = 'block';
                    } else if (schoolOnlyTypes.includes(selectedUserType)) {
                        schoolField.style.display = 'block';
                    }
                });

                // Olympiad checkbox toggle
                olympiadCheckbox.addEventListener('change', function() {
                    if (this.checked) {
                        // Hide school + class
                        schoolField.style.display = 'none';
                        classSelectWrapper.style.display = 'none';
                        // Show access code
                        accessCodeField.style.display = 'block';
                    } else {
                        // Restore school + class
                        schoolField.style.display = 'block';
                        classSelectWrapper.style.display = 'block';
                        accessCodeField.style.display = 'none';
                    }
                });
            });

            document.addEventListener('DOMContentLoaded', function() {
                const userTypeSelect = document.getElementById('userType');
                const userComeFor = document.getElementById('userComeFor');
                const schoolField = document.querySelector('.school-field');
                const userComeForField = document.querySelector('.user-come-for');
                const classSelectWrapper = document.getElementById('classSelectWrapper2');
                const schoolNameSearch = document.getElementById('schoolNameSearch');
                const schoolNameSelect = document.getElementById('schoolName');
                const schoolNameLabel = document.querySelector('label[for="schoolName"]');

                const schoolOnlyTypes = ['school_admin', 'school_teacher'];
                const studentType = 'school_student';

                userTypeSelect.addEventListener('change', function() {
                    const selectedUserType = this.value;
                    if (selectedUserType == 'other') {
                        userComeForField.style.display = 'block';
                        userComeForField.setAttribute('required', 'required');
                        userComeFor.addEventListener('change', function() {
                            const selectedComeFrom = this.value;
                            if (selectedComeFrom == 'for_academic_content') {
                                classSelectWrapper.style.display = 'block';
                                classSelectWrapper.setAttribute('required', 'required');
                            } else if (selectedComeFrom == 'both') {
                                classSelectWrapper.style.display = 'block';
                                classSelectWrapper.setAttribute('required', 'required');
                            } else {
                                classSelectWrapper.style.display = 'none';
                            }
                        })
                    }
                    // Reset all fields
                    schoolField.style.display = 'none';
                    classSelectWrapper.style.display = 'none';
                    schoolNameSelect.value = '';
                    document.getElementById('className2').value = '';

                    // Remove required attributes by default
                    schoolNameSearch.removeAttribute('required');
                    schoolNameLabel.classList.remove('required');

                    if (schoolOnlyTypes.includes(selectedUserType)) {
                        // Show only school field for admins/teachers
                        schoolField.style.display = 'block';
                        userComeForField.style.display = 'none';

                    } else if (selectedUserType === studentType) {
                        // Show both fields for students and make school search required
                        schoolField.style.display = 'block';
                        userComeForField.style.display = 'none';

                        classSelectWrapper.style.display = 'block';
                        schoolNameSearch.setAttribute('required', 'required');
                        schoolNameLabel.classList.add('required');
                    }
                    // For 'other' type, nothing is shown (default state)
                });

                // Initialize on page load
                if (schoolOnlyTypes.includes(userTypeSelect.value)) {
                    schoolField.style.display = 'block';
                    userComeForField.style.display = 'none';

                } else if (userTypeSelect.value === studentType) {
                    schoolField.style.display = 'block';
                    classSelectWrapper.style.display = 'block';
                    userComeForField.style.display = 'none';
                    schoolNameSearch.setAttribute('required', 'required');
                    schoolNameLabel.classList.add('required');
                }
            });
        </script>

        <script>
            $(".btn-refresh").click(function() {
                $.ajax({
                    type: 'GET',
                    url: '/refresh-captcha',
                    success: function(data) {
                        $(".captcha span").html(data.captcha);
                    }
                });
            });
        </script>
    <?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/auth/register_after_user_type_add.blade.php ENDPATH**/ ?>