<?php $__env->startSection('content'); ?>
    <?php echo $__env->make('admin.layouts.flash-messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <nav aria-label="breadcrumb">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <!-- Breadcrumb Section -->
            <ol class="breadcrumb m-0">
                <li class="breadcrumb-item"><a href="<?php echo e(route('sp.my.courses')); ?>">Subjects/ Courses</a></li>
                <li class="breadcrumb-item"><a href="<?php echo e(route('sp.class.subject', $classId)); ?>">Class Subjects</a></li>
                <li class="breadcrumb-item"><a
                        href="<?php echo e(route('sp.course.listing', ['id' => $subjectId, 'class_id' => $classId])); ?>">Book
                        Listing</a></li>
                <li class="breadcrumb-item active" aria-current="page">Chapters</li>

            </ol>

            <!-- Dropdown for selecting the number of chapters to display -->
            <div class="text-center mb-2">
                <label for="chapterLimit" class="fw-semibold me-2 active">Number of Chapters to Display:</label>
                <select id="chapterLimit" class="form-select d-inline-block w-auto">
                    <option value="10" <?php echo e(request('limit') == 10 ? 'selected' : ''); ?>>10 </option>
                    <option value="15" <?php echo e(request('limit') == 15 ? 'selected' : ''); ?>>15 </option>
                    <option value="20" <?php echo e(request('limit') == 20 ? 'selected' : ''); ?>>20 </option>
                    <option value="25" <?php echo e(request('limit') == 25 ? 'selected' : ''); ?>>25 </option>
                    <option value="all" <?php echo e(request('limit') == 'all' ? 'selected' : ''); ?>>All Chapters</option>
                </select>
            </div>
            <div class="text-center mb-2">
                <form method="GET" action="<?php echo e(request()->url()); ?>" <label for="chapterLimit"
                    class="fw-semibold me-2 active">Content Language</label>
                    <select id="chapterLimit" class="form-select d-inline-block w-auto" onchange="this.form.submit()"
                        name='language'>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = config('constants.CONTENT_LANGUAGE'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>"
                                <?php echo e(request('language') == $key ? 'selected' : 'bilingual'); ?>>
                                <?php echo e($lang); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </select>
                </form>
            </div>
        </div>
    </nav>


    <div class="cardBox">
        <div class="classSubjectBookName mb-1 d-flex justify-content-between align-items-center flex-wrap">
            <span class="fw-semibold">
                <?php echo e($className); ?> - <?php echo e($subjectName); ?> - <?php echo e($courseName); ?>

            </span>

            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($chapters instanceof \Illuminate\Pagination\LengthAwarePaginator): ?>
                <div class="customPagination m-0">
                    <?php echo e($chapters->links('vendor.pagination.bootstrap-5')); ?>

                </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>


        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(isset($chapters)): ?>
            <div id="chapterContainer">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $chapters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="chapterBox">
                        <div class="d-flex align-items-center gap-3 chapterName mb-4">
                            <div class="chapterNumber">
                                <?php echo e($data->sort_order); ?>

                            </div>
                            <div>
                                <h3 class="fs-6 fw-semibold mb-0"><?php echo e($data->chapter_name); ?></h3>
                                <span>Chapter Description:
                                    <b title="<?php echo e($data->chapter_description); ?>">
                                        <?php echo e(Str::limit($data->chapter_description, 150, '...')); ?>

                                    </b>
                                </span>
                            </div>
                        </div>

                        <div class="chapterVideos">
                            <?php
                                $language = request('language') ?? 'bilingual';
                                $chapterFiles = collect($data->chapterListing)->filter(function ($file) use (
                                    $language,
                                ) {
                                    return $file->language === $language;
                                });
                                $videos = $chapterFiles->whereIn('file_extension', [
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
                                    '3gp',
                                    'm2ts',
                                    'ogv',
                                    'ts',
                                    'mxf',
                                ]);
                                $documents = $chapterFiles->whereIn('file_extension', [
                                    'pdf',
                                    'docx',
                                    'xlsx',
                                    'jpeg',
                                    'jpg',
                                    'png',
                                ]);
                                $resources = collect($data->resources)->whereIn('file_extension', [
                                    'pdf',
                                    'docx',
                                    'xlsx',
                                    'jpeg',
                                    'jpg',
                                    'png',
                                ]);
                            ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($videos->isNotEmpty()): ?>
                                <div class="mb-4">
                                    <h4 class="fs-6 fw-semibold">Video <b>(<?php echo e($videos->count()); ?>)</b></h4>
                                    <ul class="chapterList documentList">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $videos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li>
                                                <div class="chapterBtn">
                                                    <figure class="position-relative">
                                                        <img src="<?php echo e(asset('frontend/images/video-icon.svg')); ?>"
                                                            alt="Video Icon" />
                                                        <button type="button" class="plybtn" data-bs-toggle="modal"
                                                            data-bs-target="#coursePreview-<?php echo e($video->id); ?>">
                                                        </button>
                                                    </figure>
                                                    <div class="w-100 p-2">
                                                        <p><?php echo e($video->file_name ? $video->file_name : $video->original_name); ?>

                                                        </p>
                                                        <div class="d-flex align-items-center gap-4">
                                                            
                                                        </div>
                                                    </div>
                                                    <div class="modal coursePrv" id="coursePreview-<?php echo e($video->id); ?>"
                                                        tabindex="-1" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            <div class="modal-content rounded-0 border-0"
                                                                style="    background: rgba(0, 0, 0, .5);color: #fff;">
                                                                <div class="modal-header border-0">
                                                                    <h1 class="modal-title fs-5 fw-normal">Course Preview
                                                                    </h1>
                                                                    <button type="button" class="btn-close"
                                                                        data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body p-0">
                                                                    <p class="py-2 px-3 fs-8">
                                                                        <?php echo e($video->sort_order); ?> .
                                                                        <?php echo e($video->file_name ? $video->file_name : $data->chapter_name); ?>

                                                                    </p>
                                                                    <video width="100%" height="240" controls
                                                                        controlsList="nodownload"
                                                                        oncontextmenu="return false;">
                                                                        <source
                                                                            src="<?php echo e(Storage::url('uploads/course_chapter_files/' . $video->attachment_file)); ?>"
                                                                            type="video/mp4">
                                                                    </video>
                                                                    

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </ul>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($documents->isNotEmpty()): ?>
                                <div class="mb-4">
                                    <h4 class="fs-6 fw-semibold">Document <b>(<?php echo e($documents->count()); ?>)</b></h4>
                                    <ul class="chapterList documentList">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $documents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li>
                                                <div class="chapterBtn">
                                                    <figure>
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(str_contains($document->file_extension, 'mp3') || str_contains($document->file_extension, 'wav')): ?>
                                                            <a href="<?php echo e(Storage::url('uploads/course_chapter_files/' . $document->attachment_file)); ?>"
                                                                target="_blank"> <img
                                                                    src="<?php echo e(asset('frontend/images/audio-icon.svg')); ?>"
                                                                    alt="Audio Icon">
                                                            </a>
                                                        <?php elseif(str_contains($document->file_extension, 'jpg') ||
                                                                str_contains($document->file_extension, 'png') ||
                                                                str_contains($document->file_extension, 'jpeg')): ?>
                                                            <a href="<?php echo e(Storage::url('uploads/course_chapter_files/' . $document->attachment_file)); ?>"
                                                                target="_blank">
                                                                <img src="<?php echo e(asset('frontend/images/jpg-icon.svg')); ?>"
                                                                    alt="Audio Icon">
                                                            </a>
                                                        <?php elseif(str_contains($document->file_extension, 'pdf')): ?>
                                                            <a href="<?php echo e(Storage::url('uploads/course_chapter_files/' . $document->attachment_file)); ?>"
                                                                target="_blank"> <img
                                                                    src="<?php echo e(asset('frontend/images/pdf-icon.svg')); ?>"
                                                                    alt="PDF Icon">
                                                            </a>
                                                        <?php elseif(str_contains($document->file_extension, 'xlsx')): ?>
                                                            <a href="<?php echo e(Storage::url('uploads/course_chapter_files/' . $document->attachment_file)); ?>"
                                                                target="_blank">
                                                                <img src="<?php echo e(asset('frontend/images/xls-img.svg')); ?>"
                                                                    alt="xls Icon">
                                                            </a>
                                                        <?php elseif(str_contains($document->file_extension, 'docx')): ?>
                                                            <a href="<?php echo e(Storage::url('uploads/course_chapter_files/' . $document->attachment_file)); ?>"
                                                                target="_blank"> <img
                                                                    src="<?php echo e(asset('frontend/images/wordpress-icon.svg')); ?>"
                                                                    alt="PDF Icon">
                                                            </a>
                                                        <?php else: ?>
                                                            <img src="<?php echo e(asset('frontend/images/default-icon.svg')); ?>"
                                                                alt="Default Icon">
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    </figure>
                                                    <div class="w-100 p-2">
                                                        <p><?php echo e($document->original_name); ?></p>
                                                        <div class="d-flex align-items-center gap-4">
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </ul>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($resources->isNotEmpty()): ?>
                                <hr class="form-divider">
                                <div class="mb-4 mt-2">
                                    <h4 class="fs-6 fw-semibold">Resources <b>(<?php echo e($resources->count()); ?>)</b></h4>
                                    <ul class="chapterList documentList">
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $resources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li>
                                                <div class="chapterBtn">
                                                    <figure>
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(str_contains($document->file_extension, 'mp3') || str_contains($document->file_extension, 'wav')): ?>
                                                            <a href="<?php echo e(Storage::url('uploads/course_chapter_files/' . $document->attachment_file)); ?>"
                                                                target="_blank"> <img
                                                                    src="<?php echo e(asset('frontend/images/audio-icon.svg')); ?>"
                                                                    alt="Audio Icon">
                                                            </a>
                                                        <?php elseif(str_contains($document->file_extension, 'jpg') ||
                                                                str_contains($document->file_extension, 'png') ||
                                                                str_contains($document->file_extension, 'jpeg')): ?>
                                                            <a href="<?php echo e(Storage::url('uploads/course_chapter_files/' . $document->attachment_file)); ?>"
                                                                target="_blank">
                                                                <img src="<?php echo e(asset('frontend/images/jpg-icon.svg')); ?>"
                                                                    alt="Audio Icon">
                                                            </a>
                                                        <?php elseif(str_contains($document->file_extension, 'pdf')): ?>
                                                            <a href="<?php echo e(Storage::url('uploads/course_chapter_files/' . $document->attachment_file)); ?>"
                                                                target="_blank"> <img
                                                                    src="<?php echo e(asset('frontend/images/pdf-icon.svg')); ?>"
                                                                    alt="PDF Icon">
                                                            </a>
                                                        <?php elseif(str_contains($document->file_extension, 'xlsx')): ?>
                                                            <a href="<?php echo e(Storage::url('uploads/course_chapter_files/' . $document->attachment_file)); ?>"
                                                                target="_blank">
                                                                <img src="<?php echo e(asset('frontend/images/xls-img.svg')); ?>"
                                                                    alt="xls Icon">
                                                            </a>
                                                        <?php elseif(str_contains($document->file_extension, 'docx')): ?>
                                                            <a href="<?php echo e(Storage::url('uploads/course_chapter_files/' . $document->attachment_file)); ?>"
                                                                target="_blank"> <img
                                                                    src="<?php echo e(asset('frontend/images/wordpress-icon.svg')); ?>"
                                                                    alt="PDF Icon">
                                                            </a>
                                                        <?php else: ?>
                                                            <img src="<?php echo e(asset('frontend/images/default-icon.svg')); ?>"
                                                                alt="Default Icon">
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                    </figure>
                                                    <div class="w-100 p-2">
                                                        <p><?php echo e(preg_replace('/[^a-zA-Z\s]/', '', Str::replace('-', ' ', strtok($document->attachment_file, '.')))); ?>

                                                        </p>
                                                        <div class="d-flex align-items-center gap-4">
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </ul>
                                </div>
                            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($chapters instanceof \Illuminate\Pagination\LengthAwarePaginator): ?>
                    <div class="customPagination mt-4">
                        <?php echo e($chapters->links('vendor.pagination.bootstrap-5')); ?>

                    </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </div>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>
    <style>
        .small {
            margin-top: 1rem !important;
        }
    </style>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let chapterLimitSelect = document.getElementById("chapterLimit");

            chapterLimitSelect.addEventListener("change", function() {
                let limit = this.value;
                let currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('limit', limit);
                currentUrl.searchParams.set('page', 1); // reset to first page
                window.location.href = currentUrl.toString();
            });
        });
    </script>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('schoolPortal.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/schoolPortal/myCourses/courses-details.blade.php ENDPATH**/ ?>