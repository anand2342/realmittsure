@extends('schoolPortal.layouts.master')
@section('content')
    <style>
        .suggested-answer-box {
            display: inline-flex;
            align-items: flex-start;
            gap: 6px;
            background-color: #eaf4ff;
            border-left: 3px solid #044783;
            border-radius: 4px;
            padding: 6px 12px;
            font-size: 13px;
            color: #333;
            max-width: 100%;
        }

        .answer-label {
            font-weight: 600;
            color: #044783;
            white-space: nowrap;
        }

        .answer-text {
            color: #444;
        }

        .answer-text p {
            margin: 0;
        }
    </style>
    @include('admin.layouts.flash-messages')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-3">
            <li class="breadcrumb-item">
                <a href="{{ url()->previous() }}">Test Paper</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">View Test Paper</li>
        </ol>
    </nav>
    <div class="cardBox  questionPaper">
        <div class="classDetail mb-4">
            <div class="d-flex flex-wrap gap-3 justify-content-between">
                <h6 class="fs-6  fw-semibold">{{ $testPaper->Class->name }} - {{ $testPaper->title }} <b
                        class="fw-normal fs-8">( {{ $testPaper->Subject->name }})</b></h6>
                <span class="fw-semibold">
                    Total Marks: <span class="total-marks-value">{{ $totalMarks }}.00</span>
                </span>
            </div>
            <p>{{ $testPaper->description }}</p>
        </div>
        @forelse ($questions as $item)
            @if ($item->Question)
                @if ($item->Question->question_type == 'mcq')
                    <div class="questionsBx">
                        <div class="d-md-flex gap-3 justify-content-between align-items-center mb-4">
                            <div class="d-flex gap-3">
                                <span>Q.{{ $loop->iteration }}</span>
                                <p class="mb-0">{!! $item->Question->question !!}</p>
                            </div>
                            @if (!in_array($testPaper->id, $testParticipent))
                                @if (getUserRoles() === 'school_admin' &&
                                        $item->Question->created_by != $item->Question->school_id &&
                                        !is_null($item->Question->school_id))
                                    <label class="switch toggleSwitch">
                                        <input type="checkbox" @if ($item->Question->is_approved == 1) checked @endif
                                            data-question-id="{{ $item->Question->id }}" class="approval-checkbox" />
                                        <span class="move"></span>
                                        <span class="conditionOn">Reject</span>
                                        <span class="conditionOff">Approve</span>
                                    </label>
                                @endif
                            @else
                                <p class="text-danger mb-0">
                                    This question is assigned to the Students.
                                </p>
                            @endif
                            <span class="question-marks d-none" data-marks="{{ $item->Question->marks }}"></span>

                        </div>
                        <div class="mcqMain">
                            <ul class="questionsUl mb-4">
                                @foreach ($item->Question->options as $option)
                                    <li><span>{{ chr(64 + $loop->iteration) }}.</span>
                                        <div class="cstmcheck w-100 ">
                                            <input type="checkbox" name="checkbox1 {{ $loop->parent->iteration }}"
                                                class="d-none"
                                                id="checkboxCheck1{{ $loop->parent->iteration }}-{{ $loop->iteration }}"@if ($option->is_correct == 1) checked @endif
                                                @disabled(true)><label
                                                for="checkboxCheck1{{ $loop->parent->iteration }}-{{ $loop->iteration }}">{!! $option->option_text !!}</label>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        {{--  @if (getUserRoles() === 'school_admin' && $item->Question->created_by != $item->Question->school_id && !is_null($item->Question->school_id))
                            <label class="switch toggleSwitch">
                                <input type="checkbox" @if ($item->Question->is_approved == 1) checked @endif
                                    data-question-id="{{ $item->Question->id }}" class="approval-checkbox" />
                                <span class="move"></span>
                                <span class="conditionOn">Reject</span>
                                <span class="conditionOff">Approve</span>
                            </label>
                        @endif  --}}
                    </div>
                @elseif($item->Question->question_type == 't/f')
                    <div class="questionsBx">
                        <div class="d-md-flex gap-3 justify-content-between align-items-center mb-4">
                            <div class="d-flex gap-3">
                                <span>Q.{{ $loop->iteration }}</span>
                                <p class="mb-0">{!! $item->Question->question !!}</p>
                            </div>
                            @if (!in_array($testPaper->id, $testParticipent))
                                @if (getUserRoles() === 'school_admin' &&
                                        $item->Question->created_by != $item->Question->school_id &&
                                        !is_null($item->Question->school_id))
                                    <label class="switch toggleSwitch">
                                        <input type="checkbox" @if ($item->Question->is_approved == 1) checked @endif
                                            data-question-id="{{ $item->Question->id }}" class="approval-checkbox" />
                                        <span class="move"></span>
                                        <span class="conditionOn">Reject</span>
                                        <span class="conditionOff">Approve</span>
                                    </label>
                                @endif
                            @else
                                <p class="text-danger mb-0">
                                    This question is assigned to the Students.
                                </p>
                            @endif
                            <span class="question-marks d-none" data-marks="{{ $item->Question->marks }}"></span>
                        </div>
                        <div class="mcqMain">
                            <ul class="questionsUl mb-4">
                                @foreach ($item->Question->options as $option)
                                    <li>
                                        <span>{{ chr(64 + $loop->iteration) }}.</span>
                                        <div class="cstmcheck w-100">
                                            <input type="radio" name="radio{{ $loop->parent->iteration }}" class="d-none"
                                                id="radioCheck{{ $loop->parent->iteration }}-{{ $loop->iteration }}"
                                                @if ($option->is_correct == 1) checked @endif
                                                @disabled(true)>
                                            <label
                                                for="radioCheck{{ $loop->parent->iteration }}-{{ $loop->iteration }}">{!! $option->option_text !!}</label>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @elseif($item->Question->question_type == 'passage')
                    @php
                        $passage = json_decode($item->Question->additional_data);
                    @endphp
                    <div class="questionsBx">
                        <span>Q.{{ $loop->iteration }}</span>
                        @if (isset($passage->paragraph) && $passage->paragraph)
                            <div class="passage-content mb-4">
                                {!! $passage->paragraph !!}
                            </div>
                        @endif
                        @if (isset($passage->paragraph_statement) && $passage->paragraph_statement)
                            <div class="passage-statement mb-3">
                                {!! $passage->paragraph_statement !!}
                            </div>
                        @endif
                        @if (!in_array($testPaper->id, $testParticipent))
                            @if (getUserRoles() === 'school_admin' &&
                                    $item->Question->created_by != $item->Question->school_id &&
                                    !is_null($item->Question->school_id))
                                <label class="switch toggleSwitch">
                                    <input type="checkbox" @if ($item->Question->is_approved == 1) checked @endif
                                        data-question-id="{{ $item->Question->id }}" class="approval-checkbox" />
                                    <span class="move"></span>
                                    <span class="conditionOn">Reject</span>
                                    <span class="conditionOff">Approve</span>
                                </label>
                            @endif
                        @else
                            <p class="text-danger mb-0">
                                This question is assigned to the Students.
                            </p>
                        @endif
                        <span class="question-marks d-none" data-marks="{{ $item->Question->marks }}"></span>
                        @if (isset($passage->questions_and_answers) && count($passage->questions_and_answers) > 0)
                            @foreach ($passage->questions_and_answers as $index => $qa)
                                <div
                                    class="d-md-flex gap-3 justify-content-between align-items-center mb-4 ms-3 @if ($index === 0) mt-2 @endif">
                                    <div class="d-flex gap-3">
                                        <span>Q.{{ chr(64 + $loop->iteration) }}</span>
                                        <p class="mb-0">{!! $qa->question !!}</p>
                                    </div>
                                    <span class="question-marks d-none" data-marks="1"></span>
                                </div>
                            @endforeach
                        @endif
                    </div>
                @else
                    <div class="questionsBx">
                        <div class="d-md-flex gap-3 justify-content-between align-items-center mb-4">
                            <div class="d-flex gap-3">
                                <span>Q.{{ $loop->iteration }}</span>
                                <p class="mb-0">{!! $item->Question->question !!}</p>
                                @if (!empty($item->Question->suggested_answer) && trim(strip_tags($item->Question->suggested_answer)))
                                    <div class="suggested-answer-box mt-2">
                                        <span class="answer-label">
                                            <i class="bi bi-check-circle-fill me-1"></i>Answer:
                                        </span>
                                        <span class="answer-text">{!! $item->Question->suggested_answer !!}</span>
                                    </div>
                                @endif
                            </div>
                            @if (!in_array($testPaper->id, $testParticipent))
                                @if (getUserRoles() === 'school_admin' &&
                                        $item->Question->created_by != $item->Question->school_id &&
                                        !is_null($item->Question->school_id))
                                    <label class="switch toggleSwitch">
                                        <input type="checkbox" @if ($item->Question->is_approved == 1) checked @endif
                                            data-question-id="{{ $item->Question->id }}" class="approval-checkbox" />
                                        <span class="move"></span>
                                        <span class="conditionOn">Reject</span>
                                        <span class="conditionOff">Approve</span>
                                    </label>
                                    <span class="question-marks d-none" data-marks="{{ $item->Question->marks }}"></span>
                                @endif
                            @else
                                <p class="text-danger mb-0">
                                    This question is assigned to the Students.
                                </p>
                            @endif
                            <span class="question-marks d-none" data-marks="{{ $item->Question->marks }}"></span>
                        </div>
                    </div>
                @endif
            @endif
        @empty
            <p style="color: #d9534f; font-weight: bold; text-align: center;">No Questions Found.</p>
        @endforelse

    </div>

    <script>
        $(document).ready(function() {
            $('.approval-checkbox').on('change', function() {
                var questionId = $(this).data('question-id');
                var isApproved = $(this).prop('checked') ? 1 : 0;

                // Send AJAX to update approve/reject status
                $.ajax({
                    url: '{{ route('update.approval.status') }}',
                    method: 'POST',
                    data: {
                        question_id: questionId,
                        is_approved: isApproved,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // Update total marks after AJAX success
                        updateTotalMarks();
                    }
                });
            });

            function updateTotalMarks() {
                var total = 0;
                $('.questionsBx').each(function() {
                    var marks = parseFloat($(this).find('.question-marks').data('marks')) || 0;
                    var checkbox = $(this).find('.approval-checkbox');

                    if (checkbox.length) {
                        if (checkbox.is(':checked')) {
                            total += marks; // Approved → Add Marks
                        } else {
                            total -= marks; // Rejected → Subtract Marks
                        }
                    } else {
                        total += marks; // No approval checkbox (Assigned Questions) → Always Add Marks
                    }
                });

                $('.total-marks-value').text(total.toFixed(2)); // Show with 2 decimal places if needed
            }



        });
    </script>

@endsection
