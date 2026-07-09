<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <h5 class="card-title pb-0">User Details</h5>
            <hr class="form-divider">

            <div class="row g-3">
                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('name', ' Name', ['class' => 'form-label required']) !!}
                    {!! Form::text('name', $userData->name ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Name',
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
                    {!! Form::label('category', 'Category', ['class' => 'form-label required']) !!}
                    {!! Form::select('category', $categoriesForD2C ?? [], $userData->userClass->category_id ?? null, [
                        'class' => 'form-select',
                        'wire:model' => 'selectedCategoryId',
                        'wire:change' => 'getClassesForD2C($event.target.value)',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('class', 'Class', ['class' => 'form-label required']) !!}
                    {!! Form::select('class', $classes ?? [], $userData->studentDetails->className->id ?? null, [
                        'class' => 'form-select',
                        'placeholder' => '--Select--',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('section', 'Section', ['class' => 'form-label ']) !!}
                    {!! Form::select('section', $sections ?? [], $userData->studentDetails->section ?? null, [
                        'class' => 'form-select',
                        'placeholder' => '--Select--',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>


                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('roll_number', 'Roll No.', ['class' => 'form-label ']) !!}
                    {!! Form::text('roll_number', $userData->studentDetails->roll_number ?? '', [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Roll No',
                    ]) !!}
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('parent_name', 'Parent / Guardian Name', ['class' => 'form-label']) !!}
                    {!! Form::text('parent_name', $userData->studentDetails->parent_name ?? '', [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Parent Name',
                    ]) !!}
                </div>
                <div class="col-md-6 px-md-2 school-field">
                    <div class="form-group">
                        <label for="schoolName" class="mb-2 form-label">School Name</label>
                        <div x-data="{
                            query: @js($userData->studentDetails->d2c_user_school_name ?? ''),
                            results: @entangle('filteredSchools'),
                            showResults: false,
                            selectSchool(school) {
                                this.query = school.name;
                                this.showResults = false;
                                @this.set('schoolName', school.name);
                            },
                            init() {
                                if (this.query) {
                                    @this.searchSchools(this.query);
                                }
                            }
                        }" @mousedown.away="showResults = false" class="position-relative">
                            <input type="text" x-model="query"
                                class="form-control fs-8 @error('schoolName') is-invalid @enderror"
                                placeholder="Search for a school" autocomplete="off" @focus="showResults = true"
                                @input.debounce.300ms="@this.searchSchools(query)">

                            <!-- Suggestions with scrollbar -->
                            <div x-show="showResults && results.length > 0"
                                class="list-group mt-2 position-absolute w-100 shadow-sm"
                                style="z-index: 1000; max-height: 300px; overflow-y: auto;">
                                <template x-for="school in results" :key="school.id">
                                    <a href="#" @click.prevent="selectSchool(school)"
                                        class="list-group-item list-group-item-action" x-text="school.name"></a>
                                </template>
                            </div>

                            <input type="hidden" name="schoolName" x-model="query">
                        </div>

                        @error('schoolName')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('school_pincode', 'School Pin Code', ['class' => 'form-label ']) !!}
                    {!! Form::text('school_pincode', $userData->studentDetails->school_pincode ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter PIN Code',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>
                {{--  @dd($selectedState)  --}}
                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('school_state', 'School State', ['class' => 'form-label ']) !!}

                    {{ Form::select(
                        'school_state',
                        $states, // Dynamic states array
                        old('school_state', $userData->studentDetails->school_state ?? null), // Pre-fill value or old input
                        [
                            'class' => 'form-select' . ($errors->has('school_state') ? ' is-invalid' : ''),
                            'placeholder' => 'Select',
                            'id' => 'state-select',
                            'wire:model' => 'selectedState',
                            'wire:change' => 'stateChanged($event.target.value)',
                            'disabled' => $viewOnly ? 'disabled' : null,
                        ],
                    ) }}
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('school_district', 'School District', ['class' => 'form-label ']) !!}

                    {{ Form::select(
                        'school_district',
                        $cities, // Dynamically populated cities based on selected state
                        $this->cities ?? null, // Pre-fill value or retain old input
                        [
                            'class' => 'form-select',
                            'placeholder' => 'Select',
                            'id' => 'city-select',
                            'wire:model' => 'city',
                            'disabled' => $viewOnly ? 'disabled' : null,
                        ],
                    ) }}
                </div>


                <div class="col-md-6 col-sm-6 col-xs-12">
                    {!! Form::label('school_address_1', 'School Address', ['class' => 'form-label  ']) !!}
                    {!! Form::text('school_address_1', $userData->studentDetails->school_address_1 ?? null, [
                        'class' => 'form-control',
                        'placeholder' => 'Enter Address',
                        'disabled' => $viewOnly ? 'disabled' : null,
                    ]) !!}
                </div>

                @php
                    $optionA = ['mathematics' => false, 'science' => false];
                    $optionB = ['mathematics' => false, 'science' => false];
                    $selectedOptions = [];

                    if ($userData->studentDetails ?? false) {
                        $studentDetails = $userData->studentDetails;

                        if ($studentDetails->option_a) {
                            $optionA = array_merge($optionA, json_decode($studentDetails->option_a, true));
                            $selectedOptions[] = 'A';
                        }

                        if ($studentDetails->option_b) {
                            $optionB = array_merge($optionB, json_decode($studentDetails->option_b, true));
                            $selectedOptions[] = 'B';
                        }
                    }

                    // Initialize Livewire properties if not set
                    if (empty($this->option_field)) {
                        $this->option_field = $selectedOptions;
                    }

                    $this->a_checkbox1 = $optionA['mathematics'];
                    $this->a_checkbox2 = $optionA['science'];
                    $this->b_checkbox1 = $optionB['mathematics'];
                    $this->b_checkbox2 = $optionB['science'];
                @endphp

                <div class="col-md-12 col-sm-12 col-xs-12">
                    {!! Form::label('option_field', 'Select Option(s)', ['class' => 'form-label required']) !!}
                    <div class="row">

                        {{-- Option A --}}
                        <div class="col-md-6">
                            <div class="form-check">
                                {!! Form::checkbox('option_field[]', 'A', in_array('A', $this->option_field), [
                                    'class' => 'form-check-input',
                                    'id' => 'optionA',
                                    'wire:model' => 'option_field',
                                ]) !!}
                                {!! Form::label('optionA', 'Option A', ['class' => 'form-check-label']) !!}
                                <small>(with Study Kit)</small>
                            </div>

                            <div x-show="$wire.option_field.includes('A')" class="ms-4 mt-2">
                                <div class="form-check">
                                    {!! Form::checkbox('a_checkbox1', 1, $this->a_checkbox1, [
                                        'class' => 'form-check-input',
                                        'id' => 'a_checkbox1',
                                        'wire:model' => 'a_checkbox1',
                                    ]) !!}
                                    {!! Form::label('a_checkbox1', 'Mathematics', ['class' => 'form-check-label']) !!}
                                </div>
                                <div class="form-check mt-2">
                                    {!! Form::checkbox('a_checkbox2', 1, $this->a_checkbox2, [
                                        'class' => 'form-check-input',
                                        'id' => 'a_checkbox2',
                                        'wire:model' => 'a_checkbox2',
                                    ]) !!}
                                    {!! Form::label('a_checkbox2', 'Science', ['class' => 'form-check-label']) !!}
                                </div>
                            </div>
                        </div>

                        {{-- Option B --}}
                        <div class="col-md-6">
                            <div class="form-check">
                                {!! Form::checkbox('option_field[]', 'B', in_array('B', $this->option_field), [
                                    'class' => 'form-check-input',
                                    'id' => 'optionB',
                                    'wire:model' => 'option_field',
                                ]) !!}
                                {!! Form::label('optionB', 'Option B', ['class' => 'form-check-label']) !!}
                                <small>(without Study Kit)</small>
                            </div>

                            <div x-show="$wire.option_field.includes('B')" class="ms-4 mt-2">
                                <div class="form-check">
                                    {!! Form::checkbox('b_checkbox1', 1, $this->b_checkbox1, [
                                        'class' => 'form-check-input',
                                        'id' => 'b_checkbox1',
                                        'wire:model' => 'b_checkbox1',
                                    ]) !!}
                                    {!! Form::label('b_checkbox1', 'Mathematics', ['class' => 'form-check-label']) !!}
                                </div>
                                <div class="form-check mt-2">
                                    {!! Form::checkbox('b_checkbox2', 1, $this->b_checkbox2, [
                                        'class' => 'form-check-input',
                                        'id' => 'b_checkbox2',
                                        'wire:model' => 'b_checkbox2',
                                    ]) !!}
                                    {!! Form::label('b_checkbox2', 'Science', ['class' => 'form-check-label']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div x-show="$wire.option_field.includes('A') && ($wire.a_checkbox1 || $wire.a_checkbox2)">
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        {!! Form::label('password', 'Password', ['class' => 'form-label required']) !!}
                        {!! Form::text('password', $userData->validate_string ?? 'Mitt@123', [
                            'class' => 'form-control',
                            'placeholder' => 'Enter Password',
                        ]) !!}
                    </div>
                </div>

                {{-- @if ($viewOnly != false)
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        {!! Form::label('password', 'Password', ['class' => 'form-label ', 'disabled' => $viewOnly ?? false]) !!}
                        {!! Form::text('password', null, [
                            'class' => 'form-control',
                            'placeholder' => 'Enter Password ',
                            'disabled' => $viewOnly ? 'disabled' : null,
                        ]) !!}
                @endif --}}
                <div class="col-sm-12 text-end">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <button type="reset" class="btn btn-secondary" onclick="window.location.reload();">Reset</button>
                </div>
            </div>


        </div>
    </div>
    </div>

</section>
