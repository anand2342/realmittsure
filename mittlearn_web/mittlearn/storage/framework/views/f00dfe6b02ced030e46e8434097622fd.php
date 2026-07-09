<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e($links['site_page_title']); ?></title>
    <meta content="Online Courses" name="keywords">
    <meta name="description"
        content="Achieve Your Dreams by Learning New Skills. Mittlearn provides you the opportunity to learn new skills at the comfort of your home." />
    <!-- Favicons -->
    <link type="image/png" href="https://mittlearn.com/images/mittlearn-favicon.png" rel="icon">
    <link type="image/png" href="https://mittlearn.com/images/mittlearn-favicon.png" rel="apple-touch-icon">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet"
        href="https://rawgit.com/mikejacobson/jquery-bootstrap-scrolling-tabs/master/dist/jquery.scrolling-tabs.min.css">
    <link href="<?php echo e(asset('admin/vendor/drop-Down-Combo-Tree/comboTreeStyle.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('frontend/css/style.css')); ?>" rel="stylesheet">
    <link href="<?php echo e(asset('frontend/css/custom.css')); ?>" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">


    <meta property="og:title" content="Mittlearn - Achieve Your Dreams by Learning New Skills.">
    <meta property="og:description"
        content="Achieve Your Dreams by Learning New Skills. Mittlearn provides you the opportunity to learn new skills at the comfort of your home.">
    <meta property="og:url" content="https://mittlearn.com/">
    <!-- <meta property="og:image" content="https://mittlearn.com//img/logo.jpeg"> -->
    <meta name="image" property="og:image" content="https://mittlearn.com/images/mittlearn-favicon.png">
    <meta property="og:image:alt" content="Mittsure Technologies LLP">
    <meta property="og:site_name" content="Mittsure Technologies LLP">
    <meta property="og:type" content="article">
    <meta property="og:locale" content="en_US">
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:title" content="Mittlearn - Achieve Your Dreams by Learning New Skills.">
    <meta property="twitter:description"
        content="Mittsure Technologies is a revolutionary startup that provides end-to-end solutions to schools for all their requirements, from academics to infrastructure.">
    <meta name="image" property="twitter:image" content="https://mittlearn.com/images/mittlearn-favicon.png">
    <meta property="twitter:image:alt" content="Mittsure Technologies LLP">
    <meta property="twitter:site" content="@YourTwitterHandle">
    <meta property="article:published_time" content="2025">
    <meta property="og:article:author" content="Mittsure Technologies LLP">

    
    <?php echo $__env->yieldContent('meta'); ?>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script type="text/javascript" src="<?php echo e(asset('frontend/js/init.js')); ?>"></script>

    <script>
        var base_url = "<?php echo e(url('/') . '/'); ?>";
        var csrf_token = "<?php echo e(csrf_token()); ?>";
    </script>
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

</head>

