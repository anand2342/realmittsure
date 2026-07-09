@extends('admin.layouts.master')
@section('content')
    @php
        $user = auth()->user();
        $canEdit = $ticket->canUserEdit($user);
        $canManage = in_array(getUserRoles(), ['admin', 'qd_developer', 'super_admin']);
        $isDeveloper = getUserRoles() === 'qd_developer';
        $canReopen =
            in_array(getUserRoles(), ['admin', 'qd_developer', 'super_admin']) &&
            in_array($ticket->status, ['resolved', 'closed']);
    @endphp
    <div class="pagetitle">
        <h1><b>View {{ ucfirst($ticket->ticket_id) }}</b></h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('tickets.index') }}">Tickets</a></li>
                <li class="breadcrumb-item active">Details</li>
            </ol>
        </nav>
    </div>
    <section class="section">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-white border-bottom">
                        <div class="d-flex align-items-center">
                            <i class="fa fa-ticket-alt text-primary me-2"></i>
                            <h5 class="card-title mb-0 fw-bold">Ticket Overview</h5>
                        </div>
                    </div>

                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-0">
                                <tbody>
                                    <tr>
                                        <th class="bg-light w-25">Module/Section</th>
                                        <td>{!! $ticket->module !!}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Issue Description</th>
                                        <td>{!! $ticket->issue !!}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Logged By User</th>
                                        <td>{{ $ticket->logged_by_user }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Priority</th>
                                        <td>
                                            @php
                                                $priorityColors = [
                                                    'low' => 'bg-success text-white',
                                                    'medium' => 'bg-warning text-dark',
                                                    'high' => 'bg-danger text-white',
                                                ];
                                                $priority = strtolower($ticket->priority);
                                                $priorityClass =
                                                    $priorityColors[$priority] ?? 'bg-secondary text-white';
                                            @endphp
                                            <span class="badge {{ $priorityClass }} px-3 py-2">
                                                {{ ucfirst($ticket->priority) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Status</th>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'open' => 'bg-success text-white',
                                                    'pending' => 'bg-warning text-dark',
                                                    'closed' => 'bg-danger text-white',
                                                    'in_progress' => 'bg-info text-dark',
                                                ];
                                                $status = strtolower($ticket->status);
                                                $statusClass = $statusColors[$status] ?? 'bg-secondary text-white';
                                            @endphp
                                            <span class="badge {{ $statusClass }} px-3 py-2">
                                                {{ str_replace('_', ' ', ucfirst($ticket->status)) }}
                                            </span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Created By</th>
                                        <td>{{ $ticket->user->name ?? 'N/A' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Assigned To</th>
                                        <td>{{ $ticket->assignee->name ?? 'Unassigned' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Created At</th>
                                        <td>{{ $ticket->created_at->format('d M Y, h:i A') }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light"><span class="color: #ff7c00">QD </span> Developer Remarks</th>
                                        <td class="bg-light">{{ $ticket->remarks_qd ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Additional Remarks</th>
                                        <td>{{ $ticket->further_remarks ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Last Updated</th>
                                        <td>{{ $ticket->updated_at->format('d M Y, h:i A') }}</td>
                                    </tr>
                                    <tr>
                                        <th class="bg-light">Attached Artifacts</th>
                                        <td>
                                            @php
                                                $files = $ticket->screenshot_path
                                                    ? explode(',', $ticket->screenshot_path)
                                                    : [];
                                            @endphp

                                            @if (count($files))
                                                @foreach ($files as $index => $filePath)
                                                    @php
                                                        $extension = strtolower(
                                                            pathinfo($filePath, PATHINFO_EXTENSION),
                                                        );
                                                        $fileUrl = Storage::url('uploads/tickets/' . $filePath);
                                                    @endphp

                                                    @if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                                        <!-- Image -->
                                                        <div class="mb-2 d-inline-block me-2">
                                                            <img src="{{ $fileUrl }}" alt="Image" width="200"
                                                                height="100" class="rounded shadow-sm"
                                                                style="cursor: pointer;" data-bs-toggle="modal"
                                                                data-bs-target="#screenshotModal{{ $ticket->id }}_{{ $index }}">
                                                            <div class="text-info small mt-1">
                                                                <a href="{{ $fileUrl }}" target="_blank">
                                                                    Click to view full screenshot
                                                                </a>
                                                            </div>
                                                        </div>

                                                        <!-- Modal for full image -->
                                                        <div class="modal fade"
                                                            id="screenshotModal{{ $ticket->id }}_{{ $index }}"
                                                            tabindex="-1"
                                                            aria-labelledby="screenshotModalLabel{{ $ticket->id }}_{{ $index }}"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-body text-center">
                                                                        <img src="{{ $fileUrl }}"
                                                                            class="img-fluid rounded shadow"
                                                                            alt="Full Screenshot">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @elseif (in_array($extension, ['mp4', 'webm', 'ogg']))
                                                        <!-- Video -->
                                                        <div class="mb-2">
                                                            <video width="320" height="200" controls
                                                                class="rounded shadow-sm">
                                                                <source src="{{ $fileUrl }}"
                                                                    type="video/{{ $extension }}">
                                                                Your browser does not support the video tag.
                                                            </video>
                                                        </div>
                                                    @else
                                                        <!-- Other file types -->
                                                        <div class="mb-2">
                                                            <span class="text-muted">File available:
                                                                <a href="{{ $fileUrl }}"
                                                                    target="_blank">{{ $filePath }}</a>
                                                            </span>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @else
                                                <span class="text-muted">No file uploaded</span>
                                            @endif
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-lg-6">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title pb-0">Status</h5>
                                <hr class="form-divider">
                                @if ($canEdit)
                                    <form method="POST" action="{{ route('tickets.status.update', $ticket) }}">
                                        @csrf
                                        @method('PATCH')
                                        <div class="row g-2">

                                            <div class="col-4">
                                                <label class="form-label">Update Status</label>
                                                <select name="status" class="form-control" required>
                                                    <option value="open"
                                                        {{ $ticket->status === 'open' ? 'selected' : '' }}>
                                                        Open
                                                    </option>
                                                    <option value="in_progress"
                                                        {{ $ticket->status === 'in_progress' ? 'selected' : '' }}>In
                                                        Progress
                                                    </option>
                                                    <option value="resolved"
                                                        {{ $ticket->status === 'resolved' ? 'selected' : '' }}>
                                                        Resolved</option>
                                                    <option value="closed"
                                                        {{ $ticket->status === 'closed' ? 'selected' : '' }}>
                                                        Closed</option>
                                                </select>
                                            </div>
                                            <div class="col-4">
                                                <label class="form-label">Date</label>
                                                <input type="date" name="logged_date" rows="2" class="form-control"
                                                    required />
                                            </div>
                                            <div class="col-4">
                                                <label class="form-label">Work duration (hours)</label>
                                                <input type="number" step="0.1" min="0.1" max="24"
                                                    name="work_duration" class="form-control" placeholder="e.g. 1.5"
                                                    @if ($isDeveloper) required @endif>
                                            </div>
                                        </div>

                                        <div class="row g-2 mt-2">
                                            <div class="col-12">
                                                <label class="form-label">Work description</label>
                                                {{-- Quill toolbar + editor --}}
                                                <div id="closure-quill-editor" style="height: 120px; background:#fff;">
                                                </div>
                                                <input type="hidden" name="closure_description"
                                                    id="closure-description-input">
                                            </div>

                                        </div>
                                        <div class="text-end mt-3">
                                            <button class="btn btn-primary">Update Status</button>
                                        </div>
                                    </form>
                                @else
                                    <div class="alert alert-info mb-0">You don't have permission to update status.</div>
                                @endif

                                @if ($canReopen)
                                    <hr class="form-divider">
                                    <h6 class="mb-2">Reopen Ticket</h6>
                                    <form method="POST" action="{{ route('tickets.reopen', $ticket) }}" class="mt-1">
                                        @csrf
                                        @method('PATCH')
                                        <div class="mb-2">
                                            <label class="form-label">Reason</label>
                                            <textarea name="reason" rows="2" class="form-control" required></textarea>
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label">Set status to</label>
                                            <select name="target_status" class="form-control">
                                                <option value="in_progress" selected>In Progress</option>
                                                <option value="open">Open</option>
                                            </select>
                                        </div>

                                        <div class="text-end">
                                            <button class="btn btn-warning">Reopen Ticket</button>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>

                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title pb-0">Time Logs</h5>
                                <hr class="form-divider">

                                <div class="table-responsive mt-3">
                                    <table class="table table-sm table-striped">
                                        <thead>
                                            <tr>
                                                <th>User</th>
                                                <th>Date</th>
                                                <th>Hours</th>
                                                <th>Description</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($ticket->timeLogs as $tl)
                                                <tr>
                                                    <td>{{ $tl->user->name ?? '—' }}</td>
                                                    <td>{{ optional($tl->logged_date)->format('Y-m-d') }}</td>
                                                    <td>{{ number_format($tl->hours, 2) }}</td>
                                                    <td>{!! $tl->description !!}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="4" class="text-center">No time logs yet.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-lg-6">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title pb-0">Comments</h5>
                                <hr class="form-divider">
                                <form method="POST" action="{{ route('tickets.comments.add', $ticket) }}">
                                    @csrf
                                    <div class="mb-2">
                                        <textarea name="comment" rows="3" class="form-control" placeholder="Write a comment..." required></textarea>
                                    </div>
                                    @if ($canManage)
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="checkbox" value="1"
                                                name="is_internal" id="is_internal">
                                            <label class="form-check-label" for="is_internal">
                                                Internal comment (visible to developer/admin only)
                                            </label>
                                        </div>
                                    @endif
                                    <div class="text-end">
                                        <button class="btn btn-primary">Add Comment</button>
                                    </div>
                                </form>

                                <div class="mt-3">
                                    @forelse($ticket->visible_comments as $c)
                                        <div class="border rounded p-2 mb-2">
                                            <div class="d-flex justify-content-between">
                                                <strong>{{ $c->user->name ?? '—' }}</strong>
                                                <small
                                                    class="text-muted">{{ $c->created_at->format('Y-m-d H:i') }}</small>
                                            </div>
                                            @if ($c->is_internal)
                                                <span class="badge bg-secondary me-1">Internal</span>
                                            @endif
                                            <div class="mt-1">{{ $c->comment }}</div>
                                        </div>
                                    @empty
                                        <div class="text-muted">No comments yet.</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>

                        {{-- <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title pb-0">Watchers</h5>
                                <hr class="form-divider">
                                <ul class="list-group list-group-flush">
                                    @forelse($ticket->watchers as $w)
                                        <li class="list-group-item">{{ $w->user->name ?? '—' }}</li>
                                    @empty
                                        <li class="list-group-item">No watchers added.</li>
                                    @endforelse
                                </ul>
                                @if ($canManage)
                                    <form method="POST" action="{{ route('tickets.watchers.add', $ticket) }}"
                                        class="mt-3">
                                        @csrf
                                        <div class="col-md-12 row g-2">
                                            <div class="col-8">
                                                <select name="user_id" class="form-control" required>
                                                    <option value="">-- Select user --</option>
                                                    @foreach ($users as $u)
                                                        <option value="{{ $u->id }}">{{ $u->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-4 text-end">
                                                <button class="btn btn-primary">Add Watcher</button>
                                            </div>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Only init if the editor container exists (canEdit guard)
                var editorEl = document.getElementById('closure-quill-editor');
                if (!editorEl) return;

                var quill = new Quill('#closure-quill-editor', {
                    theme: 'snow',
                    placeholder: 'Describe the work done (optional)...',
                    modules: {
                        toolbar: [
                            ['bold', 'italic', 'underline'],
                            [{
                                'list': 'ordered'
                            }, {
                                'list': 'bullet'
                            }],
                            ['clean']
                        ]
                    }
                });

                // On form submit, copy Quill HTML into the hidden input
                var form = editorEl.closest('form');
                if (form) {
                    form.addEventListener('submit', function() {
                        var content = quill.root.innerHTML;
                        // Treat "<p><br></p>" as empty
                        document.getElementById('closure-description-input').value =
                            content === '<p><br></p>' ? '' : content;
                    });
                }
            });
        </script>
    @endpush
@endsection
