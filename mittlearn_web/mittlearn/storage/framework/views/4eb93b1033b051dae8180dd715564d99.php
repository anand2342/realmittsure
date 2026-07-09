<div>
    <table class="table table-bordered">
          <tr>
            <th>S.No</th>
            <th>Feature</th>
            <th></th>
          </tr>

          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $this->featureRows; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php echo e(Form::hidden("feature_row[{$index}][id]",$row['id'])); ?>

            <?php echo e(Form::hidden("feature_row[{$index}][plan_id]",$row['id'])); ?>

          <tr>
            <td>
              <?php echo e($index+1); ?>

            </td>
            <td>
              <?php echo e(Form::hidden("feature_row[{$index}][id]",$row['id'])); ?>

              <?php echo e(Form::hidden("feature_row[{$index}][plan_id]",$row['id'])); ?>

              <?php echo e(Form::text("feature_row[{$index}][title]",$row['title'],['class'=>"form-control", "placeholder"=>'Enter Feature'])); ?>

            </td>
            
            <td>
              <button wire:click="removeRow(<?php echo e($index); ?>)" type="button" class="btn btn-danger btn-sm" title="Delete"><i class="fa fa-trash"></i></button>
            </td>
          </tr>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
          <tr>
            <td colspan="5" class="text-right">
              <button wire:click="addRow()" type="button" class="btn btn-success btn-sm" title="Delete Endorsement" <?php echo e($isDisableAddMoreBtn ? 'disabled' : ''); ?>>Add More</button>
            </td>
          </tr>
        </table>
</div>
<?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/livewire/subscription-plan-features-form.blade.php ENDPATH**/ ?>