@extends('admin.layouts.master')
@section('content')
    @php
        $isEditMode = isset($course) && !empty($course);
        $heading = $isEditMode ? 'Update' : 'Add';
    @endphp
    <div id="page-header" class="page-header">
        <section class="section">
            <div class="pagetitle">
                <div class="pagetitle">
                    <h1>{{ $heading }} Book/Course</h1>
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">Home</li>
                            <li class="breadcrumb-item active">Book/Course</li>
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
                                    <h4 class="card-title">{{ $heading }} Book/Course Info</h4>
                                </div>
                                <div class="col-sm-6 text-end mt-3">
                                    {{--  @isPermission('lesson.create')  --}}
                                    <a href="{{ route('courses.bulk-upload') }}" class="btn btn-success">
                                        Book/Course Bulk Upload
                                    </a>
                                    {{--  @endisPermission  --}}
                                </div>
                            </div>
                            <hr class="form-divider">
                            {{ Form::model($course ?? null, ['url' => route('course.store'), 'id' => $isEditMode ? 'edit-course-form' : 'add-course-form', 'class' => 'row g-3', 'files' => true]) }}
                            {{ Form::hidden('id', $isEditMode ? $course->id : null) }}
                            @livewire('courses-form', [
                                'category' => $category,
                                'modelsData' => $modelsData,
                                'course' => isset($course) ? $course : null,
                                'metadataFieldValues' => $metadataFieldValues ?? [],
                            ])
                            {!! Form::close() !!}
                            @if (session()->has('message'))
                                <div class="alert alert-success mt-3">
                                    {{ session('message') }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
