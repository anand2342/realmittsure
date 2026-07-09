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
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <input type="hidden"class="form-control" wire:model="editData.erp_id">
                                <input type="hidden"class="form-control" wire:model="editData.schid">
                                <input type="text" class="form-control" wire:model="editData.name">
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
                                {{ $item->class_name }}

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
                            <td>{{ $students->currentPage() * $students->perPage() - $students->perPage() + $loop->iteration . '.' }}
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
                            <td>{{ $user->password ?? null ?? '' }}</td>
                            <td>{{ $item->fathersPhone ?? '' }}</td>

                            <td>{{ $className ?? 'Not found' }}</td>
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

    @if ($students->count() > 0)
        <div class="d-flex justify-content-right text-right">
            {!! $students->links('pagination::bootstrap-4') !!}
        </div>
    @endif

</div>
