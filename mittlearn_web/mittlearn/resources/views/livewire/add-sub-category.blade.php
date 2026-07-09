<div>
    @if(isset($subCategories) && count($subCategories) > 0)
        @foreach($subCategories as $index => $sub)
            <div >
                {!! Form::label('sub_category', 'Sub-Category', ['class' => 'form-label']) !!}
                {!! Form::hidden('sub_category_ids[]', $sub['id'] ?? null) !!}
                {!! Form::text('sub_category_names[]', $sub['name'] ?? '', ['class' => 'form-control', 'placeholder' => 'Enter Sub Category']) !!}
            </div>
        @endforeach
    @endif


     <div class="row mb-3 mt-3">
        <div class="col-sm-10">
            @if(!$isEdit) 
                <button type="button" wire:click="saveSubCategory" class="btn btn-secondary">Add SubCategories</button>
            @endif
        </div>
    </div>
    

</div>