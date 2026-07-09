@extends('userPortal.layouts.master')
@section('content')
    <div class="dashboardMain p-4">
        <div class="cardBox dailyPlanner">
            @if ($classes->unique('class_id')->isNotEmpty())
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="nurseryTab">
                        <div>
                            <div class="d-md-flex justify-content-end">
                                <ul class="filterButtonUl align-items-center">
                                    <li>
                                        <select class="form-select" id="monthFilter">
                                            @foreach (range(1, 12) as $monthNumber)
                                                <option value="{{ $monthNumber }}"
                                                    {{ now()->format('n') == $monthNumber ? 'selected' : '' }}>
                                                    {{ \Carbon\Carbon::create()->month($monthNumber)->format('F') }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </li>
                                </ul>
                            </div>
                            <div class="table-responsive tbleDiv">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-start" style="vertical-align: middle;">Class</th>
                                            <th class="text-center" colspan="5">
                                                <span>Planner - {{ \Carbon\Carbon::now()->format('F') }}</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($classPlannerData as $classId => $planners)
                                            @php
                                                $class = $classes->where('class_id', $classId)->first(); // Get class name by class_id
                                            @endphp
                                            <tr>
                                                <td class="text-start fw-semibold">
                                                    {{ $class->class->name ?? 'No Class Name' }}
                                                </td>
                                                @foreach ($planners as $planner)
                                                    <td>
                                                        <a href="javascript:void(0)" class="open-monthly-planner-modal"
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
                                                            <div class="shiftBox">
                                                                <strong>{{ $planner['subject'] }}</strong>

                                                            </div>
                                                        </a>
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            @else
                <div class="">
                    <h2 class="fs-6 fw-semibold mb-3">Planner</h2>
                    <p class="text-secondary fw-medium">Not Found</p>
                </div>
            @endif
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
                $('#monthlyPlannerChapterModal').modal('show');

            } catch (error) {
                console.error('Error parsing JSON:', error);
            }
        });
        // for Month Filter
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('monthFilter').addEventListener('change', function() {
                let selectedMonth = this.value;

                fetch(`/planner/filter?month=${selectedMonth}`, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        updatePlannerTable(data);
                    })
                    .catch(error => console.error('Error fetching planner:', error));
            });
        });

        function updatePlannerTable(data) {
            let tbody = document.querySelector('.table tbody');
            tbody.innerHTML = ''; // Clear old data

            if (Object.keys(data).length === 0) {
                tbody.innerHTML = `<tr><td colspan="6" class="text-center">No planners found for this month</td></tr>`;
                return;
            }

            Object.keys(data).forEach(classId => {
                let classRow = `<tr><td class="fw-semibold">${data[classId].class_name ?? 'No Class'}</td>`;

                data[classId].planners.forEach(planner => {
                    let chapterTitlesArray = Object.values(planner
                        .titles); // Convert object to indexed array
                    let plannerData = JSON.stringify({
                        ids: planner.chapter_id,
                        titles: chapterTitlesArray,
                        course_slug: planner.course_slug || 'default-slug', // Fallback if missing
                        course_id: planner.course_id || '0' // Fallback if missing
                    });
                    classRow += `
                <td>
                    <a href="javascript:void(0)" class="open-monthly-planner-modal" data-chapters='${plannerData}'>
                        <div class="shiftBox">
                            <strong>${planner.subject}</strong>
                        </div>
                    </a>
                </td>`;
                });

                classRow += `</tr>`;
                tbody.innerHTML += classRow;
            });
        }
    </script>
@endsection
