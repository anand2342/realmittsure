<div>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-0">School Assigned Classes</h5>
            <hr class="form-divider">

            {{ Form::model($id, ['url' => route('school.assigned.class.update'), 'id' => 'edit-plan-form', 'class' => 'row g-3', 'enctype' => 'multipart/form-data']) }}
            {{ Form::hidden('id', $id) }}

            <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group bginput mb-3">
                        {!! Form::label('class', 'Assign Classes', ['class' => 'form-label required']) !!}
                        <select name="class[]" class="js-select2 form-select" multiple="multiple" placeholder="Select">
                            @foreach ($allClasses as $iD => $name)
                                <option value="{{ $iD }}" @if (in_array($iD, $assignedClasses)) selected @endif>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12 col-md-12 col-xs-12">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>

            {{ Form::close() }}
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            {{ Form::open(['url' => route('school.assign.digital.content.save'), 'id' => 'add-plan-form', 'class' => 'row g-3']) }}
            {{ Form::hidden('school_id', $id) }}
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-0">Digital Content Assignment</h5>
                    <hr class="form-divider">

                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th width="25%">Class</th>
                                <th width="22%">BookSeries</th>
                                <th width="48%">Subject</th>
                                <th width="5%">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($classes as $classId => $className)
                                {{ Form::hidden('id[' . $classId . ']', $existingData[$classId]['id'] ?? '') }}
                                <tr>
                                    <td width="25%">
                                        {{ Form::text('class_name[' . $classId . ']', $className ?? 'N/A', ['class' => 'form-control', 'readonly' => 'readonly']) }}
                                        {{ Form::hidden('class_id[' . $classId . ']', $classId) }}
                                    </td>

                                    <td colspan="3">
                                        <table class="table">
                                            @foreach ($rows[$classId] as $index => $row)
                                                <tr wire:key="row-{{ $classId }}-{{ $index }}">
                                                    <td width="25%">
                                                        {{ Form::select("series_id[$classId][$index]", $bookSeries, $row['series_id'], [
                                                            'class' => 'form-select ',
                                                            'placeholder' => '--Select--',
                                                            'wire:model' => "rows.$classId.$index.series_id",
                                                            'wire:change' => "fetchSubjects($classId, \$event.target.value, $index)", // Pass index here
                                                        ]) }}
                                                    </td>
                                                    <td width="45%">
                                                        <select x-data="select2" class="js-select2 form-select"
                                                            multiple="multiple" placeholder="Select"
                                                            name="subject[{{ $classId }}][{{ $index }}][]"
                                                            wire:model="rows.{{ $classId }}.{{ $index }}.subject_ids">
                                                            @foreach ($subjects[$classId][$index] ?? [] as $id => $name)
                                                                <option value="{{ $id }}"
                                                                    {{ in_array($id, $row['subject_ids']) ? 'selected' : '' }}>
                                                                    {{ $name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </td>

                                                    <td width="5%">
                                                        @if ($index != 0)
                                                            <button type="button"
                                                                wire:click="removeRow({{ $classId }}, {{ $index }})"
                                                                class="btn btn-danger btn-sm">
                                                                <i class="fa fa-trash"></i>
                                                            </button>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach

                                        </table>

                                        <div class="text-end">
                                            <button type="button" wire:click="addRow({{ $classId }})"
                                                class="btn btn-primary btn-sm">
                                                Add More
                                            </button>
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <hr class="form-divider">

                    <div class="col-sm-12 text-end">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="reset" class="btn btn-secondary"
                            onclick="window.location.reload();">Reset</button>
                    </div>
                </div>
            </div>

            {{ Form::close() }}
        </div>
    </div>

</div>
