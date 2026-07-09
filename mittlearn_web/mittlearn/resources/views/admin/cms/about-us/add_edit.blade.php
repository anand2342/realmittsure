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
                            <div class= "row g-3">
                                <h4 class="card-title">Banner</h4>
                                <hr class="form-divider">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('banner_image', 'Banner Image', ['class' => 'form-label']) !!}
                                    {!! Form::file('banner_image', ['class' => 'form-control', 'id' => 'formFile']) !!}
                                    {{-- <img src=""
                                        alt="Profile Image" width="100" height="50"> --}}
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('title', 'Title', ['class' => 'form-label required']) !!}
                                    {!! Form::text('title', null, ['class' => 'form-control']) !!}
                                </div>
                                <!-- Textarea with onkeyup event -->
                                <div class="col-md-12 col-sm-6 col-xs-12 position-relative">
                                    {!! Form::label('description', 'Description', ['class' => 'form-label required']) !!}
                                    {!! Form::textarea('description', null, [
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
                            <div class= "row g-3">
                                <h4 class="card-title">Mittsure Technologies At a Glance</h4>
                                <hr class="form-divider">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('image', 'Image', ['class' => 'form-label']) !!}
                                    {!! Form::file('image', ['class' => 'form-control', 'id' => 'formFile']) !!}
                                    {{-- <img src=""
                                        alt="Profile Image" width="100" height="50"> --}}
                                </div>
                                <!-- Textarea with onkeyup event -->
                                <div class="col-md-6 col-sm-6 col-xs-12 position-relative">
                                    {!! Form::label('description', 'Description', ['class' => 'form-label required']) !!}
                                    {!! Form::textarea('description', null, [
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
                                    {!! Form::text('button', null, ['class' => 'form-control']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class= "row g-3">
                                <h4 class="card-title">Mittlearn</h4>
                                <hr class="form-divider">
                                <!-- Textarea with onkeyup event -->
                                <div class="col-md-6 col-sm-6 col-xs-12 position-relative">
                                    {!! Form::label('description', 'Description', ['class' => 'form-label required']) !!}
                                    {!! Form::textarea('description', null, [
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
                                    {!! Form::label('image', 'Image', ['class' => 'form-label']) !!}
                                    {!! Form::file('image', ['class' => 'form-control', 'id' => 'formFile']) !!}
                                    {{-- <img src=""
                                        alt="Profile Image" width="100" height="50"> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class= "row g-3">
                                <h4 class="card-title">Versatile Activities</h4>
                                <hr class="form-divider">
                                <!-- Textarea with onkeyup event -->
                                <div class="col-md-6 col-sm-6 col-xs-12 position-relative">
                                    {!! Form::label('description', 'Description', ['class' => 'form-label required']) !!}
                                    {!! Form::textarea('description', null, [
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
                            <div class= "row g-3">
                                <h4 class="card-title">Our Leadership</h4>
                                <hr class="form-divider">
                                <!-- Textarea with onkeyup event -->
                                <div class="col-md-12 col-sm-6 col-xs-12 position-relative">
                                    {!! Form::label('description', 'Description', ['class' => 'form-label required']) !!}
                                    {!! Form::textarea('description', null, [
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
                                    {!! Form::label('user_id_primary', 'Select First View Leadership', ['class' => 'form-label required ']) !!}
                                    {!! Form::select('user_id_primary', $leaders, null, [
                                        'class' => 'form-control form-select fs-8 ',
                                        'placeholder' => '--Select--',
                                    ]) !!}
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('user_id_secondry', 'Select Second View Leadership', ['class' => 'form-label required ']) !!}
                                    {!! Form::select('user_id_secondry', $leaders, null, [
                                        'class' => 'form-control form-select fs-8 ',
                                        'placeholder' => '--Select--',
                                    ]) !!}
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('user_id_primary', 'Select Thired View Leadership', ['class' => 'form-label required ']) !!}
                                    {!! Form::select('user_id_primary', $leaders, null, [
                                        'class' => 'form-control form-select fs-8 ',
                                        'placeholder' => '--Select--',
                                    ]) !!}
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('user_id_primary', 'Select Fourth View Leadership', ['class' => 'form-label ']) !!}
                                    {!! Form::select('user_id_primary', $leaders, null, [
                                        'class' => 'form-control form-select fs-8 ',
                                        'placeholder' => '--Select--',
                                    ]) !!}
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('user_id_primary', 'Select Fifth View Leadership', ['class' => 'form-label ']) !!}
                                    {!! Form::select('user_id_primary', $leaders, null, [
                                        'class' => 'form-control form-select fs-8 ',
                                        'placeholder' => '--Select--',
                                    ]) !!}
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('user_id_primary', 'Select Sixeth View Leadership', ['class' => 'form-label ']) !!}
                                    {!! Form::select('user_id_primary', $leaders, null, [
                                        'class' => 'form-control form-select fs-8 ',
                                        'placeholder' => '--Select--',
                                    ]) !!}
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class= "row g-3">
                                <h4 class="card-title">Our Vision</h4>
                                <hr class="form-divider">
                                <!-- Textarea with onkeyup event -->
                                <div class="col-md-12 col-sm-6 col-xs-12 position-relative">
                                    {!! Form::label('description', 'Description', ['class' => 'form-label required']) !!}
                                    {!! Form::textarea('description', null, [
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
                                    {!! Form::file('vision_image', ['class' => 'form-control', 'id' => 'vision_image']) !!}
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('about_vision', 'About Vision', ['class' => 'form-label required']) !!}
                                    <div class="quill-editor-full" id="editor" style="height: 100px;"></div>
                                    {!! Form::hidden('about_vision', null, ['id' => 'editor-content']) !!}
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
                                <!-- Textarea with onkeyup event -->
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

                                <h4 class="card-title">Our Program First</h4>
                                <hr class="form-divider">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('program_1_title', 'Title', ['class' => 'form-label required']) !!}
                                    {!! Form::text('program_1_title', null, ['class' => 'form-control', 'id' => 'program_1_title']) !!}
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12 position-relative">
                                    {!! Form::label('program_1_description', 'Description', ['class' => 'form-label required']) !!}
                                    {!! Form::textarea('program_1_description', null, [
                                        'class' => 'form-control',
                                        'rows' => '1',
                                        'id' => 'program_1_description',
                                        'onkeyup' => 'updateWordCount(this, 50)',
                                    ]) !!}
                                    <small id="word-count-message-program-1-description"
                                        class="position-absolute text-muted bg-white p-1 rounded"
                                        style="bottom: 8px; right: 10px;">
                                        Words: 0/50
                                    </small>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('program_1_banner_image', 'Banner Image', ['class' => 'form-label']) !!}
                                    {!! Form::file('program_1_banner_image', ['class' => 'form-control', 'id' => 'program_1_banner_image']) !!}
                                </div>

                                <h4 class="card-title">Our Program Second</h4>
                                <hr class="form-divider">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('program_2_title', 'Title', ['class' => 'form-label required']) !!}
                                    {!! Form::text('program_2_title', null, ['class' => 'form-control', 'id' => 'program_2_title']) !!}
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12 position-relative">
                                    {!! Form::label('program_2_description', 'Description', ['class' => 'form-label required']) !!}
                                    {!! Form::textarea('program_2_description', null, [
                                        'class' => 'form-control',
                                        'rows' => '1',
                                        'id' => 'program_2_description',
                                        'onkeyup' => 'updateWordCount(this, 50)',
                                    ]) !!}
                                    <small id="word-count-message-program-2-description"
                                        class="position-absolute text-muted bg-white p-1 rounded"
                                        style="bottom: 8px; right: 10px;">
                                        Words: 0/50
                                    </small>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('program_2_banner_image', 'Banner Image', ['class' => 'form-label']) !!}
                                    {!! Form::file('program_2_banner_image', ['class' => 'form-control', 'id' => 'program_2_banner_image']) !!}
                                </div>

                                <h4 class="card-title">Our Program Third</h4>
                                <hr class="form-divider">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('program_3_title', 'Title', ['class' => 'form-label required']) !!}
                                    {!! Form::text('program_3_title', null, ['class' => 'form-control', 'id' => 'program_3_title']) !!}
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12 position-relative">
                                    {!! Form::label('program_3_description', 'Description', ['class' => 'form-label required']) !!}
                                    {!! Form::textarea('program_3_description', null, [
                                        'class' => 'form-control',
                                        'rows' => '1',
                                        'id' => 'program_3_description',
                                        'onkeyup' => 'updateWordCount(this, 50)',
                                    ]) !!}
                                    <small id="word-count-message-program-3-description"
                                        class="position-absolute text-muted bg-white p-1 rounded"
                                        style="bottom: 8px; right: 10px;">
                                        Words: 0/50
                                    </small>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('program_3_banner_image', 'Banner Image', ['class' => 'form-label']) !!}
                                    {!! Form::file('program_3_banner_image', ['class' => 'form-control', 'id' => 'program_3_banner_image']) !!}
                                </div>
                            </div>
                            <div class="col-sm-12 text-end">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="reset" class="btn btn-secondary"
                                    onclick="window.location.reload();">Reset</button>
                            </div>
                        </div>
                    </div>
                </div>
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
