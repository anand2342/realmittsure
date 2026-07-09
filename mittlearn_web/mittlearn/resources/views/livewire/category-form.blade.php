<div>
    <!-- Parent Category Dropdown -->
    <div class="col-md-4 col-xs-12">
        {!! Form::label('category', 'Parent Category', ['class' => 'form-label']) !!}
        {!! Form::select('parent_id', $category, null, [
            'class' => 'form-control',
            'required',
            'placeholder' => '--select--', 
            'wire:model' => 'selectedParentId', // Bind the selected parent ID
            'wire:change' => 'loadSub($event.target.value)', // Trigger loadSub when a parent category is selected
        ]) !!}
    </div>

    <!-- Sub-Category Dropdown -->
    <div class="col-md-4 col-xs-12">
        {!! Form::label('sub_category', 'Select Category', ['class' => 'form-label']) !!}
        {!! Form::select(
            'sub_category_id',
            $subCategory, 
            null,
            ['class' => 'form-control', 'required', 'placeholder' => '--select--', 'wire:model' => 'selectedSubCategoryId'], // Fixed form-control class for consistency
        ) !!}
    </div>
</div>
