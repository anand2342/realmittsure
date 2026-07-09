<div class="table-responsive tbleDiv">

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <form wire:submit.prevent="update()">

        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th><b>Name</b></th>
                    <th><b>UserName</b></th>
                    <th><b>Password</b></th>
                    <th><b>Mobile</b></th>
                    <th><b>Class</b></th>
                    <th><b>Status</b></th>
                    <th><b>Action</b></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($students as $item)
                    @php
                        $isExisitsInLMS = \App\Models\User::whereNotNull('erp_db_id')
                            ->where('erp_db_id', $item->id)
                            ->exists();
                    @endphp
                    @if ($editingId == $item->id)
                        <tr>
                            <td>{{ ($currentPage - 1) * $perPage + $loop->iteration }}.</td>
                            <td>
                                <input type="hidden"class="form-control" wire:model="editData.erp_id">
                                <input type="hidden"class="form-control" wire:model="editData.schid">
                                <input type="text" class="form-control" wire:model="editData.name">
                                <input type="hidden" class="form-control" wire:model="editData.fathersname">
                                <input type="hidden" class="form-control" wire:model="editData.currentAddress">
                            </td>
                            <td>

                                <input type="text" class="form-control" wire:model="editData.username">
                            </td>
                            <td>
                                <input type="text" class="form-control" wire:model="editData.password">
                            </td>
                            <td>
                                <input type="text" class="form-control" wire:model="editData.mobile">
                            </td>
                            <td>
                                {{ $item->admitClass ?? 'Not Found' }}

                                <select class="form-select" wire:model="selectedClass">
                                    <option value="">Select Class</option>
                                    @foreach ($classes as $id => $name)
                                        <option value="{{ $id }}"
                                            {{ $selectedClass == $id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('selectedClass')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </td>
                            <td>
                                <span class="badge {{ $isExisitsInLMS ? 'text-success' : 'text-danger' }}">
                                    {{ config('constants.PORTED_LIST')[$isExisitsInLMS] ?? 'Unknown Status' }}
                                </span>
                            </td>
                            <td class="text-nowrap">
                                <div class="d-flex gap-1">
                                    <button type="submit" class="btn btn-success btn-sm">Save</button>
                                    <button type="button" class="btn btn-secondary btn-sm"
                                        wire:click="cancel">Cancel</button>
                                </div>
                            </td>

                        </tr>
                    @else
                        <tr>
                            <td>{{ ($currentPage - 1) * $perPage + $loop->iteration }}.</td>
                            </td>
                            @php
                                $className = DB::connection('erp')
                                    ->table('class')
                                    ->where('id', $item->classid)
                                    ->value('name');
                                $user = DB::connection('erp')
                                    ->table('all_user')
                                    ->where('name', $item->addNumber)
                                    ->select('name', 'password')
                                    ->first(); 
                            @endphp
                            <td>{{ $item->fname ?? '' }}</td>
                            <td>{{ $user->name ?? null }}</td>
                            <td>{{ $user->password ?? (null ?? '') }}</td>
                            <td>{{ $item->fathersPhone ?? '' }}</td>

                            <td>{{ $item->admitClass ?? 'Not found' }}</td>
                            <td>
                                <span class="badge {{ $isExisitsInLMS ? 'text-success' : 'text-danger' }}">
                                    {{ config('constants.PORTED_LIST')[$isExisitsInLMS] ?? 'Unknown Status' }}
                                </span>
                            </td>
                            <td>
                                <button type="button" class="btn btn-primary btn-sm"
                                    wire:click="edit('{{ $item->id }}')">Edit</button>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    </form>

    @if ($totalPages > 1)
        <div class="customPagination mt-4">
            <ul class="pagination">
                <li class="page-item {{ $currentPage == 1 ? 'disabled' : '' }}">
                    <a class="page-link" href="javascript:void(0)" wire:click="previousPage">
                        <span><img src="{{ asset('frontend/images/arrowprw.svg') }}" width="6"></span>
                    </a>
                </li>

                @for ($page = 1; $page <= $totalPages; $page++)
                    <li class="page-item {{ $currentPage == $page ? 'active' : '' }}">
                        <a class="page-link" href="javascript:void(0)" wire:click="goToPage({{ $page }})">
                            {{ $page }}
                        </a>
                    </li>
                @endfor

                <li class="page-item {{ $currentPage == $totalPages ? 'disabled' : '' }}">
                    <a class="page-link" href="javascript:void(0)" wire:click="nextPage">
                        <span><img src="{{ asset('frontend/images/arrownxt.svg') }}" width="6"></span>
                    </a>
                </li>
            </ul>
        </div>
    @endif


</div>
