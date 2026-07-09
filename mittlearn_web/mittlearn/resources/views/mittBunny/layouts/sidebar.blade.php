@php
    $userDashboardRoutes = ['mittbunny.dashboard'];
    $isShowUserDashboardMenu = isPermission($userDashboardRoutes);
    $isActiveUserDashboardMenu = isActiveRoute($userDashboardRoutes);

    $userPlannerRoutes = ['mittbunny.planner', 'mittbunny.planner.detail'];
    $isShowUserPlannerMenu = isPermission($userPlannerRoutes);
    $isActiveUserPlannerMenu = isActiveRoute($userPlannerRoutes);

    $userMyCoursesRoutes = [
        'mittbunny.courses',
        'mittbunny.course.listing',
        'mittbunny.course.digital-content',
        'mittbunny.courses.chapter.listing',
    ];
    $isShowUserMyCoursesMenu = isPermission($userMyCoursesRoutes);
    $isActiveUserMyCoursesMenu = isActiveRoute($userMyCoursesRoutes);

    $userOnlineClassRoutes = ['mittbunny.online-classes', 'mittbunny.online.class.digital.content'];
    $isShowUserOnlineClassMenu = isPermission($userOnlineClassRoutes);
    $isActiveUserOnlineClassMenu = isActiveRoute($userOnlineClassRoutes);

    $digitalContentRoutes = ['mittbunny.digital-content'];
    $isShowDigitalContentMenu = isPermission($digitalContentRoutes);
    $isActiveDigitalContentMenu = isActiveRoute($digitalContentRoutes);

    $mediaGalleryRoutes = ['mittbunny.media-gallery'];
    $isShowMediaGalleryMenu = isPermission($mediaGalleryRoutes);
    $isActiveMediaGalleryMenu = isActiveRoute($mediaGalleryRoutes);

    $userSubscriptionRoutes = ['mittbunny.subscription'];
    $isShowUserSubscriptionMenu = isPermission($userSubscriptionRoutes);
    $isActiveUserSubscriptionMenu = isActiveRoute($userSubscriptionRoutes);

    $appDownloadRoutes = ['mittbunny.download'];
    $isShowUserAppDownloadMenu = isPermission($appDownloadRoutes);
    $isActiveUserAppDownloadMenu = isActiveRoute($appDownloadRoutes);
@endphp



