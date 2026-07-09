@extends('mittBunny.layouts.master')
@section('content')
    <div class="dashboardMain">

        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <div class="leftpanel">
        <div class="helloSection">
            <div class=" pe-md-5">
                <h2><b>My</b> Planner</h2>
                <p>Your personalized student planner for success.</p>
            </div>
            <div class="d-flex align-items-center gap-4">
                @php
                    $student = session('student_class');
                @endphp
                @if ($student)
                    <span class="badge">{{ $student['class'] }}</span>
                @endif
                <lottie-player src="{{ asset('mittbunny/images/peacock.json') }}" background="transparent" speed="1"
                    style="width: 80px; height: 80px;" loop autoplay></lottie-player>
                    
                </div>
            </div>
        </div>
        <div class=" h-auto">
            @include('mittBunny.layouts.profile-header')
        </div>
    </div>
    <h3 class="fs-8 text-secondary">Select Subject</h3>
        <ul class="filterButtonUl mb-4">
            @if (!empty($subjects))
                @foreach ($subjects as $subject)
                    <li>
                        <button type="button"
                            class="subjectBtn rounded-5 {{ $subject->subject->id == $subjectId ? 'active' : '' }}"
                            onclick="updateURL({{ $subject->subject->id }})">
                            {{ $subject->subject->name }}
                        </button>
                    </li>
                @endforeach
            @endif

        </ul>
        <div class="plannerSection mt-4">
            <ul class="planList">
                @foreach ($classPlannerData as $classId => $planners)
                    @foreach ($planners as $planner)
                        @php
                            $monthNumber = \Carbon\Carbon::parse($planner['start_date'])->format('m'); // Get month number
                            $isCurrentMonth = now()->format('m') == $monthNumber ? 'currentDay' : ''; // Highlight current month
                            $chapterTitles = array_values($planner['titles']); // Get chapter names
                            $firstChapter = $chapterTitles[0] ?? 'No Chapter'; // Get the first chapter name
                            $extraCount = count($chapterTitles) - 1; // Count extra chapters
                            $extraText = $extraCount > 0 ? ' +' . $extraCount . ' more chapters' : ''; // Show "+2 more" if extra chapters exist
                        @endphp
                        <li>
                            <a href="javascript:void(0)" class="planCard {{ $isCurrentMonth }} open-monthly-planner-modal"
                                data-chapters="{{ htmlspecialchars(
                                    json_encode([
                                        'ids' => $planner['chapter_id'],
                                        'titles' => array_values($planner['titles']),
                                        'course_slug' => $planner['course_slug'],
                                        'course_id' => $planner['course_id'],
                                    ]),
                                    ENT_QUOTES,
                                    'UTF-8',
                                ) }}">
                                <figure><img
                                        src="{{ asset('mittbunny/images/planimg' . (($monthNumber % 3) + 1) . '.jpg') }}"
                                        alt=""></figure>
                                <strong>Month {{ $monthNumber }}</strong>
                                {{ $firstChapter }} <span class="pluseMore">{{ $extraText }}</span>
                            </a>
                            <span class="planList-span">Month {{ $monthNumber }}</span>
                        </li>
                    @endforeach
                @endforeach
            </ul>
        </div>




    </div>
    <div class="modal fade plannerChapterModal" id="monthlyPlannerChapterModal" tabindex="-1"
        aria-labelledby="monthlyPlannerChapterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header align-items-start border-0">
                    <h1 class="modal-title fs-5" id="weekName">Chapters</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="table-responsive tbleDiv ">
                        <table class="table ">
                            <thead>
                                <tr>
                                    <th>Chapter No.</th>
                                    <th>Chapter Name</th>
                                    <th>View</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>
                                        <div class="d-flex align-items-center myCourseLft">
                                            <div class="coursesName ps-0">
                                                <h3>ABC</h3>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="" class="bg-transparent border-0 p-0">
                                            <img src="/frontend/images/icon-eye.svg" alt="" width="28">
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function updateURL(subjectId) {
            const url = new URL(window.location.href);
            url.searchParams.set('subject_id', subjectId);

            window.location.href = url.toString();
        }
        $(document).on('click', '.open-monthly-planner-modal', function(event) {
            event.preventDefault(); // Prevent default anchor behavior

            var button = $(this); // Get the clicked button
            var chaptersData = button.attr('data-chapters'); // Get chapters JSON string

            try {
                var decodedData = $('<textarea/>').html(chaptersData).text();
                var chapters = JSON.parse(decodedData);

                // Set modal title
                // $('#weekName').text(weekName + ' Chapters');

                // Clear previous content in table body
                var tableBody = $('#monthlyPlannerChapterModal tbody');
                tableBody.empty();

                // Iterate through chapters and add rows to the table
                chapters.ids.forEach(function(chapterId, index) {
                    var chapterName = chapters.titles[index] || 'No Title';
                    var courseSlug = chapters.course_slug || 'default-slug';
                    var courseId = chapters.course_id || '0';
                    var routeUrl = `/mittbunny/planner-detail/${courseSlug}/${courseId}`;

                    var tableRow = `
                    <tr>
                        <td>${index + 1}</td> 
                        <td>
                            <div class="d-flex align-items-center myCourseLft">
                                <div class="coursesName ps-0">
                                    <h3>${chapterName}</h3>
                                </div>
                            </div>
                        </td>
                        <td>
                            <a href="${routeUrl}" class="bg-transparent border-0 p-0">
                                <img src="/frontend/images/icon-eye.svg" alt="View Chapter" width="28">
                            </a>
                        </td>
                    </tr>`;

                    tableBody.append(tableRow);
                });

                // Manually show the modal
                $('#monthlyPlannerChapterModal').modal('show');

            } catch (error) {
                console.error('Error parsing JSON:', error);
            }
        });

        $(document).ready(function() {
            $('#selectType').on('change', function() {
                var selectedType = $(this).val(); // Get selected type value
                const url = new URL(window.location.href);

                if (selectedType && selectedType !== 'all') {
                    url.searchParams.set('type', selectedType);
                }
                window.location.href = url.toString();
            });
        });
    </script>
@endsection
