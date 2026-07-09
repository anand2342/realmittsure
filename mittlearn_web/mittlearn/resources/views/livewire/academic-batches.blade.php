<div>
    @foreach ($batches as $index => $batch)
        <div class="row mt-3 align-items-end border-bottom pb-3" wire:key="batch-{{ $index }}">
            <div class="col-md-4">
                {!! Form::label("batches[$index][batch_name]", 'Batch Name', ['class' => 'form-label required']) !!}
                {!! Form::text("batches[$index][batch_name]", $batch['batch_name'], [
                    'class' => 'form-control',
                    'placeholder' => 'Enter Batch Name',
                    'wire:model' => "batches.$index.batch_name",
                ]) !!}
                @error("batches.$index.batch_name")
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-md-3">
                {!! Form::label("batches[$index][start_date]", 'Start Date', ['class' => 'form-label required']) !!}
                {!! Form::month("batches[$index][start_date]", $batch['start_date'], [
                    'class' => 'form-control',
                    'wire:model' => "batches.$index.start_date",
                ]) !!}
                @error("batches.$index.start_date")
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-md-3">
                {!! Form::label("batches[$index][end_date]", 'End Date', ['class' => 'form-label required']) !!}
                {!! Form::month("batches[$index][end_date]", $batch['end_date'], [
                    'class' => 'form-control',
                    'wire:model' => "batches.$index.end_date",
                ]) !!}
                @error("batches.$index.end_date")
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-md-2">
                @if ($index > 0)
                    <button type="button" class="btn btn-danger" wire:click="removeBatch({{ $index }})">
                        Remove
                    </button>
                @endif
            </div>
        </div>
    @endforeach

    <div class="mt-3">
        <button type="button" class="btn btn-success" wire:click="addBatch">
            Add More
        </button>
    </div>
</div>
