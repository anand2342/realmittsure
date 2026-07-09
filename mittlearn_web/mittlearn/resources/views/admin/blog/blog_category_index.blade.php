@extends('admin.layouts.master')

@section('content')
    <div>
        <div class="pagetitle">
            <h1>Blog Categories</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Blog Categories</li>
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
                                    <h5 class="card-title">All Blog Categories</h5>
                                </div>
                                <div class="col-sm-6 text-end mt-3">
                                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#verticalycentered">
                                        Add Parent
                                      </button>
                                </div>
                            </div>
                            <hr class="form-divider">


                              <div class="modal fade" id="verticalycentered" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                  <div class="modal-content">
                                    <div class="modal-header">
                                      <h5 class="modal-title">Add Category</h5>                         
                                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                      {{ Form::open(array('url'=>route('blog.category.save'),'id'=>"add-plan-form", 'class'=>"row g-3", 'enctype' => 'multipart/form-data')) }}
                                      {!! Form::label('category', 'Category', ['class'=>"form-label required col-sm-2 col-form-lable"]) !!}
                                      <div class="col-sm-10">
                                      {!! Form::text('category', null, ['class' => 'form-control', 'placeholder' => 'Enter Category', 'required' => 'required']) !!}
                                      </div>  
                                      
                                    
                                        {!! Form::label('status', 'Status', ['class' => 'form-label required col-sm-2 col-form-lable']) !!}
                                        <div class="col-sm-10">
                                        {!! Form::select(
                                            'status',
                                            [ '1' => 'Active', '0' => 'Inactive'],
                                             null,
                                            ['class' => 'form-control', 'id' => 'status'],
                                        ) !!}
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                      {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
                                    </div>
                                    {!! Form::close() !!}
                                  </div>
                                </div>
                              </div>
                            <table class="table table-striped table-bordered mt-4">
                                <thead>
                                    <tr>
                                        <th>S.No.</th>
                                        <th>Category Name</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($datas as $data)
                                        @include('admin.blog.blog_category_row', [
                                            'child_index' => '',
                                            'parent_index' => $loop->iteration,
                                            'data' => $data,
                                        ])
                                    @endforeach
                                </tbody>
                            </table>
                            {{-- <div class="d-flex justify-content-right text-right">
                                {!! $datas->links('pagination::bootstrap-4') !!}
                            </div>                             --}}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

