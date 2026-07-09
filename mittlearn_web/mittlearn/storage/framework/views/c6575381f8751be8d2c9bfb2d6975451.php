<?php $__env->startSection('content'); ?>
    <div class="pagetitle">
        <h1>Assign Digital Content</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item active">Assign Digital Content</li>
            </ol>
        </nav>
    </div>
    <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('school-digital-content', ['id' => $id]);

$key = null;

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-1561232829-0', null);

$__html = app('livewire')->mount($__name, $__params, $key);

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
    <script>
        $(document).ready(function() {
            document.addEventListener('DOMContentLoaded', function() {
                const multiSelect = document.getElementById('multiSelect');
            });
        });

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
        document.addEventListener("change", function(event) {
            if (event.target.matches("[wire\\:model^='selectedSeriesId']")) {
                setTimeout(initSelect2, 500);
            }
        });
        document.addEventListener("change", function(event) {
            if (event.target.matches("[wire\\:model^='rows'][wire\\:model*='.series_id']")) {
                setTimeout(initSelect2, 500);
            }
        });
        document.addEventListener("click", function(event) {
            if (event.target.closest("[wire\\:click^='addRow']")) {
                setTimeout(initSelect2, 500);
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/admin/user/school_assigned_digital_content.blade.php ENDPATH**/ ?>