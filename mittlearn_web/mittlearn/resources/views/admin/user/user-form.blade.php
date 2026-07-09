<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <h5 class="card-title pb-0">User Details</h5>
            <hr class="form-divider">


            <div class="row g-3">

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('name', 'Student Name', ['class' => 'form-label required']) !!}
                    {!! Form::text('name', $userData->name ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Student Name',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>


                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('email', 'Email ID', ['class' => 'form-label ', 'disabled' => $viewOnly ?? false]) !!}
                    {!! Form::text('email', $userData->email ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Email',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('mobile_no', 'Mobile No.', ['class' => 'form-label required', 'disabled' => $viewOnly ?? false]) !!}
                    {!! Form::text('mobile_no', $userData->mobile_no ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Mobile No.',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('class', 'Select Class', ['class' => 'form-label ']) !!}
                    {{ Form::select('class', $classes, $userData->studentDetails->class ?? null, [
                        'class' => 'form-select',
                        'placeholder' => '--Select--',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) }}
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('category', 'Select Category', ['class' => 'form-label ']) !!}
                    {{ Form::select('category', $categories, null, [
                        'class' => 'form-select',
                        'wire:model' => 'selectedCategory',
                        'wire:change' => 'getSubCategories($event.target.value)',
                        'placeholder' => '--Select--',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) }}
                </div>

                @if ($selectedCategory == '2')
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        {!! Form::label('subcategory', 'Select Sub-Category', ['class' => 'form-label ']) !!}
                        {{ Form::select('subcategory', $subCategories, null, [
                            'class' => 'form-select',
                            'wire:model' => 'selectedSubCategory',
                            'wire:change' => 'getTalentCourses($event.target.value)',
                            'placeholder' => '--Select--',
                            'disabled' => $viewOnly ? 'disabled' : null,
                        ]) }}
                    </div>
                @endif
                @if ($selectedCategory == '1' || $selectedSubCategory)
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        {!! Form::label('course_id', 'Select Course', ['class' => 'form-label ']) !!}
                        <select name="course_id[]" class="js-select2 form-select" ,
                            @if ($viewOnly) disabled @endif , multiple="multiple"
                            placeholder="Select">
                            @foreach ($courses as $id => $name)
                                <option value="{{ $id }}">
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('password', 'Password', ['class' => 'form-label required ']) !!}
                    {!! Form::text('password', $userData->validate_string ?? 'Mitt@123', [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Password',
                    ]) !!}
                </div>
                @if ($courseData->isNotEmpty())
                    <div class="col-md-12 col-sm-6 col-xs-12">
                        <h5>Courses</h5>
                        <div class="courses-container mt-2">
                            @foreach ($courseData as $course)
                                <span class="assigned-course-badge">
                                    {{ $course['name'] }}
                                    <a
                                     {{-- href="{{ route('delete.course', [
                                        'course_id' => $course['id'],
                                        'user_id' => $userData->id ?? null,
                                    ]) }}" --}}
                                        onclick="confirmCourseDelete('{{ route('delete.course', [
                                            'course_id' => $course['id'],
                                            'user_id' => $userData->id ?? null,
                                        ]) }}')"
                                        class="delete-course-link">
                                        <i class="bi bi-x"></i>
                                    </a>
                                </span>
                            @endforeach

                        </div>
                    </div>
                @endif


                <div class="col-sm-12 text-end">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="reset" class="btn btn-secondary" onclick="window.location.reload();">Reset</button>
                </div>
            </div>

        </div>
    </div>
    </div>
</section>
