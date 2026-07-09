<?php $__env->startSection('content'); ?>
    <div>
        <style>
            .course-chip {
                display: inline-block;
                border: 1px solid #00438C;
                border-radius: 8px;
                padding: 2px 5px;
                margin: 2px 2px;
            }
        </style>
        <div class="pagetitle">
            <h1>Courses Purchase Report</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Purchase Report</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">



                            <div class="row mb-3">
                                <div class="col-sm-6">
                                    <h5 class="card-title">Courses Purchase Report</h5>
                                </div>
                                <div class="col-sm-6 text-end">
                                    <div class="course-chip">
                                        Total <i class="bi bi-currency-rupee"></i>: &nbsp;<?php echo e($totalAmountAll ?? 0); ?>


                                    </div>
                                </div>
                            </div>
                            <hr class="fromdivider">


                            <div class="table-responsive tbleDiv ">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Total Amount</th>
                                            <th>User Name</th>
                                            <th>User Mobile</th>
                                            <th>TXN Id</th>
                                            <th>Payment Id</th>
                                            <th>Date</th>
                                            <th>Purchage Courses</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $datalist; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($k + $datalist->firstItem()); ?>.</td>
                                                <td><?php echo e($val->total_amount ?? 'NA'); ?></td>
                                                <td><?php echo e($val->userDetail->name); ?></td>
                                                <td><?php echo e($val->userDetail->mobile_no); ?></td>
                                                <td><?php echo e($val->txn_id ?? 'NA'); ?></td>
                                                <td><?php echo e($val->payment_id ?? 'NA'); ?></td>
                                                <td><?php echo e($val->created_at ? $val->created_at->format('d-m-Y') : 'NA'); ?></td>
                                                <td>
                                                    <?php
                                                        $courseIds = json_decode($val->cart, true); // converts JSON string to array
                                                        $courses = [];
                                                        if ($courseIds && count($courseIds)) {
                                                            $courses = \App\Models\Course::whereIn('id', $courseIds)
                                                                ->pluck('course_name')
                                                                ->toArray();
                                                        }
                                                    ?>

                                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($courses)): ?>
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                            <span class="course-chip"><?php echo e($course); ?></span>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    <?php else: ?>
                                                        NA
                                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-right text-right">
                                <?php echo $datalist->links('pagination::bootstrap-4'); ?>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/admin/plans/purchase_report.blade.php ENDPATH**/ ?>