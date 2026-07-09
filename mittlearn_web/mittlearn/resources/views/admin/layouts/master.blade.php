<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>{{ $links['site_page_title'] }}</title>
    <meta content="Online Courses" name="keywords">
    <meta name="description"
        content="Achieve Your Dreams by Learning New Skills. Mittlearn provides you the opportunity to learn new skills at the comfort of your home."
        class="yoast-seo-meta-tag" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Favicons -->
    <link href="{{ asset('images/mittlearn-favicon.png') }}" rel="icon">
    <link href="{{ asset('images/mittlearn-favicon.png') }}" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('admin/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendor/quill/quill.snow.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendor/quill/quill.bubble.css') }}" rel="stylesheet">
    <!-- Quill CSS -->
    <link href="https://cdn.jsdelivr.net/npm/quill/dist/quill.snow.css" rel="stylesheet">

    <!-- KaTeX CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex/dist/katex.min.css">

    <link href="{{ asset('admin/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendor/simple-datatables/style.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendor/font-awesome/all.min.css') }}">

    <link href="{{ asset('admin/vendor/datatables.net-bs/css/dataTables.bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendor/sweetalert2-7.0.0/sweetalert2.css') }}" rel="stylesheet">
    {{-- <link href="{{ asset('admin/vendor/select2/dist/css/select2.min.css') }}" rel="stylesheet"> --}}
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />
    <link href="{{ asset('admin/vendor/selectize/selectize.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendor/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendor/nprogress/nprogress.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendor/bootstrap-daterangepicker/daterangepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendor/iCheck/skins/flat/green.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendor/bootstrap-datetimepicker/css/datetimepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendor/bootstrap-datepicker/css/datepicker.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendor/summernote-0.8.8/dist/summernote-bs4.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendor/drop-Down-Combo-Tree/comboTreeStyle.css') }}" rel="stylesheet">

    <!-- Tagify -->
    <link rel="stylesheet" href="{{ asset('admin/css/tagify.css') }}">
    <!-- Template Main CSS File -->
    <link href="{{ asset('admin/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/css/admin_style.css') }}" rel="stylesheet">
    <script src="{{ asset('frontend/js/init.js') }}"></script>

    <script>
        var base_url = "{{ url('/') . '/' }}";
        var csrf_token = "{{ csrf_token() }}";
    </script>


    <script type="text/javascript" src="{{ asset('admin/vendor/jquery/jquery.min.js') }}"></script>
    {{-- <script type="text/javascript" src="{{ asset('admin/vendor/select2/dist/js/select2.full.min.js') }}"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

    <script src="{{ asset('admin/vendor/selectize/selectize.js') }}"></script>
    <script src="{{ asset('admin/vendor/drop-Down-Combo-Tree/comboTreePlugin.js') }}"></script>

    @livewireStyles
    @pharaonic-livewire-select2

</head>

<body>

    @include('admin.layouts.header')

    <aside id="sidebar" class="sidebar">
        @include('admin.layouts.sidebar')
    </aside><!-- End Sidebar-->

    <main id="main" class="main">
        @include('admin.layouts.flash-messages')
        {{-- @include('admin.layouts.breadcrumbs') --}}

        @yield('breadcrumb')
        @yield('content')

    </main>

    <!-- ======= Footer ======= -->
    <footer id="footer" class="footer">
    </footer><!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>


    <!-- Vendor JS Files -->
    {{-- <script src="{{ asset('admin/vendor/apexcharts/apexcharts.min.js') }}"></script>
    <script src="{{ asset('admin/vendor/chart.js') }}/chart.umd.js') }}"></script>
    <script src="{{ asset('admin/vendor/echarts/echarts.min.js') }}"></script> --}}



    <script src="{{ asset('admin/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('admin/vendor/quill/quill.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill/dist/quill.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/katex/dist/katex.min.js"></script>

    <script src="{{ asset('admin/vendor/simple-datatables/simple-datatables.js') }}"></script>
    <script src="{{ asset('admin/vendor/tinymce/tinymce.min.js') }}"></script>
    <script src="{{ asset('admin/vendor/sweetalert2-7.0.0/sweetalert2.min.js') }}"></script>
    @yield('javascript')
    <script src="{{ asset('admin/js/tagify.js') }}"></script>

    <script type="text/javascript" src="{{ asset('admin/js/page.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/ajax_call.js') }}"></script>
    <!-- Template Main JS File -->
    <script src="{{ asset('admin/js/main.js') }}"></script>
    <script src="{{ asset('admin/js/page.js') }}"></script>
    <script src="{{ asset('admin/js/vallidation.js') }}"></script>
    <script>
        $(document).ready(function() {
            if ($('#multiSelect').length) {
                $('#multiSelect').select2({
                    placeholder: 'Choose categories...',
                    maximumSelectionLength: 20,
                });
            }
        });
    </script>


    {{-- <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script> --}}


    @stack('scripts')
    @livewireScripts
</body>

</html>
