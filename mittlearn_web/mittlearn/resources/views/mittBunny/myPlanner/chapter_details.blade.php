@extends('userPortal.layouts.master')
@section('content')
    <div class="dashboardMain p-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('up.planner') }}">My Planner</a></li>
            <li class="breadcrumb-item active" aria-current="page">Details</li>
        </ol>
        <div class="row">
            <div class="col-md-8 pe-md-1 mb-3 mb-md-0">
                <div class="cardBox planDetails">
                    <h6 class="m-0 pb-3 fw-semibold">Lesson Plan Detail</h6>
                    <div class="accordion accordion-flush mb-4" id="lessonAccordion">
                        <div class="accordion" id="lessonAccordion">
                            {{--  @dd(groupedDetails)  --}}
                            @foreach ($groupedDetails as $type => $details)
                                <div class="accordion-item">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#type-{{ $loop->index }}">
                                        <span class="numbaring">{{ $loop->iteration }}</span>
                                        {{ ucwords(str_replace('_', ' ', $type)) }}
                                    </button>
                                    <div id="type-{{ $loop->index }}" class="accordion-collapse collapse"
                                        data-bs-parent="#lessonAccordion">
                                        <div class="accordion-body">
                                            <ul class="accordianUl">
                                                @foreach ($details as $detail)
                                                    <li>
                                                        <figure class="m-0">
                                                            @if ($detail->image)
                                                                <img src="{{ Storage::url('uploads/planner-files/' . $detail->image) }}"
                                                                    alt="Image">
                                                            @else
                                                                <img src="{{ asset('frontend/images/default-image.jpg') }}"
                                                                    alt="default">
                                                            @endif
                                                        </figure>
                                                        <div>
                                                            <strong>{{ $loop->iteration }}. {{ $detail->title }}</strong>
                                                            <p>{{ $detail->description }}</p>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <h6 class="m-0 pb-3 fw-semibold">Digital Contents</h6>
                    <div class="row px-md-1">
                        @foreach ($digitalContent->chapters as $data)
                            <div class="col-md-3 col-lg-3 col-xl-3 px-md-2 mb-3">
                                <div class="digitalBox">
                                    <figure>
                                        @if (str_contains($data->file_extension, 'mp3') || str_contains($data->file_extension, 'wav'))
                                            <audio controls width="100%" height="100px" controlsList="nodownload"
                                                oncontextmenu="return false;">
                                                <source
                                                    src="{{ Storage::url('uploads/course_chapter_files/' . $data->attachment_file) }}"
                                                    type="audio/{{ $data->file_extension }}">
                                                Your browser does not support the audio element.
                                            </audio>
                                        @elseif (in_array($data->file_extension, ['mp4','avi','mov','m4v','m4p','mpg','mp2','mpeg','mpe','mpv','m2v','wmv','flv','mkv','webm','3gp','m2ts','ogv','ts','mxf']))

                                            <video controls width="100%" height="100px" controlsList="nodownload"
                                                oncontextmenu="return false;">
                                                <source
                                                    src="{{ Storage::url('uploads/course_chapter_files/' . $data->attachment_file) }}"
                                                    type="video/mp4">
                                            </video>
                                        @elseif (str_contains($data->file_extension, 'jpg') || str_contains($data->file_extension, 'png'))
                                            <img src="{{ Storage::url('uploads/course_chapter_files/' . $data->attachment_file) }}"
                                                alt="Image" width="100%" height="100px">
                                        @elseif (str_contains($data->file_extension, 'pdf'))
                                            <iframe
                                                src="{{ Storage::url('uploads/course_chapter_files/' . $data->attachment_file) }}"
                                                width="100%" height="100px">
                                            </iframe>
                                        @elseif (str_contains($data->file_extension, 'xlsx'))
                                            <iframe
                                                src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode(Storage::url('uploads/course_chapter_files/' . $data->attachment_file)) }}"
                                                width="100%" height="100px" frameborder="0">
                                                Your browser does not support displaying Excel files.
                                            </iframe>
                                        @elseif (str_contains($data->file_extension, 'docx'))
                                            <iframe
                                                src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode(Storage::url('uploads/course_chapter_files/' . $data->attachment_file)) }}"
                                                width="100%" height="100px" frameborder="0">
                                                Your browser does not support displaying Word files.
                                            </iframe>
                                        @else
                                            <img src="{{ asset('frontend/images/default-icon.svg') }}" width="100%"
                                                height="100px" alt="Default Icon">
                                        @endif
                                    </figure>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if ($supportingFiles && $supportingFiles->isNotEmpty())
                        <h6 class="m-0 pb-3 fw-semibold">Important Documents</h6>
                        <div class="footerBtn">
                            @foreach ($supportingFiles as $data)
                                <a href="{{ route('chapter.documents', $data->id) }}"
                                    class="btn btn-primary-gradient rounded-1">{{ $data->original_name }}</a>
                            @endforeach
                            <a href="{{ route('chapter.supporting-documents.download', $folderId) }}"
                                class="btn bg-success rounded-1">
                                <img src="{{ asset('frontend/images/download-icon-white.svg') }}" width="14"
                                    class="me-2">
                                Download Documents
                            </a>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                <div class="cardBox lessonDetail">
                    <h6 class="m-0 pb-3 fw-semibold">Lesson Detail</h6>
                    <div class="d-flex align-items-center gap-3 mb-3" title="{{ $digitalContent->chapter_name }}">
                        <figure class="m-0">
                            <img src="{{ asset('frontend/images/chepter-icon.jpg') }}" alt="" width="35">
                        </figure>
                        <span>{{ Str::limit($digitalContent->chapter_name, 30, '...') }}</span>
                    </div>
                    {{--  <strong class="mb-2">Instructor : Neha Vyas</strong>  --}}
                    <div class="actualStatus">
                        <b>Actual Status</b>
                        <span>{{ $actualPercentage }}%</span>
                        <div class="progress" role="progressbar" aria-label="Basic example"
                            aria-valuenow="{{ $actualPercentage }}" aria-valuemin="0" aria-valuemax="100">
                            <div class="progress-bar" style="width: {{ $actualPercentage }}%"></div>
                        </div>
                        <strong class="pt-2">
                            Estimated Status ~ 100%
                            @if (Carbon\Carbon::today()->lt($startDate))
                                (Course has not started yet)
                            @endif
                        </strong>
                    </div>

                    <div class="table-responsive tbleDiv detailTable mt-3">
                        <table class="table">
                            <tr>
                                <td>Board:</td>
                                <td>{{ $plannerLesson->board->name }}</td>
                            </tr>
                            <tr>
                                <td>Medium:</td>
                                <td>{{ $plannerLesson->medium->name }}</td>
                            </tr>
                            <tr>
                                <td>Series:</td>
                                <td>{{ $plannerLesson->series->name }}</td>
                            </tr>
                            <tr>
                                <td>Class:</td>
                                <td>{{ $plannerLesson->class->name }}</td>
                            </tr>
                            <tr>
                                <td>Subject:</td>
                                <td>{{ $plannerLesson->subject->name }}</td>
                            </tr>
                            <tr>
                                <td>Chapter No:</td>
                                <td>{{ $digitalContent->sort_order }}</td>
                            </tr>
                            <tr>
                                <td>Chapter Title:</td>
                                <td>{{ Str::limit($digitalContent->chapter_name, 30, '...') }}</td>
                            </tr>
                            <tr>
                                <td>Allotted Days:</td>
                                <td>{{ $plannerLesson->allotted_days }}</td>
                            </tr>
                            <tr>
                                <td>Start Date:</td>
                                <td>{{ $plannerLesson->start_date }}</td>
                            </tr>
                            <tr>
                                <td>End Date:</td>
                                <td>{{ $plannerLesson->completion_date }}</td>
                            </tr>
                            <tr>
                                <td>Total Periods:</td>
                                <td>{{ $plannerLesson->total_periods }}</td>
                            </tr>

                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://code.jquery.com/ui/1.14.0/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
<script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/variable-pie.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.4/js/select2.min.js"></script>
<script>
    $(function () {
        $("#datepicker").datepicker();
        $("#datepicker1").datepicker();
    });

    $('.toggleBtn').click(function () {
        $('body').toggleClass("open-sidebar");
    });

    $('.alertList').slick({
        autoplay: true,
        slidesToShow: 1,
        arrows: false,
        dots: false,
        autoplaySpeed: 0,
        speed: 15000,
        cssEase: 'linear',
        variableWidth: true,
    });

    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

    $(".js-select2").select2({
        closeOnSelect: false,
        placeholder: "Select",
        // allowHtml: true,
        allowClear: false,
        tags: true // создает новые опции на лету
    });

</script> --}}
@endsection
