@extends('admin.layouts.master')

@section('content')
    <div id="page-header" class="page-header">
        <section class="section">
            <div class="pagetitle">
                <h1>Edit Category</h1>
                <nav>
                    <ol class="breadcrumb"></ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Edit</h4>
                            {!! Form::model($subCategory, [
                                'route' => ['sub-category.update', $subCategory->id],
                                'method' => 'PUT',
                                'files' => true,
                                'class' => 'row g-3',
                            ]) !!}
                            <div class="col-md-4 col-sm-3 col-xs-12">
                                {!! Form::label('category', 'Parent Category', ['class' => 'form-label']) !!}
                                {!! Form::select('parent_id', $category, $subCategory->parent_id, [
                                    'class' => 'form-control',
                                    'required',
                                    'disabled', // Disable to prevent changes
                                ]) !!}
                            </div>

                            <!-- Subcategory Dropdown -->
                            <div class="col-md-4 col-sm-3 col-xs-12">
                                {!! Form::label('sub_category', 'Select Category', ['class' => 'form-label']) !!}
                                {!! Form::select(
                                    'sub_category_id',
                                    $categoriesUnderParent, // List of subcategories under the selected parent
                                    $subCategory->id, // Pre-select the subcategory being edited
                                    ['class' => 'form-control', 'required', 'disabled'],
                                ) !!}
                            </div>

                            <div class="col-md-4 col-sm-3 col-xs-12">
                                {!! Form::label('name', 'Subcategory Name', ['class' => 'form-label']) !!}
                                {!! Form::text('name', null, ['class' => 'form-control', 'required']) !!}
                            </div>



                            <div class="col-md-4 col-sm-3 col-xs-12">
                                {!! Form::label('featured_image', 'Featured Image', ['class' => 'form-label']) !!}
                                {!! Form::file('featured_image', ['class' => 'form-control']) !!}
                                <img src="{{ asset('Uploads/Categories/FeaturedImage/' . $subCategory->featured_image) }}"
                                    alt="No Image" class="img-thumbnail mt-2" width="100">
                            </div>

                            <!-- Icon -->
                            <div class="col-md-4 col-sm-3 col-xs-12">
                                {!! Form::label('icon', 'Icon', ['class' => 'form-label']) !!}
                                {!! Form::file('icon', ['class' => 'form-control']) !!}
                                <img src="{{ asset('Uploads/Categories/Icon/' . $subCategory->icon) }}" alt="No image"
                                    class="img-thumbnail mt-2" width="100">
                            </div>
                            <div class="col-md-4 col-sm-3 col-xs-12">
                                {!! Form::label('description', 'Description', ['class' => 'form-label']) !!}
                                {!! Form::textarea('description', null, ['class' => 'form-control', 'required']) !!}
                            </div>
                            <!-- Submit Button -->
                            <div class="text-center">
                                {!! Form::submit('Update', ['class' => 'btn btn-primary']) !!}
                                <a href="{{ route('category.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>

                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
