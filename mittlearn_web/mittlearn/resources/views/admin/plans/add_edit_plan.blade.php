@extends('admin.layouts.master')
@section('content')
    @php
        $isEditMode = 0;
        $heading = 'Add';
        if (isset($data_row) && !empty($data_row)) {
            $isEditMode = 1;
            $heading = 'Update';
        }
    @endphp

    <div>
        <div class="pagetitle">
            <h1>{{ $heading }} Subscription Plan</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Users</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">

                    <div class="card">
                        <div class="card-body">

                            @if ($isEditMode == 1)
                                {{ Form::model($data_row, ['url' => route('plans.save'), 'id' => 'edit-plan-form', 'class' => 'row g-3']) }}
                                {{ Form::hidden('id', null) }}
                            @else
                                {{ Form::open(['url' => route('plans.save'), 'id' => 'add-plan-form', 'class' => 'row g-3']) }}
                            @endif
                            <h5 class="card-title pb-0">Plan Info</h5>
                            <hr class="form-divider">

                            <!-- Plan Fields -->
                            <div class="col-md-4 col-sm-3 col-xs-12">
                                {!! Form::label('name', 'Plan Name', ['class' => 'form-label required']) !!}
                                {!! Form::text('name', null, [
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter plan name',
                                    'required',
                                    'id' => 'vallidateName',
                                ]) !!}
                                <small id="vallidateNameError" class="form-text text-danger mt-1"
                                    style="display:none;"></small>
                            </div>

                            {{-- <div class="col-md-4 col-sm-3 col-xs-12">
                                {!! Form::label('plan_type', 'Plan Type', ['class' => 'form-label']) !!}
                                {{ Form::select('plan_type', config('constants.SUBSCRIPTION_PLAN_TYPES'), null, ['class' => 'form-select', 'placeholder' => '--Select--']) }}
                            </div> --}}

                            {{-- <div class="col-md-4 col-sm-3 col-xs-12">
                    {!! Form::label('price', 'Price', ['class'=>"form-label"]) !!}
                    {!! Form::number('price', null, ['class' => 'form-control', 'placeholder' => 'Enter price']) !!}
                </div>

                <div class="col-md-4 col-sm-3 col-xs-12">
                    {!! Form::label('old_price', 'Old Price', ['class'=>"form-label"]) !!}
                    {!! Form::number('old_price', null, ['class' => 'form-control', 'placeholder' => 'Enter old price']) !!}
                </div> --}}

                            <div class="col-md-4 col-sm-3 col-xs-12 d-none">
                                {!! Form::label('currency', 'Currency', ['class' => 'form-label']) !!}
                                {{ Form::select('currency', getCurrencyList(), null, ['class' => 'form-select']) }}
                            </div>

                            <div class="col-md-4 col-sm-3 col-xs-12">
                                {!! Form::label('description', 'Description', ['class' => 'form-label']) !!}
                                {!! Form::textarea('description', null, [
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter plan description',
                                    'rows' => 1,
                                ]) !!}
                            </div>

                            <div class="col-md-4 col-sm-3 col-xs-12">
                                {!! Form::label('sort_order', 'Sort Order', ['class' => 'form-label']) !!}
                                {!! Form::number('sort_order', null, ['class' => 'form-control', 'placeholder' => 'Enter sort order']) !!}
                            </div>

                            <div class="col-md-4 col-sm-3 col-xs-12">
                                {!! Form::label('status', 'Status', ['class' => 'form-label']) !!}
                                {{ Form::select('status', config('constants.STATUS_LIST'), null, ['class' => 'form-select']) }}
                            </div>

                            {{-- <div class="col-md-4 col-sm-3 col-xs-12">
                                {!! Form::label('bg_color', 'Background Color', ['class' => 'form-label']) !!}
                                {!! Form::color('bg_color', null, [
                                    'class' => 'form-control',
                                    'placeholder' => 'Enter background color (hex code)',
                                ]) !!}
                            </div> --}}

                            <div class="col-md-12 col-sm-12 col-xs-12"></div>

                            <div class="col-md-4 col-sm-3 col-xs-12">
                                {!! Form::checkbox('is_free_trial', 1, $isEditMode && $data_row->is_free_trial ? true : false) !!}
                                {!! Form::label('is_free_trial', 'Is Free Trial', ['class' => 'form-label']) !!}
                            </div>

                            <div class="col-md-4 col-sm-3 col-xs-12">
                                {!! Form::checkbox('is_recomanded', 1, $isEditMode && $data_row->is_recomanded ? true : false) !!}
                                {!! Form::label('is_recomanded', 'Is Recommended', ['class' => 'form-label']) !!}
                            </div>

                            <hr />

                            <!-- Plan Benefits -->
                            {{-- <h4>Price Riders</h4>
                            <hr class="form-divider">
                            @livewire('subscription-plan-price-form', ['plan_data' => $isEditMode ? $data_row : null]) --}}

                            <!-- Plan Benefits -->
                            <h4>Features</h4>
                            <hr class="form-divider">
                            @livewire('subscription-plan-features-form', ['plan_data' => $isEditMode ? $data_row : null])

                            <!-- Plan Packs -->
                            @livewire('subscription-plan-pack-form', ['plan_data' => $isEditMode ? $data_row : null])

                            <!-- Plan Benefits -->
                            {{-- <h4>Assign Content</h4>
                            <hr class="form-divider">
                            @livewire('subs-cription-plan-courses-form', ['plan_data' => $isEditMode ? $data_row : null]) --}}
                            {{-- <livewire:subscription-plan-courses-form /> --}}

                            <div class="text-right">
                                <button type="submit" class="btn btn-primary">Submit</button>
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
@endsection
