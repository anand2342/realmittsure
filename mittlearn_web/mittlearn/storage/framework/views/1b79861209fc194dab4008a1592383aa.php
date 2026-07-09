

<?php $__env->startSection('content'); ?>
    <style>
        .chip {
            display: inline-block;
            padding: 6px 6px;
            ;
            background-color: #f0f0f0;
            border-radius: 8px;
            font-size: 10px;
            color: #4B4B4B;
            cursor: pointer;
            border: 1px solid #4B4B4B;
        }
    </style>
    <div class="pagetitle">
        <h1>Content Deck</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item active">Content Deck Folders</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <h5 class="card-title mb-0">All Content Deck Folders</h5>

                            <div class="d-flex align-items-center gap-2">
                                <label for="paginationSelectOnpage" class="mb-0">Per Page Records:</label>
                                <select id="paginationSelectOnpage" class="form-select form-select-sm" style="width: 80px;">
                                    <option value="" disabled <?php echo e(session('per_page_records') ? '' : 'selected'); ?>>
                                        --Select--
                                    </option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = [10, 20, 30, 40, 50]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($option); ?>"
                                            <?php echo e(session('per_page_records') == $option ? 'selected' : ''); ?>>
                                            <?php echo e($option); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </select>

                                
                                <a href="javascript:void(0)" class="btn btn-success btn-sm" data-bs-toggle="tooltip"
                                    data-bs-placement="bottom" data-bs-title="Create Folder">
                                    <span data-bs-target="#createFolder" data-bs-toggle="modal">Create New Folder</span>
                                </a>
                                
                            </div>
                        </div>

                        <hr class="form-divider">
                        <div class="table-responsive tbleDiv ">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th><b>Class Name</b></th>
                                        <th><b>Folder Name</b></th>
                                        <th><b>Distributed Series</b></th>
                                        <th><b>Distributed Role</b></th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $folderListing; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <?php
                                                $className = \App\Models\Classes::where('id', $item->class_id)->value('name')
                                            ?>
                                            <td><?php echo e($folderListing->currentPage() * $folderListing->perPage() - $folderListing->perPage() + $loop->iteration . '.'); ?>

                                            </td>
                                            <td><?php echo e($className ?? '-'); ?></td>
                                            <td><?php echo e($item->folder_name); ?></td>
                                            <td>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $item->series_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $series): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <span class="chip"><?php echo e($series->name); ?></span>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </td>
                                            <td>


                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $item->role_list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <span class="chip"><?php echo e($role->role_name); ?></span>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                            </td>
                                            <td>
                                                <a class="btn btn-sm btn-info me-1"
                                                    href="<?php echo e(route('media.gallery.folder.view', $item->id)); ?>"><i
                                                        class="fa fa-eye"></i></a>
                                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('isPermission', 'media.gallery.delete')): ?>
                                                    <a class="btn btn-danger btn-sm me-2" href="javascript:void(0);"
                                                        onclick="confirmDelete('<?php echo e(route('media.gallery.delete', $item->id)); ?>')">
                                                        <i class="fa fa-trash"></i></a>
                                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                                
                                                
                                                <a class="btn btn-success btn-sm me-2" href="javascript:void(0)"
                                                    data-bs-toggle="modal" data-bs-target="#distributeFolderModal"
                                                    data-id="<?php echo e($item->id); ?>">
                                                    Distribute Folder
                                                </a>
                                                

                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-right text-right">
                            <?php echo $folderListing->links('pagination::bootstrap-4'); ?>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="createFolder" data-bs-backdrop="static" data-bs-keyboard="false">
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
                                    <label for="class_id" class="form-label">Select Class</label>
                                    <select name="class_id" class="form-select">
                                        <option disabled selected>-- Select --</option>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $classList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($id); ?>"><?php echo e($name); ?></option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    </select>
                                </div>
                                <div class="col-md-12 col-sm-12 col-xs-12 mt-2">
                                    <label class="form-label required" for="folder_name">Enter Folder Name</label>
                                    <input type="text" class="form-control" id="folder_name" name="folder_name"
                                        placeholder="Enter here" required>
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

    
    <div class="modal fade" id="distributeFolderModal" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="">
                        <h5 class="modal-title" id="exampleModalToggleLabel">Distribute Folder to Series or Role</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="<?php echo e(route('media.gallery.distribute')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="media_id" id="media_id">

                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="series" class="form-label required">Select Series</label>
                                <select name="series[]" id="series" class="form-select js-select2" multiple>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $seriesList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($id); ?>"><?php echo e($name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="roles" class="form-label required">Select Roles</label>
                                <select name="roles[]" id="roles" class="form-select js-select2" multiple>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $roleList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slug => $role_name): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($slug); ?>"><?php echo e($role_name); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </select>
                            </div>

                            <div id="schoolDropdownContainer" class="mb-3" style="display:none;"></div>
                            <div id="teacherDropdownContainer" class="mb-3" style="display:none;"></div>
                        </div>
                        <div class="d-flex justify-content-center mt-3">
                            <button type="submit" class="btn btn-primary me-2">Submit</button>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            function refreshRoleDropdowns() {
                let selectedRoles = $('#roles').val();

                // Only reset containers if the role itself was deselected
                if (!selectedRoles || !selectedRoles.includes("school_admin")) {
                    $("#schoolDropdownContainer").hide().html("");
                }
                if (!selectedRoles || !selectedRoles.includes("school_teacher")) {
                    $("#teacherDropdownContainer").hide().html("");
                }

                if (!selectedRoles || selectedRoles.length === 0) return;

                // Only load if container is empty (avoid reloading on series change if already loaded)
                if (selectedRoles.includes("school_admin") && $("#schoolDropdownContainer").is(':empty')) {
                    loadRoleData("school_admin", "#schoolDropdownContainer");
                }

                if (selectedRoles.includes("school_teacher") && $("#teacherDropdownContainer").is(':empty')) {
                    loadRoleData("school_teacher", "#teacherDropdownContainer");
                }
            }


            $('#roles').on('change', function() {
                refreshRoleDropdowns();
            });

            // $('#series').on('change', function() {
            //     refreshRoleDropdowns();
            // });
            $('#series').on('change', function() {
                // On series change, clear and reload both active role dropdowns
                $("#schoolDropdownContainer").html("");
                $("#teacherDropdownContainer").html("");
                refreshRoleDropdowns();
            });

            $('#roles').on('change', function() {
                refreshRoleDropdowns();
            });

            // function loadRoleData(roleType, container) {

            //     $.ajax({
            //         url: "<?php echo e(route('get.user.to.assign.deck')); ?>",
            //         type: "GET",
            //         data: {
            //             role: roleType,
            //             series: $('#series').val()
            //         },
            //         success: function(response) {

            //             let data = response.data.data;

            //             let label = (roleType === "school_admin") ?
            //                 "Select Admin" :
            //                 "Select Teacher";

            //             let html = `
        //             <label class="form-label">${label}</label>
        //             <select name="${roleType}_list[]" class="form-select js-select2" multiple>
        //                 ${data.map(item =>
        //                     `<option value="${item.id}">${item.name}</option>`
        //                 ).join('')}
        //             </select>
        //         `;

            //             $(container).html(html).show();

            //             $('.js-select2').select2({
            //                 width: '100%',
            //                 placeholder: "--Select--"
            //             });
            //         }
            //     });
            // }

            function loadRoleData(roleType, container) {
                $.ajax({
                    url: "<?php echo e(route('get.user.to.assign.deck')); ?>",
                    type: "GET",
                    data: {
                        role: roleType,
                        series: $('#series').val()
                    },
                    success: function(response) {
                        let data = response.data.data;
                        let label = (roleType === "school_admin") ? "Select School Admin" :
                            "Select Teacher";
                        let name = (roleType === "school_admin") ? "school_admin_list[]" :
                            "school_teacher_list[]";

                        let html = `
                <label class="form-label">${label}</label>
                <select name="${name}" class="form-select" multiple>
                    <option value="all">-- All --</option>
                    ${data.map(item => `<option value="${item.id}">${item.name}</option>`).join('')}
                </select>
            `;

                        $(container).html(html).show();

                        // Init select2 strictly scoped to THIS container only
                        let $select = $(container).find('select');
                        $select.select2({
                            width: '100%',
                            placeholder: "--Select--",
                            closeOnSelect: false
                        });

                        // "All" handler — scoped strictly to this select only
                        $select.on('change', function() {
                            let selected = $(this).val() || [];

                            if (selected.includes('all')) {
                                // Grab all real IDs from THIS select only
                                let allIds = $(this).find('option')
                                    .map(function() {
                                        return $(this).val();
                                    })
                                    .get()
                                    .filter(v => v !== 'all');

                                // Set only real IDs, remove "all" sentinel from submission
                                $(this).val(allIds).trigger('change.select2');
                            }
                        });
                    }
                });
            }

        });
    </script>




    <script>
        $(document).ready(function() {
            $(".js-select2").select2({
                closeOnSelect: false,
                placeholder: "Select",
                allowClear: false,
                tags: true
            });
        });

        const distributeModal = document.getElementById('distributeFolderModal');
        distributeModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const mediaId = button.getAttribute('data-id');
            document.getElementById('media_id').value = mediaId;
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/admin/mediaGallery/index.blade.php ENDPATH**/ ?>