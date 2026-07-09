<?php $__env->startSection('content'); ?>
    <?php
        $flag = isset($cms) && !empty($cms) ? 1 : 0;
        $heading = $flag ? 'Edit' : 'Add';
    ?>
    <div id="page-header" class="page-header">
        <section class="section">
            <div class="pagetitle">
                <h1>CMS Pages</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Home</li>
                        <li class="breadcrumb-item active"><?php echo e($heading); ?> CMS</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="text-end mb-2">
                    <a href="<?php echo e(route('cms.index')); ?>" class="btn btn-primary"><i class="ri-arrow-left-line"></i></a>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title"><?php echo e($flag == 1 ? $cms->name : 'Add Cms'); ?> </h4>
                            <hr class="form-divider">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($flag): ?>
                                <?php echo e(Form::model($cms, ['url' => route('cms.save'), 'class' => 'row g-3', 'files' => true])); ?>

                                <?php echo e(Form::hidden('id', null)); ?>

                            <?php else: ?>
                                <?php echo e(Form::open(['url' => route('cms.save'), 'class' => 'row g-3'])); ?>

                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?> <?php echo csrf_field(); ?>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo Form::label('title', ' Page Slug', ['class' => 'form-label required']); ?>

                                <?php echo Form::text('title', null, ['class' => 'form-control', 'placeholder' => 'Enter Slug']); ?>

                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo Form::label('name', '  Page Name', ['class' => 'form-label required']); ?>

                                <?php echo Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Enter Name']); ?>

                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo Form::label('image', ' Image', ['class' => 'form-label ']); ?>

                                <small class="form-text text-muted">(Allowed formats: PNG, PDF, SVG. Image dimensions: 50x50 pixels)</small>
                                <?php echo Form::file('image', ['class' => 'form-control', 'placeholder' => 'Enter Name']); ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($flag == 1): ?>
                                    <img src="<?php echo e(Storage::url('uploads/cms_pages/' . $cms->image)); ?>" alt="Image"
                                        width="200" height="100">
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo Form::label('meta_title', ' Meta Title', ['class' => 'form-label ']); ?>

                                <?php echo Form::text('meta_title', null, ['class' => 'form-control', 'placeholder' => 'Enter  Meta Title']); ?>

                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo Form::label('meta_description', ' Meta Description', ['class' => 'form-label ']); ?>

                                <?php echo Form::textarea('meta_description', null, [
                                    'class' => 'form-control',
                                    'placeholder' => 'Meta Description',
                                    'rows' => '1',
                                ]); ?>

                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo Form::label('meta_keywords', ' Meta Keyword', ['class' => 'form-label ']); ?>

                                <?php echo Form::text('meta_keywords', null, ['class' => 'form-control', 'placeholder' => 'Enter Meta Keyword']); ?>

                            </div>
                            <div class="col-lg-12">
                                <?php echo Form::label('description', 'Description', ['class' => 'form-label required']); ?>

                                <div class="quill-editor-full" id="editor" style="height: 300px;"></div>
                                <?php echo Form::hidden('description', null, ['id' => 'editor-content']); ?>

                            </div>
                            <div class="text-end">
                                <?php echo Form::submit($flag ? 'Update' : 'Submit', ['class' => 'btn btn-primary']); ?>

                                <?php echo Form::reset('Reset', ['class' => 'btn btn-secondary']); ?>

                            </div>
                            <?php echo e(Form::close()); ?>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
<?php $__env->stopSection(); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editor = document.querySelector('.quill-editor-full');
        const initialContent = <?php echo json_encode(old('description', isset($cms) ? $cms->description : '')); ?>;
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
                    alert('The body field is required.');
                    event.preventDefault();
                }
            });
        }
    });
</script>

<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/admin/cms/cmsPage/add_edit.blade.php ENDPATH**/ ?>