<div>
    @foreach ($planners as $index => $planner)
        <hr>
        <div class="row mb-3">

            {{-- Academic Session --}}
            <div class="col-md-6">
                {!! Form::label("planners.{$index}.academic_session_id", 'Academic Session', ['class' => 'form-label required']) !!}
                {!! Form::select("planners.{$index}.academic_session_id", $academicSession, null, [
                    'wire:model' => "planners.{$index}.academic_session_id",
                    'wire:change' => "batchUpdate($index)",
                    'class' => 'form-control form-select fs-8',
                    'placeholder' => '--Select--',
                ]) !!}
                @error("planners.$index.academic_session_id")
                    <span class="text-danger fs-8">{{ $message }}</span>
                @enderror
            </div>

            {{-- Batch --}}
            <div class="col-md-6">
                {!! Form::label("planners.{$index}.batch_id", 'Batches', ['class' => 'form-label required']) !!}
                {!! Form::select("planners.{$index}.batch_id", $planner['batches'], null, [
                    'wire:model' => "planners.{$index}.batch_id",
                    'wire:change' => "batchDate($index)",
                    'class' => 'form-control form-select fs-8',
                    'placeholder' => '--Select--',
                ]) !!}
                @error("planners.$index.batch_id")
                    <span class="text-danger fs-8">{{ $message }}</span>
                @enderror
            </div>

            {{-- Allotted Days --}}
            <div class="col-md-6 mt-2">
                {!! Form::label("planners.{$index}.allotted_days", 'Allotted Days', ['class' => 'form-label required']) !!}
                {!! Form::number("planners.{$index}.allotted_days", null, [
                    'wire:model' => "planners.{$index}.allotted_days",
                    'class' => 'form-control',
                    'placeholder' => 'Enter Allotted Days',
                    'readonly' => $readonly,
                ]) !!}
                @error("planners.$index.allotted_days")
                    <span class="text-danger fs-8">{{ $message }}</span>
                @enderror
            </div>

            {{-- Total Periods --}}
            <div class="col-md-6 mt-2">
                {!! Form::label("planners.{$index}.total_periods", 'Total Periods', ['class' => 'form-label required']) !!}
                {!! Form::number("planners.{$index}.total_periods", null, [
                    'wire:model' => "planners.{$index}.total_periods",
                    'class' => 'form-control',
                    'placeholder' => 'Enter Total Periods',
                ]) !!}
                @error("planners.$index.total_periods")
                    <span class="text-danger fs-8">{{ $message }}</span>
                @enderror
            </div>

            {{-- Start Date --}}
            <div class="col-md-6 mt-2">
                {!! Form::label("planners.{$index}.start_date", 'Start Date', ['class' => 'form-label required']) !!}
                {!! Form::date("planners.{$index}.start_date", null, [
                    'wire:model' => "planners.{$index}.start_date",
                    'wire:change' => "updateCompletionDate($index)",
                    'class' => 'form-control',
                ]) !!}
                @error("planners.$index.start_date")
                    <span class="text-danger fs-8">{{ $message }}</span>
                @enderror
            </div>

            {{-- Completion Date --}}
            <div class="col-md-6 mt-2">
                {!! Form::label("planners.{$index}.completion_date", 'Completion Date', ['class' => 'form-label required']) !!}
                <span class="text-info" data-bs-toggle="tooltip" title="Note: This skips Sundays only.">
                    <i class="bi bi-exclamation-circle-fill"></i>
                </span>
                {!! Form::date("planners.{$index}.completion_date", null, [
                    'wire:model' => "planners.{$index}.completion_date",
                    'class' => 'form-control',
                    'readonly' => true,
                ]) !!}
                @error("planners.$index.completion_date")
                    <span class="text-danger fs-8">{{ $message }}</span>
                @enderror
            </div>

            {{-- Remove Button for dynamic planners only --}}
            @if ($planner['is_main'] != true)
                <div class="col-12 mt-2 text-end">
                    <button wire:click.prevent="removePlanner({{ $index }})" type="button"
                        class="btn btn-danger btn-sm">
                        Remove
                    </button>
                </div>
            @endif

        </div>
    @endforeach

    {{-- Add More Button --}}
    <div class="text-end">
        <button wire:click.prevent="addPlanner" type="button" class="btn btn-primary btn-sm mb-3">
            Add More
        </button>
    </div>
</div>

