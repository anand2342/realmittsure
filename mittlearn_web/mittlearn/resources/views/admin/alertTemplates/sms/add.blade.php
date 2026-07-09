@extends('admin.layouts.master')

@section('content')
    @php
        $flag = isset($emailTemplate) && !empty($emailTemplate) ? 1 : 0;
        $heading = $flag ? 'Edit' : 'Add';
    @endphp

    <div id="page-header" class="page-header">
        <section class="section">
            <div class="pagetitle">
                <h1>SMS Template</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">Home</li>
                        <li class="breadcrumb-item active">{{ $heading }} Template</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="text-end mb-2">
                    <a href="{{ route('email-template.index') }}" class="btn btn-primary"><i
                            class="ri-arrow-left-line"></i></a>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h4 class="card-title">{{ $heading }} Template</h4>
                            <hr class="form-divider">
                            @if ($flag)
                                {{ Form::model($emailTemplate, ['url' => route('sms-template.save'), 'class' => 'row g-3']) }}
                                {{ Form::hidden('id', null) }}
                            @else
                                {{ Form::open(['url' => route('sms-template.save'), 'class' => 'row g-3']) }}
                            @endif
                            @livewire('email-template-form', ['emailTemplateId' => $emailTemplate->id ?? null, 'type' => $type])
                            <div class="text-end">
                                {!! Form::submit($flag ? 'Update' : 'Submit', ['class' => 'btn btn-primary']) !!}
                                {!! Form::reset('Reset', ['class' => 'btn btn-secondary']) !!}
                            </div>
                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
