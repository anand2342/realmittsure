<div class="row">

    {{-- @dump($total_chapter) --}}



    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Chapters Info</h5>
                <div class="row">
                    <div class="col-md-6 col-sm-6 col-xs-12 input-div">
                        <div class="">
                            {!! Form::label('chapter_title', 'Chapter Title', ['class' => 'form-label']) !!}
                            {{ Form::text('chapter_title', null, [
                                'class' => 'form-control',
                                'autocomplete' => 'off',
                                'placeholder' => 'Chapter Title',
                            ]) }}
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-6 col-xs-12 input-div">
                        <div class="">
                            {!! Form::label('chapter_description', 'Chapter Description', ['class' => 'form-label']) !!}
                            {{ Form::text('chapter_description', null, [
                                'class' => 'form-control',
                                'autocomplete' => 'off',
                                'placeholder' => 'Chapter Description',
                            ]) }}
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-6 col-xs-12 input-div">
                        <div class="">
                            {!! Form::label('chapter_file', 'Chapter File', ['class' => 'form-label']) !!}
                            {!! Form::file('chapter_file', ['class' => 'form-control']) !!}
                        </div>
                    </div>

                    {{-- <div class="col-md-6 col-sm-6 col-xs-12 input-div">
                        <div class="">
                            {!! Form::label("supporting_file", 'Supporting File', ['class' => 'form-label']) !!}
                            {!! Form::file("supporting_file", ['class' => 'form-control']) !!}
                        </div>
                    </div> --}}

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Submit</button>
                        <button type="reset" class="btn btn-secondary" onclick="window.location.reload();">Reset</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
