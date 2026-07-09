@extends('admin.layouts.master')

@section('content')
    <div>
        <div class="pagetitle">
            <h1>Print Settings</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Print Settings</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title pb-0">Print Settings</h5>
                            <hr class="form-divider">
                            {{-- Create access code process using livewire --}}
                            {{ Form::model($settings, ['route' => 'print.setting.save', 'id' => 'edit-settings-form', 'class' => 'row g-3', 'method' => 'POST', 'enctype' => 'multipart/form-data']) }}
                            @csrf
                            {{--  @dd($settings['paper_size'])  --}}
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('paper_size', 'Paper Size', ['class' => 'form-label']) !!}
                                {{ Form::select('paper_size', config('constants.PAPER_SIZE'), $settings['paper_size'] ?? null, ['class' => 'form-control', 'placeholder' => '--select--']) }}
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('font_family', 'Font Family', ['class' => 'form-label']) !!}
                                {{ Form::select('font_family', config('constants.FONT_FAIMLY'), $settings['font_family'] ?? null, ['class' => 'form-control', 'placeholder' => '--select--']) }}
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('font_size', 'Font Size in px', ['class' => 'form-label']) !!}
                                {{ Form::number('font_size', $settings['font_size'] ?? '', ['class' => 'form-control', 'placeholder' => 'Enter Font Size in px']) }}
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('font_color', 'Font Color', ['class' => 'form-label']) !!}
                                {{ Form::select('font_color', config('constants.FONT_COLOR'), $settings['font_color'] ?? null, ['class' => 'form-control', 'placeholder' => '--select--']) }}
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('margin_from_left', 'Margin From Left In mm', ['class' => 'form-label']) !!}
                                {{ Form::number('margin_from_left', $settings['margin_from_left'] ?? '', ['class' => 'form-control', 'placeholder' => 'Enter Margin Left in mm']) }}
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('margin_from_left', 'Margin From Left In mm', ['class' => 'form-label']) !!}
                                {{ Form::number('margin_from_left', $settings['margin_from_left'] ?? '', ['class' => 'form-control', 'placeholder' => 'Enter Margin Left in mm']) }}
                            </div>
                            <div class="text-end">
                                <button type="button" id="previewButton" class="btn btn-secondary">Preview</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                    <!-- Modal -->
                    <div class="modal fade" id="previewModal" tabindex="-1" aria-labelledby="previewModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-xl modal-dialog-centered"> <!-- Center the modal -->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="previewModalLabel">Preview</h5>
                                </div>
                                <div class="modal-body text-center">
                                    <!-- A4 Size Page with Border -->
                                    <div id="previewContent"
                                        style="width: 210mm; height: 297mm; padding: 10mm; border: 1px solid #000; position: relative; margin: 0 auto;">
                                        <div id="previewText" style="position: absolute;">
                                            <!-- Dynamic preview text -->
                                            <p id="dynamicText" style="position: absolute;"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script>
        document.getElementById('previewButton').addEventListener('click', function() {
            // Get entered values
            var marginFromTop = document.querySelector('input[name="margin_from_top"]').value;
            var marginFromLeft = document.querySelector('input[name="margin_from_left"]').value;

            // Get the preview text element
            var dynamicText = document.getElementById('dynamicText');

            // Position the 'here' text according to margin values
            dynamicText.innerText = 'here';
            dynamicText.style.top = (parseInt(marginFromTop) + 10) + 'mm'; // Adjust based on margin
            dynamicText.style.left = (parseInt(marginFromLeft) + 10) + 'mm'; // Adjust based on margin

            // Show the modal
            var previewModal = new bootstrap.Modal(document.getElementById('previewModal'));
            previewModal.show();
        });
    </script>
@endsection
