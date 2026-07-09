<div class="">
    <h2 class="fs-6 fw-normal mb-3"><b class="fw-semibold">Continue </b> Watching</h2>

    <ul class="watchingUl">
        @if ($conWatching['courses']->isNotEmpty())
            @foreach ($conWatching['courses'] as $index => $course)
                @if ($course->course)
                    @php
                        $userProgress = App\Models\TrackUserVideoProgress::where('user_id', Auth::id())
                            ->where('course_id', $course->course_id)
                            ->get();
                        $videoDuration = $userProgress->sum('video_duration');
                        $watchedDuration = $userProgress->sum('watched_duration');
                        $percentage = $videoDuration > 0 ? ($watchedDuration / $videoDuration) * 100 : 0;
                        $uniqueChapters = App\Models\CourseChapter::where('course_id', $course->course_id)->count();
                        $coursePerChap = App\Models\TrackUserVideoProgress::where('user_id', Auth::id())
                            ->where('course_id', $course->course_id)
                            ->whereColumn('watched_duration', '=', 'video_duration')
                            ->select('chapter_id')
                            ->distinct()
                            ->get();
                        $count = 0;
                        foreach ($coursePerChap as $one) {
                            $count++;
                        }
                    @endphp
                    <li>
                        <div class="d-flex align-items-center gap-2">
                            <figure class="m-0">
                                <img src="{{ asset('mittbunny/images/shapes-img' . (($index % 5) + 1) . '.svg') }}"
                                    alt="">
                            </figure>
                            <strong>
                                {{ $course['course']['course_name'] }}
                                <b>{{ $count }}/{{ $uniqueChapters }} Lessons</b>
                            </strong>
                        </div>
                        <span>{{ round($percentage, 2) }}%</span>
                    </li>
                @endif
            @endforeach
        @else
            <p class="fw-medium">Nothing to continue. Explore more!</p>
        @endif
    </ul>
    <div class="text-center bottomImg">
        <img src="{{ asset('mittbunny/images/right-panel.svg') }}" alt="" width="140">
    </div>
</div>
