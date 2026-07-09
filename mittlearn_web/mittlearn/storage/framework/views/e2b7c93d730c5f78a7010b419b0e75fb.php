<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('admin.layouts.flash-messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <div class="row">
        <div class="col-lg-12 col-md-12 pe-md-1 mb-3 mb-lg-0">
            <div class="cardBox">
                <h2 class="fs-6 fw-semibold mb-4">Online Classes</h2>
                <div class="d-md-flex justify-content-between mb-3">
                    <ul class="nav nav-tabs tbs border-0 onlineTabs">
                        <li class="nav-item ">
                            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#ongoingTab"
                                type="button">Ongoing Classes</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#pastTab" type="button">Past
                                Classes</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#upcomingTab"
                                type="button">Upcoming Classes</button>
                        </li>
                    </ul>
                    <div class="">
                        <button type="button" data-bs-target="#newCall" data-bs-toggle="offcanvas"
                            class="btn-primary-gradient rounded-1 d-flex align-items-center gap-2"> <img
                                src="<?php echo e(asset('frontend/images/new-call-icon.svg')); ?>" alt="" width="18">
                            New Online Class</button>
                    </div>
                </div>

                <div class="tab-content">
                    <div class="tab-pane fade show active" id="ongoingTab">
                        <ul class="ScheduleList teacherOnline">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $ongoingClasses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ongoing): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <li>
                                    <div class="schedulContent">
                                        <figure>
                                            <img src="<?php echo e(asset('frontend/images/classroom-study2.jpg')); ?>" alt="">
                                            <div class="labelTxts"><?php echo e($ongoing->subject->name ?? 'No Subject'); ?></div>
                                            <button type="button" class="labelTxts btn btn-success"
                                                data-bs-target="#createFile" data-bs-toggle="modal"
                                                data-class-id="<?php echo e($ongoing->id); ?>"> Add Study Material</button>
                                            <span>Starts at:
                                                <?php echo e(\Carbon\Carbon::parse($ongoing->start_time)->format('h:i A')); ?></span>
                                            <a target="_blank" href="<?php echo e($ongoing->join_link); ?>" data-toggle="tooltip"
                                                data-placement="bottom" title="Click to Join Class">
                                                <lottie-player src="<?php echo e(asset('frontend/images/Play-button.json')); ?>" loop
                                                    autoplay style="width: 50px; height: 50px;" background="transparent">
                                                </lottie-player>
                                            </a>
                                        </figure>
                                        <h3><?php echo e($ongoing->title); ?></h3>
                                        <p class="peragraph"><?php echo e($ongoing->agenda); ?></p>
                                        <p><b>Instructor:</b> <?php echo e($ongoing->instructor->name ?? 'Unknown'); ?></p>
                                        <p><b>Class Start Time:</b>
                                            <?php echo e(\Carbon\Carbon::parse($ongoing->start_time)->format('h:i A')); ?></p>
                                        <p><b>Class End Time:</b>
                                            <?php echo e(\Carbon\Carbon::parse($ongoing->end_time)->format('h:i A')); ?></p>
                                        <p><b>Assigned Class:</b> <?php echo e($ongoing->class->name ?? 'Unknown'); ?></p>
                                    </div>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <p style="fw-medium mt-3 notFoundOnlineClass">
                                    No classes scheduled yet! 📅 Ready to start something amazing? Create your first class
                                    now! 🚀
                                </p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </ul>

                    </div>

                    <div class="tab-pane fade" id="pastTab">
                        <ul class="ScheduleList teacherOnline">
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $pastClasses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $past): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <li>
                                    <div class="schedulContent">
                                        <figure><img src="<?php echo e(asset('frontend/images/classroom-study1.jpg')); ?>"
                                                alt="">
                                            <div class="labelTxts"><?php echo e($past->subject->name ?? 'No Subject'); ?></div>
                                        </figure>
                                        <h3><?php echo e($past->title); ?></h3>
                                        <p><b>Instructor</b><?php echo e($past->instructor->name ?? 'Unknown'); ?></p>
                                        <p><b>Date</b> <?php echo e($past->class_date ?? 'Unknown'); ?></p>
                                        <p><b>Class start
                                                time</b><?php echo e(\Carbon\Carbon::parse($past->start_time)->format('h:i A')); ?>

                                        </p>
                                        <p><b>Class end
                                                time</b><?php echo e(\Carbon\Carbon::parse($past->end_time)->format('h:i A')); ?>

                                        </p>
                                        <p><b>Assigned class</b><?php echo e($past->class->name ?? 'Unknown'); ?></p>

                                        

                                        

                                        <a class="btn btn-primary-gradient rounded-1 mx-auto d-block"
                                            href="<?php echo e(route('online.class.details', $past->id)); ?>">View
                                            Details</a>
                                    </div>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <p style="fw-medium mt-3 notFoundOnlineClass">
                                    No past classes found! 📚 You're either just getting
                                    started or acing attendance! 🎉</p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </div>
                    <div class="tab-pane fade" id="upcomingTab">
                        <ul class="ScheduleList teacherOnline">
                            
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $upcomingClasses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $upcoming): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <li>
                                    <div class="schedulContent">
                                        <figure>
                                            <img src="<?php echo e(asset('frontend/images/classroom-study2.jpg')); ?>" alt="">
                                            <div class="labelTxts"><?php echo e($upcoming->subject->name ?? 'No Subject'); ?></div>
                                            <button type="button" class="labelTxts btn btn-success"
                                                data-bs-target="#createFile" data-bs-toggle="modal"
                                                data-class-id="<?php echo e($upcoming->id); ?>">Add Study Material</button>
                                        </figure>
                                        <h3><?php echo e($upcoming->title); ?></h3>
                                        <p class="peragraph"><?php echo e($upcoming->agenda); ?></p>
                                        <p><b>Instructor</b> <?php echo e($upcoming->instructor->name ?? 'Unknown'); ?></p>
                                        <p><b>Date</b> <?php echo e($upcoming->class_date ?? 'Unknown'); ?></p>
                                        <p><b>Class start
                                                time</b><?php echo e(\Carbon\Carbon::parse($upcoming->start_time)->format('h:i A')); ?>

                                        </p>
                                        <p><b>Class end
                                                time</b><?php echo e(\Carbon\Carbon::parse($upcoming->end_time)->format('h:i A')); ?>

                                        </p>
                                        <p><b>Assigned class</b><?php echo e($upcoming->class->name ?? 'Unknown'); ?></p>
                                    </div>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <p style="fw-medium mt-3 notFoundOnlineClass">
                                    No classes scheduled yet! 📅 Ready to start something
                                    amazing? Create your first class now! 🚀</p>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="offcanvas offcanvas-end " id="newCall">
        <div class="offcanvas-header border-bottom">
            <h5 class="offcanvas-title fs-6 fw-semibold">Add New Online Class</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <form action="<?php echo e(route('online-classes.store')); ?>" method="POST" id="add-online-class-form">
                <?php echo csrf_field(); ?>
                <div class="formPanel">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Title</label>
                                <input type="text" name="title" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Select Class Date</label>
                                <input type="date" name="class_date" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Select Class</label>
                                <select class="form-select" name="class_id" required>
                                    <option value="">Select</option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($id); ?>"><?php echo e($class); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Select Subject</label>
                                <select class="form-select" name="subject_id" required>
                                    <option value="">Select</option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($id); ?>"><?php echo e($subject); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(getUserRoles() !== 'school_teacher'): ?>
                            <div class="col-md-6">
                                <div class="form-group mb-3">
                                    <label>Select Teacher Name</label>
                                    <select class="form-select" name="instructor_id" required>
                                        <option value="">Select</option>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($teacher->id); ?>"><?php echo e($teacher->name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </select>
                                </div>
                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Select Start Time</label>
                                <input type="time" id="start_time" name="start_time" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Select End Time</label>
                                <input type="time" id="end_time" name="end_time" class="form-control" required>
                                <div class="invalid-feedback">
                                    End time must be after the start time.
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mb-3">
                                <label>Join Link</label>
                                <input type="url" name="join_link" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group mb-3">
                                <label>Agenda/Description</label>
                                <textarea name="agenda" class="form-control" style="height: 60px;" maxlength="200" required></textarea>
                                <span class="fw-medium fs-7">200 words only</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="offcanvas-footer">
                    <div class="d-flex align-items-center justify-content-end gap-4">
                        <button type="button" class="btn backbtn" data-bs-dismiss="offcanvas">Back</button>
                        <button type="submit" class="btn btn-primary-gradient rounded-1">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    


    <div class="modal fade" id="studentList">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header align-items-start border-0">
                    <h1 class="modal-title fs-6" id="exampleModalToggleLabel">Student List</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Content will be injected here -->
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade coursePrv" id="coursePreview">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-0 border-0">
                <div class="modal-header border-0">
                    <h1 class="modal-title fs-5 fw-normal">Course Preview</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0">
                    <p class="py-2 px-3 fs-8">Ceramic How to use Ceramic Cone, Designs on Paper by Ceramic cone.</p>
                    <video width="100%" height="240" controls="" controlsList="nodownload"
                        oncontextmenu="return false;">
                        <source src="<?php echo e(asset('frontend/images/modal-vid.mp4')); ?>" type="video/mp4" width="100%">
                    </video>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="createFile">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0">
                <div class="modal-header align-items-start border-0">
                    <h1 class="modal-title fs-5" id="exampleModalToggleLabel">Upload Files</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <form action="<?php echo e(route('online.class.store.files')); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="class_id" id="class_id">
                        <div class="folderChoosefile" id="dropArea">
                            <div id="fileName" class=""></div> <!-- Display uploaded file name -->
                            <label for="uploader">
                                <img src="<?php echo e(asset('frontend/images/download-file.svg')); ?>" alt=""
                                    width="25">
                                <span>Choose file to upload</span>
                                <p class="m-0">or drag and drop</p>
                                <input type="file" name="file" id="uploader" class="d-none">
                            </label>
                        </div>
                        <div class="d-flex align-items-center justify-content-end flex-column">
                            <button type="submit" class="btn btn-primary-gradient rounded-1 mb-2">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const dropArea = document.getElementById('dropArea');
        const fileInput = document.getElementById('uploader');
        const fileNameDisplay = document.getElementById('fileName');
        fileNameDisplay.style.display = 'none'; // Hide drag-and-drop text

        // Prevent default behavior for drag-and-drop events
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, (e) => e.preventDefault());
            dropArea.addEventListener(eventName, (e) => e.stopPropagation());
        });

        // Highlight the drop area on dragover
        dropArea.addEventListener('dragover', () => {
            dropArea.classList.add('dragover');
        });

        // Remove highlight on dragleave or drop
        ['dragleave', 'drop'].forEach(eventName => {
            dropArea.addEventListener(eventName, () => {
                dropArea.classList.remove('dragover');
            });
        });

        // Handle file drop
        dropArea.addEventListener('drop', (e) => {
            const files = e.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files; // Assign dropped files to the file input
                displayFileName(files[0]); // Display the file name
            }
        });

        // Display file name
        const displayFileName = (file) => {
            fileNameDisplay.style.display = 'block'; // Hide drag-and-drop text
            fileNameDisplay.textContent = `Selected File: ${file.name}`;
        };

        // Handle file selection through the file input
        fileInput.addEventListener('change', () => {
            if (fileInput.files.length > 0) {
                displayFileName(fileInput.files[0]);
            }
        });
    </script>
    <script>
        document.querySelectorAll('.btn-success').forEach(button => {
            button.addEventListener('click', function() {
                var classId = this.getAttribute('data-class-id');
                document.getElementById('class_id').value = classId;
            });
        });
    </script>

    <script>
        document.getElementById('start_time').addEventListener('change', validateTime);
        document.getElementById('end_time').addEventListener('change', validateTime);

        function validateTime() {
            const startTime = document.getElementById('start_time').value;
            const endTime = document.getElementById('end_time').value;
            const endTimeInput = document.getElementById('end_time');

            if (startTime && endTime && endTime < startTime) {
                endTimeInput.classList.add('is-invalid');
            } else {
                endTimeInput.classList.remove('is-invalid');
            }
        }
    </script>
    <script>
        $(document).ready(function() {
            $('.totalLearner').on('click', function() {
                var uniqueJoinLogs = $(this).data('unique-joinlogs');
                var modalBody = $('#studentList .modal-body');
                modalBody.empty();
                modalBody.append('<div class="studentLearner"><h3>Total Learner <b>' + uniqueJoinLogs
                    .length + '</b></h3></div>');
                var tableHtml =
                    '<div class="table-responsive learnerList"><table class="table"><tr class="position-sticky top-0"><th width="60%">Name</th><th>Mobile No.</th></tr>';
                if (uniqueJoinLogs.length > 0) {
                    $.each(uniqueJoinLogs, function(index, joinLog) {
                        tableHtml += '<tr><td><div class="profileLerner"><img src="' + (joinLog.user
                                .image ? '/storage/uploads/user/profile_image/' + joinLog
                                .user.image : '/frontend/images/default-image.jpg') +
                            '" alt="">' + joinLog.user.name + '</div></td><td>' + joinLog.user
                            .mobile_no + '</td></tr>';
                    });
                } else {
                    tableHtml += '<tr><td colspan="2">No Record Found</td></tr>';
                }
                tableHtml += '</table></div>';
                modalBody.append(tableHtml);

            });
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('schoolPortal.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/schoolPortal/onlineClass/online_class.blade.php ENDPATH**/ ?>