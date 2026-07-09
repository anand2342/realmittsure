<div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form wire:submit.prevent="save" class="row g-3" id="question-form">
                        <h5 class="card-title pb-0">Test Paper Question</h5>
                        <hr class="form-divider">
                        {!! Form::hidden('testPaperId', $id, ['wire:model' => 'testPaperId']) !!}

                        <!-- Question Type -->
                        <div class="col-md-6">
                            {!! Form::label('question_type', 'Question Type', ['class' => 'form-label required']) !!}
                            {!! Form::select('question_type', $question_types, null, [
                                'class' => 'form-control form-select',
                                'placeholder' => '--Select--',
                                'wire:model' => 'question_type',
                                'wire:change' => 'questionTypeChanged',
                                'required',
                            ]) !!}
                        </div>

                        <!-- Difficulty Level -->
                        <div class="col-md-6">
                            {!! Form::label('difficulty_level', 'Difficulty Level', ['class' => 'form-label required']) !!}
                            {!! Form::select('difficulty_level', config('constants.DIFFICULTY_LEVEL'), null, [
                                'class' => 'form-control form-select',
                                'placeholder' => '--Select--',
                                'wire:model' => 'difficulty_level',
                                'required',
                            ]) !!}
                        </div>

                        <!-- Marks -->
                        <div class="col-md-6">
                            {!! Form::label('marks', 'Marks', ['class' => 'form-label required']) !!}
                            {!! Form::number('marks', null, [
                                'class' => 'form-control',
                                'wire:model' => 'marks',
                                'placeholder' => 'Marks of Question',
                                'required' => true,
                            ]) !!}
                        </div>

                        <!-- Status -->
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            {!! Form::label('status', 'Status', ['class' => 'form-label required']) !!}
                            {!! Form::select('status', config('constants.STATUS_LIST'), null, [
                                'class' => 'form-control form-select fs-8',
                                'wire:model' => 'status',
                                'placeholder' => '--Select--',
                                'required',
                            ]) !!}
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
                                                <span class="text-danger"
                                                    style="font-size: 13px;">{{ $message }}</span>
                                            @enderror

                                            <div class="d-flex justify-content-between align-items-center mt-2">
                                                <label class="form-label">
                                                    <input type="radio" name="correct" value="{{ $tfindex }}"
                                                        wire:model="correct"
                                                        wire:change="setCorrectOption({{ $tfindex }})">
                                                    Correct
                                                </label>

                                                @if ($tfindex > 0)
                                                    <button type="button"
                                                        wire:click="removeTfOption({{ $tfindex }})"
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
                                                    <span class="text-danger"
                                                        style="font-size: 13px;">{{ $message }}</span>
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
                                                    <button type="button"
                                                        wire:click="removeTextEditor({{ $index }})"
                                                        class="btn btn-danger btn-sm">
                                                        Remove
                                                    </button>
                                                @endif
                                            </div>
                                            @error('pictureBasedQuestion')
                                                <div class="col-md-12 mt-1">
                                                    <span class="text-danger"
                                                        style="font-size: 13px;">{{ $message }}</span>
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
                                                <span class="text-danger"
                                                    style="font-size: 13px;">{{ $message }}</span>
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
                                                <span class="text-danger"
                                                    style="font-size: 13px;">{{ $message }}</span>
                                            </div>
                                        @enderror

                                        @if ($index > 0)
                                            <div class="col-md-6">
                                                {!! Form::hidden("passage[$index][id]", $option['id'] ?? null, [
                                                    'wire:model.defer' => "passage[$index].id",
                                                ]) !!}
                                                <button type="button"
                                                    wire:click="removePassage({{ $index }})"
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
                                                    wire:model="matchTheFollowing.{{ $index }}.option_match"
                                                    required>
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
                                                    wire:model="matchTheFollowing.{{ $actualIndex }}.option_match"
                                                    required>
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
                        <div class="col-sm-12 text-end">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <button type="reset" class="btn btn-secondary"
                                onclick="window.location.reload();">Reset</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- <div id="toolbar">
        <button id="latex-button">Insert LaTeX</button>
    </div>

    <!-- Editor -->
    <div id="editor-container"></div> --}}
    {{-- <script>
        document.addEventListener("DOMContentLoaded", function() {
            const quill = new Quill("#editor-container", {
                theme: "snow",
                modules: {
                    toolbar: [
                        [{
                            script: "sub"
                        }, {
                            script: "super"
                        }],
                        ["bold", "italic", "underline"]
                    ],
                },
            });

            const languageSelector = document.getElementById("language");
            const insertButton = document.getElementById("insert-text");

            // Custom LaTeX Blot
            const BlockEmbed = Quill.import("blots/block/embed");

            class LatexBlot extends BlockEmbed {
                static create(value) {
                    const node = super.create();
                    katex.render(value, node, {
                        throwOnError: false
                    });
                    node.setAttribute("data-latex", value);
                    return node;
                }

                static value(node) {
                    return node.getAttribute("data-latex");
                }
            }

            LatexBlot.blotName = "latex";
            LatexBlot.tagName = "div";
            Quill.register(LatexBlot);

            // LaTeX Insert Button
            document.getElementById("latex-button").addEventListener("click", function() {
                const latex = prompt("Enter LaTeX code (e.g., E = mc^2):");
                if (latex) {
                    const range = quill.getSelection();
                    quill.insertEmbed(range.index, "latex", latex);
                }
            });


            insertButton.addEventListener("click", function() {
                const selectedLanguage = languageSelector.value;
                const range = quill.getSelection();
                let textToInsert = "";

                if (selectedLanguage === "hindi") {
                    textToInsert = "यह हिंदी टेक्स्ट क्विल एडिटर में जोड़ा गया है।";
                    quill.formatText(range.index, textToInsert.length, "font", "hindi-text");
                } else {
                    textToInsert = "This is English text added to the Quill editor.";
                }

                if (range) {
                    quill.insertText(range.index, textToInsert, "bold", true);
                }
            });
        });
    </script> --}}
    <script>
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
                        'question': 'question',
                        'paragraph_statement': 'paragraph_statement',
                        'option_': `pictureBasedQuestion.${this.getOptionIndex()}.option`,
                        'questions': `passage.${this.getOptionIndex()}.questions`,
                        'answer': `passage.${this.getOptionIndex()}.answer`,
                        'option_match': `matchTheFollowing.${this.getOptionIndex()}.option_match`,
                        'left_option_': `matchTheFollowing.${this.getOptionIndex()}.option_match`,
                        'right_option_': `matchTheFollowing.${this.getOptionIndex()}.option_match`
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
