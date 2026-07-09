<div class="siderBar" id="siderBar">
    <div class="sideMenu">
        <div class="sideMenuscrl">
            {{-- <ul class="menuList">
                <li>
                    <a href="{{ route('sp.dashboard') }}"
                        class="{{ request()->routeIs('sp.dashboard') ? 'active' : '' }}"><img
                            src="{{ asset('frontend/images/dashboard-icon.svg') }}" width="16" class="me-2">
                        Dashboard</a>
                </li>
                <li>
                    <a href="{{ route('user.dashboard') }}" class="active"><img src="{{ asset('frontend/images/my-courses-icon.svg') }}"
                            width="18" class="me-2">
                        My Courses</a>
                </li>
            </ul> --}}
            @if (isset($subscription) && !empty($subscription))
                <div class="paymentBox">
                    <figure>
                        <img src="{{ asset('frontend/images/mittlearnround-logo.svg') }}" alt="" width="50">
                    </figure>
                    <p> {{ $subscription->plan_json['name'] }}</p>
                    <span class="d-block fw-medium fs-8">Subscription Expired on
                        {{ dateConvert($subscription['end_date'], 'd, M Y') }}</span>
                    <div class="mx-auto">
                        <a href="{{ route('/') }}"
                            class="btn btn-primary-gradient rounded-1 w-75 fs-7 px-4 my-3 mb-2">Upgrade</a>
                    </div>
                    <a href="#" class="cancelBtn">Cancel</a>
                </div>
            @else
                <!-- Empty space if no subscription -->
                <div class="paymentBox">
                    <figure>
                        <img src="{{ asset('frontend/images/mittlearnround-logo.svg') }}" alt="" width="50">
                    </figure>
                    <p class="fs-6 text-center">No Active Subscription</p>
                    <span class="d-block fw-medium fs-8 text-center">
                        Subscribe now to unlock premium features and access a wide range of academic and talent & skills
                        courses.
                    </span>
                    <div class="mx-auto">
                        <a href="{{ route('/') }}"
                            class="btn btn-primary-gradient rounded-1 w-75 fs-7 px-4 my-3 mb-2">View Plans</a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
