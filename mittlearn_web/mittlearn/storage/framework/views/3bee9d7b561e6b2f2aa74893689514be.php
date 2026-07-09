<?php $__env->startSection('content'); ?>
    <div id="page-header" class="page-header">
        <section class="section">
            <div class="pagetitle">
                <h1>Website Page Content</h1>
                <nav>
                    <ol class="breadcrumb">
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="text-end mb-2">
                    <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-primary"><i class="ri-arrow-left-line"></i></a>
                </div>
                <?php echo e(Form::open(['url' => route('home.page-content.save'), 'method' => 'post', 'files' => true])); ?>

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class= "row g-3">
                                <h4 class="card-title">Banner</h4>
                                <hr class="form-divider">
                                <div class="col-md-12 col-sm-6 col-xs-12">
                                    <?php echo Form::hidden('section_name_1', 'first_banner', ['class' => 'form-control']); ?>

                                </div>
                                <div class="col-md-12 col-sm-6 col-xs-12">
                                    <?php echo Form::label('heading', 'Heading', ['class' => 'form-label required']); ?>

                                    <?php echo Form::text('heading', $firstBanner->heading ?? null, ['class' => 'form-control']); ?>

                                </div>
                                <h6>Category & Images</h6>
                                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('home-page-academic-group', ['firstBannerAddtional' => $firstBannerAddtional]);

$key = null;

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-4020454745-0', null);

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
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class= "row g-3">
                                <h4 class="card-title">Core Feature Banner</h4>
                                <hr class="form-divider">
                                <div class="col-md-12 col-sm-6 col-xs-12">
                                    <?php echo Form::hidden('section_name_2', 'feature_banner', ['class' => 'form-control']); ?>

                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <?php echo Form::label('core_title', 'Title', ['class' => 'form-label required']); ?>

                                    <?php echo Form::text('core_title', $coreFeatureBanner->core_title ?? null, ['class' => 'form-control']); ?>

                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <?php echo Form::label('core_heading', 'Heading', ['class' => 'form-label required']); ?>

                                    <?php echo Form::text('core_heading', $coreFeatureBanner->core_heading ?? null, ['class' => 'form-control']); ?>

                                </div>
                                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('home-core-feature-content', ['coreAcademicFeatureAddtional' => $coreAcademicFeatureAddtional]);

$key = null;

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-4020454745-1', null);

$__html = app('livewire')->mount($__name, $__params, $key);

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>
                                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('non-academic-core-feature', ['coreNonAcademicFeatureAddtional' => $coreNonAcademicFeatureAddtional]);

$key = null;

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-4020454745-2', null);

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

                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class= "row g-3">
                                <h4 class="card-title">Instructor Banner</h4>
                                <hr class="form-divider">
                                <div class="col-md-12 col-sm-6 col-xs-12">
                                    <?php echo Form::hidden('section_name_3', 'instructor_banner', ['class' => 'form-control']); ?>

                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <?php echo Form::label('instructor_title', 'Title', ['class' => 'form-label required']); ?>

                                    <?php echo Form::text('instructor_title', $instructorBanner->instructor_title ?? null, ['class' => 'form-control']); ?>

                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <?php echo Form::label('instructor_description', 'Description', ['class' => 'form-label required']); ?>

                                    <?php echo Form::text('instructor_description', $instructorBanner->instructor_description ?? null, [
                                        'class' => 'form-control',
                                    ]); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class= "row g-3">
                                <h4 class="card-title">Testimonial Banner</h4>
                                <hr class="form-divider">
                                <div class="col-md-12 col-sm-6 col-xs-12">
                                    <?php echo Form::hidden('section_name_4', 'testimonial_banner', ['class' => 'form-control']); ?>

                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <?php echo Form::label('heading_1', 'Heading', ['class' => 'form-label required']); ?>

                                    <?php echo Form::text('heading_1', $testimonialBanner->heading_1 ?? null, [
                                        'class' => 'form-control',
                                        'required',
                                        'placeholder' => 'Enter Heading',
                                    ]); ?>

                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-12">
                                    <?php echo Form::label('sub_heading_1', 'Sub Heading', ['class' => 'form-label required']); ?>

                                    <?php echo Form::text('sub_heading_1', $testimonialBanner->sub_heading_1 ?? null, [
                                        'class' => 'form-control',
                                        'required',
                                        'placeholder' => 'Enter Sub Heading',
                                    ]); ?>

                                </div>
                            </div>
                            <div class="text-end mt-3">
                                <?php echo Form::submit('Submit', ['class' => 'btn btn-primary']); ?>

                                <?php echo Form::reset('Reset', ['class' => 'btn btn-secondary']); ?>

                            </div>
                        </div>
                    </div>
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

<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/admin/websitePages/home-content-page.blade.php ENDPATH**/ ?>