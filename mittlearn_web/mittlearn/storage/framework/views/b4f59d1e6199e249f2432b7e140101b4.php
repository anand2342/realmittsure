<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('admin.layouts.flash-messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="row px-lg-1">
        <div class="col-lg-8 px-lg-2 mb-3">
            <div class="cardBox adminBx h-100">
                <div class="">
                    <h6>Welcome, <b class="text-primary fw-semibold"> <?php echo e(Auth::user()->name); ?> </b> <lottie-player
                            src="<?php echo e(asset('frontend/images/hand.json')); ?>" loop autoplay
                            style="width: 35px;height: 30px;"></lottie-player>
                    </h6>
                    <span>Always stay updated in your teacher portal</span>
                    <p>
                        Access real-time updates, manage activities, and streamline your tasks effortlessly. Explore
                        tools and features designed to simplify teacher administration.
                    </p>

                </div>
                <div class="position-relative">
                    <img src="<?php echo e(asset('frontend/images/admin-img2.svg')); ?>" alt="" width="230">
                    <lottie-player src="<?php echo e(asset('frontend/images/rocket.json')); ?>" background="transparent" speed="1"
                        style="width: 100px; height: 100px;position: absolute;top: -20px;left: -30px;" loop
                        autoplay></lottie-player>
                </div>
            </div>
        </div>
        <div class="col-lg-4 px-lg-2">
            <div class="cardBox countBx mb-3">
                <div class="d-flex justify-content-between align-items-center py-1">
                    <figure class="mb-0">
                        <img src="<?php echo e(asset('frontend/images/total-students-icon.svg')); ?>" alt="" width="70">
                    </figure>
                    <span>Total Students <b><?php echo e($students); ?></b></span>
                </div>

            </div>
            <div class="cardBox countBx">
                <div class="d-flex justify-content-between align-items-center py-1">
                    <figure class="mb-0">
                        <img src="<?php echo e(asset('frontend/images/total-parents-icon.svg')); ?>" alt="">
                    </figure>
                    <span>Total Parents/Guardian <b><?php echo e($students); ?></b></span>
                </div>

            </div>
        </div>
    </div>
    <div class="row px-lg-1">
        <div class="col-lg-5 px-lg-2 mb-3">
            <div class="cardBox">
                <div class="headingBx">
                    <h4>Planned Online Classes</h4>
                    <div class="d-flex gap-2">
                        <a href="<?php echo e(route('online.class')); ?>" class="viewAll">View all</a>
                    </div>
                </div>
                <ul class="listingUl plannedCall">
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
                            <?php
                                $startTime = Carbon\Carbon::parse($class->start_time);
                                $endTime = Carbon\Carbon::parse($class->end_time);
                                $duration = $endTime->diffInMinutes($startTime);
                            ?>
                            <li>
                                <div class="listBox">
                                    <div class="plannedMain">
                                        <div class="d-flex gap-2">
                                            <figure class="m-0">
                                                <img src="<?php echo e(asset('frontend/images/notification-img1.jpg')); ?>"
                                                    alt="">
                                            </figure>
                                            <div>
                                                <span><?php echo e($class->instructor->name); ?></span>
                                                <div class="iconBtm align-items-start">
                                                    <b><img src="<?php echo e(asset('frontend/images/time-date-icon.svg')); ?>"
                                                            alt="" class="me-2"
                                                            width="15"><?php echo e($class->class_date . '  ' . $class->start_time); ?></b>
                                                </div>
                                                <span class="badge green"><?php echo e($class->subject->name); ?></span>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </ul>
            </div>
        </div>
        <div class="col-lg-7 px-lg-2 mb-3">
            <div class="cardBox">
                <div class="headingBx d-block d-md-flex justify-content-between overallSelect">
                    <h4>Class-wise count of students</h4>
                    
                    <div class="d-flex align-items-center  gap-2 mt-3 mt-md-0">


                    </div>
                </div>
                <div id="courseStatistics" style="height: 242px;"></div>
            </div>
        </div>
    </div>
    

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/variable-pie.js"></script>
    <script src="https://code.highcharts.com/modules/xrange.js"></script>

    <script>
        document.getElementById('classFilter').addEventListener('change', function() {
            const selectedClass = this.value;
            const classItems = document.querySelectorAll('#plannedClassesList li');

            classItems.forEach(item => {
                if (selectedClass === 'all' || item.dataset.classId === selectedClass) {
                    item.style.display = 'block';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    </script>
    <script>
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
        // Create the chart
        Highcharts.chart('courseStatistics', {
            chart: {
                type: 'column'
            },
            title: {
                align: 'left',
                text: null
            },
            subtitle: {
                align: 'left',
                text: null
            },
            accessibility: {
                announceNewData: {
                    enabled: true
                }
            },
            xAxis: {
                type: 'category'
            },
            yAxis: {
                title: {
                    text: null
                }
            },
            legend: {
                enabled: false
            },
            tooltip: {
                headerFormat: '<span style="font-size:11px">{series.name}</span><br>',
                pointFormat: '<span style="color:{point.color}">{point.name}</span>: ' +
                    '<b>{point.y:.2f}%</b> of total<br/>'
            },
            series: [{
                name: 'Subjects',
                colorByPoint: true,
                data: <?php echo json_encode($chartData, 15, 512) ?> // Pass the PHP data to JavaScript
            }]
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('schoolPortal.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/schoolPortal/teacherPortal/dashboard.blade.php ENDPATH**/ ?>