<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Test Paper</title>
    <style>
        body {
            font-family: 'Noto Sans Devanagari', sans-serif;
            font-size: 12pt;
            margin: 0;
            padding: 0;
        }

        /* .correct-option {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 20px;
            height: 20px;
            background-color: #2ecc71;
            color: #ffffff;
            border-radius: 50%;
            font-weight: 700;
            font-size: 11pt;
            margin-right: 6px;
            vertical-align: middle;
        } */
        .correct-option {
            display: inline-block;
            width: 10px;
            height: 18px;
            line-height: 18px;
            /* critical */
            text-align: center;
            background-color: #2ee67b;
            color: #000000ef;
            border-radius: 50%;
            font-weight: bold;
            /* font-size: 11pt; */
            margin-right: 6px;
            vertical-align: middle;
            padding: 0 !important;
            /* must be zero */
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
                        style="height:60px; object-fit:contain; display:block;">
                @endif
            </td>
            <!-- Center -->
            <td valign="middle" style="text-align:center; padding:0 10px;">
                <div style="font-size:18pt; font-weight:700; line-height:1.1;">{{ $schoolName }}</div>
                <div style="font-size:15pt; font-weight:700; margin-top:6px;">{{ $paper->test_term }}</div>
                <div style="margin-top:8px; font-size:11pt;">
                    <span style="margin-right:28px;">CLASS: {{ trim(str_ireplace('class', '', $className)) }}</span>
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
                @php
                    $totalMinutes = (int) $paper->duration;
                    $hours = intdiv($totalMinutes, 60);
                    $minutes = $totalMinutes % 60;
                @endphp

                Duration:
                {{ $hours }} {{ $hours == 1 ? 'hour' : 'hours' }}
                {{ $minutes }} {{ $minutes == 1 ? 'min' : 'mins' }}
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
                    <span> Section {{ chr(65 + $loop->index) }}: {{ $sectionTitle }} </span>
                    <span>({{ $group->sum('marks') }} Marks)</span>
                </td>
            </tr>
        </table>
        <!-- Iterate questions in group -->
        @foreach ($group as $index => $q)
            @php $qnNumber = $loop->iteration; @endphp
            <!-- If not passage, print question header in table row with marks -->
            @if ($q->question_type != 'passage')
                @php
                    // Process the option text to resize images
                    $processedQuestion = $q->question ?? '';
                    $processedQuestion = preg_replace(
                        '/<img([^>]*)>/',
                        '<img$1 style="max-width:150px; max-height:150px; width:150px; height:150px; object-fit:cover; display:inline-block; vertical-align:middle;">',
                        $processedQuestion,
                    );
                @endphp
                <table width="100%" cellpadding="4" cellspacing="0"
                    style="border-collapse:collapse; margin-bottom:4px; table-layout:fixed;">
                    <tr>
                        <td width="2%" style="font-weight:700; font-size:11pt; vertical-align:top;">
                            {{ $qnNumber }}.
                        </td>

                        <td width="90%" style="font-size:11pt; vertical-align:middle; word-wrap:break-word;">
                            {!! $processedQuestion !!}
                        </td>

                        <td width="8%"
                            style="text-align:right; font-weight:700; font-size:11pt; vertical-align:middle; white-space:nowrap;">
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
                                        {{-- <input width="10%" type="checkbox" disabled
                                            style="transform:scale(1.1); margin-right:6px; vertical-align:middle;"> --}}
                                        @if ($userType == 'teacher' && $opt->is_correct)
                                            <span width="20%" class="correct-option"
                                                style="font-weight:bold; margin-right:6px; vertical-align:middle;">
                                                {{ chr(97 + $key) }}.
                                            </span>
                                        @else
                                            <span width="20%"
                                                style="font-weight:bold; margin-right:6px; vertical-align:middle;">
                                                {{ chr(97 + $key) }}.
                                            </span>
                                        @endif
                                        <span width="30%" style="vertical-align:middle; font-size:10pt;">
                                            {!! preg_replace('/^<p>(.*)<\/p>$/si', '$1', $opt->option_text) !!}
                                        </span>
                                        {{-- @if ($userType == 'teacher' && $opt->is_correct)
                                            <img src="{{ asset('frontend/images/green-check.avif') }}"
                                                style="height:25px;">
                                        @endif --}}
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
                                {{-- @dd($userType, $opt->is_correct); --}}
                                <td width="50%" valign="top" style="white-space:wrap;">
                                    <span width="100%" style="white-space:wrap; vertical-align:middle;">

                                        {{-- <input width="10%" type="checkbox" disabled
                                            style="transform:scale(1.1); margin-right:6px; vertical-align:middle;"> --}}
                                        @if ($userType == 'teacher' && $opt->is_correct)
                                            <span width="20%" class="correct-option"
                                                style="font-weight:bold; margin-right:6px; vertical-align:middle;">
                                                {{ chr(97 + $key) }}.
                                            </span>
                                        @else
                                            <span width="20%"
                                                style="font-weight:bold; margin-right:6px; vertical-align:middle;">
                                                {{ chr(97 + $key) }}.
                                            </span>
                                        @endif
                                        <span width="30%" style="vertical-align:middle; font-size:10pt;">
                                            {!! preg_replace('/^<p>(.*)<\/p>$/si', '$1', $opt->option_text) !!}
                                        </span>
                                        {{-- @if ($userType == 'teacher' && $opt->is_correct)
                                            <img src="{{ asset('frontend/images/green-check.avif') }}"
                                                style="height:25px;">
                                        @endif --}}
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
                {{-- <table width="100%" cellpadding="8" cellspacing="0"
                    style="border-collapse:collapse; margin:12px 0; border:1px dashed #999;">
                    <tr>
                        <td style="text-align:center; padding:10px;">
                            {!! $q->question !!}
                        </td>
                    </tr>
                </table> --}}
                <!-- options as MCQ table (if any) -->
                @if (!empty($q->options))
                    <table width="100%" cellpadding="4" cellspacing="0"
                        style="border-collapse:collapse; margin-left:20px; margin-bottom:10px;">
                        @php $optionIndex = 0; @endphp
                        @foreach ($q->options->chunk(2) as $row)
                            <tr>
                                @foreach ($row as $opt)
                                    @php
                                        // Process the option text to resize images
                                        $processedText = $opt->option_text ?? '';
                                        $processedText = preg_replace(
                                            '/<img([^>]*)>/',
                                            '<img$1 style="max-width:150px; max-height:150px; width:150px; height:150px; object-fit:cover; display:inline-block; vertical-align:middle;">',
                                            $processedText,
                                        );
                                    @endphp
                                    <td width="50%" valign="middle" style="padding:4px;">
                                        <table width="100%" cellpadding="0" cellspacing="0"
                                            style="border-collapse:collapse;">
                                            <tr>
                                                <td width="30" valign="middle" style="padding:0;">
                                                    @if ($userType == 'teacher' && $opt->is_correct)
                                                        <span class="correct-option"
                                                            style="font-weight:bold; margin-right:6px; vertical-align:middle;">
                                                            {{ chr(97 + $optionIndex) }}.
                                                        </span>
                                                    @else
                                                        <span
                                                            style="font-weight:bold; margin-right:6px; vertical-align:middle;">
                                                            {{ chr(97 + $optionIndex) }}.
                                                        </span>
                                                    @endif
                                                </td>
                                                <td valign="middle" style="padding:0; font-size:10pt;">
                                                    {!! $processedText !!}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                    @php $optionIndex++; @endphp
                                @endforeach
                                {{-- Fill empty cell if odd number of options --}}
                                @if ($row->count() == 1)
                                    <td width="50%"></td>
                                @endif
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
                                        {{-- <input type="radio" name="q{{ $q->id }}" disabled
                                            style="transform:scale(1.15); margin-right:8px; vertical-align:middle;"> --}}
                                        <!-- letter -->
                                        @if ($userType == 'teacher' && $opt->is_correct)
                                            <span class="correct-option"
                                                style="font-weight:bold; margin-right:6px; vertical-align:middle;">
                                                {{ chr(97 + $optionIndex) }}.
                                            </span>
                                        @else
                                            <span style="font-weight:bold; margin-right:6px; vertical-align:middle;">
                                                {{ chr(97 + $optionIndex) }}.
                                            </span>
                                        @endif
                                        <!-- option text (strip single wrapping <p>..</p>) -->
                                        <span style="vertical-align:middle; font-size:10pt;">
                                            {!! preg_replace('/^<p>(.*)<\/p>$/si', '$1', $opt->option_text ?? '') !!}
                                        </span>
                                        {{-- @if ($userType == 'teacher' && $opt->is_correct)
                                            <img src="{{ asset('frontend/images/green-check.avif') }}"
                                                style="height:25px;">
                                        @endif --}}
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
                {{-- <table width="100%" cellpadding="6" cellspacing="0"
                    style="border-collapse:collapse; margin-left:20px; margin-bottom:6px;">
                    <tr>
                        <td style="padding:6px 0; border-bottom:1px dashed #999; height:28px;"></td>
                    </tr>
                </table> --}}
                {{-- @if ($userType == 'teacher' && $q->answer_text)
                    <table width="100%" cellpadding="6" cellspacing="0"
                        style="border-collapse:collapse; margin-left:20px; margin-bottom:6px;">
                        <tr>
                            <td style="border:1px dashed #999; padding:6px; font-style:italic;">Answer:
                                {!! $q->answer_text !!}</td>
                        </tr>
                    </table>
                @endif --}}
                @if ($userType == 'teacher' && !empty($q->suggested_answer))
                    <table width="100%" cellpadding="6" cellspacing="0"
                        style="border-collapse:collapse; margin-left:20px; margin-bottom:10px;">
                        <tr>
                            <td style="border-left: 3px solid #2ecc71; padding: 6px 10px; font-size:11pt;">
                                <strong>Suggested Answer:</strong> {!! $q->suggested_answer !!}
                            </td>
                        </tr>
                    </table>
                @endif
                <!-- ---------- SHORT ANSWER ---------- -->
            @elseif ($type === 'short-answer-questions')
                {{-- <table width="100%" cellpadding="4" cellspacing="0"
                    style="border-collapse:collapse; margin-left:20px; margin-bottom:6px;">
                    @for ($i = 0; $i < 3; $i++)
                        <tr>
                            <td style="height:22px; border-bottom:1px dashed #999;"></td>
                        </tr>
                    @endfor
                </table>
                @if ($userType == 'teacher' && $q->answer_text)
                    <table width="100%" cellpadding="6" cellspacing="0"
                        style="border-collapse:collapse; margin-left:20px; margin-bottom:6px;">
                        <tr>
                            <td style="border:1px dashed #999; padding:6px; font-style:italic;">Answer:
                                {!! $q->answer_text !!}</td>
                        </tr>
                    </table>
                @endif --}}
                @if ($userType == 'teacher' && !empty($q->suggested_answer))
                    <table width="100%" cellpadding="6" cellspacing="0"
                        style="border-collapse:collapse; margin-left:20px; margin-bottom:10px;">
                        <tr>
                            <td style="border-left: 3px solid #2ecc71; padding: 6px 10px; font-size:11pt;">
                                <strong>Suggested Answer:</strong> {!! $q->suggested_answer !!}
                            </td>
                        </tr>
                    </table>
                @endif
                <!-- ---------- LONG ANSWER ---------- -->
            @elseif ($type === 'long-answer-questions')
                {{-- <table width="100%" cellpadding="4" cellspacing="0"
                    style="border-collapse:collapse; margin-left:20px; margin-bottom:6px;">
                    @for ($i = 0; $i < 8; $i++)
                        <tr>
                            <td style="height:22px; border-bottom:1px dashed #999;"></td>
                        </tr>
                    @endfor
                </table>
                @if ($userType == 'teacher' && $q->answer_text)
                    <table width="100%" cellpadding="6" cellspacing="0"
                        style="border-collapse:collapse; margin-left:20px; margin-bottom:6px;">
                        <tr>
                            <td style="border:1px dashed #999; padding:6px; font-style:italic;">Answer:
                                {!! $q->answer_text !!}</td>
                        </tr>
                    </table>
                @endif --}}
                @if ($userType == 'teacher' && !empty($q->suggested_answer))
                    <table width="100%" cellpadding="6" cellspacing="0"
                        style="border-collapse:collapse; margin-left:20px; margin-bottom:10px;">
                        <tr>
                            <td style="border-left: 3px solid #2ecc71; padding: 6px 10px; font-size:11pt;">
                                <strong>Suggested Answer:</strong> {!! $q->suggested_answer !!}
                            </td>
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
                @if ($userType == 'teacher')
                    <table width="100%" cellpadding="6" cellspacing="0"
                        style="border-collapse:collapse; margin-left:20px; margin-bottom:8px;">
                        <tr>
                            <td style="border:1px dashed #999; padding:6px; font-style:italic;">
                                <strong>Correct Matches:</strong><br>
                                @foreach ($q->options->whereNotNull('is_correct')->take(4) as $opt)
                                    @php
                                        $matched = $q->options->firstWhere('id', $opt->is_correct);
                                    @endphp

                                    {{ $opt->option_text }} → {{ $matched->option_text ?? '' }}<br>
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
                        @if ($userType == 'teacher' && !empty($sub['answer']))
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
                    {{-- <tr>
                        <td style="height:30px; border-bottom:1px dashed #999;"></td>
                    </tr> --}}
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
                                    {{-- <input type="checkbox" disabled style="transform:scale(1.15);"> --}}
                                </td>
                                @if ($userType == 'teacher' && $opt->is_correct)
                                    <td class="correct-option" width="30" valign="top"
                                        style="padding:3px 6px 3px 0; font-weight:700;">
                                        {{ chr(97 + $key) }}.</td>
                                @else
                                    <td width="30" valign="top"
                                        style="padding:3px 6px 3px 0; font-weight:700;">
                                        {{ chr(97 + $key) }}.</td>
                                @endif
                                <td valign="top" style="padding:3px 0; font-size:11pt;">{!! $opt->option_text !!}
                                    {{-- @if ($userType == 'teacher' && $opt->is_correct)
                                        <img src="{{ asset('frontend/images/green-check.avif') }}"
                                            style="height:25px;">
                                    @endif --}}
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
    {{-- @dd(1) --}}
    <!-- END OF PAPER -->
    <table width="100%" cellpadding="10" cellspacing="0" style="border-collapse:collapse; margin-top:18px;">
        <tr>
            <td style="text-align:center; font-weight:700;">*** END OF QUESTION PAPER ***</td>
        </tr>
    </table>
</body>

</html>
