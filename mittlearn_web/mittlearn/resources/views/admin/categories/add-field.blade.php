@extends('admin.layouts.master')
@section('content')
    <div id="page-header" class="page-header">
        <section class="section">
            <div class="pagetitle">
                <h1>{{ $categories->name }} Form Fields</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Home</li>
                        <li class="breadcrumb-item active">{{ $categories->name }} Form Fields</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">Choose {{ $categories->name }} Course Form Fields </h4>
                            <hr class="form-divider">
                            <div class="tbleDiv">
                                {{ Form::open(['route' => 'category.form-fields.store', 'method' => 'POST', 'id' => 'fields-form']) }}

                                <table id="datatable" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>
                                                All <input type="checkbox" id="select-all">
                                            </th>
                                            <th width="10%">Sort Order</th>
                                            <th>Field Label</th>
                                            <th>Field Name</th>
                                            <th>Field Type</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($metaDataFields as $field)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="row-checkbox" name="selected_fields[]"
                                                        value="{{ $field->id }}"
                                                        @if ($existingTemplateFields->contains('field_label', $field->field_label)) checked @endif>
                                                </td>
                                                <td width="10%">
                                                    <input type="number" class="form-control form-control-sm"
                                                        name="sort_order[{{ $field->id }}]"
                                                        value="{{ $existingTemplateFields->firstWhere('field_label', $field->field_label)?->sort_order ?? 0 }}"
                                                        min="0">
                                                </td>
                                                <td>{{ $field->field_label }}</td>
                                                <td>{{ $field->field_name }}</td>
                                                <td>{{ $field->field_type }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <!-- Hidden fields for additional data -->
                                {{ Form::hidden('category_id', $categories->id) }}

                                <!-- Add Fields Component -->
                                {{-- @livewire('add-fields', ['categoryId' => $categories->id]) --}}
                                @livewire('add-fields', [
                                    'categoryId' => $categories->id,
                                    'existingCustom ' => $existingCustomFields,
                                ])

                                <!-- Form Actions -->
                                <div class="mt-5">
                                    {{ Form::submit('Save Selected Fields', ['class' => 'btn btn-primary text-end']) }}
                                    <button type="reset" class="btn btn-secondary">Reset</button>
                                </div>

                                {{ Form::close() }}
                            </div>

                        </div>
                    </div>
                </div>
        </section>
    </div>

    <style>
        th:first-child,
        td:first-child {
            width: 40px;
            text-align: center;
        }
    </style>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const selectAll = document.getElementById('select-all');
            const checkboxes = document.querySelectorAll('.row-checkbox');

            selectAll.addEventListener('change', function() {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = selectAll.checked;
                });
            });
        });
    </script>
    <script>
        // Select all checkboxes
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Livewire event listener to sync with form
        document.addEventListener('livewire:load', function() {
            Livewire.on('fieldsAdded', () => {
                // Refresh the table or form after fields are added
                window.location.reload();
            });
        });
    </script>
@endpush
