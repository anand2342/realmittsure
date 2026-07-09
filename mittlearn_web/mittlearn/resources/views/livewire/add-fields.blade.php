<div>
    <!-- Fields Container -->
    <hr class="form-divider">
    @foreach ($fields as $index => $field)
        <div class="row mb-3 border-bottom pb-3 field-row mt-3" wire:key="field-{{ $index }}">

            <div class="col-md-1">
                <label class="form-label">Sort Order</label>
                <input type="number" name="new_fields[{{ $index }}][sort_order]"
                    wire:model="fields.{{ $index }}.sort_order" class="form-control" min="0"
                    value="{{ $field['sort_order'] ?? 0 }}" required>
            </div>

            <div class="col-md-2">
                <label class="form-label">Field Name</label>
                <input type="text" name="new_fields[{{ $index }}][field_name]"
                    wire:model="fields.{{ $index }}.field_name"
                    class="form-control @error('fields.' . $index . '.field_name') is-invalid @enderror" required>
                @error('fields.' . $index . '.field_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-2">
                <label class="form-label">Field Label</label>
                <input type="text" name="new_fields[{{ $index }}][field_label]"
                    wire:model="fields.{{ $index }}.field_label"
                    class="form-control @error('fields.' . $index . '.field_label') is-invalid @enderror" required>
                @error('fields.' . $index . '.field_label')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-2">
                <label class="form-label">Placeholder</label>
                <input type="text" name="new_fields[{{ $index }}][field_placeholder]"
                    wire:model="fields.{{ $index }}.field_placeholder" class="form-control">
            </div>

            <div class="col-md-2">
                <label class="form-label">Field Type</label>
                <select name="new_fields[{{ $index }}][field_type]"
                    wire:model="fields.{{ $index }}.field_type"
                    class="form-select @error('fields.' . $index . '.field_type') is-invalid @enderror" required>
                    <option value="text">Text</option>
                    <option value="number">Number</option>
                    <option value="date">Date</option>
                    <option value="file">File</option>
                    <option value="textarea">Textarea</option>
                </select>
                @error('fields.' . $index . '.field_type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-2">
                <label class="form-label">Status</label>
                <select name="new_fields[{{ $index }}][is_active]"
                    wire:model="fields.{{ $index }}.is_active" class="form-select">
                    <option value="1">Active</option>
                    <option value="0">Inactive</option>
                </select>
            </div>

            <div class="col-md-1 d-flex align-items-end">
                @if ($index >= 0)
                    <button type="button" wire:click="removeField({{ $index }})" class="btn btn-danger btn-sm">
                        Remove
                    </button>
                @endif
            </div>
        </div>
    @endforeach

    <div class="text-end mb-3 mt-2">
        <button class="btn btn-sm btn-primary me-2" wire:click="addField" type="button">
            Add Another Field </button>
    </div>
</div>
