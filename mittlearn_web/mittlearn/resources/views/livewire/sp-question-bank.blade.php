<div>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-1"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @elseif(session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-octagon me-1"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @elseif(session()->has('info'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-octagon me-1"></i>
            {{ session('info') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="cardBox teacherMain pt-md-4 pb-0  mb-3 ">
        <div class="formPanel">
            <form wire:submit.prevent="save" class="row g-3" id="question-form">
                <h5 class="card-title pb-0"> Add Question Bank</h5>
                <hr class="form-divider">
                @if (isset($id))
                    {!! Form::hidden('id', $id) !!}
                @endif
                <div class="col-md-6">
                    <div class="form-group bginput">
                        {!! Form::label('class_id', 'Select Class', ['class' => 'form-label required']) !!}

                        {!! Form::select('class_id', $classes, null, [
                            'class' => 'form-control form-select fs-8 ',
                            'placeholder' => '--Select--',
                            'wire:model' => 'class_id',
                        ]) !!}
                        @error('class_id')
                            <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group bginput">
                        {!! Form::label('subject_id', 'Select Subject', ['class' => 'form-label required']) !!}
                        {!! Form::select('subject_id', $subjects, null, [
                            'class' => 'form-control form-select fs-8 ',
                            'placeholder' => '--Select--',
                            'wire:model' => 'subject_id',
                        ]) !!}
                        @error('subject_id')
                            <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group bginput">
                        {!! Form::label('question_type', 'Question Type', ['class' => 'form-label required']) !!}
                        {!! Form::select('question_type', $question_types, null, [
                            'class' => 'form-control form-select fs-8',
                            'placeholder' => '--Select--',
                            'wire:model' => 'question_type',
                            'wire:change' => 'questionTypeChanged',
                        ]) !!}
                        @error('question_type')
                            <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group bginput"> {!! Form::label('difficulty_level', 'Difficult Level', ['class' => 'form-label required ']) !!}
                        {!! Form::select(' ', config('constants.DIFFICULTY_LEVEL'), null, [
                            'class' => 'form-control form-select fs-8 ',
                            'placeholder' => '--Select--',
                            'wire:model' => 'difficulty_level',
                        ]) !!}
                        @error('difficulty_level')
                            <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group  mb-3">
                        {!! Form::label('marks', 'Marks', ['class' => 'form-label required ']) !!}
                        {!! Form::number('marks', null, [
                            'class' => 'form-control',
                            'wire:model' => 'marks',
                            'placeholder' => 'Marks of Question',
                        ]) !!}
                        @error('marks')
                            <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                        @enderror
                    </div>

                </div>

                <div class="col-md-6">
                    <div class="form-group bginput">
                        {!! Form::label('status', 'Status', ['class' => 'form-label required ']) !!}
                        {!! Form::select('status', config('constants.STATUS_LIST'), 1, [
                            'class' => 'form-control form-select fs-8 ',
                            'wire:model' => 'status',
                            'placeholder' => '--Select--',
                        ]) !!}
                        @error('status')
                            <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                        @enderror
                    </div>
                </div>




                @if (in_array($question_type, [
                        'one-word-answer',
                        'circle-underline',
                        'read-circle',
                        'short-answer-questions',
                        'long-answer-questions',
                        'fill-ups',
                    ]))
                    <div class="col-md-12" wire:ignore x-data="quillEditor('question', @js($question))">
                        {!! Form::label('question', 'Question Title', ['class' => 'form-label required']) !!}
                        <div x-ref="editor" style="height: 200px;"></div>
                        {!! Form::hidden('question', $question, ['id' => 'editor-content-question', 'required' => true]) !!}
                    </div>

                    <div class="col-md-12 mt-4" wire:ignore x-data="quillEditor('description', @js($description))">
                        {!! Form::label('description', 'Rubric', ['class' => 'form-label']) !!}
                        <small class="text-muted">(Hint or solution if any)</small>
                        <div x-ref="editor" style="height: 200px;"></div>
                        {!! Form::hidden('description', $description, ['id' => 'editor-content-description', 'required' => false]) !!}
                    </div>
                @endif
                @if ($question_type === 't/f')
                    <div class="col-md-12" wire:ignore x-data="quillEditor('question', @js($question))">
                        {!! Form::label('question', 'Question Title', ['class' => 'form-label required']) !!}
                        <div x-ref="editor" style="height: 200px;"></div>
                        {!! Form::hidden('question', $question, ['id' => 'editor-content-question', 'required' => true]) !!}
                    </div>
                    @error('question')
                        <div class="col-md-12">
                            <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                        </div>
                    @enderror

                    <!-- Options for T/F -->
                    <div class="row">
                        @foreach ($tfoptions as $tfindex => $option)
                            <div class="col-md-6 col-sm-12 mt-3">
                                <label class="form-label required">Option {{ $tfindex + 1 }}</label>
                                <div class="option-group mb-2">
                                    <input type="text" class="form-control"
                                        wire:model="tfoptions.{{ $tfindex }}.option"
                                        placeholder="Enter the option">

                                    @error("tfoptions.{$tfindex}.option")
                                        <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                                    @enderror

                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <label class="form-label">
                                            <input type="radio" name="correct" value="{{ $tfindex }}"
                                                wire:model="correct"
                                                wire:change="setCorrectOption({{ $tfindex }})">
                                            Correct
                                        </label>

                                        @if ($tfindex > 0)
                                            <button type="button" wire:click="removeTfOption({{ $tfindex }})"
                                                class="btn btn-danger btn-sm">
                                                Remove
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @error('tfoptions')
                        <div class="col-md-12">
                            <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                        </div>
                    @enderror
                    @error('correct')
                        <div class="col-md-12">
                            <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                        </div>
                    @enderror

                    <div class="col-md-6">
                        <button type="button" wire:click="addTfOption" class="btn btn-success mt-3">
                            Add More Option
                        </button>
                    </div>

                    <div class="col-md-12 mt-4" wire:ignore x-data="quillEditor('description', @js($description))">
                        {!! Form::label('description', 'Rubric', ['class' => 'form-label']) !!}
                        <small class="text-muted">(Hint or solution if any)</small>
                        <div x-ref="editor" style="height: 200px;"></div>
                        {!! Form::hidden('description', $description, ['id' => 'editor-content-description', 'required' => false]) !!}
                    </div>
                @endif

                <!-- Picture Based/MCQ Question Type -->
                @if ($question_type === 'picture-based-questions' || $question_type === 'mcq' || $question_type === 'tick')
                    <div class="col-md-12" wire:ignore x-data="quillEditor('question', @js($question), 'editor-content-question')">
                        {!! Form::label('question', 'Question Title', ['class' => 'form-label required']) !!}
                        <div x-ref="editor" style="height: 200px;"></div>
                        {!! Form::hidden('question', $question, [
                            'id' => 'editor-content-question',
                            'required' => true,
                            'wire:model' => 'question',
                        ]) !!}
                    </div>
                    @error('question')
                        <div class="col-md-12">
                            <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                        </div>
                    @enderror
                    <!-- Options -->
                    <div class="row">
                        @foreach ($pictureBasedQuestion as $index => $option)
                            <div class="col-md-6 col-sm-12 mt-3">
                                <label class="form-label required">Option {{ $index + 1 }}</label>
                                <div class="option-group mb-2" data-index="{{ $index }}">
                                    <!-- Option Editor -->
                                    <d iv wire:ignore x-data="quillEditor('option_{{ $index }}', @js($option['option'] ?? ''), 'option-editor-{{ $index }}')">
                                        <div x-ref="editor" style="height: 150px;"></div>
                                        <input type="hidden"
                                            name="pictureBasedQuestion[{{ $index }}][option]"
                                            id="option-editor-{{ $index }}"
                                            wire:model="pictureBasedQuestion.{{ $index }}.option">
                                    </d>
                                    @error("pictureBasedQuestion.{$index}.option")
                                        <div class="col-md-12">
                                            <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                                        </div>
                                    @enderror

                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <!-- Correct Checkbox -->
                                        <label class="form-check form-check-inline mb-0">
                                            <input type="checkbox" class="form-check-input"
                                                wire:model="pictureBasedQuestion.{{ $index }}.correct">
                                            <span class="form-check-label">Correct</span>
                                        </label>
                                        @if ($index > 0)
                                            <input type="hidden"
                                                wire:model="pictureBasedQuestion.{{ $index }}.id">
                                            <button type="button" wire:click="removeTextEditor({{ $index }})"
                                                class="btn btn-danger btn-sm">
                                                Remove
                                            </button>
                                        @endif
                                    </div>
                                    @error('pictureBasedQuestion')
                                        <div class="col-md-12 mt-1">
                                            <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Add More Option Button -->
                    <div class="col-md-6">
                        <button type="button" wire:click="addTextEditorOption" class="btn btn-success mt-3">
                            Add More Option
                        </button>
                    </div>

                    <div class="col-md-12 mt-4" wire:ignore x-data="quillEditor('description', @js($description))">
                        {!! Form::label('description', 'Rubric', ['class' => 'form-label']) !!}
                        <small class="text-muted">(Hint or solution if any)</small>
                        <div x-ref="editor" style="height: 200px;"></div>
                        {!! Form::hidden('description', $description, ['id' => 'editor-content-description', 'required' => false]) !!}
                    </div>
                @endif

                <!-- Passage Question Type -->
                @if ($question_type === 'passage')
                    <div class="col-md-6" wire:ignore x-data="quillEditor('paragraph', @js($paragraph))">
                        {!! Form::label('paragraph', 'paragraph', ['class' => 'form-label required']) !!}
                        <div x-ref="editor" style="height: 200px;"></div>
                        {!! Form::hidden('paragraph', $paragraph, ['id' => 'editor-content-paragraph', 'required' => true]) !!}
                    </div>
                    @error('paragraph')
                        <div class="col-md-6">
                            <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                        </div>
                    @enderror

                    <div class="col-md-6" wire:ignore x-data="quillEditor('paragraph_statement', @js($paragraph_statement))">
                        {!! Form::label('paragraph_statement', 'Paragraph Statement', ['class' => 'form-label required']) !!}
                        <div x-ref="editor" style="height: 200px;"></div>
                        {!! Form::hidden('paragraph_statement', $paragraph_statement, [
                            'id' => 'editor-content-paragraph_statement',
                            'required' => true,
                        ]) !!}
                    </div>
                    @error('paragraph_statement')
                        <div class="col-md-6">
                            <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                        </div>
                    @enderror

                    <div class="col-md-12">
                        @foreach ($passage as $index => $option)
                            <div class="option-group row g-3 mt-2" data-index="{{ $index }}">
                                <div class="col-md-6" wire:ignore x-data="quillEditor('questions', @js($option['questions']))">
                                    {!! Form::label('questions', 'Question ' . $index + 1, ['class' => 'form-label']) !!}
                                    <div x-ref="editor" style="height: 200px;"></div>
                                    {!! Form::hidden('options[' . $index . '][questions]', $option['questions'], [
                                        'id' => 'editor-content-questions',
                                        'required' => true,
                                    ]) !!}
                                </div>
                                @error("passage.{$index}.questions")
                                    <div class="col-md-6">
                                        <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                                    </div>
                                @enderror

                                <div class="col-md-6" wire:ignore x-data="quillEditor('answer', @js($option['answer']))">
                                    {!! Form::label('answer', 'Answer ' . $index + 1, ['class' => 'form-label']) !!}
                                    <div x-ref="editor" style="height: 200px;"></div>
                                    {!! Form::hidden('options[' . $index . '][answer]', $option['answer'], [
                                        'id' => 'editor-content-answer',
                                        'required' => true,
                                    ]) !!}
                                </div>
                                @error("passage.{$index}.answer")
                                    <div class="col-md-6">
                                        <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                                    </div>
                                @enderror

                                @if ($index > 0)
                                    <div class="col-md-6">
                                        {!! Form::hidden("passage[$index][id]", $option['id'] ?? null, [
                                            'wire:model.defer' => "passage[$index].id",
                                        ]) !!}
                                        <button type="button" wire:click="removePassage({{ $index }})"
                                            class="btn btn-danger btn-sm mt-2">
                                            Remove
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @endforeach

                        <button type="button" wire:click="addPassage" class="btn btn-success mt-3">
                            Add More Option
                        </button>
                    </div>
                    <div class="col-md-12 mt-4" wire:ignore x-data="quillEditor('description', @js($description))">
                        {!! Form::label('description', 'Rubric', ['class' => 'form-label']) !!}
                        <small class="text-muted">(Hint or solution if any)</small>
                        <div x-ref="editor" style="height: 200px;"></div>
                        {!! Form::hidden('description', $description, ['id' => 'editor-content-description', 'required' => false]) !!}
                    </div>
                @endif

                <!-- Match the Following Question Type -->
                @if ($question_type === 'match-the-following')
                    <!-- Question Editor -->
                    <div class="col-md-12" wire:ignore x-data="quillEditor('question', @js($question), 'editor-content-question')">
                        {!! Form::label('question', 'Question Title', ['class' => 'form-label required']) !!}
                        <div x-ref="editor" style="height: 200px;"></div>
                        {!! Form::hidden('question', $question, [
                            'id' => 'editor-content-question',
                            'required' => true,
                            'wire:model' => 'question',
                        ]) !!}
                        @error('question')
                            <div class="col-md-12 mt-1">
                                <div class="text-danger small">{{ $message }}</div>
                            </div>
                        @enderror
                    </div>

                    <!-- Match the Following Options -->
                    <div class="row mt-3">
                        <!-- Left Column (4 options) -->
                        <div class="col-md-6">
                            <label class="form-label required">Options - Left</label>
                            @foreach (collect($matchTheFollowing)->take(4) as $index => $option)
                                <div class="option-group mb-3" data-index="{{ $index }}">
                                    <div wire:ignore x-data="quillEditor('left_option_{{ $index }}', @js($option['option_match'] ?? ''), 'left_option_{{ $index }}')">
                                        <div x-ref="editor" style="height: 150px;"></div>
                                        <input type="hidden"
                                            name="matchTheFollowing[{{ $index }}][option_match]"
                                            id="left_option_{{ $index }}"
                                            wire:model="matchTheFollowing.{{ $index }}.option_match" required>
                                    </div>
                                    @error("matchTheFollowing.{$index}.option_match")
                                        <div class="text-danger small mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            @endforeach
                        </div>

                        <!-- Right Column (4 options) -->
                        <div class="col-md-6">
                            <label class="form-label required">Options - Right</label>
                            @foreach (collect($matchTheFollowing)->slice(4)->values() as $index => $option)
                                @php $actualIndex = $index + 4; @endphp
                                <div class="option-group mb-3" data-index="{{ $actualIndex }}">
                                    <div wire:ignore x-data="quillEditor('right_option_{{ $actualIndex }}', @js($option['option_match'] ?? ''), 'right_option_{{ $actualIndex }}')">
                                        <div x-ref="editor" style="height: 150px;"></div>
                                        <input type="hidden"
                                            name="matchTheFollowing[{{ $actualIndex }}][option_match]"
                                            id="right_option_{{ $actualIndex }}"
                                            wire:model="matchTheFollowing.{{ $actualIndex }}.option_match" required>
                                    </div>
                                    @error("matchTheFollowing.{$actualIndex}.option_match")
                                        <div class="text-danger small mt-1">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Answers Section -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label class="form-label required">Match Answers</label>
                            @foreach (collect($matchTheFollowing)->take(4) as $index => $option)
                                <div class="row mb-2">
                                    <div class="col-md-3">
                                        <label class="form-label">Left Option {{ $index + 1 }} matches
                                            with:</label>
                                    </div>
                                    <div class="col-md-3">
                                        <select
                                            class="form-select @error("answers.{$index}.correct") is-invalid @enderror"
                                            wire:model="answers.{{ $index }}.correct">
                                            <option value="">Select match</option>
                                            @foreach (range(1, 4) as $rightIndex)
                                                <option value="{{ $rightIndex }}">Right Option
                                                    {{ $rightIndex }}</option>
                                            @endforeach
                                        </select>
                                        @error("answers.{$index}.correct")
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- General validation error -->
                    @error('matchTheFollowing')
                        <div class="col-12 mt-2">
                            <div class="alert alert-danger">{{ $message }}</div>
                        </div>
                    @enderror

                    <div class="col-md-12 mt-4" wire:ignore x-data="quillEditor('description', @js($description))">
                        {!! Form::label('description', 'Rubric', ['class' => 'form-label']) !!}
                        <small class="text-muted">(Hint or solution if any)</small>
                        <div x-ref="editor" style="height: 200px;"></div>
                        {!! Form::hidden('description', $description, ['id' => 'editor-content-description', 'required' => false]) !!}
                    </div>
                @endif


                <div class="offcanvas-footer">
                    <div class="d-flex align-items-center justify-content-end gap-4">
                        <button type="reset" class="btn backbtn" onclick="window.location.reload();">Reset</button>
                        <button type="submit" class="btn btn-primary-gradient rounded-1">Submit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <script>
        // ✅ Move initSelect2 to global scope
        function initSelect2() {
            $(".js-select2").select2({
                closeOnSelect: false,
                placeholder: "Select",
                allowClear: false,
                tags: true
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Initialize Select2
            initSelect2();

            $(".js-select2").on('change', function(e) {
                let chapterId = $(this).val();
                @this.set('chapter_id', chapterId);
            });
        });

        document.addEventListener("livewire:load", function() {
            Livewire.hook('message.processed', () => {
                initSelect2();
            });
        });

        document.addEventListener("change", function(event) {
            if (
                event.target.matches("[wire\\:model='subject_id']") ||
                event.target.matches("[wire\\:model='question_type']") ||
                event.target.matches("[wire\\:model^='options.']") ||
                event.target.matches("[wire\\:model^='tfoptions.']")
            ) {
                setTimeout(initSelect2, 500);
            }
        });

        document.addEventListener('alpine:init', () => {
            Alpine.data('quillEditor', (name, initialContent, editorId) => ({
                quill: null,
                content: initialContent || '',
                editorId: editorId || `editor-content-${name}`,

                init() {
                    this.$nextTick(() => {
                        if (!this.quill && this.$refs.editor) {
                            this.initializeQuill();
                            this.setupEventHandlers();
                        }
                    });

                    this.setupLivewireHooks();
                },

                initializeQuill() {
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

                    if (this.content) {
                        this.quill.root.innerHTML = this.content;
                    }
                },

                setupEventHandlers() {
                    this.quill.on('text-change', () => {
                        this.handleContentChange();
                    });
                },

                setupLivewireHooks() {
                    Livewire.hook('message.processed', (message, component) => {
                        if (!this.quill) return;

                        const input = document.getElementById(this.editorId);
                        if (input && input.value !== this.quill.root.innerHTML) {
                            this.quill.root.innerHTML = input.value;
                            this.content = input.value;
                        }
                    });
                },

                handleContentChange() {
                    this.content = this.quill.root.innerHTML;
                    this.updateLivewire();
                    this.updateHiddenInput();
                    this.validateContent();
                },

                updateLivewire() {
                    const path = this.determineLivewirePath();
                    if (path) {
                        @this.set(path, this.content, false);
                    }
                },

                determineLivewirePath() {
                    const pathMappings = {
                        'question': 'question',
                        'description': 'description',
                        'paragraph': 'paragraph',
                        'paragraph_statement': 'paragraph_statement',
                        'option_': `pictureBasedQuestion.${this.getOptionIndex()}.option`,
                        'questions': `passage.${this.getOptionIndex()}.questions`,
                        'answer': `passage.${this.getOptionIndex()}.answer`,
                        'option_match': `matchTheFollowing.${this.getOptionIndex()}.option_match`,
                        'left_option_': `matchTheFollowing.${this.getOptionIndex()}.option_match`,
                        'right_option_': `matchTheFollowing.${this.getOptionIndex()}.option_match`,
                        'question': 'question',

                    };

                    for (const [prefix, path] of Object.entries(pathMappings)) {
                        if (name.startsWith(prefix)) {
                            return path;
                        }
                    }
                    return null;
                },

                getOptionIndex() {
                    return this.$el.closest('.option-group')?.dataset?.index ||
                        name.split('_').pop() ||
                        0;
                },

                updateHiddenInput() {
                    const input = document.getElementById(this.editorId);
                    if (input) {
                        input.value = this.content;
                        input.dispatchEvent(new Event('input'));
                    }
                },

                validateContent() {
                    const input = document.getElementById(this.editorId);
                    if (input) {
                        input.classList.toggle('is-invalid', !this.content.trim());
                    }
                }
            }));
        });
    </script>

</div>
