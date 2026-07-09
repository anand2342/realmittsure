@extends('admin.layouts.master')

@section('content')
    <div>
        <div class="pagetitle">
            <h1>Coupons</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Coupons</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="card-title mb-0">All Coupons</h5>
                                @isPermission('coupon.create')
                                    <a class="btn btn-success addnew" href="{{ route('coupon.create') }}">Add Coupon</a>
                                @endisPermission
                            </div>
                            <hr class="formdivider">
                            <div class="table-responsive tbleDiv ">
                                <table id="datatable" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Coupons Code</th>
                                            <th>Discount Type</th>
                                            <th>Discount Value</th>
                                            <th>Min. Cart Value</th>
                                            <th>Max. Cart Value</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($couponsData as $data)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $data->code }}</td>
                                                <td>{{ $data->discount_type }}</td>
                                                <td>{{ $data->discount_value }}</td>
                                                <td>{{ $data->min_cart_value }}</td>
                                                <td>{{ $data->max_cart_value }}</td>

                                                <td>
                                                    @isPermission('coupon.edit')
                                                        <a class="btn btn-sm btn-warning"
                                                            href="{{ route('coupon.edit', $data->id) }}">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                    @endisPermission
                                                    @isPermission('coupon.destroy')
                                                        <a class="btn btn-danger btn-sm me-2" href="javascript:void(0);"
                                                            onclick="confirmDelete('{{ route('coupon.destroy', $data->id) }}')">
                                                            <i class="fa fa-trash"></i></a>
                                                    @endisPermission
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-right text-right">
                                {!! $couponsData->links('pagination::bootstrap-4') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
