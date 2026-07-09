@extends('layouts.app')

@section('content')
    @php
        // Check if $activeTab is set, if not, default to null
        $tab = $activeTab ?? null;
        $innertab = $activeTab2 ?? null;
        $error = $activeError ?? null;
    @endphp
    <div class="loginMain">
        <div class="loginSec">
            <div class="pb-3 text-center">
                <a href="{{ route('/') }}"><img src="{{ asset(config('constants.SITE_LOGO')) }}" alt=""
                        width="200" /></a>
            </div>
            <div class="loginFormBox">
                <div class="text-center">
                    <h3 class="afterNone mb-0">Login</h3>
                    <p class="mb-4 mt-0">Hey, Enter your details to Login</p>
                </div>
                <ul class="nav nav-pills loginTabs mb-3">
                    <li class="nav-item">
                        <button class="nav-link {{ $tab === 'otp' ? '' : 'active' }}" data-bs-toggle="pill"
                            data-bs-target="#loginwithPassword" type="button">Login with Password</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link {{ $tab === 'otp' ? 'active' : '' }}" data-bs-toggle="pill"
                            data-bs-target="#loginwithOtp" type="button">Login with OTP</button>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane {{ $tab === 'otp' ? '' : 'active' }}" id="loginwithPassword">
                        @if ($tab != 'otp' || $error === 'error')
                            @if (session('error'))
                                <span>
                                    <label class="error">{{ session('error') }}</label>
                                </span>
                            @endif
                        @endif
                        <form method="post" action="{{ route('login.submit') }}">
                            @csrf
                            <input type="hidden" id="guestUserId" name="guest_user_id" value="">
                            <div class="mb-4">
                                <label class="form-label">Email/ Mobile Number/ Username</label>
                                <input class="form-control w-100 @error('username') is-invalid @enderror" type="text"
                                    name="username"
                                    value="{{ $data ?? (Cookie::get('remember_username') ?? old('username')) }}"
                                    placeholder="" autocomplete="username" autofocus>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-1">
                                <label class="form-label">Password</label>
                                <div class="position-relative">
                                    <input class="form-control w-100 pe-5 @error('password') is-invalid @enderror"
                                        id="password" type="password" placeholder="" name="password"
                                        value="{{ Cookie::get('remember_password') ? decrypt(Cookie::get('remember_password')) : '' }}"
                                        autocomplete="current-password">
                                    <span class="eyeInput eye_icon" data-id="password">
                                        <i class="bi bi-eye-slash"></i>
                                    </span>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="loginbtm mt-2">
                                <div class="cstmCheckbox">
                                    <input type="checkbox" id="rememberCheck" name="remember"
                                        {{ old('remember', Cookie::get('remember_username') ? 'checked' : '') }}> <label
                                        for="rememberCheck">Remember Me</label>
                                </div>
                                @if (Route::has('forgot_password'))
                                    <a href="{{ route('forgot_password') }}">
                                        {{ __('Forgot Password?') }}
                                    </a>
                                @endif
                            </div>
                            <div class="text-center my-2 mt-4">
                                <button type="submit" class="btn btn-primary-gradient fs-7 rounded-2 w-75">Login</button>
                            </div>

                            <strong class="signupTxt pb-0">Don't have an account?
                                @if (Route::has('admin.register'))
                                    <a href="{{ route('register') }}">
                                        {{ __('Register') }}
                                    </a>
                                @endif
                            </strong>
                        </form>
                    </div>

                    <div class="tab-pane {{ $tab === 'otp' ? 'active' : '' }}" id="loginwithOtp">

                        <div class="emailDiv {{ $innertab === 'otp2' ? 'd-none' : '' }}">
                            <form method="post" action="{{ route('login.otp.fill') }}">
                                @csrf

                                <div class="mb-4">
                                    <label class="form-label">Email/Mobile Number</label>
                                    <input class="form-control w-100 @error('id') is-invalid @enderror" type="text"
                                        name="id" value="{{ old('id') }}" required autocomplete="id" autofocus
                                        placeholder="">
                                    @if ($error === 'error' || $tab === 'otp')
                                        @if (session('error'))
                                            <span>
                                                <label class="error">{{ session('error') }}</label>
                                            </span>
                                        @endif
                                    @endif
                                </div>

                                <div class="text-center my-2 mt-4">
                                    <button type="submit"
                                        class="btn btn-primary-gradient fs-7 rounded-2 w-75">Submit</button>
                                </div>
                                <strong class="signupTxt pb-0">Don't have an account?
                                    @if (Route::has('admin.register'))
                                        <a href="{{ route('register') }}">
                                            {{ __('Register') }}
                                        </a>
                                    @endif
                                </strong>

                            </form>
                        </div>

                        {{--  <div class="email-and-otp-div {{ $innertab === 'otp2' ? '' : 'd-none' }}">
                            <form method="post" action="{{ route('login.otp.check') }}">
                                @csrf
                                <div class="mb-4">
                                    <label class="form-label">Email/Mobile Number</label>
                                    <input class="form-control w-100" type="text" name="id" id="id"
                                        value="{{ old('id', isset($userId) ? $userId : request()->userId) }}" readonly
                                        required autocomplete="id" autofocus>
                                </div>
                                <a href="{{ route('login.otp') }}" class="textUnderline mb-3">Change Email or Mobile
                                    Number</a>
                                <div class="otpMain">
                                    <strong>Enter OTP</strong>
                                    <div class="otpFind">
                                        @php
                                            $otpValue = session('otp_value', ''); 
                                            $otpDigits = str_split($otpValue); 
                                        @endphp
                                        @for ($i = 0; $i < 6; $i++)
                                            <input type="text" class="form-control otp-input" maxlength="1"
                                                name="otp[]" value="{{ isset($otpDigits[$i]) ? $otpDigits[$i] : '' }}"
                                                required oninput="handleInput(this, {{ $i }})"
                                                autocomplete="off" />
                                        @endfor
                                    </div>
                                    @if (session('error'))
                                        <span>
                                            <label class="error">{{ session('error') }}</label>
                                        </span>
                                    @endif
                                </div>

                                <div>
                                    <livewire:otp-timer />
                                </div>
                                @livewireScripts

                                <strong class="signupTxt pb-0">Didn't get a OTP?
                                    <a id="resend_otp" class="resend_coursor"><u>Click to Resend</u></a>
                                </strong>

                                <div class="text-center my-2 mt-4">
                                    <button type="submit"
                                        class="btn btn-primary-gradient fs-7 rounded-2 w-75">Submit</button>
                                </div>
                                <strong class="signupTxt pb-0">Don't have an account?
                                    @if (Route::has('admin.register'))
                                        <a href="{{ route('register') }}">
                                            {{ __('Register') }}
                                        </a>
                                    @endif
                                </strong>

                            </form>
                        </div> --}}
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
                <span class="bgIcons10"><img src="{{ asset('frontend/images/bgIcon10.png') }}" width="55"></span>
                <span class="bgIcons11"><img src="{{ asset('frontend/images/bgIcon11.png') }}" width="50"></span>
                <span class="bgIcons12"><img src="{{ asset('frontend/images/bgIcon12.png') }}" width="50"></span>
                <span class="bgIcons13"><img src="{{ asset('frontend/images/bgIcon13.png') }}" width="60"></span>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#resend_otp').on('click', function() {
                const mobileEmail = $('#id').val();
                $.ajax({
                    url: '{{ route('login.resend.otp') }}',
                    type: 'POST',
                    data: {
                        mobile_email: mobileEmail,
                        _token: '{{ csrf_token() }}'
                    },

                });
            });
        });
    </script>
    <script>
        function moveToNext(current) {
            const next = current.nextElementSibling;
            if (current.value.length === current.maxLength && next) {
                next.focus();
            }
        }
        document.querySelectorAll('.otp-input').forEach((input) => {
            input.addEventListener('keydown', (e) => {
                if (e.key === 'Backspace' && input.value === '') {
                    const previous = input.previousElementSibling;
                    if (previous) {
                        previous.focus();
                    }
                }
            });
        });
    </script>
    <script>
        function handleInput(input, index) {
            const value = input.value;

            // Move to next input if a digit is entered
            if (value.length === 1 && index < 5) {
                const nextInput = input.parentNode.children[index + 1];
                if (nextInput) nextInput.focus();
            }

            // Move to previous input if the input is deleted
            if (value.length === 0) {
                if (index > 0) {
                    const prevInput = input.parentNode.children[index - 1];
                    if (prevInput) {
                        prevInput.focus();
                        prevInput.select(); // Select content to easily replace it
                    }
                }
            }
        }
    </script>
@endsection
