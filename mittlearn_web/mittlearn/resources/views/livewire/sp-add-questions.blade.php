<div>
    @if (session()->has('successMsg'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-1"></i>
            @if (session('successMsg'))
                {{ session('successMsg') }}
            @endif
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    <div class="cardBox teacherMain pt-md-4 pb-0  mb-3">
        <div class="row ">
            <div class="col-md-12 mb-3">
                <div class="teacherLeft">
                    <h5 class="fw-semibold mb-3">Test Paper Questions</h5>
                    <hr class="form-divider">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    {!! Form::label('question_setup', 'Question Setup', [
                        'class' => 'form-label',
                        'style' => 'font-size:14px; color:#044783; font-weight:500; padding-bottom:10px;',
                    ]) !!} {!! Form::select('question_setup', config('constants.QUESTIONSETUP') ?? [], null, [
                        'class' => 'form-control form-select',
                        'placeholder' => '--Select--',
                        'wire:model' => 'questionSetup',
                        'wire:change' => 'getQuestions',
                        'required',
                    ]) !!}
                </div>
            </div>
            <hr class="border-secondary my-4">
            @if ($questionSetup == 'auto' && !empty($questionSummary))
                <div class="table-responsive tbleDiv">
                    <div class="d-flex justify-content-end align-items-center mb-2">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="selectAll" wire:model="selectAll">
                            <label class="form-check-label fw-medium" for="selectAll" style="color:#044783;">
                                ✓ Add All
                            </label>
                        </div>
                    </div>

                    <table class="table table-bordered align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Question Type</th>
                                <th>No. of Questions to Add</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($questionSummary as $index => $summary)
                                <tr>
                                    <td>{{ ucwords(str_replace('-', ' ', $summary['type'])) }}</td>
                                    <td>
                                        <select wire:model.defer="questionSummary.{{ $index }}.count"
                                            class="form-select" style="width:100px;">
                                            @for ($i = 0; $i <= $summary['count']; $i++)
                                                <option value="{{ $i }}" @selected($summary['questionsInTest'] == $i)>
                                                    {{ $i }}</option>
                                            @endfor
                                        </select>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="d-flex justify-content-center mt-3">
                        <button wire:click="addQuestions" class="btn btn-primary-gradient rounded-1">
                            Add Questions
                        </button>
                    </div>
                </div>

            @endif


            @if ($questionSetup == 'manual')
                <div class="px-3 py-2">
                    <div class="table-responsive tbleDiv">
                        <form action="{{ route('assign.test.questions') }}" method="POST">
                            @csrf
                            <table class="table mb-0" id="question-table">
                                <thead>
                                    <tr>
                                        <th><input type="checkbox" id="selectAll"></th>
                                        <!-- Add select all checkbox -->
                                        <th>S. No.</th>
                                        <th>Question</th>
                                        <th>Marks</th>
                                        <th>Question Type</th>
                                        <th>Diff. Level</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <input type="hidden" name="test_id" value="{{ $testPaperId }}">
                                    @forelse ($questionBank ?? [] as $item)
                                        <tr data-name="{{ $item->question }}">
                                            <td>
                                                <input type="checkbox" class="question-checkbox"
                                                    name="selected_questions[]" value="{{ $item->id }}"
                                                    id="question_{{ $item->id }}"
                                                    @if ($testPaperQuestions->contains('question_id', $item->id)) checked @endif>
                                            </td>

                                            <td>{{ $loop->iteration }}.</td>
                                            <td>{{ strip_tags(Str::limit($item->question, 20)) }}</td>
                                            <td>{{ $item->marks }}</td>
                                            <td>{{ Str::upper($item->question_type) }}</td>
                                            <td>{{ config('constants.DIFFICULTY_LEVEL')[$item->difficulty_level] ?? '' }}
                                            </td>
                                            <td>
                                                <!-- ... existing action dropdown code ... -->
                                            </td>
                                        </tr>
                                        <!-- ... existing modal code ... -->
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No Questions Found</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="offcanvas-footer">
                                <div class="d-flex align-items-center justify-content-end gap-4">
                                    <button type="button" class="btn backbtn"
                                        onclick="window.history.back()">Back</button>
                                    <button type="submit" class="btn btn-primary-gradient rounded-1">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
            @if ($questionSetup == 'add_new')
                <div class="formPanel">
                    <form wire:submit.prevent="save" class="row g-3" id="question-form">
                        {!! Form::hidden('testPaperId', $id, ['wire:model' => 'testPaperId']) !!}


                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('question_type', 'Question Type', ['class' => 'form-label required']) !!}
                                {!! Form::select('question_type', $question_types ?? [], null, [
                                    'class' => 'form-control form-select',
                                    'placeholder' => '--Select--',
                                    'wire:model' => 'question_type',
                                    'wire:change' => 'questionTypeChanged',
                                    'required',
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('difficulty_level', 'Difficulty Level', ['class' => 'form-label required']) !!}
                                {!! Form::select('difficulty_level', config('constants.DIFFICULTY_LEVEL'), null, [
                                    'class' => 'form-control form-select',
                                    'placeholder' => '--Select--',
                                    'wire:model' => 'difficulty_level',
                                    'required',
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('marks', 'Marks', ['class' => 'form-label required']) !!}
                                {!! Form::number('marks', null, [
                                    'class' => 'form-control',
                                    'wire:model' => 'marks',
                                    'placeholder' => 'Marks of Question',
                                    'required' => true,
                                ]) !!}
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                {!! Form::label('status', 'Status', ['class' => 'form-label required']) !!}
                                {!! Form::select('status', config('constants.STATUS_LIST'), null, [
                                    'class' => 'form-control form-select fs-8',
                                    'wire:model' => 'status',
                                    'placeholder' => '--Select--',
                                    'required',
                                ]) !!}
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

                            @if (in_array($question_type, ['one-word-answer', 'short-answer-questions', 'long-answer-questions', 'fill-ups']))
                                <div class="col-md-12 mt-4" wire:ignore x-data="quillEditor('suggested_answer', @js($suggested_answer))">
                                    {!! Form::label('suggested_answer', 'Suggested Answer for Teacher', ['class' => 'form-label required']) !!}
                                    <div x-ref="editor" style="height: 200px;"></div>
                                    {!! Form::hidden('suggested_answer', $suggested_answer, [
                                        'id' => 'editor-content-suggested_answer',
                                        'required' => true,
                                    ]) !!}
                                </div>
                            @endif

                            <div class="col-md-12 mt-4" wire:ignore x-data="quillEditor('description', @js($description))">
                                <div class="form-group">
                                    {!! Form::label('description', 'Rubric', ['class' => 'form-label']) !!}
                                    <small class="text-muted">(Hint or solution if any)</small>
                                    <div x-ref="editor" style="height: 200px;"></div>
                                    {!! Form::hidden('description', $description, ['id' => 'editor-content-description', 'required' => false]) !!}
                                </div>
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
                                <div class="form-group">
                                    {!! Form::label('description', 'Rubric', ['class' => 'form-label']) !!}
                                    <small class="text-muted">(Hint or solution if any)</small>
                                    <div x-ref="editor" style="height: 200px;"></div>
                                    {!! Form::hidden('description', $description, ['id' => 'editor-content-description', 'required' => false]) !!}
                                </div>
                            </div>
                        @endif

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
                                <div class="form-group">
                                    {!! Form::label('description', 'Rubric', ['class' => 'form-label']) !!}
                                    <small class="text-muted">(Hint or solution if any)</small>
                                    <div x-ref="editor" style="height: 200px;"></div>
                                    {!! Form::hidden('description', $description, ['id' => 'editor-content-description', 'required' => false]) !!}
                                </div>
                            </div>
                        @endif

                        @if ($question_type === 'passage')
                            <div class="row">
                                {{-- Paragraph --}}
                                <div class="col-md-6">
                                    <div wire:ignore x-data="quillEditor('paragraph', @js($paragraph))">
                                        {!! Form::label('paragraph', 'Paragraph', ['class' => 'form-label required']) !!}
                                        <div x-ref="editor" style="height: 200px;"></div>
                                        {!! Form::hidden('paragraph', $paragraph, ['id' => 'editor-content-paragraph', 'required' => true]) !!}
                                    </div>
                                    @error('paragraph')
                                        <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                                    @enderror
                                </div>

                                {{-- Paragraph Statement --}}
                                <div class="col-md-6">
                                    <div wire:ignore x-data="quillEditor('paragraph_statement', @js($paragraph_statement))">
                                        {!! Form::label('paragraph_statement', 'Paragraph Statement', ['class' => 'form-label required']) !!}
                                        <div x-ref="editor" style="height: 200px;"></div>
                                        {!! Form::hidden('paragraph_statement', $paragraph_statement, [
                                            'id' => 'editor-content-paragraph_statement',
                                            'required' => true,
                                        ]) !!}
                                    </div>
                                    @error('paragraph_statement')
                                        <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>


                            <div class="col-md-12">
                                @foreach ($passage as $index => $option)
                                    <div class="option-group row  mt-3 " data-index="{{ $index }}">

                                        {{-- Question Editor --}}
                                        <div class="col-md-6">
                                            <div wire:ignore x-data="quillEditor('questions', @js($option['questions']))">
                                                {!! Form::label('questions', 'Question ' . ($index + 1), ['class' => 'form-label']) !!}
                                                <div x-ref="editor" style="height: 200px;"></div>
                                                {!! Form::hidden('options[' . $index . '][questions]', $option['questions'], [
                                                    'id' => 'editor-content-questions-' . $index,
                                                    'required' => true,
                                                ]) !!}
                                            </div>
                                            @error("passage.{$index}.questions")
                                                <span class="text-danger"
                                                    style="font-size: 13px;">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        {{-- Answer Editor --}}
                                        <div class="col-md-6">
                                            <div wire:ignore x-data="quillEditor('answer', @js($option['answer']))">
                                                {!! Form::label('answer', 'Answer ' . ($index + 1), ['class' => 'form-label']) !!}
                                                <div x-ref="editor" style="height: 200px;"></div>
                                                {!! Form::hidden('options[' . $index . '][answer]', $option['answer'], [
                                                    'id' => 'editor-content-answer-' . $index,
                                                    'required' => true,
                                                ]) !!}
                                            </div>
                                            @error("passage.{$index}.answer")
                                                <span class="text-danger"
                                                    style="font-size: 13px;">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        {{-- Remove button and ID --}}
                                        @if ($index > 0)
                                            <div class="col-md-12">
                                                {!! Form::hidden("passage[$index][id]", $option['id'] ?? null) !!}
                                                <button type="button"
                                                    wire:click="removePassage({{ $index }})"
                                                    class="btn btn-danger btn-sm mt-2">
                                                    Remove
                                                </button>
                                            </div>
                                        @endif

                                    </div>
                                @endforeach

                                {{-- Add More Option Button --}}
                                <div class="mt-3">
                                    <button type="button" wire:click="addPassage" class="btn btn-success">
                                        Add More Option
                                    </button>
                                </div>
                            </div>

                            <div class="col-md-12 mt-4" wire:ignore x-data="quillEditor('description', @js($description))">
                                <div class="form-group">
                                    {!! Form::label('description', 'Rubric', ['class' => 'form-label']) !!}
                                    <small class="text-muted">(Hint or solution if any)</small>
                                    <div x-ref="editor" style="height: 200px;"></div>
                                    {!! Form::hidden('description', $description, ['id' => 'editor-content-description', 'required' => false]) !!}
                                </div>
                            </div>
                        @endif


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
                                <div class="form-group">
                                    {!! Form::label('description', 'Rubric', ['class' => 'form-label']) !!}
                                    <small class="text-muted">(Hint or solution if any)</small>
                                    <div x-ref="editor" style="height: 200px;"></div>
                                    {!! Form::hidden('description', $description, ['id' => 'editor-content-description', 'required' => false]) !!}
                                </div>
                            </div>
                        @endif

                        <div class="offcanvas-footer">
                            <div class="d-flex align-items-center justify-content-end gap-4">
                                <button type="Submit" class="btn btn-primary-gradient rounded-1">Submit</button>
                                <button type="reset" class="btn btn-secondary"
                                    onclick="window.location.reload();">Reset</button>

                            </div>
                        </div>
                    </form>
                </div>
            @endif
        </div>
        <hr class="border-secondary my-4">
        <div>
            <div class="text-end mb-3">
                <button class="btn btn-primary-gradient rounded-1" wire:click="preview">Preview</button>
            </div>

            @if ($showModal)
                <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,0.5);">
                    <div class="modal-dialog modal-xl" style="max-width:90%;">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Test Paper Preview</h5>
                                <button type="button" class="btn-close" wire:click="closeModal"></button>
                            </div>
                            <div class="modal-body">
                                <iframe src="{{ $previewUrl }}" width="100%" height="600px"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Select all checkbox functionality
            const selectAll = document.getElementById('selectAll');
            const checkboxes = document.querySelectorAll('.question-checkbox');

            selectAll.addEventListener('change', function() {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = selectAll.checked;
                });
            });

            // Uncheck "select all" if any checkbox is unchecked
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    if (!this.checked) {
                        selectAll.checked = false;
                    } else {
                        // Check if all checkboxes are checked
                        const allChecked = Array.from(checkboxes).every(cb => cb.checked);
                        selectAll.checked = allChecked;
                    }
                });
            });
        });
    </script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('quillEditor', (name, initialContent) => ({
                quill: null,
                content: initialContent || '',
                editorId: `editor-content-${name}`,
                errorMessage: null,

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
                        theme: "snow",
                        placeholder: `Enter ${name.replace('_', ' ')} here...`
                    });

                    if (this.content) {
                        this.quill.root.innerHTML = this.content;
                    }

                    this.quill.container.classList.add('quill-container');
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

                        this.updateErrorState();
                    });
                },

                handleContentChange() {
                    this.content = this.quill.root.innerHTML;
                    this.updateHiddenInput();
                    this.updateLivewire();
                    this.validateContent();
                },

                updateHiddenInput() {
                    const input = document.getElementById(this.editorId);
                    if (input) {
                        input.value = this.content;
                        input.dispatchEvent(new Event('input'));
                    }
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

                    // First try exact matches
                    if (pathMappings[name]) {
                        return pathMappings[name];
                    }

                    // Then try prefix matches
                    for (const [prefix, path] of Object.entries(pathMappings)) {
                        if (name.startsWith(prefix)) {
                            return path;
                        }
                    }

                    // Fallback for other fields
                    return name;
                },

                getOptionIndex() {
                    const optionGroup = this.$el.closest('.option-group');
                    if (optionGroup) {
                        return optionGroup.dataset.index;
                    }

                    // Extract index from field name if format is like "option_1"
                    const match = name.match(/_(\d+)$/);
                    return match ? match[1] : 0;
                },

                validateContent() {
                    const isValid = this.content.trim() !== '';
                    this.quill.container.classList.toggle('is-invalid', !isValid);

                    if (isValid && this.errorMessage === 'This field is required') {
                        this.errorMessage = null;
                        this.hideValidationError();
                    }
                },

                updateErrorState() {
                    const errorElement = this.findErrorElement();
                    if (errorElement) {
                        this.errorMessage = errorElement.textContent;
                        this.showValidationError();
                    } else {
                        this.errorMessage = null;
                        this.hideValidationError();
                    }
                },

                findErrorElement() {
                    // First check for error message in the same container
                    const container = this.$el;
                    let errorElement = container.querySelector('.text-danger');

                    if (!errorElement) {
                        // For option groups, check the parent container
                        const optionGroup = this.$el.closest('.option-group');
                        if (optionGroup) {
                            const index = optionGroup.dataset.index;
                            errorElement = document.querySelector(
                                `[wire\\:key="passage.${index}.${name}-error"]`);
                        }
                    }

                    if (!errorElement) {
                        // Fallback to Livewire's error element
                        errorElement = document.querySelector(`[wire\\:key="${name}-error"]`);
                    }

                    return errorElement;
                },

                showValidationError() {
                    if (!this.quill || !this.errorMessage) return;

                    this.quill.container.classList.add('is-invalid');

                    let tooltip = this.quill.container.querySelector('.invalid-tooltip');
                    if (!tooltip) {
                        tooltip = document.createElement('div');
                        tooltip.className = 'invalid-tooltip';
                        this.quill.container.appendChild(tooltip);
                    }
                    tooltip.textContent = this.errorMessage;
                },

                hideValidationError() {
                    if (!this.quill) return;

                    this.quill.container.classList.remove('is-invalid');
                    const tooltip = this.quill.container.querySelector('.invalid-tooltip');
                    if (tooltip) {
                        tooltip.remove();
                    }
                }
            }));
        });
    </script>
</div>
