@extends('userPortal.layouts.master')
@section('content')
    <div class="dashboardMain p-4">
        <div class="cardBox dailyPlanner">
            @if (isset($classes) && $classes->unique('class_id')->isNotEmpty())
                @if ($plannerType === 'daily')
                    @include('userPortal.myPlanner.daily_planner', [
                        'allDates' => $allDates,
                        'subjects' => $subjects,
                        'dayWiseData' => $dayWiseData,
                    ])
                @elseif ($plannerType === 'weekly')
                    @include('userPortal.myPlanner.weekly_planner', [])
                @else
                     <p class="fw-medium">Your class planner isn’t ready just yet!
                    Please check back soon to see it in action! </p>
                @endif
            @else
                <div class="">
                    <h2 class="fs-6 fw-semibold mb-3">Planner</h2>
                    <p class="fw-medium">Your class planner isn’t ready just yet!
                        Please check back soon to see it in action! </p>
                </div>
            @endif
        </div>
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
                        var routeUrl = `/planner/${courseSlug}/${courseId}`;

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

        $(document).ready(function() {
            $('#selectType').on('change', function() {
                var selectedType = $(this).val();
                var classId = getParameterByName('class_id');

                updateURL(classId, selectedType);
            });
        });

        function updateURL(classId, typeValue) {
            const url = new URL(window.location.href);

            if (typeValue && typeValue !== 'all') {
                url.searchParams.set('type', typeValue);
                url.searchParams.delete('class_id');
            } else if (classId) {
                url.searchParams.set('class_id', classId);
                url.searchParams.delete('type');
            }

            window.location.href = url.toString();
        }

        // Helper function to get query parameters from URL
        function getParameterByName(name) {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get(name);
        }
    </script>
    <script>
        // When the modal is opened, store the necessary data in a global variable
        $(document).ready(function() {
            // Listen for when the modal is triggered
            $('#statusMdl').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // The button that triggered the modal

                // Get data from the button's attributes
                var schoolId = button.data('school-id');
                var dayIndex = button.data('day-index');
                var selectedDate = button.data('date');

                // Set the hidden input fields in the modal
                $('#modal-school-id').val(schoolId);
                $('#modal-day-index').val(dayIndex);
                $('#modal-date').val(selectedDate);

                // console.log("School ID:", schoolId);
                // console.log("Day Index:", dayIndex);
                // console.log("Selected Date:", selectedDate);
            });

            // When the "Yes" button is clicked in the modal, make the API request
            $('#confirmHolidayBtn').click(function() {
                // Get the values from the hidden input fields
                var schoolId = $('#modal-school-id').val();
                var dayIndex = $('#modal-day-index').val();
                var selectedDate = $('#modal-date').val();

                // Make the AJAX request
                $.ajax({
                    url: '{{ route('daily.planner.mark.holiday') }}', // Define the route for marking a holiday
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        holiday_date: selectedDate,
                        school_id: schoolId,
                        day_index: dayIndex // Send the correct day index
                    },
                    success: function(response) {
                        if (response.success) {
                            alert('Holiday successfully assigned!');
                            location.reload(); // Reload the page to reflect changes
                        } else {
                            alert('Something went wrong!');
                        }
                    }
                });
            });
        });
    </script>
@endsection
