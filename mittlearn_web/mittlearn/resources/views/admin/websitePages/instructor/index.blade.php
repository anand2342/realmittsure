@extends('admin.layouts.master')

@section('content')
    <div id="page-header" class="page-header">
        <div class="pagetitle">
            <h1>Instructor Content</h1>
            <nav>
                <ol class="breadcrumb">
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
                                    <h4 class="card-title"> Content Information </h4>
                                </div>
                                <div class="col-sm-6 text-end mt-3">
                                    <a href="{{ route('home.instructor.page-content.add') }}" class="btn btn-success">Add
                                        Instructor</a>
                                </div>
                            </div>

                            <!-- Table with stripped rows -->
                            <table id="datatable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>Action</th>

                                        <th>
                                            <b>N</b>ame
                                        </th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (!$data)
                                        <td class="text-center">No Entries Found</td>
                                    @else
                                        @foreach ($data as $instructor)
                                            <tr>
                                                <td>{{ $instructor->name }}</td>
                                                <td>{{ $instructor->category }}</td>
                                                <td class="action-td">
                                                    <a title="Edit"
                                                        href="{{ route('home.instructor.page-content.edit', $instructor->id) }}"
                                                        class="btn btn-warning btn-sm">
                                                        <i class="fa fa-pencil"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif

                                </tbody>
                            </table>
                            <div class="d-flex justify-content-center">
                                {!! $data->links('vendor.pagination.bootstrap-4') !!}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
@endsection
