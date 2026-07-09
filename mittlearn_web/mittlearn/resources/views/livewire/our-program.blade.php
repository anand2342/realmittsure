<div>
    <h6>Our Program Details</h6>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>S.No</th>
                <th> Title</th>
                <th> Image <small class="form-text text-muted">(Allowed formats: PNG, PDF, SVG. Image dimensions: 50x50
                        pixels)</small></th>
                <th> Description</th>
                <th>Know More URL</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($row as $index => $row)
                <tr wire:key="row-{{ $index }}">
                    <!-- Serial Number -->
                    <td>{{ $index + 1 }}</td>

                    <!-- Icon Title Field -->
                    <td>
                        {!! Form::hidden("row[$index][id]", $row['id'] ?? null, [
                            'wire:model.defer' => "row[$index].id",
                        ]) !!}
                        <div class="col-md-12 col-sm-6 col-xs-12">
                            {!! Form::hidden('type', 'our_program', ['class' => 'form-control']) !!}
                        </div>
                        {!! Form::text("row[$index][title]", $row['title'], [
                            'class' => 'form-control',
                            'required',
                            'wire:model.defer' => "row[$index].title",
                            'placeholder' => 'Enter  Title',
                        ]) !!}
                    </td>

                    <!-- Icon Image Field -->
                    <td>
                        {!! Form::file("row[$index][image]", [
                            'class' => 'form-control',
                            'wire:model.defer' => "row[$index].image",
                        ]) !!}
                        @if (isset($row['image']) && $row['image'])
                            <div class="mt-2">
                                <img src="{{ Storage::url('uploads/cms-about-us/our-program/' . $row['image']) }}"
                                    alt="Icon Image" width="100">
                            </div>
                        @endif
                    </td>

                    <!-- Icon Description Field -->
                    <td>
                        {!! Form::textarea("row[$index][description]", $row['description'], [
                            'class' => 'form-control',
                            'rows' => 2,
                            'wire:model.defer' => "row[$index].description",
                            'placeholder' => 'Enter  Description',
                            'required',
                        ]) !!}
                    </td>
                    <td>
                        {!! Form::textarea("row[$index][url_redirection]", $row['url_redirection'], [
                            'class' => 'form-control',
                            'rows' => 2,
                            'wire:model.defer' => "row[$index].url_redirection",
                            'placeholder' => 'Enter URL',
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
