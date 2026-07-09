  <div class="table-responsive tbleDiv">
      <table id="datatable-books" class="table table-striped table-bordered">
          <thead>
              <tr>
                  <th>S.No.</th>
                  <th>Board</th>
                  <th>Medium</th>
                  <th>Book Series</th>
                  <th>Class</th>
                  <th>Subject</th>
                  <th>Book Name</th>
                  <th>Action</th>
              </tr>
          </thead>
          <tbody>
              <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $courses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $course): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                  <?php
                      $board = $course->metadataValues->where('field_name', 'board')->pluck('field_value')->first();
                      $medium = $course->metadataValues->where('field_name', 'medium')->pluck('field_value')->first();
                      $class = $course->metadataValues->where('field_name', 'class')->pluck('field_value')->first();
                      $series = $course->metadataValues->where('field_name', 'series')->pluck('field_value')->first();
                      $subject = $course->metadataValues->where('field_name', 'subject')->pluck('field_value')->first();
                      $boardName = App\Models\Board::where('id', $board)->pluck('name')->first();
                      $mediumName = App\Models\Medium::where('id', $medium)->pluck('name')->first();
                      $seriesName = App\Models\BookSeries::where('id', $series)->pluck('name')->first();
                      $className = App\Models\Classes::where('id', $class)->pluck('name')->first();
                      $subjectName = App\Models\Subject::where('id', $subject)->pluck('name')->first();
                  ?>
                  <tr>
                      <td><?php echo e($courses->firstItem() + $loop->index); ?>.</td>
                      <td><?php echo e($boardName ?? '-'); ?></td>
                      <td><?php echo e($mediumName ?? '-'); ?></td>
                      <td><?php echo e($seriesName ?? '-'); ?></td>
                      <td><?php echo e($className ?? '-'); ?></td>
                      <td><?php echo e($subjectName ?? 'Talent-Skills'); ?></td>
                      <td><span><?php echo e($course->course_name); ?></span></td>
                      <td>
                          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('isPermission', 'course.edit')): ?>
                              <a class="btn btn-warning btn-sm me-2" href="<?php echo e(route('course.edit', $course->id)); ?>"
                                  title="Edit">
                                  <i class="fa fa-pencil"></i>
                              </a>
                              <a class="btn btn-info btn-sm me-2" href="<?php echo e(route('about-acadcourse', $course->slug)); ?>"
                                  title="View On Page" target="_blank">
                                  <i class="fa fa-eye"></i>
                              </a>
                          <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('isPermission', 'course.activate')): ?>
                              <a class="btn btn-sm statusBtn <?php echo e($course->is_active ? 'btn-success' : 'btn-danger'); ?>"
                                  href="javascript:void(0);"
                                  onclick="confirmStatus('<?php echo e(route('course.activate', $course->id)); ?>', <?php echo e($course->is_active); ?>)">
                                  <?php echo e($course->is_active ? 'Active' : 'Inactive'); ?>

                              </a>
                          <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                          <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if (\Illuminate\Support\Facades\Blade::check('isPermission', 'course.add.chapter')): ?>
                              <a class="btn btn-primary btn-sm ms-1" href="<?php echo e(route('course.add.chapter', $course->id)); ?>"
                                  title="Manage Chapters">
                                  Manage Chapters
                              </a>
                          <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                      </td>
                  </tr>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
          </tbody>
      </table>
  </div>

  <!-- Pagination info and pagination links -->
  <div class="d-flex justify-content-between">
      <div>
          <!-- Display current page info -->
          <span>Showing <?php echo e($courses->firstItem()); ?> to
              <?php echo e($courses->lastItem()); ?> of <?php echo e($courses->total()); ?>

              entries</span>
      </div>
      <div class="d-flex justify-content-end">
          <!-- Display pagination links -->
          <?php echo $courses->appends(array_merge(request()->query()))->links('pagination::bootstrap-4'); ?>

      </div>
  </div>
<?php /**PATH C:\Users\Mittsure\Desktop\mittlearn_web1\mittlearn_web\mittlearn\resources\views/admin/courses/index-academic.blade.php ENDPATH**/ ?>