<?php $__env->startSection('content'); ?>
    <?php
        $flag = 0;
        $heading = 'Add';
        if (isset($data) && !empty($data)) {
            $flag = 1;
            $heading = 'Update';
        }
    ?>
    <div>
        <div class="pagetitle">
            <h1><?php echo e($heading); ?> <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($flag == 1): ?> <?php echo e(ucfirst($data->ticket_id)); ?> <?php else: ?> Ticket <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?></h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Ticket Management</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title pb-0">Ticket Information</h5>
                            <hr class="form-divider">

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($flag == 1): ?>
                                <?php echo e(Form::model($data, ['url' => route('tickets.store'), 'id' => 'edit-ticket-form', 'class' => 'row g-3', 'files' => true])); ?>

                                <?php echo e(Form::hidden('id', null)); ?>

                            <?php else: ?>
                                <?php echo e(Form::open(['url' => route('tickets.store'), 'id' => 'add-ticket-form', 'class' => 'row g-3', 'files' => true])); ?>

                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php
                                $canManage = in_array(getUserRoles(), ['admin', 'qd_developer', 'super_admin']);
                                $isEdit = isset($data);
                                if ($isEdit) {
                                    $ticketValues = [
                                        'open' => 'Open',
                                        'in_progress' => 'In Progress',
                                        'closed' => 'Closed',
                                    ];
                                } else {
                                    $ticketValues = [
                                        'open' => 'Open',
                                    ];
                                }
                                $isQD = getUserRoles() === 'qd_developer';
                                $readonly = $isEdit && $isQD ? ['readonly' => true] : [];
                                $readonlyClass = $isEdit && $isQD ? 'readonly-field' : '';
                            ?>

                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <?php echo Form::label('module', 'Module Name/ Section/ Item name', ['class' => 'form-label required']); ?>

                                <?php echo Form::textarea(
                                    'module',
                                    null,
                                    array_merge(
                                        [
                                            'class' => 'form-control required ' . $readonlyClass,
                                            'placeholder' => 'Enter module name ',
                                            'rows' => 4,
                                            'required',
                                        ],
                                        $readonly,
                                    ),
                                ); ?>

                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                <?php echo Form::label('screenshot_path', 'Screenshot/ Video File (If possible)', ['class' => 'form-label']); ?>

                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!($isEdit && $isQD)): ?>
                                    <?php echo Form::file('screenshot_path[]', [
                                        'class' => 'form-control',
                                        'multiple' => true, // <-- allow multiple
                                    ]); ?>

                                    <small class="text-muted">Upload one or more (select with ctrl) screenshots, videos, or
                                        other files</small>
                                <?php else: ?>
                                    <small class="text-muted">Screenshot upload disabled for developers while
                                        editing.</small>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($flag && isset($data->screenshot_path) && $data->screenshot_path): ?>
                                    <div class="mt-2">
                                        <img src="<?php echo e(Storage::url('uploads/tickets/' . $data->screenshot_path)); ?>"
                                            alt="Image" width="200" height="100">
                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <?php echo Form::label('issue', 'Exact Issue Observed', ['class' => 'form-label required']); ?>

                                <div class="quill-editor-full<?php echo e($isEdit && $isQD ? ' readonly-field' : ''); ?>"
                                    id="quill-editor" style="height: 150px;"></div>
                                <?php echo Form::hidden('issue', null, ['id' => 'editor-content', 'required' => true]); ?>

                            </div>


                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo Form::label('logged_by_user', 'Logged By User (enter your name)', ['class' => 'form-label required']); ?>

                                <?php echo Form::text(
                                    'logged_by_user',
                                    null,
                                    array_merge(
                                        [
                                            'class' => 'form-control required ' . $readonlyClass,
                                            'placeholder' => 'Enter your name here',
                                            'required',
                                        ],
                                        $readonly,
                                    ),
                                ); ?>

                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo Form::label('priority', 'Priority', ['class' => 'form-label required']); ?>

                                <?php echo Form::select('priority', $priority, null, [
                                    'class' => 'form-control required ' . ($isEdit && $isQD ? 'readonly-field' : ''),
                                    'placeholder' => '--Select Priority--',
                                    'required',
                                    'disabled' => $isEdit && $isQD,
                                ]); ?>

                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo Form::label('status', 'Status', ['class' => 'form-label required']); ?>

                                <?php echo Form::select(
                                    'status',
                                
                                    $ticketValues,
                                    null,
                                    [
                                        'class' => 'form-control required',
                                        'required',
                                    ],
                                ); ?>

                            </div>

                            
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                <?php echo Form::label('assigned_to', 'Assign to', ['class' => 'form-label']); ?>

                                <?php echo Form::select('assigned_to', $qdDevelopers ?? [], null, [
                                    'class' => 'form-control ' . ($isEdit && $isQD ? 'readonly-field' : ''),
                                    'placeholder' => '--Select--',
                                    'disabled' => $isEdit && $isQD,
                                ]); ?>

                            </div>
                            

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($canManage): ?>
                                <div class="col-md-12">
                                    <?php echo Form::label('remarks_qd', 'Remark by QD Team', ['class' => 'form-label']); ?>

                                    <?php echo Form::textarea('remarks_qd', null, [
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter remarks from QD team',
                                        'rows' => 3,
                                        'style' => 'min-height: 100px;',
                                    ]); ?>

                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <div class="col-md-12">
                                <?php echo Form::label('further_remarks', 'Further Remarks', ['class' => 'form-label']); ?>

                                <?php echo Form::textarea(
                                    'further_remarks',
                                    null,
                                    array_merge(
                                        [
                                            'class' => 'form-control ' . $readonlyClass,
                                            'placeholder' => 'Enter any additional remarks or updates',
                                            'rows' => 3,
                                        ],
                                        $readonly,
                                    ),
                                ); ?>

                            </div>

                            <div class="col-sm-12 text-end">
                                <button type="submit" class="btn btn-primary"><?php echo e($flag ? 'Update' : 'Submit'); ?></button>
                                <button type="reset" class="btn btn-secondary"
                                    onclick="window.location.reload();">Reset</button>
                            </div>

                            <?php echo e(Form::close()); ?>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <style>
        .readonly-field,
        .readonly-field:disabled,
        .readonly-field[readonly] {
            background-color: #f8f9fa !important;
            color: #6c757d !important;
            border-color: #dee2e6 !important;
        }
    </style>

    <script>
        $(document).ready(function() {
            <?php if($flag && isset($data->issue)): ?>
                setTimeout(function() {
                    $('.quill-editor-full .ql-editor').html(<?php echo json_encode($data->issue); ?>);
                }, 100);
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            <?php if(isset($data) && getUserRoles() === 'qd_developer'): ?>
                setTimeout(function() {
                    $('.quill-editor-full .ql-toolbar').hide();
                    $('.quill-editor-full .ql-editor').attr('contenteditable', false);
                }, 100);
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

            $('form').on('submit', function() {
                var quillContent = $('.quill-editor-full .ql-editor').html();
                $('#editor-content').val(quillContent);
            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/admin/tickets/add.blade.php ENDPATH**/ ?>