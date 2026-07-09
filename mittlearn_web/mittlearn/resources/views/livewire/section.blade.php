<div>
    <div class="card">
        <div class="card-body">
            @if ($flag == 1)
                {{ Form::model($data, ['url' => route('section.save'), 'id' => 'edit-plan-form', 'class' => 'row g-3']) }}
                {{ Form::hidden('id', null) }}
            @else
                {{ Form::open(['url' => route('section.save'), 'id' => 'add-plan-form', 'class' => 'row g-3']) }}
            @endif
            <h5 class="card-title pb-0">Section Info</h5>
            <hr class="form-divider">

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::label('section_name', 'Section Name', ['class' => 'form-label required']) !!}
                {!! Form::text('section_name', null, [
                    'class' => 'form-control required',
                    'placeholder' => 'Enter section name (use a unique name each time, like A, B, C, D, etc.)',
                    'required',
                ]) !!}
                <small id="vallidateNameError" class="form-text text-danger mt-1" style="display:none;"></small>
            </div>

            <div class="col-md-6 col-sm-6 col-xs-12">
                {!! Form::label('is_active', 'Status', ['class' => 'form-label required ']) !!}
                {!! Form::select('is_active', config('constants.STATUS_LIST'), null, [
                    'class' => 'form-control form-select fs-8 ',
                    'placeholder' => '--Select--',
                    'required',
                ]) !!}
            </div>
            <div class="col-sm-12 text-end">
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="reset" class="btn btn-secondary" onclick="window.location.reload();">Reset</button>
            </div>

            {{ Form::close() }}
        </div>
    </div>
</div>