<body>
    <?php
        $index = ['/', 'about-acadcourse', 'about-nonacadcourse', ''];
        $isActiveIndex = isActiveRoute($index);

        $aboutUs = ['about-us'];
        $isActiveAboutUs = isActiveRoute($aboutUs);

        $blog = ['blogs', 'blog.details'];
        $isActiveBlog = isActiveRoute($blog);

        $contactUs = ['contact-us'];
        $isActiveContactUs = isActiveRoute($contactUs);

        $cart = ['cart'];
        $isActiveCart = isActiveRoute($cart);

        $ourOfferings = ['our-offerings'];
        $isActiveOurOfferings = isActiveRoute($ourOfferings);

    ?>
    <header class="mainHeader">
        <nav class="navbar navbar-expand-md">
            <div class="container-fluid">
                <a class="navbar-brand p-0" href="<?php echo e(route('/')); ?>"><img
                        src="<?php echo e(asset('frontend/images/mittlearn-logo.svg')); ?>" width="150"></a>
                <button class="navbar-toggler" type="button">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class=" navbar-collapse" id="navbarContent">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item">
                            <a class="nav-link <?php echo e($isActiveIndex); ?>" aria-current="page"
                                href="<?php echo e(route('/')); ?>">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e($isActiveAboutUs); ?>" href="<?php echo e(route('about-us')); ?>">About Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e($isActiveBlog); ?>" href="<?php echo e(route('blogs')); ?>">Blog</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e($isActiveContactUs); ?>" href="<?php echo e(route('contact-us')); ?>">Contact
                                Us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?php echo e($isActiveOurOfferings); ?>" href="<?php echo e(route('our-offerings')); ?>">Our
                                Offerings</a>
                        </li>
                    </ul>
                    <div class="d-flex gap-2 p-3 justify-content-center p-md-0 btnHeader">

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Auth::check()): ?>
                            <?php
                                $userRole = auth()->check() ? getUserRoles() : '';
                                $landingUi = auth()->check() ? getUserClassLandingUi() : '';

                                if ($userRole == 'super_admin' || $userRole == 'admin' ||  $userRole == 'qd_developer') {
                                    $dashboardRoute = route('dashboard');
                                } elseif ($userRole == 'school_admin' || $userRole == 'school_teacher') {
                                    $dashboardRoute = route('sp.dashboard');
                                } elseif (in_array($userRole, ['b2c_student', 'school_student', 'd2c_user'])) {
                                    $dashboardRoute =
                                        $landingUi == 'mittbunny'
                                            ? route('mittbunny.dashboard')
                                            : route('up.dashboard');
                                } else {
                                    $dashboardRoute = route('logout');
                                }
                            ?>
                            <a href="<?php echo e($dashboardRoute); ?>" class="btn btn-primary rounded-2">Dashboard</a>
                            <!-- Cart btn -->
                            <form action="<?php echo e(route('cart')); ?>" method="GET" class="cartForm">
                                <input type="hidden" name="user_id" value="<?php echo e(Auth::id()); ?>">
                                <input type="hidden" id="guestUserId" name="session_id" value="">
                                <button class="btn btn-primary rounded-2 " type="submit"><img
                                        src="<?php echo e(asset('frontend/images/cart-icon-filled.svg')); ?>" alt="Cart Icon"
                                        class="cart-icon" width="15"> Cart</button>
                            </form>
                        <?php else: ?>
                            <a href="<?php echo e(route('register')); ?>"
                                class="btn btn-primary rounded-2 registerBtn">Register</a>
                            <a href="<?php echo e(route('login')); ?>" class="btn btn-success rounded-2">Login</a>
                            <form action="<?php echo e(route('cart')); ?>" method="GET" class="cartForm">
                                <input type="hidden" name="user_id"
                                    value="<?php echo e(auth()->check() ? base64_encode(auth()->user()->id) : ''); ?>">
                                <input type="hidden" id="guestUserId" name="session_id" value="">
                                <button class="btn btn-primary rounded-2" type="submit"><img
                                        src="<?php echo e(asset('frontend/images/cart-icon-filled.svg')); ?>" alt="Cart Icon"
                                        class="cart-icon" width="15"> Cart</button>
                            </form>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <?php echo $__env->yieldContent('content'); ?>
    <footer class="footerMain">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4 pe-md-5 mb-4">
                    <figure>
                        <img src="<?php echo e(asset('frontend/images/mittlearn-logo.svg')); ?>" alt="" width="200">
                    </figure>
                    <p>Mittlearn provides you the opportunity to learn new skills at the comfort of your home
                    </p>
                </div>
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="footLink">
                                <h3>
                                    <span class="greenBorder"></span>Blogs
                                </h3>
                                <ul>
                                    <li>
                                        <a href="<?php echo e(route('blogs')); ?>">All Blogs</a>
                                    </li>
                                </ul>
                            </div>
                            <div class="download mt-5"><a href="<?php echo e(route('download.app')); ?>"
                                    class="btn btn-primary rounded-2">Download Mittlearn App</a></div>
                        </div>
                        <div class="col-md-4 mb-4">
                            <div class="footLink">
                                <h3>
                                    <span class="greenBorder"></span>Resources
                                </h3>
                                <ul>
                                    <li>
                                        <a href="<?php echo e(route('about-us')); ?>">About Us</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo e(route('terms.condition')); ?>">Terms & Conditions</a>
                                    </li>
                                    <li>
                                        <a href="<?php echo e(route('privacy.policy')); ?>">Privacy Policy</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="footLink">
                                <h3>
                                    <span class="greenBorder"></span>Contact Us
                                </h3>
                                <ul>
                                    <li class="d-flex gap-2">
                                        
                                        <i class="fa-solid fa-location-dot"></i>
                                        <?php echo e($links[' address']); ?>

                                    </li>
                                    <li class="d-flex gap-2">
                                        
                                        <i class="fa-solid fa-phone"></i>
                                        <a href="tel:+1800 8917070"><?php echo e($links['contact_number']); ?></a>
                                    </li>
                                    <li class="d-flex gap-2 emailTxt">
                                        
                                        <i class="fa-solid fa-envelope"></i>
                                        <a href="mailto:<?php echo e($links['email']); ?>"><?php echo e($links['email']); ?></a>

                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="container-fluid mt-3">
            <div class="row copyrightTxt">
                <div class="col-md-6 pb-3">
                    <p class="mb-0">Copyright <?php echo e('@' . date('Y')); ?> Mittlearn. All Rights Reserved.</p>
                </div>
                <div class="col-md-6 pb-3">
                    <ul class="d-flex flex-wrap gap-2 justify-content-end">
                        <li><a target="_blank" href=<?php echo e($links['facebook']); ?>><img
                                    src="<?php echo e(asset('frontend/images/facebook-front.svg')); ?>" width="10"></a>
                        </li>
                        <li><a target="_blank" href=<?php echo e($links['instagram']); ?>><img
                                    src="<?php echo e(asset('frontend/images/instagram-front.svg')); ?>" width="20"></a></li>
                        <li><a target="_blank" href=<?php echo e($links['twitter']); ?>><img
                                    src="<?php echo e(asset('frontend/images/twitter-front.svg')); ?>" width="20"></a>
                        </li>
                        <li><a target="_blank" href=<?php echo e($links['linkedin']); ?>><img
                                    src="<?php echo e(asset('frontend/images/linkedin-front.svg')); ?>" width="20"></a></li>
                        <li><a target="_blank" href=<?php echo e($links['you_tube']); ?>><img
                                    src="<?php echo e(asset('frontend/images/youtube-front.svg')); ?>" width="25"></a>
                        </li>
                    </ul>
                </div>

            </div>

        </div>
    </footer>
    <div class="modal fade previewVdo" id="coursePurchage" tabindex="-1" aria-labelledby="ccoursePurchageLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-0 border-0">
                <div class="modal-header border-0">
                    <h1 class="modal-title fs-5 fw-normal " id="ccoursePurchageLabel">
                        Course Preview</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="purchase-message">
                    <div class="message-content"
                        style="padding: 20px; color: #333; border-radius: 8px; font-size: 1.2em;">
                        <strong class="">Unlock the full content for an amazing learning
                            experience!</strong><br>
                        <span class=""> Click on "Add to Cart" and purchase this course to gain access to all
                            the content and dive deeper into your learning journey.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Add Livewire Scripts -->
    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script src="https://rawgit.com/mikejacobson/jquery-bootstrap-scrolling-tabs/master/dist/jquery.scrolling-tabs.min.js">
    </script>
    <script src="<?php echo e(asset('frontend/js/script.js')); ?>"></script>
    <script src="<?php echo e(asset('admin/vendor/drop-Down-Combo-Tree/comboTreePlugin.js')); ?>"></script>

    <script>
        var toggled = 1;
        // JavaScript function to toggle the display of academic content
        function toggleAcademicContent() {
            if (toggled) {
                $('.academic-content').show();
                $('.academic-nonacademic-content').hide();
            } else {
                $('.academic-content').hide();
                $('.academic-nonacademic-content').show();
            }
            toggled = !toggled;
        }
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let sessionId = globalVar.sessionId; // your custom session tracking
            let activeTab = localStorage.getItem("activeTab") || "academic";

            // Function to update activeTab in session
            function updateSessionTab(tab) {
                fetch("<?php echo e(route('store.browser.session')); ?>", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"
                    },
                    body: JSON.stringify({
                        user_session_id: sessionId,
                        user_activeTab: tab
                    })
                });
            }

            // Initial update on page load
            updateSessionTab(activeTab);

            // Listen for tab change (assuming your tabs trigger this somehow)
            document.querySelectorAll('.tabLink').forEach(button => {
                button.addEventListener('click', function() {
                    const newTab = this.getAttribute('data-tab');
                    localStorage.setItem('activeTab', newTab);
                    updateSessionTab(newTab);
                });
            });
        });
    </script>
    <script>
        $(document).ready(function() {
            $('.previewVdo').on('hidden.bs.modal', function() {
                // Find the video inside the closed modal and pause it
                let video = $(this).find('video')[0];
                if (video) {
                    video.pause();
                    video.currentTime = 0; // Optional: reset to start
                }
            });
        });
    </script>
</body>

</html>
<?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/frontend/layouts/master.blade.php ENDPATH**/ ?>