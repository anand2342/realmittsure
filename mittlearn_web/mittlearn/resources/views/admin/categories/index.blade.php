@extends('admin.layouts.master')

@section('content')
    <div>
        <div class="pagetitle">
            <h1>Groups</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Groups</li>
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
                                    <h5 class="card-title">All Groups</h5>
                                </div>
                                {{-- <div class="col-sm-6 text-end mt-3">
                                    @isPermission('sub-category.add')
                                        <a class="btn btn-success" href="{{ route('sub-category.add') }}">Add Parent</a>
                                    @endisPermission
                                </div> --}}
                            </div>
                            <hr class="formdivider">
                            <div class="table-responsive tbleDiv ">
                                <table id="datatable" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Category Name</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($categories as $category)
                                            @include('admin.categories.category-row', [
                                                'child_index' => '',
                                                'parent_index' => $loop->iteration,
                                                'category' => $category,
                                            ])
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-right text-right">
                                {!! $categories->links('pagination::bootstrap-4') !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
