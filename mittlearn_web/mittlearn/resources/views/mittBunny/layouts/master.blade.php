<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $links['site_page_title'] }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="{{ asset('mittbunny/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/css/custom.css') }}" rel="stylesheet">
    {{-- crooper --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script type="text/javascript" src="{{ asset('frontend/js/init.js') }}"></script>

    <script>
        var base_url = "{{ url('/') . '/' }}";
        var csrf_token = "{{ csrf_token() }}";
    </script>
</head>

<body style="background-color: #F9F9F9;">


    @include('mittBunny.layouts.header')
    @include('mittBunny.layouts.sidebar')

    <main id="main" class="main">
        @yield('content')

    </main>

    <div class="footerBottom">
        <ul class="footerLeft">
            <li><strong>{{ $links['company_name'] }}</strong>
            </li>
            <li><img src="{{ asset('frontend/images/location-icon.svg') }}" alt=""
                    width="12">{{ $links['user_email'] }}
            </li>
            <li><img src="{{ asset('frontend/images/call-icon.svg') }}" alt="" width="13">
                {{ $links['user_contact_number'] }}</li>
            <li><img src="{{ asset('frontend/images/mail-icon.svg') }}" alt="" width="18">
                {{ $links['user_address'] }}</li>
        </ul>
        <ul class="footerright">
            <li><a target="_blank" href={{ $links['facebook'] }}><img
                        src="{{ asset('frontend/images/facebook.svg') }}" width="18" height="18"></a>
            </li>
            <li><a target="_blank" href={{ $links['instagram'] }}><img
                        src="{{ asset('frontend/images/instagram.svg') }}" width="18" height="18"></a></li>
            <li><a target="_blank" href={{ $links['twitter'] }}><img src="{{ asset('frontend/images/twitter.svg') }}"
                        width="18" height="18"></a>
            </li>
            <li><a target="_blank" href={{ $links['linkedin'] }}><img
                        src="{{ asset('frontend/images/linkedin.svg') }}" width="18" height="18"></a></li>
            <li><a target="_blank" href={{ $links['you_tube'] }}><img src="{{ asset('frontend/images/youtube.svg') }}"
                        width="18" height="18"></a>
            </li>
        </ul>
    </div>
    <script src="{{ asset('frontend/js/script.js') }}"></script>
    {{-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/variable-pie.js"></script>
    <script src="https://code.highcharts.com/modules/xrange.js"></script>
    <script src="https://code.highcharts.com/highcharts-more.js"></script>
    <script src="https://code.highcharts.com/modules/solid-gauge.js"></script>
    {{-- crooper --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Target all modals with the 'coursePrv' class
            const modals = document.querySelectorAll('.modalvid');

            modals.forEach(modal => {
                modal.addEventListener('hidden.bs.modal', function() {
                    // Pause all videos inside the modal
                    const videos = modal.querySelectorAll('video');
                    videos.forEach(video => {
                        video.pause();
                        video.currentTime = 0; // Optional: Reset video to start
                    });
                });
            });
        });
        $('.alertList').slick({
            autoplay: true,
            slidesToShow: 1,
            arrows: false,
            dots: false,
            autoplaySpeed: 0,
            speed: 30000,
            cssEase: 'linear',
            variableWidth: true,
            pauseOnHover: true
        });
        $('.toggleBtn').click(function() {
            $('body').toggleClass("open-sidebar");
        });

        if ($('#courseStatistics').length) {
            Highcharts.chart('courseStatistics', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Time Spent Per Month'
                },
                xAxis: {
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov',
                        'Dec'
                    ],
                    crosshair: true
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: 'Minutes Watched'
                    }
                },
                tooltip: {
                    valueSuffix: ' min'
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.1,
                        borderWidth: 0
                    }
                },
                series: [{
                    name: 'Academic',
                    data: timeSpendingsData.academic,
                    color: '#F2C200'
                }, {
                    name: 'Talent/Skiils',
                    data: timeSpendingsData.non_academic,
                    color: '#785FF4'
                }]
            });
        }
    </script>
</body>

</html>
