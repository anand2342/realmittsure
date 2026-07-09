<td>
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($isEditing): ?>
        <input type="number" wire:model="sortOrder" wire:blur="updateSortOrder" class="border px-2 py-1 w-16">
    <?php else: ?>
        <span><?php echo e($sortOrder); ?></span>
        <button wire:click="$set('isEditing', true)" class="btn btn btn-sm me-4"><i class="fa fa-edit"></i></button>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
</td>
<?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/livewire/courses-chapter-sort-order.blade.php ENDPATH**/ ?>