
<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('admin.layouts.flash-messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="row px-lg-1">
        <div class="col-lg-6 px-lg-2 mb-3">
            <div class="cardBox adminBx h-100">
                <div class="">
                    <h6>Hi, <?php echo e(Auth::user()->name); ?> <lottie-player src="<?php echo e(asset('frontend/images/hand.json')); ?>" loop
                            autoplay style="width: 35px;height: 30px;"></lottie-player></h6>
                    <span>Stay Informed with Your School Admin Portal</span>
                    <p>
                        Access real-time updates, manage activities, and streamline your tasks effortlessly. Explore
                        tools and features designed to simplify school administration.
                    </p>
                </div>
                <img src="<?php echo e(asset('frontend/images/admin-img.png')); ?>" alt="" width="200">
            </div>
        </div>
        <div class="col-lg-6 px-lg-2">
            <div class="row px-md-1">
                <div class="col-md-6 px-md-2 mb-3">
                    <div class="cardBox countBx h-100">
                        <div class="d-flex justify-content-between">
                            <figure>
                                <img src="<?php echo e(asset('frontend/images/total-student-icon.svg')); ?>" alt=""
                                    width="70">
                            </figure>
                            <span>Total Students <b><?php echo e($students); ?></b></span>
                        </div>
                        <p>
                            <img src="<?php echo e($studentChangePercentage >= 0 ? asset('frontend/images/higher-icon.svg') : asset('frontend/images/less-icon.svg')); ?>"
                                alt="" width="14" class="me-2">
                            <?php echo e(abs($studentChangePercentage)); ?>% <?php echo e($studentChangePercentage >= 0 ? 'Higher' : 'Less'); ?>

                            than Last Month
                        </p>
                    </div>
                </div>
                <div class="col-md-6 px-md-2 mb-3">
                    <div class="cardBox countBx h-100">
                        <div class="d-flex justify-content-between">
                            <figure>
                                <img src="<?php echo e(asset('frontend/images/total-teachers-icon.svg')); ?>" alt="">
                            </figure>
                            <span>Total Teachers <b><?php echo e($teachers); ?></b></span>
                        </div>
                        <p>
                            <img src="<?php echo e($studentChangePercentage >= 0 ? asset('frontend/images/higher-icon.svg') : asset('frontend/images/less-icon.svg')); ?>"
                                alt="" width="14" class="me-2">
                            <?php echo e(abs($studentChangePercentage)); ?>%
                            <?php echo e($studentChangePercentage >= 0 ? 'Higher' : 'Less'); ?> than Last Month
                        </p>
                    </div>
                </div>
                <div class="col-md-6 px-md-2 mb-3">
                    <div class="cardBox countBx h-100">
                        <div class="d-flex justify-content-between">
                            <figure>
                                <img src="<?php echo e(asset('frontend/images/digital-content.svg')); ?>" alt="">
                            </figure>
                            <span>Digital Content <b><?php echo e($digitalContent); ?></b></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 px-md-2 mb-3">
                    <div class="cardBox countBx h-100">
                        <div class="d-flex justify-content-between">
                            <figure>
                                <img src="<?php echo e(asset('frontend/images/available-access-icon.svg')); ?>" alt="">
                            </figure>
                            <span>Licenses/ Access Codes <br>Teachlite : <strong class="accessCodeCount">
                                    <?php echo e($availableAccessCodesTeachlite); ?> <br></strong>MittsureLens : <strong
                                    class="accessCodeCount"> <?php echo e($availableAccessCodesMittlense); ?></strong></span>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    

    

    

    

    <div class="row px-lg-1">
        <div class="col-lg-6 px-lg-2 mb-3">
            <div class="cardBox">
                <div class="headingBx">
                    <h4>Planned Online Classes</h4>
                    <div class="d-flex gap-2">
                        <select id="classFilter" class="form-select">
                            <option value="all" selected>Select Class</option>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($id); ?>"><?php echo e($class); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </select>
                    </div>
                </div>
                <ul id="plannedClassesList" class="classesUl">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($plannedClasses->isEmpty()): ?>
                        <li>
                            <div class="plannedList text-center py-4">
                                <strong>
                                    <h4>No classes scheduled</h4>
                                </strong>
                            </div>
                        </li>
                    <?php else: ?>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $plannedClasses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li data-class-id="<?php echo e($class->class_id); ?>">
                                <div class="plannedList">
                                    <div class="d-flex planUser gap-2">
                                        <figure>
                                            <img src="<?php echo e(asset('frontend/images/gallery1.jpg')); ?>" alt="">
                                        </figure>
                                        <div>
                                            <h4><?php echo e($class->title); ?></h4>
                                            <span>
                                                <img src="<?php echo e(asset('frontend/images/list-profile.jpg')); ?>" alt="">
                                                <?php echo e($class->instructor->name ?? 'N/A'); ?>

                                            </span>
                                        </div>
                                    </div>
                                    <?php
                                        $startTime = Carbon\Carbon::parse($class->start_time ?? 'N/A');
                                        $endTime = Carbon\Carbon::parse($class->end_time ?? 'N/A');
                                        $duration = $endTime->diffInMinutes($startTime);
                                    ?>
                                    <strong>Duration<b><?php echo e($duration); ?> Min.</b></strong>
                                    <strong>Scheduled
                                        Time<b><?php echo e($class->class_date ?? ('N/A' . '  ' . $class->start_time ?? 'N/A')); ?></b></strong>
                                </div>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </ul>
            </div>
        </div>
        <div class="col-lg-6 px-lg-2 mb-3">
            <div class="cardBox">
                <div class="headingBx">
                    <h4>Student Count</h4>
                    <div class="d-flex align-items-center gap-2">
                    </div>
                </div>
                <div id="studentCount" style="height: 240px;"></div>
            </div>
        </div>
    </div>
<?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($showExpiryPopup) && $showExpiryPopup): ?>
<div class="modal fade" id="sessionExpiryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-center p-4" style="border-radius: 12px;">
            
            <h5 class="mb-3 text-danger fw-bold">Your Access Has Expired</h5>

            <p class="mb-2">
                Your academic session has recently expired. You are currently in a 
                <strong>15-day grace period</strong>, so your access is still temporarily available.
            </p>

            <p class="mb-3">
                To continue uninterrupted learning and access to all digital content, we recommend renewing your account at the earliest.
            </p>

            <div class="mb-3">
                <strong>Don’t miss out on your learning progress.</strong><br>
                Secure your access now and continue without any interruption.
            </div>

            <p class="mb-3">
                For assistance or to renew your account, please contact Mittsure Support or email us at  
                <a href="mailto:support@mittsure.com"><b>itsupport@mittsure.com</b></a>.
            </p>

            <div class="d-flex justify-content-center gap-2">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    Maybe Later
                </button>
               
            </div>

        </div>
    </div>
