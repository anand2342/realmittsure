<div>
    <!-- Upload Form -->
    <div class="card-body">
        <div class="row">
            <div class="col-md-2 col-sm-12 mt-2">
                <!-- Right side: Download Sample File -->
                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" id="downloadDropdown"
                    data-bs-toggle="dropdown" aria-expanded="false">
                    Download Sample File
                </button>
                <ul class="dropdown-menu" aria-labelledby="downloadDropdown">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $roleKey => $roleValue): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li>
                            <a class="dropdown-item" href="#" onclick="downloadSampleFile('<?php echo e($roleKey); ?>')">
                                <?php echo e($roleValue); ?>

                            </a>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </ul>
            </div>
            <div class="col-md-4 col-sm-12 mt-2">
            </div>
            <div class="col-md-4 col-sm-12 mt-2">
                <!-- Left side: Bulk Upload Form -->
                <form wire:submit.prevent="uploadUsers">
                    <input type="file" wire:model="file" class="form-control">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="error"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <div class="text-end mt-3">
                        <div wire:loading wire:target="uploadUsers" class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
            </div>
            <div class="col-md-2 col-sm-12 mt-2">
                <button type="submit" class="btn btn-primary">Upload File</button>
            </div>
            </form>
            <div class="text-center">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('successMsg')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-1"></i>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(is_array(session('successMsg'))): ?>
                            <?php echo e(implode(', ', session('successMsg'))); ?>

                        <?php else: ?>
                            <?php echo e(session('successMsg')); ?>

                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
    <!-- Loader Spinner and Alerts -->

    <!-- Modal to show uploaded data -->
    <div x-data="{ open: <?php if ((object) ('isModalOpen') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('isModalOpen'->value()); ?>')<?php echo e('isModalOpen'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('isModalOpen'); ?>')<?php endif; ?> }" x-show="open" x-transition @click.away="open = false"
        @keydown.escape.window="open = false" class="modal fade" tabindex="-1" :class="{ 'show d-block': open }"
        style="background: rgba(0, 0, 0, 0.5);">
        <div class="modal-dialog modal-xl">
            <?php if(session()->has('errorMsg')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-octagon me-1"></i>
                    <strong>Error:</strong>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(is_array(session('errorMsg'))): ?>
                        <?php echo e(implode(', ', session('errorMsg'))); ?>

                    <?php else: ?>
                        <?php echo e(session('errorMsg')); ?>

                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Uploaded Data</h5>
                    <button type="button" class="btn-close" x-on:click="open = false; window.Livewire.find('<?php echo e($_instance->getId()); ?>').closeModal()"></button>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($uploadedData)): ?>
                    <form wire:submit.prevent="processSelectedData">
                        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($rowErrors)): ?>
                                <div class="alert alert-danger mb-3">
                                    <strong>Errors Found:</strong> Please correct the highlighted errors below before
                                    proceeding.
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">
                                            <input type="checkbox" id="select-all-checkbox" x-data="{ isChecked: false }"
                                                x-model="isChecked"
                                                @click="$dispatch('select-all', { isChecked: !isChecked })">
                                            <label for="select-all-checkbox" style="margin-left: 5px;">Select
                                                All</label>
                                        </th>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $headers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $header): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <th><?php echo e($header); ?></th>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <th style="width: 100px;">Status</th>
                                    </tr>
                                </thead>
                                <tbody x-data
                                    @select-all.window="event => {
                                        const checkboxes = [...$el.querySelectorAll('input[type=checkbox]')];
                                        checkboxes.forEach(c => {
                                            c.checked = event.detail.isChecked;
                                            if (event.detail.isChecked) {
                                                window.Livewire.find('<?php echo e($_instance->getId()); ?>').set('selectedData', [...window.Livewire.find('<?php echo e($_instance->getId()); ?>').get('selectedData'), c.value]);
                                            } else {
                                                window.Livewire.find('<?php echo e($_instance->getId()); ?>').set('selectedData', []);
                                            }
                                        });
                                    }">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $uploadedData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $rowKey => $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $hasErrors = isset($rowErrors[$rowKey]);
                                            $rowClass = $hasErrors ? 'table-danger' : '';
                                            $convertedHeaders = array_map(function ($h) {
                                                return $this->convertToSnakeCase($h);
                                            }, $headers);
                                        ?>

                                        <tr class="<?php echo e($rowClass); ?>">
                                            <td>
                                                <input type="checkbox" wire:model="selectedData"
                                                    value="<?php echo e($rowKey); ?>">
                                            </td>

                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $headers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $header): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $convertedHeader = $convertedHeaders[$index];
                                                    $fieldErrors =
                                                        $hasErrors && isset($rowErrors[$rowKey][$convertedHeader])
                                                            ? $rowErrors[$rowKey][$convertedHeader]
                                                            : null;
                                                ?>

                                                <td <?php if($fieldErrors): ?> class="has-error" <?php endif; ?>>
                                                    <?php echo e($row[$header]); ?>


                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($fieldErrors): ?>
                                                        <div class="error-tooltip">
                                                            <i class="bi bi-exclamation-circle text-danger"></i>
                                                            <div class="error-tooltip-text">
                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(is_array($fieldErrors)): ?>
                                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $fieldErrors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <?php echo e($error); ?><br>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                                <?php else: ?>
                                                                    <?php echo e($fieldErrors); ?>

                                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </td>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                            <td class="text-center">
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($hasErrors): ?>
                                                    <span class="badge bg-danger">Error</span>
                                                <?php elseif(in_array($rowKey, $selectedData)): ?>
                                                    <span class="badge bg-success">Ready</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Pending</span>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer d-flex justify-content-between">
                            <div>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($rowErrors)): ?>
                                    <span class="text-danger me-3">
                                        <i class="bi bi-exclamation-triangle"></i>
                                        <?php echo e(count($rowErrors)); ?> row(s) have errors
                                    </span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div>
                                <button type="button" class="btn btn-secondary me-2"
                                    x-on:click="open = false; window.Livewire.find('<?php echo e($_instance->getId()); ?>').closeModal()">Close</button>
                                <button type="submit" class="btn btn-primary" <i class="fas fa-upload me-1"></i>
                                    Process Selected Data
                                </button>
                            </div>
                        </div>
                    </form>
                <?php else: ?>
                    <div class="modal-body">
                        <p class="text-muted">No data available to display.</p>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>

    <style>
        .has-error {
            position: relative;
            background-color: #fff5f5;
        }

        .error-tooltip {
            position: absolute;
            top: 2px;
            right: 2px;
            cursor: help;
        }

        .error-tooltip-text {
            visibility: hidden;
            width: 250px;
            background-color: #c73b49;
            color: white;
            text-align: center;
            border-radius: 4px;
            padding: 5px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .error-tooltip:hover .error-tooltip-text {
            visibility: visible;
            opacity: 1;
        }

        .table-danger td {
            background-color: rgba(220, 53, 69, 0.1);
        }
    </style>
    

</div>
<?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/livewire/user-bulk-upload.blade.php ENDPATH**/ ?>