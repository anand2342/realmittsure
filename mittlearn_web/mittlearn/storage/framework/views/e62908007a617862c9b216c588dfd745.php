<?php $__env->startSection('content'); ?>
<?php echo $__env->make('admin.layouts.flash-messages', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php
        $flag = 0;
        $heading = 'Add Student';
        if (isset($data) && !empty($data)) {
            $flag = 1;
            $heading = 'View/Edit Student Details';
        }
    ?>
    <div class="cardBox teacherMain py-md-4  mb-3">
        <div class="row">
            <div class="col-md-8">
                <div class="teacherLeft">
                    <h5 class="fw-semibold">Login Access Details</h5>
                    <p> Below are the login credentials and access details for the users. Only school administrators
                        can view this information to manage user access securely.
                    </p>
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($userType == 'teachers'): ?>
                        <a href="<?php echo e(route('sp.teacher.manager')); ?>"
                            class="btn btn-primary-gradient rounded-1 addBtn ">Back</a>
                    <?php else: ?>
                        <a href="<?php echo e(route('sp.student.manager')); ?>"
                            class="btn btn-primary-gradient rounded-1 addBtn ">Back</a>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </div>
            </div>
            <div class="col-md-4">
                <div class="teacherRighr position-relative">
                    <img src="<?php echo e(asset('frontend/images/teacher-manager-img.svg')); ?>" alt="" class="teacherImg">
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 mb-3">
            <div class="teacherTable">
                <div class="headerTbl">
                    <h6 class="m-0">All Users</h6>
                    <div class="teacherrightTable">
                        <div class="tableSearch">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search by Name">
                        </div>
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($userType == 'students'): ?>
                        <div class="col">
                            <form method="GET" action="<?php echo e(url()->current()); ?>">
                                
                                <select name="class" class="form-select" onchange="this.form.submit()">
                                    <option value="">Search by Class</option>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $id => $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($id); ?>" <?php echo e($id == request('class') ? 'selected' : ''); ?>>
                                            <?php echo e($class); ?>

                                        </option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                </select>
                            </form>
                        </div>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    

                        <div class="dropdown">
                            <button class="bg-transparent border-0 p-0" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <span data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Sort">
                                    <img src="<?php echo e(asset('frontend/images/sort-icon.svg')); ?>" alt="">
                                </span>
                            </button>

                            <ul class="dropdown-menu" id="sortDropdown">
                                <li><a class="dropdown-item" href="#" id="sortAsc">Sort A to Z</a></li>
                                <li><a class="dropdown-item" href="#" id="sortDesc">Sort Z to A</a></li>
                            </ul>
                        </div>
                        <div class="dropdown">
                            <button class="bg-transparent border-0 p-0" type="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                <span data-bs-toggle="tooltip" data-bs-placement="bottom" data-bs-title="Filter">
                                    <img src="<?php echo e(asset('frontend/images/filter-icon.svg')); ?>" alt="">
                                </span>
                            </button>
                            <ul class="dropdown-menu">
                                
                                <li><a class="dropdown-item" href="#" id="active">Active Students</a></li>
                                <li><a class="dropdown-item" href="#" id="inactive">Inactive Students</a>
                                <li><a class="dropdown-item" href="<?php echo e(route('sp.student.manager')); ?>">All Students</a>
                                </li>
                            </ul>
                        </div>
                        <a href="<?php echo e(route('login.access.details.export', $userType)); ?>" class="bg-transparent border-0 p-0">
                            <button class="bg-transparent border-0 p-0" type="button">
                                <span>
                                    <img src="<?php echo e(asset('frontend/images/download-icon.svg')); ?>" alt="Download"
                                        title="Download">
                                </span>
                            </button>
                        </a>

                    </div>
                </div>
                <div class="px-3 py-2">
                    <div class="table-responsive tbleDiv">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($userType == 'students'): ?>
                                        <th>Class</th>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                    <th>Login Email</th>
                                    <th>Login Mob. No.</th>
                                    <th>Password</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody id="studentTableBody">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($user->name ?? ''); ?> </td>
                                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($userType == 'students'): ?>
                                            <td><?php echo e(App\Models\SchoolClass::where('id', $user->studentDetails->class)->value('name')); ?>

                                            </td>
                                        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                        <td><?php echo e($user->email ?? ''); ?> </td>
                                        <td><?php echo e($user->mobile_no ?? ''); ?></td>
                                        <td><?php echo e($user->validate_string ?? ''); ?></td>
                                        <td>
                                            <span class="<?php echo e($user->status == 1 ? 'activeTxt' : 'deactiveTxt'); ?>">
                                                <?php echo e($user->status == 1 ? 'Active' : 'Inactive'); ?>

                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="customPagination mt-4">
                        <ul class="pagination">
                            <li class="page-item <?php echo e($users->onFirstPage() ? 'disabled' : ''); ?> previous-item">
                                <a class="page-link" href="<?php echo e($users->previousPageUrl()); ?>">
                                    <span><img src="<?php echo e(asset('frontend/images/arrowprw.svg')); ?>" width="6"></span>
                                </a>
                            </li>

                            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $users->getUrlRange(1, $users->lastPage()); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $page => $url): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="page-item <?php echo e($page == $users->currentPage() ? 'active' : ''); ?>">
                                    <a class="page-link" href="<?php echo e($url); ?>"><?php echo e($page); ?></a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

                            <li class="page-item <?php echo e($users->hasMorePages() ? '' : 'disabled'); ?> next-item">
                                <a class="page-link" href="<?php echo e($users->nextPageUrl()); ?>">
                                    <span><img src="<?php echo e(asset('frontend/images/arrownxt.svg')); ?>" width="6"></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function filterStudents(status) {
            const url = new URL(window.location.href);

            url.searchParams.set('status', status);
            window.location.href = url.toString();
        }

        document.addEventListener("DOMContentLoaded", function() {
            const searchInput = document.getElementById('searchInput');

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    const query = this.value.toLowerCase();

                    tableRows.forEach(row => {
                        const title = row.getAttribute('data-name').toLowerCase();

                        if (title.includes(query)) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            }


            const urlParams = new URLSearchParams(window.location.search);
            const status = urlParams.get("status");

            if (status === 1) {
                document.getElementById("active").classList.add('active');
            } else if (status === 0) {
                document.getElementById("inactive").classList.add('active');
            }

            document.getElementById('active').addEventListener('click', function() {
                filterStudents(1);
            });

            document.getElementById('inactive').addEventListener('click', function() {
                filterStudents(0);
            });
        });
    </script>
    <script>
        function sortUsers(order) {
            const url = new URL(window.location.href);
            url.searchParams.set('sort', order);
            window.location.href = url.toString();
        }

        document.addEventListener("DOMContentLoaded", function() {
            const urlParams = new URLSearchParams(window.location.search);
            const sortOrder = urlParams.get("sort");

            if (sortOrder === 'asc') {
                document.getElementById("sortAsc").classList.add('active');
            } else if (sortOrder === 'desc') {
                document.getElementById("sortDesc").classList.add('active');
            }

            document.getElementById('sortAsc').addEventListener('click', function(event) {
                event.preventDefault();
                sortUsers('asc');
            });

            document.getElementById('sortDesc').addEventListener('click', function(event) {
                event.preventDefault();
                sortUsers('desc');
            });
        });

    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('schoolPortal.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/schoolPortal/user/login-aceess.blade.php ENDPATH**/ ?>