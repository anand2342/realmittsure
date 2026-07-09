@extends('admin.layouts.master')
@section('content')
    @php 
    $flag=0;
    $heading=('Add');
    if(isset($category) && !empty($category)){
        $flag=1;
        $heading=('Edit');
    }
    @endphp

<section class="section">
  <div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{$heading}} Category</h5>
                <hr class="form-divider">

                @if($flag==1)
                {{ Form::model($category,array('url'=>route('blog.category.save'),'id'=>"edit-plan-form", 'class'=>"row g-3", 'enctype' => 'multipart/form-data')) }}
                {{Form::hidden('id',null)}}
                @else
                {{ Form::open(array('url'=>route('blog.category.save'),'id'=>"add-plan-form", 'class'=>"row g-3", 'enctype' => 'multipart/form-data')) }}
                @endif

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('category', 'Category', ['class'=>"form-label required"]) !!}
                    {!! Form::text('category', isset($category) ? $category->name : null, ['class' => 'form-control', 'placeholder' => 'Enter Category', 'required' => 'required']) !!}
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                  @livewire('add-sub-category', ['subCategories' => $subCategory ?? [], 'isEdit' => isset($category)])
                </div>
                
                <div class="modal-footer">
                  <div class="text-right" >
                      <button type="submit" class="btn btn-primary">Submit</button>
                      <button type="reset" class="btn btn-secondary" onclick="window.location.reload();">Reset</button>
                  </div>
              </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  let count = 0;
    $(document).ready(function() {
        $('#add-more').click(function() {
          if(count <= 2 ){
            $('#sub-container').append(`
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <label class="form-label">Sub-Category</label>
                    <input type="text" name="sub_category_names[]" class="form-control" placeholder="Enter Sub Category">
                </div>
            `);
          }
        });
    });
</script>


</section>

@endsection


