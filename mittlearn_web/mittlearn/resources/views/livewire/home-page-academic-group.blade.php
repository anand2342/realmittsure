<div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>S.No</th>
                <th> Group</th>
                <th>Visible Name</th>
                <th> Image <small class="form-text text-muted">(Allowed formats: PNG, SVG. Image dimensions: 600x400
                        pixels)</small></th>
                <th>Redirection Link</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if ($rows && count($rows) > 0)
                @foreach ($rows as $index => $row)
                    <tr wire:key="row-{{ $index }}">
                        <!-- Serial Number -->
                        <td>{{ $index + 1 }}</td>

                        <!-- Academic Category Field -->
                        <td>
                            {!! Form::hidden("rows[$index][id]", $row['id'] ?? null, [
                                'wire:model.defer' => "rows[$index].id",
                            ]) !!}
                            {!! Form::select("rows[$index][group_id]", $categories, $row['group_id'], [
                                'class' => 'form-control',
                                'wire:model.defer' => "rows[$index].group_id",
                                'required' => 'required',
                                'placeholder' => '--Select Banner Category Group --',
                            ]) !!}</td>
                        <td>

                            {!! Form::text("rows[$index][group_academic_title]", $row['group_academic_title'] ?? null, [
                                'class' => 'form-control',
                                'wire:model.defer' => "rows[$index].group_academic_title",
                                'required' => 'required',
                                'placeholder' => 'Please Enter Text',
                            ]) !!}
                        </td>

                        <!-- Academic Image Field -->
                        <td>
                            {!! Form::file("rows[$index][group_academic_image]", [
                                'class' => 'form-control',
                                'wire:model.defer' => "rows[$index].group_academic_image",
                            ]) !!}

                            @if (!empty($row['group_academic_image']) && is_string($row['group_academic_image']))
                                <div class="mt-2">
                                    <img src="{{ Storage::url('uploads/website-pages/academic/' . $row['group_academic_image']) }}"
                                        alt="Academic Image" width="100" height="50">
                                </div>
                            @endif
                        </td>
                        <td>
                            {!! Form::text("rows[$index][redirection_link]", $row['redirection_link'] ?? null, [
                                'class' => 'form-control',
                                'wire:model.defer' => "rows[$index].redirection_link",
                                'placeholder' => 'Please Enter Url',
                            ]) !!}
                        </td>
                        <!-- Delete Button -->
                        <td>
                            <button wire:click.prevent="removeRow({{ $index }})" type="button"
                                class="btn btn-danger btn-sm">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4" class="text-center">No academic categories found. Please add a row.</td>
                </tr>
            @endif

            <!-- Add More Row -->
            <tr>
                <td colspan="6" class="text-end">
                    <button wire:click.prevent="addRow" type="button" class="btn btn-success btn-sm">Add More</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
