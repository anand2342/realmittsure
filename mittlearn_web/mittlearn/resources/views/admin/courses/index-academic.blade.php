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
              @foreach ($courses as $course)
                  @php
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
                  @endphp
                  <tr>
                      <td>{{ $courses->firstItem() + $loop->index }}.</td>
                      <td>{{ $boardName ?? '-' }}</td>
                      <td>{{ $mediumName ?? '-' }}</td>
                      <td>{{ $seriesName ?? '-' }}</td>
                      <td>{{ $className ?? '-' }}</td>
                      <td>{{ $subjectName ?? 'Talent-Skills' }}</td>
                      <td><span>{{ $course->course_name }}</span></td>
                      <td>
                          @isPermission('course.edit')
                              <a class="btn btn-warning btn-sm me-2" href="{{ route('course.edit', $course->id) }}"
                                  title="Edit">
                                  <i class="fa fa-pencil"></i>
                              </a>
                              <a class="btn btn-info btn-sm me-2" href="{{ route('about-acadcourse', $course->slug) }}"
                                  title="View On Page" target="_blank">
                                  <i class="fa fa-eye"></i>
                              </a>
                          @endisPermission
                          @isPermission('course.activate')
                              <a class="btn btn-sm statusBtn {{ $course->is_active ? 'btn-success' : 'btn-danger' }}"
                                  href="javascript:void(0);"
                                  onclick="confirmStatus('{{ route('course.activate', $course->id) }}', {{ $course->is_active }})">
                                  {{ $course->is_active ? 'Active' : 'Inactive' }}
                              </a>
                          @endisPermission
                          @isPermission('course.add.chapter')
                              <a class="btn btn-primary btn-sm ms-1" href="{{ route('course.add.chapter', $course->id) }}"
                                  title="Manage Chapters">
                                  Manage Chapters
                              </a>
                          @endisPermission
                      </td>
                  </tr>
              @endforeach
          </tbody>
      </table>
  </div>

  <!-- Pagination info and pagination links -->
  <div class="d-flex justify-content-between">
      <div>
          <!-- Display current page info -->
          <span>Showing {{ $courses->firstItem() }} to
              {{ $courses->lastItem() }} of {{ $courses->total() }}
              entries</span>
      </div>
      <div class="d-flex justify-content-end">
          <!-- Display pagination links -->
          {!! $courses->appends(array_merge(request()->query()))->links('pagination::bootstrap-4') !!}
      </div>
  </div>
