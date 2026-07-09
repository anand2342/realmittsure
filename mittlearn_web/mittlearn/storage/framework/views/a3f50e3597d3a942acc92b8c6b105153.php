<tr>
    <td>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($child_index !== ''): ?>
            <?php echo e($parent_index . '.' . $child_index); ?>

        <?php else: ?>
            <?php echo e($parent_index . '.'); ?>

        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </td>
    <td><?php echo e($data->name); ?></td>
    <td>
        <div class="d-flex align-items-center">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('isPermission', 'blog.category.edit')): ?>
                <a class="btn btn-sm btn-warning me-2" href="<?php echo e(route('blog.category.edit', $data->id)); ?>" title="Edit">
                    <i class="bi bi-pencil"></i>
                </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(is_null($data->parent_id)): ?>
                <button class="btn btn-sm btn-primary me-2" type="button" data-bs-toggle="collapse"
                    data-bs-target="#subcategories-<?php echo e($data->id); ?>" title="Sub-Category">
                    <i class="fa fa-code-fork"></i>
                    <span class="badge bg-white text-primary">
                        <?php echo e($data->children()->count()); ?>

                    </span>
                </button>
                <a class="btn btn-sm btn-info me-2" href="<?php echo e(route('blog.sub_category.create', $data->id)); ?>">
                    <i class="bi bi-plus-lg"></i>
                </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('isPermission', 'blog.category.delete')): ?>
                <button class="btn btn-sm btn-danger"
                    onclick="confirmDelete('<?php echo e(route('blog.category.delete', $data->id)); ?>')">
                    <i class="fa fa-trash"></i>
                </button>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

        </div>
    </td>
</tr>

<tr>
    <td colspan="3">
        <div class="collapse" id="subcategories-<?php echo e($data->id); ?>">
            <table class="table table-bordered">
                <tbody>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($data->children->isEmpty()): ?>
                        <tr>
                            <td colspan="3" class="text-center">No subcategories available</td>
                        </tr>
                    <?php else: ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $data->children; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subcategory): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php echo $__env->make('admin.blog.blog_category_row', [
                                'child_index' => '',
                                'parent_index' => $parent_index . '.' . $loop->iteration,
                                'data' => $subcategory,
                            ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
    </td>
</tr>
<?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/admin/blog/blog_category_row.blade.php ENDPATH**/ ?>