@extends('admin.layouts.master')

@section('content')
    <div class="pagetitle">
        <h1>Teacher Development Contents</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item active">Teacher Development Contents</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">

                        <!-- HEADER -->
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="card-title">All Teacher Development Contents</div>
                            </div>
                            <div class="col-sm-6 text-end mt-3">
                                <a href="{{ route('teacher.development.create') }}" class="btn btn-success">
                                    Add New
                                </a>
                            </div>
                        </div>

                        <hr class="formdivider">

                        <!-- SUCCESS / ERROR FLASH -->
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        @endif

                        <!-- TABLE -->
                        <div class="table-responsive tbleDiv">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th><b>Title</b></th>
                                        <th><b>Videos</b></th>
                                        <th><b>Access</b></th>
                                        <th><b>Status</b></th>
                                        <th width="180">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($contents as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}.</td>
                                            <td>{{ $item->title }}</td>
                                            <td>
                                                <span class="badge bg-info">
                                                    {{ $item->videos_count }} Videos
                                                </span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge {{ $item->is_for_all_schools ? 'bg-success' : 'bg-warning' }}">
                                                    {{ $item->is_for_all_schools ? 'All Schools' : 'Selected Schools' }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge {{ $item->is_active ? 'text-success' : 'text-danger' }}">
                                                    {{ config('constants.STATUS_LIST')[$item->is_active] ?? 'Active' }}
                                                </span>
                                            </td>
                                            <td>
                                                <!-- EDIT -->
                                                <a class="btn btn-sm btn-warning"
                                                    href="{{ route('teacher.development.edit', $item->id) }}">
                                                    <i class="fa fa-pencil"></i>
                                                </a>

                                                <!-- ASSIGN SCHOOLS — opens modal -->
                                                <button class="btn btn-sm btn-info"
                                                    onclick="openAssignModal({{ $item->id }})" title="Assign Schools">
                                                    <i class="fa fa-building"></i>
                                                </button>

                                                <!-- DELETE -->
                                                <form method="POST"
                                                    action="{{ route('teacher.development.destroy', $item->id) }}"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Delete this content?')">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>


    <div class="modal fade" id="assignSchoolsModal" tabindex="-1" aria-labelledby="assignSchoolsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title" id="assignSchoolsModalLabel">Assign Schools</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Body is filled dynamically via AJAX -->
                <div class="modal-body" id="assignModalBody">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <script>
        // -------------------------------------------------------
        // 1. Open modal and load content via AJAX
        // -------------------------------------------------------
        function openAssignModal(contentId) {
            // Reset body to spinner
            document.getElementById('assignModalBody').innerHTML = `
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>`;

            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('assignSchoolsModal'));
            modal.show();

            // Load form HTML from server
            fetch(`/admin/teacher-development/${contentId}/assign-schools-modal`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.text())
                .then(html => {
                    // 1. Inject HTML
                    document.getElementById('assignModalBody').innerHTML = html;

                    // 2. Init Select2 NOW — element is in DOM at this point
                    initModalSelect2();

                    // 3. Wire up form submit + toggle
                    initAssignForm(contentId);
                })
                .catch(() => {
                    document.getElementById('assignModalBody').innerHTML =
                        '<div class="alert alert-danger">Failed to load. Please try again.</div>';
                });
        }

        // -------------------------------------------------------
        // 2. Initialize Select2 on the modal multiselect
        // -------------------------------------------------------
        function initModalSelect2() {
            // Destroy first if already initialized (prevents double-init on re-open)
            if ($('#modalSchoolSelect').hasClass('select2-hidden-accessible')) {
                $('#modalSchoolSelect').select2('destroy');
            }

            $('#modalSchoolSelect').select2({
                dropdownParent: $('#assignSchoolsModal'), // CRITICAL: keeps dropdown inside modal
                placeholder: 'Select schools...',
                allowClear: true,
                closeOnSelect: false,
                width: '100%',
            });
        }

        // -------------------------------------------------------
        // 3. Wire up toggle + AJAX form submit
        // -------------------------------------------------------
        function initAssignForm(contentId) {

            // Toggle school list on load
            toggleSchoolList();
            document.getElementById('allSchools')?.addEventListener('change', toggleSchoolList);

            // AJAX form submit
            const form = document.getElementById('assignSchoolsForm');
            if (!form) return;

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const btn = form.querySelector('[type=submit]');
                const origTxt = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Saving...';

                const formData = new FormData(form);

                // If no school selected, send empty array so server syncs correctly
                if (!formData.has('school_ids[]')) {
                    formData.append('school_ids[]', '');
                }

                fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: formData,
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            bootstrap.Modal.getInstance(
                                document.getElementById('assignSchoolsModal')
                            ).hide();

                            showFlash('success', data.message ?? 'Schools assigned successfully');
                            updateAccessBadge(contentId, data.is_for_all);
                        } else {
                            showFlash('danger', data.message ?? 'Something went wrong.');
                        }
                    })
                    .catch(() => showFlash('danger', 'Server error. Please try again.'))
                    .finally(() => {
                        btn.disabled = false;
                        btn.innerHTML = origTxt;
                    });
            });
        }

        // -------------------------------------------------------
        // 4. Toggle school select visibility
        // -------------------------------------------------------
        function toggleSchoolList() {
            const isChecked = document.getElementById('allSchools')?.checked;
            const list = document.getElementById('schoolList');
            if (!list) return;

            list.style.display = isChecked ? 'none' : 'block';

            // Clear Select2 selection when "all schools" is checked
            if (isChecked && $('#modalSchoolSelect').hasClass('select2-hidden-accessible')) {
                $('#modalSchoolSelect').val(null).trigger('change');
            }
        }

        // -------------------------------------------------------
        // 5. Update Access badge in the table row (no reload)
        // -------------------------------------------------------
        function updateAccessBadge(contentId, isForAll) {
            const btn = document.querySelector(`[data-content-id="${contentId}"]`);
            if (!btn) return;
            const badge = btn.closest('tr')?.querySelector('.access-badge');
            if (!badge) return;
            badge.className = `badge access-badge ${isForAll ? 'bg-success' : 'bg-warning'}`;
            badge.textContent = isForAll ? 'All Schools' : 'Selected Schools';
        }

        // -------------------------------------------------------
        // 6. Flash message helper
        // -------------------------------------------------------
        function showFlash(type, message) {
            const existing = document.getElementById('ajaxFlash');
            if (existing) existing.remove();

            const div = document.createElement('div');
            div.id = 'ajaxFlash';
            div.className = `alert alert-${type} alert-dismissible fade show`;
            div.role = 'alert';
            div.innerHTML = `${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>`;

            const tableWrapper = document.querySelector('.table-responsive');
            tableWrapper?.parentNode.insertBefore(div, tableWrapper);

            setTimeout(() => div?.remove(), 4000);
        }
    </script>
@endsection
