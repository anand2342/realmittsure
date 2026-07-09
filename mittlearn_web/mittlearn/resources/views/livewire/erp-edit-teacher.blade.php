<div class="table-responsive tbleDiv">
    <form id="teacherEditForm" method="POST" action="{{ route('erp-data.save.teachers') }}">
        @csrf
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th><b>Name</b></th>
                    <th><b>Mobile</b></th>
                    <th><b>Password</b></th>
                    <th><b>Assigned Classes</b></th>
                    <th><b>Assigned Subjects</b></th>
                    <th><b>Status</b></th>
                    <th><b>Action</b></th>
                </tr>
            </thead>

            <tbody>
                @foreach ($teachers as $index => $item)
                    {{-- @dd($item) --}}
                    @php
                        $isExisitsInLMS = \App\Models\User::whereNotNull('erp_db_id')
                            ->where('erp_db_id', $item->id)
                            ->exists();
                    @endphp
                    @if ($editingId === $item->id)
                        @csrf

                        <input type="hidden" name="erp_id" value="{{ $item->id }}">
                        <input type="hidden" name="schid" value="{{ $item->schid }}">
                        <tr>
      						  <td>{{ (($currentPage - 1) * $perPage) + $index + 1 }}.</td>
                            <td class="col-md-2 col-sm-6 col-xs-12">
                                <input type="text" name="name" value="{{ $item->fname }}" class="form-control">
                            </td>
                            <td class="col-md-2 col-sm-6 col-xs-12">
                                <input type="text" name="mobile" value="{{ $item->mobile }}" class="form-control">
                            </td>
                            <td class="col-md-2 col-sm-6 col-xs-12">
                                <input type="text" name="password" value="{{ $item->password }}"
                                    class="form-control">
                            </td>

                            <td class="col-md-2 col-sm-6 col-xs-12">
                                <span style="white-space: normal; max-width: 400px;">
                                    <div style="display: flex; flex-wrap: wrap; gap: 5px;">
                                        @foreach (array_unique(array_map('trim', explode(',', $item->assigned_classes))) as $class)
                                            @if (!empty($class))
                                                <span class="badge bg-info">{{ $class }}</span>
                                            @endif
                                        @endforeach
                                    </div>
                                </span>
                                <div class="form-group bginput mb-3 mt-2" wire:ignore>
                                    <select class="js-select2 form-select classes-select" name="classes[]" multiple>
                                        @foreach ($classes as $id => $name)
                                            <option value="{{ $id }}" @selected(in_array($id, $item->assigned_classes_array ?? []))>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </td>
                            <td class="col-md-2 col-sm-6 col-xs-12">
                                <span style="white-space: normal; max-width: 400px;">
                                    <div style="display: flex; flex-wrap: wrap; gap: 5px;">
                                        @foreach (array_unique(array_map('trim', explode(',', $item->assigned_subjects))) as $subject)
                                            @if (!empty($subject))
                                                <span class="badge bg-info">{{ $subject }}</span>
                                            @endif
                                        @endforeach
                                    </div>
                                </span>

                                <div class="form-group bginput mb-3 mt-2" wire:ignore>
                                    <select class="js-select2 form-select subjects-select" name="subjects[]" multiple>
                                        @foreach ($subjects as $id => $name)
                                            <option value="{{ $id }}" @selected(in_array($id, $item->assigned_subjects_array ?? []))>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </td>
                            <td class="col-md-2 col-sm-6 col-xs-12">
                                <span class="badge {{ $isExisitsInLMS ? 'text-success' : 'text-danger' }}">
                                    {{ config('constants.PORTED_LIST')[$isExisitsInLMS] ?? 'Unknown Status' }}
                                </span>
                            </td>

                            <td class="col-md-2 col-sm-6 col-xs-12">
                                <div class="d-flex gap-1">
                                    <button type="submit" class="btn btn-success btn-sm">Save</button>
                                    <button type="button" class="btn btn-secondary btn-sm"
                                        wire:click="cancel">Cancel</button>
                                </div>
                            </td>
                        </tr>
                    @else
                        <tr>
       						 <td>{{ (($currentPage - 1) * $perPage) + $index + 1 }}.</td>
                            <td>{{ $item->fname ?? '' }}</td>
                            <td>{{ $item->mobile ?? '' }}</td>
                            <td>{{ $item->password ?? '' }}</td>
                            <td style="white-space: normal; max-width: 400px;">
                                <div style="display: flex; flex-wrap: wrap; gap: 5px;">
                                    @foreach (array_unique(array_map('trim', explode(',', $item->assigned_classes))) as $class)
                                        @if (!empty($class))
                                            <span class="badge bg-info">{{ $class }}</span>
                                        @endif
                                    @endforeach
                                </div>
                            </td>
                            <td style="white-space: normal; max-width: 400px;">
                                <div style="display: flex; flex-wrap: wrap; gap: 5px;">
                                    @foreach (array_unique(array_map('trim', explode(',', $item->assigned_subjects))) as $subject)
                                        @if (!empty($subject))
                                            <span class="badge bg-info">{{ $subject }}</span>
                                        @endif
                                    @endforeach
                                </div>
                            </td>
                            <td>
                                <span class="badge {{ $isExisitsInLMS ? 'text-success' : 'text-danger' }}">
                                    {{ config('constants.PORTED_LIST')[$isExisitsInLMS] ?? 'Unknown Status' }}
                                </span>
                            </td>
                            <td>
                                <button type="button" wire:click="edit({{ $item->id }})"
                                    class="btn btn-primary btn-sm">Edit</button>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>

        </table>
    </form>

    @if (session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif
<div class="customPagination mt-4">
    <ul class="pagination">
        {{-- Previous Button --}}
        <li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }} previous-item">
            <a class="page-link" href="javascript:void(0)" wire:click="previousPage">
                <span><img src="{{ asset('frontend/images/arrowprw.svg') }}" width="6"></span>
            </a>
        </li>

        {{-- Page Numbers --}}
        @for ($page = 1; $page <= $totalPages; $page++)
            <li class="page-item {{ $page == $currentPage ? 'active' : '' }}">
                <a class="page-link" href="javascript:void(0)" wire:click="goToPage({{ $page }})">
                    {{ $page }}
                </a>
            </li>
        @endfor

        {{-- Next Button --}}
        <li class="page-item {{ $currentPage == $totalPages ? 'disabled' : '' }} next-item">
            <a class="page-link" href="javascript:void(0)" wire:click="nextPage">
                <span><img src="{{ asset('frontend/images/arrownxt.svg') }}" width="6"></span>
            </a>
        </li>
    </ul>
</div>


</div>

@push('scripts')
    <script>
        function initSelect2() {
            $(".js-select2").select2({
                closeOnSelect: false,
                placeholder: "Select",
                allowClear: false,
                tags: true
            });
        }

        document.addEventListener("livewire:load", function() {
            initSelect2();

        });
        // Optional: Re-initialize Select2 after edit mode is enabled
        document.addEventListener("click", function(event) {
            if (event.target.closest("[wire\\:click^='edit']")) {
                setTimeout(initSelect2, 500);
            }
        });
    </script>
@endpush
