<?php $__env->startSection('content'); ?>

    <?php
        $flag = 0;
        $heading = 'Add';

        if (isset($content) && !empty($content)) {
            $flag = 1;
            $heading = 'Update';
        }
    ?>

    <div>
        <div class="pagetitle">
            <h1><?php echo e($heading); ?> Teacher Development Content</h1>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">

                            
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($flag == 1): ?>
                                <?php echo e(Form::model($content, ['route' => ['teacher.development.update', $content->id], 'method' => 'PUT', 'class' => 'row g-3', 'files' => true])); ?>

                            <?php else: ?>
                                <?php echo e(Form::open(['route' => 'teacher.development.store', 'class' => 'row g-3', 'files' => true])); ?>

                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            
                            <h5 class="card-title pb-0">Basic Info</h5>
                            <hr class="form-divider">
                            <div class="col-md-6">
                                <?php echo Form::label('type', 'Type', ['class' => 'form-label required']); ?>

                                <?php echo Form::select('type', config('constants.TDC_TYPE'), $content->type ?? 1, [
                                    'class' => 'form-control form-select',
                                    'required',
                                ]); ?>

                            </div>

                            <div class="col-md-6">
                                <?php echo Form::label('title', 'Title', ['class' => 'form-label required']); ?>

                                <?php echo Form::text('title', null, ['class' => 'form-control', 'required']); ?>

                            </div>

                            

                            
                            <div id="video-wrapper" class="col-md-12">
                                <h5 class="card-title mt-3">Videos</h5>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($flag == 1): ?>
                                    
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $content->videos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="video-row border p-3 mb-3 row position-relative">

                                            
                                            <input type="hidden" name="videos[<?php echo e($i); ?>][video_id]"
                                                value="<?php echo e($video->id); ?>">

                                            
                                            <input type="hidden" name="videos[<?php echo e($i); ?>][existing_file]"
                                                value="<?php echo e($video->video_file); ?>">
                                            <div class="col-md-2">
                                                <label class="form-label">Order</label>
                                                <input type="number" name="videos[<?php echo e($i); ?>][order]"
                                                    class="form-control" value="<?php echo e($video->order ?? $i + 1); ?>"
                                                    min="1" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label required">Video Title</label>
                                                <input type="text" name="videos[<?php echo e($i); ?>][title]"
                                                    class="form-control" value="<?php echo e($video->video_title); ?>" required>
                                            </div>

                                            <div class="col-md-4">
                                                <label class="form-label">
                                                    Upload Video
                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($video->video_file): ?>
                                                        <small class="text-muted">(leave empty to keep existing)</small>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </label>
                                                <input type="file" name="videos[<?php echo e($i); ?>][file]"
                                                    class="form-control" accept="video/mp4,video/x-m4v,video/*">

                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($video->video_file): ?>
                                                    <small>
                                                        <a href="<?php echo e(Storage::url($video->video_file)); ?>" target="_blank">
                                                            ▶ View Existing File
                                                        </a>
                                                    </small>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </div>

                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($i > 0): ?>
                                                <div class="col-md-12 text-end mt-2">
                                                    <button type="button"
                                                        class="btn btn-sm btn-danger removeVideo">Remove</button>
                                                </div>
                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php else: ?>
                                    
                                    <div class="video-row border p-3 mb-3 row">
                                        <div class="col-md-2">
                                            <label class="form-label">Order</label>
                                            <input type="number" name="videos[0][order]" class="form-control"
                                                value="1" min="1" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label required">Video Title</label>
                                            <input type="text" name="videos[0][title]" class="form-control" required>
                                        </div>
                                        <div class="col-md-4">
                                            <label class="form-label">Upload Video</label>
                                            <input type="file" name="videos[0][file]" class="form-control"
                                                accept="video/mp4,video/x-m4v,video/*">
                                        </div>
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            </div>

                            <div class="col-md-12 text-end">
                                <button type="button" class="btn btn-secondary" id="addVideoBtn">+ Add More Video</button>
                            </div>

                            
                            <h5 class="card-title mt-3">School Access</h5>
                            <hr class="form-divider">

                            <div class="col-md-12 mb-2">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_for_all" name="is_for_all"
                                        value="1"
                                        <?php echo e(isset($content) && $content->is_for_all_schools ? 'checked' : ''); ?>>
                                    <label class="form-check-label" for="is_for_all">
                                        Available for All Schools
                                    </label>
                                </div>
                            </div>

                            <div class="col-md-12" id="schoolBox">
                                <label class="form-label">Assign to Individual Schools</label>
                                <select name="school_ids[]" id="school_ids" class="form-select js-select2" multiple
                                    style="width: 100%;">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $schools ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schoolId => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($schoolId); ?>"
                                            <?php echo e(isset($content) && $content->schools->pluck('id')->contains($schoolId) ? 'selected' : ''); ?>>
                                            <?php echo e($name); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </select>
                            </div>

                            
                            <div class="col-md-12 text-end">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="<?php echo e(route('teacher.development.index')); ?>" class="btn btn-secondary">Cancel</a>
                            </div>

                            <?php echo e(Form::close()); ?>


                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <script>
        $(document).ready(function() {

            // Select2 init
            $(".js-select2").select2({
                closeOnSelect: false,
                placeholder: "Select Schools",
                allowClear: true,
            });

            // Toggle school box on load and on change
            function toggleSchoolBox() {
                if ($('#is_for_all').is(':checked')) {
                    $('#schoolBox').hide();
                    $('#school_ids').val(null).trigger('change');
                } else {
                    $('#schoolBox').show();
                }
            }

            toggleSchoolBox();
            $('#is_for_all').on('change', toggleSchoolBox);
        });

        // -----------------------------------------------
        // VIDEO INDEX: use a counter that always increases
        // so indices never collide even after removes
        // -----------------------------------------------
        let videoIndex = <?php echo e(isset($content) ? count($content->videos) : 1); ?>;

        document.getElementById('addVideoBtn').addEventListener('click', function() {
            const html = `
                <div class="video-row border p-3 mb-3 row">
                    <div class="col-md-2">
                        <label class="form-label">Order</label>
                        <input type="number" name="videos[${videoIndex}][order]" 
                            class="form-control" value="${videoIndex + 1}" min="1" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label required">Video Title</label>
                        <input type="text" name="videos[${videoIndex}][title]" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Upload Video</label>
                        <input type="file" name="videos[${videoIndex}][file]" class="form-control"
                            accept="video/mp4,video/x-m4v,video/*">
                    </div>
                  
                    <div class="col-md-12 text-end mt-2">
                        <button type="button" class="btn btn-sm btn-danger removeVideo">Remove</button>
                    </div>
                </div>
            `;
            document.getElementById('video-wrapper').insertAdjacentHTML('beforeend', html);
            videoIndex++;
        });

        // Remove video row
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('removeVideo')) {
                e.target.closest('.video-row').remove();
            }
        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/admin/teacher-development/add.blade.php ENDPATH**/ ?>