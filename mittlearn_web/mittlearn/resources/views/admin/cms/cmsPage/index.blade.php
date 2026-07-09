@extends('admin.layouts.master')

@section('content')
    <div>
        <div class="pagetitle">
            <h1>CMS Pages</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">CMS</li>
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
                                    <h4 class="card-title">All CMS Pages</h4>
                                </div>
                                {{--  <div class="col-sm-6 text-end mt-3">
                                    <a class="btn btn-success " href="{{ route('cms.add') }}">Add New CMS</a>
                                </div>  --}}
                            </div>
                            <div class="table-responsive tbleDiv ">
                                <table id="datatable" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th> Name</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($getData as $data)
                                            <tr>
                                                <td>{{ $getData->currentPage() * $getData->perPage() - $getData->perPage() + $loop->iteration . '.' }}
                                                <td>{{ $data->name }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @isPermission('cms.edit')
                                                            <a class="btn btn-warning btn-sm me-2"
                                                                href="{{ route('cms.edit', $data->id) }}" title="Edit">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                        @endisPermission
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-right text-right">
                                {!! $getData->links('pagination::bootstrap-4') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
