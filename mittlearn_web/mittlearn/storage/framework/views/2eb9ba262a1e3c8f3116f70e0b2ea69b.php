<div class="row">
    
    <div class="col-md-6 col-sm-6 col-xs-12 input-div">
        <?php echo Form::label('group', 'Group', ['class' => 'form-label required']); ?>

        <?php echo Form::select('group', $categories, $selectedCategory, [
            'class' => 'form-select',
            'required',
            'placeholder' => '--Select Group--',
            'wire:model' => 'selectedCategory',
            'wire:change' => 'loadConstants($event.target.value)',
        ]); ?>

    </div>
    <!-- Conditional Child Category Dropdown for academic type only -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($childCategories) && $selectedCategory == 1): ?>
        <div class="col-md-6 col-sm-6 col-xs-12 input-div" id="acadmic-category-div">
            <?php echo Form::label('subgroup', 'Subgroup', ['class' => 'form-label required']); ?>

            <?php echo Form::select('subgroup', $childCategories, $selectedSubCategory, [
                'class' => 'form-select',
                'required',
                'placeholder' => '--Select Subgroup--',
                'wire:model' => 'selectedSubCategory',
                'wire:change' => 'getMetaDataByCategoryId',
            ]); ?>

        </div>
    <?php else: ?>
        <div class="col-md-6 col-sm-6 col-xs-12 input-div <?php echo e($selectedCategory == 2 ? '' : 'd-none'); ?>"
            id="non-acadmic-category-div">
            <div class="" wire:ignore>
                <?php echo Form::label('subgroup', 'Subgroup', ['class' => 'form-label required']); ?>

                <?php echo e(Form::text('subgroup', null, ['class' => 'form-control', 'id' => 'non-academic-category-input-courses', 'autocomplete' => 'off', 'placeholder' => '--Select Subgroup--', 'required'])); ?>

                <?php echo Form::hidden('subgroup', '', ['id' => 'subCategoryInput']); ?> <!-- Hidden input field -->
            </div>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <div class="col-md-6 col-sm-6 col-xs-12 input-div">
        <?php echo Form::label('course_name', 'Book/Course Name', ['class' => 'form-label required']); ?>

        <?php echo Form::text('course_name', $course->course_name ?? null, [
            'class' => 'form-control',
            'wire:model' => 'course_name',
            'placeholder' => 'Enter Course Name',
            'required' => true,
        ]); ?>

        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['course_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
            <div class="text-danger"><?php echo e($message); ?></div>
        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($selectedCategory == 2): ?>
        <div class="col-md-6 col-sm-6 col-xs-12 input-div">
            <?php echo Form::label('price_type', 'Course Price Type (Free or Paid)', ['class' => 'form-label required']); ?>

            <?php echo e(Form::select('price_type', $priceType, $course->price_type ?? [], ['class' => 'form-control', 'placeholder' => '--select--', 'wire:model' => 'type', 'wire:change' => 'priceTypeChange', 'required' => true])); ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['price_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="text-danger"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array($selectedCategory, [null, 1]) || ($selectedCategory == 2 && $type === 'paid')): ?>
        <div class="col-md-6 col-sm-6 col-xs-12 input-div">
            <?php echo Form::label('price', 'Price', ['class' => 'form-label required']); ?>

            <?php echo e(Form::number('price', $course->price ?? null, ['class' => 'form-control', 'placeholder' => 'Enter Price', 'wire:model' => 'price', 'wire:keyup' => "onChangeFiledValue(\$event.target.value, 'price')"])); ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="text-danger"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12 input-div">
            <?php echo Form::label('discount_type', 'Discount Type', ['class' => 'form-label required']); ?>

            <?php echo e(Form::select('discount_type', config('constants.DISCOUNT_TYPES'), $course->discount_type ?? null, ['class' => 'form-select', 'id' => 'discount_type', 'wire:model' => 'discount_type', 'placeholder' => '--Select--', 'wire:change' => "onChangeFiledValue(\$event.target.value, 'discount_type')"])); ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['discount_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="text-danger"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>

        <div class="col-md-6 col-sm-6 col-xs-12 input-div">
            <?php echo Form::label('discount_value', 'Discount Value', ['class' => 'form-label required']); ?>

            <div class="input-group">
                <span class="input-group-text" id="discount-symbol"></span>
                <?php echo e(Form::number('discount_value', $course->discount_value ?? null, ['class' => 'form-select', 'placeholder' => 'Enter Amount', 'wire:model' => 'discount_value', 'wire:keyup' => "onChangeFiledValue(\$event.target.value, 'discount_value')"])); ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['discount_value'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="text-danger"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
        <div class="col-md-6 col-sm-6 col-xs-12 input-div">
            <?php echo Form::label('amount', 'Amount', ['class' => 'form-label']); ?>

            <?php echo e(Form::text('amount', $amount ?? null, ['class' => 'form-control', 'placeholder' => 'Plan Final Price', 'readonly' => true])); ?>

        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(($showBoardSelect && !empty($courseMetadataFields)) || $course): ?>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $courseMetadataFields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            

            <div class="col-md-6 col-sm-6 col-xs-12 input-div">
                <?php echo Form::label($field->field_name, $field->field_label, [
                    'class' => 'form-label' . ($field->is_required ? ' required' : ''),
                ]); ?>

                
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array($field->field_name, ['book_cover_image', 'thumbnail_image', 'banner_image', 'instructor_image'])): ?>
                    <small>(jpg, png, svg, webp image, dimensions: 300x450)</small>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php switch($field->field_type):
                    case ('select'): ?>
                        <?php echo Form::select(
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
                        ); ?>

                    <?php break; ?>

                    <?php case ('multiselect'): ?>
                        <?php echo Form::select(
                            "course_attribute[{$field->field_name}][{$field->id}]",
                            $modelsDataList[$field->lookup_with] ?? [],
                            $metadataFieldValues[$field->field_name] ?? null,
                            [
                                'class' => 'form-select js-select2',
                                'wire:model' => "course_attribute.{$field->field_name}",
                                'required' => $field->is_required ? true : false,
                                'placeholder' => $field->field_placeholder,
                            ],
                        ); ?>

                    <?php break; ?>

                    <?php case ('radio'): ?>
                        <?php echo Form::radio(
                            "course_attribute[{$field->field_name}][{$field->id}]",
                            1,
                            $metadataFieldValues[$field->field_name] ?? false,
                            [
                                'class' => 'form-check-input',
                                'wire:model' => $field->field_name,
                                'required' => $field->is_required ? true : false,
                            ],
                        ); ?>

                    <?php break; ?>

                    
                    <?php case ('text'): ?>
                        <?php echo Form::text(
                            "course_attribute[{$field->field_name}][{$field->id}]",
                            $metadataFieldValues[$field->field_name] ?? null,
                            [
                                'class' => 'form-control',
                                'wire:model' => $field->field_name,
                                'required' => $field->is_required ? true : false,
                                'placeholder' => $field->field_placeholder,
                            ],
                        ); ?>

                    <?php break; ?>

                    <?php case ('textarea'): ?>
                        <?php
                            $isRichText = in_array($field->field_name, [
                                'course_overview',
                                'instructor',
                                'requirements',
                                'what_you_will_learn',
                                'description',
                            ]);
                            $currentValue = $metadataFieldValues[$field->field_name] ?? '';
                            $encodedContent = htmlspecialchars(json_encode($currentValue), ENT_QUOTES, 'UTF-8');
                        ?>

                        <div class="textarea-grid" wire:ignore>
                            <div class="textarea-container">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isRichText): ?>
                                    <div x-data="quillEditor({
                                        fieldName: '<?php echo e($field->field_name); ?>',
                                        initialContent: <?php echo $encodedContent; ?>

                                    })" x-init="init()" class="quill-editor-container">
                                        <div x-ref="editor" class="form-control" style="min-height: 120px;"></div>

                                        <textarea name="course_attribute[<?php echo e($field->field_name); ?>][<?php echo e($field->id); ?>]" id="hidden-<?php echo e($field->field_name); ?>"
                                            x-ref="hiddenTextarea" style="display: none;"><?php echo $currentValue; ?></textarea>
                                    </div>
                                <?php else: ?>
                                    <?php echo Form::textarea("course_attribute[{$field->field_name}][{$field->id}]", $currentValue, [
                                        'class' => 'form-control textarea-input',
                                        'rows' => '1',
                                        'required' => $field->is_required ? true : false,
                                        'placeholder' => $field->field_placeholder,
                                        'data-field-name' => $field->field_name,
                                    ]); ?>

                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    <?php break; ?>

                    <?php case ('number'): ?>
                        <?php echo Form::number(
                            "course_attribute[{$field->field_name}][{$field->id}]",
                            $metadataFieldValues[$field->field_name] ?? null,
                            [
                                'class' => 'form-control',
                                'wire:model' => $field->field_name,
                                'required' => $field->is_required ? true : false,
                                'placeholder' => $field->field_placeholder,
                            ],
                        ); ?>

                    <?php break; ?>

                    
                    <?php case ('file'): ?>
                        
                        

                        <?php echo Form::file("course_attribute[{$field->field_name}][{$field->id}]", [
                            'class' => 'form-control',
                            'wire:model' => $field->field_name,
                            'required' => $field->is_required ? true : false,
                        ]); ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($course) && !empty($metadataFieldValues[$field->field_name])): ?>
                            <div class="mb-2 mt-2">
                                <img src="<?php echo e(Storage::url($metadataFieldValues[$field->field_name])); ?>"alt="course-image"
                                    width="300" height="450">
                            </div>
                            <?php echo Form::hidden(
                                "course_attribute[{$field->field_name}][{$field->id}]",
                                $metadataFieldValues[$field->field_name],
                            ); ?> <!-- Hidden input field -->
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php break; ?>

                    <?php case ('checkbox'): ?>
                        <?php echo Form::checkbox(
                            "course_attribute[{$field->field_name}][{$field->id}]",
                            1,
                            $metadataFieldValues[$field->field_name] ?? false,
                            [
                                'class' => 'form-check-input',
                                'wire:model' => $field->field_name,
                                'required' => $field->is_required ? true : false,
                            ],
                        ); ?>

                    <?php break; ?>

                    <?php case ('date'): ?>
                        <?php echo Form::date(
                            "course_attribute[{$field->field_name}][{$field->id}]",
                            $metadataFieldValues[$field->field_name] ?? null,
                            [
                                'class' => 'form-control',
                                'wire:model' => $field->field_name,
                                'required' => $field->is_required ? true : false,
                            ],
                        ); ?>

                    <?php break; ?>

                    <?php case ('datetime'): ?>
                        <?php echo Form::datetimeLocal(
                            "course_attribute[{$field->field_name}][{$field->id}]",
                            $metadataFieldValues[$field->field_name] ?? null,
                            [
                                'class' => 'form-control',
                                'wire:model' => $field->field_name,
                                'required' => $field->is_required ? true : false,
                            ],
                        ); ?>

                    <?php break; ?>

                    <?php default: ?>
                <?php endswitch; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
<?php $__env->startPush('scripts'); ?>
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
            const jsonData = <?php echo json_encode($allChildCategories); ?>;
            const selectedSubCategory =
                <?php echo e(isset($selectedSubCategory) ? json_encode($selectedSubCategory) : 'null'); ?>;

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
<?php $__env->stopPush(); ?>
<?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/livewire/courses-form.blade.php ENDPATH**/ ?>