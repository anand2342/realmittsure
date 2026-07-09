@extends('admin.layouts.master')

@section('content')

    @php
        $flag = 0;
        $heading = 'Add';

        if (isset($content) && !empty($content)) {
            $flag = 1;
            $heading = 'Update';
        }
    @endphp

    <div>
        <div class="pagetitle">
            <h1>{{ $heading }} Teacher Development Content</h1>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">

                            {{-- FORM START --}}
                            @if ($flag == 1)
                                {{ Form::model($content, ['route' => ['teacher.development.update', $content->id], 'method' => 'PUT', 'class' => 'row g-3', 'files' => true]) }}
                            @else
                                {{ Form::open(['route' => 'teacher.development.store', 'class' => 'row g-3', 'files' => true]) }}
                            @endif

                            {{-- BASIC INFO --}}
                            <h5 class="card-title pb-0">Basic Info</h5>
                            <hr class="form-divider">
                            <div class="col-md-6">
                                {!! Form::label('type', 'Type', ['class' => 'form-label required']) !!}
                                {!! Form::select('type', config('constants.TDC_TYPE'), $content->type ?? 1, [
                                    'class' => 'form-control form-select',
                                    'required',
                                ]) !!}
                            </div>

                            <div class="col-md-6">
                                {!! Form::label('title', 'Title', ['class' => 'form-label required']) !!}
                                {!! Form::text('title', null, ['class' => 'form-control', 'required']) !!}
                            </div>

                            {{-- <div class="col-md-6">
                                {!! Form::label('is_active', 'Status', ['class' => 'form-label required']) !!}
                                {!! Form::select('is_active', config('constants.STATUS_LIST'), $content->is_active ?? 1, [
                                    'class' => 'form-control form-select',
                                    'required',
                                ]) !!}
                            </div> --}}

                            {{-- VIDEOS --}}
                            <div id="video-wrapper" class="col-md-12">
                                <h5 class="card-title mt-3">Videos</h5>

                                @if ($flag == 1)
                                    {{-- EDIT MODE: render existing videos with hidden IDs --}}
                                    @foreach ($content->videos as $i => $video)
                                        <div class="video-row border p-3 mb-3 row position-relative">

                                            {{-- Hidden: existing video record ID --}}
                                            <input type="hidden" name="videos[{{ $i }}][video_id]"
                                                value="{{ $video->id }}">

                                            {{-- Hidden: existing file path (used if no new file uploaded) --}}
                                            <input type="hidden" name="videos[{{ $i }}][existing_file]"
                                                value="{{ $video->video_file }}">
                                            <div class="col-md-2">
                                                <label class="form-label">Order</label>
                                                <input type="number" name="videos[{{ $i }}][order]"
                                                    class="form-control" value="{{ $video->order ?? $i + 1 }}"
                                                    min="1" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label required">Video Title</label>
                                                <input type="text" name="videos[{{ $i }}][title]"
                                                    class="form-control" value="{{ $video->video_title }}" required>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">
                                                    Upload Video
                                                    @if ($video->video_file)
                                                        <small class="text-muted">(leave empty to keep existing)</small>
                                                    @endif
                                                </label>
                                                <input type="file" name="videos[{{ $i }}][file]"
                                                    class="form-control" accept="video/mp4,video/x-m4v,video/*">

                                                @if ($video->video_file)
                                                    <small>
                                                        <a href="{{ Storage::url($video->video_file) }}" target="_blank">
                                                            ▶ View Existing File
                                                        </a>
                                                    </small>
                                                @endif
                                            </div>

                                            @if ($i > 0)
                                                <div class="col-md-12 text-end mt-2">
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger removeVideo">Remove</button>
                                                </div>
                                            @endif

                                        </div>
                                    @endforeach
                                @else
                                    {{-- CREATE MODE: one empty row --}}
                                    <div class="video-row border p-3 mb-3 row">
                                        <div class="col-md-2">
                                            <label class="form-label">Order</label>
                                            <input type="number" name="videos[0][order]" class="form-control"
                                                value="1" min="1" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label required">Video Title</label>
                                            <input type="text" name="videos[0][title]" class="form-control" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Upload Video</label>
                                            <input type="file" name="videos[0][file]" class="form-control"
                                                accept="video/mp4,video/x-m4v,video/*">
                                        </div>
                                    </div>
                                @endif

                            </div>

                            <div class="col-md-12 text-end">
                                <button type="button" class="btn btn-secondary" id="addVideoBtn">+ Add More Video</button>
                            </div>

                            {{-- ACCESS CONTROL --}}
                            <h5 class="card-title mt-3">School Access</h5>
                            <hr class="form-divider">

                            <div class="col-md-12 mb-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_for_all" name="is_for_all"
                                        value="1"
                                        {{ isset($content) && $content->is_for_all_schools ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_for_all">
                                        Available for All Schools
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-12" id="schoolBox">
                                <label class="form-label">Assign to Individual Schools</label>
                                <select name="school_ids[]" id="school_ids" class="form-select js-select2" multiple
                                    style="width: 100%;">
                                    @foreach ($schools ?? [] as $schoolId => $name)
                                        <option value="{{ $schoolId }}"
                                            {{ isset($content) && $content->schools->pluck('id')->contains($schoolId) ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- SUBMIT --}}
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="{{ route('teacher.development.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>

                            {{ Form::close() }}

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        $(document).ready(function() {

            // Select2 init
            $(".js-select2").select2({
                closeOnSelect: false,
                placeholder: "Select Schools",
                allowClear: true,
            });

            // Toggle school box on load and on change
            function toggleSchoolBox() {
                if ($('#is_for_all').is(':checked')) {
                    $('#schoolBox').hide();
                    $('#school_ids').val(null).trigger('change');
                } else {
                    $('#schoolBox').show();
                }
            }

            toggleSchoolBox();
            $('#is_for_all').on('change', toggleSchoolBox);
        });

        // -----------------------------------------------
        // VIDEO INDEX: use a counter that always increases
        // so indices never collide even after removes
        // -----------------------------------------------
        let videoIndex = {{ isset($content) ? count($content->videos) : 1 }};

        document.getElementById('addVideoBtn').addEventListener('click', function() {
            const html = `
                <div class="video-row border p-3 mb-3 row">
                    <div class="col-md-2">
                        <label class="form-label">Order</label>
                        <input type="number" name="videos[${videoIndex}][order]" 
                            class="form-control" value="${videoIndex + 1}" min="1" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required">Video Title</label>
                        <input type="text" name="videos[${videoIndex}][title]" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Upload Video</label>
                        <input type="file" name="videos[${videoIndex}][file]" class="form-control"
                            accept="video/mp4,video/x-m4v,video/*">
                    </div>
                  
                    <div class="col-md-12 text-end mt-2">
                        <button type="button" class="btn btn-sm btn-danger removeVideo">Remove</button>
                    </div>
                </div>
            `;
            document.getElementById('video-wrapper').insertAdjacentHTML('beforeend', html);
            videoIndex++;
        });

        // Remove video row
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('removeVideo')) {
                e.target.closest('.video-row').remove();
            }
        });
    </script>

@endsection
