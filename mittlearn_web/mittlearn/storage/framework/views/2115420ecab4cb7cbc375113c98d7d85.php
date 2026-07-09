<div>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Sort Order</th>
                <th> Offering Name</th>
                <th> Image <small class="form-text text-muted">(Allowed formats: PNG, SVG. Image dimensions: 600x400
                        pixels)</small></th>
                <th>Description</th>
                <th>Redirection Link</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($rows && count($rows) > 0): ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $rows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr wire:key="row-<?php echo e($index); ?>">
                        <!-- Serial Number -->
                        <td>
                            <?php echo Form::text("rows[$index][our_offerings_sort_order]", $row['our_offerings_sort_order'] ?? null, [
                                'class' => 'form-control',
                                'wire:model.defer' => "rows[$index].our_offerings_sort_order",
                                'required' => 'required',
                                'placeholder' => 'Please Enter Sort Order',
                            ]); ?>

                        </td>

                        <!-- Academic Category Field -->
                        <td>
                            <?php echo Form::hidden("rows[$index][id]", $row['id'] ?? null, [
                                'wire:model.defer' => "rows[$index].id",
                            ]); ?>


                            <?php echo Form::text("rows[$index][our_offerings_title]", $row['our_offerings_title'] ?? null, [
                                'class' => 'form-control',
                                'wire:model.defer' => "rows[$index].our_offerings_title",
                                'required' => 'required',
                                'placeholder' => 'Please Enter Text',
                            ]); ?>

                        </td>

                        <!-- Academic Image Field -->
                        <td>
                            <?php echo Form::file("rows[$index][our_offerings_image]", [
                                'class' => 'form-control',
                                'wire:model.defer' => "rows[$index].our_offerings_image",
                            ]); ?>


                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($row['our_offerings_image']) && is_string($row['our_offerings_image'])): ?>
                                <div class="mt-2">
                                    <img src="<?php echo e(Storage::url('uploads/website-pages/our-offerings/' . $row['our_offerings_image'])); ?>"
                                        alt="Academic Image" width="100" height="50">
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </td>
                        <td>
                            <?php echo Form::textarea("rows[$index][ourOfferings_desc]", $row['ourOfferings_desc'] ?? null, [
                                'class' => 'form-control',
                                'wire:model.defer' => "rows[$index].ourOfferings_desc",
                                'placeholder' => 'Please Enter Description',
                            ]); ?>

                        </td>
                        <td>
                            <?php echo Form::text("rows[$index][redirection_link]", $row['redirection_link'] ?? null, [
                                'class' => 'form-control',
                                'wire:model.defer' => "rows[$index].redirection_link",
                                'placeholder' => 'Please Enter Url',
                            ]); ?>

                        </td>
                        <!-- Delete Button -->
                        <td>
                            <button wire:click.prevent="removeRow(<?php echo e($index); ?>)" type="button"
                                class="btn btn-danger btn-sm">
                                <i class="fa fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" class="text-center">No academic categories found. Please add a row.</td>
                </tr>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <!-- Add More Row -->
            <tr>
                <td colspan="6" class="text-end">
                    <button wire:click.prevent="addRow" type="button" class="btn btn-success btn-sm">Add More</button>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/livewire/our-offerings.blade.php ENDPATH**/ ?>