@extends('admin.layouts.master')
@section('content')
    <div id="page-header" class="page-header">
        <section class="section">
            <div class="pagetitle">
                <h1>Our Offerings</h1>
                <nav>
                    <ol class="breadcrumb">
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="text-end mb-2">
                    <a href="{{ route('dashboard') }}" class="btn btn-primary"><i class="ri-arrow-left-line"></i></a>
                </div>
                {{ Form::open(['url' => route('our.offerings.save'), 'method' => 'post', 'files' => true]) }}
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class= "row g-3">
                                <h4 class="card-title">Our Offerings & Images</h4>
                                <hr class="form-divider">
                                <div class="col-md-12 col-sm-6 col-xs-12">
                                    {!! Form::hidden('section_name_1', 'our_offerings', ['class' => 'form-control']) !!}
                                </div>
                                {{-- <h6>Our Offerings & Images</h6> --}}
                                @livewire('our-offerings', ['ourOfferingsAddtional' => $ourOfferingsAddtional])
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-end mt-3">
                    {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
                    {!! Form::reset('Reset', ['class' => 'btn btn-secondary']) !!}
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
