@extends('admin.layouts.master')
@section('content')
    <div>
        <div class="pagetitle">
            <h1>Subscription Plans</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Subscription Plans</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">



                            <div class="row">
                                <div class="col-sm-6">
                                    <h5 class="card-title">All Plans</h5>
                                </div>
                                <div class="col-sm-6 text-end mt-3">
                                    @isPermission('plans.add')
                                        <a href="{{ route('plans.add') }}" class="btn btn-success">
                                            Add New
                                        </a>
                                    @endisPermission

                                </div>
                            </div>
                            <hr class="fromdivider">

                            <div class="table-responsive tbleDiv ">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Name</th>
                                            <th>Is Recommended</th>
                                            <th>Is Free Trial</th>
                                            <th>Description</th>
                                            <th>Sort Order</th>
                                            <th>BG Color</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($datalist as $k => $val)
                                            <tr>
                                                <td>{{ $k + $datalist->firstItem() }}.</td>
                                                <td>{{ $val->name }}</td>
                                                <td>{{ $val->is_recomanded ? 'Yes' : 'No' }}</td>
                                                <td>{{ $val->is_free_trial ? 'Yes' : 'No' }}</td>
                                                <td>{{ $val->description }}</td>
                                                <td>{{ $val->sort_order }}</td>
                                                <td>{{ $val->bg_color }}</td>
                                                <td>{!! getStatusBtn($val->status) !!}</td>
                                                <td>

                                                    @isPermission('plans.edit')
                                                        <a class="btn btn-sm btn-warning"
                                                            href="{{ route('plans.edit', [$val->id]) }}"><i
                                                                class="fa fa-pencil"></i></a>
                                                    @endisPermission
                                                    @isPermission('plans.delete')
                                                        <button class="btn btn-danger btn-sm delete_btn"
                                                            data-url="{{ route('plans.delete', [$val->id]) }}"
                                                            title="{{ 'Delete' }}"><i class="fa fa-trash"></i></button>
                                                    @endisPermission
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-right text-right">
                                {!! $datalist->links('pagination::bootstrap-4') !!}
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
@endsection
