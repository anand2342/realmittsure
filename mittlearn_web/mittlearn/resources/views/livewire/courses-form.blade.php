<div class="row">
    {{-- @dump($course) --}}
    <div class="col-md-6 col-sm-6 col-xs-12 input-div">
        {!! Form::label('group', 'Group', ['class' => 'form-label required']) !!}
        {!! Form::select('group', $categories, $selectedCategory, [
            'class' => 'form-select',
            'required',
            'placeholder' => '--Select Group--',
            'wire:model' => 'selectedCategory',
            'wire:change' => 'loadConstants($event.target.value)',
        ]) !!}
    </div>
    <!-- Conditional Child Category Dropdown for academic type only -->
    @if (!empty($childCategories) && $selectedCategory == 1)
        <div class="col-md-6 col-sm-6 col-xs-12 input-div" id="acadmic-category-div">
            {!! Form::label('subgroup', 'Subgroup', ['class' => 'form-label required']) !!}
            {!! Form::select('subgroup', $childCategories, $selectedSubCategory, [
                'class' => 'form-select',
                'required',
                'placeholder' => '--Select Subgroup--',
                'wire:model' => 'selectedSubCategory',
                'wire:change' => 'getMetaDataByCategoryId',
            ]) !!}
        </div>
    @else
        <div class="col-md-6 col-sm-6 col-xs-12 input-div {{ $selectedCategory == 2 ? '' : 'd-none' }}"
            id="non-acadmic-category-div">
            <div class="" wire:ignore>
                {!! Form::label('subgroup', 'Subgroup', ['class' => 'form-label required']) !!}
                {{ Form::text('subgroup', null, ['class' => 'form-control', 'id' => 'non-academic-category-input-courses', 'autocomplete' => 'off', 'placeholder' => '--Select Subgroup--', 'required']) }}
                {!! Form::hidden('subgroup', '', ['id' => 'subCategoryInput']) !!} <!-- Hidden input field -->
            </div>
        </div>
    @endif
    <div class="col-md-6 col-sm-6 col-xs-12 input-div">
        {!! Form::label('course_name', 'Book/Course Name', ['class' => 'form-label required']) !!}
        {!! Form::text('course_name', $course->course_name ?? null, [
            'class' => 'form-control',
            'wire:model' => 'course_name',
            'placeholder' => 'Enter Course Name',
            'required' => true,
        ]) !!}
        @error('course_name')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    @if ($selectedCategory == 2)
        <div class="col-md-6 col-sm-6 col-xs-12 input-div">
            {!! Form::label('price_type', 'Course Price Type (Free or Paid)', ['class' => 'form-label required']) !!}
            {{ Form::select('price_type', $priceType, $course->price_type ?? [], ['class' => 'form-control', 'placeholder' => '--select--', 'wire:model' => 'type', 'wire:change' => 'priceTypeChange', 'required' => true]) }}
            @error('price_type')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
    @endif
    @if (in_array($selectedCategory, [null, 1]) || ($selectedCategory == 2 && $type === 'paid'))
        <div class="col-md-6 col-sm-6 col-xs-12 input-div">
            {!! Form::label('price', 'Price', ['class' => 'form-label required']) !!}
            {{ Form::number('price', $course->price ?? null, ['class' => 'form-control', 'placeholder' => 'Enter Price', 'wire:model' => 'price', 'wire:keyup' => "onChangeFiledValue(\$event.target.value, 'price')"]) }}
            @error('price')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12 input-div">
            {!! Form::label('discount_type', 'Discount Type', ['class' => 'form-label required']) !!}
            {{ Form::select('discount_type', config('constants.DISCOUNT_TYPES'), $course->discount_type ?? null, ['class' => 'form-select', 'id' => 'discount_type', 'wire:model' => 'discount_type', 'placeholder' => '--Select--', 'wire:change' => "onChangeFiledValue(\$event.target.value, 'discount_type')"]) }}
            @error('discount_type')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="col-md-6 col-sm-6 col-xs-12 input-div">
            {!! Form::label('discount_value', 'Discount Value', ['class' => 'form-label required']) !!}
            <div class="input-group">
                <span class="input-group-text" id="discount-symbol"></span>
                {{ Form::number('discount_value', $course->discount_value ?? null, ['class' => 'form-select', 'placeholder' => 'Enter Amount', 'wire:model' => 'discount_value', 'wire:keyup' => "onChangeFiledValue(\$event.target.value, 'discount_value')"]) }}
                @error('discount_value')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12 input-div">
            {!! Form::label('amount', 'Amount', ['class' => 'form-label']) !!}
            {{ Form::text('amount', $amount ?? null, ['class' => 'form-control', 'placeholder' => 'Plan Final Price', 'readonly' => true]) }}
        </div>
    @endif
    {{-- @dump($courseMetadataFields) --}}
    @if (($showBoardSelect && !empty($courseMetadataFields)) || $course)
        @foreach ($courseMetadataFields as $field)
            {{-- @dump($metadataFieldValues[$field->field_name]) --}}

            <div class="col-md-6 col-sm-6 col-xs-12 input-div">
                {!! Form::label($field->field_name, $field->field_label, [
                    'class' => 'form-label' . ($field->is_required ? ' required' : ''),
                ]) !!}
                {{-- Display additional message for specific fields --}}
                @if (in_array($field->field_name, ['book_cover_image', 'thumbnail_image', 'banner_image', 'instructor_image']))
                    <small>(jpg, png, svg, webp image, dimensions: 300x450)</small>
                @endif

                @switch($field->field_type)
                    @case('select')
                        {!! Form::select(
                            "course_attribute[{$field->field_name}][{$field->id}]",
                            $modelsDataList[$field->lookup_with] ?? [],
                            $metadataFieldValues[$field->field_name] ?? null,
                            [
                                'class' => 'form-select',
                                'wire:model' => "course_attribute.{$field->field_name}",
                                'required' => $field->is_required ? true : false,
                                'placeholder' => $field->field_placeholder,
                                'wire:change' =>
                                    $field->lookup_with == 'boards'
                                        ? "getBookSeries(\$event.target.value, \$wire.course_attribute.medium)"
                                        : ($field->lookup_with == 'mediums'
                                            ? "getBookSeries(\$wire.course_attribute.board, \$event.target.value)"
                                            : ($field->lookup_with == 'book_series'
                                                ? 'getSeriesId($event.target.value)'
                                                : ($field->lookup_with == 'classes'
                                                    ? 'getSubjectsByClass($event.target.value)'
                                                    : null))),
                            ],
                        ) !!}
                    @break

                    @case('multiselect')
                        {!! Form::select(
                            "course_attribute[{$field->field_name}][{$field->id}]",
                            $modelsDataList[$field->lookup_with] ?? [],
                            $metadataFieldValues[$field->field_name] ?? null,
                            [
                                'class' => 'form-select js-select2',
                                'wire:model' => "course_attribute.{$field->field_name}",
                                'required' => $field->is_required ? true : false,
                                'placeholder' => $field->field_placeholder,
                            ],
                        ) !!}
                    @break

                    @case('radio')
                        {!! Form::radio(
                            "course_attribute[{$field->field_name}][{$field->id}]",
                            1,
                            $metadataFieldValues[$field->field_name] ?? false,
                            [
                                'class' => 'form-check-input',
                                'wire:model' => $field->field_name,
                                'required' => $field->is_required ? true : false,
                            ],
                        ) !!}
                    @break

                    {{-- @case('select')
                        {!! Form::select(
                            "course_attribute[{$field->field_name}][{$field->id}]",
                            $modelsDataList[$field->lookup_with] ?? [],
                            $metadataFieldValues[$field->field_name] ?? null,
                            [
                                'class' => 'form-select js-select2',
                                'wire:model' => "course_attribute.{$field->field_name}",
                                'required' => $field->is_required ? true : false,
                                'placeholder' => $field->field_placeholder,
                                'wire:change' =>
                                    $field->lookup_with == 'boards'
                                        ? "getBookSeries(\$event.target.value, \$wire.course_attribute.medium)"
                                        : ($field->lookup_with == 'mediums'
                                            ? "getBookSeries(\$wire.course_attribute.board, \$event.target.value)"
                                            : ($field->lookup_with == 'book_series'
                                                ? 'getSeriesId($event.target.value)'
                                                : ($field->lookup_with == 'classes'
                                                    ? 'getSubjectsByClass($event.target.value)'
                                                    : null))),
                            ],
                        ) !!}
                    @break --}}
                    @case('text')
                        {!! Form::text(
                            "course_attribute[{$field->field_name}][{$field->id}]",
                            $metadataFieldValues[$field->field_name] ?? null,
                            [
                                'class' => 'form-control',
                                'wire:model' => $field->field_name,
                                'required' => $field->is_required ? true : false,
                                'placeholder' => $field->field_placeholder,
                            ],
                        ) !!}
                    @break

                    @case('textarea')
                        @php
                            $isRichText = in_array($field->field_name, [
                                'course_overview',
                                'instructor',
                                'requirements',
                                'what_you_will_learn',
                                'description',
                            ]);
                            $currentValue = $metadataFieldValues[$field->field_name] ?? '';
                            $encodedContent = htmlspecialchars(json_encode($currentValue), ENT_QUOTES, 'UTF-8');
                        @endphp

                        <div class="textarea-grid" wire:ignore>
                            <div class="textarea-container">
                                @if ($isRichText)
                                    <div x-data="quillEditor({
                                        fieldName: '{{ $field->field_name }}',
                                        initialContent: {!! $encodedContent !!}
                                    })" x-init="init()" class="quill-editor-container">
                                        <div x-ref="editor" class="form-control" style="min-height: 120px;"></div>

                                        <textarea name="course_attribute[{{ $field->field_name }}][{{ $field->id }}]" id="hidden-{{ $field->field_name }}"
                                            x-ref="hiddenTextarea" style="display: none;">{!! $currentValue !!}</textarea>
                                    </div>
                                @else
                                    {!! Form::textarea("course_attribute[{$field->field_name}][{$field->id}]", $currentValue, [
                                        'class' => 'form-control textarea-input',
                                        'rows' => '1',
                                        'required' => $field->is_required ? true : false,
                                        'placeholder' => $field->field_placeholder,
                                        'data-field-name' => $field->field_name,
                                    ]) !!}
                                @endif
                            </div>
                        </div>
                    @break

                    @case('number')
                        {!! Form::number(
                            "course_attribute[{$field->field_name}][{$field->id}]",
                            $metadataFieldValues[$field->field_name] ?? null,
                            [
                                'class' => 'form-control',
                                'wire:model' => $field->field_name,
                                'required' => $field->is_required ? true : false,
                                'placeholder' => $field->field_placeholder,
                            ],
                        ) !!}
                    @break

                    {{-- @case('file')
                        <!-- For content_file, allow multiple file selection -->
                        @if ($field->field_name === 'content_file' || $field->field_name === 'attach_file_pdfs')
                            {!! Form::file("course_attribute[{$field->field_name}][{$field->id}][]", [
                                'class' => 'form-control',
                                'wire:model' => $field->field_name,
                                'required' => $field->is_required ? true : false,
                                'multiple' => 'multiple',
                            ]) !!}
                        @else
                            {!! Form::file("course_attribute[{$field->field_name}][{$field->id}]", [
                                'class' => 'form-control',
                                'wire:model' => $field->field_name,
                                'required' => $field->is_required ? true : false,
                            ]) !!}
                        @endif
                    @break --}}
                    @case('file')
                        {{-- For content_file or attach_file_pdfs, allow multiple file selection --}}
                        {{-- @if ($field->field_name === 'content_file' || $field->field_name === 'attach_file_pdfs')
                            @if (isset($course) && !empty($metadataFieldValues[$field->field_name]))
                                <div class="mb-2">
                                    <label>Uploaded Files:</label>
                                    <ul>
                                        @foreach ($metadataFieldValues[$field->field_name] as $file)
                                            <li>
                                                <a href="{{ Storage::url($file) }}" target="_blank">
                                                    {{ basename($file) }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            {!! Form::file("course_attribute[{$field->field_name}][{$field->id}][]", [
                                'class' => 'form-control',
                                'wire:model' => $field->field_name,
                                'required' => $field->is_required ? true : false,
                                'multiple' => 'multiple',
                            ]) !!}
                        @else
                            @if (isset($course) && !empty($metadataFieldValues[$field->field_name]))
                                <div class="mb-2">
                                    <img src="{{ Storage::url($metadataFieldValues[$field->field_name]) }}"alt="course-image"
                                        width="100" height="50">
                                </div>
                            @endif
                            {!! Form::file("course_attribute[{$field->field_name}][{$field->id}]", [
                                'class' => 'form-control',
                                'wire:model' => $field->field_name,
                                'required' => $field->is_required ? true : false,
                            ]) !!}
                        @endif --}}

                        {!! Form::file("course_attribute[{$field->field_name}][{$field->id}]", [
                            'class' => 'form-control',
                            'wire:model' => $field->field_name,
                            'required' => $field->is_required ? true : false,
                        ]) !!}
                        @if (isset($course) && !empty($metadataFieldValues[$field->field_name]))
                            <div class="mb-2 mt-2">
                                <img src="{{ Storage::url($metadataFieldValues[$field->field_name]) }}"alt="course-image"
                                    width="300" height="450">
                            </div>
                            {!! Form::hidden(
                                "course_attribute[{$field->field_name}][{$field->id}]",
                                $metadataFieldValues[$field->field_name],
                            ) !!} <!-- Hidden input field -->
                        @endif
                    @break

                    @case('checkbox')
                        {!! Form::checkbox(
                            "course_attribute[{$field->field_name}][{$field->id}]",
                            1,
                            $metadataFieldValues[$field->field_name] ?? false,
                            [
                                'class' => 'form-check-input',
                                'wire:model' => $field->field_name,
                                'required' => $field->is_required ? true : false,
                            ],
                        ) !!}
                    @break

                    @case('date')
                        {!! Form::date(
                            "course_attribute[{$field->field_name}][{$field->id}]",
                            $metadataFieldValues[$field->field_name] ?? null,
                            [
                                'class' => 'form-control',
                                'wire:model' => $field->field_name,
                                'required' => $field->is_required ? true : false,
                            ],
                        ) !!}
                    @break

                    @case('datetime')
                        {!! Form::datetimeLocal(
                            "course_attribute[{$field->field_name}][{$field->id}]",
                            $metadataFieldValues[$field->field_name] ?? null,
                            [
                                'class' => 'form-control',
                                'wire:model' => $field->field_name,
                                'required' => $field->is_required ? true : false,
                            ],
                        ) !!}
                    @break

                    @default
                @endswitch

            </div>
        @endforeach
    @endif
    <div class="text-end">
        <button type="submit" class="btn btn-primary">Submit</button>
        <button type="reset" class="btn btn-secondary" onclick="window.location.reload();">Reset</button>
    </div>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('quillEditor', ({
                fieldName,
                initialContent
            }) => ({
                quill: null,
                fieldName,
                initialContent,

                init() {
                    if (this.quill) return;

                    this.quill = new Quill(this.$refs.editor, {
                        modules: {
                            toolbar: [
                                ['bold', 'italic', 'underline'],
                                [{
                                    'script': 'super'
                                }, {
                                    'script': 'sub'
                                }],
                                ['image']
                            ]
                        },
                        theme: 'snow'
                    });

                    // Set content
                    const content = this.initialContent || '';
                    this.quill.root.innerHTML = content;
                    this.updateHiddenTextarea(content);

                    this.quill.on('text-change', () => {
                        const updatedContent = this.quill.root.innerHTML;
                        this.updateHiddenTextarea(updatedContent);
                    });
                },

                updateHiddenTextarea(content) {
                    const textarea = this.$refs.hiddenTextarea;
                    if (textarea) {
                        textarea.value = content;
                        textarea.dispatchEvent(new Event('input'));
                    }
                }
            }));
        });

        // Sync on form submit
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function() {
                    document.querySelectorAll('.quill-editor-container').forEach(editor => {
                        const quill = editor.__x?.$data?.quill;
                        const textarea = editor.querySelector('textarea');
                        if (quill && textarea) {
                            textarea.value = quill.root.innerHTML;
                        }
                    });
                });
            }
        });
    </script>