</div>
<?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    


    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/variable-pie.js"></script>
    <script src="https://code.highcharts.com/modules/xrange.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>

    <script>
document.addEventListener('DOMContentLoaded', function () {
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(!empty($showExpiryPopup) && $showExpiryPopup): ?>
        var myModal = new bootstrap.Modal(document.getElementById('sessionExpiryModal'));
        myModal.show();
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
});
</script>
    <script>
        document.getElementById('downloadButton').addEventListener('click', function() {
            // Capture the whole dashboardMain, including scrolled content
            html2canvas(document.querySelector(".dashboardMain"), {
                scrollX: 0,
                scrollY: -window.scrollY,
                useCORS: true,
                onrendered: function(canvas) {
                    let imgData = canvas.toDataURL("image/png");
                    let link = document.createElement('a');
                    link.href = imgData;
                    link.download = 'dashboard.png';
                    link.click();
                }
            });
        });


        $('.alertList').slick({
            autoplay: true,
            slidesToShow: 1,
            arrows: false,
            dots: false,
            autoplaySpeed: 0,
            speed: 15000,
            cssEase: 'linear',
            variableWidth: true,
        });
    </script>

    <script>
        // Convert PHP collection to JS object
        const studentsData = <?php echo json_encode($studentsPerMonth, 15, 512) ?>;

        // Map month numbers to month names (e.g., 1 → "Jan")
        const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

        // Prepare data for Highcharts (fill missing months with 0)
        const chartData = Array(12).fill(0).map((_, index) => {
            const month = index + 1; // Months are 1-12
            return studentsData[month] || 0; // Default to 0 if no data
        });

        // Now create your chart
        document.addEventListener('DOMContentLoaded', function() {
            const yearlyTotal = chartData.reduce((sum, count) => sum + count, 0);

            Highcharts.chart('studentCount', {
                chart: {
                    type: 'column',
                    backgroundColor: 'transparent',
                    events: {
                        load: function() {
                            // Add total label in top-right corner
                            this.renderer.label(
                                    `Total: <b>${yearlyTotal}</b> students`,
                                    this.chartWidth - 120,
                                    15,
                                    undefined,
                                    undefined,
                                    undefined,
                                    true
                                )
                                .css({
                                    fontSize: '13px'
                                })
                                .add();
                        }
                    }
                },
                title: {
                    text: null
                },
                subtitle: {
                    text: `Student Enrollment (${new Date().getFullYear()})`,
                    align: 'left'
                },
                xAxis: {
                    categories: monthNames
                },
                yAxis: {
                    title: {
                        text: 'Number of Students'
                    }
                },
                plotOptions: {
                    column: {
                        color: '#fabc5b',
                        borderRadius: 3,
                        dataLabels: {
                            enabled: true,
                            format: '{y}',
                            style: {
                                textOutline: 'none'
                            }
                        }
                    }
                },
                tooltip: {
                    shared: true,
                    formatter: function() {
                        return `
                    <b>${monthNames[this.x]}</b><br>
                    New Students: <b>${this.points[0].y}</b><br>
                    Cumulative Total: <b>${this.points[0].total}</b>
                `;
                    }
                },
                series: [{
                    name: 'Monthly',
                    data: chartData.map(point => ({
                        y: point,
                        total: yearlyTotal // Pass total for tooltip
                    })),
                    showInLegend: false
                }]
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            try {
                const filter = document.getElementById('classFilter');
                const classList = document.getElementById('plannedClassesList');

                if (!filter || !classList) {
                    throw new Error('Required filter elements not found');
                }

                filter.addEventListener('change', function() {
                    const selectedClass = this.value;
                    const items = classList.querySelectorAll('li[data-class-id]');

                    items.forEach(item => {
                        const shouldShow = selectedClass === 'all' ||
                            item.dataset.classId === selectedClass.toString();
                        item.style.display = shouldShow ? 'block' : 'none';
                    });
                });

                // Initialize filter on load
                filter.dispatchEvent(new Event('change'));
            } catch (error) {
                console.error('Filter initialization failed:', error);
            }
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('schoolPortal.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/schoolPortal/dashboard.blade.php ENDPATH**/ ?>