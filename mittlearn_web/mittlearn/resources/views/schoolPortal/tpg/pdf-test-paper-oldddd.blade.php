<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Test Paper</title>
    <style>
        /* Small allowed CSS: page size. All layout styling is inline. */
        @page {
            size: A3;
            margin: 1.5cm;
        }

        /* body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12pt;
            margin: 0;
            padding: 0;
        } */

        body {
            font-family: 'notosansdevanagari', sans-serif;
            font-size: 12pt;
        }
    </style>
</head>

<body>

    <!-- ========= HEADER (table-based) ========= -->
    <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse; margin-bottom:12px; width:100%;">
        <tr>
            <!-- Logo left -->
            <td width="120" valign="middle" style="padding:0;">
                @if (!empty($paper->logo) && Storage::disk('public')->exists($paper->logo))
                    <img src="{{ storage_path('app/public/' . $paper->logo) }}" alt="School Logo"
                        style="height: {{ $logoHeight }}px; object-fit:contain; display:block;">
                @endif
            </td>

            <!-- Center -->
            <td valign="middle" style="text-align:center; padding:0 10px;">
                <div style="font-size:18pt; font-weight:700; line-height:1.1;">{{ $schoolName }}</div>
                <div style="font-size:15pt; font-weight:700; margin-top:6px;">{{ $paper->test_term }}</div>
                <div style="margin-top:8px; font-size:11pt;">
                    <span style="margin-right:28px;">CLASS: {{ $className }}</span>
                    <span>SUBJECT: {{ $subjectName }}</span>
                </div>
            </td>

            <!-- Right empty for symmetry -->
            <td width="120" valign="middle" style="padding:0;"></td>
        </tr>
    </table>

    <!-- Header bottom: duration and marks -->
    <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse; margin-bottom:12px;">
        <tr>
            <td style="text-align:left; font-size:12pt; padding:2px 0;">
                Duration: {{ number_format($paper->duration / 60, 1) }} hours
            </td>
            <td style="text-align:right; font-size:12pt; padding:2px 0;">
                Total Marks: {{ $totalMarks }}
            </td>
        </tr>
    </table>
    

    <!-- Roll no and Date -->
    <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse; margin-bottom:10px;">
        <tr>
            <td style="padding:4px 0;">Roll No.: _______________________</td>
            <td style="text-align:right; padding:4px 0;">Date: _______________________</td>
        </tr>
    </table>

    <!-- Instructions box -->
    <table width="100%" cellpadding="6" cellspacing="0"
        style="border-collapse:collapse; border:1px solid #000; margin-bottom:14px; background-color:#ffffff;">
        <tr>
            <td style="font-weight:700; font-size:12pt;">General Instructions:</td>
        </tr>
        <tr>
            <td style="font-size:11pt; padding-top:4px;">{{ $paper->description }}</td>
        </tr>
    </table>

    <!-- ========== SECTIONS & QUESTIONS ========== -->
    @foreach ($questions as $type => $group)
        @php
            $sectionTitle = match ($type) {
                'mcq' => 'Multiple Choice Questions',
                't/f' => 'True or False',
                'fill-ups' => 'Fill in the Blanks',
                'one-word-answer' => 'One Word Answers',
                'match-the-following' => 'Match the Following',
                'passage' => 'Passage-Based Questions',
                'picture-based-questions' => 'Picture-Based Questions',
                'read-circle' => 'Read & Circle',
                'circle-underline' => 'Circle/Underline',
                'tick' => 'Tick the Correct Option',
                'short-answer-questions' => 'Short Answer Questions (Answer in 2–3 lines)',
                'long-answer-questions' => 'Long Answer Questions (Answer in 200 words)',
                default => 'Other Questions',
            };
        @endphp

        <!-- Section title row -->
        <table width="100%" cellpadding="6" cellspacing="0"
            style="border-collapse:collapse; margin-top:10px; margin-bottom:6px;">
            <tr>
                <td style="font-weight:700; font-size:13pt; text-decoration:underline;">
                    Section {{ chr(65 + $loop->index) }}: {{ $sectionTitle }} ({{ $group->sum('marks') }} Marks)
                </td>
            </tr>
        </table>

        <style>
            .question-text p:first-child {
                margin-top: 0 !important;
            }
        </style>

        <!-- Iterate questions in group -->
        @foreach ($group as $index => $q)
            @php $qnNumber = $loop->iteration; @endphp

            <!-- If not passage, print question header in table row with marks -->
            @if ($q->question_type != 'passage')
                <table width="100%" cellpadding="4" cellspacing="0"
                    style="border-collapse:collapse; margin-bottom:4px; table-layout:fixed;">
                    <tr>
                        <td width="4%" style="font-weight:700; font-size:11pt; vertical-align:top;">
                            {{ $qnNumber }}.
                        </td>

                        <td width="88%" class="question-text"
                            style="font-size:11pt; vertical-align:top; word-wrap:break-word;">
                            {!! $q->question !!}
                        </td>

                        <td width="8%"
                            style="text-align:right; font-weight:700; font-size:11pt; vertical-align:top; white-space:nowrap;">
                            [{{ $q->marks }}]
                        </td>
                    </tr>
                </table>
            @endif




            <!-- ---------- TICK (checkbox list) ---------- -->
            @if ($type === 'tick' && !empty($q->options))
                <table width="100%" cellpadding="4" cellspacing="0"
                    style="border-collapse:collapse; margin-left:20px; margin-bottom:10px;">

                    @foreach ($q->options->chunk(2) as $row)
                        <tr>

                            @foreach ($row as $key => $opt)
                                <td width="50%" valign="top" style="white-space:wrap;">

                                    <span width="100%" style="white-space:wrap; vertical-align:middle;">

                                        <input width="10%" type="checkbox" disabled
                                            @if ($userType === 'teacher' && $opt->is_correct) checked @endif
                                            style="transform:scale(1.1); margin-right:6px; vertical-align:middle;">

                                        <span width="20%"
                                            style="font-weight:bold; margin-right:6px; vertical-align:middle;">
                                            {{ chr(97 + $key) }}.
                                        </span>

                                        <span width="30%" style="vertical-align:middle;">
                                            {!! preg_replace('/^<p>(.*)<\/p>$/si', '$1', $opt->option_text) !!}
                                        </span>

                                    </span>

                                </td>
                            @endforeach

                            {{-- Fill empty cell if odd number of options --}}
                            @if ($row->count() == 1)
                                <td width="50%"></td>
                            @endif

                        </tr>
                    @endforeach

                </table>


                <!-- ---------- MCQ (radio-style) ---------- -->
            @elseif ($type === 'mcq' && !empty($q->options))
                <table width="100%" cellpadding="4" cellspacing="0"
                    style="border-collapse:collapse; margin-left:20px; margin-bottom:10px;">

                    @foreach ($q->options->chunk(2) as $row)
                        <tr>

                            @foreach ($row as $key => $opt)
                                <td width="50%" valign="top" style="white-space:wrap;">

                                    <span width="100%" style="white-space:wrap; vertical-align:middle;">

                                        <input width="10%" type="checkbox" disabled
                                            @if ($userType === 'teacher' && $opt->is_correct) checked @endif
                                            style="transform:scale(1.1); margin-right:6px; vertical-align:middle;">

                                        <span width="20%"
                                            style="font-weight:bold; margin-right:6px; vertical-align:middle;">
                                            {{ chr(97 + $key) }}.
                                        </span>

                                        <span width="30%" style="vertical-align:middle;">
                                            {!! preg_replace('/^<p>(.*)<\/p>$/si', '$1', $opt->option_text) !!}
                                        </span>

                                    </span>

                                </td>
                            @endforeach

                            {{-- Fill empty cell if odd number of options --}}
                            @if ($row->count() == 1)
                                <td width="50%"></td>
                            @endif

                        </tr>
                    @endforeach

                </table>




                <!-- ---------- PICTURE-BASED ---------- -->
            @elseif ($type === 'picture-based-questions')
                <!-- Picture area boxed -->
                <table width="100%" cellpadding="8" cellspacing="0"
                    style="border-collapse:collapse; margin:12px 0; border:1px dashed #999;">
                    <tr>
                        <td style="text-align:center; padding:10px;">
                            {!! $q->question !!}
                        </td>
                    </tr>
                </table>

                <!-- options as MCQ table (if any) -->
                @if (!empty($q->options))
                    <table width="100%" cellpadding="3" cellspacing="0"
                        style="border-collapse:collapse; margin-left:20px; margin-bottom:8px;">
                        @foreach ($q->options as $key => $opt)
                            <tr>
                                <td width="30" valign="top" style="padding:3px 6px 3px 0;">
                                    <input type="radio" name="q{{ $q->id }}" disabled
                                        @if ($userType === 'teacher' && $opt->is_correct) checked @endif
                                        style="transform:scale(1.15);">
                                </td>
                                <td width="30" valign="top" style="padding:3px 6px 3px 0; font-weight:700;">
                                    {{ chr(97 + $key) }}.</td>
                                <td valign="top" style="padding:3px 0; font-size:11pt;">{!! $opt->option_text ?? '' !!}</td>
                            </tr>
                        @endforeach
                    </table>
                @endif

                <!-- ---------- TRUE / FALSE ---------- -->
            @elseif ($type === 't/f' && !empty($q->options))
                @php $optionIndex = 0; @endphp

                <table width="100%" cellpadding="4" cellspacing="0"
                    style="border-collapse:collapse; margin-left:20px; margin-bottom:10px; table-layout:fixed;">

                    @foreach ($q->options->chunk(2) as $row)
                        <tr>

                            @foreach ($row as $opt)
                                <td width="50%" valign="top" style="padding:4px; vertical-align:middle;">

                                    <!-- Keep everything inline but allow text to wrap within the cell -->
                                    <span style="vertical-align:middle;">

                                        <!-- radio -->
                                        <input type="radio" name="q{{ $q->id }}" disabled
                                            @if ($userType === 'teacher' && $opt->is_correct) checked @endif
                                            style="transform:scale(1.15); margin-right:8px; vertical-align:middle;">

                                        <!-- letter -->
                                        <span style="font-weight:700; margin-right:6px; vertical-align:middle;">
                                            {{ chr(97 + $optionIndex) }}.
                                        </span>

                                        <!-- option text (strip single wrapping <p>..</p>) -->
                                        <span style="font-size:11pt; vertical-align:middle;">
                                            {!! preg_replace('/^<p>(.*)<\/p>$/si', '$1', $opt->option_text ?? '') !!}
                                        </span>

                                    </span>

                                </td>

                                @php $optionIndex++; @endphp
                            @endforeach

                            {{-- Fill empty cell if odd number of options --}}
                            @if ($row->count() == 1)
                                <td width="50%" style="padding:4px;"></td>
                            @endif

                        </tr>
                    @endforeach

                </table>

                <!-- ---------- ONE WORD / FILL-UPS ---------- -->
            @elseif (in_array($type, ['one-word-answer', 'fill-ups']))
                <table width="100%" cellpadding="6" cellspacing="0"
                    style="border-collapse:collapse; margin-left:20px; margin-bottom:6px;">
                    <tr>
                        <td style="padding:6px 0; border-bottom:1px dashed #999; height:28px;"></td>
                    </tr>
                </table>
                @if ($userType === 'teacher' && $q->answer_text)
                    <table width="100%" cellpadding="6" cellspacing="0"
                        style="border-collapse:collapse; margin-left:20px; margin-bottom:6px;">
                        <tr>
                            <td style="border:1px dashed #999; padding:6px; font-style:italic;">Answer:
                                {!! $q->answer_text !!}</td>
                        </tr>
                    </table>
                @endif

                <!-- ---------- SHORT ANSWER ---------- -->
            @elseif ($type === 'short-answer-questions')
                <table width="100%" cellpadding="4" cellspacing="0"
                    style="border-collapse:collapse; margin-left:20px; margin-bottom:6px;">
                    @for ($i = 0; $i < 3; $i++)
                        <tr>
                            <td style="height:22px; border-bottom:1px dashed #999;"></td>
                        </tr>
                    @endfor
                </table>
                @if ($userType === 'teacher' && $q->answer_text)
                    <table width="100%" cellpadding="6" cellspacing="0"
                        style="border-collapse:collapse; margin-left:20px; margin-bottom:6px;">
                        <tr>
                            <td style="border:1px dashed #999; padding:6px; font-style:italic;">Answer:
                                {!! $q->answer_text !!}</td>
                        </tr>
                    </table>
                @endif

                <!-- ---------- LONG ANSWER ---------- -->
            @elseif ($type === 'long-answer-questions')
                <table width="100%" cellpadding="4" cellspacing="0"
                    style="border-collapse:collapse; margin-left:20px; margin-bottom:6px;">
                    @for ($i = 0; $i < 8; $i++)
                        <tr>
                            <td style="height:22px; border-bottom:1px dashed #999;"></td>
                        </tr>
                    @endfor
                </table>
                @if ($userType === 'teacher' && $q->answer_text)
                    <table width="100%" cellpadding="6" cellspacing="0"
                        style="border-collapse:collapse; margin-left:20px; margin-bottom:6px;">
                        <tr>
                            <td style="border:1px dashed #999; padding:6px; font-style:italic;">Answer:
                                {!! $q->answer_text !!}</td>
                        </tr>
                    </table>
                @endif

                <!-- ---------- MATCH THE FOLLOWING (expects >=8 options, displayed as 2 columns) ---------- -->
            @elseif ($type === 'match-the-following' && $q->options->count() >= 8)
                @php
                    $leftOptions = $q->options->slice(0, 4)->values();
                    $rightOptions = $q->options->slice(4, 4)->values();
                @endphp
                <table width="100%" cellpadding="6" cellspacing="0"
                    style="border-collapse:collapse; margin-left:20px; margin-bottom:8px; border:1px solid #000;">
                    <thead>
                        <tr>
                            <th style="text-align:left; width:50%; padding:6px; background-color:#f5f5f5;">Column A
                            </th>
                            <th style="text-align:left; width:50%; padding:6px; background-color:#f5f5f5;">Column B
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @for ($i = 0; $i < 4; $i++)
                            <tr>
                                <td style="padding:6px; vertical-align:top;">{{ chr(65 + $i) }}.
                                    {!! $leftOptions[$i]->option_text ?? '' !!}</td>
                                <td style="padding:6px; vertical-align:top;">{{ chr(97 + $i) }}.
                                    {!! $rightOptions[$i]->option_text ?? '' !!}</td>
                            </tr>
                        @endfor
                    </tbody>
                </table>

                @if ($userType === 'teacher')
                    <table width="100%" cellpadding="6" cellspacing="0"
                        style="border-collapse:collapse; margin-left:20px; margin-bottom:8px;">
                        <tr>
                            <td style="border:1px dashed #999; padding:6px; font-style:italic;">
                                <strong>Correct Matches:</strong><br>
                                @foreach ($q->options as $opt)
                                    {!! $opt->left_text ?? '' !!} → {!! $opt->right_text ?? '' !!}<br>
                                @endforeach
                            </td>
                        </tr>
                    </table>
                @endif

                <!-- ---------- PASSAGE BASED ---------- -->
            @elseif ($type === 'passage')
                @php $data = json_decode($q->additional_data ?? '{}', true); @endphp
                @if (!empty($data['paragraph_statement']))
                    <table width="100%" cellpadding="6" cellspacing="0"
                        style="border-collapse:collapse; margin-left:20px; margin-bottom:4px;">
                        <tr>
                            <td style="font-weight:700;">{!! $data['paragraph_statement'] !!}</td>
                        </tr>
                    </table>
                @endif
                <table width="100%" cellpadding="8" cellspacing="0"
                    style="border-collapse:collapse; margin-left:20px; margin-bottom:8px; border:1px solid #ddd; background-color:#f9f9f9;">
                    <tr>
                        <td style="padding:8px; font-size:11pt;">{!! $data['paragraph'] ?? '' !!}</td>
                    </tr>
                </table>

                @if (!empty($data['questions_and_answers']))
                    @foreach ($data['questions_and_answers'] as $index => $sub)
                        <table width="100%" cellpadding="4" cellspacing="0"
                            style="border-collapse:collapse; margin-left:30px; margin-bottom:6px;">
                            <tr>
                                <td width="92%" valign="top" style="font-size:11pt; padding:4px 0;">
                                    <span style="font-weight:700; margin-right:6px;">{{ chr(65 + $index) }}.</span>
                                    <span>{!! $sub['question'] ?? '' !!}</span>
                                </td>
                                <td width="8%" valign="top"
                                    style="text-align:right; font-weight:700; padding:4px 0;"></td>
                            </tr>
                        </table>
                        <table width="100%" cellpadding="6" cellspacing="0"
                            style="border-collapse:collapse; margin-left:30px; margin-bottom:6px;">
                            <tr>
                                <td style="height:40px; border-bottom:1px dashed #999;"></td>
                            </tr>
                        </table>
                        @if ($userType === 'teacher' && !empty($sub['answer']))
                            <table width="100%" cellpadding="6" cellspacing="0"
                                style="border-collapse:collapse; margin-left:30px; margin-bottom:6px;">
                                <tr>
                                    <td style="border:1px dashed #999; padding:6px; font-style:italic;">Answer:
                                        {!! $sub['answer'] !!}</td>
                                </tr>
                            </table>
                        @endif
                    @endforeach
                @endif

                <!-- ---------- READ & CIRCLE ---------- -->
            @elseif ($type === 'read-circle')
                <table width="100%" cellpadding="6" cellspacing="0"
                    style="border-collapse:collapse; margin-left:20px; margin-bottom:6px;">
                    <tr>
                        <td style="font-size:11pt; padding:4px 0;">{!! $q->paragraph !!}</td>
                    </tr>
                    <tr>
                        <td style="font-size:11pt; padding:4px 0;">Circle from:
                            {{ implode(' | ', $q->choices ?? []) }}</td>
                    </tr>
                    <tr>
                        <td style="height:30px; border-bottom:1px dashed #999;"></td>
                    </tr>
                </table>

                <!-- ---------- CATCH-ALL (other types) ---------- -->
            @else
                <!-- If options exist, show them in option-table style -->
                @if (!empty($q->options))
                    <table width="100%" cellpadding="3" cellspacing="0"
                        style="border-collapse:collapse; margin-left:20px; margin-bottom:8px;">
                        @foreach ($q->options as $key => $opt)
                            <tr>
                                <td width="30" valign="top" style="padding:3px 6px 3px 0;">
                                    <input type="checkbox" disabled @if ($userType === 'teacher' && $opt->is_correct) checked @endif
                                        style="transform:scale(1.15);">
                                </td>
                                <td width="30" valign="top" style="padding:3px 6px 3px 0; font-weight:700;">
                                    {{ chr(97 + $key) }}.</td>
                                <td valign="top" style="padding:3px 0; font-size:11pt;">{!! $opt->option_text !!}
                                </td>
                            </tr>
                        @endforeach
                    </table>
                @endif
            @endif

            <!-- End of a non-passage question block: small separator -->
            @if ($q->question_type != 'passage')
                <table width="100%" cellpadding="0" cellspacing="0"
                    style="border-collapse:collapse; margin-bottom:6px;">
                    <tr>
                        <td style="height:4px;"></td>
                    </tr>
                </table>
            @endif
        @endforeach
    @endforeach

    <!-- END OF PAPER -->
    <table width="100%" cellpadding="10" cellspacing="0" style="border-collapse:collapse; margin-top:18px;">
        <tr>
            <td style="text-align:center; font-weight:700;">*** END OF QUESTION PAPER ***</td>
        </tr>
    </table>
</body>

</html>
