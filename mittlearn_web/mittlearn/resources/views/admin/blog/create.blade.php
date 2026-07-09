@extends('admin.layouts.master')
@section('content')
    @php
        $flag = 0;
        $heading = 'Add';
        if (isset($blog) && !empty($blog)) {
            $flag = 1;
            $heading = 'Edit';
        }

    @endphp

    <div>
        <div class="pagetitle">
            <h1>{{ $heading }} Blog</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Blogs</li>
                </ol>
            </nav>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title"> Blog Info</h5>
                            <hr class="form-divider">

                            @if ($flag == 1)
                                {{ Form::model($blog, ['url' => route('blog.save'), 'id' => 'edit-plan-form', 'class' => 'row g-3', 'enctype' => 'multipart/form-data']) }}
                                {{ Form::hidden('id', null) }}
                            @else
                                {{ Form::open(['url' => route('blog.save'), 'id' => 'add-plan-form', 'class' => 'row g-3', 'enctype' => 'multipart/form-data']) }}
                            @endif

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('categories', 'Select Category', ['class' => 'form-label required']) !!}
                                {!! Form::select('categories', $categories, isset($category_id) ? $category_id : null, [
                                    'class' => 'form-control',
                                    'id' => 'select_category',
                                    'placeholder' => 'Select Category',
                                    'required',
                                ]) !!}
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12" id="subcategory-container"
                                style="display: {{ isset($subcategory_id) && $subcategory_id ? 'block' : 'none' }};">
                                {!! Form::label('subcategories', 'Select Subcategory', ['class' => 'form-label required']) !!}
                                {!! Form::select(
                                    'subcategories',
                                    isset($sub_categories) ? $sub_categories : [],
                                    isset($subcategory_id) ? $subcategory_id : [],
                                    [
                                        'class' => 'form-control',
                                        'id' => 'select_subcategory',
                                    ],
                                ) !!}
                            </div>


                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('title', 'Title', ['class' => 'form-label required']) !!}
                                {!! Form::text('title', null, [
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter Title',
                                    'id' => 'title',
                                    'id' => 'vallidateTitle',
                                ]) !!}
                                <small id="vallidateTitleError" class="form-text text-danger mt-1"
                                    style="display:none;"></small>

                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('slug', 'Slug', ['class' => 'form-label']) !!}
                                {!! Form::text('slug', null, [
                                    'class' => 'form-control',
                                    'placeholder' => 'Slug will be auto-generated from Title',
                                    'id' => 'slug',
                                    'readonly' => 'readonly',
                                ]) !!}
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('featured_image', 'Featured Image', ['class' => 'form-label required']) !!}
                                <small class="form-text text-muted">(Allowed formats: PNG, JPG, SVG. Image dimensions: 50x50
                                    pixels)</small>
                                {!! Form::file('featured_image', ['class' => 'form-control']) !!}
                                @if (isset($featured_image) && $featured_image->attachment_file)
                                    <img src="{{ Storage::url('uploads/blog/' . $featured_image->attachment_file) }}"
                                        alt="Featured Image" class="img-thumbnail mt-2" style="max-width: 150px;">
                                @endif

                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('status', 'Status', ['class' => 'form-label required']) !!}
                                {!! Form::select(
                                    'status',
                                    ['published' => 'Published', 'draft' => 'Draft', 'archived' => 'Archived'],
                                    isset($blog) ? $blog->status : null,
                                    ['class' => 'form-control', 'id' => 'status', 'required' => 'required'],
                                ) !!}
                            </div>
                            
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('meta_title', 'Meta Title', ['class' => 'form-label']) !!}
                                {!! Form::text('meta_title', null, ['class' => 'form-control']) !!}
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('meta_keywords', 'Meta Keyword', ['class' => 'form-label']) !!}
                                {!! Form::text('meta_keywords', null, ['class' => 'form-control']) !!}
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('meta_description', 'Meta Description', ['class' => 'form-label']) !!}
                                {!! Form::textarea('meta_description', null, ['class' => 'form-control', 'rows' => '1']) !!}
                            </div>

                            <div class="col-lg-12">
                                {!! Form::label('body', 'Body', ['class' => 'form-label']) !!}
                                <div class="quill-editor-full" id="editor" style="height: 200px;"></div>
                                {!! Form::hidden('body', null, ['id' => 'editor-content', 'required']) !!}
                            </div>


                            <div class="col-sm-12 text-end">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="reset" class="btn btn-secondary"
                                    onclick="window.location.reload();">Reset</button>
                            </div>

                            {!! Form::close() !!}

                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#select_category').change(function() {
                const categoryId = $(this).val();
                const $subcategoryContainer = $('#subcategory-container');
                const $selectSubcategory = $('#select_subcategory');
                $selectSubcategory.empty().append('<option selected value="">Select Subcategory</option>');
                if (categoryId) {
                    $.ajax({
                        url: `/admin/blog/subcategories/${categoryId}`,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            if ($.isEmptyObject(data)) {
                                $subcategoryContainer.hide();
                            } else {
                                $.each(data, function(key, value) {
                                    $selectSubcategory.append(
                                        `<option value="${key}">${value}</option>`);
                                });
                                $subcategoryContainer.show();
                            }
                        },
                        error: function() {
                            $subcategoryContainer.hide();
                        }
                    });
                } else {
                    $subcategoryContainer.hide();
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const editor = document.querySelector('.quill-editor-full');
            const initialContent = {!! json_encode(old('body', isset($blog) ? $blog->body : '')) !!};
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
                        alert('The body field is required.');
                        event.preventDefault();
                    }
                });
            }
        });
    </script>
@endsection
