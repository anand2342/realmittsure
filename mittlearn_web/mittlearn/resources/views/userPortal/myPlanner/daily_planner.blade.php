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
                            <button type="button" class="filterbutton active" data-scroll-target="day-1">Stage
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
                                    $formattedDate = $date->format('Y-m-d'); // Format current loop date
                                    $isToday = $formattedDate === now()->format('Y-m-d'); // Check if it's today

                                @endphp

                                <th class="day-header @if ($isToday) currentBg  @endif" data-date="{{ $formattedDate }}">
                                    <div class="d-flex justify-content-between">
                                        <span>Day {{ $index + 1 }} <b>{{ $dayName }}</span>
                                        @if ($isToday)
                                            <strong class="todayBadge">Today</strong>
                                        @endif
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
                                                @if (!empty($chapter['chapter_id']))
                                                    <a href="{{ route('up.planner.chapter.listing', ['slug' => $chapter['course_slug'] ?? 'abc', 'id' => $chapter['course_id'] ?? '0']) }}"
                                                        title="{{ $chapter['title'] }}">
                                                        <div class="shiftBox {{ $chapter['class'] }}">
                                                            <strong>{{ Str::limit($chapter['title'], 20, '...') }}</strong>
                                                        </div>
                                                    </a>
                                                @else
                                                    <div class="shiftBox lightred">
                                                        <strong>No Task</strong>
                                                    </div>
                                                @endif
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dayHeaders = document.querySelectorAll('.day-header');
        const stageButtons = document.querySelectorAll('.filterbutton');
        const currentDate = new Date();
        const todayFormatted = currentDate.toISOString().split('T')[0]; // Format: YYYY-MM-DD

        // Get total planner days from Blade
        const totalPlannerDays = {{ $totalPlannerDays }};
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
                button: stageButtons[i] // Store reference to the stage button
            });
            startDay = endDay + 1;
        }

        // Assign IDs to headers (day-1, day-2, day-3, ...)
        dayHeaders.forEach((header, index) => {
            header.id = `day-${index + 1}`;
        });

        // Find today's column and determine its stage
        let todayColumn = null;
        let todayStageIndex = null;

        dayHeaders.forEach((header, index) => {
            const dateString = header.getAttribute('data-date'); // Ensure Blade sets this correctly
            if (dateString === todayFormatted) {
                todayColumn = header;

                // Determine the stage in which today falls
                const todayDayNumber = index + 1; // Since day-1 corresponds to index 0
                stages.forEach((stage, stageIndex) => {
                    if (todayDayNumber >= stage.startDay && todayDayNumber <= stage.endDay) {
                        todayStageIndex = stageIndex;
                    }
                });
            }
        });

        // Scroll to today's column smoothly
        if (todayColumn) {
            setTimeout(() => {
                todayColumn.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest',
                    inline: 'center'
                });
            }, 300);
        }

        // If today falls within a stage, activate that stage button
        if (todayStageIndex !== null) {
            stageButtons.forEach(btn => btn.classList.remove('active')); // Remove active from all
            stages[todayStageIndex].button.classList.add('active'); // Activate today's stage
        }

        // Handle stage button clicks
        stageButtons.forEach((button, index) => {
            button.addEventListener('click', function() {
                stageButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');

                // Scroll to the first day of the selected stage
                const targetDay = stages[index].startDay;
                const targetHeader = document.getElementById(`day-${targetDay}`);
                if (targetHeader) {
                    targetHeader.scrollIntoView({
                        behavior: 'smooth',
                        block: 'nearest',
                        inline: 'start'
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
                stages[currentStage].button.classList.add('active');
            }
        });
    });
</script>
