<div>
    <!-- Class Assignment Form -->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-0">Assign Classes Content</h5>
            <hr class="form-divider">

            {{ Form::open(['url' => route('d2c-content.class.course.update'), 'id' => 'edit-plan-form', 'class' => 'row g-3', 'enctype' => 'multipart/form-data']) }}

            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12" wire:ignore>
                    {!! Form::label('medium_id', 'Medium', ['class' => 'form-label']) !!}
                    {!! Form::select('medium_id[]', $medium ?? [], $selectedMediumIds ?? [], [
                        'class' => 'js-select2 form-select',
                        'multiple' => 'multiple',
                    ]) !!}
                </div>

                <div class="col-md-6 col-sm-12 col-xs-12">
                    <div class="form-group bginput mb-3 multipleSel" wire:ignore>
                        {!! Form::label('class', 'Assign Classes', ['class' => 'form-label required']) !!}
                        <select name="class[]" class="js-select2 form-select" multiple="multiple" placeholder="Select">
                            @foreach ($allClasses ?? [] as $id => $name)
                                <option value="{{ $id }}" @if (in_array($id, $selectedClassIds ?? [])) selected @endif>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        {!! Form::hidden('category_id', $category_id) !!}
                        {!! Form::hidden('parent_category_id', $parent_category_id) !!}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12 col-md-12 col-xs-12">
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </div>

            {{ Form::close() }}
        </div>
    </div>

    <!-- Course Assignment Section -->
    <div class="row mt-4">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-0">Digital Content Assignment</h5>
                    <hr class="form-divider">

                    {{ Form::open(['url' => route('d2c-content.courses'), 'id' => 'add-plan-form', 'class' => 'row g-3']) }}
                    <table class="table table-striped table-bordered align-middle text-nowrap">
                        <thead>
                            <tr>
                                <th>Medium</th>
                                <th>Class</th>
                                <th>Courses</th>
                                <th>QR Code</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $groupedData = [];
                                foreach ($existingData as $key => $data) {
                                    $classId = $data['class_id'];
                                    $mediumId = $data['medium_id'];
                                    $groupKey = "{$classId}_" . ($mediumId !== null ? $mediumId : 'null');

                                    if (!isset($groupedData[$groupKey])) {
                                        $groupedData[$groupKey] = [];
                                    }
                                    $groupedData[$groupKey][] = ['key' => $key, 'data' => $data];
                                }
                            @endphp

                            @foreach ($groupedData as $groupKey => $items)
                                @php
                                    $firstItem = $items[0]['data'];
                                    $classId = $firstItem['class_id'];
                                    $mediumId = $firstItem['medium_id'];
                                @endphp

                                @if (isset($allClasses[$classId]))
                                    @foreach ($items as $index => $item)
                                        @php
                                            $key = $item['key'];
                                            $data = $item['data'];
                                            $sn = $data['sn'] ?? 1;
                                        @endphp
                                        <tr>
                                            <input type="hidden" name="category_id" value="{{ $category_id }}">
                                            <input type="hidden" name="parent_category_id"
                                                value="{{ $parent_category_id }}">

                                            {{-- Serial Number --}}
                                            <input type="hidden" name="sn[{{ $key }}]"
                                                value="{{ $sn }}">

                                            {{-- Medium Name --}}
                                            <td>
                                                <input type="text" class="form-control"
                                                    value="{{ $medium[$mediumId] ?? 'N/A' }}" readonly>
                                                <input type="hidden" name="medium_id[{{ $key }}]"
                                                    value="{{ $mediumId }}">
                                            </td>

                                            {{-- Class Name --}}
                                            <td>
                                                <input type="text" class="form-control fw-bold"
                                                    value="{{ $allClasses[$classId] ?? 'N/A' }}" readonly>
                                                <input type="hidden" name="class_id[{{ $key }}]"
                                                    value="{{ $classId }}">
                                            </td>

                                            {{-- Courses --}}
                                            <td class="multipleSel" wire:ignore>
                                                <select name="course_ids[{{ $key }}][]"
                                                    class="js-select2 form-select" multiple>
                                                    @foreach ($categoryCoursesPerClass[$groupKey] ?? [] as $courseId => $courseName)
                                                        <option value="{{ $courseId }}"
                                                            {{ in_array($courseId, $data['course_ids'] ?? []) ? 'selected' : '' }}>
                                                            {{ $courseName }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>

                                            {{-- QR Code --}}
                                            <td class="text-center">
                                                @php
                                                    $filePath = $data['qr_name'] ? 'qrcodes/' . $data['qr_name'] : null;
                                                    $fileExists = $filePath
                                                        ? Storage::disk('public')->exists($filePath)
                                                        : false;
                                                @endphp

                                                @if (!$fileExists)
                                                    <button wire:click="generateQrCode('{{ $key }}')"
                                                        wire:loading.attr="disabled"
                                                        wire:target="generateQrCode('{{ $key }}')"
                                                        type="button" class="btn btn-sm btn-primary mb-2">
                                                        <span wire:loading.remove
                                                            wire:target="generateQrCode('{{ $key }}')">
                                                            <i class="fa fa-qrcode me-1"></i>Generate QR
                                                        </span>
                                                        <span wire:loading
                                                            wire:target="generateQrCode('{{ $key }}')">
                                                            <span class="spinner-border spinner-border-sm"></span>
                                                            Generating...
                                                        </span>
                                                    </button>
                                                @endif

                                                @if ($fileExists && isset($data['qr_name']) && isset($data['qr_code_link']))
                                                    <div>
                                                        <div class="mb-2">
                                                            <img src="{{ asset('storage/' . $filePath) }}"
                                                                alt="QR Code" style="max-width: 150px;">
                                                        </div>
                                                        <a href="{{ route('qr.download', ['filename' => $data['qr_name']]) }}"
                                                            class="btn btn-sm btn-outline-secondary d-block mb-2">
                                                            <i class="fa fa-download me-1"></i>Download
                                                        </a>

                                                        <div class="input-group mb-2">
                                                            <input type="text" class="form-control form-control-sm"
                                                                id="qrLink{{ $key }}"
                                                                value="{{ $data['qr_code_link'] }}" readonly>
                                                            <button class="btn btn-sm btn-outline-primary"
                                                                type="button" id="copyButton{{ $key }}"
                                                                title="Copy">
                                                                <i class="fa fa-copy"></i>
                                                            </button>
                                                        </div>
                                                    </div>

                                                    <input type="hidden" name="qr_name[{{ $key }}]"
                                                        value="{{ $data['qr_name'] }}">
                                                    <input type="hidden" name="qr_code_link[{{ $key }}]"
                                                        value="{{ $data['qr_code_link'] }}">
                                                @endif
                                            </td>

                                            {{-- Actions --}}
                                            <td class="text-center">
                                                @if ($index === 0)
                                                    {{-- Add More button only on first row of each class/medium group --}}
                                                    <button type="button"
                                                        wire:click="addMoreQR({{ $classId }}, {{ $mediumId ?? 'null' }})"
                                                        class="btn btn-sm btn-success mb-1" title="Add More QR">
                                                        <i class="fa fa-plus"></i>
                                                    </button>
                                                @endif

                                                @if (count($items) > 1)
                                                    {{-- Delete button for additional rows --}}
                                                    <button type="button" wire:click="deleteQR('{{ $key }}')"
                                                        wire:confirm="Are you sure you want to delete this QR entry?"
                                                        class="btn btn-sm btn-danger" title="Delete QR">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                        </tbody>
                    </table>

                    <hr class="form-divider">

                    <div class="col-sm-12 text-end">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="button" class="btn btn-secondary" wire:click="resetForm">Reset</button>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Add Courses Modal (unchanged) -->
    <div x-data="{ open: @entangle('showModal') }" x-show="open" @keydown.escape.window="open = false" style="display: none;"
        x-transition>
        <div class="modal-backdrop fade show" x-show="open"></div>

        <div class="modal fade show" style="display: block;">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Add Courses</h5>
                        <button type="button" class="btn-close" @click="open = false" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <form wire:submit.prevent="addCourses" class="row g-3">
                            <div class="col-md-6 mb-3">
                                <label for="parent_id" class="form-label ">Parent Category</label>
                                <select id="parent_id" class="form-control" wire:model="parent_id"
                                    wire:change="loadSubcategories($event.target.value)">
                                    <option value="">-- Select Parent Category --</option>
                                    @foreach ($parentCategroy as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="selectedModalCourses" id="selectedModalCoursesHidden">
                                @error('parent_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="category_id" class="form-label ">Sub Category</label>
                                <select id="category_id" class="form-control" wire:model="sub_category_id"
                                    wire:change="loadCourses($event.target.value)">
                                    <option value="">-- Select Sub Category --</option>
                                    @foreach ($subCategory as $id => $name)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div wire:ignore.self>
                                <label for="courses" class="form-label ">Courses</label>
                                <select id="courses" class="form-control js-select2" multiple>
                                    @foreach ($subCategoryCourse as $id => $name)
                                        <option value="{{ $id }}"
                                            {{ in_array($id, $selectedCoursesId ?? []) ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('selectedModalCourses')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" @click="open = false">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const copyButtons = document.querySelectorAll('[id^="copyButton"]');

            copyButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const key = this.id.replace('copyButton', '');
                    const input = document.getElementById('qrLink' + key);

                    if (input) {
                        input.select();
                        input.setSelectionRange(0, 99999);

                        try {
                            const successful = document.execCommand('copy');
                            if (successful) {
                                const originalHTML = this.innerHTML;
                                this.innerHTML = '<i class="fa fa-check"></i>';

                                setTimeout(() => {
                                    this.innerHTML = originalHTML;
                                }, 2000);
                            }
                        } catch (err) {
                            alert('Failed to copy link. Please copy manually.');
                        }
                        window.getSelection().removeAllRanges();
                    }
                });
            });
        });
    </script>

    <script>
        document.addEventListener('livewire:load', function() {
            initSelect2();

            Livewire.hook('message.processed', (message, component) => {
                initSelect2();
            });
        });

        function initSelect2() {
            $(".js-select2").select2({
                closeOnSelect: false,
                placeholder: "Select",
                allowClear: false,
                tags: true
            });
        }

        document.addEventListener("DOMContentLoaded", function() {
            initSelect2();
        });

        $('.js-select2').on('change', function(e) {
            const data = $(this).val();
            Livewire.dispatch('updateCourses', {
                selected: data
            });
        });

        document.addEventListener("change", function(event) {
            if (event.target.matches("[wire\\:model^='rows'][wire\\:model*='.series_id']")) {
                setTimeout(initSelect2, 500);
            }
        });

        document.addEventListener("click", function(event) {
            if (event.target.closest("[wire\\:click^='generateQrCode']")) {
                setTimeout(initSelect2, 500);
            }
        });
        document.addEventListener("click", function(event) {
            if (event.target.closest("[wire\\:click^='addMoreQR']")) {
                setTimeout(initSelect2, 500);
            }
        });

        document.addEventListener("click", function(event) {
            if (event.target.closest("[wire\\:click^='openAddCoursesModal']")) {
                setTimeout(initSelect2, 500);
            }
        });

        document.addEventListener("click", function(event) {
            if (event.target.closest("[wire\\:click^='addOtherCategoryCourses']")) {
                setTimeout(initSelect2, 500);
            }
        });

        document.addEventListener("change", function(event) {
            if (event.target.closest("[wire\\:change^='loadSubcategories']")) {
                setTimeout(initSelect2, 500);
            }
        });

        document.addEventListener("change", function(event) {
            if (event.target.closest("[wire\\:change^='loadCourses']")) {
                setTimeout(initSelect2, 500);
            }
        });

        document.addEventListener("change", function(event) {
            if (event.target.matches("[wire\\:model='coursesIds']")) {
                setTimeout(initSelect2, 1000);
            }
        });
    </script>
@endpush
