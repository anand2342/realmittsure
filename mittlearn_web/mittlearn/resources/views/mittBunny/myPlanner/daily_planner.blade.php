<h3 class="fs-8 text-secondary">Select Stage</h3>
<ul class="filterButtonUl">
    <ul class="filterButtonUl">
        @php
            $stages = [];
            $daysPerStage = ceil($totalPlannerDays / 4);
            $startDay = 1;
            for ($i = 0; $i < 4; $i++) {
                $endDay = min($startDay + $daysPerStage - 1, $totalPlannerDays);
                $stages[] = ['startDay' => $startDay, 'endDay' => $endDay];
                $startDay = $endDay + 1;
            }
        @endphp

        @foreach ($stages as $index => $stage)
            <li>
                <button type="button" class="filterbutton {{ $index === 0 ? 'active' : '' }}"
                    data-scroll-target="day-{{ $stage['startDay'] }}">
                    Stage {{ $index + 1 }}
                </button>
            </li>
        @endforeach
    </ul>

</ul>
<div class="plannerSection mt-4">
    <ul class="planList">
        @foreach ($allDates as $index => $date)
            @php
                $day = $index + 1;
                $dayNumber = $index + 1;
                $formattedDate = $date->format('Y-m-d');
                $dayName = $weekDays[$date->format('w')]; // Get weekday name
                $isToday = $formattedDate === now()->format('Y-m-d'); // Check if it's today
            @endphp

            <li id="day-{{ $dayNumber }}">
                @php
                    // Get first available subject for this day
                    $subject = $subjects->first();
                    $chapter = isset($dayWiseData[$day][$subjectId][0]) ? $dayWiseData[$day][$subjectId][0] : null;
                @endphp

                @if ($chapter && isset($chapter['course_slug']) && isset($chapter['course_id']))
                    <a href="{{ route('mittbunny.planner.detail', ['slug' => $chapter['course_slug'], 'id' => $chapter['course_id']]) }}"
                        class="planCard day-header {{ $isToday ? 'currentDay' : '' }}">
                        <figure>
                            <img src="{{ asset('mittbunny/images/planimg' . (($index % 3) + 1) . '.jpg') }}"
                                alt="">
                        </figure>
                        <strong>{{ $dayName }}</strong> {{ Str::limit($chapter['title'], 20, '...') }}
                    </a>
                @else
                    <a href="javascript:void(0)" class="planCard disabled">
                        <figure>
                            <img src="{{ asset('mittbunny/images/planimg' . (($index % 3) + 1) . '.jpg') }}"
                                alt="">
                        </figure>
                        <strong>{{ $dayName }}</strong> No Task
                    </a>
                @endif
                <span class="planList-span">Day {{ $day }}</span>
            </li>
        @endforeach
    </ul>

    {{-- <ul class="planList">
        @foreach ($allDates as $index => $date)
            @php
                $day = $index + 1;
                $dayNumber = $index + 1;
                $formattedDate = $date->format('Y-m-d');
                $dayName = $weekDays[$date->format('w')]; // Get weekday name
                $isToday = $formattedDate === now()->format('Y-m-d'); // Check if it's today
            @endphp

            <li id="day-{{ $dayNumber }}">
                @php
                    // Get first available subject for this day
                    $subject = $subjects->first();
                @endphp

                @if ($subject && isset($dayWiseData[$day][$subjectId]))
                    @php
                        $chapter = $dayWiseData[$day][$subjectId][0]; // Get first chapter data
                        dd($chapter);
                    @endphp
                    <a href="{{ route('mittbunny.courses.chapter.listing', ['slug' => $chapter['course_slug'], 'id' => $chapter['course_id']]) }}"
                        class="planCard day-header {{ $isToday ? 'currentDay' : '' }}">
                        <figure><img src="{{ asset('mittbunny/images/planimg' . (($index % 3) + 1) . '.jpg') }}"
                                alt=""></figure>
                        <strong>{{ $dayName }}</strong> {{ Str::limit($chapter['title'], 20, '...') }}
                    </a>
                @else
                    <a href="javascript:void(0)" class="planCard disabled">
                        <figure><img src="{{ asset('mittbunny/images/planimg' . (($index % 3) + 1) . '.jpg') }}"
                                alt=""></figure>
                        <strong>{{ $dayName }}</strong> No Task
                    </a>
                @endif
                <span class="planList-span">Day {{ $day }}</span>
            </li>
        @endforeach
    </ul> --}}

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const planItems = document.querySelectorAll('.planList li');
        const stageButtons = document.querySelectorAll('.filterbutton');
        const currentDate = new Date();
        const todayFormatted = currentDate.toISOString().split('T')[0];

        // Get total planner days dynamically
        const totalPlannerDays = planItems.length;
        const daysPerStage = Math.ceil(totalPlannerDays / 4);

        // Create stages dynamically
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

        // Assign IDs to items (day-1, day-2, etc.)
        planItems.forEach((item, index) => {
            item.id = `day-${index + 1}`;
        });

        // Find today's item and its stage
        let todayItem = null;
        let todayStageIndex = null;

        planItems.forEach((item, index) => {
            const dateString = item.querySelector('.planCard')?.getAttribute('data-date');
            if (dateString === todayFormatted) {
                todayItem = item;
                const todayDayNumber = index + 1;
                stages.forEach((stage, stageIndex) => {
                    if (todayDayNumber >= stage.startDay && todayDayNumber <= stage.endDay) {
                        todayStageIndex = stageIndex;
                    }
                });
            }
        });

        // Scroll to today's item smoothly
        if (todayItem) {
            setTimeout(() => {
                todayItem.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest',
                    inline: 'center'
                });
            }, 300);
        }

        // If today falls within a stage, activate that stage button
        if (todayStageIndex !== null) {
            stageButtons.forEach(btn => btn.classList.remove('active'));
            stages[todayStageIndex].button.classList.add('active');
        }

        // Handle stage button clicks
        stageButtons.forEach((button, index) => {
            button.addEventListener('click', function() {
                stageButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');

                // Scroll to the first day of the selected stage
                const targetDay = stages[index].startDay;
                const targetItem = document.getElementById(`day-${targetDay}`);
                if (targetItem) {
                    targetItem.scrollIntoView({
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

            // Determine the active stage based on visible item
            for (let i = stages.length - 1; i >= 0; i--) {
                const stage = stages[i];
                const item = document.getElementById(`day-${stage.startDay}`);
                if (item && item.getBoundingClientRect().left < window.innerWidth / 2) {
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
