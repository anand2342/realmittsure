{{--
    PARTIAL VIEW — AJAX only. Do NOT extend any layout.
    Loaded into #assignModalBody by openAssignModal() JS function.
    NOTE: No <script> here — Select2 is initialized by initModalSelect2() in index blade.
--}}

<form id="assignSchoolsForm"
    method="POST"
    action="{{ route('teacher.development.saveAssignedSchools', $content->id) }}">
    @csrf

    <p class="text-muted mb-3">
        Assigning schools for: <strong>{{ $content->title }}</strong>
    </p>

    {{-- ALL SCHOOLS TOGGLE --}}
    <div class="mb-3 form-check form-switch">
        <input class="form-check-input" type="checkbox" id="allSchools"
            name="is_for_all" value="1"
            {{ $content->is_for_all_schools ? 'checked' : '' }}>
        <label class="form-check-label fw-semibold" for="allSchools">
            Available for All Schools
        </label>
    </div>

    {{-- SELECT2 MULTISELECT --}}
    <div id="schoolList">
        <label class="form-label fw-bold mb-2">Select Individual Schools</label>
        <select name="school_ids[]"
                id="modalSchoolSelect"
                class="form-select"
                multiple
                style="width: 100%;">
            @forelse ($schools as $id => $name)
                <option value="{{ $id }}"
                    {{ $content->schools->pluck('id')->contains($id) ? 'selected' : '' }}>
                    {{ $name }}
                </option>
            @empty
            @endforelse
        </select>
        @if ($schools->isEmpty())
            <p class="text-muted mt-2 mb-0">No schools available.</p>
        @endif
    </div>

    {{-- FOOTER BUTTONS --}}
    <div class="mt-4 d-flex justify-content-end gap-2">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            Cancel
        </button>
        <button type="submit" class="btn btn-primary">
            Save
        </button>
    </div>

</form>