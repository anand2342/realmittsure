<div class="row">
    @php
        $categories = getCategoriesWithChild();
    @endphp
    <div class="col-sm-12 col-lg-12 mb-3">
        <div class="row g-3">
            <div class="col-md-4 col-sm-3 col-xs-12">
                {!! Form::label('category', 'Content Group', ['class'=>"form-label"]) !!}
                {{ Form::select('category', $parentCategories, null, ['class'=>'form-select','placeholder'=>'--Select--', 'id'=>'category', 'wire:model.live'=>"filterCategory", 'wire:change'=>'handleFilterCourses($event.target.value, "filterCategory")']) }}   
            </div> 

            <div class="col-sm-12"></div>

            <div class="row form-section-1" style="display: {{$filterCategory != 1 ? 'none' : ''}}">
                <div class="col-md-4 col-sm-3 col-xs-12" wire:ignore>
                    <div>{!! Form::label('class', 'Select Class', ['class'=>"form-label"]) !!}</div>
                    {{ Form::select('classes', $classesList, null, ['class'=>'form-select', 'multiple'=>true, 'wire:model'=>"filterClasses", 'id'=>'classes' ]) }}   
                </div>

                <div class="col-md-4 col-sm-3 col-xs-12" wire:ignore>
                    <div>{!! Form::label('subject', 'Select Subject', ['class'=>"form-label"]) !!}</div>
                    {{ Form::select('subjects', $subjectsList, null, ['class'=>'form-select', 'multiple'=>true, 'wire:model'=>"filterSubjects", 'id'=>'subjects' ]) }}   
                </div>

                <div class="col-md-4 col-sm-3 col-xs-12" wire:ignore>
                    <div>{!! Form::label('book-series', 'Select Series', ['class'=>"form-label"]) !!}</div>
                    {{ Form::select('book_series', $bookSeriesList, null, ['class'=>'form-select', 'multiple'=>true, 'wire:model'=>"filterBookSeries", 'id'=>'book-series' ]) }}   
                </div>
            </div>
            <div class="row form-section-1" style="display: {{$filterCategory != 2 ? 'none' : ''}}">
                <div class="col-md-4 col-sm-3 col-xs-12" wire:ignore>
                    <div>{!! Form::label('book-series', 'Select Sub-Category', ['class'=>"form-label"]) !!}</div>
                    
                    {{ Form::text('non_aca_category', null, ['class'=>'form-control', 'id'=>'non-academic-category-input', 'autocomplete'=>'off', 'placeholder'=>'Select Category' ]) }}   
                    {{-- {{ Form::select('non_aca_category', [], null, ['class'=>'form-select', 'multiple'=>true, 'wire:model'=>"filterCategoryNonAcademic", 'id'=>'non-academic-category' ]) }}    --}}
                </div>
            </div>

            <div class="col-sm-12"></div>

            <div class="col-md-4 col-sm-3 col-xs-12">
                <div>{!! Form::label('course_name', 'Search Content', ['class'=>"form-label"]) !!}</div>
                {!! Form::text('course_name', null, ['class' => 'form-control', 'placeholder' => 'Search Content Name', 'wire:keyup'=>'handleFilterCourses($event.target.value, "filterCourseName")']) !!}
            </div>
            
            <div class="col-sm-12 d-flex justify-content-end">
                <button type="button" class="btn btn-success" wire:click='handleFilterCourses("", "search")'>Search</button>
            </div>
        </div>
    </div>

    <div class="row" style="display: {{empty($filterCategory) ? 'none' : ''}}">
        <div class="col-sm-12 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title pb-0">Search Result</h5>
                    <hr class="form-divider">

                    <ul class="list-group">
                         @php
                            $selectedIds = [...$selectedCourseIds['academic'], ...$selectedCourseIds['non_academic']];
                            
                        @endphp
                        @foreach ($selectedIds as $val) 
                            <input 
                                    name="course_ids[]"
                                    class="form-check-input me-1" 
                                    type="hidden" 
                                    value="{{$val}}" 
                                >
                        @endforeach

                        @forelse ($coursesList['display_courses'] as $key=>$courseRow)
                            @php
                                $subData = getCourseSubData($courseRow);
                            @endphp
                            <li class="list-group-item pointer {{ in_array($courseRow->id, $selectedIds) ? 'active-course-li' : ''}}" wire:click="toggleCourse({{ $courseRow->id }})">
                                <div class="">
                                    {{-- <input 
                                        name="course_ids[]"
                                        class="form-check-input me-1" 
                                        type="checkbox" 
                                        value="{{$courseRow->id}}" 
                                        readonly
                                    > --}}
                                    <strong>{{!empty($courseRow->course_name) ? $courseRow->course_name : 'NA'}}</strong>
                                </div>
                                <small class="text-muted">
                                    {!! getCourseSubInfoForDisplay($subData) !!}
                                </small>
                            </li>
                        @empty
                            <li class="list-group-item text-danger">No courses found.</li>
                        @endforelse
                </ul>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-lg-6">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title pb-0">Selected Academic Courses</h5>
                    <hr class="form-divider">

                    <ul class="list-group">
                        @forelse ($coursesList['academic'] as $key=>$courseRow)
                            @if (in_array($courseRow->id, $selectedCourseIds['academic']))
                                @php
                                    $subData = getCourseSubData($courseRow);
                                @endphp
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-bold">{{!empty($courseRow->course_name) ? $courseRow->course_name : 'NA'}}</div>
                                        <small class="text-muted">
                                            {!! getCourseSubInfoForDisplay($subData) !!}
                                        </small>
                                    </div>
                                    {{-- <span class="" wire:click="toggleCourse({{ $courseRow->id }})">
                                        <i class="fa fa-trash me-1 text-danger"></i>
                                    </span> --}}
                                </li>
                            @endif
                        @empty
                            {{-- <li class="list-group-item d-flex justify-content-between align-items-start text-danger">No courses selected.</li> --}}
                        @endforelse
                        @if (count($selectedCourseIds['academic']) == 0)
                            <li class="list-group-item d-flex justify-content-between align-items-start text-danger">No courses selected.</li>
                        @endif
                    </ul>
                </div>

                <div class="card-body">
                    <h5 class="card-title pb-0">Selected Talent & Skills Courses</h5>
                    <hr class="form-divider">

                    <ul class="list-group">
                        @forelse ($coursesList['non_academic'] as $key=>$courseRow)
                            @if (in_array($courseRow->id, $selectedCourseIds['non_academic']))
                                @php
                                    $subData = getCourseSubData($courseRow);
                                @endphp
                                <li class="list-group-item d-flex justify-content-between align-items-start">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-bold">{{!empty($courseRow->course_name) ? $courseRow->course_name : 'NA'}}</div>
                                        <small class="text-muted">
                                            {!! getCourseSubInfoForDisplay($subData) !!}
                                        </small>
                                    </div>
                                    {{-- <span class="" wire:click="toggleCourse({{ $courseRow->id }})">
                                        <i class="fa fa-trash me-1 text-danger"></i>
                                    </span> --}}
                                </li>
                        @endif
                        @empty
                            {{-- <li class="list-group-item d-flex justify-content-between align-items-start text-danger">No courses selected.</li> --}}
                        @endforelse
                        @if (count($selectedCourseIds['non_academic']) == 0)
                            <li class="list-group-item d-flex justify-content-between align-items-start text-danger">No courses selected.</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
    <script>
    document.addEventListener('livewire:init', function () {
        let comboTree1;
        const jsonData = {!! json_encode($categoriesWithChildren) !!};
        comboTree1 = $("#non-academic-category-input").comboTree({
          source: jsonData,
          isMultiple: true,
          cascadeSelect: true,
          collapse: false,
        });
        comboTree1.onChange(()=> {
        const selectedItems = comboTree1.getSelectedIds();
            @this.set('filterCategoryNonAcademic', selectedItems);
        });

        function initSelect2() {
            $('#classes').select2({
                    placeholder: 'Select Classes',
                    allowClear: true
            });
            $('#subjects').select2({
                    placeholder: 'Select Subjects',
                    allowClear: true
            });
            $('#book-series').select2({
                    placeholder: 'Select Subjects',
                    allowClear: true
            });

    
        }
   
        $('#category').change(function(e){
            const selectedVal = $(e.target).val();
            if(parseInt(selectedVal) === 1) {
                initSelect2();
            }
        })
        initSelect2();

        // Trigger Livewire update on select2 change
        $('#classes').on('change', function (e) {
            let selectedValue = $(this).val();
            @this.set('filterClasses', selectedValue);
        });

        $('#subjects').on('change', function (e) {
            let selectedValue = $(this).val();
            @this.set('filterSubjects', selectedValue);
        });

        $('#book-series').on('change', function (e) {
            let selectedValue = $(this).val();
            @this.set('filterBookSeries', selectedValue);
        });
        
       
    });
    </script>
@endpush