@extends('admin.layouts.master')
@section('content')
    <div>
        <div class="pagetitle">
            <h1>SMS Template</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">SMS Templates</li>
                </ol>
            </nav>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <h4 class="card-title">Templates Information</h4>
                                </div>
                                <div class="col-sm-6 text-end mt-3">
                                    {{--  @isPermission('sms-template.add')  --}}
                                        <a class="btn btn-success " href="{{ route('sms-template.add') }}">Add Template</a>
                                    {{--  @endisPermission  --}}
                                </div>
                            </div>
                            <div class="table-responsive tbleDiv ">
                                <table id="datatable" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Name</th>
                                            <th>Subject</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($smsData as $data)
                                            <tr>
                                                <td>{{ $smsData->currentPage() * $smsData->perPage() - $smsData->perPage() + $loop->iteration . '.' }}
                                                </td>
                                                <td> {{ $data->name }}</td>
                                                <td> {{ $data->subject }}</td>
                                                <td>
                                                    {{--  @isPermission('sms-template.edit')  --}}
                                                        <a class="btn btn-warning btn-sm"
                                                            href='{{ route('sms-template.edit', $data->id) }}'>
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                    {{--  @endisPermission  --}}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-right text-right">
                                {!! $smsData->links('pagination::bootstrap-4') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
