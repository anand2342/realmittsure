<div class="container mt-5">
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-1"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @elseif(session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-octagon me-1"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @elseif(session()->has('info'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-octagon me-1"></i>
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @php
        if ($type === 'daily') {
            $range = 15;
        } else {
            $range = 16;
        }
    @endphp
    <!-- Progress Bar -->
    <div class="container mt-5" x-data="{
        step: @entangle('step'), // Sync with Livewire's 'step'
        totalSteps: 17,
        nextStep() {
            if (this.step < this.totalSteps) {
                this.step++; // This will trigger Livewire to update
            }
        },
        prevStep() {
            if (this.step > 1) {
                this.step--; // This will trigger Livewire to update
            }
        }
    }">
        <!-- Progress Bar -->
        <div class="d-flex justify-content-between align-items-center mb-2">
            @for ($i = 1; $i <= $range; $i++)
                <div class="step position-relative" :class="{ 'active': step >= {{ $i }} }"
                    title="{{ $i === 1 ? 'Planner Info' : $stepTypesName[$i] ?? '' }}" style="text-align: center;">
                    <span class="step-number">{{ $i === 1 ? 'P' : $i - 1 }}</span>
                    @if ($i < $range)
                        <span class="step-title">---</span>
                    @endif
                </div>
            @endfor
        </div>
        @for ($i = 1; $i <= $range; $i++)
            @if ($i == 1)
                <div x-show="step === {{ $i }}" x-cloak>
                    <form wire:submit.prevent="save">
                        <h5 class="card-title pb-0">Planner Info - Step {{ $i }}</h5>
                        <hr class="form-divider">
                        <div class="row g-3">
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('board_id', 'Board', ['class' => 'form-label required ']) !!}
                                {!! Form::select('board_id', $boards, null, [
                                    'class' => 'form-control form-select fs-8 ',
                                    'placeholder' => '--Select--',
                                    'wire:model' => 'board_id',
                                    'wire:change' => 'getBookSeries($event.target.value, $wire.medium_id)',
                                ]) !!}
                                @error('board_id')
                                    <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('medium_id', 'Medium', ['class' => 'form-label required ']) !!}
                                {!! Form::select('medium_id', $mediums, null, [
                                    'class' => 'form-control form-select fs-8 ',
                                    'placeholder' => '--Select--',
                                    'wire:model' => 'medium_id',
                                    'wire:change' => 'getBookSeries($wire.board_id, $event.target.value)',
                                ]) !!}
                                @error('medium_id')
                                    <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('series_id', 'Series', ['class' => 'form-label required ']) !!}
                                {!! Form::select('series_id', $series, null, [
                                    'class' => 'form-control form-select fs-8 ',
                                    'placeholder' => '--Select--',
                                    'wire:model' => 'series_id',
                                    'wire:change' => 'getSeriesId($event.target.value)',
                                ]) !!}
                                @error('series_id')
                                    <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('class_id', 'Class', ['class' => 'form-label required ']) !!}
                                {!! Form::select('class_id', $class, null, [
                                    'class' => 'form-control form-select fs-8 ',
                                    'placeholder' => '--Select--',
                                    'wire:model' => 'class_id',
                                    'wire:change' => 'getSubjectsByClass($event.target.value)',
                                ]) !!}
                                @error('class_id')
                                    <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col-md-12 col-sm-6 col-xs-12">
                                {!! Form::label('type', 'Planner Type', ['class' => 'form-label required ']) !!}
                                <div class="row mt-3">
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            {!! Form::label('type', 'Daily', ['class' => 'form-check-label']) !!}
                                            {!! Form::radio('type', 'daily', false, [
                                                'class' => 'form-check-input',
                                                'wire:change' => 'updateType',
                                                'wire:model' => 'type',
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            {!! Form::label('type', 'Weekly', ['class' => 'form-check-label']) !!}
                                            {!! Form::radio('type', 'weekly', false, [
                                                'class' => 'form-check-input',
                                                'wire:change' => 'updateType',
                                                'wire:model' => 'type',
                                            ]) !!}
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            {!! Form::label('type', 'Monthly', ['class' => 'form-check-label']) !!}
                                            {!! Form::radio('type', 'monthly', false, [
                                                'class' => 'form-check-input',
                                                'wire:change' => 'updateType',
                                                'wire:model' => 'type',
                                            ]) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('subject_id', 'Subject', ['class' => 'form-label required ']) !!}
                                {!! Form::select('subject_id', $subject, null, [
                                    'class' => 'form-control form-select fs-8 ',
                                    'placeholder' => '--Select--',
                                    'wire:model' => 'subject_id',
                                    'wire:change' => 'updateChapterName',
                                ]) !!}
                                @error('subject_id')
                                    <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                                @enderror
                            </div>


                            <!-- Chapter Single Select -->
                            <div class="col-md-6 col-sm-6 col-xs-12"
                                style="display: {{ $type == 'daily' ? 'block' : 'none' }};">
                                {!! Form::label('chapter_id', 'Chapter Title', ['class' => 'form-label required']) !!}
                                {!! Form::select('chapter_id', $chapters, null, [
                                    'class' => 'form-control form-select fs-8',
                                    'placeholder' => '--Select--',
                                    'wire:model' => 'chapter_id',
                                ]) !!}
                                @error('chapter_id')
                                    <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Chapter Multi Select -->
                            <div class="col-md-6 col-sm-6 col-xs-12"
                                style="display: {{ $type != 'daily' ? 'block' : 'none' }};">
                                <div class="" wire:ignore>
                                    {!! Form::label('chapter_ids', 'Chapter Title', ['class' => 'form-label required']) !!}
                                    {!! Form::select('chapter_ids[]', [], null, [
                                        'class' => 'form-control form-select fs-8 js-select2',
                                        'placeholder' => '--Select--',
                                        'wire:model' => 'chapter_ids',
                                        'id' => 'chapter_select',
                                        'multiple' => 'multiple',
                                    ]) !!}
                                    @error('chapter_ids')
                                        <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            @foreach ($this->planners as $index => $planner)
                                {{-- Batch --}}
                                <div class="col-md-6">
                                    {!! Form::label("planners[$index][academic_session_id]", 'Academic Session', ['class' => 'form-label required']) !!}
                                    {!! Form::select("planners[$index][academic_session_id]", $academicSession, $planner['academic_session_id'], [
                                        'class' => 'form-control form-select fs-8',
                                        'placeholder' => '--Select--',
                                        'wire:model' => "planners.$index.academic_session_id",
                                        'wire:change' => "batchUpdate($index)",
                                    ]) !!}
                                    @error("planners.$index.academic_session_id")
                                        <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    {!! Form::label("planners[$index][batch_id]", 'Batches', ['class' => 'form-label required']) !!}
                                    {!! Form::select("planners[$index][batch_id]", $planner['batches'] ?? [], $planner['batch_id'], [
                                        'class' => 'form-control form-select fs-8',
                                        'placeholder' => '--Select--',
                                        'wire:model' => "planners.$index.batch_id",
                                        'wire:change' => "batchDate($index)",
                                    ]) !!}
                                    @error("planners.$index.batch_id")
                                        <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                                    @enderror
                                </div>


                                {{-- Allotted Days --}}
                                <div class="col-md-6">
                                    {!! Form::label("planners[$index][allotted_days]", 'Allotted Days', ['class' => 'form-label required']) !!}
                                    {!! Form::number("planners[$index][allotted_days]", $planner['allotted_days'], [
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter Allotted Days',
                                        'wire:model' => "planners.$index.allotted_days",
                                    ]) !!}
                                    @error("planners.$index.allotted_days")
                                        <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Start Date --}}
                                <div class="col-md-6">
                                    {!! Form::label("planners[$index][start_date]", 'Start Date', ['class' => 'form-label required']) !!}
                                    {!! Form::date("planners[$index][start_date]", $planner['start_date'], [
                                        'class' => 'form-control',
                                        'wire:model' => "planners.$index.start_date",
                                        'wire:change' => "updateCompletionDate($index)",
                                    ]) !!}
                                    @error("planners.$index.start_date")
                                        <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Completion Date --}}
                                <div class="col-md-6">
                                    {!! Form::label("planners[$index][completion_date]", 'Completion Date', ['class' => 'form-label required']) !!}
                                    <span class="text-info"
                                        title="Note: This is not a fixed completion date. It skips Sundays only.">
                                        <i class="bi bi-exclamation-circle-fill"></i>
                                    </span>
                                    {!! Form::date("planners[$index][completion_date]", $planner['completion_date'], [
                                        'class' => 'form-control',
                                        'wire:model' => "planners.$index.completion_date",
                                        'readonly' => 'readonly',
                                    ]) !!}
                                    @error("planners.$index.completion_date")
                                        <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Total Periods --}}
                                <div class="col-md-6">
                                    {!! Form::label("planners[$index][total_periods]", 'Total Periods', ['class' => 'form-label required']) !!}
                                    {!! Form::number("planners[$index][total_periods]", $planner['total_periods'], [
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter Total Periods',
                                        'wire:model' => "planners.$index.total_periods",
                                    ]) !!}
                                    @error("planners.$index.total_periods")
                                        <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                                    @enderror
                                </div>
                                {{-- Remove Button --}}
                                @if ($index != 0)
                                    <div class="col-md-12 text-end">
                                        @if (count($this->planners) > 1)
                                            <button class="btn btn-danger btn-sm mt-2" type="button"
                                                wire:click="removePlanner({{ $index }})">Remove</button>
                                        @endif
                                    </div>
                                @endif
                                <hr>
                            @endforeach

                            {{-- Add More Button --}}
                            <div class="text-end mt-3">
                                <button class="btn btn-primary" type="button" wire:click="addPlanner">Add
                                    More Batch</button>
                            </div>

                            <div class="col-sm-12 text-end  mt-3">
                                <div x-data="{ step: @entangle('step') }">
                                    <button type="button" class="btn btn-primary" wire:click="nextStep"
                                        x-show="step <= totalSteps">
                                        Next
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
            @if (in_array($i, [2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17]))
                @if ($i === 16 && ($type === 'weekly' || $type === 'monthly'))
                    <div x-show="step === {{ $i }}" x-cloak>
                        <form wire:submit.prevent="save">
                            <h5 class="card-title pb-0">{{ $stepTypesName[$i] }} - {{ $i }}</h5>
                            <hr class="form-divider">
                            <div class="row">
                                <!-- Add your custom fields for the 'event_function' step here -->
                                @if ($selectedBatch != null)
                                    @foreach ($selectedBatch as $index => $data)
                                        <div class="col-12 mb-3">
                                            <div class="border rounded p-3 bg-light">
                                                <div class="row">
                                                    <div class="col-md-12 col-sm-6 col-xs-12 mb-2">
                                                        {!! Form::label('academic_session_id' . $index, 'Academic Session', ['class' => 'form-label']) !!}
                                                        {!! Form::text('academic_session_id' . $index, $data->name, [
                                                            'class' => 'form-control',
                                                            'readonly' => 'readonly',
                                                            'placeholder' => 'Enter Batch Name',
                                                        ]) !!}
                                                    </div>
                                                    <div class="col-md-12 col-sm-6 col-xs-12 mb-2">
                                                        {!! Form::label('batch' . $index, 'Batch', ['class' => 'form-label']) !!}
                                                        {!! Form::text('batch' . $index, $data->batch_name, [
                                                            'class' => 'form-control',
                                                            'readonly' => 'readonly',
                                                            'placeholder' => 'Enter Batch Name',
                                                        ]) !!}
                                                    </div>

                                                    <div class="col-md-6 col-sm-6 col-xs-12 mb-2">
                                                        {!! Form::label("event_title. {$index}", 'Event Title', ['class' => 'form-label']) !!}
                                                        {!! Form::text("event_title.{$index}", null, [
                                                            'class' => 'form-control',
                                                            'wire:model' => "event_title.{$index}",
                                                            'placeholder' => 'Enter Event Title',
                                                        ]) !!}
                                                        @error('event_title.' . $index)
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>

                                                    <div class="col-md-6 col-sm-6 col-xs-12 mb-2" wire:ignore
                                                        x-data="quillEditor('event_description.{$index}', @js($row['event_description'] ?? ''))" x-init="init">
                                                        {!! Form::label("event_description.{$index}", 'Event Description', ['class' => 'form-label']) !!}
                                                        <div x-ref="editor" style="height: 150px;"></div>
                                                        <input type="hidden"
                                                            name="event_description.{{ $index }}"
                                                            wire:model="event_description.{{ $index }}"
                                                            required>
                                                        @error('event_description.' . $index)
                                                            <span class="text-danger">{{ $message }}</span>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endif
                                <div class="text-end mt-3 d-flex justify-content-end g-3">
                                    <div x-data="{ step: @entangle('step') }">
                                        <button type="button" class="btn btn-primary" wire:click="nextStep"
                                            x-show="step <= totalSteps">
                                            Next
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                @else
                    {{-- Render other steps as usual --}}
                    <div x-show="step === {{ $i }}" x-cloak>
                        <form wire:submit.prevent="save">
                            <h5 class="card-title pb-0">{{ $stepTypesName[$i] }} - {{ $i }}</h5>
                            <hr class="form-divider">
                            <div class="row">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Title</th>
                                            <th>Image</th>
                                            <th>Description</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rows_1 as $index => $row)
                                            <tr wire:key="row-{{ $index }}">
                                                <!-- Serial Number -->
                                                <td>{{ $index + 1 }}</td>

                                                <!-- Title Field -->
                                                <td>
                                                    {!! Form::text("rows_1[$index][title]", $row['title'], [
                                                        'class' => 'form-control',
                                                        'required',
                                                        'wire:model.defer' => "rows_1.$index.title",
                                                        'placeholder' => 'Enter Icon Title',
                                                    ]) !!}
                                                    @error("rows_1.$index.title")
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </td>

                                                <!-- Image Field -->
                                                <td>
                                                    {!! Form::file("rows_1[$index][image]", [
                                                        'class' => 'form-control',
                                                        'wire:model.defer' => "rows_1.$index.image",
                                                    ]) !!}
                                                    @error("rows_1.$index.image")
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </td>

                                                <!-- Description Field -->
                                                <td wire:key="row-{{ $index }}-desc">
                                                    <div wire:ignore x-data="quillEditor('rows_{{ $index }}_description', @js($row['description'] ?? ''))" x-init="init">
                                                        <div x-ref="editor" style="height: 150px;"></div>
                                                        <input type="hidden"
                                                            name="rows_1[{{ $index }}][description]"
                                                            wire:model="rows_1.{{ $index }}.description"
                                                            required>
                                                    </div>
                                                    @error("rows_1.$index.description")
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                                <!-- Delete Button -->
                                                <td>
                                                    @if ($index != 0)
                                                        <button wire:click.prevent="removeRow({{ $index }})"
                                                            type="button" class="btn btn-danger btn-sm">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach

                                        <!-- Add More Button -->
                                        <tr>
                                            <td colspan="5" class="text-right">
                                                <button wire:click.prevent="addRow" type="button"
                                                    class="btn btn-success btn-sm">Add More</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <div class="text-end mt-3 d-flex justify-content-end g-3">
                                    {{--  <button type="button" class="btn btn-secondary me-2" x-show="step > 1"
                                    x-on:click="prevStep()">Back</button>  --}}

                                    <div x-data="{ step: @entangle('step') }">
                                        <button type="button" class="btn btn-primary" wire:click="nextStep"
                                            x-show="step <= totalSteps">
                                            Next
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                @endif
            @endif
        @endfor
    </div>
