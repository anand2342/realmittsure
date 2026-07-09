@extends('frontend.layouts.master')

@section('content')
    <div>
        <div class="contactBanner">
            <div class="lottieSquare">
                <lottie-player src="{{ asset('frontend/images/square-shape-loading.json') }}" autoPlay loop
                    style="width: 120px; height: 120px;"></lottie-player>
            </div>
            <img src="{{ asset('frontend/images/blue-square.svg') }}" alt="" width="80" class="squareImg">
            <div class="container">
                <div class="bannerTxt">
                    <h1>We are here for you, contact us <b>anytime</b></h1>
                    <p>Have any questions about our services or just want to talk with us?<br> Reach us out </p>
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-1"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                </div>
            </div>
        </div>
        <div class="contactUs">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-6 col-lg-3 col-xl-3 px-md-2 mb-3">
                        <div class="detailsContact h-100">
                            <a target="_blank" href="mailto:{{ $getSetting['email'] }}">
                                <figure>
                                    <a href="mailto:{{ $getSetting['email'] }}"> <img
                                            src="{{ asset('frontend/images/mailus-contact.svg') }}" alt=""
                                            width="50"></a>
                                </figure>
                            </a>
                            <span>Mail Us <b>We're here to help</b></span>
                            <hr>
                            <a href="mailto:{{ $getSetting['email'] }}" style="text-decoration: none; color: inherit;">
                                <strong>{{ $getSetting['email'] }}</strong></a>

                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 col-xl-3 px-md-2 mb-3">
                        <div class="detailsContact h-100">
                            <a target="_blank" href="tel:18008917070">
                                <figure>
                                    <a href="tel:+1800 8917070"><img src="{{ asset('frontend/images/callus-contact.svg') }}"
                                            alt="" width="50"></a>
                                </figure>
                            </a>
                            <span>Call Us <b>Speak to our Team</b></span>
                            <hr>
                            <a href="tel:+1800 8917070" style="text-decoration: none; color: inherit;">
                                <strong>{{ $getSetting['contact_number'] }}</strong></a>
                            {{-- <strong><a href="tel:18008917070">1800 891 7070</a></strong> <!-- Tel link --> --}}
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-3 col-xl-3 px-md-2 mb-3">
                        <div class="detailsContact h-100">
                            <a target="_blank" href="https://maps.app.goo.gl/A9Xs2YYd56diPzqdA">
                                <figure>
                                    <img src="{{ asset('frontend/images/location-contact.svg') }}" alt=""
                                        width="50">
                                </figure>
                            </a>
                            <span>Visit Us <b>Visit our Office HQ</b></span>
                            <hr>
                            <strong>{{ $getSetting['address'] }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="messageMain">
            <div class="lottieRactangle">
                <lottie-player src="{{ asset('frontend/images/master-loading.json') }}" autoPlay loop
                    style="width: 180px; height: 180px;opacity: .4;"></lottie-player>
            </div>
            <div class="container">
                <div class="row m-0">
                    <div class="col-lg-5 col-md-12 p-0">
                        <figure class="sendForm">
                            <img src="{{ asset('frontend/images/contact-us.jpg') }}" alt="">
                        </figure>
                    </div>
                    <div class="col-lg-7 col-md-12 p-0">
                        <div class="sendMessage">
                            <h6>Have a question or feedback?</h6>
                            <p>We’re just a message away! Fill in the details below and our team will get back to you
                                shortly.
                            </p>
                            <form method="POST" action="{{ route('contact-us.save') }}">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label required">Your Name</label>
                                            <input class="form-control w-100 @error('name') is-invalid @enderror"
                                                type="text" name="name" placeholder="Enter your name"
                                                value="{{ old('name') }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label required">Your Email</label>
                                            <input class="form-control w-100 @error('email') is-invalid @enderror"
                                                type="email" name="email" placeholder="Enter your email"
                                                value="{{ old('email') }}" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label required">Your Mobile Number</label>
                                            <input class="form-control w-100 @error('mobile_no') is-invalid @enderror"
                                                type="number" name="mobile_no" placeholder="Enter your mobile number"
                                                value="{{ old('mobile_no') }}" required>
                                            @error('mobile_no')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label required">Subject</label>
                                            <input class="form-control w-100 @error('subject') is-invalid @enderror"
                                                type="text" name="subject" placeholder="Enter subject"
                                                value="{{ old('subject') }}" required>
                                            @error('subject')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="message" class="form-label required">Message</label>
                                            <textarea class="form-control @error('message') is-invalid @enderror" name="message" id="message"
                                                style="height: 80px;" placeholder="Enter your message" required>{{ old('message') }}</textarea>
                                            @error('message')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="d-block mb-3" id="google-recaptcha-checkbox" width="230"></div> --}}
                                <div class="form-group{{ $errors->has('captcha') ? ' has-error' : '' }}">
                                    <label for="captcha" class="col-md-4 control-label required">Captcha</label>
                                    <div class="col-md-12">
                                        <div class="captcha">
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <span>{!! captcha_img() !!}</span>
                                                <button type="button" class="bg-transparent border-0 btn-refresh">
                                                    <i class="fa fa-refresh"></i>
                                                </button>
                                                <input id="captcha" type="text" class="form-control"
                                                    placeholder="Enter Captcha" name="captcha">
                                            </div>

                                            @if ($errors->has('captcha'))
                                                <div class="text-danger mt-1">
                                                    <small>{{ $errors->first('captcha') }}</small>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn btn-primary-gradient rounded-1">Send
                                        Message</button>
                                </div>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="askQueations">
            <div class="container">
                <div class="section-heading mx-0 text-start">
                    <h2><span class="greenBorder"></span>
                        Frequently Asked Questions (FAQs)</h2>
                    <p>Our FAQ section provides, solutions to common queries about Mittlearn's platform, courses, payments,
                        and more – all in one place!
                    </p>
                </div>
                <div class="row align-items-center">
                    <div class="col-md-12 col-lg-8">
                        <div class="accordion" id="accordionExample">
                            @if (isset($getFaqs))
                                @foreach ($getFaqs as $faq)
                                    <div class="accordion-item">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapse{{ $loop->iteration }}"
                                            aria-expanded="false" aria-controls="collapse{{ $loop->iteration }}">
                                            Q.{{ $faq->sort_order ?? '' }} {{ $faq->question ?? '' }}
                                        </button>
                                        <div id="collapse{{ $loop->iteration }}" class="accordion-collapse collapse"
                                            data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <p class="m-0">{{ $faq->answer ?? '' }}</p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>

                    </div>
                    <div class="col-md-12 col-lg-4">
                        <lottie-player src="{{ asset('frontend/images/business-thinking.json') }}" autoPlay loop
                            style="width: 220px;height: 220px;margin: auto;"></lottie-player>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        $(".btn-refresh").click(function() {
            $.ajax({
                type: 'GET',
                url: '/refresh-captcha',
                success: function(data) {
                    $(".captcha span").html(data.captcha);
                }
            });
        });
    </script>
    {{-- <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>
    <script type="text/javascript">
        var onloadCallback = function() {
            grecaptcha.render('google-recaptcha-checkbox', {
                'sitekey': '{{ env('CAPTCHA_SITE_KEY') }}' // Dynamically load from .env
            });
        };
    </script> --}}
@endsection
