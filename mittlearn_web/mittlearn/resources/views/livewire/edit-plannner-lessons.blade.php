<div>
    <!-- Button to trigger modal -->
    <button type="button" class="btn btn-primary" wire:click="openModal('{{ $stepType }}')">
        Edit Details
    </button>
    <!-- Modal -->
    @if ($showModal)
        <div class="modal fade" tabindex="-1" :class="{ 'show d-block': open }" style="background: rgba(0, 0, 0, 0.5);"
            id="ExtralargeModal" aria-labelledby="ExtralargeModalLabel" aria-hidden="true" role="dialog">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ExtralargeModalLabel">Edit
                            {{ ucwords(str_replace('_', ' ', $this->stepType)) }}</h5>
                        <button type="button" class="btn-close" wire:click="closeModal" aria-label="Close"></button>
                    </div>
                    <form wire:submit.prevent="savePlannerLesson" enctype="multipart/form-data">
                        <div class="modal-body">
                            @if ($stepType != 'event_function')
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th>Title</th>
                                            <th>Image</th>
                                            <th>Description</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($rowsByType as $index => $row)
                                            <!-- Ensure that row is an array and access its values correctly -->
                                            <input type="hidden" wire:model="rowsByType.{{ $index }}.model_id"
                                                value="{{ $row['model_id'] }}">
                                            <input type="hidden" wire:model="rowsByType.{{ $index }}.id"
                                                value="{{ $row['id'] }}">
                                            <input type="hidden" wire:model="rowsByType.{{ $index }}.type"
                                                value="{{ $stepType }}">

                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <input type="text"
                                                        wire:model="rowsByType.{{ $index }}.title"
                                                        class="form-control" value="{{ $row['title'] }}"
                                                        placeholder="Enter title">
                                                    @error("rowsByType.$index.title")
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="file"
                                                        wire:model="rowsByType.{{ $index }}.image"
                                                        class="form-control">
                                                    @if (!empty($row['image']))
                                                        <img src="{{ Storage::url('uploads/planner-files/' . $row['image']) }}"
                                                            alt="Image" width="100">
                                                    @endif
                                                    @error("rowsByType.$index.image")
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                                <td wire:key="row-{{ $index }}-desc">
                                                    <div wire:ignore x-data="quillEditor('rowsByType_{{ $index }}_description', @js($row['description'] ?? ''))" x-init="init">
                                                        <div x-ref="editor" style="height: 150px;"></div>
                                                        <input type="hidden"
                                                            name="rowsByType[{{ $index }}][description]"
                                                            wire:model="rowsByType.{{ $index }}.description"
                                                            required>
                                                    </div>
                                                    @error("rowsByType.$index.description")
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </td>
                                                <td>
                                                    @if ($index != 0)
                                                        <button type="button" class="btn btn-danger btn-sm"
                                                            wire:click="removeRow({{ $index }})">
                                                            <i class="fa fa-trash"></i>
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td class="text-end" colspan="5">
                                                <button type="button" class="btn btn-success btn-sm"
                                                    wire:click="addRow">Add More</button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            @else
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Event Title</th>
                                            <th>Event Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                {!! Form::text('event_title', null, [
                                                    'class' => 'form-control',
                                                    'wire:model' => 'event_title',
                                                    'placeholder' => 'Enter Event Title',
                                                ]) !!}
                                                @error('event_title')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </td>
                                            <td wire:ignore>
                                                <input type="hidden" wire:model="model_id"
                                                    value="{{ $model_id }}">
                                                <input type="hidden" wire:model="id" value="{{ $id }}">
                                                <input type="hidden" wire:model="type" value="{{ $type }}">

                                                <div x-data="quillEditor('event_description', @js($event_description ?? ''))" x-init="init">
                                                    <div x-ref="editor" style="height: 150px;"></div>
                                                    <input type="hidden" name="event_description"
                                                        wire:model="event_description" required>
                                                </div>

                                                @error('event_description')
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" wire:click="closeModal">Close</button>
                            <button type="submit" class="btn btn-primary">Save changes</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    @endif
</div>
@push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('quillEditor', (name, initialContent) => ({
                quill: null,
                content: initialContent,
                instanceKey: null,

                init() {
                    this.instanceKey = `quill-${name}-${Math.random().toString(36).substr(2, 9)}`;

                    this.$nextTick(() => {
                        if (!this.quill) {
                            this.quill = new Quill(this.$refs.editor, {
                                modules: {
                                    toolbar: [
                                        ["bold", "italic", "underline"],
                                        [{
                                            "script": "super"
                                        }, {
                                            "script": "sub"
                                        }],
                                        ["image"]
                                    ]
                                },
                                theme: "snow"
                            });

                            // Set initial content
                            if (this.content) {
                                this.quill.root.innerHTML = this.content;
                            }

                            // Update Livewire on content change
                            this.quill.on('text-change', () => {
                                const html = this.quill.root.innerHTML;
                                this.$el.querySelector('input[type="hidden"]').value =
                                    html;
                                // Manually trigger Livewire update
                                this.$el.querySelector('input[type="hidden"]')
                                    .dispatchEvent(new Event('input'));
                            });
                        }
                    });
                },

                destroy() {
                    if (this.quill) {
                        this.quill.off('text-change');
                        this.quill = null;
                    }
                }
            }));
        });
    </script>
@endpush
