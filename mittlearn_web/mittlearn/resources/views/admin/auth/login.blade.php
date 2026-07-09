@include('admin.layouts.head-links')
<main>
    <div class="container">

        <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">

                        <div class="d-flex justify-content-center py-4">
                            <a href="{{ url('/') }}" class="logo d-flex align-items-center w-auto">
                                <a href="{{ route('/') }}"><img src="{{ asset(config('constants.SITE_LOGO')) }}"
                                        alt="" width="200" /></a>
                                {{-- <span class="d-none d-lg-block">Mittlearn</span> --}}
                            </a>
                        </div><!-- End Logo -->

                        <div class="card mb-3">

                            <div class="card-body">

                                <div class="pt-4 pb-2">
                                    <h5 class="card-title text-center pb-0 fs-4">Login to Your Account</h5>
                                    <p class="text-center small">Enter your useremail & password to login</p>
                                </div>
                                @if (session('status'))
                                    <div class="alert alert-success">
                                        {{ session('status') }}
                                    </div>
                                @endif
                                @if (session('error'))
                                    <span>
                                        <label class="error">{{ session('error') }}</label>
                                    </span>
                                @endif
                                <form method="POST" action="{{ route('admin.login') }}" class="row g-3" novalidate>
                                    @csrf
                                    <div class="col-12">
                                        <label for="yourUsername" class="form-label">Username</label>
                                        <div class="input-group has-validation">
                                            <input id="username" type="username"
                                                class="form-control @error('username') is-invalid @enderror"
                                                name="username"
                                                value="{{ Cookie::get('remember_username') ?? old('username') }}"
                                                required autocomplete="username" autofocus>
                                            @error('username')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <label class="form-label">Password</label>
                                        <div class="position-relative">
                                            <input
                                                class="form-control w-100 pe-5 @error('password') is-invalid @enderror"
                                                id="password" type="password" placeholder="" name="password"
                                                autocomplete="current-password"value="{{ Cookie::get('remember_password') ? decrypt(Cookie::get('remember_password')) : '' }}">
                                            <span class="eyeInput eye_icon" data-id="password"
                                                onclick="togglePassword()">
                                                <i id="eyeIcon" class="bi bi-eye-slash"></i>
                                            </span>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Forgot Password Link -->
                                    <div class="col-12 text-end">
                                        <a href="{{ route('admin.reset-password') }}" class="small">Forgot
                                            Password?</a>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-check">
                                            <input type="checkbox"class="form-check-input" id="rememberCheck"
                                                name="remember"
                                                {{ old('remember', Cookie::get('remember_username') ? 'checked' : '') }}>
                                            <label class="form-check-label" for="rememberCheck">Remember Me</label>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <button class="btn btn-primary w-100" type="submit">Login</button>
                                    </div>
                                    {{-- <div class="col-12">
                                        <p class="small mb-0">Don't have an account? <a
                                                href="{{ route('admin.register') }}">Create an account</a></p>
                                    </div> --}}
                                </form>

                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </section>

    </div>

</main>

<a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
        class="bi bi-arrow-up-short"></i></a>
<script>
    function togglePassword() {
        const passwordInput = document.getElementById("password");
        const eyeIcon = document.getElementById("eyeIcon");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            eyeIcon.classList.remove("bi-eye-slash");
            eyeIcon.classList.add("bi-eye");
        } else {
            passwordInput.type = "password";
            eyeIcon.classList.remove("bi-eye");
            eyeIcon.classList.add("bi-eye-slash");
        }
    }
</script>
@include('admin.layouts.footer-links')
