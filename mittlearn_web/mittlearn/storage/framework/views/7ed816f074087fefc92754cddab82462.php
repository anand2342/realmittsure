

<?php $__env->startSection('content'); ?>
    <div class="pagetitle d-flex justify-content-between align-items-center">
        <div>
            <h1>CRM Automation Dashboard</h1>
            <nav>
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-outline-secondary text-center" title="Go Back">
                <i class="bi bi-arrow-left"></i>
                <span>Back</span>
            </a>
            <?php
                $now = \Carbon\Carbon::now('Asia/Kolkata');
                $hour = $now->hour;

                // Show between 8 PM (20) to 8 AM (8)
                $showButton = $hour >= 20 || $hour < 8;
            ?>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(Auth::user()->is_admin == 1 && $showButton): ?>
                <a href="<?php echo e(route('crm.automation.log')); ?>" class="btn btn-outline-secondary text-center" title="Go Back">
                    <span>Log</span> <i class="bi bi-arrow-right"></i>
                </a>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
    <section class="section dashboard mt-1">
        
        <?php
            $boxes = [
                ['key' => 'total', 'label' => 'CRM Fetched Schools', 'icon' => 'bi-building', 'color' => '#4472C4'],
                [
                    'key' => 'activated',
                    'label' => 'Activated Schools',
                    'icon' => 'bi-check-circle',
                    'color' => '#70AD47',
                ],
                ['key' => 'not_activated', 'label' => 'Not Activated', 'icon' => 'bi-x-circle', 'color' => '#FF0000'],
                [
                    'key' => 'logged_once',
                    'label' => 'Logged In Once',
                    'icon' => 'bi-person-check',
                    'color' => '#ED7D31',
                ],
                ['key' => 'not_logged', 'label' => 'Not Logged In Yet', 'icon' => 'bi-person-x', 'color' => '#9E480E'],
                [
                    'key' => 'addon_alloted',
                    'label' => 'Licence Alloted',
                    'icon' => 'bi-patch-check',
                    'color' => '#7030A0',
                ],
            ];
        ?>

        <div class="row g-3 mb-4">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $boxes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $box): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-xl-2 col-lg-4 col-md-6">
                    <a href="<?php echo e(route('crm.automation.dashboard', ['filter' => $box['key']])); ?>"
                        class="text-decoration-none">
                        <div class="card h-100 shadow-sm stat-box <?php echo e($filter === $box['key'] ? 'active-box' : ''); ?>"
                            style="border-top: 4px solid <?php echo e($box['color']); ?>; cursor: pointer;">
                            <div class="card-body text-center py-3">
                                <i class="bi <?php echo e($box['icon']); ?> fs-2" style="color: <?php echo e($box['color']); ?>"></i>
                                <h3 class="fw-bold mt-2 mb-0" style="color: <?php echo e($box['color']); ?>">
                                    <?php echo e($stats[$box['key']]); ?>

                                </h3>
                                <p class="text-muted mb-0" style="font-size: 13px;"><?php echo e($box['label']); ?></p>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
        
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($filter): ?>
            <div class="card shadow-sm">
                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">
                            Schools —
                            <?php
                                $labelMap = [
                                    'total' => 'CRM Fetched Schools',
                                    'activated' => 'Activated Schools',
                                    'not_activated' => 'Not Activated Schools',
                                    'logged_once' => 'Logged In Once',
                                    'not_logged' => 'Not Logged In Yet',
                                    'addon_alloted' => 'Licence Alloted',
                                ];
                            ?>
                            <span class="text-primary"><?php echo e($labelMap[$filter] ?? $filter); ?></span>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($schools instanceof \Illuminate\Pagination\LengthAwarePaginator): ?>
                                <span class="badge bg-success text-white ms-1"><?php echo e($schools->total()); ?></span>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </h5>

                        <a href="<?php echo e(route('crm.automation.dashboard.export', ['filter' => $filter])); ?>"
                            class="btn btn-success btn-sm">
                            <i class="bi bi-file-earmark-excel me-1"></i> Download Excel
                        </a>
                    </div>

                    <form method="GET" action="<?php echo e(route('crm.automation.dashboard')); ?>" id="searchForm">
                        <input type="hidden" name="filter" value="<?php echo e($filter); ?>">

                        <div class="d-flex align-items-center justify-content-between mb-3">

                            
                            <div class="d-flex align-items-center gap-2">

                                
                                <div class="input-group" style="width: 320px;">
                                    <span class="input-group-text"><i class="bi bi-search"></i></span>

                                    <input type="text" name="search" class="form-control form-control-sm"
                                        placeholder="Search by School Name or SOID" value="<?php echo e(request('search')); ?>">
                                </div>

                                
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search"></i> Search
                                </button>

                                
                                <a href="<?php echo e(route('crm.automation.dashboard', ['filter' => $filter, 'per_page' => request('per_page', 10)])); ?>"
                                    class="btn btn-secondary">
                                    Clear
                                </a>


                            </div>

                            
                            <div class="d-flex align-items-center gap-1">
                                <label class="mb-0 text-muted" style="font-size:13px;">Per Page</label>
                                <select name="per_page" class="form-select form-select-sm" style="width: 80px;"
                                    onchange="this.form.submit()">
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [10, 20, 30, 50, 100]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $opt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($opt); ?>"
                                            <?php echo e(request('per_page', 10) == $opt ? 'selected' : ''); ?>>
                                            <?php echo e($opt); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </select>
                            </div>

                        </div>
                    </form>

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($schools->isNotEmpty()): ?>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" style="font-size: 13px;">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>SOID</th>
                                        <th>School Name</th>
                                        <th>Email</th>
                                        <th>Mobile</th>
                                        <th>Decision Maker</th>
                                        <th>RM Name</th>
                                        <th>RM Mobile</th>
                                        <th>Series</th>
                                        <th>LMS Status</th>
                                        <th>Last Login</th>
                                        <th>Mittlens</th>
                                        <th>Techlite</th>
                                        <th>Onboarded</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $schools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $i => $school): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                            $user = $school->user;
                                            $uad = $school->user_additional_details;
                                            $rmId = $uad->assign_to ?? null;
                                            $rm = $rmId ? \App\Models\User::find($rmId) : null;
                                            $addon = $user
                                                ? \App\Models\CrmSchoolAddon::where('user_id', $user->id)
                                                    ->where('series_name', 'think trail')
                                                    ->first()
                                                : null;

                                            $series = \App\Models\SchoolAssignedDigitalContent::where(
                                                'school_id',
                                                $school->user_id,
                                            )
                                                ->join(
                                                    'book_series',
                                                    'book_series.id',
                                                    '=',
                                                    'school_assigned_digital_contents.series_id',
                                                )
                                                ->distinct()
                                                ->pluck('book_series.name');

                                            $chipColors = [
                                                '#4472C4',
                                                '#70AD47',
                                                '#ED7D31',
                                                '#7030A0',
                                                '#FF0000',
                                                '#0F6E56',
                                            ];
                                            $lastLogin = $user->loginLogs()->latest('login_at')->first();

                                            // Serial number accounting for pagination offset
                                            $serial = ($schools->currentPage() - 1) * $schools->perPage() + $i + 1;
                                        ?>
                                        <tr>
                                            <td><?php echo e($serial); ?></td>
                                            <td><?php echo e($user->soid ?? '—'); ?></td>
                                            <td class="fw-semibold"><?php echo e($school->name ?? '—'); ?></td>
                                            <td><?php echo e($user->email ?? '—'); ?></td>
                                            <td><?php echo e($user->mobile_no ?? '—'); ?></td>
                                            <td>
                                                <?php echo e($uad->decision_maker ?? '—'); ?><br>
                                                <small
                                                    class="text-muted"><?php echo e($uad->decision_maker_mobile_no ?? ''); ?></small>
                                            </td>
                                            <td><?php echo e($rm->name ?? '—'); ?></td>
                                            <td><?php echo e($rm->mobile_no ?? '—'); ?></td>
                                            <td>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $series; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $si => $sName): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <span
                                                        style="display:inline-block;padding:1px 8px;border-radius:999px;
                                                background:<?php echo e($chipColors[$si % count($chipColors)]); ?>22;
                                                border:1px solid <?php echo e($chipColors[$si % count($chipColors)]); ?>;
                                                color:<?php echo e($chipColors[$si % count($chipColors)]); ?>;
                                                font-size:11px;white-space:nowrap;">
                                                        <?php echo e($sName); ?>

                                                    </span>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($user && $user->status == 1): ?>
                                                    <span class="badge bg-success">Approved in LMS</span>
                                                <?php else: ?>
                                                    <span class="badge bg-danger">Not Approved</span>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </td>
                                            <td><?php echo e($lastLogin ? \Carbon\Carbon::parse($lastLogin->login_at)->format('d M Y') : '—'); ?>

                                            </td>
                                            <td class="text-center"><?php echo e($addon->mittleance ?? 0); ?></td>
                                            <td class="text-center"><?php echo e($addon->techlite ?? 0); ?></td>
                                            <td><?php echo e($school->created_at ? $school->created_at->format('d M Y') : '—'); ?></td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </tbody>
                            </table>
                        </div>

                        
                        <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                            <div class="text-muted" style="font-size:13px;">
                                Showing <?php echo e($schools->firstItem()); ?> to <?php echo e($schools->lastItem()); ?>

                                of <?php echo e($schools->total()); ?> entries
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(request('search')): ?>
                                    <span class="text-primary">(filtered by "<?php echo e(request('search')); ?>")</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                            <div>
                                <?php echo e($schools->links('pagination::bootstrap-5')); ?>

                            </div>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info">No schools found for this filter.</div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                </div>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    </section>

    <style>
        .stat-box {
            transition: transform 0.15s, box-shadow 0.15s;
        }

        .stat-box:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12) !important;
        }

        .active-box {
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.25) !important;
            transform: translateY(-2px);
        }
    </style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/admin/dashboard/automation-dashboard.blade.php ENDPATH**/ ?>