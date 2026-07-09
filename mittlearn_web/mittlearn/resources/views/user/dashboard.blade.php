@extends('user.layouts.master')
@section('content')
    

    <div class="dashboardMain">
        
        <div class="row px-lg-1">
            <div class="col-lg-6 px-lg-2 mb-3">
                <div class="cardBox adminBx h-100">
                    <div class="">
                        <h6>Hi, James Smith <lottie-player src="{{ asset('frontend/images/hand.json') }}" loop autoplay
                                style="width: 35px;height: 30px;"></lottie-player></h6>
                        <span>Always stay updated in your school admin portal</span>
                        <p>Simply dummy text of the printing and typesetting industry. Lorem Ipsum has been</p>
                    </div>
                    <img src="{{ asset('frontend/images/admin-img.png') }}" alt="" width="200">
                </div>
            </div>
            <div class="col-lg-6 px-lg-2">
                <div class="row px-md-1">
                    <div class="col-md-6 px-md-2 mb-3">
                        <div class="cardBox countBx h-100">
                            <div class="d-flex justify-content-between">
                                <figure>
                                    <img src="{{ asset('frontend/images/total-student-icon.svg') }}" alt="" width="70">
                                </figure>
                                <span>Total Students <b>{{$students}}</b></span>
                            </div>
                            <p><img src="{{ asset('frontend/images/higher-icon.svg') }}" alt="" width="14" class="me-2">10% Higher then Last
                                Month</p>
                        </div>
                    </div>
                    <div class="col-md-6 px-md-2 mb-3">
                        <div class="cardBox countBx h-100">
                            <div class="d-flex justify-content-between">
                                <figure>
                                    <img src="{{ asset('frontend/images/total-teachers-icon.svg') }}" alt="">
                                </figure>
                                <span>Total Teachers <b>{{$teachers}}</b></span>
                            </div>
                            <p><img src="{{ asset('frontend/images/less-icon.svg') }}" alt="" width="14" class="me-2">5% Less then Last Month
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6 px-md-2 mb-3">
                        <div class="cardBox countBx h-100">
                            <div class="d-flex justify-content-between">
                                <figure>
                                    <img src="{{ asset('frontend/images/digital-content.svg') }}" alt="">
                                </figure>
                                <span>Digital Content <b>1K</b></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 px-md-2 mb-3">
                        <div class="cardBox countBx h-100">
                            <div class="d-flex justify-content-between">
                                <figure>
                                    <img src="{{ asset('frontend/images/available-access-icon.svg') }}" alt="">
                                </figure>
                                <span>Available Access <br> Code <b>500</b></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row px-lg-1">
            <div class="col-lg-6 px-lg-2 mb-3">
                <div class="cardBox">
                    <div class="headingBx d-block d-md-flex">
                        <h4>Learning Activity</h4>
                        <div class="d-flex gap-2 mt-3 mt-md-0">
                            <select class="form-select">
                                <option selected>Select Class</option>
                                @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                            <select class="form-select">
                                <option selected>Six Months</option>
                            </select>
                        </div>
                    </div>
                    <div id="learningAct" style="height: 280px;"></div>
                </div>
            </div>
            <div class="col-lg-6 px-lg-2 mb-3">
                <div class="cardBox">
                    <div class="headingBx">
                        <h4>Best Performance</h4>
                        <div>
                            <select class="form-select" name="duration">
                                @foreach(config('constants.DURATION_TYPES') as $duration)
                                    <option value="{{ $duration['value'] }}" {{ $duration['value'] == 'weekly' ? 'selected' : '' }}>
                                        {{ $duration['label'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div id="performanceChart" style="height:280px ;"></div>
                </div>
            </div>
        </div>
        <div class="row px-lg-1">
            <div class="col-lg-4 px-lg-2 mb-3">
                <div class="cardBox">
                    <div class="headingBx">
                        <h4>Notification</h4>
                        <div class="d-flex gap-2">
                            <a href="" class="viewAll">View all</a>
                        </div>
                    </div>
                    <ul class="listingUl">
                        <li>
                            <div class="listBox">
                                <div class="">
                                    <figure class="m-0">
                                        <img src="{{ asset('frontend/images/notification-img1.jpg') }}" alt="">
                                    </figure>
                                </div>
                                <div>
                                    <span>Next Extracurricular Class</span>
                                    <div class="iconBtm">
                                        <b><img src="{{ asset('frontend/images/notification-watch-icon.svg') }}" alt="" class="me-2">4:00
                                            PM</b>
                                        <b><img src="{{ asset('frontend/images/notification-calender-icon.svg') }}" alt="" class="me-2">25
                                            Oct</b>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="listBox">
                                <div class="">
                                    <figure class="m-0">
                                        <img src="{{ asset('frontend/images/notification-img2.jpg') }}" alt="">
                                    </figure>
                                </div>
                                <div>
                                    <span>Scheduled Live Class</span>
                                    <div class="iconBtm">
                                        <b><img src="{{ asset('frontend/images/notification-watch-icon.svg') }}" alt="" class="me-2">2:00
                                            PM</b>
                                        <b><img src="{{ asset('frontend/images/notification-calender-icon.svg') }}" alt="" class="me-2">20
                                            Oct</b>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="listBox">
                                <div class="">
                                    <figure class="m-0">
                                        <img src="{{ asset('frontend/images/notification-img1.jpg') }}" alt="">
                                    </figure>
                                </div>
                                <div>
                                    <span>Next Extracurricular Clubs</span>
                                    <div class="iconBtm">
                                        <b><img src="{{ asset('frontend/images/notification-watch-icon.svg') }}" alt="" class="me-2">4:00
                                            PM</b>
                                        <b><img src="{{ asset('frontend/images/notification-calender-icon.svg') }}" alt="" class="me-2">25
                                            Oct</b>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="listBox">
                                <div class="">
                                    <figure class="m-0">
                                        <img src="{{ asset('frontend/images/notification-img2.jpg') }}" alt="">
                                    </figure>
                                </div>
                                <div>
                                    <span>Scheduled Live Class</span>
                                    <div class="iconBtm">
                                        <b><img src="{{ asset('frontend/images/notification-watch-icon.svg') }}" alt="" class="me-2">1:00
                                            PM</b>
                                        <b><img src="{{ asset('frontend/images/notification-calender-icon.svg') }}" alt="" class="me-2">24
                                            Oct</b>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="listBox">
                                <div class="">
                                    <figure class="m-0">
                                        <img src="{{ asset('frontend/images/notification-img2.jpg') }}" alt="">
                                    </figure>
                                </div>
                                <div>
                                    <span>Next Extracurricular Clubs</span>
                                    <div class="iconBtm">
                                        <b><img src="{{ asset('frontend/images/notification-watch-icon.svg') }}" alt="" class="me-2">4:00
                                            PM</b>
                                        <b><img src="{{ asset('frontend/images/notification-calender-icon.svg') }}" alt="" class="me-2">1
                                            Nov</b>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-8 px-lg-2 mb-3">
                <div class="cardBox">
                    <div class="headingBx d-block d-md-flex">
                        <h4>Overall Performance</h4>
                        <div class="d-flex align-items-center overallSelect gap-2 mt-3 mt-md-0">
                            <select class="form-select">
                                <option selected>Select class</option>
                                @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                            <select class="form-select">
                                <option selected>2023</option>
                            </select>to
                            <select class="form-select">
                                <option selected>2024</option>
                            </select>
                        </div>
                    </div>
                    <div id="overallPerformance" style="height: 240px;"></div>
                </div>
            </div>
        </div>
        <div class="cardBox  mb-3">
            <div class="headingBx">
                <h4>Course Statistics</h4>
                <div class="d-flex align-items-center gap-2">
                    <select class="form-select">
                        <option selected>Nursery Class</option>
                        @foreach($classes as $class)
                        <option value="{{ $class->id }}">{{ $class->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div id="courseStatistics" style="height: 300px;"></div>
        </div>

        <div class="row px-md-1">
            <div class="col-md-7 col-lg-8 px-md-2 mb-3">
                <div class="cardBox">
                    <div class="headingBx">
                        <h4>Teacher Count</h4>
                        <button type="button" class="downloadBtn">
                            <img src="{{ asset('frontend/images/download.svg') }}" alt="" width="30">
                        </button>
                    </div>
                    <div id="teacherCount" style="height: 270px;"></div>
                </div>
            </div>
            <div class="col-md-5 col-lg-4 px-md-2 mb-3">
                <div class="cardBox">
                    <div class="headingBx d-block">
                        <h4 class="mb-2">Total Students with Access Code</h4>

                        <strong class="fs-3">700</strong>
                    </div>
                    <div id="accessCode" style="height: 200px;"></div>
                    <p class="mb-0 mt-3 fs-9 text-center"><img src="{{ asset('frontend/images/chart-icon.svg') }}" width="18"> 68% more
                        earnings than last month</p>
                </div>
            </div>
        </div>

        <div class="row px-lg-1">
            <div class="col-lg-6 px-lg-2 mb-3">
                <div class="cardBox">
                    <div class="headingBx">
                        <h4>Planned Online Classes</h4>
                        <div class="d-flex gap-2">
                            <select class="form-select">
                                <option selected>Select Class</option>
                                @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <ul class="classesUl">
                        <li>
                            <div class="plannedList">
                                <div class="d-flex planUser gap-2">
                                    <figure>
                                        <img src="{{ asset('frontend/images/gallery1.jpg') }}" alt="">
                                    </figure>
                                    <div>
                                        <h4>Planned Online Classes</h4>
                                        <span><img src="{{ asset('frontend/images/list-profile.jpg') }}" alt=""> Joni Iskandar</span>
                                    </div>
                                </div>
                                <strong>Duration<b>1h 35m</b></strong>
                                <strong>Scheduled Time<b>11:51 PM</b></strong>
                            </div>
                        </li>
                        <li>
                            <div class="plannedList">
                                <div class="d-flex planUser gap-2">
                                    <figure>
                                        <img src="{{ asset('frontend/images/gallery1.jpg') }}" alt="">
                                    </figure>
                                    <div>
                                        <h4>Planned Online Classes</h4>
                                        <span><img src="{{ asset('frontend/images/list-profile.jpg') }}" alt=""> Joni Iskandar</span>
                                    </div>
                                </div>
                                <strong>Duration<b>1h 35m</b></strong>
                                <strong>Scheduled Time<b>11:51 PM</b></strong>
                            </div>
                        </li>
                        <li>
                            <div class="plannedList">
                                <div class="d-flex planUser gap-2">
                                    <figure>
                                        <img src="{{ asset('frontend/images/gallery1.jpg') }}" alt="">
                                    </figure>
                                    <div>
                                        <h4>Planned Online Classes</h4>
                                        <span><img src="{{ asset('frontend/images/list-profile.jpg') }}" alt=""> Joni Iskandar</span>
                                    </div>
                                </div>
                                <strong>Duration<b>1h 35m</b></strong>
                                <strong>Scheduled Time<b>11:51 PM</b></strong>
                            </div>
                        </li>
                        <li>
                            <div class="plannedList">
                                <div class="d-flex planUser gap-2">
                                    <figure>
                                        <img src="{{ asset('frontend/images/gallery1.jpg') }}" alt="">
                                    </figure>
                                    <div>
                                        <h4>Planned Online Classes</h4>
                                        <span><img src="{{ asset('frontend/images/list-profile.jpg') }}" alt=""> Joni Iskandar</span>
                                    </div>
                                </div>
                                <strong>Duration<b>1h 35m</b></strong>
                                <strong>Scheduled Time<b>11:51 PM</b></strong>
                            </div>
                        </li>
                        <li>
                            <div class="plannedList">
                                <div class="d-flex planUser gap-2">
                                    <figure>
                                        <img src="{{ asset('frontend/images/gallery1.jpg') }}" alt="">
                                    </figure>
                                    <div>
                                        <h4>Planned Online Classes</h4>
                                        <span><img src="{{ asset('frontend/images/list-profile.jpg') }}" alt=""> Joni Iskandar</span>
                                    </div>
                                </div>
                                <strong>Duration<b>1h 35m</b></strong>
                                <strong>Scheduled Time<b>11:51 PM</b></strong>
                            </div>
                        </li>
                        <li>
                            <div class="plannedList">
                                <div class="d-flex planUser gap-2">
                                    <figure>
                                        <img src="{{ asset('frontend/images/gallery1.jpg') }}" alt="">
                                    </figure>
                                    <div>
                                        <h4>Planned Online Classes</h4>
                                        <span><img src="{{ asset('frontend/images/list-profile.jpg') }}" alt=""> Joni Iskandar</span>
                                    </div>
                                </div>
                                <strong>Duration<b>1h 35m</b></strong>
                                <strong>Scheduled Time<b>11:51 PM</b></strong>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6 px-lg-2 mb-3">
                <div class="cardBox">
                    <div class="headingBx">
                        <h4>Student Count</h4>
                        <div class="d-flex align-items-center gap-2">
                            <select class="form-select">
                                <option selected>Select Class</option>
                                @foreach($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->name }}</option>
                                @endforeach
                            </select>
                            <button type="button" class="downloadBtn">
                                <img src="{{ asset('frontend/images/download.svg') }}" alt="" width="30">
                            </button>
                        </div>
                    </div>
                    <div id="studentCount" style="height: 240px;"></div>

                </div>
            </div>

        </div>

        <div class="cardBox">
            <div class="headingBx pb-3">
                <h4>Total Students with active access code</h4>
                <button type="button" class="downloadBtn">
                    <img src="{{ asset('frontend/images/download.svg') }}" alt="" width="30">
                </button>
            </div>
            <div class="table-responsive tbleDiv ">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Class</th>
                            <th>Code</th>
                            <th>Occupied</th>
                            <th>Remaining</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Nursery</td>
                            <td>100</td>
                            <td>50</td>
                            <td>50</td>
                            <td class="text-success"><a href="">
                                    <img src="{{ asset('frontend/images/green-arrow.svg') }}" alt="" width="26">
                                </a></td>
                        </tr>
                        <tr>
                            <td>K1</td>
                            <td>120</td>
                            <td>80</td>
                            <td>40</td>
                            <td class="text-success"><a href="">
                                    <img src="{{ asset('frontend/images/green-arrow.svg') }}" alt="" width="26">
                                </a></td>
                        </tr>
                        <tr>
                            <td>First</td>
                            <td>100</td>
                            <td>100</td>
                            <td>20</td>
                            <td class="text-success"><a href="">
                                    <img src="{{ asset('frontend/images/green-arrow.svg') }}" alt="" width="26">
                                </a></td>
                        </tr>
                        <tr>
                            <td>K1</td>
                            <td>120</td>
                            <td>100</td>
                            <td>20</td>
                            <td class="text-success"><a href="">
                                    <img src="{{ asset('frontend/images/green-arrow.svg') }}" alt="" width="26">
                                </a></td>
                        </tr>
                        <tr>
                            <td>First</td>
                            <td>100</td>
                            <td>100</td>
                            <td>20</td>
                            <td class="text-success"><a href="">
                                    <img src="{{ asset('frontend/images/green-arrow.svg') }}" alt="" width="26">
                                </a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>



@endsection
