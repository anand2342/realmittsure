<div>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-0">Digital Content Assignment</h5>
            <hr class="form-divider">

            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead>
                        <tr>
                            <th width="5%">#</th>
                            <th width="45%">Courses</th>
                            <th width="35%">QR Code</th>
                            <th width="15%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rows as $index => $row)
                            <tr wire:key="talent-row-{{ $index }}">
                                {{-- S.No --}}
                                <td class="text-center fw-bold">{{ $index + 1 }}</td>

                                {{-- Multi-select Courses --}}
                                <td class="multipleSel" wire:ignore>
                                    <select class="js-select2-talent form-select" multiple
                                        data-index="{{ $index }}" id="talent-select-{{ $index }}">
                                        @foreach ($availableCourses as $courseId => $courseName)
                                            <option value="{{ $courseId }}"
                                                {{ in_array((string) $courseId, array_map('strval', $row['course_ids'] ?? [])) ? 'selected' : '' }}>
                                                {{ $courseName }}
                                            </option>
                                        @endforeach
                                    </select>
                                </td>

                                {{-- QR Code --}}
                                <td class="text-center">
                                    @php
                                        $filePath = $row['qr_name'] ? 'qrcodes/' . $row['qr_name'] : null;
                                        $fileExists = $filePath ? Storage::disk('public')->exists($filePath) : false;
                                    @endphp

                                    @if (!$fileExists)
                                        <button wire:click="generateQrCode({{ $index }})"
                                            wire:loading.attr="disabled"
                                            wire:target="generateQrCode({{ $index }})" type="button"
                                            class="btn btn-sm btn-primary mb-1">
                                            <span wire:loading.remove wire:target="generateQrCode({{ $index }})">
                                                <i class="fa fa-qrcode me-1"></i>Generate QR
                                            </span>
                                            <span wire:loading wire:target="generateQrCode({{ $index }})">
                                                <span class="spinner-border spinner-border-sm"></span>
                                                Generating...
                                            </span>
                                        </button>
                                    @endif

                                    @if ($fileExists && isset($row['qr_name']) && isset($row['qr_code_link']))
                                        <div>
                                            <div class="mb-2">
                                                <img src="{{ asset('storage/' . $filePath) }}" alt="QR Code"
                                                    style="max-width: 150px;">
                                            </div>
                                            <a href="{{ route('qr.download', ['filename' => $row['qr_name']]) }}"
                                                class="btn btn-sm btn-outline-secondary d-block mb-2">
                                                <i class="fa fa-download me-1"></i>Download QR
                                            </a>
                                            <div class="input-group input-group-sm">
                                                <input type="text" class="form-control"
                                                    id="qrLink_{{ $index }}" value="{{ $row['qr_code_link'] }}"
                                                    readonly>
                                                <button class="btn btn-outline-primary copy-btn" type="button"
                                                    data-target="qrLink_{{ $index }}" title="Copy to clipboard">
                                                    <i class="fa fa-copy"></i>
                                                </button>
                                            </div>
                                            {{-- <button wire:click="generateQrCode({{ $index }})"
                                                wire:loading.attr="disabled"
                                                wire:target="generateQrCode({{ $index }})" type="button"
                                                class="btn btn-sm btn-outline-warning mt-1 w-100">
                                                <span wire:loading.remove
                                                    wire:target="generateQrCode({{ $index }})">
                                                    <i class="fa fa-refresh me-1"></i>Regenerate QR
                                                </span>
                                                <span wire:loading wire:target="generateQrCode({{ $index }})">
                                                    <span class="spinner-border spinner-border-sm"></span>
                                                    Generating...
                                                </span>
                                            </button> --}}
                                        </div>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="text-center">

                                    <button wire:click="removeRow({{ $index }})" wire:confirm="Remove this row?"
                                        type="button" class="btn btn-sm btn-danger">
                                        <i class="fa fa-trash me-1"></i>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No rows yet. Click "Add More" to
                                    begin.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-2">
                <button wire:click="addRow" type="button" class="btn btn-outline-primary">
                    <i class="fa fa-plus me-1"></i> Add More
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        (function() {

            // ── helpers ────────────────────────────────────────────────────────
            function syncToLivewire(index) {
                const el = document.getElementById('talent-select-' + index);
                if (!el) return;
                @this.set('rows.' + index + '.course_ids', $(el).val() || []);
            }

            function initOne(el) {
                // Must be in the DOM and visible before select2 can measure width
                if (!document.body.contains(el)) return;

                if ($(el).hasClass('select2-hidden-accessible')) {
                    $(el).select2('destroy');
                }

                $(el).select2({
                    closeOnSelect: false,
                    placeholder: 'Select courses',
                    allowClear: false,
                    width: '100%',
                });

                const index = el.dataset.index;
                $(el).off('change.talent').on('change.talent', function() {
                    syncToLivewire(index);
                });
            }

            window.initTalentSelect2 = function() {
                document.querySelectorAll('.js-select2-talent').forEach(initOne);
            };

            // ── MutationObserver: init any NEW select the moment it lands in DOM ─
            // This fires before any setTimeout could, catching even fast Livewire patches.
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    mutation.addedNodes.forEach(function(node) {
                        if (node.nodeType !== 1) return; // elements only

                        // The added node itself might be the select
                        if (node.matches && node.matches('.js-select2-talent')) {
                            initOne(node);
                        }
                        // Or it could be a parent (tr / td) containing the select
                        node.querySelectorAll && node.querySelectorAll('.js-select2-talent')
                            .forEach(initOne);
                    });
                });
            });

            // Start observing once the tbody exists
            function startObserver() {
                const tbody = document.querySelector('table tbody');
                if (tbody) {
                    observer.observe(tbody, {
                        childList: true,
                        subtree: true
                    });
                }
            }

            // ── Boot ────────────────────────────────────────────────────────────
            document.addEventListener('DOMContentLoaded', function() {
                initTalentSelect2();
                startObserver();
            });

            // Livewire 2
            document.addEventListener('livewire:load', function() {
                initTalentSelect2();
                startObserver();
                Livewire.hook('message.processed', function() {
                    // Re-init all after any Livewire response (covers removeRow, generateQrCode, etc.)
                    initTalentSelect2();
                });
            });

            // Livewire 3
            document.addEventListener('livewire:initialized', function() {
                initTalentSelect2();
                startObserver();
            });
            document.addEventListener('livewire:update', function() {
                // Small tick so Livewire finishes patching the DOM first
                requestAnimationFrame(initTalentSelect2);
            });

            // ── Sync ALL selects to Livewire before any wire:click fires ────────
            document.addEventListener('click', function(e) {
                if (!e.target.closest('[wire\\:click]')) return;
                document.querySelectorAll('.js-select2-talent').forEach(function(el) {
                    syncToLivewire(el.dataset.index);
                });
            }, true); // capture phase — runs before Livewire handles the click

            // ── Copy to clipboard ───────────────────────────────────────────────
            document.addEventListener('click', function(e) {
                const btn = e.target.closest('.copy-btn');
                if (!btn) return;
                const input = document.getElementById(btn.dataset.target);
                if (!input) return;
                input.select();
                input.setSelectionRange(0, 99999);
                try {
                    document.execCommand('copy');
                    const orig = btn.innerHTML;
                    btn.innerHTML = '<i class="fa fa-check"></i>';
                    setTimeout(function() {
                        btn.innerHTML = orig;
                    }, 2000);
                } catch (_) {
                    alert('Failed to copy. Please copy manually.');
                }
                window.getSelection().removeAllRanges();
            });

        }());
    </script>
@endpush
