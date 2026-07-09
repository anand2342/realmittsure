@extends('admin.layouts.master')

@section('content')
    <div class="pagetitle">
        <h1>ERP Data List</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item active">ERP</li>
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
                                <div class="card-title">ERP Data</div>
                            </div>
                        </div>
                        <hr class="form-divider">
                        <div class="table-responsive tbleDiv ">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th><b>username</b></th>
                                        <th><b>Name</b></th>
                                        <th><b>user_type</b></th>
                                        <th><b>Mobile</b></th>
                                        <th><b>Action</b></th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $item)
                                        {{-- @dd($item->status) --}}
                                        <tr>
                                            <td>{{ $users->currentPage() * $users->perPage() - $users->perPage() + $loop->iteration . '.' }}
                                            </td>
                                            <td>{{ $item->username }}</td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->user_type ?? '' }}</td>
                                            <td>{{ $item->contactNo ?? '' }}</td>
                                            <td>{{ $item->contactNo ?? '' }}</td>
                                            {{-- @isPermission('course.add.chapter') --}}
                                            <td> <a class="btn btn-primary btn-sm ms-1"
                                                    href="{{ route('erp-data.school.users', $item->id) }}"
                                                    title="View Teachers and Students">
                                                    View Data
                                                </a></td>
                                            {{-- @endisPermission --}}
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        {{-- <div class="d-flex justify-content-right text-right">
                            {!! $users->links('pagination::bootstrap-4') !!}
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
