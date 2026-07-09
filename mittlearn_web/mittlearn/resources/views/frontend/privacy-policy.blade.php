@extends('frontend.layouts.master')

@section('content')
    <div>
        <div class="aboutMain">
            <div class="">
                <div class="item">
                    <img src="{{ asset('frontend/images/sliderOne.png') }}" alt="">
                </div>
            </div>
            <div class="container">
                <div class="bannerTxt">
                    <div class="sliderTxt">
                        <h3>{{ $policy->title }}</h3>
                        <p>{{ $policy->meta_title }}</p>
                    </div>
                </div>
            </div>

        </div>
        <div class="technoSection">
            <div class="container">
                <div class="section-heading">
                    <h2><span class="greenBorder"></span>
                </div>
                <div class="row align-items-center">
                    {!! $policy->description !!}
                </div>
            </div>
        </div>
    </div>
@endsection
