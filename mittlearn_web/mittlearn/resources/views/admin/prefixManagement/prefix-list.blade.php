@extends('admin.layouts.master')

@section('content')
    <div class="pagetitle">
        <h1>Prefixes List</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item active">Prefix List</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="card-title">All Prefixes</div>
                        <hr class="formdivider">
                        <div class="table-responsive tbleDiv ">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th><b>Prefix</b></th>
                                        <th><b>Status</b></th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($prefixes))
                                        @foreach ($prefixes as $item)
                                            <tr>
                                                <td>{{ $prefixes->currentPage() * $prefixes->perPage() - $prefixes->perPage() + $loop->iteration . '.' }}
                                                </td>
                                                <td>{{ $item->prefix ?? '' }}</td>
                                                <td>
                                                    <span
                                                        class="badge {{ $item->is_active ? 'text-success' : 'text-danger' }}">
                                                        {{ config('constants.STATUS_LIST')[$item->is_active] ?? 'Unknown Status' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @isPermission('prefix.edit')
                                                    <a class="btn btn-sm btn-warning"
                                                        href="{{ route('prefix.edit', $item->id) }}" title="Edit"><i
                                                            class="fa fa-pencil"></i></a>
                                                    @endisPermission
                                                    @isPermission('prefix.delete')
                                                    <a class="btn btn-danger btn-sm me-2" href="javascript:void(0);"
                                                        onclick="confirmDelete('{{ route('prefix.delete', $item->id) }}')"
                                                        title="Delete">
                                                        <i class="fa fa-trash"></i></a>
                                                    @endisPermission
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-right text-right">
                            {!! $prefixes->links('pagination::bootstrap-4') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection
