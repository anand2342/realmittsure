@extends('layouts.app')

@section('content')
    <style>
        .loginFormBox h3 {
            position: relative;
            text-align: center;
        }

        .loginFormBox h3::after {
            content: '';
            background: linear-gradient(90deg, #044783 0%, #00C056 100%);
            border-radius: 10px;
            position: absolute;
            bottom: -6px;

            left: 50%;
            transform: translateX(-50%);

            width: 100px;
            height: 3px;
        }
    </style>
    <div class="loginMain">
        <div class="loginMain">

            <div class="loginSec registerPage">
                <div class="pb-3 text-center">
                    <a href="{{ route('/') }}">
                        <img src="{{ asset(config('constants.SITE_LOGO')) }}" width="200" />
                    </a>
                </div>

                <div class="loginFormBox">
                    <h3 class="text-center">Welcome</h3>
                    <p class="mb-4 text-center">
                        Register or login to access content mapped with this QR code
                    </p>

                    {{-- Tabs --}}
                    <ul class="nav nav-pills loginTabs mb-4 justify-content-center">
                        <li class="nav-item">
                            <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#registerTab"
                                type="button">
                                New User
                            </button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="pill" data-bs-target="#alreadyUserTab" type="button">
                                Already a User
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content">

                        {{-- ================= REGISTER TAB ================= --}}
                        <div class="tab-pane fade show active" id="registerTab">

                            @if (session('error'))
                                <label class="error">{{ session('error') }}</label>
                            @endif

                            <form method="POST" action="{{ route('talentAndSkill.qr.register.store') }}">
                                @csrf

                                {{-- QR Context --}}
                                <input type="hidden" name="course_ids"
                                    value="{{ implode(',', $matchedCourses->pluck('id')->toArray()) }}">
                                <input type="hidden" name="sub_category_id" value="{{ $matchedCategory->id }}">

                                <div class="row">

                                    <div class="col-md-12 mb-4">
                                        <label class="form-label required">Name</label>
                                        <input type="text" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name') }}">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email') }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label class="form-label required">Mobile Number</label>
                                        <input type="text" name="mobile"
                                            class="form-control @error('mobile') is-invalid @enderror"
                                            value="{{ old('mobile') }}">
                                        @error('mobile')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label class="form-label required">Password</label>
                                        <div class="position-relative">
                                            <input type="password" id="reg_password" name="password"
                                                class="form-control @error('password') is-invalid @enderror">
                                            <span class="eyeInput eye_icon" data-id="reg_password">
                                                <i class="bi bi-eye-slash"></i>
                                            </span>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6 mb-4">
                                        <label class="form-label required">Confirm Password</label>
                                        <div class="position-relative">
                                            <input type="password" id="password_confirmation" name="password_confirmation"
                                                class="form-control">
                                            <span class="eyeInput eye_icon" data-id="password_confirmation">
                                                <i class="bi bi-eye-slash"></i>
                                            </span>
                                        </div>
                                    </div>

                                </div>

                                {{-- Captcha --}}
                                <div class="mb-4">
                                    <label class="form-label required">Captcha</label>
                                    <div class="d-flex gap-2 align-items-center">
                                        <span class="captcha-img">{!! captcha_img() !!}</span>
                                        <button type="button" class="btn-refresh bg-transparent border-0">
                                            <i class="fa fa-refresh"></i>
                                        </button>
                                    </div>
                                    <input type="text" name="captcha" class="form-control mt-2">
                                    @error('captcha')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                {{-- Terms --}}
                                <div class="cstmCheckbox mb-3">
                                    <input type="checkbox" name="terms_accepted" checked>
                                    <label>
                                        I agree to the
                                        <a href="{{ route('terms.condition') }}">Terms</a> &
                                        <a href="{{ route('privacy.policy') }}">Privacy Policy</a>
                                    </label>
                                </div>

                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary-gradient w-75">
                                        Register
                                    </button>
                                </div>
                            </form>
                        </div>

                        {{-- ================= ALREADY USER TAB ================= --}}
                        <div class="tab-pane fade" id="alreadyUserTab">

                            @if (session('error'))
                                <label class="error">{{ session('error') }}</label>
                            @endif

                            <form method="POST" action="{{ route('login.submit') }}">
                                @csrf

                                {{-- QR Context --}}
                                <input type="hidden" name="course_ids"
                                    value="{{ implode(',', $matchedCourses->pluck('id')->toArray()) }}">
                                <input type="hidden" name="sub_category_id" value="{{ $matchedCategory->id }}">


                                <div class="mb-4">
                                    <label class="form-label">Email / Mobile / Username</label>
                                    <input type="text" name="username"
                                        class="form-control @error('username') is-invalid @enderror"
                                        value="{{ old('username') }}">
                                    @error('username')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-2">
                                    <label class="form-label">Password</label>
                                    <div class="position-relative">
                                        <input type="password" id="login_password" name="password"
                                            class="form-control @error('password') is-invalid @enderror">
                                        <span class="eyeInput eye_icon" data-id="login_password">
                                            <i class="bi bi-eye-slash"></i>
                                        </span>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="loginbtm mt-2">
                                    <div class="cstmCheckbox">
                                        {{-- <input type="checkbox" name="remember">
                                        <label>Remember Me</label> --}}
                                    </div>
                                    <a href="{{ route('forgot_password') }}">Forgot Password?</a>
                                </div>

                                <div class="text-center mt-4">
                                    <button type="submit" class="btn btn-primary-gradient w-75">
                                        Login
                                    </button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
                <div class="mainBanner p-0">
                    <span class="bgIcons1"><img src="{{ asset('frontend/images/bgIcon1.svg') }}" width="30"></span>
                    <span class="bgIcons2"><img src="{{ asset('frontend/images/bgIcon2.png') }}" width="50"></span>
                    <span class="bgIcons3"><img src="{{ asset('frontend/images/bgIcon3.png') }}" width="50"></span>
                    <span class="bgIcons4"><img src="{{ asset('frontend/images/bgIcon4.png') }}" width="50"></span>
                    <span class="bgIcons5"><img src="{{ asset('frontend/images/bgIcon5.png') }}" width="60"></span>
                    <span class="bgIcons6"><img src="{{ asset('frontend/images/bgIcon6.png') }}" width="40"></span>
                    <span class="bgIcons7"><img src="{{ asset('frontend/images/bgIcon7.png') }}" width="40"></span>
                    <span class="bgIcons8"><img src="{{ asset('frontend/images/bgIcon8.png') }}" width="55"></span>
                    <span class="bgIcons9"><img src="{{ asset('frontend/images/bgIcon9.png') }}" width="60"></span>
                    <span class="bgIcons10"><img src="{{ asset('frontend/images/bgIcon10.png') }}"
                            width="55"></span>
                    <span class="bgIcons11"><img src="{{ asset('frontend/images/bgIcon11.png') }}"
                            width="50"></span>
                    <span class="bgIcons12"><img src="{{ asset('frontend/images/bgIcon12.png') }}"
                            width="50"></span>
                    <span class="bgIcons13"><img src="{{ asset('frontend/images/bgIcon13.png') }}"
                            width="60"></span>
                </div>
            </div>
        </div>
    </div>

    {{-- Captcha Refresh --}}
    <script>
        $('.btn-refresh').click(function() {
            $.get('/refresh-captcha', function(data) {
                $('.captcha-img').html(data.captcha);
            });
        });
    </script>
@endsection
