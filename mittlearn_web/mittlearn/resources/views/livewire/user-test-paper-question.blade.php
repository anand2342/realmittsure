<div>

    <style>
        .cstmcheck p {
            margin: 0;
        }

        .cstmcheck img {
            max-width: 250px;
            max-height: 200px;
            min-width: 250px;
            min-height: 180px;
            object-fit: cover
        }
    </style>
    {{-- <div wire:poll.1000ms="updateTimer"> --}}
    <div class="row questionsSts">
        <div class="col-lg-3 mb-3">
            <div class="cardBox mb-3">
                <h2 class="fs-7 text-success fw-medium mb-2">{{ $testPaper->title }}</h2>
                <span class="fw-semibold fs-8">{{ $testPaper->description }}
                    <b class="d-block fw-normal text-secondary mt-1 fs-9">({{ $testPaper->Subject->name }})</b>
                </span>
            </div>
            <div class="cardBox">
                <h3 class="fs-7 text-success fw-medium mb-3">Questions</h3>
                <ul class="questionsStatus">
                    @foreach ($questions as $index => $q)
                        <li>
                            <span
                                class="{{ $index == $currentQuestionIndex ? 'attemptQus' : '' }}">{{ $index + 1 }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        {{--  @dump($currentQuestion->question->question_type)  --}}
        <div class="col-lg-9 ps-lg-1">
            <div class="cardBox questionPaper">
                <div class="d-flex flex-wrap gap-3 justify-content-between mb-4">
                    @if (isset($currentQuestion->question->question_type))
                        <span class="fw-semibold cardboxSpan qType">
                            {{ ucfirst(str_replace('-', ' ', $currentQuestion->question->question_type)) ?? '' }}
                        </span>
                    @endif
                    <span wire:ignore class="fw-semibold cardboxSpan" x-data="{
                        seconds: {{ $remainingSeconds }},
                        get formattedTime() {
                            let mins = Math.floor(this.seconds / 60);
                            let secs = this.seconds % 60;
                            return String(mins).padStart(2, '0') + ':' + String(secs).padStart(2, '0');
                        },
                        init() {
                            let interval = setInterval(() => {
                                if (this.seconds > 0) {
                                    this.seconds--;
                                    $wire.updateRemainingSeconds(this.seconds); // ✅ working call
                                }
                                else {
                                    clearInterval(interval);
                                    $wire.handleTimeOver(); // also call backend if needed
                                }
                            }, 1000);
                        }
                    }">
                        Remaining Time:
                        <b class="fw-semibold timer-text" :class="{ 'text-danger': seconds <= 300 }"
                            x-text="formattedTime">
                        </b>
                    </span>

                </div>
                <div class="questionsBx bg-white p-0">
                    @if ($currentQuestion)
                        <!-- Error Message -->
                        @if ($errorMessage)
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="bi bi-exclamation-octagon me-1"></i>
                                {{ $errorMessage }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"
                                    aria-label="Close"></button>
                            </div>
                        @endif
                        @php
                            $question = $currentQuestion->question;
                            $cleanQuestion = strip_tags($question->question, '<span><strong><b><i><u>');
                        @endphp

                        <div class="mb-4">
                            <p class="mb-0">
                                <strong>{{ $currentQuestionIndex + 1 }}.</strong>
                                &nbsp;

                                @if ($question->question_type === 'passage')
                                    @php
                                        $question = $currentQuestion->question;
                                        $additionalData = json_decode($question->additional_data, true);
                                        $paragraph = $additionalData['paragraph'] ?? '';
                                        $paragraphStatement = $additionalData['paragraph_statement'] ?? '';
                                        $questionsAndAnswers = $additionalData['questions_and_answers'] ?? [];
                                    @endphp

                                    <div class="mb-3">

                                        <div><strong>Passage Statement:</strong>{!! $paragraphStatement !!}</div>

                                        <div><strong>Passage:</strong>{!! $paragraph !!}</div>
                                    </div>

                                    @foreach ($questionsAndAnswers as $index => $qa)
                                        <div class="mb-2">
                                            <strong>Q{{ $currentQuestionIndex + 1 }}.{{ $index + 1 }}</strong>:
                                            {!! $qa['question'] !!}
                                            <input type="text"class="form-control mt-1"
                                                wire:model.lazy="userAnswers.{{ $question->id }}.{{ $index }}">

                                        </div>
                                    @endforeach
                                @else
                                    {!! $cleanQuestion !!}
                                @endif
                            </p>
                            <!-- Trigger text -->
                            <strong style="cursor: pointer;" data-bs-toggle="modal" data-bs-target="#hintModal">
                                View Hint :
                            </strong>

                            <!-- Hint Modal -->
                            <div class="modal fade" id="hintModal" tabindex="-1" aria-labelledby="hintModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="hintModalLabel">Hint</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            {!! $question->description !!}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="mcqMain ps-0">
                            @php $question = $currentQuestion->question; @endphp
                            @switch($question->question_type)
                                @case('mcq')
                                @case('tick')
                                    <ul class="questionsUl mb-4">
                                        @foreach ($question->options as $key => $option)
                                            <li>
                                                <span>{{ chr(65 + $key) }}.</span>
                                                <div class="cstmcheck w-100">
                                                    <input type="checkbox" id="option_{{ $option->id }}"
                                                        wire:model="userAnswers.{{ $question->id }}.{{ $option->id }}"
                                                        value="{{ $option->id }}" class="d-none">
                                                    <label for="option_{{ $option->id }}">{!! $option->option_text !!}</label>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @break

                                @case('t/f')
                                    <div>
                                        <ul class="questionsUl mb-4">
                                            @foreach ($question->options as $key => $option)
                                                <li>
                                                    <span>{{ chr(65 + $key) }}.</span>
                                                    <div class="cstmcheck w-100">
                                                        <input type="radio" id="option_{{ $option->id }}"
                                                            wire:model="userAnswers.{{ $question->id }}"
                                                            wire:change="submitAnswer" value="{{ $option->id }}"
                                                            class="d-none">
                                                        <label for="option_{{ $option->id }}">{!! $option->option_text !!}</label>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @break

                                @case('one-word-answer')
                                @case('short-answer-questions')
                                    <div>
                                        <input type="text" class="form-control"
                                            wire:model.lazy="userAnswers.{{ $question->id }}">
                                    </div>
                                @break

                                @case('long-answer-questions')
                                    <div>
                                        <textarea class="form-control" wire:model.lazy="userAnswers.{{ $question->id }}"></textarea>
                                    </div>
                                @break
                            @endswitch
                        </div>

                    @endif

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <button wire:click="prevQuestion" class="text-primary fs-8 fw-semibold border-0"
                            @if ($currentQuestionIndex == 0) disabled @endif>
                            <img src="{{ asset('frontend/images/privious-icon.svg') }}" alt="" width="14"
                                class="me-1"> Previous
                        </button>

                        @if ($currentQuestionIndex == count($questions) - 1)
                            <button type="button" wire:click="nextQuestion" class="btn btn-primary-gradient rounded-1">
                                Submit
                            </button>
                        @else
                            <button wire:click="nextQuestion" class="btn btn-primary-gradient rounded-1">
                                Next
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->

    <div x-data="{ open: @entangle('isModalOpen') }" x-show="open" x-transition @click.away="open = false"
        @keydown.escape.window="open = false" class="modal fade" tabindex="-1" :class="{ 'show d-block': open }"
        style="background: rgba(0, 0, 0, 0.5);">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="text-center">
                        <lottie-player src="{{ asset('frontend/images/conformation.json') }}" loop autoplay
                            style="width: 130px; height: 130px; margin: auto;"
                            background="transparent"></lottie-player>
                        <h6 class="fw-semibold mt-2">Confirm?</h6>
                        <p>Are you sure you want to Submit? Once submitted, you cannot change your answers.</p>
                        <button type="button" class="btn btn-primary-gradient rounded-1"
                            wire:click="submitTest">Yes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
