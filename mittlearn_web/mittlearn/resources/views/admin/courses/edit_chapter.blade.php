@extends('admin.layouts.master')
@section('content')
    <div id="page-header" class="page-header">
        <section class="section">
            <div class="pagetitle">
                <div class="pagetitle">
                    <h1>Edit Course Chapter</h1>
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">Home</li>
                            <li class="breadcrumb-item active">Courses</li>
                            <li class="breadcrumb-item active">{{ $courseName }}</li>
                        </ol>
                    </nav>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="card-title">Edit Chapter Content</div>
                                </div>
                                {{-- <div class="col-sm-6 text-end mt-3">
                                    <button type="button" class="btn btn-success" data-bs-toggle="tooltip"
                                        data-bs-placement="bottom" data-bs-title="Create Folder">
                                        <span data-bs-target="#createFolder" data-bs-toggle="modal">Create New Folder</span>
                                    </button>
                                </div> --}}
                            </div>
                            <hr class="form-divider">

                            {{ Form::model($chapter ?? null, [
                                'url' => route('course.chapter.update', $chapter->id),
                                'method' => 'PUT',
                                'id' => 'edit-chapter-form',
                                'class' => 'row g-3',
                                'files' => true,
                            ]) }}

                            <div class="row">

                                <div class="col-md-6 col-sm-6 col-xs-12 input-div">
                                    {!! Form::label('chapter_title', 'Chapter Title', ['class' => 'form-label']) !!}
                                    {{ Form::text('chapter_title', $chapter->chapter_name ?? ' ', [
                                        'class' => 'form-control',
                                        'autocomplete' => 'off',
                                        'placeholder' => 'Chapter Title',
                                    ]) }}
                                </div>


                                <div class="col-md-6 col-sm-6 col-xs-12 input-div">
                                    {!! Form::label('chapter_description', 'Chapter Description', ['class' => 'form-label']) !!}
                                    {{ Form::text('chapter_description', $chapter->chapter_description ?? ' ', [
                                        'class' => 'form-control',
                                        'autocomplete' => 'off',
                                        'placeholder' => 'Chapter Description',
                                    ]) }}
                                </div>

                                <div class="col-md-6 col-sm-6 col-xs-12 input-div">
                                    <div class="">
                                        {!! Form::label('topic_covered', 'Topic Covered', ['class' => 'form-label ']) !!}
                                        {{ Form::text('topic_covered', $chapter->topic_covered ?? ' ', [
                                            'class' => 'form-control',
                                            'autocomplete' => 'off',
                                            'required',
                                            'placeholder' => 'Topic Covered',
                                        ]) }}
                                    </div>
                                </div>


                                <div class="col-md-6 col-sm-6 col-xs-12 input-div">
                                    {!! Form::label('sort_order', 'Chapter Sort Order', ['class' => 'form-label']) !!}
                                    {{ Form::number('sort_order', $chapter->sort_order ?? ' ', [
                                        'class' => 'form-control',
                                        'autocomplete' => 'off',
                                        'placeholder' => 'Sort Order',
                                    ]) }}
                                </div>

                                <div class="col-md-6 col-sm-6 col-xs-12 input-div">
                                    <div class="">
                                        {!! Form::label('content_creation_date', 'Content Creation Date', ['class' => 'form-label ']) !!}
                                        {{ Form::date('content_creation_date', $chapter->content_creation_date ?? ' ', [
                                            'class' => 'form-control',
                                            'autocomplete' => 'off',
                                            'required',
                                        ]) }}
                                    </div>
                                </div>


                                {{-- <div class="col-md-6 col-sm-6 col-xs-12 input-div">
                                    {!! Form::label('supporting_folder_id', 'Select Supporting Folder', ['class' => 'form-label']) !!}
                                    {!! Form::select('supporting_folder_id', $folder_list, $chapter->supporting_folder_id, [
                                        'class' => 'form-control form-select fs-8',
                                        'autocomplete' => 'off',
                                        'placeholder' => 'Select',
                                    ]) !!}
                                </div> --}}

                                <div class="col-md-6 col-sm-6 col-xs-12 input-div">
                                    {!! Form::label('teaching_manuals', 'Teaching Manual', ['class' => 'form-label']) !!}
                                    {!! Form::file('teaching_manuals', ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12 input-div">
                                    {!! Form::label('question_bank', 'Question Bank', ['class' => 'form-label']) !!}
                                    {!! Form::file('question_bank', ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12 input-div">
                                    {!! Form::label('lesson_planner', 'Lesson Planner', ['class' => 'form-label']) !!}
                                    {!! Form::file('lesson_planner', ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 input-div">
                                    <div class="row">
                                        <div class="col-md-1">
                                            {!! Form::label('language[0]', 'Language', ['class' => 'form-label']) !!}
                                            {!! Form::select('language[0]', config('constants.CONTENT_LANGUAGE'), null, [
                                                'class' => 'form-select',
                                                'placeholder' => '--Select Content Language--',
                                            ]) !!}
                                        </div>
                                        <!-- NEW: Sort Order Column (col-md-1) -->
                                        <div class="col-md-1">
                                            {!! Form::label('video_sort_order', 'Order', ['class' => 'form-label']) !!}
                                            <input type="number" name="video_sort_order[0]" class="form-control"
                                                placeholder="" min="1" />
                                        </div>
                                        <div class="col-md-2">
                                            {!! Form::label('video_view_type[0]', 'Video Type', ['class' => 'form-label']) !!}
                                            {!! Form::select('video_view_type[0]', config('constants.VIDEO_VIEW_TYPE'), null, [
                                                'class' => 'form-select',
                                                'placeholder' => '--Select Video Type--',
                                            ]) !!}
                                        </div>
                                        <!-- Updated: File Name Column (col-md-5 instead of col-md-6) -->
                                        <div class="col-md-3">
                                            {!! Form::label('chapter_file', 'Digital Content File Name', ['class' => 'form-label']) !!}
                                            <input type="text" name="file_name[0]" class="form-control"
                                                placeholder="Enter file name" />
                                        </div>
                                        <!-- File Upload Column (col-md-6 remains) -->
                                        <div class="col-md-3">
                                            {!! Form::label('chapter_file', 'Choose Digital Content File', ['class' => 'form-label']) !!}
                                            <input type="file" name="chapter_file[0]" class="form-control video-input"
                                                data-index="0" />
                                            <input type="hidden" name="video_duration[0]" id="video-duration-0" />
                                        </div>
                                        <div class="col-md-2 col-sm-2 col-xs-12 input-div mt-4">
                                            <button type="button" id="add-more" class="btn btn-success"
                                                style="margin-top: 7px;">Add More Files</button>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-md-12 add-more-cols mt-3"></div>

                                @php
                                    $courseId = \App\Models\CourseChapter::where('id', $chapter_id)->value('course_id');
                                    $subcategoryId = \App\Models\Course::where('id', $courseId)->value(
                                        'sub_category_id',
                                    );
                                @endphp
                                @if ($subcategoryId == 37)
                                    <hr class="form-divider">
                                    <div class="col-md-12 col-sm-12 col-xs-12 input-div" id="link-inputs-container">
                                        <div class="row" id="link-group-0">
                                            <div class="col-md-1">
                                                {!! Form::label('link_sort_order', 'Order', ['class' => 'form-label']) !!}
                                                <input type="number" name="link_sort_order[0]" class="form-control"
                                                    placeholder="" min="1" />
                                            </div>
                                            <div class="col-md-5">
                                                {!! Form::label('link_name', 'Activity Name', ['class' => 'form-label']) !!}
                                                <input type="text" name="link_name[0]" class="form-control"
                                                    placeholder="Enter url name" />
                                            </div>
                                            <div class="col-md-6">
                                                {!! Form::label('link_link_url', 'Activity URL', ['class' => 'form-label']) !!}
                                                <input type="text" name="link_url[0]" class="form-control"
                                                    placeholder="Paste your url here" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2 col-sm-2 col-xs-12 input-div mt-4">
                                        <button type="button" id="add-more-links" class="btn btn-success"
                                            style="margin-top: 7px;">Add More Links</button>
                                    </div>

                                    <span class="add-more-links-cols"></span>
                                @endif


                                {{-- 3 extra document files --}}
                                @if ($chapter_content_extra && count($chapter_content_extra))
                                    <div class="col-md-12 col-sm-12 col-xs-12 input-div mt-2">
                                        <label class="form-label">Uploaded TM/QB/LP</label>
                                        <hr class="form-divider">
                                        <div class="row">
                                            @foreach ($chapter_content_extra as $index => $file)
                                                <div class="col-md-3 col-sm-3 col-4 mb-3 position-relative class-item">
                                                    <div class="card h-100 p-3">
                                                        <a class="h-100 d-block text-center filesView"
                                                            href="{{ Storage::url('uploads/course_chapter_files/' . $file->attachment_file) }}"
                                                            target="_blank">
                                                            {{-- @if (in_array($file->file_extension, ['jpg', 'jpeg', 'png', 'webp', 'gif']))
                                                            <img src="{{ Storage::url('uploads/course_chapter_files/' . $file->attachment_file) }}"
                                                                class="card-img-top" alt="{{ $file->original_name }}"
                                                                style="height: 100px; object-fit: contain;">
                                                        @else
                                                            <div class="card-body text-center">
                                                                <i class="bi bi-filetype-{{ $file->file_extension }} fs-2"></i>
                                                            </div>
                                                        @endif --}}
                                                            <i class="bi bi-filetype-{{ $file->file_extension }} fs-1"
                                                                style="color:#30C768;"></i>
                                                            <p class="text-center mt-2 mb-0" style="font-size: 13px">
                                                                {{ $file->attachment_file }}</p>
                                                        </a>
                                                        @if (
                                                            $file->file_extension == 'mp4' ||
                                                                $file->file_extension == 'avi' ||
                                                                $file->file_extension == 'mov' ||
                                                                $file->file_extension == 'mkv')
                                                            <p class="text-center mt-2" style="font-size: 13px">Duration:
                                                                {{ $file->video_duration ? gmdate('H:i:s', $file->video_duration) : 'N/A' }}
                                                            </p>
                                                        @endif
                                                        <button type="button"
                                                            class="btn btn-danger btn-sm position-absolute top-0 end-0"
                                                            onclick="confirmDelete('{{ route('course.chapter.file.delete', $file->id) }}')">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                            <hr class="form-divider">
                                        </div>
                                    </div>
                                @endif

                                @if ($activity_worksheet_content && count($activity_worksheet_content))
                                    <div class="col-12 mt-4">
                                        <label class="form-label fw-semibold mb-2">Uploaded Activity Content</label>
                                        <hr class="form-divider mb-3">

                                        <div class="row g-3">
                                            @foreach ($activity_worksheet_content as $index => $file)
                                                <div class="col-md-3 col-sm-4 col-6">
                                                    <div class="card border-0 shadow-sm position-relative h-100">
                                                        <div class="card-body text-center px-2 py-3">
                                                            @php
                                                                $url = $file->link_url;
                                                                $fullUrl = Str::startsWith($url, [
                                                                    'http://',
                                                                    'https://',
                                                                ])
                                                                    ? $url
                                                                    : 'https://' . $url;
                                                            @endphp

                                                            <a href="{{ $fullUrl }}" target="_blank"
                                                                class="h-100 d-block text-center filesView text-decoration-none text-dark">
                                                                <i class="bi bi-link-45deg fs-1 text-primary"></i>
                                                                <p class="text-center mt-2 mb-0 small">
                                                                    {{ $file->file_name ?: $file->original_name }}
                                                                </p>
                                                            </a>

                                                            <button type="button"
                                                                class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2"
                                                                onclick="confirmDelete('{{ route('course.chapter.file.delete', $file->id) }}')">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif


                                {{-- uploaded digital files --}}
                                <div class="col-md-12 col-sm-12 col-xs-12 input-div mt-2">
                                    <label class="form-label">Uploaded Digital Content Files</label>
                                    <hr class="form-divider">
                                    <div class="row">
                                        @foreach ($chapter_content as $index => $file)
                                            <div class="col-md-3 col-sm-3 col-4 mb-3 position-relative class-item">
                                                <div class="card h-100 p-3">

                                                    <a class="h-100 d-block text-center filesView"
                                                        href="{{ Storage::url('uploads/course_chapter_files/' . $file->attachment_file) }}"
                                                        target="_blank">
                                                        @php
                                                            $knownFileIcons = [
                                                                'mp4',
                                                                'avi',
                                                                'mov',
                                                                'pdf',
                                                                'doc',
                                                                'docx',
                                                                'xls',
                                                                'xlsx',
                                                                'ppt',
                                                                'pptx',
                                                                'png',
                                                                'jpg',
                                                                'jpeg',
                                                            ];
                                                            $extension = strtolower($file->file_extension);
                                                        @endphp

                                                        <i class="bi {{ in_array($extension, $knownFileIcons) ? 'bi-filetype-' . $extension : 'bi-file-play' }} fs-1"
                                                            style="color:#30C768;"></i>
                                                        <p class="text-center mt-2 mb-0" style="font-size: 13px">
                                                            {{ $file->file_name ? $file->file_name : $file->original_name }}
                                                        </p>

                                                    </a>
                                                    <livewire:chapter-file-sort-order-editor :file="$file"
                                                        :key="$file->id" />
                                                    <button type="button"
                                                        class="btn btn-danger btn-sm position-absolute top-0 end-0"
                                                        onclick="confirmDelete('{{ route('course.chapter.file.delete', $file->id) }}')">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                        <hr class="form-divider">
                                    </div>
                                </div>

                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary">Update</button>
                                    <button type="reset" class="btn btn-secondary"
                                        onclick="window.location.reload();">Reset</button>
                                </div>
                            </div>

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>
    <script>
        function getLanguageOptionsHtml(index) {
            let options = `<option value="">--Select Content Language--</option>`;
            for (const [key, value] of Object.entries(window.CONTENT_LANGUAGE_OPTIONS)) {
                options += `<option value="${key}">${value}</option>`;
            }
            return `
        <select name="language[${index}]" class="form-select">
            ${options}
        </select>
    `;
        }
        window.CONTENT_LANGUAGE_OPTIONS = @json(config('constants.CONTENT_LANGUAGE'));
        window.CONTENT_LANGUAGE_OPTIONS = @json(config('constants.CONTENT_LANGUAGE'));

        function getVideoViewTypeOptionsHtml(index) {
            let options = `<option value="">--Select Video Type--</option>`;
            for (const [key, value] of Object.entries(window.VIDEO_VIEW_TYPE_OPTIONS)) {
                options += `<option value="${key}">${value}</option>`;
            }
            return `
        <select name="video_view_type[${index}]" class="form-select">
            ${options}
        </select>
    `;
        }
        window.VIDEO_VIEW_TYPE_OPTIONS = @json(config('constants.VIDEO_VIEW_TYPE'));

        document.addEventListener('DOMContentLoaded', function() {
            let fileIndex = 0;

            // Add More functionality
            document.getElementById('add-more').addEventListener('click', function() {
                fileIndex++;

                // Create a new file input group with a unique index
                const fileGroup = `
                <div class="row" id="file-group-${fileIndex}">
                    <div class="col-md-12 col-sm-12 col-xs-12 input-div">
                        <div class="row">
                            <div class="col-md-1">
                                <label class="form-label">Language</label>
                                ${getLanguageOptionsHtml(fileIndex)}
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">Order</label>
                                <input type="number" name="video_sort_order[${fileIndex}]" class="form-control" placeholder="" min="1" />
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Video Type</label>
                                ${getVideoViewTypeOptionsHtml(fileIndex)}
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Digital Content File Name</label>
                                <input type="text" name="file_name[${fileIndex}]" class="form-control" placeholder="Enter file name" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Choose Digital Content File</label>
                                <input type="file" name="chapter_file[${fileIndex}]" class="form-control video-input" data-index="${fileIndex}" required />
                                <input type="hidden" name="video_duration[${fileIndex}]" id="video-duration-${fileIndex}" />
                            </div>
                             <div class="col-md-2 d-flex align-items-end input-div" style="margin-top: 30px !important;">
                                <button type="button" class="btn btn-danger btn-sm remove-file" data-index="${fileIndex}">Remove</button>
                             </div>
                        </div>
                    </div>
                   
                </div>
                `;



                // Append the new file input group
                document.querySelector('.add-more-cols').insertAdjacentHTML('beforeend', fileGroup);
            });

            // Remove dynamically added file inputs
            document.querySelector('.add-more-cols').addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-file')) {
                    const index = e.target.getAttribute('data-index');
                    document.getElementById(`file-group-${index}`).remove();
                }
            });

            // video_duration updates when a new video is selected but retains old values when not changed.
            document.addEventListener('DOMContentLoaded', function() {
                document.querySelectorAll('.video-input').forEach(input => {
                    input.addEventListener('change', function(e) {
                        const file = e.target.files[0];
                        const index = e.target.getAttribute('data-index');

                        if (file && file.type.startsWith('video/')) {
                            const video = document.createElement('video');
                            video.preload = 'metadata';

                            video.onloadedmetadata = function() {
                                window.URL.revokeObjectURL(video.src);
                                const duration = Math.floor(video
                                    .duration); // Get duration in seconds
                                document.getElementById(`video-duration-${index}`)
                                    .value = duration;
                            };

                            video.src = URL.createObjectURL(file);
                        } else {
                            // If file is not a video, clear duration
                            document.getElementById(`video-duration-${index}`).value = "";
                        }
                    });
                });
            });
        });
    </script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let linkIndex = 0;

            // Add More Links functionality
            document.getElementById('add-more-links').addEventListener('click', function() {
                linkIndex++;

                const linkGroup = `
            <div class="row " id="link-group-${linkIndex}">
                <div class="col-md-12 col-sm-12 col-xs-12 input-div">
                    <div class="row">
                        <div class="col-md-1">
                            <input type="number" name="link_sort_order[${linkIndex}]" class="form-control" placeholder="" min="1" />
                        </div>
                        <div class="col-md-5">
                            <input type="text" name="link_name[${linkIndex}]" class="form-control" placeholder="Enter url name" />
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="link_url[${linkIndex}]" class="form-control" placeholder="Paste your url here" />
                        </div>
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-center">
                    <button type="button" class="btn btn-danger btn-sm remove-link" data-index="${linkIndex}">Remove</button>
                </div>
            </div>
        `;

                document.querySelector('.add-more-links-cols').insertAdjacentHTML('beforeend', linkGroup);
            });

            // Remove Link group
            document.querySelector('.add-more-links-cols').addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-link')) {
                    const index = e.target.getAttribute('data-index');
                    document.getElementById(`link-group-${index}`).remove();
                }
            });
        });
    </script>
@endsection