</div>
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $(".js-select2").select2({
                closeOnSelect: false,
                placeholder: "Select",
                allowClear: false,
                tags: true
            });
            $(".js-select2").on('change', function(e) {
                let selectedValues = $(this).val();
                @this.set('chapter_ids', selectedValues); // Sync with Livewire
            });

            // Fetch options dynamically based on changes in the dependent fields
            $(document).on('change', '#board_id, #medium_id, #series_id, #class_id, #subject_id', function() {
                var boardId = $('#board_id').val();
                var mediumId = $('#medium_id').val();
                var seriesId = $('#series_id').val();
                var classId = $('#class_id').val();
                var subjectId = $('#subject_id').val();

                if (boardId && mediumId && seriesId && classId && subjectId) {
                    $.ajax({
                        url: '{{ route('planner.get.chapters') }}',
                        type: 'GET',
                        data: {
                            board_id: boardId,
                            medium_id: mediumId,
                            series_id: seriesId,
                            class_id: classId,
                            subject_id: subjectId,
                        },
                        success: function(response) {
                            // Clear existing options and populate with new ones
                            $('#chapter_select').empty();
                            $('#chapter_select').append('<option value="">--Select--</option>');

                            $.each(response, function(chapterId, chapterTitle) {
                                $('#chapter_select').append(
                                    '<option value="' + chapterId + '">' +
                                    chapterTitle + '</option>'
                                );
                            });

                            // Reinitialize Select2 after updating options
                            $('#chapter_select').trigger('change');
                        },
                        error: function(xhr, status, error) {},
                    });
                }
            });
        });
    </script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('quillEditor', (name, initialContent) => ({
                quill: null,
                content: initialContent,
                instanceKey: null,

                init() {
                    this.instanceKey = `quill-${name}-${Math.random().toString(36).substr(2, 9)}`;

                    this.$nextTick(() => {
                        if (!this.quill) {
                            this.quill = new Quill(this.$refs.editor, {
                                modules: {
                                    toolbar: [
                                        ["bold", "italic", "underline"],
                                        [{
                                            "script": "super"
                                        }, {
                                            "script": "sub"
                                        }],
                                        ["image"]
                                    ]
                                },
                                theme: "snow"
                            });

                            // Set initial content
                            if (this.content) {
                                this.quill.root.innerHTML = this.content;
                            }

                            // Update Livewire on content change
                            this.quill.on('text-change', () => {
                                const html = this.quill.root.innerHTML;
                                this.$el.querySelector('input[type="hidden"]').value =
                                    html;
                                // Manually trigger Livewire update
                                this.$el.querySelector('input[type="hidden"]')
                                    .dispatchEvent(new Event('input'));
                            });
                        }
                    });
                },

                destroy() {
                    if (this.quill) {
                        this.quill.off('text-change');
                        this.quill = null;
                    }
                }
            }));
        });
    </script>
@endpush
