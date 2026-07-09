@extends('admin.layouts.master')

@section('content')

<section class="section">
  <div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Add Sub-Category</h5>
                <hr class="form-divider">

                {!! Form::open(['route' => 'blog.sub_category.store', 'method' => 'post','class'=>"row g-3"]) !!}
                {!! Form::hidden('category_name',$category->name ?? null) !!}

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('category', 'Category', ['class' => 'form-label required']) !!}
                    {!! Form::text('category', $category->name ?? null, ['class' => 'form-control', 'disabled' => 'disabled']) !!}
                </div>

                <div  class="col-md-6 col-sm-6 col-xs-12">
                  @if (isset($subCategory))
                  @foreach ($subCategory as $data)
                  <div class="col-md-12 col-sm-12 col-xs-12">
                      {!! Form::label('sub_category', 'Sub-Category', ['class' => 'form-label']) !!}
                      {!! Form::text('sub_category[]', ' ',  ['class' => 'form-control']) !!}
                  </div>
                  @endforeach
                  @else
                  <div class="col-md-12 col-sm-12 col-xs-12">
                    {!! Form::label('sub_category', 'Sub-Category', ['class' => 'form-label']) !!}
                    {!! Form::text('sub_category[]', ' ',  ['class' => 'form-control']) !!}
                  </div>  
                  @endif
                </div>
                <div  class="col-lg-12">
                <div id="sub-container" class="row">

                </div>
                </div>

                <div class="modal-footer">
                  <button type="button" id="add-more" class="btn btn-secondary me-2">Add More</button>
                  {!! Form::submit('Submit', ['class' => 'btn btn-primary']) !!}
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#add-more').click(function() {
            $('#sub-container').append(`
                 <div class="col-md-6 col-sm-12 col-xs-12">
                      {!! Form::label('sub_category', 'Sub-Category', ['class' => 'form-label']) !!}
                      {!! Form::text('sub_category[]', ' ',  ['class' => 'form-control']) !!}
                  </div>
            `);
        });
    });
</script>
</section>

@endsection




