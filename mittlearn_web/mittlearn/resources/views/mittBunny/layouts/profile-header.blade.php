<div class="profile pb-3">
    <h2 class="fs-6 fw-normal m-0"><b class="fw-semibold">My</b> Profile</h2>
        <a href="{{ route('mittbunny.profile') }}" class="dropdownPrf">

            <img
            src="{{ Auth::user()->image ? Storage::url('uploads/user/profile_image/' . Auth::user()->image) : asset('images/default-profile.png') }}"
            alt="Profile Image">
            {{-- @if(Auth::user()->image)
            <img src="{{ Storage::url('uploads/user/profile_image/' . Auth::user()->image) }}" alt="">
            @else
            <img src="{{ asset('images/default-profile.png') }}" alt="">
            @endif --}}
        </a>
    

</div>
