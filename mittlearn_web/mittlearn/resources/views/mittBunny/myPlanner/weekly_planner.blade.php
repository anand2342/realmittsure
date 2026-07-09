<h3 class="fs-8 text-secondary">Select Week</h3>
<ul class="filterButtonUl">
    @foreach ($weeks as $weekNumber => $week)
        <li>
            <button type="button" class="filterbutton {{ now()->format('W') == $weekNumber ? 'active' : '' }}">Week
                {{ $weekNumber }}</button>
        </li>
    @endforeach
</ul>
<div class="plannerSection mt-4">
    <ul class="planList">
        @foreach ($weeks as $weekNumber => $week)
            @php
                $isCurrentWeek = now()->format('W') == $weekNumber;
                $weekData = $weekWiseData[$weekNumber] ?? [];
                $firstSubject = reset($weekData);
                $firstCourseSlug = $firstSubject ? $firstSubject[0]['course_slug'] : '#';
                $firstChapterTitles = $firstSubject ? implode(', ', $firstSubject[0]['titles']) : 'No Task';
                $chapterTitles = $firstSubject ? $firstSubject[0]['titles'] : [];
                $chapterIds = $firstSubject ? $firstSubject[0]['chapter_id'] : []; // Get chapter IDs
                $isDisabled = empty($weekData) ? 'disabled' : '';
                $firstCourseId = $firstSubject ? $firstSubject[0]['course_id'] : null;
                $extraCount = count($chapterTitles) - 1;
                $extraText = $extraCount > 0 ? ' +' . $extraCount . ' more chapters' : '';

            @endphp
            <li>
                <a href="javascript:void(0)"
                    class="planCard {{ $isDisabled }} {{ $isCurrentWeek ? 'currentDay' : '' }} open-weekly-planner-modal"
                    data-week-number="{{ $weekNumber }}" data-week-name="Week {{ $weekNumber }}"
                    data-chapters="{{ htmlspecialchars(
                        json_encode([
                            'ids' => $chapterIds,
                            'titles' => explode(',', $firstChapterTitles),
                            'course_slug' => $firstCourseSlug,
                            'course_id' => $firstCourseId,
                        ]),
                        ENT_QUOTES,
                        'UTF-8',
                    ) }}">
                    <figure>
                        <img src="{{ asset('mittbunny/images/planimg' . (($weekNumber % 3) + 1) . '.jpg') }}"
                            alt="Week {{ $weekNumber }}">
                    </figure>
                    <strong>Week {{ $weekNumber }}</strong> {{ Str::limit($firstChapterTitles, 30, '...') }}
                </a>
                <span class="planList-span">Week {{ $weekNumber }}</span>
            </li>
        @endforeach
    </ul>
</div>
