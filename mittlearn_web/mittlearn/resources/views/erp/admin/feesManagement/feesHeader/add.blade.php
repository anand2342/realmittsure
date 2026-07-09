@extends('admin.layouts.master')
@section('content')
    <div>
        <div class="pagetitle">
            <h1>Add Fee Header</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Fee Header</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">

                            {{-- {{ Form::model($data, ['url' => route('save.fee.header'), 'id' => 'edit-plan-form', 'class' => 'row g-3']) }}
                                {{ Form::hidden('id', null) }} --}}
                            {{ Form::open(['url' => route('save.fee.header'), 'id' => 'add-plan-form', 'class' => 'row g-3']) }}
                            <h5 class="card-title pb-0">Fee Header Info</h5>
                            <hr class="form-divider">


                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('fee_name', 'Fees Name', ['class' => 'form-label required']) !!}
                                {!! Form::text('fee_name', null, [
                                    'class' => 'form-control',
                                    'id' => 'vallidateName',
                                    'placeholder' => 'Enter Fees name',
                                    'required',
                                ]) !!}
                                <small id="vallidateNameError" class="form-text text-danger mt-1"
                                    style="display:none;"></small>
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('fees_type', 'Fees type', ['class' => 'form-label required']) !!}
                                {!! Form::select('fees_type', config('constants.FEES_TYPE'), null, [
                                    'class' => 'form-control form-select fs-8 ',
                                    'placeholder' => '--Select--',
                                    'required',
                                ]) !!}
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('fees_cycle', 'Fees Cycle', ['class' => 'form-label required']) !!}
                                {!! Form::select('fees_cycle', config('constants.FEES_DURATION_TYPES'), null, [
                                    'class' => 'form-control form-select fs-8 ',
                                    'placeholder' => '--Select--',
                                    'required',
                                ]) !!}
                            </div>

                            <div class="col-sm-12 text-end">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="reset" class="btn btn-secondary"
                                    onclick="window.location.reload();">Reset</button>
                            </div>

                            {{ Form::close() }}

                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section class="section">
            {{-- <div class="row"> --}}
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="card-title">All Fees Header</div>
                            </div>
                            <div class="col-sm-6 text-end mt-3">
                                {{-- @isPermission('board.create') --}}
                                <a href="{{ route('create.fee.header') }}" class="btn btn-success">
                                    Add New
                                </a>
                                {{-- @endisPermission --}}
                            </div>
                        </div>

                        <div class="table-responsive tbleDiv ">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th><b>Fees Name</b></th>
                                        <th><b>Fees Type </b></th>
                                        <th><b>Fees Cycle </b></th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        <tr>
                                            <td>{{ $data->currentPage() * $data->perPage() - $data->perPage() + $loop->iteration . '.' }}
                                            </td>
                                            <td>{{ $item->fee_name }}</td>
                                            <td>{{ config('constants.FEES_TYPE')[$item->fees_type] ?? 'Unknown' }}</td>
                                            <td>{{ config('constants.FEES_DURATION_TYPES')[$item->fees_cycle] ?? 'Unknown' }}
                                            </td>
                                            <td>
                                                {{-- @isPermission('board.edit') --}}
                                                <a class="btn btn-sm btn-warning"
                                                    href="{{ route('edit.fee.header', $item->id) }}"><i
                                                        class="fa fa-pencil"></i></a>
                                                {{-- @endisPermission --}}
                                                {{-- @isPermission('board.delete') --}}
                                                <a class="btn btn-danger btn-sm me-2" href="javascript:void(0);"
                                                    onclick="confirmDelete('{{ route('delete.fee.header', $item->id) }}')">
                                                    <i class="fa fa-trash"></i></a>
                                                {{-- @endisPermission --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-right text-right">
                            {!! $data->links('pagination::bootstrap-4') !!}
                        </div>
                    </div>
                </div>
            </div>
            {{-- </div> --}}
        </section>
    @endsection
