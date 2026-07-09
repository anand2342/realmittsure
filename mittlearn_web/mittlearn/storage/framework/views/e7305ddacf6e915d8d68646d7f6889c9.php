<div>
    <h6>Our Program Details</h6>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>S.No</th>
                <th> Title</th>
                <th> Image <small class="form-text text-muted">(Allowed formats: PNG, PDF, SVG. Image dimensions: 50x50
                        pixels)</small></th>
                <th> Description</th>
                <th>Know More URL</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $row; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr wire:key="row-<?php echo e($index); ?>">
                    <!-- Serial Number -->
                    <td><?php echo e($index + 1); ?></td>

                    <!-- Icon Title Field -->
                    <td>
                        <?php echo Form::hidden("row[$index][id]", $row['id'] ?? null, [
                            'wire:model.defer' => "row[$index].id",
                        ]); ?>

                        <div class="col-md-12 col-sm-6 col-xs-12">
                            <?php echo Form::hidden('type', 'our_program', ['class' => 'form-control']); ?>

                        </div>
                        <?php echo Form::text("row[$index][title]", $row['title'], [
                            'class' => 'form-control',
                            'required',
                            'wire:model.defer' => "row[$index].title",
                            'placeholder' => 'Enter  Title',
                        ]); ?>

                    </td>

                    <!-- Icon Image Field -->
                    <td>
                        <?php echo Form::file("row[$index][image]", [
                            'class' => 'form-control',
                            'wire:model.defer' => "row[$index].image",
                        ]); ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($row['image']) && $row['image']): ?>
                            <div class="mt-2">
                                <img src="<?php echo e(Storage::url('uploads/cms-about-us/our-program/' . $row['image'])); ?>"
                                    alt="Icon Image" width="100">
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </td>

                    <!-- Icon Description Field -->
                    <td>
                        <?php echo Form::textarea("row[$index][description]", $row['description'], [
                            'class' => 'form-control',
                            'rows' => 2,
                            'wire:model.defer' => "row[$index].description",
                            'placeholder' => 'Enter  Description',
                            'required',
                        ]); ?>

                    </td>
                    <td>
                        <?php echo Form::textarea("row[$index][url_redirection]", $row['url_redirection'], [
                            'class' => 'form-control',
                            'rows' => 2,
                            'wire:model.defer' => "row[$index].url_redirection",
                            'placeholder' => 'Enter URL',
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

            <!-- Add More Button -->
            <tr>
                <td colspan="5" class="text-right">
                    <button wire:click.prevent="addRow" type="button" class="btn btn-success btn-sm">Add More</button>
                </td>
            </tr>
        </tbody>
    </table>

</div>
<?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/livewire/our-program.blade.php ENDPATH**/ ?>