<div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Sort Order</th>
                <th> Offering Name</th>
                <th> Image <small class="form-text text-muted">(Allowed formats: PNG, SVG. Image dimensions: 600x400
                        pixels)</small></th>
                <th>Description</th>
                <th>Redirection Link</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @if ($rows && count($rows) > 0)
                @foreach ($rows as $index => $row)
                    <tr wire:key="row-{{ $index }}">
                        <!-- Serial Number -->
                        <td>
                            {!! Form::text("rows[$index][our_offerings_sort_order]", $row['our_offerings_sort_order'] ?? null, [
                                'class' => 'form-control',
                                'wire:model.defer' => "rows[$index].our_offerings_sort_order",
                                'required' => 'required',
                                'placeholder' => 'Please Enter Sort Order',
                            ]) !!}
                        </td>

                        <!-- Academic Category Field -->
                        <td>
                            {!! Form::hidden("rows[$index][id]", $row['id'] ?? null, [
                                'wire:model.defer' => "rows[$index].id",
                            ]) !!}

                            {!! Form::text("rows[$index][our_offerings_title]", $row['our_offerings_title'] ?? null, [
                                'class' => 'form-control',
                                'wire:model.defer' => "rows[$index].our_offerings_title",
                                'required' => 'required',
                                'placeholder' => 'Please Enter Text',
                            ]) !!}
                        </td>

                        <!-- Academic Image Field -->
                        <td>
                            {!! Form::file("rows[$index][our_offerings_image]", [
                                'class' => 'form-control',
                                'wire:model.defer' => "rows[$index].our_offerings_image",
                            ]) !!}

                            @if (!empty($row['our_offerings_image']) && is_string($row['our_offerings_image']))
                                <div class="mt-2">
                                    <img src="{{ Storage::url('uploads/website-pages/our-offerings/' . $row['our_offerings_image']) }}"
                                        alt="Academic Image" width="100" height="50">
                                </div>
                            @endif
                        </td>
                        <td>
                            {!! Form::textarea("rows[$index][ourOfferings_desc]", $row['ourOfferings_desc'] ?? null, [
                                'class' => 'form-control',
                                'wire:model.defer' => "rows[$index].ourOfferings_desc",
                                'placeholder' => 'Please Enter Description',
                            ]) !!}
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
