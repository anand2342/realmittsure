<div>
    <h6> Core Feature Details</h6>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>S.No</th>
                <th>Icon Title</th>
                <th>Icon Image <small class="form-text text-muted">(Allowed formats: PNG, PDF, SVG. Image dimensions: 50x50 pixels)</small></th>
                <th>Icon Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rows_2 as $index => $row)
                <tr wire:key="row-{{ $index }}">
                    <!-- Serial Number -->
                    <td>{{ $index + 1 }}</td>

                    <!-- Icon Title Field -->
                    <td>
                        {!! Form::hidden("rows_2[$index][id]", $row['id'] ?? null, [
                            'wire:model.defer' => "rows_2[$index].id",
                        ]) !!}
                        <div class="col-md-12 col-sm-6 col-xs-12">
                            {!! Form::hidden('type_2', 'non_academic_feature_banner', ['class' => 'form-control']) !!}
                        </div>
                        {!! Form::text("rows_2[$index][icon_title]", $row['icon_title'], [
                            'class' => 'form-control',
                            'required',
                            'wire:model.defer' => "rows_2[$index].icon_title",
                            'placeholder' => 'Enter Icon Title',
                        ]) !!}
                    </td>

                    <!-- Icon Image Field -->
                    <td>
                        {!! Form::file("rows_2[$index][icon_image]", [
                            'class' => 'form-control',
                            'wire:model.defer' => "rows_2[$index].icon_image",
                        ]) !!}
                        @if (isset($row['icon_image']) && $row['icon_image'])
                            <div class="mt-2">
                                <img src="{{ Storage::url('uploads/website-pages/non_academic_core_icon_image/' . $row['icon_image']) }}"
                                    alt="Icon Image" width="100">
                            </div>
                        @endif
                    </td>

                    <!-- Icon Description Field -->
                    <td>
                        {!! Form::textarea("rows_2[$index][icon_description]", $row['icon_description'], [
                            'class' => 'form-control',
                            'rows' => 2,
                            'wire:model.defer' => "rows_2[$index].icon_description",
                            'placeholder' => 'Enter Icon Description',
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

            <!-- Add More Button -->
            <tr>
                <td colspan="5" class="text-right">
                    <button wire:click.prevent="addRow" type="button" class="btn btn-success btn-sm">Add More</button>
                </td>
            </tr>
        </tbody>
    </table>

</div>
