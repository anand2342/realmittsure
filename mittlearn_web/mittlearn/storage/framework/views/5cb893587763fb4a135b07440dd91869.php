

<?php $__env->startSection('content'); ?>
    <div class="pagetitle">
        <h1>Content Deck Folder Files</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item active">Content Deck Folder Files</li>
            </ol>
        </nav>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="col-md-12">
                        <div class="cardBox classDetails">
                            <div class="d-flex justify-content-between align-items-center flex-wrap mb-4">
                                <div class="col-sm-6">
                                    <div class="card-title"><?php echo e($folder->folder_name); ?></div>
                                </div>
                                <div class="plannerHeader">
                                    
                                    <button type="button" class="btn btn-primary" data-bs-toggle="tooltip"
                                        data-bs-placement="bottom" data-bs-title="Add New Content"><span
                                            data-bs-target="#createFile" data-bs-toggle="modal">Add File</span></button>
                                </div>
                            </div>
                            <hr class="form-divider">
                            <div class="classesCourse mb-4">
                                <div class="row">
                                    <div id="search-results" class="row mt-3">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($contentFolderView && $contentFolderView->count() > 0): ?>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $contentFolderView; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="col-xl-2 col-lg-3 col-md-3 mb-3 px-2 position-relative class-item"
                                                    data-title="<?php echo e($data->original_name); ?>">
                                                    <div class="classesBx">
                                                        <figure>
                                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(str_contains($data->file_extension, 'mp3') || str_contains($data->file_extension, 'wav')): ?>
                                                                <a href="<?php echo e(Storage::url('uploads/media-files/' . $data->attachment_file)); ?>"
                                                                    target="_blank"> <img
                                                                        src="<?php echo e(asset('frontend/images/audio-icon.svg')); ?>"
                                                                        alt="Audio Icon">
                                                                </a>
                                                            <?php elseif(in_array($data->file_extension, [
                                                                    'mp4',
                                                                    'avi',
                                                                    'mov',
                                                                    'm4v',
                                                                    'm4p',
                                                                    'mpg',
                                                                    'mp2',
                                                                    'mpeg',
                                                                    'mpe',
                                                                    'mpv',
                                                                    'm2v',
                                                                    'wmv',
                                                                    'flv',
                                                                    'mkv',
                                                                    'webm',
                                                                    '3gp',
                                                                    'm2ts',
                                                                    'ogv',
                                                                    'ts',
                                                                    'mxf',
                                                                ])): ?>
                                                                <!-- For video files, display video icon -->
                                                                <a href="<?php echo e(Storage::url('uploads/media-files/' . $data->attachment_file)); ?>"
                                                                    target="_blank">
                                                                    <img src="<?php echo e(asset('frontend/images/video-icon.svg')); ?>"
                                                                        alt="Video Icon" />
                                                                </a>
                                                            <?php elseif(str_contains($data->file_extension, 'jpg') ||
                                                                    str_contains($data->file_extension, 'jpeg') ||
                                                                    str_contains($data->file_extension, 'png')): ?>
                                                                <a href="<?php echo e(Storage::url('uploads/media-files/' . $data->attachment_file)); ?>"
                                                                    target="_blank">
                                                                    <img src="<?php echo e(asset('frontend/images/jpg-icon.svg')); ?>"
                                                                        alt="Audio Icon">
                                                                </a>
                                                            <?php elseif(str_contains($data->file_extension, 'pdf')): ?>
                                                                <a href="<?php echo e(Storage::url('uploads/media-files/' . $data->attachment_file)); ?>"
                                                                    target="_blank"> <img
                                                                        src="<?php echo e(asset('frontend/images/pdf-icon.svg')); ?>"
                                                                        alt="PDF Icon">
                                                                </a>
                                                            <?php elseif(str_contains($data->file_extension, 'xlsx')): ?>
                                                                <a href="<?php echo e(Storage::url('uploads/media-files/' . $data->attachment_file)); ?>"
                                                                    target="_blank">
                                                                    <img src="<?php echo e(asset('frontend/images/xls-img.svg')); ?>"
                                                                        alt="xls Icon">
                                                                </a>
                                                            <?php elseif(str_contains($data->file_extension, 'docx')): ?>
                                                                <a href="<?php echo e(Storage::url('uploads/media-files/' . $data->attachment_file)); ?>"
                                                                    target="_blank"> <img
                                                                        src="<?php echo e(asset('frontend/images/wordpress-icon.svg')); ?>"
                                                                        alt="PDF Icon">
                                                                </a>
                                                            <?php else: ?>
                                                                <img src="<?php echo e(asset('frontend/images/default-icon.svg')); ?>"
                                                                    alt="Default Icon">
                                                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                                        </figure>
                                                        <span><?php echo e(\Illuminate\Support\Str::limit($data->original_name, 20)); ?></span>
                                                        <p><?php echo e(\Carbon\Carbon::parse($data->created_at)->format('d M Y')); ?>

                                                        </p>
                                                    </div>
                                                    <button type="button"
                                                        class="btn btn-danger btn-sm position-absolute top-0 end-0  me-2"
                                                        onclick="confirmDelete('<?php echo e(route('media.gallery.file.delete', $data->id)); ?>')">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <?php else: ?>
                                            <div class="col-xl-2 col-lg-3 col-md-3 mb-3 px-2">
                                                <span>No Data Available</span>
                                            </div>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mt-4">
                <div class="card-header"><strong>Assigned Series</strong></div>
                <div class="card-body d-flex flex-wrap gap-2">

                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $assignedSeries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $series): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="badge bg-secondary p-2 d-flex align-items-center">
                            <?php echo e($series); ?>

                            <a href="<?php echo e(route('media.folder.remove.series', [$folder->id, $id])); ?>"
                                class="text-white ms-2">
                                <i class="fa fa-times"></i>
                            </a>
                        </span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header"><strong>Assigned User Types</strong></div>
                <div class="card-body d-flex flex-wrap gap-2">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $assignedRoles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="badge bg-primary p-2 d-flex align-items-center">
                            <?php echo e(ucfirst(str_replace('_', ' ', $role))); ?>

                            <a href="<?php echo e(route('media.folder.remove.role', [$folder->id, $role])); ?>"
                                class="text-white ms-2">
                                <i class="fa fa-times"></i>
                            </a>
                        </span>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array('school_admin', $assignedRoles)): ?>
                <div class="card mt-4">
                    <div class="card-header"><strong>Assigned Schools</strong></div>
                    <div class="card-body d-flex flex-wrap gap-2">

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($folder->distribute_schools === 'all'): ?>
                            <span class="badge bg-success p-2">
                                All Schools
                            </span>
                        <?php else: ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $assignedSchools; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="badge bg-info p-2 d-flex align-items-center">
                                    <?php echo e($name); ?>

                                    <a href="<?php echo e(route('media.folder.remove.school', [$folder->id, $id])); ?>"
                                        class="text-white ms-2">
                                        <i class="fa fa-times"></i>
                                    </a>
                                </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(in_array('school_teacher', $assignedRoles)): ?>
                <div class="card mt-4">
                    <div class="card-header"><strong>Assigned Teachers</strong></div>
                    <div class="card-body d-flex flex-wrap gap-2">

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($folder->distribute_teachers === 'all'): ?>
                            <span class="badge bg-success p-2">
                                All Teachers
                            </span>
                        <?php else: ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $assignedTeachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="badge bg-success p-2 d-flex align-items-center">
                                    <?php echo e($name); ?>

                                    <a href="<?php echo e(route('media.folder.remove.teacher', [$folder->id, $id])); ?>"
                                        class="text-dark ms-2">
                                        <i class="fa fa-times"></i>
                                    </a>
                                </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                    </div>
                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>


        </div>

        <div class="modal fade" id="createFile">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="">
                            <h5 class="modal-title" id="exampleModalToggleLabel">Upload Files</h5>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body pt-0">
                        <form action="<?php echo e(route('store.files')); ?>" method="POST" enctype="multipart/form-data">
                            <?php echo csrf_field(); ?>
                            <div class="folderChoosefile" id="dropArea">
                                <div id="fileName" class=""></div> <!-- Display uploaded file name -->
                                <label for="uploader" class="mt-3">
                                    <img src="<?php echo e(asset('frontend/images/download-file.svg')); ?>" alt=""
                                        width="25">
                                    <span>Choose file to upload</span>
                                    <p class="m-0">or drag and drop</p>
                                    <input type="file" name="file" id="uploader" class="d-none">
                                    <input type="hidden" name="folder_id" value="<?php echo e($folder->id); ?>">
                                </label>
                            </div>
                            <div class="d-flex align-items-center justify-content-end flex-column mt-3">
                                <button type="submit" class="btn btn-primary">Submit</button>
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

        <style>
            .folderChoosefile {
                border: 2px dashed #ccc;
                padding: 20px;
                text-align: center;
                border-radius: 10px;
                transition: border-color 0.3s;
                cursor: pointer;
            }

            .folderChoosefile.dragover {
                border-color: #007bff;
                background-color: #f0f8ff;
            }

            #fileName {
                font-size: 14px;
                color: #000;
                padding: 5px;
                background-color: #DBF8EA;
            }
        </style>


    </div>
    <script>
        var globalVar = {
            page: 'content_folder_view',
        };
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/admin/mediaGallery/folder_files_view.blade.php ENDPATH**/ ?>