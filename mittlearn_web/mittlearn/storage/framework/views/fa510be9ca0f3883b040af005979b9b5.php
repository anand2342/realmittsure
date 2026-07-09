    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center ">

        <div class="d-flex align-items-center justify-content-between">
            <a href="<?php echo e(route('dashboard')); ?>" class="logo d-flex align-items-center">
                <img src="<?php echo e(asset(config('constants.SITE_LOGO'))); ?>" alt="" width="130" height="auto" />
            </a>
            <i class="bi bi-list toggle-sidebar-btn fs-4"></i>
        </div><!-- End Logo -->

        <!-- <div class="search-bar">
            <form class="search-form d-flex align-items-center m-0" method="POST" action="#">
                <input type="text" name="query" placeholder="Search" title="Enter search keyword">
                <button type="submit" title="Search"><i class="bi bi-search"></i></button>
            </form>
        </div> -->
        <!-- End Search Bar -->

        <nav class="header-nav ms-auto">
            <ul class="d-flex align-items-center">

                <li class="nav-item d-block d-lg-none">
                    <a class="nav-link nav-icon search-bar-toggle " href="#">
                        <i class="bi bi-search"></i>
                    </a>
                </li><!-- End Search Icon-->

                <li class="nav-item dropdown">
                    <!-- Dropdown Button -->
                    <a class="btn btn-primary btn-sm me-4" href="#" data-bs-toggle="dropdown">Quick Action</a>

                    <!-- Dropdown Menu -->
                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow notifications">
                        <li class="notification-item">
                            <a href="<?php echo e(route('category.index')); ?>" class="dropdown-item py-1 small">
                                <div class="d-flex align-items-center">
                                    <i class="bi bi-plus-circle fs-6"></i>
                                    <div class="small">
                                        Create Group
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li class="notification-item">
                            <a href="<?php echo e(route('user.create')); ?>" class="dropdown-item py-1 small">
                                <div class="d-flex align-items-center">
                                    <i class="ri-map-pin-user-fill fs-5"></i>
                                    <div class="small">
                                        Create User
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li class="notification-item">
                            <a href="<?php echo e(route('plans.index')); ?>" class="dropdown-item py-1 small">
                                <div class="d-flex align-items-center">
                                    <i class="ri-folder-5-line fs-5"></i>
                                    <div class="small">
                                        Subcription Plans
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li class="notification-item">
                            <a href="<?php echo e(route('school.list')); ?>" class="dropdown-item py-1 small">
                                <div class="d-flex align-items-center">
                                    
                                    <i class="ri-community-fill fs-5"></i>
                                    <div class="small">
                                        School Management
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li class="notification-item">
                            <a href="<?php echo e(route('course.index', ['group' => 'academic-digital-content'])); ?>"
                                class="dropdown-item py-1 small">
                                <div class="d-flex align-items-center">
                                    <i class="ri-file-list-2-line fs-5"></i>
                                    <div class="small">
                                        Digital Content Management
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li class="notification-item">
                            <a href="<?php echo e(route('planner.index')); ?>" class="dropdown-item py-1 small">
                                <div class="d-flex align-items-center">
                                    <i class="ri-calendar-event-fill fs-5"></i>
                                    <div class="small">
                                        Planner Management
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li class="notification-item">
                            <a href="<?php echo e(route('permissions.assign')); ?>" class="dropdown-item py-1 small">
                                <div class="d-flex align-items-center">
                                    <i class="ri-task-line fs-5"></i>
                                    <div class="small">
                                        Assign Permissions
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li class="notification-item">
                            <a href="<?php echo e(route('folder.list')); ?>" class="dropdown-item py-1 small">
                                <div class="d-flex align-items-center">
                                    <i class="ri-article-line fs-5"></i>
                                    <div class="small">
                                        Content Deck
                                    </div>
                                </div>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li class="notification-item">
                            <a href="<?php echo e(route('enquiries')); ?>" class="dropdown-item py-1 small">
                                <div class="d-flex align-items-center">
                                    <i class="ri-folder-keyhole-line fs-5"></i>
                                    <div class="small">
                                        Contact-us Enquiries
                                    </div>
                                </div>
                            </a>
                        </li>

                    </ul>
                </li>



                
                <li class="nav-item dropdown pe-3">

                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#"
                        data-bs-toggle="dropdown">
                        <img src="<?php echo e(Auth::user()->image ? Storage::url('uploads/user/profile_image/' . Auth::user()->image) : asset('admin/img/profile-img.jpg')); ?>"
                            alt="Profile" class="rounded-circle">
                        <span class="d-none d-md-block dropdown-toggle ps-2"><?php echo e(Auth::user()->name); ?></span>
                    </a><!-- End Profile Iamge Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile profiledropDown">
                        <!-- <li class="dropdown-header">
                            <h6><?php echo e(Auth::user()->name); ?></h6>
                        </li> -->
                        <li>
                            <!-- <hr class="dropdown-divider"> -->
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="<?php echo e(route('profile')); ?>">
                                <i class="bi bi-person"></i>
                                <span>My Profile</span>
                            </a>
                        </li>
                        <li>
                            <!-- <hr class="dropdown-divider"> -->
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="<?php echo e(route('admin.logout')); ?>">
                                <i class="bi bi-box-arrow-right"></i>
                                <span>Sign Out</span>
                            </a>
                        </li>

                    </ul><!-- End Profile Dropdown Items -->
                </li><!-- End Profile Nav -->

            </ul>
        </nav><!-- End Icons Navigation -->

    </header>
    <!-- End Header -->
<?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/admin/layouts/header.blade.php ENDPATH**/ ?>