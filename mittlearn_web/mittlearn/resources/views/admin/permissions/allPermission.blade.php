@extends('admin.layouts.master')

@section('content')

<div class="pagetitle">
    <h1>Permissions</h1>
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item">Home</li>
        <li class="breadcrumb-item active">Permissions</li>
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
                        <div class="card-title">All Permissions</div>
                    </div>
                    <div class="col-sm-6 text-end mt-3">
                        <a href="{{ route('permissions.add') }}" class="btn btn-success">
                            Add New
                        </a>
                    </div>
                </div>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th><b>Category</b></th>
                            <th><b>Title</b></th>
                            <th><b>Route Name</b></th>
                            <th><b>Permission Type</b></th>
                            <th><b>Accessable For</b></th>
                            <th><b>Description</b></th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $item)
                            <tr>
                                <td>{{ $data->currentPage() * $data->perPage() - $data->perPage() + $loop->iteration .'.' }}</td>
                                <td>{{ $item->category }}</td>
                                <td>{{ $item->title }}</td>
                                <td>{{ $item->slug }}</td>
                                <td>{{ $item->permission_type }}</td>
                                <td>{{ $item->accessable_for }}</td>
                                <td>{{ $item->description }}</td>
                                <td>
                                    <a class="btn btn-sm btn-warning" href="{{ route('permission.edit', $item->id) }}" ><i class="fa fa-pencil"></i></a>
                                    <button class="btn btn-sm btn-danger" onclick="confirmDelete('{{ route('permission.delete', $item->id) }}')">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-right text-right">
                  {!! $data->links('pagination::bootstrap-4') !!}
                </div>
            </div>
        </div>
    </div>
  </div>
</section>
@endsection
