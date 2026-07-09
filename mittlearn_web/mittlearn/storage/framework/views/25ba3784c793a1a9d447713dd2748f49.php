<?php $__env->startSection('content'); ?>
    <div id="page-header" class="page-header">
        <section class="section">
            <div class="pagetitle">
                <h1>About Us Page Content</h1>
                <nav>
                    <ol class="breadcrumb">
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="text-end mb-2">
                    <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-primary"><i class="ri-arrow-left-line"></i></a>
                </div>
                <?php echo e(Form::model($data, ['url' => route('cms-about.save'), 'files' => true])); ?>

                <?php echo csrf_field(); ?>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-3">
                                <h4 class="card-title">Banner</h4>
                                <hr class="form-divider">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <?php echo Form::label('banner_image', 'Banner Image', ['class' => 'form-label']); ?>

                                    <small class="form-text text-muted">(Allowed formats: PNG, PDF, SVG. Image dimensions:
                                        50x50 pixels)</small>
                                    <?php echo Form::file('banner_image', ['class' => 'form-control', 'id' => 'formFile']); ?>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($data) && $data['banner_image']): ?>
                                        <img src="<?php echo e(Storage::url($data['banner_image'])); ?>" alt="Featured Image"
                                            class="img-thumbnail mt-2" style="max-width: 150px;">
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <?php echo Form::label('title', 'Title', ['class' => 'form-label required']); ?>

                                    <?php echo Form::text('title', null, ['class' => 'form-control']); ?>

                                </div>
                                <!-- Textarea with onkeyup event for banner description -->
                                <div class="col-md-12 col-sm-6 col-xs-12 position-relative">
                                    <?php echo Form::label('banner_description', 'Description', ['class' => 'form-label required']); ?>

                                    <?php echo Form::textarea('banner_description', null, [
                                        'class' => 'form-control',
                                        'rows' => '1',
                                        'id' => 'textarea',
                                        'onkeyup' => 'updateWordCount(this, 50)',
                                    ]); ?>

                                    <small id="word-count-message" class="position-absolute text-muted bg-white p-1 rounded"
                                        style="bottom: 8px; right: 10px;">
                                        Words: 0/50
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-3">
                                <h4 class="card-title">Mittsure Technologies At a Glance</h4>
                                <hr class="form-divider">
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <?php echo Form::label('glance_image', 'Image', ['class' => 'form-label']); ?>

                                    <small class="form-text text-muted">(Allowed formats: PNG, PDF, SVG. Image dimensions:
                                        50x50 pixels)</small>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($glance['glance_image'])): ?>
                                        <div class="mb-3">
                                            <img src="<?php echo e(asset('storage/' . $glance['glance_image'])); ?>" alt="Glance Image"
                                                class="img-thumbnail" style="max-height: 150px;">
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php echo Form::file('glance_image', ['class' => 'form-control', 'id' => 'formFile']); ?>

                                </div>
                                <!-- Textarea with onkeyup event for mittsure description -->
                                <div class="col-md-6 col-sm-6 col-xs-12 position-relative">
                                    <?php echo Form::label('mittsure_description', 'Description', ['class' => 'form-label required']); ?>

                                    <?php echo Form::textarea('mittsure_description', $glance['mittsure_at_glance_description'] ?? null, [
                                        'class' => 'form-control',
                                        'rows' => '1',
                                        'id' => 'textarea',
                                        'onkeyup' => 'updateWordCount(this, 50)',
                                    ]); ?>

                                    <small id="word-count-message" class="position-absolute text-muted bg-white p-1 rounded"
                                        style="bottom: 8px; right: 10px;">
                                        Words: 0/50
                                    </small>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <?php echo Form::label('button', 'Button', ['class' => 'form-label required']); ?>

                                    <?php echo Form::text('button', $glance['button'] ?? null, ['class' => 'form-control']); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-3">
                                <h4 class="card-title">Mittsure</h4>
                                <hr class="form-divider">
                                <!-- Textarea with onkeyup event for mittsure section description -->
                                <div class="col-md-6 col-sm-6 col-xs-12 position-relative">
                                    <?php echo Form::label('mittsure_section_description', 'Description', ['class' => 'form-label required']); ?>

                                    <small class="form-text text-muted">(Allowed formats: PNG, PDF, SVG. Image dimensions:
                                        50x50 pixels)</small>
                                    <?php echo Form::textarea('mittsure_section_description', $mittsure_section['mittsure_section_description'] ?? null, [
                                        'class' => 'form-control',
                                        'rows' => '1',
                                        'id' => 'textarea',
                                        'onkeyup' => 'updateWordCount(this, 50)',
                                    ]); ?>

                                    <small id="word-count-message" class="position-absolute text-muted bg-white p-1 rounded"
                                        style="bottom: 8px; right: 10px;">
                                        Words: 0/50
                                    </small>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <?php echo Form::label('mittsure_section_image', 'Image', ['class' => 'form-label']); ?>

                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($mittsure_section['mittsure_section_image'])): ?>
                                        <div class="mb-3">
                                            <img src="<?php echo e(asset('storage/' . $mittsure_section['mittsure_section_image'])); ?>"
                                                alt="Glance Image" class="img-thumbnail" style="max-height: 150px;">
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php echo Form::file('mittsure_section_image', ['class' => 'form-control', 'id' => 'formFile']); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-3">
                                <h4 class="card-title">Versatile Activities</h4>
                                <hr class="form-divider">
                                <!-- Textarea with onkeyup event for versatile activities description -->
                                <div class="col-md-6 col-sm-6 col-xs-12 position-relative">
                                    <?php echo Form::label('versatile_activities_description', 'Description', ['class' => 'form-label required']); ?>

                                    <?php echo Form::textarea('versatile_activities_description', null, [
                                        'class' => 'form-control',
                                        'rows' => '1',
                                        'id' => 'textarea',
                                        'onkeyup' => 'updateWordCount(this, 50)',
                                    ]); ?>

                                    <small id="word-count-message" class="position-absolute text-muted bg-white p-1 rounded"
                                        style="bottom: 8px; right: 10px;">
                                        Words: 0/50
                                    </small>
                                </div>
                                <!-- Radio buttons for Versatile Activities -->
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <?php echo Form::label('versatile_activities', 'Versatile Activities', ['class' => 'form-label required']); ?>

                                    <div class="form-check">
                                        <?php echo Form::radio('versatile_activities', 'random', false, [
                                            'class' => 'form-check-input',
                                            'id' => 'view_random',
                                            'checked' => 'checked',
                                            'onclick' => 'toggleDropdown(false)',
                                        ]); ?>

                                        <?php echo Form::label('view_random', 'View Random from Groups', ['class' => 'form-check-label']); ?>

                                    </div>
                                    <div class="form-check">
                                        <?php echo Form::radio('versatile_activities', 'selected', false, [
                                            'class' => 'form-check-input',
                                            'id' => 'view_selected',
                                            'onclick' => 'toggleDropdown(true)',
                                        ]); ?>

                                        <?php echo Form::label('view_selected', 'View Selected', ['class' => 'form-check-label']); ?>

                                    </div>
                                </div>

                                <!-- Dropdown for categories (hidden by default) -->
                                <div class="col-md-6 col-sm-6 col-xs-12" id="categoryDropdown" style="display: none;">
                                    <?php echo Form::label('category_id', 'Select Categories', ['class' => 'form-label required']); ?>

                                    <small class="text-muted">Select a minimum of 20 and a maximum of 20 categories.</small>
                                    <?php echo Form::select('category_id[]', $categories, null, [
                                        'class' => 'form-select',
                                        'id' => 'multiSelect',
                                        'multiple' => 'multiple',
                                    ]); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-3">
                                <h4 class="card-title">Our Leadership</h4>
                                <hr class="form-divider">
                                <!-- Textarea with onkeyup event -->
                                <div class="col-md-12 col-sm-6 col-xs-12 position-relative">
                                    <?php echo Form::label('our_leadership_description', 'Our Leadership Description', ['class' => 'form-label required']); ?>

                                    <?php echo Form::textarea('our_leadership_description', $leadership['our_leadership_description'] ?? null, [
                                        'class' => 'form-control',
                                        'rows' => '1',
                                        'id' => 'textarea',
                                        'onkeyup' => 'updateWordCount(this, 50)',
                                    ]); ?>

                                    <small id="word-count-message" class="position-absolute text-muted bg-white p-1 rounded"
                                        style="bottom: 8px; right: 10px;">
                                        Words: 0/50
                                    </small>
                                </div>
                                <!-- Leadership Dropdowns -->
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = ['primary', 'secondary', 'third', 'fourth', 'fifth']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <?php echo Form::label('user_id_' . $index, 'Select ' . ucfirst($index) . ' View Leadership', [
                                            'class' => 'form-label required',
                                        ]); ?>

                                        <?php echo Form::select('user_id_' . $index, $leaders, $leadership[$index] ?? null, [
                                            'class' => 'form-control form-select fs-8',
                                            'placeholder' => '--Select--',
                                        ]); ?>

                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>


                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-3">
                                <h4 class="card-title">Our Vision</h4>
                                <hr class="form-divider">

                                <div class="col-md-12 col-sm-6 col-xs-12 position-relative">
                                    <?php echo Form::label('vision_description', 'Description', ['class' => 'form-label required']); ?>

                                    <?php echo Form::textarea('vision_description', $data->vision_description ?? null, [
                                        'class' => 'form-control',
                                        'rows' => '1',
                                        'id' => 'textarea',
                                        'onkeyup' => 'updateWordCount(this, 50)',
                                    ]); ?>

                                    <small id="word-count-message" class="position-absolute text-muted bg-white p-1 rounded"
                                        style="bottom: 8px; right: 10px;">
                                        Words: 0/50
                                    </small>
                                </div>

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <?php echo Form::label('vision_image', 'Banner Image', ['class' => 'form-label']); ?>

                                    <small class="form-text text-muted">(Allowed formats: PNG, PDF, SVG. Image dimensions:
                                        50x50 pixels)</small>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($data->vision_image)): ?>
                                        <div class="mb-3">
                                            <img src="<?php echo e(asset('storage/' . $data->vision_image)); ?>" alt="Glance Image"
                                                class="img-thumbnail" style="max-height: 150px;">
                                        </div>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <?php echo Form::file('vision_image', ['class' => 'form-control', 'id' => 'vision_image']); ?>

                                </div>

                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <?php echo Form::label('about_vision', 'About Vision', ['class' => 'form-label required']); ?>

                                    <div class="quill-editor-full" id="editor" style="height: 100px;"></div>
                                    <?php echo Form::hidden('about_vision', $data->about_vision ?? null, ['id' => 'editor-content']); ?>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-3">
                                <h4 class="card-title">Our Programs</h4>
                                <hr class="form-divider">

                                <!-- Overall Description -->
                                <div class="col-md-12 col-sm-6 col-xs-12 position-relative">
                                    <?php echo Form::label('program_description', 'Overall Description', ['class' => 'form-label required']); ?>

                                    <?php echo Form::textarea('program_description', null, [
                                        'class' => 'form-control',
                                        'rows' => '1',
                                        'id' => 'program_description',
                                        'onkeyup' => 'updateWordCount(this, 50)',
                                    ]); ?>

                                    <small id="word-count-message-program-description"
                                        class="position-absolute text-muted bg-white p-1 rounded"
                                        style="bottom: 8px; right: 10px;">
                                        Words: 0/50
                                    </small>
                                </div>
                                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('our-program', ['programs' => $programs]);

