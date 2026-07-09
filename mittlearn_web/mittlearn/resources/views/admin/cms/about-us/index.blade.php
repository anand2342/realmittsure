@extends('admin.layouts.master')
@section('content')
    <div id="page-header" class="page-header">
        <section class="section">
            <div class="pagetitle">
                <h1>About Us Page Content</h1>
                <nav>
                    <ol class="breadcrumb">
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="text-end mb-2">
                    <a href="{{ route('dashboard') }}" class="btn btn-primary"><i class="ri-arrow-left-line"></i></a>
                </div>
                {{ Form::model($data, ['url' => route('cms-about.save'), 'files' => true]) }}
                @csrf
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-3">
                                <h4 class="card-title">Banner</h4>
                                <hr class="form-divider">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('banner_image', 'Banner Image', ['class' => 'form-label']) !!}
                                    <small class="form-text text-muted">(Allowed formats: PNG, PDF, SVG. Image dimensions:
                                        50x50 pixels)</small>
                                    {!! Form::file('banner_image', ['class' => 'form-control', 'id' => 'formFile']) !!}
                                    @if (isset($data) && $data['banner_image'])
                                        <img src="{{ Storage::url($data['banner_image']) }}" alt="Featured Image"
                                            class="img-thumbnail mt-2" style="max-width: 150px;">
                                    @endif
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('title', 'Title', ['class' => 'form-label required']) !!}
                                    {!! Form::text('title', null, ['class' => 'form-control']) !!}
                                </div>
                                <!-- Textarea with onkeyup event for banner description -->
                                <div class="col-md-12 col-sm-6 col-xs-12 position-relative">
                                    {!! Form::label('banner_description', 'Description', ['class' => 'form-label required']) !!}
                                    {!! Form::textarea('banner_description', null, [
                                        'class' => 'form-control',
                                        'rows' => '1',
                                        'id' => 'textarea',
                                        'onkeyup' => 'updateWordCount(this, 50)',
                                    ]) !!}
                                    <small id="word-count-message" class="position-absolute text-muted bg-white p-1 rounded"
                                        style="bottom: 8px; right: 10px;">
                                        Words: 0/50
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-3">
                                <h4 class="card-title">Mittsure Technologies At a Glance</h4>
                                <hr class="form-divider">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('glance_image', 'Image', ['class' => 'form-label']) !!}
                                    <small class="form-text text-muted">(Allowed formats: PNG, PDF, SVG. Image dimensions:
                                        50x50 pixels)</small>
                                    @if (!empty($glance['glance_image']))
                                        <div class="mb-3">
                                            <img src="{{ asset('storage/' . $glance['glance_image']) }}" alt="Glance Image"
                                                class="img-thumbnail" style="max-height: 150px;">
                                        </div>
                                    @endif
                                    {!! Form::file('glance_image', ['class' => 'form-control', 'id' => 'formFile']) !!}
                                </div>
                                <!-- Textarea with onkeyup event for mittsure description -->
                                <div class="col-md-6 col-sm-6 col-xs-12 position-relative">
                                    {!! Form::label('mittsure_description', 'Description', ['class' => 'form-label required']) !!}
                                    {!! Form::textarea('mittsure_description', $glance['mittsure_at_glance_description'] ?? null, [
                                        'class' => 'form-control',
                                        'rows' => '1',
                                        'id' => 'textarea',
                                        'onkeyup' => 'updateWordCount(this, 50)',
                                    ]) !!}
                                    <small id="word-count-message" class="position-absolute text-muted bg-white p-1 rounded"
                                        style="bottom: 8px; right: 10px;">
                                        Words: 0/50
                                    </small>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('button', 'Button', ['class' => 'form-label required']) !!}
                                    {!! Form::text('button', $glance['button'] ?? null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-3">
                                <h4 class="card-title">Mittsure</h4>
                                <hr class="form-divider">
                                <!-- Textarea with onkeyup event for mittsure section description -->
                                <div class="col-md-6 col-sm-6 col-xs-12 position-relative">
                                    {!! Form::label('mittsure_section_description', 'Description', ['class' => 'form-label required']) !!}
                                    <small class="form-text text-muted">(Allowed formats: PNG, PDF, SVG. Image dimensions:
                                        50x50 pixels)</small>
                                    {!! Form::textarea('mittsure_section_description', $mittsure_section['mittsure_section_description'] ?? null, [
                                        'class' => 'form-control',
                                        'rows' => '1',
                                        'id' => 'textarea',
                                        'onkeyup' => 'updateWordCount(this, 50)',
                                    ]) !!}
                                    <small id="word-count-message" class="position-absolute text-muted bg-white p-1 rounded"
                                        style="bottom: 8px; right: 10px;">
                                        Words: 0/50
                                    </small>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('mittsure_section_image', 'Image', ['class' => 'form-label']) !!}
                                    @if (!empty($mittsure_section['mittsure_section_image']))
                                        <div class="mb-3">
                                            <img src="{{ asset('storage/' . $mittsure_section['mittsure_section_image']) }}"
                                                alt="Glance Image" class="img-thumbnail" style="max-height: 150px;">
                                        </div>
                                    @endif
                                    {!! Form::file('mittsure_section_image', ['class' => 'form-control', 'id' => 'formFile']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-3">
                                <h4 class="card-title">Versatile Activities</h4>
                                <hr class="form-divider">
                                <!-- Textarea with onkeyup event for versatile activities description -->
                                <div class="col-md-6 col-sm-6 col-xs-12 position-relative">
                                    {!! Form::label('versatile_activities_description', 'Description', ['class' => 'form-label required']) !!}
                                    {!! Form::textarea('versatile_activities_description', null, [
                                        'class' => 'form-control',
                                        'rows' => '1',
                                        'id' => 'textarea',
                                        'onkeyup' => 'updateWordCount(this, 50)',
                                    ]) !!}
                                    <small id="word-count-message" class="position-absolute text-muted bg-white p-1 rounded"
                                        style="bottom: 8px; right: 10px;">
                                        Words: 0/50
                                    </small>
                                </div>
                                <!-- Radio buttons for Versatile Activities -->
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('versatile_activities', 'Versatile Activities', ['class' => 'form-label required']) !!}
                                    <div class="form-check">
                                        {!! Form::radio('versatile_activities', 'random', false, [
                                            'class' => 'form-check-input',
                                            'id' => 'view_random',
                                            'checked' => 'checked',
                                            'onclick' => 'toggleDropdown(false)',
                                        ]) !!}
                                        {!! Form::label('view_random', 'View Random from Groups', ['class' => 'form-check-label']) !!}
                                    </div>
                                    <div class="form-check">
                                        {!! Form::radio('versatile_activities', 'selected', false, [
                                            'class' => 'form-check-input',
                                            'id' => 'view_selected',
                                            'onclick' => 'toggleDropdown(true)',
                                        ]) !!}
                                        {!! Form::label('view_selected', 'View Selected', ['class' => 'form-check-label']) !!}
                                    </div>
                                </div>

                                <!-- Dropdown for categories (hidden by default) -->
                                <div class="col-md-6 col-sm-6 col-xs-12" id="categoryDropdown" style="display: none;">
                                    {!! Form::label('category_id', 'Select Categories', ['class' => 'form-label required']) !!}
                                    <small class="text-muted">Select a minimum of 20 and a maximum of 20 categories.</small>
                                    {!! Form::select('category_id[]', $categories, null, [
                                        'class' => 'form-select',
                                        'id' => 'multiSelect',
                                        'multiple' => 'multiple',
                                    ]) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-3">
                                <h4 class="card-title">Our Leadership</h4>
                                <hr class="form-divider">
                                <!-- Textarea with onkeyup event -->
                                <div class="col-md-12 col-sm-6 col-xs-12 position-relative">
                                    {!! Form::label('our_leadership_description', 'Our Leadership Description', ['class' => 'form-label required']) !!}
                                    {!! Form::textarea('our_leadership_description', $leadership['our_leadership_description'] ?? null, [
                                        'class' => 'form-control',
                                        'rows' => '1',
                                        'id' => 'textarea',
                                        'onkeyup' => 'updateWordCount(this, 50)',
                                    ]) !!}
                                    <small id="word-count-message" class="position-absolute text-muted bg-white p-1 rounded"
                                        style="bottom: 8px; right: 10px;">
                                        Words: 0/50
                                    </small>
                                </div>
                                <!-- Leadership Dropdowns -->
                                @foreach (['primary', 'secondary', 'third', 'fourth', 'fifth'] as $index)
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        {!! Form::label('user_id_' . $index, 'Select ' . ucfirst($index) . ' View Leadership', [
                                            'class' => 'form-label required',
                                        ]) !!}
                                        {!! Form::select('user_id_' . $index, $leaders, $leadership[$index] ?? null, [
                                            'class' => 'form-control form-select fs-8',
                                            'placeholder' => '--Select--',
                                        ]) !!}
                                    </div>
                                @endforeach


                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-3">
                                <h4 class="card-title">Our Vision</h4>
                                <hr class="form-divider">

                                <div class="col-md-12 col-sm-6 col-xs-12 position-relative">
                                    {!! Form::label('vision_description', 'Description', ['class' => 'form-label required']) !!}
                                    {!! Form::textarea('vision_description', $data->vision_description ?? null, [
                                        'class' => 'form-control',
                                        'rows' => '1',
                                        'id' => 'textarea',
                                        'onkeyup' => 'updateWordCount(this, 50)',
                                    ]) !!}
                                    <small id="word-count-message" class="position-absolute text-muted bg-white p-1 rounded"
                                        style="bottom: 8px; right: 10px;">
                                        Words: 0/50
                                    </small>
                                </div>

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('vision_image', 'Banner Image', ['class' => 'form-label']) !!}
                                    <small class="form-text text-muted">(Allowed formats: PNG, PDF, SVG. Image dimensions:
                                        50x50 pixels)</small>
                                    @if (!empty($data->vision_image))
                                        <div class="mb-3">
                                            <img src="{{ asset('storage/' . $data->vision_image) }}" alt="Glance Image"
                                                class="img-thumbnail" style="max-height: 150px;">
                                        </div>
                                    @endif
                                    {!! Form::file('vision_image', ['class' => 'form-control', 'id' => 'vision_image']) !!}
                                </div>

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('about_vision', 'About Vision', ['class' => 'form-label required']) !!}
                                    <div class="quill-editor-full" id="editor" style="height: 100px;"></div>
                                    {!! Form::hidden('about_vision', $data->about_vision ?? null, ['id' => 'editor-content']) !!}
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-3">
                                <h4 class="card-title">Our Programs</h4>
                                <hr class="form-divider">

                                <!-- Overall Description -->
                                <div class="col-md-12 col-sm-6 col-xs-12 position-relative">
                                    {!! Form::label('program_description', 'Overall Description', ['class' => 'form-label required']) !!}
                                    {!! Form::textarea('program_description', null, [
                                        'class' => 'form-control',
                                        'rows' => '1',
                                        'id' => 'program_description',
                                        'onkeyup' => 'updateWordCount(this, 50)',
                                    ]) !!}
                                    <small id="word-count-message-program-description"
                                        class="position-absolute text-muted bg-white p-1 rounded"
                                        style="bottom: 8px; right: 10px;">
                                        Words: 0/50
                                    </small>
                                </div>
                                @livewire('our-program', ['programs' => $programs])
                            </div>
                            <div class="text-end mt-3">
                                {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
                                {!! Form::reset('Reset', ['class' => 'btn btn-secondary']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                @if (isset($activitiesGallaryImages) && $activitiesGallaryImages)
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row g-3 mb-3">
                                    <h4 class="card-title">Our Activities</h4>
                                    <hr class="form-divider">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        {{ $activitiesGallaryImages->folder_name }} Images
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <a class="btn btn-sm btn-info me-1"
                                            href="{{ route('media.gallery.folder.view', $activitiesGallaryImages->id) }}">View
                                            / Add Images</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            {{ Form::close() }}
        </section>
    </div>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const multiSelect = document.getElementById('multiSelect');

        // Toggle the dropdown visibility
        window.toggleDropdown = function(showDropdown) {
            const dropdown = document.getElementById('categoryDropdown');
            dropdown.style.display = showDropdown ? 'block' : 'none';
        };

        // Validate the selection limits
        multiSelect.addEventListener('change', function() {
            const selectedOptions = Array.from(multiSelect.selectedOptions);
            if (selectedOptions.length > 20) {
                alert('You can select a maximum of 20 categories.');
                // Deselect the last selected option
                selectedOptions[selectedOptions.length - 1].selected = false;
            } else if (selectedOptions.length < 1) {
                alert('Please select at least one category.');
            }
        });
    });


    function updateWordCount(element, maxWords) {
        const text = element.value.trim();
        const words = text.split(/\s+/).filter(word => word.length > 0);
        const wordCount = words.length;

        if (wordCount > maxWords) {
            element.value = words.slice(0, maxWords).join(" ");
            document.getElementById('word-count-message').textContent = `Maximum ${maxWords} words allowed.`;
        } else {
            document.getElementById('word-count-message').textContent = `Words: ${wordCount}/${maxWords}`;
        }
    }
    document.addEventListener("DOMContentLoaded", function() {
        const textarea = document.getElementById('textarea');
        updateWordCount(textarea, 50);
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editor = document.querySelector('.quill-editor-full');
        const initialContent = {!! json_encode(old('about_vision', isset($data) ? $data->about_vision : '')) !!};
        if (editor && window.Quill) {
            const quill = Quill.find(editor);
            if (quill) {
                quill.root.innerHTML = initialContent;
                quill.on('text-change', function() {
                    document.getElementById('editor-content').value = quill.root.innerHTML.trim();
                });
            }
            document.querySelector('form').addEventListener('submit', function(event) {
                const quillContent = quill.root.innerHTML.trim();
                document.getElementById('editor-content').value = quillContent;
                if (!quillContent) {
                    alert('The about_vision field is required.');
                    event.preventDefault();
                }
            });
        }
    });
</script>
