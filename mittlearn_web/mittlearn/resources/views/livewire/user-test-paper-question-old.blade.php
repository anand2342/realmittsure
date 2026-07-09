<div>
    {{-- <div wire:poll.1000ms="updateTimer"> --}}
    <div>
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

            <div class="col-lg-9 ps-lg-1">
                <div class="cardBox questionPaper">
                    <div class="d-flex flex-wrap gap-3 justify-content-between mb-4">
                        <span class="fw-semibold fs-7">Question: {{ $currentQuestionIndex + 1 }}</span>
                        <span class="fw-semibold fs-7">
                            Remaining Time: <b class="fw-semibold text-black timer-text">
                                {{ gmdate('i:s', $remainingTime) }}
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
                            <div class="mb-4">
                                <p class="mb-4">{!! $currentQuestion->question->question !!}</p>
                            </div>

                            <div class="mcqMain ps-0">
                                <ul class="questionsUl mb-4">
                                    @foreach ($currentQuestion->question->options as $key => $option)
                                        <li>
                                            <span>{{ chr(65 + $key) }}.</span>
                                            <div class="cstmcheck w-100">
                                                <input type="radio" name="question_{{ $currentQuestion->id }}"
                                                    id="option_{{ $option->id }}"
                                                    wire:model="userAnswers.{{ $currentQuestion->question->id }}"
                                                    wire:change="submitAnswer" value="{{ $option->id }}"
                                                    class="d-none" @if (isset($submittedAnswers[$currentQuestion->question->id]) &&
                                                            $submittedAnswers[$currentQuestion->question->id] == $option->id) checked @endif>
                                                <label for="option_{{ $option->id }}">{!! $option->option_text !!}</label>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                        @endif

                        <div class="d-flex justify-content-between align-items-center">
                            <button wire:click="prevQuestion" class="text-primary fs-8 fw-semibold border-0"
                                @if ($currentQuestionIndex == 0) disabled @endif>
                                <img src="{{ asset('frontend/images/privious-icon.svg') }}" alt=""
                                    width="14" class="me-1"> Previous
                            </button>

                            @if ($currentQuestionIndex == count($questions) - 1)
                                <button type="button" wire:click="nextQuestion"
                                    class="btn btn-primary-gradient rounded-1">
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

</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let remainingTime = @json($remainingTime); // Get remaining time in seconds
        const timerDisplay = document.querySelector('.timer-text');
        const timerContainer = document.querySelector('#remainingTimeDisplay');

        function updateTimer() {
            if (remainingTime > 0) {
                remainingTime--;

                let minutes = Math.floor(remainingTime / 60);
                let seconds = remainingTime % 60;
                timerDisplay.textContent =
                    `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;

                if (remainingTime <= 300) { // If less than 5 minutes
                    timerDisplay.style.color = "red";
                    timerContainer.classList.toggle("blinkTimer"); // BlinkTimering effect
                }

                setTimeout(updateTimer, 1000);
            }
        }

        updateTimer();
    });
</script>
