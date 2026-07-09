@extends('mittBunny.layouts.master')
@section('content')
    <div class="dashboardMain">

        <div class="helloSection">
            <div class=" pe-md-5">
                <h2><b>My</b> Planner</h2>
                <p>Your personalized student planner for success.</p>
            </div>
            <div class="d-flex align-items-center gap-4">
                <span class="badge">Nursery</span>
                <lottie-player src="{{ asset('mittbunny/images/peacock.json') }}" background="transparent" speed="1"
                    style="width: 80px; height: 80px;" loop autoplay></lottie-player>
            </div>
        </div>
        <h3 class="fs-8 text-secondary">Select Subject</h3>
        <ul class="filterButtonUl mb-4">
            <li>
                <button type="button" class="filterbutton rounded-5 active">Science</button>
            </li>
            <li>
                <button type="button" class="filterbutton rounded-5">Mathematics</button>
            </li>
            <li>
                <button type="button" class="filterbutton rounded-5">English</button>
            </li>
            <li>
                <button type="button" class="filterbutton rounded-5">Art & Craft</button>
            </li>
            <li>
                <button type="button" class="filterbutton rounded-5">Hindi</button>
            </li>
            <li>
                <button type="button" class="filterbutton rounded-5">Computer</button>
            </li>
            <li>
                <button type="button" class="filterbutton rounded-5">General Knowledge</button>
            </li>
        </ul>
        <h3 class="fs-8 text-secondary">Select Stage</h3>
        <ul class="filterButtonUl">
            <li>
                <button type="button" class="filterbutton active">Stage 1</button>
            </li>
            <li>
                <button type="button" class="filterbutton">Stage 2</button>
            </li>
            <li>
                <button type="button" class="filterbutton">Stage 3</button>
            </li>
            <li>
                <button type="button" class="filterbutton">Stage 4</button>
            </li>
        </ul>
        <div class="plannerSection mt-4">
            <ul class="planList">
                <li>
                    <a href="" class="planCard disabled">
                        <figure><img src="{{ asset('mittbunny/images/planimg1.jpg')}}" alt=""></figure>
                        <strong>Monday</strong>Hindi Sulek Barakhadi
                    </a>
                    <span>Day 1</span>
                </li>
                <li>
                    <a href="" class="planCard disabled">
                        <figure><img src="{{ asset('mittbunny/images/planimg1.jpg')}}" alt=""></figure><strong>Tuesday</strong>Hindi Sulek
                        Barakhadi
                    </a><span>Day 2</span>
                </li>
                <li>
                    <a href="" class="planCard currentDay">
                        <figure><img src="{{ asset('mittbunny/images/planimg2.jpg')}}" alt=""></figure><strong>Wednesday</strong>Hindi Sulek
                        Barakhadi
                    </a><span>Day 3</span>
                </li>
                <li>
                    <a href="" class="planCard">
                        <figure><img src="{{ asset('mittbunny/images/planimg3.jpg')}}" alt=""></figure> <strong>Thursday</strong>Hindi Sulek
                        Barakhadi
                    </a><span>Day 4</span>
                </li>
                <li>
                    <a href="" class="planCard">
                        <figure><img src="{{ asset('mittbunny/images/planimg1.jpg')}}" alt=""></figure><strong>Friday</strong>Hindi Sulek
                        Barakhadi
                    </a><span>Day 5</span>
                </li>
                <li>
                    <a href="" class="planCard">
                        <figure><img src="{{ asset('mittbunny/images/planimg2.jpg')}}" alt=""></figure><strong>Saturday</strong>Hindi Sulek
                        Barakhadi
                    </a><span>Day 6</span>
                </li>
                <li>
                    <a href="" class="planCard">
                        <figure><img src="{{ asset('mittbunny/images/planimg3.jpg')}}" alt=""></figure> <strong>Sunday</strong>Hindi Sulek
                        Barakhadi
                    </a><span>Day 7</span>
                </li>
                <li>
                    <a href="" class="planCard">
                        <figure><img src="{{ asset('mittbunny/images/planimg1.jpg')}}" alt=""></figure><strong>Monday</strong>Hindi Sulek
                        Barakhadi
                    </a><span>Day 8</span>
                </li>
                <li>
                    <a href="" class="planCard">
                        <figure><img src="{{ asset('mittbunny/images/planimg2.jpg')}}" alt=""></figure><strong>Tuesday</strong>Hindi Sulek
                        Barakhadi
                    </a><span>Day 9</span>
                </li>
                <li>
                    <a href="" class="planCard">
                        <figure><img src="{{ asset('mittbunny/images/planimg3.jpg')}}" alt=""></figure> <strong>Wednesday</strong>Hindi
                        Sulek
                        Barakhadi
                    </a><span>Day 10</span>
                </li>
                <li>
                    <a href="" class="planCard">
                        <figure><img src="{{ asset('mittbunny/images/planimg1.jpg')}}" alt=""></figure><strong>Thursday</strong>Hindi Sulek
                        Barakhadi
                    </a><span>Day 11</span>
                </li>
                <li>
                    <a href="" class="planCard">
                        <figure><img src="{{ asset('mittbunny/images/planimg2.jpg')}}" alt=""></figure><strong>Friday</strong>Hindi Sulek
                        Barakhadi
                    </a><span>Day 12</span>
                </li>
            </ul>
        </div>



    </div>
@endsection
