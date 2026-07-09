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
                        <lottie-player src="{{ asset('mittbunny/images/peacock.json') }}" background="transparent"
                            speed="1" style="width: 80px; height: 80px;" loop autoplay></lottie-player>

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
        @if (isset($classes) && $classes->unique('class_id')->isNotEmpty())
            @if ($plannerType === 'daily')
                @include('mittBunny.myPlanner.daily_planner', [
                    'allDates' => $allDates,
                    'subjects' => $subjects,
                    'dayWiseData' => $dayWiseData,
                ])
            @elseif ($plannerType === 'weekly')
                @include('mittBunny.myPlanner.weekly_planner', [])
            @else
                <p class="fw-medium">Your class planner isn’t ready just yet!
                    Please check back soon to see it in action! </p>
            @endif
        @else
            <div class="">
                <h2 class="fs-6 fw-semibold mb-3">Planner</h2>
                <p class="fw-medium">Your class planner isn’t ready just yet!
                    Please check back soon to see it in action!</p>
            </div>
        @endif
    </div>

    <div class="modal fade plannerChapterModal" id="weeklyPlannerChapterModal" tabindex="-1"
        aria-labelledby="weeklyPlannerChapterModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header align-items-start border-0">
                    <h1 class="modal-title fs-5" id="weekName"></h1>
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
        $(document).ready(function() {
            $('th[class*="currentBg"], th span').on('click', function() {
                var weekNumber = $(this).text().trim().replace('Week ', ''); // Extract week number

                // Remove active class from all headers and add to clicked one
                $('th').removeClass('active-week');
                $(this).closest('th').addClass('active-week');

                // Scroll to the corresponding column
                var targetColumn = $('td[data-week-number="' + weekNumber + '"]');
                if (targetColumn.length) {
                    $('html, body').animate({
                        scrollLeft: targetColumn.offset().left -
                            100 // Adjust offset for better view
                    }, 800);
                }
            });
            $('.open-weekly-planner-modal').on('click', function(event) {
                event.preventDefault(); // Prevent default anchor behavior

                var button = $(this); // Get the clicked button
                var weekNumber = button.data('week-number');
                var weekName = button.data('week-name');
                var chaptersData = button.attr('data-chapters'); // Get chapters JSON string

                try {
                    var decodedData = $('<textarea/>').html(chaptersData).text();
                    var chapters = JSON.parse(decodedData);

                    // Set modal title
                    $('#weekName').text(weekName + ' Chapters');

                    // Clear previous content in table body
                    var tableBody = $('#weeklyPlannerChapterModal tbody');
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
                    $('#weeklyPlannerChapterModal').modal('show');

                } catch (error) {
                    console.error('Error parsing JSON:', error);
                }
            });
        });

        function updateURL(subjectId) {
            const url = new URL(window.location.href);
            url.searchParams.set('subject_id', subjectId);

            window.location.href = url.toString();
        }

        // Helper function to get query parameters from URL
        function getParameterByName(name) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(name);
        }
    </script>

@endsection
