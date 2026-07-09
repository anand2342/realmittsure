<div>
    {{ Form::open(['url' => route('permissions.save'), 'id' => 'update-permission-form', 'class' => 'row g-3']) }}
    <hr class="form-divider">
    <div class="card">
        <div class="card-body">
            <div class="row mt-2">

                <div class="col-md-3 col-sm-3 col-xs-12">
                    <label class="form-label">School</label>
                    <select wire:model="school_id" wire:change="schoolChange($event.target.value)" class="form-select">
                        <option value="">--Select School--</option>
                        @foreach ($schoolList as $id => $schoolName)
                            <option value="{{ $id }}" {{ $school_id == $id ? 'selected' : '' }}>
                                {{ $schoolName }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 col-sm-3 col-xs-12">
                    <label class="form-label">Teacher</label>
                    <select wire:model="teacher_id" class="form-select">
                        <option value="">All</option>
                        @foreach ($teachers as $id => $item)
                            <option value="{{ $id }}" {{ $item == $id ? 'selected' : '' }}>
                                {{ $item }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 col-sm-2 col-xs-12">
                    <label class="form-label">Status</label>
                    {{ Form::select('status', config('constants.ONLINE_CLASS_STATUS'), null, ['class' => 'form-select', 'wire:model' => 'status']) }}
                </div>

                <div class="col-md-2 col-sm-2 col-xs-12">
                    <label class="form-label">Class</label>
                    {{ Form::select('user_id', $classes, null, ['class' => 'form-select', 'placeholder' => '--Select--', 'wire:model' => 'class_id']) }}
                </div>
                <div class="col-md-2 mt-2 pt-4">
                    <button type="button" wire:click="search" class="btn btn-primary">Search</button>
                    <a href="{{ route('online.class.logs') }}" class="btn btn-secondary">Clear</a>
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
                            <th>Class Name</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Status</th>
                            <th>Instructor Name</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($onlineClassLogs != [])
                            @foreach ($onlineClassLogs as $index => $log)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $log->class->name }}</td>
                                    <td>{{ $log->start_time }}</td>
                                    <td>{{ $log->end_time }}</td>
                                    <td>{{ $log->status }}</td>
                                    <td>{{ $log->instructor->name }}</td>
                                    <td>
                                        @isPermission('online.class.log.details')
                                            <a class="btn btn-sm btn-info me-1"
                                                href="{{ route('online.class.log.details', $log->id) }}">
                                                <i class="fa fa-eye"></i></a>
                                        @endisPermission

                                    </td>
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
