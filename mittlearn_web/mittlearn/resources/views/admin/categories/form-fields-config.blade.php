<form method="POST" action="{{ route('category.form-fields.store') }}">
    @csrf
    <input type="hidden" name="category_id" value="{{ $category->id }}">
    <div class="row">
        <div class="col-md-2">
            <label for="field_name" class="form-label">Field Name</label>
            <input type="text" name="field_name" class="form-control" required>
        </div>
        <div class="col-md-2">
            <label for="field_label" class="form-label">Field Label</label>
            <input type="text" name="field_label" class="form-control" required>
        </div>
        <div class="col-md-2">
            <label for="field_placeholder" class="form-label">Field Placeholder</label>
            <input type="text" name="field_placeholder" class="form-control">
        </div>
        <div class="col-md-2">
            <label for="field_type" class="form-label">Field Type</label>
            <select name="field_type" class="form-select" required>
                <option value="text">Text</option>
                <option value="number">Number</option>
                {{-- <option value="boolean">Boolean</option> --}}
                <option value="date">Date</option>
                <option value="file">File</option>
                <option value="select">Select</option>
                {{-- <option value="multiselect">Multi Select</option> --}}
                <option value="textarea">Textarea</option>
            </select>
        </div>
        <div class="col-md-2">
            <label for="lookup_with" class="form-label">Lookup With</label>
            <select name="lookup_with" class="form-select">
                <option value="" selected>Select</option>
                <option value="boards">Board</option>
                <option value="mediums">Medium</option>
                <option value="book_series">Series</option>
                <option value="classes">Classes</option>
                <option value="subjects">Subjects</option>
                <option value="content_language">Content Language</option>
                <option value="status">Status</option>
                <option value="levels">Course Levels</option>
            </select>
        </div>

        <div class="col-md-2">
            <label for="is_active" class="form-label">Status</label>
            <select name="is_active" class="form-select">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12 text-end">
            <button type="submit" class="btn btn-success">Save Field</button>
        </div>
    </div>
</form>
