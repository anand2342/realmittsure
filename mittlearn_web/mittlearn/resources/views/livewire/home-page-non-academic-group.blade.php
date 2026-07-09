<div>
    <!-- Loop through rows to display inputs -->
    @foreach ($rows as $index => $row)
        <div class="row g-3 mb-3" wire:key="row-{{ $index }}">
            <!-- Non-Academic Category Dropdown -->
            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::label("rows.$index.group_non_academic_title", 'Talent & Skills Category', [
                    'class' => 'form-label required',
                ]) !!}
                {!! Form::select(
                    "rows.$index.group_non_academic_title",
                    $nonAcademicCategory,
                    $row['group_non_academic_title'],
                    [
                        'class' => 'form-control',
                        'required',
                        'placeholder' => '--select--',
                        'wire:model.defer' => "rows.$index.group_non_academic_title", // Livewire binding
                    ],
                ) !!}
            </div>

            <!-- Non-Academic Image Field -->
            <div class="col-md-6 col-sm-6 col-xs-12">
                <!-- Non-Academic Image Field -->
                <div class="col-md-6">
                    {!! Form::label("rows.$index.group_non_academic_image", 'Talent & Skills Image', ['class' => 'form-label']) !!}
                    {!! Form::file("rows.$index.group_non_academic_image", [
                        'class' => 'form-control',
                        'wire:model.defer' => "rows.$index.group_non_academic_image",
                    ]) !!}

                    @if (isset($row['group_non_academic_image']) && $row['group_non_academic_image'])
                        <!-- Display the uploaded image if exists -->
                        <div class="mt-2">
                            <img src="{{ Storage::url('uploads/website-pages/non-academic/' . $row['group_non_academic_image']) }}"
                                alt="Talent & Skill Image" width="200" height="100">
                        </div>
                    @endif
                </div>
            </div>


            <!-- Delete Button -->
            <div class="col-md-12 text-end mt-2">
                <button wire:click.prevent="removeRow({{ $index }})" type="button"
                    class="btn btn-danger btn-sm">
                    Delete
                </button>
            </div>
        </div>
    @endforeach

    <!-- Add More Button -->
    <div class="text-right mt-3">
        <button wire:click.prevent="addRow" type="button" class="btn btn-success btn-sm">Add More</button>
    </div>
</div>
