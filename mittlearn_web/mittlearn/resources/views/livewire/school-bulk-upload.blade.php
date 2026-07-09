<div>
    <!-- Upload Section -->
    <div class="mb-3">
        <div class="row align-items-center">
            <!-- Left Column: Download Button -->
            <div class="col-md-2">
                <a href="{{ asset($roleName === 'school_student' ? '/admin/sample-files/school_student-sample-file.xlsx' : '/admin/sample-files/school_teacher-sample-file.xlsx') }}"
                    class="btn btn-sm btn-primary-gradient rounded-1 px-3 w-100 text-center bluckBtn" download>
                    Sample File
                </a>
            </div>
            <div class="col-md-2">

            </div>

            <!-- Right Column: File Upload Section -->
            <div class="col-md-8">
                <form class="d-flex align-items-center gap-2">
                    <!-- File Input -->
                    <div class="flex-grow-1">
                        <input type="file" wire:model="file" class="form-control" required>
                        @error('file')
                            <span class="text-danger small">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Upload Button (Triggered only after file selection) -->
                    <button type="button" class="btn btn-sm btn-success rounded-1 px-3 bluckBtn"
                        wire:click="uploadUsers" wire:loading.attr="disabled"
                        @if (!$file) disabled @endif>
                        Upload
                    </button>

                    <!-- Loading Spinner -->
                    <div wire:loading wire:target="uploadUsers" class="spinner-border text-primary spinner-border-sm"
                        role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </form>
            </div>
        </div>

    </div>


    <div class="text-center">

        @if (session()->has('successMsg'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-1"></i>
                @if (is_array(session('successMsg')))
                    {{ implode(', ', session('successMsg')) }}
                @else
                    {{ session('successMsg') }}
                @endif
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <div x-data="{ open: @entangle('isModalOpen') }" x-show="open" x-transition @click.away="open = false"
        @keydown.escape.window="open = false" class="modal fade" tabindex="-1" :class="{ 'show d-block': open }"
        style="background: rgba(0, 0, 0, 0.5);">
        <div class="modal-dialog modal-xl">
            @if (session()->has('errorMsg'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-octagon me-1"></i>
                    <strong>Error:</strong>
                    @if (is_array(session('errorMsg')))
                        {{ implode(', ', session('errorMsg')) }}
                    @else
                        {{ session('errorMsg') }}
                    @endif
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Uploaded Data</h5>
                    <button type="button" class="btn-close" x-on:click="open = false; @this.closeModal()"></button>
                </div>
                @if (!empty($uploadedData))
                    <form wire:submit.prevent="processSelectedData">
                        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                            @if (!empty($rowErrors))
                                <div class="alert alert-danger mb-3">
                                    <strong>Errors Found:</strong> Please correct the highlighted errors below before
                                    proceeding.
                                </div>
                            @endif

                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 50px;">
                                            <input type="checkbox" id="select-all-checkbox" x-data="{ isChecked: false }"
                                                x-model="isChecked"
                                                @click="$dispatch('select-all', { isChecked: !isChecked })">
                                            <label for="select-all-checkbox" style="margin-left: 5px;">Select
                                                All</label>
                                        </th>
                                        @foreach ($headers as $header)
                                            <th>{{ $header }}</th>
                                        @endforeach
                                        <th style="width: 100px;">Status</th>
                                    </tr>
                                </thead>
                                <tbody x-data
                                    @select-all.window="event => {
                                        const checkboxes = [...$el.querySelectorAll('input[type=checkbox]')];
                                        checkboxes.forEach(c => {
                                            c.checked = event.detail.isChecked;
                                            if (event.detail.isChecked) {
                                                @this.set('selectedData', [...@this.get('selectedData'), c.value]);
                                            } else {
                                                @this.set('selectedData', []);
                                            }
                                        });
                                    }">
                                    @foreach ($uploadedData as $rowKey => $row)
                                        @php
                                            $hasErrors = isset($rowErrors[$rowKey]);
                                            $rowClass = $hasErrors ? 'table-danger' : '';
                                            $convertedHeaders = array_map(function ($h) {
                                                return $this->convertToSnakeCase($h);
                                            }, $headers);
                                        @endphp

                                        <tr class="{{ $rowClass }}">
                                            <td>
                                                <input type="checkbox" wire:model="selectedData"
                                                    value="{{ $rowKey }}">
                                            </td>

                                            @foreach ($headers as $index => $header)
                                                @php
                                                    $convertedHeader = $convertedHeaders[$index];
                                                    $fieldErrors =
                                                        $hasErrors && isset($rowErrors[$rowKey][$convertedHeader])
                                                            ? $rowErrors[$rowKey][$convertedHeader]
                                                            : null;
                                                @endphp

                                                <td @if ($fieldErrors) class="has-error" @endif>
                                                    {{ $row[$header] }}

                                                    @if ($fieldErrors)
                                                        <div class="error-tooltip">
                                                            <i class="bi bi-exclamation-circle text-danger"></i>
                                                            <div class="error-tooltip-text">
                                                                @if (is_array($fieldErrors))
                                                                    @foreach ($fieldErrors as $error)
                                                                        {{ $error }}<br>
                                                                    @endforeach
                                                                @else
                                                                    {{ $fieldErrors }}
                                                                @endif
                                                            </div>
                                                        </div>
                                                    @endif
                                                </td>
                                            @endforeach

                                            <td class="text-center">
                                                @if ($hasErrors)
                                                    <span class="badge bg-danger">Error</span>
                                                @elseif(in_array($rowKey, $selectedData))
                                                    <span class="badge bg-success">Ready</span>
                                                @else
                                                    <span class="badge bg-secondary">Pending</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer d-flex justify-content-between">
                            <div>
                                @if (!empty($rowErrors))
                                    <span class="text-danger me-3">
                                        <i class="bi bi-exclamation-triangle"></i>
                                        {{ count($rowErrors) }} row(s) have errors
                                    </span>
                                @endif
                            </div>
                            <div>
                                <button type="button" class="btn btn-secondary me-2"
                                    x-on:click="open = false; @this.closeModal()">Close</button>
                                <button type="submit" class="btn btn-primary" <i class="fas fa-upload me-1"></i>
                                    Process Selected Data
                                </button>
                            </div>
                        </div>
                    </form>
                @else
                    <div class="modal-body">
                        <p class="text-muted">No data available to display.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <style>
        .has-error {
            position: relative;
            background-color: #fff5f5;
        }

        .error-tooltip {
            position: absolute;
            top: 2px;
            right: 2px;
            cursor: help;
        }

        .error-tooltip-text {
            visibility: hidden;
            width: 250px;
            background-color: #c73b49;
            color: white;
            text-align: center;
            border-radius: 4px;
            padding: 5px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            transform: translateX(-50%);
            opacity: 0;
            transition: opacity 0.3s;
        }

        .error-tooltip:hover .error-tooltip-text {
            visibility: visible;
            opacity: 1;
        }

        .table-danger td {
            background-color: rgba(220, 53, 69, 0.1);
        }
    </style>
</div>
