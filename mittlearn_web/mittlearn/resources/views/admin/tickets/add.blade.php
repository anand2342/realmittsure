@extends('admin.layouts.master')
@section('content')
    @php
        $flag = 0;
        $heading = 'Add';
        if (isset($data) && !empty($data)) {
            $flag = 1;
            $heading = 'Update';
        }
    @endphp
    <div>
        <div class="pagetitle">
            <h1>{{ $heading }} @if ($flag == 1) {{ ucfirst($data->ticket_id) }} @else Ticket @endif</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Ticket Management</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title pb-0">Ticket Information</h5>
                            <hr class="form-divider">

                            @if ($flag == 1)
                                {{ Form::model($data, ['url' => route('tickets.store'), 'id' => 'edit-ticket-form', 'class' => 'row g-3', 'files' => true]) }}
                                {{ Form::hidden('id', null) }}
                            @else
                                {{ Form::open(['url' => route('tickets.store'), 'id' => 'add-ticket-form', 'class' => 'row g-3', 'files' => true]) }}
                            @endif
                            @php
                                $canManage = in_array(getUserRoles(), ['admin', 'qd_developer', 'super_admin']);
                                $isEdit = isset($data);
                                if ($isEdit) {
                                    $ticketValues = [
                                        'open' => 'Open',
                                        'in_progress' => 'In Progress',
                                        'closed' => 'Closed',
                                    ];
                                } else {
                                    $ticketValues = [
                                        'open' => 'Open',
                                    ];
                                }
                                $isQD = getUserRoles() === 'qd_developer';
                                $readonly = $isEdit && $isQD ? ['readonly' => true] : [];
                                $readonlyClass = $isEdit && $isQD ? 'readonly-field' : '';
                            @endphp

                            <div class="col-md-6 col-sm-6 col-xs-6">
                                {!! Form::label('module', 'Module Name/ Section/ Item name', ['class' => 'form-label required']) !!}
                                {!! Form::textarea(
                                    'module',
                                    null,
                                    array_merge(
                                        [
                                            'class' => 'form-control required ' . $readonlyClass,
                                            'placeholder' => 'Enter module name ',
                                            'rows' => 4,
                                            'required',
                                        ],
                                        $readonly,
                                    ),
                                ) !!}
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-6">
                                {!! Form::label('screenshot_path', 'Screenshot/ Video File (If possible)', ['class' => 'form-label']) !!}
                                @if (!($isEdit && $isQD))
                                    {!! Form::file('screenshot_path[]', [
                                        'class' => 'form-control',
                                        'multiple' => true, // <-- allow multiple
                                    ]) !!}
                                    <small class="text-muted">Upload one or more (select with ctrl) screenshots, videos, or
                                        other files</small>
                                @else
                                    <small class="text-muted">Screenshot upload disabled for developers while
                                        editing.</small>
                                @endif
                                @if ($flag && isset($data->screenshot_path) && $data->screenshot_path)
                                    <div class="mt-2">
                                        <img src="{{ Storage::url('uploads/tickets/' . $data->screenshot_path) }}"
                                            alt="Image" width="200" height="100">
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                {!! Form::label('issue', 'Exact Issue Observed', ['class' => 'form-label required']) !!}
                                <div class="quill-editor-full{{ $isEdit && $isQD ? ' readonly-field' : '' }}"
                                    id="quill-editor" style="height: 150px;"></div>
                                {!! Form::hidden('issue', null, ['id' => 'editor-content', 'required' => true]) !!}
                            </div>


                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('logged_by_user', 'Logged By User (enter your name)', ['class' => 'form-label required']) !!}
                                {!! Form::text(
                                    'logged_by_user',
                                    null,
                                    array_merge(
                                        [
                                            'class' => 'form-control required ' . $readonlyClass,
                                            'placeholder' => 'Enter your name here',
                                            'required',
                                        ],
                                        $readonly,
                                    ),
                                ) !!}
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('priority', 'Priority', ['class' => 'form-label required']) !!}
                                {!! Form::select('priority', $priority, null, [
                                    'class' => 'form-control required ' . ($isEdit && $isQD ? 'readonly-field' : ''),
                                    'placeholder' => '--Select Priority--',
                                    'required',
                                    'disabled' => $isEdit && $isQD,
                                ]) !!}
                            </div>

                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('status', 'Status', ['class' => 'form-label required']) !!}
                                {!! Form::select(
                                    'status',
                                
                                    $ticketValues,
                                    null,
                                    [
                                        'class' => 'form-control required',
                                        'required',
                                    ],
                                ) !!}
                            </div>

                            {{-- @if ($canManage) --}}
                            <div class="col-md-6 col-sm-6 col-xs-12">
                                {!! Form::label('assigned_to', 'Assign to', ['class' => 'form-label']) !!}
                                {!! Form::select('assigned_to', $qdDevelopers ?? [], null, [
                                    'class' => 'form-control ' . ($isEdit && $isQD ? 'readonly-field' : ''),
                                    'placeholder' => '--Select--',
                                    'disabled' => $isEdit && $isQD,
                                ]) !!}
                            </div>
                            {{-- @endif --}}

                            @if ($canManage)
                                <div class="col-md-12">
                                    {!! Form::label('remarks_qd', 'Remark by QD Team', ['class' => 'form-label']) !!}
                                    {!! Form::textarea('remarks_qd', null, [
                                        'class' => 'form-control',
                                        'placeholder' => 'Enter remarks from QD team',
                                        'rows' => 3,
                                        'style' => 'min-height: 100px;',
                                    ]) !!}
                                </div>
                            @endif

                            <div class="col-md-12">
                                {!! Form::label('further_remarks', 'Further Remarks', ['class' => 'form-label']) !!}
                                {!! Form::textarea(
                                    'further_remarks',
                                    null,
                                    array_merge(
                                        [
                                            'class' => 'form-control ' . $readonlyClass,
                                            'placeholder' => 'Enter any additional remarks or updates',
                                            'rows' => 3,
                                        ],
                                        $readonly,
                                    ),
                                ) !!}
                            </div>

                            <div class="col-sm-12 text-end">
                                <button type="submit" class="btn btn-primary">{{ $flag ? 'Update' : 'Submit' }}</button>
                                <button type="reset" class="btn btn-secondary"
                                    onclick="window.location.reload();">Reset</button>
                            </div>

                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <style>
        .readonly-field,
        .readonly-field:disabled,
        .readonly-field[readonly] {
            background-color: #f8f9fa !important;
            color: #6c757d !important;
            border-color: #dee2e6 !important;
        }
    </style>

    <script>
        $(document).ready(function() {
            @if ($flag && isset($data->issue))
                setTimeout(function() {
                    $('.quill-editor-full .ql-editor').html({!! json_encode($data->issue) !!});
                }, 100);
            @endif

            @if (isset($data) && getUserRoles() === 'qd_developer')
                setTimeout(function() {
                    $('.quill-editor-full .ql-toolbar').hide();
                    $('.quill-editor-full .ql-editor').attr('contenteditable', false);
                }, 100);
            @endif

            $('form').on('submit', function() {
                var quillContent = $('.quill-editor-full .ql-editor').html();
                $('#editor-content').val(quillContent);
            });
        });
    </script>
@endsection
