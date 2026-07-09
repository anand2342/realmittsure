@extends('layouts.app')

@section('content')
    <div class="loginMain">
        <div class="loginMain">

            <div class="loginSec registerPage">
                <div class="pb-3 text-center">
                    <a href="{{ route('/') }}"><img src="{{ asset(config('constants.SITE_LOGO')) }}" alt=""
                            width="200" /></a>
                </div>
                <div class="loginFormBox">
                    <h3>Registration</h3>
                    <p class=" mb-4">Hey, Enter your details to get your account register</p>
                    @if (session('error'))
                        <span>
                            <label class="error">{{ session('error') }}</label>
                        </span>
                    @endif
                    <form method="POST" action="{{ route('olympiad.register.submit') }}" id="register-password-form">
                        @csrf
                        {{-- <input type="hidden" placeholder="" name="class_id" value="{{ $matchedClass }}">
                        <input type="hidden" placeholder="" name="category_id" value="{{ $matchedCategory }}"> --}}

                        <div class="row px-md-1">
                            <div class="col-md-12 px-md-2">
                                <div class="form-group mb-4">
                                    <label class="form-label required">Name</label>
                                    <input class="form-control w-100 @error('name') is-invalid @enderror" type="text"
                                        placeholder="" name="name" value="{{ $data->name ?? old('name') }}"
                                        autocomplete="name" autofocus>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 px-md-2">
                                <div class="form-group mb-4">
                                    <label class="form-label">Email</label>
                                    <div class="position-relative">
                                        <input class="form-control w-100 pe-5 @error('email') is-invalid @enderror"
                                            id="email" type="email" placeholder="" name="email"
                                            value="{{ $data->email ?? old('email') }}" autocomplete="email">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 px-md-2">
                                <div class="form-group mb-4">
                                    <label class="form-label required">Mobile Number</label>
                                    <div class="position-relative">
                                        <input class="form-control w-100 pe-5 @error('mobile') is-invalid @enderror"
                                            type="text" placeholder="" id="mobile" name="mobile"
                                            value="{{ $data->mobile_no ?? old('mobile') }}" autocomplete="mobile" autofocus>
                                        @error('mobile')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 px-md-2">
                                <div class="form-group mb-4">
                                    <label class="form-label required">Access Code</label>
                                    <input class="form-control w-100 @error('access_code') is-invalid @enderror"
                                        type="text" placeholder="Enter Access Code" name="access_code" id="access_code"
                                        value="{{ $data->access_code ?? old('access_code') }}" autocomplete="access_code"
                                        autofocus>
                                    @error('access_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <script>
                                document.getElementById('access_code').addEventListener('input', function(e) {
                                    // Convert only letters to uppercase
                                    const transformed = e.target.value.replace(/[a-z]/g, char => char.toUpperCase());
                                    if (e.target.value !== transformed) {
                                        e.target.value = transformed;
                                    }
                                });
                            </script>

                            <div class="loginbtm mt-2">
                                <div class="cstmCheckbox">
                                    <input type="checkbox" id="termsCheck" checked name="terms_accepted">
                                    <label for="termsCheck">By Clicking you are indicating that you have read and agreed to
                                        the
                                        <a href="{{ route('terms.condition') }}">terms of use</a> & <a
                                            href="{{ route('privacy.policy') }}">Privacy
                                            policy</a></label>
                                    @error('terms_accepted')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="text-center my-2 mt-4">
                                <button type="submit"
                                    class="btn btn-primary-gradient fs-7 rounded-2 w-75">Register</button>
                            </div>
                    </form>
                </div>
            </div>
            <div class="mainBanner p-0">
                <span class="bgIcons1"><img src="{{ asset('frontend/images/bgIcon1.svg') }}" width="30"></span>
                <span class="bgIcons2"><img src="{{ asset('frontend/images/bgIcon2.png ') }}" width="50"></span>
                <span class="bgIcons3"><img src="{{ asset('frontend/images/bgIcon3.png ') }}" width="50"></span>
                <span class="bgIcons4"><img src="{{ asset('frontend/images/bgIcon4.png ') }}" width="50"></span>
                <span class="bgIcons5"><img src="{{ asset('frontend/images/bgIcon5.png ') }}" width="60"></span>
                <span class="bgIcons6"><img src="{{ asset('frontend/images/bgIcon6.png ') }}" width="40"></span>
                <span class="bgIcons7"><img src="{{ asset('frontend/images/bgIcon7.png ') }}" width="40"></span>
                <span class="bgIcons8"><img src="{{ asset('frontend/images/bgIcon8.png ') }}" width="55"></span>
                <span class="bgIcons9"><img src="{{ asset('frontend/images/bgIcon9.png ') }}" width="60"></span>
                <span class="bgIcons10"><img src="{{ asset('frontend/images/bgIcon10.png ') }}" width="55"></span>
                <span class="bgIcons11"><img src="{{ asset('frontend/images/bgIcon11.png ') }}" width="50"></span>
                <span class="bgIcons12"><img src="{{ asset('frontend/images/bgIcon12.png ') }}" width="50"></span>
                <span class="bgIcons13"><img src="{{ asset('frontend/images/bgIcon13.png ') }}" width="60"></span>
            </div>
        </div>
    @endsection
