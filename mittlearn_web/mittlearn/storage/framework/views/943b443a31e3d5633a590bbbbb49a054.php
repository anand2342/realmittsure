<?php $__env->startSection('content'); ?>
    <div>
        <div class="contactBanner">
            <div class="lottieSquare">
                <lottie-player src="<?php echo e(asset('frontend/images/square-shape-loading.json')); ?>" autoPlay loop
                    style="width: 120px; height: 120px;"></lottie-player>
            </div>
            <img src="<?php echo e(asset('frontend/images/blue-square.svg')); ?>" alt="" width="80" class="squareImg">
            <div class="container">
                <div class="bannerTxt">
                    <h1>We are here for you, contact us <b>anytime</b></h1>
                    <p>Have any questions about our services or just want to talk with us?<br> Reach us out </p>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-1"></i>
                            <?php echo e(session('success')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                </div>
            </div>
        </div>
        <div class="contactUs">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6 col-lg-3 col-xl-3 px-md-2 mb-3">
                        <div class="detailsContact h-100">
                            <a target="_blank" href="mailto:<?php echo e($getSetting['email']); ?>">
                                <figure>
                                    <a href="mailto:<?php echo e($getSetting['email']); ?>"> <img
                                            src="<?php echo e(asset('frontend/images/mailus-contact.svg')); ?>" alt=""
                                            width="50"></a>
                                </figure>
                            </a>
                            <span>Mail Us <b>We're here to help</b></span>
                            <hr>
                            <a href="mailto:<?php echo e($getSetting['email']); ?>" style="text-decoration: none; color: inherit;">
                                <strong><?php echo e($getSetting['email']); ?></strong></a>

                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 col-xl-3 px-md-2 mb-3">
                        <div class="detailsContact h-100">
                            <a target="_blank" href="tel:18008917070">
                                <figure>
                                    <a href="tel:+1800 8917070"><img src="<?php echo e(asset('frontend/images/callus-contact.svg')); ?>"
                                            alt="" width="50"></a>
                                </figure>
                            </a>
                            <span>Call Us <b>Speak to our Team</b></span>
                            <hr>
                            <a href="tel:+1800 8917070" style="text-decoration: none; color: inherit;">
                                <strong><?php echo e($getSetting['contact_number']); ?></strong></a>
                            
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 col-xl-3 px-md-2 mb-3">
                        <div class="detailsContact h-100">
                            <a target="_blank" href="https://maps.app.goo.gl/A9Xs2YYd56diPzqdA">
                                <figure>
                                    <img src="<?php echo e(asset('frontend/images/location-contact.svg')); ?>" alt=""
                                        width="50">
                                </figure>
                            </a>
                            <span>Visit Us <b>Visit our Office HQ</b></span>
                            <hr>
                            <strong><?php echo e($getSetting['address']); ?></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="messageMain">
            <div class="lottieRactangle">
                <lottie-player src="<?php echo e(asset('frontend/images/master-loading.json')); ?>" autoPlay loop
                    style="width: 180px; height: 180px;opacity: .4;"></lottie-player>
            </div>
            <div class="container">
                <div class="row m-0">
                    <div class="col-lg-5 col-md-12 p-0">
                        <figure class="sendForm">
                            <img src="<?php echo e(asset('frontend/images/contact-us.jpg')); ?>" alt="">
                        </figure>
                    </div>
                    <div class="col-lg-7 col-md-12 p-0">
                        <div class="sendMessage">
                            <h6>Have a question or feedback?</h6>
                            <p>We’re just a message away! Fill in the details below and our team will get back to you
                                shortly.
                            </p>
                            <form method="POST" action="<?php echo e(route('contact-us.save')); ?>">
                                <?php echo csrf_field(); ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label required">Your Name</label>
                                            <input class="form-control w-100 <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                type="text" name="name" placeholder="Enter your name"
                                                value="<?php echo e(old('name')); ?>" required>
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
                                        <div class="mb-3">
                                            <label class="form-label required">Your Email</label>
                                            <input class="form-control w-100 <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                type="email" name="email" placeholder="Enter your email"
                                                value="<?php echo e(old('email')); ?>" required>
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
                                        <div class="mb-3">
                                            <label class="form-label required">Your Mobile Number</label>
                                            <input class="form-control w-100 <?php $__errorArgs = ['mobile_no'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                type="number" name="mobile_no" placeholder="Enter your mobile number"
                                                value="<?php echo e(old('mobile_no')); ?>" required>
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
                                        <div class="mb-3">
                                            <label class="form-label required">Subject</label>
                                            <input class="form-control w-100 <?php $__errorArgs = ['subject'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                                type="text" name="subject" placeholder="Enter subject"
                                                value="<?php echo e(old('subject')); ?>" required>
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
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="message" class="form-label required">Message</label>
                                            <textarea class="form-control <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="message" id="message"
                                                style="height: 80px;" placeholder="Enter your message" required><?php echo e(old('message')); ?></textarea>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['message'];
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
                                
                                <div class="form-group<?php echo e($errors->has('captcha') ? ' has-error' : ''); ?>">
                                    <label for="captcha" class="col-md-4 control-label required">Captcha</label>
                                    <div class="col-md-12">
                                        <div class="captcha">
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <span><?php echo captcha_img(); ?></span>
                                                <button type="button" class="bg-transparent border-0 btn-refresh">
                                                    <i class="fa fa-refresh"></i>
                                                </button>
                                                <input id="captcha" type="text" class="form-control"
                                                    placeholder="Enter Captcha" name="captcha">
                                            </div>

                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($errors->has('captcha')): ?>
                                                <div class="text-danger mt-1">
                                                    <small><?php echo e($errors->first('captcha')); ?></small>
                                                </div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary-gradient rounded-1">Send
                                        Message</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="askQueations">
            <div class="container">
                <div class="section-heading mx-0 text-start">
                    <h2><span class="greenBorder"></span>
                        Frequently Asked Questions (FAQs)</h2>
                    <p>Our FAQ section provides, solutions to common queries about Mittlearn's platform, courses, payments,
                        and more – all in one place!
                    </p>
                </div>
                <div class="row align-items-center">
                    <div class="col-md-12 col-lg-8">
                        <div class="accordion" id="accordionExample">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($getFaqs)): ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $getFaqs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $faq): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="accordion-item">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse<?php echo e($loop->iteration); ?>"
                                            aria-expanded="false" aria-controls="collapse<?php echo e($loop->iteration); ?>">
                                            Q.<?php echo e($faq->sort_order ?? ''); ?> <?php echo e($faq->question ?? ''); ?>

                                        </button>
                                        <div id="collapse<?php echo e($loop->iteration); ?>" class="accordion-collapse collapse"
                                            data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <p class="m-0"><?php echo e($faq->answer ?? ''); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>

                    </div>
                    <div class="col-md-12 col-lg-4">
                        <lottie-player src="<?php echo e(asset('frontend/images/business-thinking.json')); ?>" autoPlay loop
                            style="width: 220px;height: 220px;margin: auto;"></lottie-player>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
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

<?php echo $__env->make('frontend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/frontend/contact-us.blade.php ENDPATH**/ ?>