$key = null;

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-677916662-0', null);

$__html = app('livewire')->mount($__name, $__params, $key);

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                            </div>
                            <div class="text-end mt-3">
                                <?php echo Form::submit('Submit', ['class' => 'btn btn-primary']); ?>

                                <?php echo Form::reset('Reset', ['class' => 'btn btn-secondary']); ?>

                            </div>
                        </div>
                    </div>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($activitiesGallaryImages) && $activitiesGallaryImages): ?>
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row g-3 mb-3">
                                    <h4 class="card-title">Our Activities</h4>
                                    <hr class="form-divider">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <?php echo e($activitiesGallaryImages->folder_name); ?> Images
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <a class="btn btn-sm btn-info me-1"
                                            href="<?php echo e(route('media.gallery.folder.view', $activitiesGallaryImages->id)); ?>">View
                                            / Add Images</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
            <?php echo e(Form::close()); ?>

        </section>
    </div>
<?php $__env->stopSection(); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const multiSelect = document.getElementById('multiSelect');

        // Toggle the dropdown visibility
        window.toggleDropdown = function(showDropdown) {
            const dropdown = document.getElementById('categoryDropdown');
            dropdown.style.display = showDropdown ? 'block' : 'none';
        };

        // Validate the selection limits
        multiSelect.addEventListener('change', function() {
            const selectedOptions = Array.from(multiSelect.selectedOptions);
            if (selectedOptions.length > 20) {
                alert('You can select a maximum of 20 categories.');
                // Deselect the last selected option
                selectedOptions[selectedOptions.length - 1].selected = false;
            } else if (selectedOptions.length < 1) {
                alert('Please select at least one category.');
            }
        });
    });


    function updateWordCount(element, maxWords) {
        const text = element.value.trim();
        const words = text.split(/\s+/).filter(word => word.length > 0);
        const wordCount = words.length;

        if (wordCount > maxWords) {
            element.value = words.slice(0, maxWords).join(" ");
            document.getElementById('word-count-message').textContent = `Maximum ${maxWords} words allowed.`;
        } else {
            document.getElementById('word-count-message').textContent = `Words: ${wordCount}/${maxWords}`;
        }
    }
    document.addEventListener("DOMContentLoaded", function() {
        const textarea = document.getElementById('textarea');
        updateWordCount(textarea, 50);
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editor = document.querySelector('.quill-editor-full');
        const initialContent = <?php echo json_encode(old('about_vision', isset($data) ? $data->about_vision : '')); ?>;
        if (editor && window.Quill) {
            const quill = Quill.find(editor);
            if (quill) {
                quill.root.innerHTML = initialContent;
                quill.on('text-change', function() {
                    document.getElementById('editor-content').value = quill.root.innerHTML.trim();
                });
            }
            document.querySelector('form').addEventListener('submit', function(event) {
                const quillContent = quill.root.innerHTML.trim();
                document.getElementById('editor-content').value = quillContent;
                if (!quillContent) {
                    alert('The about_vision field is required.');
                    event.preventDefault();
                }
            });
        }
    });
</script>

<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/admin/cms/about-us/index.blade.php ENDPATH**/ ?>