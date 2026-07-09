<?php $__env->startSection('content'); ?>
    <div id="page-header" class="page-header">
        <section class="section">
            <div class="pagetitle">
                <div class="pagetitle">
                    <h1>Book/Course Chapters</h1>
                    <nav>
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item">Home</li>
                            <li class="breadcrumb-item">Book/Courses</li>
                            <li class="breadcrumb-item active"><?php echo e($courseName); ?></li>
                        </ol>
                    </nav>
                </div>
            </div>
            <section class="section">
                <div class="row">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row align-items-center">
                                <!-- Search section (col-md-8) -->
                                <div class="col-md-8">
                                    <form method="GET" action="<?php echo e(route('course.add.chapter', [$course_id])); ?>"
                                        class="row g-2 align-items-center">
                                        <div class="col-md-5">
                                            <input type="text" name="chapter_name" class="form-control"
                                                placeholder="Search by Chapter Name"
                                                value="<?php echo e(request('chapter_name')); ?>" />
                                        </div>
                                        <div class="col-md-4">
                                            <button type="submit" class="btn btn-primary">Search</button>
                                            <a href="<?php echo e(route('course.add.chapter', [$course_id])); ?>"
                                                class="btn btn-secondary">Clear</a>
                                        </div>
                                    </form>
                                </div>

                                <!-- Per page records (col-md-4) -->
                                <div class="col-md-4">
                                    <div class="d-flex align-items-center justify-content-end">
                                        <label for="roles" class="me-2">Per Page Record:</label>
                                        <select id="paginationSelectOnpage" class="form-select form-select-sm"
                                            style="width: 80px;">
                                            <option value="" disabled
                                                <?php echo e(session('per_page_records') ? '' : 'selected'); ?>>
                                                --Select--</option>
                                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [10, 20, 30, 40, 50]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($option); ?>"
                                                    <?php echo e(session('per_page_records') == $option ? 'selected' : ''); ?>>
                                                    <?php echo e($option); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="card-title">All Chapters For: &nbsp;<?php echo e($courseName); ?></h5>
                                <div class="text-end">
                                    <a href="<?php echo e(route('courses.chapter.bulk-upload', request()->route('course_id'))); ?>"
                                        class="btn btn-primary">Chapter Bulk Upload</a>
                                    <button type="button" class="btn btn-success" onclick="scrollToAddNewRow()">Add New
                                        Chapter</button>
                                </div>
                            </div>
                            <div class="table-responsive tbleDiv ">
                                <table id="datatable" class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>S.No.</th>
                                            <th>Chapter Name</th>
                                            <th>Chapter Description</th>
                                            <th>Sort Order</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $chapters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chapter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td><?php echo e($chapters->firstItem() + $loop->index); ?>.</td>
                                                <td>
                                                    <span><input type="text" disabled
                                                            value="<?php echo e($chapter->chapter_name); ?>"
                                                            style="border: none; font-size: 11px;; background: transparent; width:100%"></span>
                                                </td>
                                                <td>
                                                    <span title="<?php echo e($chapter->chapter_description); ?>"
                                                        style="cursor: pointer;">
                                                        <?php echo e(\Illuminate\Support\Str::limit($chapter->chapter_description, 80)); ?>

                                                    </span>
                                                </td>


                                                </td>
                                                <?php
$__split = function ($name, $params = []) {
    return [$name, $params];
};
[$__name, $__params] = $__split('courses-chapter-sort-order', ['chapter' => $chapter]);

$key = $chapter->id;

$key ??= \Livewire\Features\SupportCompiledWireKeys\SupportCompiledWireKeys::generateKey('lw-1664989552-0', $chapter->id);

$__html = app('livewire')->mount($__name, $__params, $key);

echo $__html;