<div class="siderBar" id="siderBar">
    <div class="sideMenu">
        <div class="sideTop">
            <figure><img src="{{ asset('mittbunny/images/mittlearn-logo.svg') }}" width="100"></figure>
            <p>" Children's Enlightenment "</p>
            <lottie-player src="{{ asset('mittbunny/images/girl-building-sand-castle.json') }}" background="transparent"
                speed="1" style="width: 140px; height: 140px;" loop autoplay></lottie-player>
        </div>
        <div class="sideMenuscrl">
            <ul class="menuList">
                @if ($isShowUserDashboardMenu)
                    <li>
                        <a href="{{ route('mittbunny.dashboard') }}" class="{{ $isActiveUserDashboardMenu }}"><i><img
                                    src="{{ asset('mittbunny/images/dashboard-white.svg') }}" width="16"><img
                                    src="{{ asset('mittbunny/images/dashboard-green.svg') }}" width="16"
                                    class="hoverImg "></i>
                            Dashboard</a>
                    </li>
                @endif
                @if ($isShowUserPlannerMenu)
                    <li>
                        <a href="{{ route('mittbunny.planner') }} " class="{{ $isActiveUserPlannerMenu }}"><i><img
                                    src="{{ asset('mittbunny/images/my-planner-white.svg') }}" width="16"><img
                                    src="{{ asset('mittbunny/images/my-planner-green.svg') }}" width="16"
                                    class="hoverImg "></i>
                            My Planner</a>
                    </li>
                @endif
                @if ($isShowUserMyCoursesMenu)
                    <li>
                        <a href="{{ route('mittbunny.courses') }}" class="{{ $isActiveUserMyCoursesMenu }}"><i><img
                                    src="{{ asset('mittbunny/images/my-courses-white.svg') }}" width="13"><img
                                    src="{{ asset('mittbunny/images/my-courses-green.svg') }}" width="13"
                                    class="hoverImg "></i>
                            Subjects/ Courses</a>
                    </li>
                @endif
                @if ($isShowUserOnlineClassMenu)
                    <li>
                        <a href="{{ route('mittbunny.online-classes') }}"
                            class="{{ $isActiveUserOnlineClassMenu }}"><i><img
                                    src="{{ asset('mittbunny/images/online-classes-white.svg') }}" width="16"><img
                                    src="{{ asset('mittbunny/images/online-classes-green.svg') }}" width="16"
                                    class="hoverImg "></i>
                            Online Classes</a>
                    </li>
                @endif
                @if ($isShowDigitalContentMenu)
                    <li>
                        <a href="{{ route('mittbunny.digital-content') }}"
                            class="{{ $isActiveDigitalContentMenu }}"><i><img
                                    src="{{ asset('mittbunny/images/digital-content-white.svg') }}" width="13"><img
                                    src="{{ asset('mittbunny/images/digital-content-green.svg') }}" width="13"
                                    class="hoverImg"></i>
                            Digital Content</a>
                    </li>
                @endif
                @if ($isShowMediaGalleryMenu)
                    <li>
                        <a href="{{ route('mittbunny.media-gallery') }}"
                            class="{{ $isActiveMediaGalleryMenu }}"><i><img
                                    src="{{ asset('mittbunny/images/digital-content-white.svg') }}" width="13"><img
                                    src="{{ asset('mittbunny/images/digital-content-green.svg') }}" width="13"
                                    class="hoverImg"></i>
                            Media Gallery</a>
                    </li>
                @endif
                @if ($isShowUserSubscriptionMenu)
                    <li>
                        <a href="{{ route('mittbunny.subscription') }}"
                            class="{{ $isActiveUserSubscriptionMenu }}"><i><img
                                    src="{{ asset('mittbunny/images/subscription-white.svg') }}" width="13"><img
                                    src="{{ asset('mittbunny/images/subscription-green.svg') }}" width="13"
                                    class="hoverImg"></i>
                            Subscription</a>
                    </li>
                @endif
                {{--  @if ($isShowUserAppDownloadMenu)  --}}
                <li>
                    <a href="{{ route('mittbunny.download') }}" class="{{ $isActiveUserAppDownloadMenu }}"><i><img
                                src="{{ asset('frontend/images/fill_white_download.svg') }}" width="13"><img
                                src="{{ asset('frontend/images/fill_green_download.svg') }}" width="13"
                                class="hoverImg"></i>
                        Download App</a>
                </li>
                {{--  @endif  --}}
            </ul>
            @if ($notificationAlerts && $notificationAlerts->marketing_banner)
                <hr class="form_divider m-0 mt-3">
                @php
                    $file = $notificationAlerts->marketing_banner;
                    $extension = pathinfo($file, PATHINFO_EXTENSION);
                    $videoExtensions = [
                        'mp4',
                        'avi',
                        'mov',
                        'm4v',
                        'm4p',
                        'mpg',
                        'mp2',
                        'mpeg',
                        'mpe',
                        'mpv',
                        'm2v',
                        'wmv',
                        'flv',
                        'mkv',
                        'webm',
                        '3gp',
                        '3gp',
                        'm2ts',
                        'ogv',
                        'ts',
                        'mxf',
                        'ogg',
                    ];
                @endphp
                @if (in_array(strtolower($extension), $videoExtensions))
                    <a target="_blank" href="{{ $notificationAlerts->redirection_url }}"><video autoplay loop muted
                            playsinline class="img-thumbnail">
                            <source src="{{ Storage::url('uploads/marketing_banner/' . $file) }}"
                                type="video/mp4">
                            Your browser does not support the video tag.
                        </video></a>
                @else
                    <a target="_blank" href="{{ $notificationAlerts->redirection_url }}"><img
                            src="{{ Storage::url('uploads/marketing_banner/' . $file) }}" alt="Marketing Banner"
                            width="300"></a>
                @endif
            @endif
        </div>
    </div>
</div>
