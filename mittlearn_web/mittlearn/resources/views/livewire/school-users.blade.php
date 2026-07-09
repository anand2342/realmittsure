<div>
    {{ Form::open(['url' => route('permissions.save'), 'id' => 'update-permission-form', 'class' => 'row g-3']) }}
    <hr class="form-divider">
    <div class="card">
        <div class="card-body">
            <div class="row mt-2">

                <div class="col-md-3 col-sm-3 col-xs-12">
                    <label class="form-label">School</label>
                    <select wire:model="school_id" class="form-select">
                        <option value="">--Select School--</option>
                        @foreach ($schoolList as $id => $schoolName)
                            <option value="{{ $id }}" {{ $school_id == $id ? 'selected' : '' }}>
                                {{ $schoolName }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <label class="form-label">Role</label>
                    <select wire:model="role" class="form-select">
                        <option value="all">All</option>
                        <option value="school_teacher">Teacher</option>
                        <option value="school_student">Student</option>
                    </select>
                </div>

                <div class="col-md-3 col-sm-2 col-xs-12">
                    <label class="form-label">Status</label>
                    {{ Form::select('status', config('constants.STATUS_LIST'), null, ['class' => 'form-select', 'wire:model' => 'status']) }}
                </div>

               
                <div class="col-md-2 mt-2 pt-4">
                    <button type="button" wire:click="search" class="btn btn-primary">Search</button>
                    <a href="{{ route('school.users') }}" class="btn btn-secondary">Clear</a>
                </div>
            </div>
        </div>
    </div>

    {{ Form::close() }}
    <div class="card">
        <div class="card-body">

            <h5 class="card-title">Logs</h5>
            <hr class="form-divider">

            <div class="table-responsive tbleDiv ">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th>Class</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {{-- @dump($filteredUsers) --}}
                        @if ($filteredUsers != [])
                            @foreach ($filteredUsers as $index => $log)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $log->name }}</td>
                                    <td>{{ config('constants.STATUS_LIST')[$log->status] ?? 'Unknown' }}</td>
                                    <td>{{ $log->class_names }}</td>
                                    <td><a class="btn btn-sm btn-info me-1"
                                            href="{{ route('school.users.details', $log->id) }}">
                                            <i class="fa fa-eye"></i></a></td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="7" class="text-center">No Results</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    {{-- <div class="d-flex justify-content-right text-right">
        {!! $onlineClassLogs->links('pagination::bootstrap-4') !!}
    </div> --}}
</div>
