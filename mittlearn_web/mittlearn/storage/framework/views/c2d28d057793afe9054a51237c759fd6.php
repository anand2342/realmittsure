

<?php $__env->startSection('content'); ?>
    <?php
        // Check if $activeTab is set, if not, default to null
        $tab = $activeTab ?? null;
        $innertab = $activeTab2 ?? null;
        $error = $activeError ?? null;
    ?>
    <div class="loginMain">
        <div class="loginSec">
            <div class="pb-3 text-center">
                <a href="<?php echo e(route('/')); ?>"><img src="<?php echo e(asset(config('constants.SITE_LOGO'))); ?>" alt=""
                        width="200" /></a>
            </div>
            <div class="loginFormBox">
                <div class="text-center">
                    <h3 class="afterNone mb-0">Login</h3>
                    <p class="mb-4 mt-0">Hey, Enter your details to Login</p>
                </div>
                <ul class="nav nav-pills loginTabs mb-3">
                    <li class="nav-item">
                        <button class="nav-link <?php echo e($tab === 'otp' ? '' : 'active'); ?>" data-bs-toggle="pill"
                            data-bs-target="#loginwithPassword" type="button">Login with Password</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link <?php echo e($tab === 'otp' ? 'active' : ''); ?>" data-bs-toggle="pill"
                            data-bs-target="#loginwithOtp" type="button">Login with OTP</button>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane <?php echo e($tab === 'otp' ? '' : 'active'); ?>" id="loginwithPassword">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tab != 'otp' || $error === 'error'): ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
                                <span>
                                    <label class="error"><?php echo e(session('error')); ?></label>
                                </span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <form method="post" action="<?php echo e(route('login.submit')); ?>">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" id="guestUserId" name="guest_user_id" value="">
                            <div class="mb-4">
                                <label class="form-label">Email/ Mobile Number/ Username</label>
                                <input class="form-control w-100 <?php $__errorArgs = ['username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="text"
                                    name="username"
                                    value="<?php echo e($data ?? (Cookie::get('remember_username') ?? old('username'))); ?>"
                                    placeholder="" autocomplete="username" autofocus>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['username'];
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
                            <div class="mb-1">
                                <label class="form-label">Password</label>
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
                                        value="<?php echo e(Cookie::get('remember_password') ? decrypt(Cookie::get('remember_password')) : ''); ?>"
                                        autocomplete="current-password">
                                    <span class="eyeInput eye_icon" data-id="password">
                                        <i class="bi bi-eye-slash"></i>
                                    </span>
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
                            <div class="loginbtm mt-2">
                                <div class="cstmCheckbox">
                                    <input type="checkbox" id="rememberCheck" name="remember"
                                        <?php echo e(old('remember', Cookie::get('remember_username') ? 'checked' : '')); ?>> <label
                                        for="rememberCheck">Remember Me</label>
                                </div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('forgot_password')): ?>
                                    <a href="<?php echo e(route('forgot_password')); ?>">
                                        <?php echo e(__('Forgot Password?')); ?>

                                    </a>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div class="text-center my-2 mt-4">
                                <button type="submit" class="btn btn-primary-gradient fs-7 rounded-2 w-75">Login</button>
                            </div>

                            <strong class="signupTxt pb-0">Don't have an account?
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('admin.register')): ?>
                                    <a href="<?php echo e(route('register')); ?>">
                                        <?php echo e(__('Register')); ?>

                                    </a>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </strong>
                        </form>
                    </div>

                    <div class="tab-pane <?php echo e($tab === 'otp' ? 'active' : ''); ?>" id="loginwithOtp">

                        <div class="emailDiv <?php echo e($innertab === 'otp2' ? 'd-none' : ''); ?>">
                            <form method="post" action="<?php echo e(route('login.otp.fill')); ?>">
                                <?php echo csrf_field(); ?>

                                <div class="mb-4">
                                    <label class="form-label">Email/Mobile Number</label>
                                    <input class="form-control w-100 <?php $__errorArgs = ['id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" type="text"
                                        name="id" value="<?php echo e(old('id')); ?>" required autocomplete="id" autofocus
                                        placeholder="">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($error === 'error' || $tab === 'otp'): ?>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('error')): ?>
                                            <span>
                                                <label class="error"><?php echo e(session('error')); ?></label>
                                            </span>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>

                                <div class="text-center my-2 mt-4">
                                    <button type="submit"
                                        class="btn btn-primary-gradient fs-7 rounded-2 w-75">Submit</button>
                                </div>
                                <strong class="signupTxt pb-0">Don't have an account?
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Route::has('admin.register')): ?>
                                        <a href="<?php echo e(route('register')); ?>">
                                            <?php echo e(__('Register')); ?>

                                        </a>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </strong>

                            </form>
                        </div>

                        
                    </div>
                </div>
            </div>
            <div class="mainBanner p-0">
                <span class="bgIcons1"><img src="<?php echo e(asset('frontend/images/bgIcon1.svg')); ?>" width="30"></span>
                <span class="bgIcons2"><img src="<?php echo e(asset('frontend/images/bgIcon2.png')); ?>" width="50"></span>
                <span class="bgIcons3"><img src="<?php echo e(asset('frontend/images/bgIcon3.png')); ?>" width="50"></span>
                <span class="bgIcons4"><img src="<?php echo e(asset('frontend/images/bgIcon4.png')); ?>" width="50"></span>
                <span class="bgIcons5"><img src="<?php echo e(asset('frontend/images/bgIcon5.png')); ?>" width="60"></span>
                <span class="bgIcons6"><img src="<?php echo e(asset('frontend/images/bgIcon6.png')); ?>" width="40"></span>
                <span class="bgIcons7"><img src="<?php echo e(asset('frontend/images/bgIcon7.png')); ?>" width="40"></span>
                <span class="bgIcons8"><img src="<?php echo e(asset('frontend/images/bgIcon8.png')); ?>" width="55"></span>
                <span class="bgIcons9"><img src="<?php echo e(asset('frontend/images/bgIcon9.png')); ?>" width="60"></span>
                <span class="bgIcons10"><img src="<?php echo e(asset('frontend/images/bgIcon10.png')); ?>" width="55"></span>
                <span class="bgIcons11"><img src="<?php echo e(asset('frontend/images/bgIcon11.png')); ?>" width="50"></span>
                <span class="bgIcons12"><img src="<?php echo e(asset('frontend/images/bgIcon12.png')); ?>" width="50"></span>
                <span class="bgIcons13"><img src="<?php echo e(asset('frontend/images/bgIcon13.png')); ?>" width="60"></span>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#resend_otp').on('click', function() {
                const mobileEmail = $('#id').val();
                $.ajax({
                    url: '<?php echo e(route('login.resend.otp')); ?>',
                    type: 'POST',
                    data: {
                        mobile_email: mobileEmail,
                        _token: '<?php echo e(csrf_token()); ?>'
                    },

                });
            });
        });
    </script>
    <script>
        function moveToNext(current) {
            const next = current.nextElementSibling;
            if (current.value.length === current.maxLength && next) {
                next.focus();
            }
        }
        document.querySelectorAll('.otp-input').forEach((input) => {
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && input.value === '') {
                    const previous = input.previousElementSibling;
                    if (previous) {
                        previous.focus();
                    }
                }
            });
        });
    </script>
    <script>
        function handleInput(input, index) {
            const value = input.value;

            // Move to next input if a digit is entered
            if (value.length === 1 && index < 5) {
                const nextInput = input.parentNode.children[index + 1];
                if (nextInput) nextInput.focus();
            }

            // Move to previous input if the input is deleted
            if (value.length === 0) {
                if (index > 0) {
                    const prevInput = input.parentNode.children[index - 1];
                    if (prevInput) {
                        prevInput.focus();
                        prevInput.select(); // Select content to easily replace it
                    }
                }
            }
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/auth/login-new.blade.php ENDPATH**/ ?>