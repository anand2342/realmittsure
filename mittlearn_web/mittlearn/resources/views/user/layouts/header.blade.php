<header class="dashboardHead">
    <div class="leftItem">
        <a href="{{ route('/') }}"><img src="{{ asset('frontend/images/mittlearn-logo.svg') }}" alt=""
                width="130"></a>

    </div>
    <div class="rightItem">
        <button type="button" class="toggleBtn">
            <img src="{{ asset('frontend/images/toggletop-icon.svg') }}" alt="" width="16" class="me-md-3">
        </button>
        <div class="searchBox dropdown-menu d-md-block">
            <input type="text" class="form-control" placeholder="Search">
        </div>
        <button type="button" class="searchBtn d-md-none ms-auto me-3" data-bs-toggle="dropdown"><img
                src="{{ asset('frontend/images/topsearch-icon.svg') }}" alt="img" width="20"></button>
        <a href="javascript:void(0)" class="ms-md-auto me-3 me-md-4">
            <img src="{{ asset('frontend/images/notification-icon.svg') }}" alt="" width="25">
        </a>
        <button class="dropdownPrf" type="button">
            <img src="{{ asset('frontend/images/profile-img.svg') }}" alt="">{{ Auth::user()->name }}
        </button>
    </div>
</header>