unset($__html);
unset($__name);
unset($__params);
unset($__split);
if (isset($__slots)) unset($__slots);
?>

                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('isPermission', 'course.edit')): ?>
                                                            <a class="btn btn-warning btn-sm me-2"
                                                                href="<?php echo e(route('course.chapter.edit', $chapter->id)); ?>"
                                                                title="Edit">
                                                                <i class="fa fa-pencil"></i>
                                                            </a>
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('isPermission', 'course.delete')): ?>
                                                            <a class="btn btn-danger btn-sm me-2" href="javascript:void(0);"
                                                                onclick="confirmDelete('<?php echo e(route('course.chapter.delete', $chapter->id)); ?>')"
                                                                title="Delete">
                                                                <i class="fa fa-trash"></i>
                                                            </a>
                                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                                                    </div>
                                                </td>
                                            </tr>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </tbody>

                                </table>
                            </div>
                            <div class="d-flex justify-content-right text-right">
                                <?php echo $chapters->links('pagination::bootstrap-4'); ?>

                            </div>
                        </div>
                    </div>
                </div>
    </div>
    </section>

    <div class="row" id="add-new-row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="card-title">Add New Chapter</div>
                        </div>
                        
                    </div>
                    <hr class="form-divider">

                    <?php echo e(Form::model($course ?? null, ['url' => route('course.add.chapter.store'), 'id' => 'add-course-form', 'class' => 'row g-3', 'files' => true])); ?>

                    <?php echo e(Form::hidden('course_id', request()->route('course_id'))); ?>


                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-xs-12 input-div">
                            <div class="">
                                <?php echo Form::label('chapter_title', 'Chapter Title', ['class' => 'form-label required']); ?>

                                <?php echo e(Form::text('chapter_title', null, [
                                    'class' => 'form-control',
                                    'autocomplete' => 'off',
                                    'required',
                                    'id' => 'vallidateTitle',
                                    'placeholder' => 'Chapter Title',
                                ])); ?>

                            </div>
                            <small id="vallidateTitleError" class="form-text text-danger mt-1"
                                style="display:none;"></small>
                        </div>

                        <div class="col-md-6 col-sm-6 col-xs-12 input-div">
                            <div class="">
                                <?php echo Form::label('chapter_description', 'Chapter Description', ['class' => 'form-label required']); ?>

                                <?php echo e(Form::textarea('chapter_description', null, [
                                    'class' => 'form-control',
                                    'autocomplete' => 'off',
                                    'required',
                                    'placeholder' => 'Chapter Description',
                                ])); ?>

                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6 col-xs-12 input-div">
                            <div class="">
                                <?php echo Form::label('topic_covered', 'Topic Covered', ['class' => 'form-label ']); ?>

                                <?php echo e(Form::text('topic_covered', null, [
                                    'class' => 'form-control',
                                    'autocomplete' => 'off',
                                    'placeholder' => 'Topic Covered',
                                ])); ?>

                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6 col-xs-12 input-div">
                            <div class="">
                                <?php echo Form::label('sort_order', 'Chapter Sort Order', ['class' => 'form-label required']); ?>

                                <?php echo e(Form::number('sort_order', null, [
                                    'class' => 'form-control',
                                    'autocomplete' => 'off',
                                    'required',
                                    'placeholder' => 'Sort Order',
                                ])); ?>

                            </div>
                        </div>

                        <div class="col-md-6 col-sm-6 col-xs-12 input-div">
                            <div class="">
                                <?php echo Form::label('content_creation_date', 'Content Creation Date', ['class' => 'form-label']); ?>

                                <?php echo e(Form::date('content_creation_date', \Carbon\Carbon::today()->format('Y-m-d'), [
                                    'class' => 'form-control',
                                    'autocomplete' => 'off',
                                    'required',
                                ])); ?>

                            </div>
                        </div>

                        

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($courseCategory == 1): ?>
                            <div class="col-md-6 col-sm-6 col-xs-12 input-div">
                                <?php echo Form::label('teaching_manuals', 'Teaching Manual', ['class' => 'form-label']); ?>

                                <?php echo Form::file('teaching_manuals', ['class' => 'form-control']); ?>

                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12 input-div">
                                <?php echo Form::label('question_bank', 'Question Bank', ['class' => 'form-label']); ?>

                                <?php echo Form::file('question_bank', ['class' => 'form-control']); ?>

                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12 input-div">
                                <?php echo Form::label('lesson_planner', 'Lesson Plan', ['class' => 'form-label']); ?>

                                <small data-bs-toggle="tooltip" title="PDF of Lesson Plan">(PDF of Lesson
                                    Plan)</small>
                                <?php echo Form::file('lesson_planner', ['class' => 'form-control']); ?>

                            </div>
                        <?php else: ?>
                            <div class="col-md-6 col-sm-6 col-xs-12 input-div">
                                <?php echo Form::label('worksheet', 'Worksheets/Practice Sheets', ['class' => 'form-label']); ?>

                                <?php echo Form::file('worksheet', ['class' => 'form-control']); ?>

                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12 input-div">
                                <?php echo Form::label('answer_sheet', 'Answer Sheet', ['class' => 'form-label']); ?>

                                <?php echo Form::file('answer_sheet', ['class' => 'form-control']); ?>

                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12 input-div">
                                <?php echo Form::label('other_pdf', 'Other PDF', ['class' => 'form-label']); ?>

                                <small data-bs-toggle="tooltip" title="Extra PDF">(Extra PDF)</small>
                                <?php echo Form::file('other_pdf', ['class' => 'form-control']); ?>

                            </div>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                        <?php
                            $subcategoryId = \App\Models\Course::where('id', $course_id)->value('sub_category_id');
                        ?>
                        <div class="col-md-12 col-sm-12 col-xs-12 input-div">
                            <div class="row">
                                <div class="col-md-1">
                                    <?php echo Form::label('language[0]', 'Language', ['class' => 'form-label']); ?>

                                    <?php echo Form::select('language[0]', config('constants.CONTENT_LANGUAGE'), null, [
                                        'class' => 'form-select',
                                        'placeholder' => '--Select Content Language--',
                                    ]); ?>

                                </div>
                                <div class="col-md-1">
                                    <?php echo Form::label('video_sort_order', 'Order', ['class' => 'form-label']); ?>

                                    <input type="number" name="video_sort_order[0]" class="form-control" placeholder=""
                                        min="1" />
                                </div>
                                <div class="col-md-2">
                                    <?php echo Form::label('video_view_type[0]', 'Video Type', ['class' => 'form-label']); ?>

                                    <?php echo Form::select('video_view_type[0]', config('constants.VIDEO_VIEW_TYPE'), null, [
                                        'class' => 'form-select',
                                        'placeholder' => '--Select Video Type--',
                                    ]); ?>

                                </div>
                                <div class="col-md-3">
                                    <?php echo Form::label('chapter_file', 'Digital Content File Name', ['class' => 'form-label']); ?>

                                    <input type="text" name="file_name[0]" class="form-control"
                                        placeholder="Enter file name" />
                                </div>
                                <div class="col-md-3">
                                    <?php echo Form::label('chapter_file', 'Choose Digital Content File', ['class' => 'form-label']); ?>

                                    <input type="file" name="chapter_file[0]" class="form-control video-input"
                                        data-index="0" <?php if($subcategoryId != 37): ?> required <?php endif; ?> />
                                    <input type="hidden" name="video_duration[0]" id="video-duration-0" />
                                </div>
                                <div class="col-md-2 col-sm-2 col-xs-12 input-div mt-4">
                                    <button type="button" id="add-more" class="btn btn-success"
                                        style="margin-top: 7px;">Add
                                        More Files</button>
                                </div>
                            </div>
                        </div>


                        <span class="add-more-cols"></span>

                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($subcategoryId == 37): ?>
                            <hr class="form-divider">
                            <div class="col-md-12 col-sm-12 col-xs-12 input-div" id="link-inputs-container">
                                <div class="row" id="link-group-0">
                                    <div class="col-md-1">
                                        <?php echo Form::label('link_sort_order', 'Order', ['class' => 'form-label']); ?>

                                        <input type="number" name="link_sort_order[0]" class="form-control"
                                            placeholder="" min="1" />
                                    </div>
                                    <div class="col-md-5">
                                        <?php echo Form::label('link_name', 'Activity Name', ['class' => 'form-label']); ?>

                                        <input type="text" name="link_name[0]" class="form-control"
                                            placeholder="Enter url name" />
                                    </div>
                                    <div class="col-md-6">
                                        <?php echo Form::label('link_link_url', 'Activity URL', ['class' => 'form-label']); ?>

                                        <input type="text" name="link_url[0]" class="form-control"
                                            placeholder="Paste your url here" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2 col-sm-2 col-xs-12 input-div mt-4">
                                <button type="button" id="add-more-links" class="btn btn-success"
                                    style="margin-top: 7px;">Add More Links</button>
                            </div>

                            <span class="add-more-links-cols"></span>
                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="reset" class="btn btn-secondary"
                                onclick="window.location.reload();">Reset</button>
                        </div>

                        <?php echo Form::close(); ?>


                    </div>
                </div>
            </div>
        </div>
    </div>
    </section>
    </div>
    <div class="modal fade" id="createFolder">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="">
                        <h5 class="modal-title" id="exampleModalToggleLabel">Create New Folder</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo e(route('create.folder')); ?>" method="POST">
                        <?php echo csrf_field(); ?> <!-- Include CSRF token for security -->
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <label class="form-label" for="folder_name">Enter Folder Name</label>
                                    <input type="text" class="form-control" id="folder_name" name="folder_name"
                                        placeholder="Enter here" required>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 mt-3">
                                    <label class="form-label" for="folder_color">Select Folder Color</label>
                                    <input type="color" class="form-control" id="folder_color" value="#DBF8EA"
                                        name="folder_color" required>
                                </div>
                            </div>
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
        // Build language select HTML from config options
        function getLanguageOptionsHtml(index) {
            let options = `<option value="">--Select Content Language--</option>`;
            for (const [key, value] of Object.entries(window.CONTENT_LANGUAGE_OPTIONS)) {
                options += `<option value="${key}">${value}</option>`;
            }
            return `
        <select name="language[${index}]" class="form-select">
            ${options}
        </select>
    `;
        }
        window.CONTENT_LANGUAGE_OPTIONS = <?php echo json_encode(config('constants.CONTENT_LANGUAGE'), 15, 512) ?>;

        function getVideoViewTypeOptionsHtml(index) {
            let options = `<option value="">--Select Video Type--</option>`;
            for (const [key, value] of Object.entries(window.VIDEO_VIEW_TYPE_OPTIONS)) {
                options += `<option value="${key}">${value}</option>`;
            }
            return `
        <select name="video_view_type[${index}]" class="form-select">
            ${options}
        </select>
    `;
        }
        window.VIDEO_VIEW_TYPE_OPTIONS = <?php echo json_encode(config('constants.VIDEO_VIEW_TYPE'), 15, 512) ?>;

        document.addEventListener('DOMContentLoaded', function() {
            let fileIndex = 0;

            // Add More functionality
            document.getElementById('add-more').addEventListener('click', function() {
                fileIndex++;

                // Create a new file input group with a unique index
                const fileGroup = `
                <div class="row" id="file-group-${fileIndex}">
                    <div class="col-md-12 col-sm-12 col-xs-12 input-div">
                        <div class="row">
                            <div class="col-md-1">
                                <label class="form-label">Language</label>
                                ${getLanguageOptionsHtml(fileIndex)}
                            </div>
                            <div class="col-md-1">
                                <label class="form-label">Order</label>
                                <input type="number" name="video_sort_order[${fileIndex}]" class="form-control" placeholder="" min="1" />
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Video Type</label>
                                ${getVideoViewTypeOptionsHtml(fileIndex)}
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Digital Content File Name</label>
                                <input type="text" name="file_name[${fileIndex}]" class="form-control" placeholder="Enter file name" />
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Choose Digital Content File</label>
                                <input type="file" name="chapter_file[${fileIndex}]" class="form-control video-input" data-index="${fileIndex}" required />
                                <input type="hidden" name="video_duration[${fileIndex}]" id="video-duration-${fileIndex}" />
                            </div>
                             <div class="col-md-2 d-flex align-items-end input-div" style="margin-top: 30px !important;">
                                <button type="button" class="btn btn-danger btn-sm remove-file" data-index="${fileIndex}">Remove</button>
                            </div>
                        </div>
                    </div>
                   
                </div>
                `;


                // Append the new file input group
                document.querySelector('.add-more-cols').insertAdjacentHTML('beforeend', fileGroup);
            });

            document.querySelector('.add-more-cols').addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-file')) {
                    const index = e.target.getAttribute('data-index');
                    document.getElementById(`file-group-${index}`).remove();
                }
            });

            // Handle file input changes to get video duration
            document.addEventListener('change', function(e) {
                if (e.target.classList.contains('video-input')) {
                    const file = e.target.files[0];
                    const index = e.target.getAttribute('data-index');
                    const durationInput = document.getElementById(`video-duration-${index}`);

                    if (file) {
                        if (file.type.startsWith('video/')) {
                            const video = document.createElement('video');
                            video.preload = 'metadata';

                            video.onloadedmetadata = function() {
                                window.URL.revokeObjectURL(video.src);
                                const duration = Math.floor(video.duration);
                                durationInput.value = duration;
                            };

                            video.src = URL.createObjectURL(file);
                        } else {
                            durationInput.value = '';
                        }
                    } else {
                        durationInput.value = '';
                    }
                }
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let linkIndex = 0;

            // Add More Links functionality
            document.getElementById('add-more-links').addEventListener('click', function() {
                linkIndex++;

                const linkGroup = `
            <div class="row " id="link-group-${linkIndex}">
                <div class="col-md-12 col-sm-12 col-xs-12 input-div">
                    <div class="row">
                        <div class="col-md-1">
                            <input type="number" name="link_sort_order[${linkIndex}]" class="form-control" placeholder="" min="1" />
                        </div>
                        <div class="col-md-5">
                            <input type="text" name="link_name[${linkIndex}]" class="form-control" placeholder="Enter url name" />
                        </div>
                        <div class="col-md-6">
                            <input type="text" name="link_url[${linkIndex}]" class="form-control" placeholder="Paste your url here" />
                        </div>
                          <div class="col-md-2 d-flex align-items-centee" style="margin-top: 30px !important;">
                            <button type="button" class="btn btn-danger btn-sm remove-link" data-index="${linkIndex}">Remove</button>
                          </div>
                    </div>
                </div>
              
            </div>
        `;

                document.querySelector('.add-more-links-cols').insertAdjacentHTML('beforeend', linkGroup);
            });

            // Remove Link group
            document.querySelector('.add-more-links-cols').addEventListener('click', function(e) {
                if (e.target.classList.contains('remove-link')) {
                    const index = e.target.getAttribute('data-index');
                    document.getElementById(`link-group-${index}`).remove();
                }
            });
        });
    </script>


    <script>
        function scrollToAddNewRow() {
            const element = document.getElementById('add-new-row');
            element.scrollIntoView({
                behavior: 'smooth'
            });
        }
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/admin/courses/add_edit_chapter.blade.php ENDPATH**/ ?>