<div class="col-lg-12 col-xl-4 mb-3">
    <div class="cardBox watchingCon h-100">
        <h2 class="fs-6 fw-semibold mb-3">Continue Watching</h2>
        <div class="WatchMain">
            <div class="row px-md-1">
                <div class="col-lg-12 col-xl px-md-2 mb-1">
                    @if ($conWatching['courses']->isNotEmpty())
                        @foreach ($conWatching['courses'] as $index => $course)
                            @if (!empty($courses) && $course->course)
                                @php
                                    $Image1 = $course->course->metadataValues
                                        ->where('field_name', 'thumbnail_image')
                                        ->first();
                                    $Image2 = $course->course->metadataValues
                                        ->where('field_name', 'banner_image')
                                        ->first();
                                    $Image3 = $course->course->metadataValues
                                        ->where('field_name', 'book_cover_image')
                                        ->first();
                                    $userProgress = App\Models\TrackUserVideoProgress::where('user_id', Auth::id())
                                        ->where('course_id', $course->course_id)
                                        ->get();
                                    $videoDuration = $userProgress->sum('video_duration');
                                    $watchedDuration = $userProgress->sum('watched_duration');
                                    $percentage = $videoDuration > 0 ? ($watchedDuration / $videoDuration) * 100 : 0;
                                    $uniqueChapters = App\Models\CourseChapter::where(
                                        'course_id',
                                        $course->course_id,
                                    )->count();
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
                                <div class="watchingBox mb-2">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center">
                                            <figure class="m-0">
                                                <img src="{{ $Image1 ? Storage::url($Image1->field_value) : ($Image2 ? Storage::url($Image2->field_value) : ($Image3 ? Storage::url($Image3->field_value) : asset('frontend/images/default-image.jpg'))) }}"
                                                    alt="">
                                            </figure>
                                            <div class="coursesName">
                                                <h3>{{ $course['course']['course_name'] }}</h3>
                                                <p>{{ $count }}/{{ $uniqueChapters }} Lessons</p>
                                            </div>
                                        </div>
                                        <div class="position-relative">
                                            <div id="watchingTrend{{ $index }}"
                                                style="width: 80px; height: 80px;"></div>
                                            <strong class="watchingTxt">{{ round($percentage, 2) }}%</strong>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    @else
                        <tr>
                            <td class="text-center">Nothing to continue. Explore more!</td>
                        </tr>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
