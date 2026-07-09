<div>
    <!-- Trigger Button -->
    <button class="btn btn-sm btn-primary" wire:click="loadSchoolInfo">
        Info
    </button>

    <!-- Modal -->
    <div x-data="{ open: @entangle('isOpenModal') }" x-show="open" x-transition @click.away="open = false"
        @keydown.escape.window="open = false" class="modal fade" tabindex="-1" :class="{ 'show d-block': open }"
        style="background: rgba(0, 0, 0, 0.5);">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Access Code Details</h5>
                    <button type="button" class="btn-close" x-on:click="open = false; @this.closeModal()"></button>
                </div>
                <div class="modal-body">
                    @if ($schoolInfo)
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Access Code:</strong> {{ $schoolInfo->access_code ?? 'N/A' }}</p>
                                <p><strong>School Name:</strong> {{ $schoolInfo->user->name ?? 'N/A' }}</p>
                                <p><strong>Medium:</strong> {{ $schoolInfo->medium->name ?? 'N/A' }}</p>
                                <p><strong>Created By:</strong> {{ $schoolInfo->user->name ?? 'N/A' }}</p>
                                <p><strong>Is Activated ? :</strong> {{ $schoolInfo->is_active === 1 ? 'No' : 'Yes' }}
                                </p>
                                <p><strong>Start Date:</strong> {{ $schoolInfo->start_date ?? 'N/A' }}</p>
                                <p><strong>Used By:</strong> {{ $schoolInfo->usedBy->name ?? 'N/A' }}</p>

                            </div>
                            <div class="col-md-6">
                                <p><strong>Book Series Name:</strong>
                                    @if ($schoolInfo->book_series_id == 1)
                                        Mittsure Digital Content
                                    @elseif ($schoolInfo->book_series_id == 3)
                                        Luma Learn
                                    @else
                                        Embibe
                                    @endif
                                </p>
                                <p><strong>Board:</strong> {{ $schoolInfo->board->name ?? 'N/A' }}</p>
                                <p><strong>Class:</strong> {{ $schoolInfo->class->name ?? 'N/A' }}</p>
                                <p><strong>Status:</strong> {{ $schoolInfo->status ?? 'N/A' }}</p>
                                <p><strong>Genration Type:</strong> {{ $schoolInfo->generation_type ?? 'N/A' }}</p>
                                <p><strong>Expired Date:</strong> {{ $schoolInfo->end_date ?? 'N/A' }}</p>
                            </div>
                        </div>
                    @else
                        <p>No information available.</p>
                    @endif
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                        x-on:click="open = false; @this.closeModal()">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
