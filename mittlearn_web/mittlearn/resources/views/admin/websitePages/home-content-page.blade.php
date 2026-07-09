@extends('admin.layouts.master')
@section('content')
    <div id="page-header" class="page-header">
        <section class="section">
            <div class="pagetitle">
                <h1>Website Page Content</h1>
                <nav>
                    <ol class="breadcrumb">
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="text-end mb-2">
                    <a href="{{ route('dashboard') }}" class="btn btn-primary"><i class="ri-arrow-left-line"></i></a>
                </div>
                {{ Form::open(['url' => route('home.page-content.save'), 'method' => 'post', 'files' => true]) }}
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class= "row g-3">
                                <h4 class="card-title">Banner</h4>
                                <hr class="form-divider">
                                <div class="col-md-12 col-sm-6 col-xs-12">
                                    {!! Form::hidden('section_name_1', 'first_banner', ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-12 col-sm-6 col-xs-12">
                                    {!! Form::label('heading', 'Heading', ['class' => 'form-label required']) !!}
                                    {!! Form::text('heading', $firstBanner->heading ?? null, ['class' => 'form-control']) !!}
                                </div>
                                <h6>Category & Images</h6>
                                @livewire('home-page-academic-group', ['firstBannerAddtional' => $firstBannerAddtional])
                                {{--  @livewire('home-page-non-academic-group', ['firstBanner' => $firstBanner])  --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class= "row g-3">
                                <h4 class="card-title">Core Feature Banner</h4>
                                <hr class="form-divider">
                                <div class="col-md-12 col-sm-6 col-xs-12">
                                    {!! Form::hidden('section_name_2', 'feature_banner', ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('core_title', 'Title', ['class' => 'form-label required']) !!}
                                    {!! Form::text('core_title', $coreFeatureBanner->core_title ?? null, ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('core_heading', 'Heading', ['class' => 'form-label required']) !!}
                                    {!! Form::text('core_heading', $coreFeatureBanner->core_heading ?? null, ['class' => 'form-control']) !!}
                                </div>
                                @livewire('home-core-feature-content', ['coreAcademicFeatureAddtional' => $coreAcademicFeatureAddtional])
                                @livewire('non-academic-core-feature', ['coreNonAcademicFeatureAddtional' => $coreNonAcademicFeatureAddtional])
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class= "row g-3">
                                <h4 class="card-title">Instructor Banner</h4>
                                <hr class="form-divider">
                                <div class="col-md-12 col-sm-6 col-xs-12">
                                    {!! Form::hidden('section_name_3', 'instructor_banner', ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('instructor_title', 'Title', ['class' => 'form-label required']) !!}
                                    {!! Form::text('instructor_title', $instructorBanner->instructor_title ?? null, ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('instructor_description', 'Description', ['class' => 'form-label required']) !!}
                                    {!! Form::text('instructor_description', $instructorBanner->instructor_description ?? null, [
                                        'class' => 'form-control',
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
                                <h4 class="card-title">Testimonial Banner</h4>
                                <hr class="form-divider">
                                <div class="col-md-12 col-sm-6 col-xs-12">
                                    {!! Form::hidden('section_name_4', 'testimonial_banner', ['class' => 'form-control']) !!}
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('heading_1', 'Heading', ['class' => 'form-label required']) !!}
                                    {!! Form::text('heading_1', $testimonialBanner->heading_1 ?? null, [
                                        'class' => 'form-control',
                                        'required',
                                        'placeholder' => 'Enter Heading',
                                    ]) !!}
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    {!! Form::label('sub_heading_1', 'Sub Heading', ['class' => 'form-label required']) !!}
                                    {!! Form::text('sub_heading_1', $testimonialBanner->sub_heading_1 ?? null, [
                                        'class' => 'form-control',
                                        'required',
                                        'placeholder' => 'Enter Sub Heading',
                                    ]) !!}
                                </div>
                            </div>
                            <div class="text-end mt-3">
                                {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
                                {!! Form::reset('Reset', ['class' => 'btn btn-secondary']) !!}
                            </div>
                        </div>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </section>
    </div>
@endsection
<script>
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
