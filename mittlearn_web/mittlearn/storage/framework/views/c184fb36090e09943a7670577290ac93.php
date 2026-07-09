<?php $__env->startSection('content'); ?>
    <div id="page-header" class="page-header">
        <section class="section">
            <div class="pagetitle">
                <h1>Our Offerings</h1>
                <nav>
                    <ol class="breadcrumb">
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="text-end mb-2">
                    <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-primary"><i class="ri-arrow-left-line"></i></a>
                </div>
                <?php echo e(Form::open(['url' => route('our.offerings.save'), 'method' => 'post', 'files' => true])); ?>

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class= "row g-3">
                                <h4 class="card-title">Our Offerings & Images</h4>
                                <hr class="form-divider">
                                <div class="col-md-12 col-sm-6 col-xs-12">
                                    <?php echo Form::hidden('section_name_1', 'our_offerings', ['class' => 'form-control']); ?>

                                </div>
                                
                                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('our-offerings', ['ourOfferingsAddtional' => $ourOfferingsAddtional]);

$key = null;

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-2058337009-0', null);

$__html = app('livewire')->mount($__name, $__params, $key);

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-end mt-3">
                    <?php echo Form::submit('Submit', ['class' => 'btn btn-primary']); ?>

                    <?php echo Form::reset('Reset', ['class' => 'btn btn-secondary']); ?>

                </div>
                <?php echo e(Form::close()); ?>

            </div>
        </section>
    </div>
<?php $__env->stopSection(); ?>
<script>
    function updateWordCount(element, maxWords) {
        const text = element.value.trim();
        const words = text.split(/\s+/).filter(word => word.length > 0);
        const wordCount = words.length;

        if (wordCount > maxWords) {
            element.value = words.slice(0, maxWords).join(" ");
            document.getElementById('word-count-message').textContent = `Maximum ${maxWords} words allowed.`;
        } else {
            document.getElementById('word-count-message').textContent = `Words: ${wordCount}/${maxWords}`;
        }
    }
    document.addEventListener("DOMContentLoaded", function() {
        const textarea = document.getElementById('textarea');
        updateWordCount(textarea, 50);
    });
</script>

<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/admin/websitePages/ourOfferings/our-offerings-page.blade.php ENDPATH**/ ?>