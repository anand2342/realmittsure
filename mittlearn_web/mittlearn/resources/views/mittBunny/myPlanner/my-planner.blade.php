@extends('schoolPortal.layouts.master')
@section('content')
    <div class="dashboardMain">
        <div class="cardBox dailyPlanner">
            @if ($classes->unique('class_id')->isNotEmpty())
                <div class="">
                    <h2 class="fs-6 fw-semibold mb-3">Daily Planner</h2>
                    <p class="text-secondary fw-medium">Select Class</p>
                </div>
                <ul class="nav nav-tabs classTabs" id="classTabs">
                    @foreach ($classes->unique('class_id') as $item)
                        <li class="nav-item">
                            <button class="nav-link {{ $loop->first ? 'active' : '' }}" data-bs-toggle="tab"
                                data-bs-target="#classTab{{ $item->class_id }}" type="button">
                                <span>{{ substr($item->class->name ?? 'N/A', 0, 1) }}</span>{{ $item->class->name }}
                            </button>
                        </li>
                    @endforeach
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="classTab">
                        <div>
                            <div class="dailypHeader d-md-flex">
                                <div class="d-flex flex-column">
                                    <span>Select Stage</span>
                                </div>
                                <ul class="filterButtonUl">
                                    <ul class="filterButtonUl">
                                        <li>
                                            <button type="button" class="filterbutton active"
                                                data-scroll-target="day-1">Stage
                                                1</button>
                                        </li>
                                        <li>
                                            <button type="button" class="filterbutton" data-scroll-target="day-6">Stage
                                                2</button>
                                        </li>
                                        <li>
                                            <button type="button" class="filterbutton" data-scroll-target="day-11">Stage
                                                3</button>
                                        </li>
                                        <li>
                                            <button type="button" class="filterbutton" data-scroll-target="day-16">Stage
                                                4</button>
                                        </li>
                                    </ul>
                            </div>
                            <div class="table-responsive tbleDiv plannerTblFix">
                                <table class="table table-bordered ">
                                    <thead>
                                        <tr>
                                            <th class="text-start" style="vertical-align: middle;">Subjects</th>
                                            @foreach ($allDates as $index => $date)
                                                @php
                                                    $dayName = $weekDays[$date->format('w')]; // Get weekday name
                                                @endphp
                                                <th class="day-header">
                                                    <div class="d-flex justify-content-between">
                                                        <span>Day {{ $index + 1 }}
                                                            <b>{{ $dayName }}</b>
                                                        </span>
                                                        <button type="button" class="btnremoveBg" data-bs-toggle="modal"
                                                            data-bs-target="#statusMdl" data-school-id="{{ $schoolId }}"
                                                            data-day-index="{{ $index + 1 }}"
                                                            data-date="{{ $date->format('Y-m-d') }}">
                                                            <img src="{{ asset('frontend/images/sorting-icon.svg') }}"
                                                                alt="" width="25">
                                                        </button>
                                                    </div>
                                                </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($subjects as $subject)
                                            <tr>
                                                <td class="text-start fw-semibold">{{ $subject->subject->name }}</td>
                                                @foreach ($allDates as $index => $date)
                                                    @php
                                                        $day = $index + 1; // Adjust day number for dayWiseData
                                                    @endphp
                                                    <td>
                                                        @if (isset($dayWiseData[$day][$subject->subject->id]))
                                                            @foreach ($dayWiseData[$day][$subject->subject->id] as $chapter)
                                                                <a href="{{ route('chapter.details', $chapter['chapter_id']) }}"
                                                                    title="{{ $chapter['title'] }}">
                                                                    <div class="shiftBox {{ $chapter['class'] }}">
                                                                        <strong>{{ Str::limit($chapter['title'], 20, '...') }}</strong>
                                                                    </div>
                                                                </a>
                                                            @endforeach
                                                        @else
                                                            <a href="javascript:void(0)">
                                                                <div class="shiftBox lightred">
                                                                    <strong>No Task</strong>
                                                                </div>
                                                            </a>
                                                        @endif
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
                    <h2 class="fs-6 fw-semibold mb-3">Daily Planner</h2>
                    <p class="text-secondary fw-medium">Not Found</p>
                </div>
            @endif
        </div>
    </div>

    <div class="offcanvas offcanvas-end " id="editPlanner">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title fs-6 fw-semibold">Edit Planner</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body ">
            <div class="formPanel">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label>Allotted Days</label>
                            <input type="text" class="form-control" placeholder="2">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label>Start Date</label>
                            <input type="text" class="form-control" id="datepicker" placeholder="02/10/2023">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label>Completion Date</label>
                            <input type="text" class="form-control" id="datepicker1" placeholder="04/10/2023">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label>Total Periods</label>
                            <input type="text" class="form-control" placeholder="2">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="offcanvas-footer">
            <div class="d-flex align-items-center justify-content-end gap-4">
                <button type="button" class="btn backbtn">Back</button>
                <button type="button" class="btn btn-primary-gradient rounded-1">Submit</button>
            </div>
        </div>
    </div>
    <div class="modal fade" id="statusMdl">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="text-center">
                        <lottie-player src="{{ asset('frontend/images/study-idea.json') }}" loop=""
                            autoplay="" style="width: 130px;height: 130px;margin: auto;"
                            background="transparent"></lottie-player>
                        <h6 class="fw-semibold">Are you sure?</h6>
                        <p>Do you want to assign off on the <br> selected date</p>
                        <input type="hidden" id="modal-school-id">
                        <input type="hidden" id="modal-day-index">
                        <input type="hidden" id="modal-date">
                        <button type="button" class="btn btn-primary-gradient rounded-1"
                            id="confirmHolidayBtn">Yes</button>
                        <div>
                            <button type="button" class="btn btnNo" data-bs-dismiss="modal">No</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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

        document.addEventListener('DOMContentLoaded', function() {
            const dayHeaders = document.querySelectorAll('.day-header');
            const stageButtons = document.querySelectorAll('.filterbutton');
            const currentDate = new Date();

            // Get the totalPlannerDays value from the server (pass this from Blade to JS)
            const totalPlannerDays =
            {{ $totalPlannerDays }}; // Assuming you are passing totalPlannerDays to JavaScript from the backend

            // Calculate number of days per stage
            const daysPerStage = Math.ceil(totalPlannerDays / 4);

            // Dynamically create the stages based on totalPlannerDays
            const stages = [];
            let startDay = 1;
            for (let i = 0; i < 4; i++) {
                const endDay = startDay + daysPerStage - 1;
                stages.push({
                    id: `stage-${i + 1}`,
                    startDay: startDay,
                    endDay: endDay > totalPlannerDays ? totalPlannerDays : endDay,
                });
                startDay = endDay + 1;
            }

            // Assign IDs to headers (day-1, day-2, day-3, ...)
            dayHeaders.forEach((header, index) => {
                header.id = `day-${index + 1}`;
            });

            // Determine the current stage based on the current date
            let currentStageIndex = null;
            const currentDay = currentDate.getDate();
            stages.forEach((stage, index) => {
                if (currentDay >= stage.startDay && currentDay <= stage.endDay) {
                    currentStageIndex = index;
                }
            });

            // If a current stage is found, activate it and scroll to its header
            if (currentStageIndex !== null) {
                const currentStage = stages[currentStageIndex];
                const targetHeader = document.getElementById(`day-${currentStage.startDay}`);
                stageButtons.forEach(btn => btn.classList.remove('active'));
                stageButtons[currentStageIndex].classList.add('active');
                if (targetHeader) {
                    targetHeader.scrollIntoView({
                        behavior: 'smooth',
                        block: 'nearest',
                        inline: 'start',
                    });
                }
            }

            // Handle stage button clicks
            stageButtons.forEach((button, index) => {
                button.addEventListener('click', function() {
                    stageButtons.forEach(btn => btn.classList.remove('active'));

                    // Add active class to clicked button
                    this.classList.add('active');

                    // Scroll to the corresponding day
                    const targetDay = stages[index].startDay;
                    const targetHeader = document.getElementById(`day-${targetDay}`);
                    if (targetHeader) {
                        targetHeader.scrollIntoView({
                            behavior: 'smooth',
                            block: 'nearest',
                            inline: 'start',
                        });
                    }
                });
            });

            // Highlight the active stage dynamically based on scroll position
            window.addEventListener('scroll', function() {
                let currentStage = null;

                // Determine the active stage based on visible header
                for (let i = stages.length - 1; i >= 0; i--) {
                    const stage = stages[i];
                    const header = document.getElementById(`day-${stage.startDay}`);
                    if (header && header.getBoundingClientRect().left < window.innerWidth / 2) {
                        currentStage = i;
                        break;
                    }
                }

                // Update active button
                if (currentStage !== null) {
                    stageButtons.forEach(btn => btn.classList.remove('active'));
                    stageButtons[currentStage].classList.add('active');
                }
            });
        });
    </script>
@endsection