</div>
@push('scripts')
    <script>
        // Display '₹' or '%' symbol before the discount value
        document.addEventListener('DOMContentLoaded', function() {
            const discountType = document.getElementById('discount_type');
            const discountSymbol = document.getElementById('discount-symbol');

            function updateDiscountSymbol() {
                setTimeout(function() {
                    discountSymbol.textContent = discountType.value === 'flat' ? '₹' : '%';
                }, 500);
            }
            updateDiscountSymbol();
            discountType.addEventListener('change', updateDiscountSymbol);
        });
        // Initialize comboTree for non-academic category selection
        document.addEventListener('livewire:init', function() {
            let comboTreeForCourses;
            const jsonData = {!! json_encode($allChildCategories) !!};
            const selectedSubCategory =
                {{ isset($selectedSubCategory) ? json_encode($selectedSubCategory) : 'null' }};

            comboTreeForCourses = $("#non-academic-category-input-courses").comboTree({
                source: jsonData,
                isMultiple: false,
                selected: selectedSubCategory ? [selectedSubCategory] : [],
            });
            if (comboTreeForCourses?.onChange) {
                comboTreeForCourses.onChange(() => {
                    const selectedItems = comboTreeForCourses.getSelectedIds();
                    $('#subCategoryInput').val(selectedItems);
                });
            }

        });

        function initSelect2() {
            $(".js-select2").select2({
                closeOnSelect: false,
                placeholder: "Select",
                allowClear: false,
                tags: true
            });
        }
        document.addEventListener("DOMContentLoaded", function() {
            initSelect2();
        });
        document.addEventListener("livewire:load", function() {
            Livewire.hook('message.processed', (message, component) => {
                initSelect2();
            });
        });
        document.addEventListener("change", function(event) {
            if (event.target.matches("[wire\\:model='selectedCategory']")) {
                setTimeout(initSelect2, 1000); // Small delay to allow Livewire to update the DOM
            }
        });
        document.addEventListener("change", function(event) {
            if (event.target.matches("[wire\\:model='price']")) {
                setTimeout(initSelect2, 1000); // Small delay to allow Livewire to update the DOM
            }
        });
        document.addEventListener("change", function(event) {
            if (event.target.matches("[wire\\:model='discount_type']")) {
                setTimeout(initSelect2, 1000); // Small delay to allow Livewire to update the DOM
            }
        });

        document.addEventListener("change", function(event) {
            if (event.target.matches("[wire\\:model='discount_value']")) {
                setTimeout(initSelect2, 1000); // Small delay to allow Livewire to update the DOM
            }
        });
    </script>
@endpush
