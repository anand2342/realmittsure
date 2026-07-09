<div x-data="{ selected: @entangle('selectedItems').defer }" x-init="new TomSelect($refs.select, {
    plugins: ['remove_button'],
    onChange: function(values) {
        selected = values;
    }
});">
    <label for="subjects" class="form-label">Select Subjects</label>
    <select x-ref="select" multiple class="form-select">
        @foreach ($options as $option)
            <option value="{{ $option['id'] }}">{{ $option['name'] }}</option>
        @endforeach
    </select>
</div>
