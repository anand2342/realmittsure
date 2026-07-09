<div>
    <ul class="nav nav-tabs tbs " id="classTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link tab-btn <?php echo e($tab === 'embibe' ? 'active' : ''); ?>"
                wire:click="$set('tab', 'embibe')">Embibe</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link tab-btn <?php echo e($tab === 'olympiad' ? 'active' : ''); ?>"
                wire:click="$set('tab', 'olympiad')">Olympiad</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link tab-btn <?php echo e($tab === 'digitalContent' ? 'active' : ''); ?>" data-toggle="modal"
                data-target="#serviceUnAvailable">Mittsure Digital Content</a>
        </li>

        <li class="nav-item" role="presentation">
            <a class="nav-link tab-btn <?php echo e($tab === 'lumalearn' ? 'active' : ''); ?>" data-toggle="modal"
                data-target="#serviceUnAvailable">Luma
                Learn</a>
        </li>

        

    </ul>

    <div class="tab-content mt-3" id="classTabContent">
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tab == 'embibe'): ?>
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
            <div class="row mt-4">
                <!-- Download Button -->
                <div class="col-md-3 col-sm-12 mt-4">
                    <h5 class="card-title">Teachlite (For Teachers)</h5>

                </div>
                <div class="col-md-3 col-sm-12 mt-4">
                    <a href="<?php echo e(asset('admin/sample-files/access-code-teachlite-sample-file.xlsx')); ?>"
                        class="btn btn-primary">Download sample file</a>
                </div>

                <!-- File Upload Form -->
                <div class="col-md-6 col-sm-12 mt-4 gap-2">
                    <form wire:submit.prevent="uploadEmbibeAccessCodeTeachlite" class="d-flex align-items-center">
                        <div class="row">
                            <input type="file" wire:model="file" class="form-control">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-danger" style="font-size: 13px;"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        </div>
                        <div wire:loading wire:target="uploadEmbibeAccessCodeTeachlite" style="margin-left: 30px "
                            class="spinner-border text-primary " role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <button type="submit" class="btn btn-primary" style="margin-left: 55px ">Upload
                            File</button>

                    </form>
                </div>
            </div>
            <hr class="form-divider">
            <div class="row mt-4">
                <!-- Download Button -->
                <div class="col-md-3 col-sm-12 mt-4">
                    <h5 class="card-title">Mittsure Lens (For Students)</h5>

                </div>
                <div class="col-md-3 col-sm-12 mt-4">
                    <a href="<?php echo e(asset('admin/sample-files/access-code-mittlense-sample-file.csv')); ?>"
                        class="btn btn-primary">Download sample file</a>
                </div>

                <!-- File Upload Form -->
                <div class="col-md-6 col-sm-12 mt-4 gap-2">
                    <form wire:submit.prevent="uploadEmbibeAccessCodeMittlense" class="d-flex align-items-center">
                        <div class="row">
                            <input type="file" wire:model="file" class="form-control">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['file'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <span class="text-danger" style="font-size: 13px;"><?php echo e($message); ?></span>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        </div>
                        <div wire:loading wire:target="uploadEmbibeAccessCodeMittlense" style="margin-left: 30px "
                            class="spinner-border text-primary " role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <button type="submit" class="btn btn-primary" style="margin-left: 55px ">Upload
                            File</button>

                    </form>
                </div>
            </div>
        <?php elseif($tab == 'olympiad'): ?>
            <div class="row">
                <div class="col-md-6 col-sm-4 col-xs-12 mb-3">
                    
                    <label class="form-label required">Select Book Series</label>
                    <?php echo Form::select('book_series_id', $olmpiadBookSeries, null, [
                        'class' => 'form-select',
                        'wire:model' => 'book_series_id',
                        'required',
                    ]); ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['book_series_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-danger" style="font-size: 13px;"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="col-md-6 col-sm-4 col-xs-12 mb-3">
                    <label class="form-label required">Select Class</label>
                    <?php echo Form::select('class_id', $olympiadClasses, null, [
                        'class' => 'form-select',
                        'placeholder' => 'Select a Class',
                        'wire:model' => 'class_id',
                        // 'wire:change' => 'loadBookSet($event.target.value)',
                        'required',
                    ]); ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['class_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-danger" style="font-size: 13px;"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="col-md-6 col-sm-4 col-xs-12 mb-3">
                    <div class="form-group bginput">
                        <?php echo Form::label('subject_id', 'Select Subject', ['class' => 'form-label required']); ?>

                        <?php echo Form::select('subject_id', $olympiadSubjects, null, [
                            'class' => 'form-select',
                            'placeholder' => 'Select a Subject',
                            'wire:model' => 'subject_id',
                        ]); ?>

                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['subject_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-danger" style="font-size: 13px;"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="col-md-6 col-sm-4 col-xs-12 mb-3">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$showNewPrefixInput): ?>
                        <label class="form-label required">Select Prefix</label>
                        <select class="form-select" wire:model="prefix"
                            wire:change="handlePrefixChange($event.target.value)" required>
                            <option value="">Select a prefix</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $prefixes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($id); ?>"><?php echo e($name); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <option value="add_new">Add New</option>
                        </select>
                    <?php else: ?>
                        <label class="form-label required">Select Prefix</label>
                        <input type="text" class="form-control" wire:model.lazy="prefix"
                            oninput="this.value = this.value.toUpperCase()" wire:change="saveNewPrefix"
                            placeholder="Enter new prefix">
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['prefix'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-danger" style="font-size: 13px;"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="col-md-6 col-sm-4 col-xs-12 mb-3">
                    <label class="form-label required">Code Length</label>
                    <?php echo e(Form::text('code_length', '', ['class' => 'form-control', 'placeholder' => 'Enter length', 'wire:model' => 'code_length'])); ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['code_length'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-danger" style="font-size: 13px;"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="col-md-6 col-sm-4 col-xs-12 mb-3">
                    <label class="form-label required">Number of codes to be generated</label>
                    <?php echo Form::text('numbers_of_code', '', [
                        'class' => 'form-control',
                        'placeholder' => 'No. of codes to be generated',
                        'wire:model' => 'numbers_of_code',
                        'max' => '1000',
                        'required',
                    ]); ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['numbers_of_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-danger" style="font-size: 13px;"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="col-md-6 col-sm-4 col-xs-12 mb-3">
                    <label class="form-label required">Video Content Access Validity</label>
                    <?php echo e(Form::date('end_date', old('end_date', '2026-03-31'), [
                        'class' => 'form-control',
                        'required',
                        'wire:model' => 'end_date',
                        'placeholder' => 'Enter your name here',
                    ])); ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-danger" style="font-size: 13px;"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="col-md-6 col-sm-4 col-xs-12 mb-3">
                    <label class="form-label required">User Name (Code Generator)</label>
                    <?php echo e(Form::text('code_generator', null, ['class' => 'form-control', 'required', 'wire:model' => 'code_generator', 'placeholder' => 'Enter your name here'])); ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['code_generator'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-danger" style="font-size: 13px;"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="text-right mt-5">
                    
                    <button class="btn btn-primary" wire:click="generatePreviewOlympiadCodes"
                        wire:loading.attr="disabled" wire:target="generatePreviewOlympiadCodes">
                        <span wire:loading.remove wire:target="generatePreviewOlympiadCodes">Generate and Preview
                            Code</span>
                        <span wire:loading wire:target="generatePreviewOlympiadCodes">
                            <div class="spinner-border spinner-border-sm text-light me-2" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            Generating...
                        </span>
                    </button>

                    <button type="button" class="btn btn-secondary" wire:click="resetForm">Reset</button>
                </div>
            </div>
        <?php else: ?>
            <div class="row">
                <p>Code Generation Type</p>
                <div class="col-md-6 col-sm-4 col-xs-12 mb-2">
                    <input type="radio" id="random" value="random" name="generationType"
                        <?php echo e($generationType === 'random' ? 'checked' : ''); ?>

                        wire:change="$set('generationType', 'random')" class="form-check-input">
                    <label for="random" class="form-check-label">Random Code</label>
                </div>
                <div class="col-md-6 col-sm-4 col-xs-12 mb-2">
                    <input type="radio" id="custom" value="custom" name="generationType"
                        <?php echo e($generationType === 'custom' ? 'checked' : ''); ?>

                        wire:change="$set('generationType', 'custom')" class="form-check-input">
                    <label for="custom" class="form-check-label">Custom Code</label>
                </div>
                <hr class="form-divider">

                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($generationType === 'custom'): ?>
                    <div class="col-md-6 col-sm-4 col-xs-12 mb-3">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!$showNewPrefixInput): ?>
                            <label class="form-label required">Select Prefix</label>
                            <select class="form-select" wire:model="prefix"
                                wire:change="handlePrefixChange($event.target.value)" required>
                                <option value="">Select a prefix</option>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $prefixes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($id); ?>"><?php echo e($name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <option value="add_new">Add New</option>
                            </select>
                        <?php else: ?>
                            <label class="form-label required">Select Prefix</label>
                            <input type="text" class="form-control" wire:model.lazy="prefix"
                                oninput="this.value = this.value.toUpperCase()" wire:change="saveNewPrefix"
                                placeholder="Enter new prefix">
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['prefix'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-danger" style="font-size: 13px;"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="col-md-6 col-sm-4 col-xs-12 mb-3">
                        <label class="form-label required">Code Length</label>
                        <?php echo e(Form::text('code_length', '', ['class' => 'form-control', 'placeholder' => 'Enter length', 'wire:model' => 'code_length'])); ?>

                    </div>
                    <div class="col-md-6 col-sm-4 col-xs-12 mb-3">
                        <label class="form-label required">Select Book Series</label>
                        <?php echo Form::select('book_series_id', $bookSeries, null, [
                            'class' => 'form-select',
                            'placeholder' => 'Select a Book Series',
                            'wire:model' => 'book_series_id',
                            'required',
                        ]); ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['book_series_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-danger" style="font-size: 13px;"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="col-md-6 col-sm-4 col-xs-12 mb-3">
                        <label class="form-label required">Select Class</label>
                        <?php echo Form::select('class_id', $schoolClasses, null, [
                            'class' => 'form-select',
                            'placeholder' => 'Select a Class',
                            'wire:model' => 'class_id',
                            'wire:change' => 'loadBookSet($event.target.value)',
                            'required',
                        ]); ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['class_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-danger" style="font-size: 13px;"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tab === 'digitalContent'): ?>
                    <div class="col-md-6 col-sm-4 col-xs-12 mb-3">
                        <label class="form-label required">Select School</label>
                        <?php echo Form::select('school_id', $schools, null, [
                            'class' => 'form-select',
                            'placeholder' => 'Select a School',
                            'wire:model' => 'school_id',
                            'required',
                        ]); ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['school_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-danger" style="font-size: 13px;"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <div class="col-md-6 col-sm-4 col-xs-12 mb-3">
                    <label class="form-label">Select Board</label>
                    <?php echo Form::select('board_id', $boards, null, [
                        'class' => 'form-select',
                        'placeholder' => 'Select a Board',
                        'wire:model' => 'board_id',
                        'wire:change' => 'loadBookSet($event.target.value)',
                    ]); ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['board_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-danger" style="font-size: 13px;"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="col-md-6 col-sm-4 col-xs-12 mb-3">
                    <label class="form-label">Select Medium</label>
                    <?php echo Form::select('medium_id', $mediums, null, [
                        'class' => 'form-select',
                        'placeholder' => 'Select a Medium',
                        'wire:model' => 'medium_id',
                        'wire:change' => 'loadBookSet($event.target.value)',
                    ]); ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['medium_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-danger" style="font-size: 13px;"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($tab === 'digitalContent'): ?>
                    <div class="col-md-6 col-sm-4 col-xs-12 mb-3">
                        <label class="form-label required">Select Book Series</label>
                        <?php echo Form::select('book_series_id', $bookSeries, null, [
                            'class' => 'form-select',
                            'placeholder' => 'Select a Book Series',
                            'wire:model' => 'book_series_id',
                            'required',
                        ]); ?>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['book_series_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <span class="text-danger" style="font-size: 13px;"><?php echo e($message); ?></span>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <div class="col-md-6 col-sm-4 col-xs-12 mb-3">
                    <label class="form-label required">Select Class</label>
                    <?php echo Form::select('class_id', $schoolClasses, null, [
                        'class' => 'form-select',
                        'placeholder' => 'Select a Class',
                        'wire:model' => 'class_id',
                        'wire:change' => 'loadBookSet($event.target.value)',
                        'required',
                    ]); ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['class_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-danger" style="font-size: 13px;"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="col-md-6 col-sm-4 col-xs-12 mb-3">
                    <label class="form-label">Choose Option</label>
                    <div>
                        <input type="radio" id="book_set_option" value="book_set" name="option"
                            <?php echo e($selectedOption === 'book_set' ? 'checked' : ''); ?>

                            wire:click="$set('selectedOption', 'book_set')" class="form-check-input">
                        <label for="book_set_option" class="form-check-label">Book Set</label>

                        <input type="radio" id="subject_option" value="subject" name="option"
                            <?php echo e($selectedOption === 'subject' ? 'checked' : ''); ?>

                            wire:click="$set('selectedOption', 'subject')" class="form-check-input">
                        <label for="subject_option" class="form-check-label">Subject</label>
                    </div>
                </div>

                <div class="col-md-6 col-sm-4 col-xs-12 mb-3"
                    style="display: <?php echo e($selectedOption === 'book_set' ? 'block' : 'none'); ?>;">
                    <label class="form-label required">Select Book Set</label>
                    <?php echo Form::select('book_set_id', $bookSets, null, [
                        'class' => 'form-select',
                        'placeholder' => 'Select a Book Set',
                        'wire:model' => 'book_set_id',
                        'required',
                    ]); ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['book_set_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-danger" style="font-size: 13px;"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="col-md-6 col-sm-4 col-xs-12 mb-3"
                    style="display: <?php echo e($selectedOption === 'subject' ? 'block' : 'none'); ?>;">
                    <div class="form-group bginput" wire:ignore>
                        <?php echo Form::label('subject_ids', 'Select Subject', ['class' => 'form-label required']); ?>

                        <?php echo Form::select('subject_ids[]', $subjects, null, [
                            'class' => 'js-select2 form-select',
                            'multiple' => 'multiple',
                            'wire:model' => 'subject_ids',
                        ]); ?>

                    </div>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['subject_ids'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-danger" style="font-size: 13px;"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <?php echo Form::hidden('selectedSubject[]', null, [
                    'class' => 'form-select',
                    'wire:model' => 'selectedSubject',
                ]); ?>

                <div class="col-md-6 col-sm-4 col-xs-12 mb-3">
                    <label class="form-label required">Number of codes to be generated</label>
                    <?php echo Form::text('numbers_of_code', '', [
                        'class' => 'form-control',
                        'placeholder' => 'No. of Student strength (Book sold)',
                        'wire:model' => 'numbers_of_code',
                        'max' => '1000',
                        'required',
                    ]); ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['numbers_of_code'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-danger" style="font-size: 13px;"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="col-md-6 col-sm-4 col-xs-12 mb-3">
                    <label class="form-label required">Start Date</label>
                    <?php echo e(Form::date('start_date', null, ['class' => 'form-control', 'required', 'wire:model' => 'start_date', 'min' => \Carbon\Carbon::today()->toDateString()])); ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-danger" style="font-size: 13px;"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>

                <div class="col-md-6 col-sm-4 col-xs-12 mb-3">
                    <label class="form-label required">Expiration Date</label>
                    <?php echo e(Form::date('end_date', null, ['class' => 'form-control', 'required', 'wire:model' => 'end_date'])); ?>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <span class="text-danger" style="font-size: 13px;"><?php echo e($message); ?></span>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="text-right mt-5">
                    
                    <button class="btn btn-primary" wire:click="generatePreviewCodes" wire:loading.attr="disabled"
                        wire:target="generatePreviewCodes">
                        <span wire:loading.remove wire:target="generatePreviewCodes">Generate and Preview
                            Code</span>
                        <span wire:loading wire:target="generatePreviewCodes">
                            <div class="spinner-border spinner-border-sm text-light me-2" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            Generating...
                        </span>
                    </button>

                    <button type="button" class="btn btn-secondary" wire:click="resetForm">Reset</button>
                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <!-- Embibe accessCodePreviewModal Structure -->
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
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="select-all-checkbox" x-data="{ isChecked: false }"
                                                x-model="isChecked"
                                                @click="$dispatch('select-all', { isChecked: !isChecked })">
                                            <label for="select-all-checkbox" style="margin-left: 5px;">Select
                                                All</label>
                                        </th>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $headers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $header): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <th><?php echo e($header); ?></th>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
                                        <tr>
                                            <td>
                                                <input type="checkbox" wire:model="selectedData"
                                                    value="<?php echo e($rowKey); ?>">
                                            </td>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $headers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $header): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <td>
                                                    <?php
                                                        $convertedHeader = $this->convertToSnakeCase($header); // Ensure this matches the conversion in $convertedRow
                                                    ?> <?php echo e($row[$header]); ?>

                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($rowErrors[$rowKey]) && isset($rowErrors[$rowKey][$convertedHeader])): ?>
                                                        <div class="errormsg p-1" style="background-color: #ff2137">
                                                            <span class="text-white small d-block p-0">
                                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $rowErrors[$rowKey][$convertedHeader]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <?php echo e($error); ?><br>
                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                            </span>
                                                        </div>
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </td>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Process Selected
                                Data</button>
                            <button type="button" class="btn btn-secondary"
                                x-on:click="open = false; window.Livewire.find('<?php echo e($_instance->getId()); ?>').closeModal()">Close</button>
                        </div>
                    </form>
                <?php else: ?>
                    <p>No data available to display.</p>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        </div>
    </div>
    <!-- accessCodePreviewModal  Modal Structure -->
    <div x-data="{ open: <?php if ((object) ('isPreviewModalOpen') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('isPreviewModalOpen'->value()); ?>')<?php echo e('isPreviewModalOpen'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('isPreviewModalOpen'); ?>')<?php endif; ?> }" x-show="open" x-transition @click.away="open = false"
        @keydown.escape.window="open = false" class="modal fade" tabindex="-1"
        aria-labelledby="accessCodePreviewModalLabel" aria-hidden="true" x-init="() => {
            $watch('open', value => {
                if (value) {
                    document.body.classList.add('modal-open');
                    let backdrop = document.createElement('div');
                    backdrop.className = 'modal-backdrop fade show';
                    document.body.appendChild(backdrop);
                } else {
                    document.body.classList.remove('modal-open');
                    document.querySelector('.modal-backdrop')?.remove();
                }
            })
        }"
        :class="{ 'show d-block': open }">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title w-100 fw-bold" id="accessCodePreviewModalLabel">
                        Preview Access Codes
                    </h5>
                    <button type="button" class="btn-close" x-on:click="open = false; window.Livewire.find('<?php echo e($_instance->getId()); ?>').closeModal()"></button>

                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-between">
                        <p class="mb-0 fs-7"><b class="fw-semibold">Type:</b>
                            <?php echo e($accessCodes[0]['series_name'] ?? 'N/A'); ?></p>
                        <p class="mb-0 fs-7"><b class="fw-semibold">School Name:</b>
                            <?php echo e($accessCodes[0]['school_name'] ?? 'N/A'); ?></p>
                    </div>
                    <hr class="form-divider">
                    <div class="d-flex justify-content-between">
                        <p class="mb-0 fs-7"><b class="fw-semibold">Class:</b>
                            <?php echo e($accessCodes[0]['class_name'] ?? 'N/A'); ?></p>
                        <p class="mb-0 fs-7"><b class="fw-semibold">Start Date:</b>
                            <?php echo e($accessCodes[0]['generation_date'] ?? 'N/A'); ?></p>
                    </div>
                    <hr class="form-divider">
                    <div class="d-flex justify-content-between">
                        <p class="mb-0  fs-7"><b class="fw-semibold">Expiration Date:</b>
                            <?php echo e($accessCodes[0]['expiration_date'] ?? 'N/A'); ?></p>
                    </div>
                    <hr class="form-divider">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($accessCodes)): ?>
                        <div class="table-responsive tbleDiv">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Code</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $accessCodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($loop->iteration); ?></td>
                                            <td><?php echo e($code['code']); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p>No access codes available for preview.</p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="open = false">Close</button>
                    <button type="button" class="btn btn-success" wire:click="saveCodes"
                        wire:loading.attr="disabled" wire:target="saveCodes">
                        <span wire:loading.remove wire:target="saveCodes">Save Codes</span>
                        <span wire:loading wire:target="saveCodes">
                            <div class="spinner-border spinner-border-sm text-light me-2" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            Saving...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- accessCode olmpiad PreviewModal  Modal Structure -->
    <div x-data="{ open: <?php if ((object) ('isPreviewModalOpenOlympiad') instanceof \Livewire\WireDirective) : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('isPreviewModalOpenOlympiad'->value()); ?>')<?php echo e('isPreviewModalOpenOlympiad'->hasModifier('live') ? '.live' : ''); ?><?php else : ?>window.Livewire.find('<?php echo e($__livewire->getId()); ?>').entangle('<?php echo e('isPreviewModalOpenOlympiad'); ?>')<?php endif; ?> }" x-show="open" x-transition @click.away="open = false"
        @keydown.escape.window="open = false" class="modal fade" tabindex="-1"
        aria-labelledby="accessCodePreviewModalLabel" aria-hidden="true" x-init="() => {
            $watch('open', value => {
                if (value) {
                    document.body.classList.add('modal-open');
                    let backdrop = document.createElement('div');
                    backdrop.className = 'modal-backdrop fade show';
                    document.body.appendChild(backdrop);
                } else {
                    document.body.classList.remove('modal-open');
                    document.querySelector('.modal-backdrop')?.remove();
                }
            })
        }"
        :class="{ 'show d-block': open }">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title w-100 fw-bold" id="accessCodePreviewModalLabel">
                        Preview Access Codes
                    </h5>
                    <button type="button" class="btn-close" x-on:click="open = false; window.Livewire.find('<?php echo e($_instance->getId()); ?>').closeModal()"></button>

                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-between">
                        <p class="mb-0 fs-7"><b class="fw-semibold">Type:</b>
                            <?php echo e($accessCodes[0]['series_name'] ?? 'N/A'); ?></p>
                        <p class="mb-0 fs-7"><b class="fw-semibold">Class:</b>
                            <?php echo e($accessCodes[0]['class_name'] ?? 'N/A'); ?></p>
                    </div>
                    <hr class="form-divider">
                    <div class="d-flex justify-content-between">
                        <p class="mb-0 fs-7"><b class="fw-semibold">Subject:</b>
                            <?php echo e($accessCodes[0]['subject_name'] ?? 'N/A'); ?></p>
                        <p class="mb-0  fs-7"><b class="fw-semibold">Expiration Date:</b>
                            <?php echo e($accessCodes[0]['expiration_date'] ?? 'N/A'); ?></p>
                    </div>

                    <hr class="form-divider">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($accessCodes)): ?>
                        <div class="table-responsive tbleDiv">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Code</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $accessCodes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td><?php echo e($loop->iteration); ?></td>
                                            <td><?php echo e($code['code']); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p>No access codes available for preview.</p>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="open = false">Close</button>
                    <button type="button" class="btn btn-success" wire:click="saveOlympiadCodes"
                        wire:loading.attr="disabled" wire:target="saveOlympiadCodes">
                        <span wire:loading.remove wire:target="saveOlympiadCodes">Save Codes</span>
                        <span wire:loading wire:target="saveOlympiadCodes">
                            <div class="spinner-border spinner-border-sm text-light me-2" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            Saving...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="serviceUnAvailable" tabindex="-1" role="dialog"
        aria-labelledby="serviceUnAvailableLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="serviceUnAvailableLabel">🚫 Process Unavailable! 🚫</h5>
                    
                </div>
                <div class="modal-body">
                    This feature isn't activated at the moment. 🌟 Feel free to explore other functionalities and enjoy
                    an exceptional experience! 🎉 </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


</div>


<?php $__env->startPush('scripts'); ?>
    <script>
        document.addEventListener('livewire:load', function() {
            Livewire.hook('message.processed', (message, component) => {
                $('.js-select2').select2();
            });
        });

        document.addEventListener('livewire:init', function() {
            initializeSelect2();
        });

        document.addEventListener('livewire:update', function() {
            initializeSelect2();
        });

        function initializeSelect2() {
            $(".js-select2").select2({
                closeOnSelect: false,
                placeholder: "--Select--",
                allowClear: false,
                tags: true
            });

            // Ensure Livewire can capture the selected value
            $(".js-select2").on('change', function(e) {
                let selectedValues = $(this).val();
                window.Livewire.find('<?php echo e($_instance->getId()); ?>').set('selectedSubject', selectedValues); // Sync with Livewire
            });
        }
        // // Sync Select2 changes with Livewire

        document.addEventListener('openModal', () => {
            const modalElement = new bootstrap.Modal(document.getElementById('accessCodePreviewModal'));
            modalElement.show();
        });

        document.addEventListener('codeSaved', function() {
            // Use Bootstrap's modal method to hide the modal
            const modalElement = document.getElementById('accessCodePreviewModal');
            const modalInstance = bootstrap.Modal.getInstance(modalElement);
            if (modalInstance) {
                modalInstance.hide();
            }
        });
        document.addEventListener('codeSavedOlympiad', function() {
            // Use Bootstrap's modal method to hide the modal
            const modalElement = document.getElementById('accessCodePreviewModal');
            const modalInstance = bootstrap.Modal.getInstance(modalElement);
            if (modalInstance) {
                modalInstance.hide();
            }
        });
    </script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/livewire/access-code-form.blade.php ENDPATH**/ ?>