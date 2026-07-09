<div class="col-lg-12">
    <div class="card">
        <div class="card-body row">
            <div class="col-md-9">
                <form method="GET" action="{{ route('erp-data.teachers.index') }}">
                    <div class="d-flex flex-wrap align-items-center mt-4">
                        {{-- School Dropdown --}}
                        <div class="col-md-3 me-2 mb-2">
                            <label for="schid" class="form-label">Select School</label>
                            <select name="schid" class="form-select" required>
                                <option value="" disabled {{ request('schid') ? '' : 'selected' }}>Select</option>
                                @foreach ($schools as $key => $value)
                                    <option value="{{ $value->erp_schid }}"
                                        {{ request('schid') == $value->erp_schid ? 'selected' : '' }}>
                                        {{ $value->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Name Search --}}
                        <div class="col-md-3 me-2 mb-2">
                            <label for="name" class="form-label">Search by Name</label>
                            <input type="text" name="name" value="{{ request('name') }}" class="form-control"
                                placeholder="Enter teacher name">
                        </div>

                        {{-- Buttons --}}
                        <div class="col-md-2 me-2 mb-2 mt-4">
                            <button type="submit" class="btn btn-primary">Search</button>
                            <a href="{{ route('erp-data.teachers.index') }}" class="btn btn-secondary">Clear</a>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-3 d-flex align-items-center gap-2 mt-2 mt-sm-0">
                <label for="paginationSelectOnpage" class="me-2 mb-0 text-nowrap">Per Page
                    Records:</label>
                <select id="paginationSelectOnpage" class="form-select form-select-sm" style="width: 80px;">
                    <option value="" disabled {{ session('per_page_records') ? '' : 'selected' }}>
                        --Select--</option>
                    @foreach ([10, 20, 30, 40, 50] as $option)
                        <option value="{{ $option }}"
                            {{ session('per_page_records') == $option ? 'selected' : '' }}>
                            {{ $option }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
</div>
@livewire('erp-edit-teacher', ['datalist' => $datalist])


@stack('scripts')
