<div class="text-center">
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isEditing): ?>
        <div class="row mb-2">
            <div class="col-md-8 mb-2">
                <label class="form-label">Language</label>
                <select wire:model="language" class="form-select form-select-sm">
                    <option value="">--Select Content Language--</option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = config('constants.CONTENT_LANGUAGE'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>"><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </select>
            </div>
            <div class="col-md-4 mb-2">
                <label class="form-label">File Order</label>
                <input type="number" wire:model="sortOrder" class="form-control form-control-sm">
            </div>
            <div class="col-md-12 mb-2">
                <label class="form-label">Video Type</label>
                <select wire:model="video_view_type" class="form-select form-select-sm">
                    <option value="">--Select Content Language--</option>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = config('constants.VIDEO_VIEW_TYPE'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($key); ?>"><?php echo e($label); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </select>
            </div>
            <div class="col-md-12 mb-2">
                <label class="form-label">File Name</label>
                <input type="text" wire:model="file_name" class="form-control form-control-sm">
            </div>

        </div>
        <div class="mt-2">
            <button type="button" wire:click="updateSortOrder" class="btn btn-sm btn-success me-2">Save</button>
            <button type="button" wire:click="$set('isEditing', false)"
                class="btn btn-sm btn-secondary">Cancel</button>
        </div>
    <?php else: ?>
        <div class="d-flex justify-content-center align-items-center gap-4 small text-muted mb-2">
            <div><strong>Language:</strong> <?php echo e(config('constants.CONTENT_LANGUAGE')[$language] ?? '—'); ?></div>
            <div><strong>File Order:</strong> <?php echo e($sortOrder); ?></div>
        </div>
        <div class="small text-muted mb-3"><strong>Video Type:</strong> <?php echo e($video_view_type); ?></div>
        <div>
            <button type="button" wire:click="$set('isEditing', true)" class="btn btn-sm btn-primary">
                <i class="fa fa-edit"></i>
            </button>
        </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</div>
<?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/livewire/chapter-file-sort-order-editor.blade.php ENDPATH**/ ?>