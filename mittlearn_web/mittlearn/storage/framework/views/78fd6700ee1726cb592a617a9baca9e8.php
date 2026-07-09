<?php $__env->startSection('content'); ?>
    <?php
        $flag = 0;
        $hidebutton = null;
        $heading = 'Add';
        if (isset($data) && !empty($data)) {
            $flag = 1;
            $heading = isset($viewOnly) ? 'View' : 'Update';
            $hidebutton = '1';
            // dd($hidebutton);
        }

    ?>

    <div>
        <div class="pagetitle">
            <h1><?php echo e($heading); ?> User</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">User</li>
                </ol>
            </nav>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($flag != 1): ?>
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-0">Bulk Upload Users</h5>
                                <hr class="form-divider">

                                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('user-bulk-upload', ['roles' => $roles]);

$key = null;

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-2001366481-0', null);

$__html = app('livewire')->mount($__name, $__params, $key);

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

                                <!-- Bulk Upload Status/Feedback -->
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session()->has('errorMsg')): ?>
                                    <div class="alert alert-danger mt-3">
                                        <?php echo e(session('errorMsg')[0]); ?>

                                    </div>
                                <?php elseif(session()->has('successMsg')): ?>
                                    <div class="alert alert-success mt-3">
                                        <?php echo e(session('successMsg')); ?>

                                    </div>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    <div class="card">
                        <div class="card-body">

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($flag == 1): ?>
                                <?php echo e(Form::model($data, ['url' => route('user.save'), 'id' => 'edit-plan-form', 'class' => 'row g-3', 'enctype' => 'multipart/form-data'])); ?>

                                <?php echo e(Form::hidden('id', $data->id)); ?>

                            <?php else: ?>
                                <?php echo e(Form::open(['url' => route('user.save'), 'id' => 'add-plan-form', 'class' => 'row g-3', 'enctype' => 'multipart/form-data'])); ?>

                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('role-form', ['roles' => $roles, 'users' => $users, 'salesman' => isset($salesman) ? $salesman : null, 'distributors' => isset($distributors) ? $distributors : null, 'boards' => $boards, 'mediums' => $mediums, 'sections' => $sections, 'classes' => $classes,'courseData' => $courseData, 'subjects' => $subjects, 'cities' => $cities, 'states' => $states, 'schoolList' => $schoolList,'schools' => $schools, 'verify' => $verify ?? null, 'userData' => $data ?? null, 'school_classes' => $school_assigned_class ?? null, 'flag' => $flag, 'viewOnly' => $viewOnly ?? false]);

$key = null;

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-2001366481-1', null);

$__html = app('livewire')->mount($__name, $__params, $key);

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                            
                            
                            

                            <?php echo e(Form::close()); ?>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script>
        function downloadSampleFile(roleKey) {

            let downloadUrl;
            if (roleKey === 'school_student' || roleKey === 'school_admin' || roleKey === 'school_teacher' || roleKey ===
                'b2c_student' || roleKey === 'd2c_user') {
                downloadUrl = `/admin/sample-files/${roleKey}-sample-file.xlsx`;
            } else {
                downloadUrl = `/admin/sample-files/user_file-sample-file.xlsx`;
            }

            window.location.href = downloadUrl; // Now it's accessible here
        }
    </script>
    <script>
        $(document).ready(function() {
            // Initialize Select2 with custom checkboxes
            document.addEventListener('DOMContentLoaded', function() {
                const multiSelect = document.getElementById('multiSelect');
            });

        });
    </script>
    <script>
        function initSelect2() {
            $(".js-select2").select2({
                closeOnSelect: false,
                placeholder: "Select",
                allowClear: false,
                tags: true
            });
        }
        document.addEventListener("DOMContentLoaded", function() {
            initSelect2();
        });
        document.addEventListener("livewire:load", function() {
            Livewire.hook('message.processed', (message, component) => {
                initSelect2();
            });
        });
 
        document.addEventListener("change", function(event) {
            if (event.target.matches("[wire\\:model='selectedState']")) {
                setTimeout(initSelect2, 1000); // Small delay to allow Livewire to update the DOM
            }
        });
        document.addEventListener("change", function(event) {
            if (event.target.matches("[wire\\:model='selectedSchool']")) {
                setTimeout(initSelect2, 1000); // Small delay to allow Livewire to update the DOM
            }
        });

        document.addEventListener("change", function(event) {
            if (event.target.matches("[wire\\:model='schoolType']")) {
                setTimeout(initSelect2, 1000); // Small delay to allow Livewire to update the DOM
            }
        });
        document.addEventListener("change", function(event) {
            if (event.target.matches("[wire\\:model='schoolRole']")) {
                setTimeout(initSelect2, 1000); // Small delay to allow Livewire to update the DOM
            }
        });
        document.addEventListener("change", function(event) {
            if (event.target.matches("[wire\\:model='selectedSession']")) {
                setTimeout(initSelect2, 1000); 
            }
        });
        document.addEventListener("change", function(event) {
            if (event.target.matches("[wire\\:model='selectedCategory']")) {
                setTimeout(initSelect2, 500); 
            }
        });
        document.addEventListener("change", function(event) {
            if (event.target.matches("[wire\\:model='selectedSubCategory']")) {
                setTimeout(initSelect2, 500); 
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/admin/user/add.blade.php ENDPATH**/ ?>