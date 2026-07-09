    <!-- ======= Header ======= -->
    <header id="header" class="header fixed-top d-flex align-items-center ">

        <div class="d-flex align-items-center justify-content-between">
            <a href="{{ route('dashboard') }}" class="logo d-flex align-items-center">
                <img src="{{ asset(config('constants.SITE_LOGO')) }}" alt="" width="130" height="auto" />
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
                            <a href="{{ route('category.index') }}" class="dropdown-item py-1 small">
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
                            <a href="{{ route('user.create') }}" class="dropdown-item py-1 small">
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
                            <a href="{{ route('plans.index') }}" class="dropdown-item py-1 small">
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
                            <a href="{{ route('school.list') }}" class="dropdown-item py-1 small">
                                <div class="d-flex align-items-center">
                                    {{-- <i class="bi bi-plus-circle text-success fs-6"></i> --}}
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
                            <a href="{{ route('course.index', ['group' => 'academic-digital-content']) }}"
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
                            <a href="{{ route('planner.index') }}" class="dropdown-item py-1 small">
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
                            <a href="{{ route('permissions.assign') }}" class="dropdown-item py-1 small">
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
                            <a href="{{ route('folder.list') }}" class="dropdown-item py-1 small">
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
                            <a href="{{ route('enquiries') }}" class="dropdown-item py-1 small">
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



                {{--  <li class="nav-item dropdown">

                    <a class="nav-link nav-icon" href="#" data-bs-toggle="dropdown">
                        <i class="bi bi-chat-left-text"></i>
                        <span class="badge bg-success badge-number">3</span>
                    </a><!-- End Messages Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow messages">
                        <li class="dropdown-header">
                            You have 3 new messages
                            <a href="#"><span class="badge rounded-pill bg-primary p-2 ms-2">View all</span></a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li class="message-item">
                            <a href="#">
                                <img src="{{ asset('admin/img/messages-1.jpg') }}" alt=""
                                    class="rounded-circle">
                                <div>
                                    <h4>Maria Hudson</h4>
                                    <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                                    <p>4 hrs. ago</p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li class="message-item">
                            <a href="#">
                                <img src="{{ asset('admin/img/messages-2.jpg') }}" alt=""
                                    class="rounded-circle">
                                <div>
                                    <h4>Anna Nelson</h4>
                                    <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                                    <p>6 hrs. ago</p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li class="message-item">
                            <a href="#">
                                <img src="{{ asset('admin/img/messages-3.jpg') }}" alt=""
                                    class="rounded-circle">
                                <div>
                                    <h4>David Muldon</h4>
                                    <p>Velit asperiores et ducimus soluta repudiandae labore officia est ut...</p>
                                    <p>8 hrs. ago</p>
                                </div>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>

                        <li class="dropdown-footer">
                            <a href="#">Show all messages</a>
                        </li>

                    </ul><!-- End Messages Dropdown Items -->

                </li><!-- End Messages Nav -->    --}}
                <li class="nav-item dropdown pe-3">

                    <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#"
                        data-bs-toggle="dropdown">
                        <img src="{{ Auth::user()->image ? Storage::url('uploads/user/profile_image/' . Auth::user()->image) : asset('admin/img/profile-img.jpg') }}"
                            alt="Profile" class="rounded-circle">
                        <span class="d-none d-md-block dropdown-toggle ps-2">{{ Auth::user()->name }}</span>
                    </a><!-- End Profile Iamge Icon -->

                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile profiledropDown">
                        <!-- <li class="dropdown-header">
                            <h6>{{ Auth::user()->name }}</h6>
                        </li> -->
                        <li>
                            <!-- <hr class="dropdown-divider"> -->
                        </li>

                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('profile') }}">
                                <i class="bi bi-person"></i>
                                <span>My Profile</span>
                            </a>
                        </li>
                        <li>
                            <!-- <hr class="dropdown-divider"> -->
                        </li>
                        <li>
                            <a class="dropdown-item d-flex align-items-center" href="{{ route('admin.logout') }}">
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
