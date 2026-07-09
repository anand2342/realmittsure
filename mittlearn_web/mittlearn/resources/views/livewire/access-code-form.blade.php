<div>
    <ul class="nav nav-tabs tbs " id="classTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link tab-btn {{ $tab === 'embibe' ? 'active' : '' }}"
                wire:click="$set('tab', 'embibe')">Embibe</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link tab-btn {{ $tab === 'olympiad' ? 'active' : '' }}"
                wire:click="$set('tab', 'olympiad')">Olympiad</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link tab-btn {{ $tab === 'digitalContent' ? 'active' : '' }}" data-toggle="modal"
                data-target="#serviceUnAvailable">Mittsure Digital Content</a>
        </li>

        <li class="nav-item" role="presentation">
            <a class="nav-link tab-btn {{ $tab === 'lumalearn' ? 'active' : '' }}" data-toggle="modal"
                data-target="#serviceUnAvailable">Luma
                Learn</a>
        </li>

        {{-- <li class="nav-item" role="presentation">
            <a class="nav-link tab-btn {{ $tab === 'digitalContent' ? 'active' : '' }}"
                wire:click="$set('tab', 'digitalContent')">Mittsure Digital Content</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link tab-btn {{ $tab === 'lumalearn' ? 'active' : '' }}"
                wire:click="$set('tab', 'lumalearn')">Luma
                Learn</a>
        </li> --}}

    </ul>

    <div class="tab-content mt-3" id="classTabContent">
        @if ($tab == 'embibe')
            <div class="text-center">
                @if (session()->has('successMsg'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-1"></i>
                        @if (is_array(session('successMsg')))
                            {{ implode(', ', session('successMsg')) }}
                        @else
                            {{ session('successMsg') }}
                        @endif
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            </div>
            <div class="row mt-4">
                <!-- Download Button -->
                <div class="col-md-3 col-sm-12 mt-4">
                    <h5 class="card-title">Teachlite (For Teachers)</h5>

                </div>
                <div class="col-md-3 col-sm-12 mt-4">
                    <a href="{{ asset('admin/sample-files/access-code-teachlite-sample-file.xlsx') }}"
                        class="btn btn-primary">Download sample file</a>
                </div>

                <!-- File Upload Form -->
                <div class="col-md-6 col-sm-12 mt-4 gap-2">
                    <form wire:submit.prevent="uploadEmbibeAccessCodeTeachlite" class="d-flex align-items-center">
                        <div class="row">
                            <input type="file" wire:model="file" class="form-control">
                            @error('file')
                                <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                            @enderror

                        </div>
                        <div wire:loading wire:target="uploadEmbibeAccessCodeTeachlite" style="margin-left: 30px "
                            class="spinner-border text-primary " role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <button type="submit" class="btn btn-primary" style="margin-left: 55px ">Upload
                            File</button>

                    </form>
                </div>
            </div>
            <hr class="form-divider">
            <div class="row mt-4">
                <!-- Download Button -->
                <div class="col-md-3 col-sm-12 mt-4">
                    <h5 class="card-title">Mittsure Lens (For Students)</h5>

                </div>
                <div class="col-md-3 col-sm-12 mt-4">
                    <a href="{{ asset('admin/sample-files/access-code-mittlense-sample-file.csv') }}"
                        class="btn btn-primary">Download sample file</a>
                </div>

                <!-- File Upload Form -->
                <div class="col-md-6 col-sm-12 mt-4 gap-2">
                    <form wire:submit.prevent="uploadEmbibeAccessCodeMittlense" class="d-flex align-items-center">
                        <div class="row">
                            <input type="file" wire:model="file" class="form-control">
                            @error('file')
                                <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                            @enderror

                        </div>
                        <div wire:loading wire:target="uploadEmbibeAccessCodeMittlense" style="margin-left: 30px "
                            class="spinner-border text-primary " role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <button type="submit" class="btn btn-primary" style="margin-left: 55px ">Upload
                            File</button>

                    </form>
                </div>
            </div>
        @elseif($tab == 'olympiad')
            <div class="row">
                <div class="col-md-6 col-sm-4 col-xs-12 mb-3">
                    {{-- @dump($olmpiadBookSeries) --}}
                    <label class="form-label required">Select Book Series</label>
                    {!! Form::select('book_series_id', $olmpiadBookSeries, null, [
                        'class' => 'form-select',
                        'wire:model' => 'book_series_id',
                        'required',
                    ]) !!}
                    @error('book_series_id')
                        <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6 col-sm-4 col-xs-12 mb-3">
                    <label class="form-label required">Select Class</label>
                    {!! Form::select('class_id', $olympiadClasses, null, [
                        'class' => 'form-select',
                        'placeholder' => 'Select a Class',
                        'wire:model' => 'class_id',
                        // 'wire:change' => 'loadBookSet($event.target.value)',
                        'required',
                    ]) !!}
                    @error('class_id')
                        <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6 col-sm-4 col-xs-12 mb-3">
                    <div class="form-group bginput">
                        {!! Form::label('subject_id', 'Select Subject', ['class' => 'form-label required']) !!}
                        {!! Form::select('subject_id', $olympiadSubjects, null, [
                            'class' => 'form-select',
                            'placeholder' => 'Select a Subject',
                            'wire:model' => 'subject_id',
                        ]) !!}
                    </div>
                    @error('subject_id')
                        <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6 col-sm-4 col-xs-12 mb-3">
                    @if (!$showNewPrefixInput)
                        <label class="form-label required">Select Prefix</label>
                        <select class="form-select" wire:model="prefix"
                            wire:change="handlePrefixChange($event.target.value)" required>
                            <option value="">Select a prefix</option>
                            @foreach ($prefixes as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                            <option value="add_new">Add New</option>
                        </select>
                    @else
                        <label class="form-label required">Select Prefix</label>
                        <input type="text" class="form-control" wire:model.lazy="prefix"
                            oninput="this.value = this.value.toUpperCase()" wire:change="saveNewPrefix"
                            placeholder="Enter new prefix">
                    @endif
                    @error('prefix')
                        <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6 col-sm-4 col-xs-12 mb-3">
                    <label class="form-label required">Code Length</label>
                    {{ Form::text('code_length', '', ['class' => 'form-control', 'placeholder' => 'Enter length', 'wire:model' => 'code_length']) }}
                    @error('code_length')
                        <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6 col-sm-4 col-xs-12 mb-3">
                    <label class="form-label required">Number of codes to be generated</label>
                    {!! Form::text('numbers_of_code', '', [
                        'class' => 'form-control',
                        'placeholder' => 'No. of codes to be generated',
                        'wire:model' => 'numbers_of_code',
                        'max' => '1000',
                        'required',
                    ]) !!}
                    @error('numbers_of_code')
                        <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6 col-sm-4 col-xs-12 mb-3">
                    <label class="form-label required">Video Content Access Validity</label>
                    {{ Form::date('end_date', old('end_date', '2026-03-31'), [
                        'class' => 'form-control',
                        'required',
                        'wire:model' => 'end_date',
                        'placeholder' => 'Enter your name here',
                    ]) }}
                    @error('end_date')
                        <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6 col-sm-4 col-xs-12 mb-3">
                    <label class="form-label required">User Name (Code Generator)</label>
                    {{ Form::text('code_generator', null, ['class' => 'form-control', 'required', 'wire:model' => 'code_generator', 'placeholder' => 'Enter your name here']) }}
                    @error('code_generator')
                        <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="text-right mt-5">
                    {{-- <button class="btn btn-primary" wire:click="generatePreviewOlympiadCodes">
                    Generate and Preview Code </button> --}}
                    <button class="btn btn-primary" wire:click="generatePreviewOlympiadCodes"
                        wire:loading.attr="disabled" wire:target="generatePreviewOlympiadCodes">
                        <span wire:loading.remove wire:target="generatePreviewOlympiadCodes">Generate and Preview
                            Code</span>
                        <span wire:loading wire:target="generatePreviewOlympiadCodes">
                            <div class="spinner-border spinner-border-sm text-light me-2" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            Generating...
                        </span>
                    </button>

                    <button type="button" class="btn btn-secondary" wire:click="resetForm">Reset</button>
                </div>
            </div>
        @else
            <div class="row">
                <p>Code Generation Type</p>
                <div class="col-md-6 col-sm-4 col-xs-12 mb-2">
                    <input type="radio" id="random" value="random" name="generationType"
                        {{ $generationType === 'random' ? 'checked' : '' }}
                        wire:change="$set('generationType', 'random')" class="form-check-input">
                    <label for="random" class="form-check-label">Random Code</label>
                </div>
                <div class="col-md-6 col-sm-4 col-xs-12 mb-2">
                    <input type="radio" id="custom" value="custom" name="generationType"
                        {{ $generationType === 'custom' ? 'checked' : '' }}
                        wire:change="$set('generationType', 'custom')" class="form-check-input">
                    <label for="custom" class="form-check-label">Custom Code</label>
                </div>
                <hr class="form-divider">

                @if ($generationType === 'custom')
                    <div class="col-md-6 col-sm-4 col-xs-12 mb-3">
                        @if (!$showNewPrefixInput)
                            <label class="form-label required">Select Prefix</label>
                            <select class="form-select" wire:model="prefix"
                                wire:change="handlePrefixChange($event.target.value)" required>
                                <option value="">Select a prefix</option>
                                @foreach ($prefixes as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                                <option value="add_new">Add New</option>
                            </select>
                        @else
                            <label class="form-label required">Select Prefix</label>
                            <input type="text" class="form-control" wire:model.lazy="prefix"
                                oninput="this.value = this.value.toUpperCase()" wire:change="saveNewPrefix"
                                placeholder="Enter new prefix">
                        @endif
                        @error('prefix')
                            <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6 col-sm-4 col-xs-12 mb-3">
                        <label class="form-label required">Code Length</label>
                        {{ Form::text('code_length', '', ['class' => 'form-control', 'placeholder' => 'Enter length', 'wire:model' => 'code_length']) }}
                    </div>
                    <div class="col-md-6 col-sm-4 col-xs-12 mb-3">
                        <label class="form-label required">Select Book Series</label>
                        {!! Form::select('book_series_id', $bookSeries, null, [
                            'class' => 'form-select',
                            'placeholder' => 'Select a Book Series',
                            'wire:model' => 'book_series_id',
                            'required',
                        ]) !!}
                        @error('book_series_id')
                            <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6 col-sm-4 col-xs-12 mb-3">
                        <label class="form-label required">Select Class</label>
                        {!! Form::select('class_id', $schoolClasses, null, [
                            'class' => 'form-select',
                            'placeholder' => 'Select a Class',
                            'wire:model' => 'class_id',
                            'wire:change' => 'loadBookSet($event.target.value)',
                            'required',
                        ]) !!}
                        @error('class_id')
                            <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                        @enderror
                    </div>
                @endif
                @if ($tab === 'digitalContent')
                    <div class="col-md-6 col-sm-4 col-xs-12 mb-3">
                        <label class="form-label required">Select School</label>
                        {!! Form::select('school_id', $schools, null, [
                            'class' => 'form-select',
                            'placeholder' => 'Select a School',
                            'wire:model' => 'school_id',
                            'required',
                        ]) !!}
                        @error('school_id')
                            <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                        @enderror
                    </div>
                @endif
                <div class="col-md-6 col-sm-4 col-xs-12 mb-3">
                    <label class="form-label">Select Board</label>
                    {!! Form::select('board_id', $boards, null, [
                        'class' => 'form-select',
                        'placeholder' => 'Select a Board',
                        'wire:model' => 'board_id',
                        'wire:change' => 'loadBookSet($event.target.value)',
                    ]) !!}
                    @error('board_id')
                        <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6 col-sm-4 col-xs-12 mb-3">
                    <label class="form-label">Select Medium</label>
                    {!! Form::select('medium_id', $mediums, null, [
                        'class' => 'form-select',
                        'placeholder' => 'Select a Medium',
                        'wire:model' => 'medium_id',
                        'wire:change' => 'loadBookSet($event.target.value)',
                    ]) !!}
                    @error('medium_id')
                        <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                    @enderror
                </div>
                @if ($tab === 'digitalContent')
                    <div class="col-md-6 col-sm-4 col-xs-12 mb-3">
                        <label class="form-label required">Select Book Series</label>
                        {!! Form::select('book_series_id', $bookSeries, null, [
                            'class' => 'form-select',
                            'placeholder' => 'Select a Book Series',
                            'wire:model' => 'book_series_id',
                            'required',
                        ]) !!}
                        @error('book_series_id')
                            <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                        @enderror
                    </div>
                @endif
                <div class="col-md-6 col-sm-4 col-xs-12 mb-3">
                    <label class="form-label required">Select Class</label>
                    {!! Form::select('class_id', $schoolClasses, null, [
                        'class' => 'form-select',
                        'placeholder' => 'Select a Class',
                        'wire:model' => 'class_id',
                        'wire:change' => 'loadBookSet($event.target.value)',
                        'required',
                    ]) !!}
                    @error('class_id')
                        <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-6 col-sm-4 col-xs-12 mb-3">
                    <label class="form-label">Choose Option</label>
                    <div>
                        <input type="radio" id="book_set_option" value="book_set" name="option"
                            {{ $selectedOption === 'book_set' ? 'checked' : '' }}
                            wire:click="$set('selectedOption', 'book_set')" class="form-check-input">
                        <label for="book_set_option" class="form-check-label">Book Set</label>

                        <input type="radio" id="subject_option" value="subject" name="option"
                            {{ $selectedOption === 'subject' ? 'checked' : '' }}
                            wire:click="$set('selectedOption', 'subject')" class="form-check-input">
                        <label for="subject_option" class="form-check-label">Subject</label>
                    </div>
                </div>

                <div class="col-md-6 col-sm-4 col-xs-12 mb-3"
                    style="display: {{ $selectedOption === 'book_set' ? 'block' : 'none' }};">
                    <label class="form-label required">Select Book Set</label>
                    {!! Form::select('book_set_id', $bookSets, null, [
                        'class' => 'form-select',
                        'placeholder' => 'Select a Book Set',
                        'wire:model' => 'book_set_id',
                        'required',
                    ]) !!}
                    @error('book_set_id')
                        <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6 col-sm-4 col-xs-12 mb-3"
                    style="display: {{ $selectedOption === 'subject' ? 'block' : 'none' }};">
                    <div class="form-group bginput" wire:ignore>
                        {!! Form::label('subject_ids', 'Select Subject', ['class' => 'form-label required']) !!}
                        {!! Form::select('subject_ids[]', $subjects, null, [
                            'class' => 'js-select2 form-select',
                            'multiple' => 'multiple',
                            'wire:model' => 'subject_ids',
                        ]) !!}
                    </div>
                    @error('subject_ids')
                        <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                    @enderror
                </div>
                {!! Form::hidden('selectedSubject[]', null, [
                    'class' => 'form-select',
                    'wire:model' => 'selectedSubject',
                ]) !!}
                <div class="col-md-6 col-sm-4 col-xs-12 mb-3">
                    <label class="form-label required">Number of codes to be generated</label>
                    {!! Form::text('numbers_of_code', '', [
                        'class' => 'form-control',
                        'placeholder' => 'No. of Student strength (Book sold)',
                        'wire:model' => 'numbers_of_code',
                        'max' => '1000',
                        'required',
                    ]) !!}
                    @error('numbers_of_code')
                        <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6 col-sm-4 col-xs-12 mb-3">
                    <label class="form-label required">Start Date</label>
                    {{ Form::date('start_date', null, ['class' => 'form-control', 'required', 'wire:model' => 'start_date', 'min' => \Carbon\Carbon::today()->toDateString()]) }}
                    @error('start_date')
                        <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-6 col-sm-4 col-xs-12 mb-3">
                    <label class="form-label required">Expiration Date</label>
                    {{ Form::date('end_date', null, ['class' => 'form-control', 'required', 'wire:model' => 'end_date']) }}
                    @error('end_date')
                        <span class="text-danger" style="font-size: 13px;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="text-right mt-5">
                    {{-- <button class="btn btn-primary" wire:click="generatePreviewCodes">
                    Generate and Preview Code </button> --}}
                    <button class="btn btn-primary" wire:click="generatePreviewCodes" wire:loading.attr="disabled"
                        wire:target="generatePreviewCodes">
                        <span wire:loading.remove wire:target="generatePreviewCodes">Generate and Preview
                            Code</span>
                        <span wire:loading wire:target="generatePreviewCodes">
                            <div class="spinner-border spinner-border-sm text-light me-2" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            Generating...
                        </span>
                    </button>

                    <button type="button" class="btn btn-secondary" wire:click="resetForm">Reset</button>
                </div>
            </div>
        @endif
    </div>
    <!-- Embibe accessCodePreviewModal Structure -->
    <div x-data="{ open: @entangle('isModalOpen') }" x-show="open" x-transition @click.away="open = false"
        @keydown.escape.window="open = false" class="modal fade" tabindex="-1" :class="{ 'show d-block': open }"
        style="background: rgba(0, 0, 0, 0.5);">
        <div class="modal-dialog modal-xl">
            @if (session()->has('errorMsg'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-octagon me-1"></i>
                    <strong>Error:</strong>
                    @if (is_array(session('errorMsg')))
                        {{ implode(', ', session('errorMsg')) }}
                    @else
                        {{ session('errorMsg') }}
                    @endif
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Uploaded Data</h5>
                    <button type="button" class="btn-close" x-on:click="open = false; @this.closeModal()"></button>
                </div>
                @if (!empty($uploadedData))
                    <form wire:submit.prevent="processSelectedData">
                        <div class="modal-body" style="max-height: 70vh; overflow-y: auto;">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" id="select-all-checkbox" x-data="{ isChecked: false }"
                                                x-model="isChecked"
                                                @click="$dispatch('select-all', { isChecked: !isChecked })">
                                            <label for="select-all-checkbox" style="margin-left: 5px;">Select
                                                All</label>
                                        </th>
                                        @foreach ($headers as $header)
                                            <th>{{ $header }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody x-data
                                    @select-all.window="event => {
                                        const checkboxes = [...$el.querySelectorAll('input[type=checkbox]')];
                                        checkboxes.forEach(c => {
                                            c.checked = event.detail.isChecked;
                                            if (event.detail.isChecked) {
                                                @this.set('selectedData', [...@this.get('selectedData'), c.value]);
                                            } else {
                                                @this.set('selectedData', []);
                                            }
                                        });
                                    }">
                                    @foreach ($uploadedData as $rowKey => $row)
                                        <tr>
                                            <td>
                                                <input type="checkbox" wire:model="selectedData"
                                                    value="{{ $rowKey }}">
                                            </td>
                                            @foreach ($headers as $header)
                                                <td>
                                                    @php
                                                        $convertedHeader = $this->convertToSnakeCase($header); // Ensure this matches the conversion in $convertedRow
                                                    @endphp {{ $row[$header] }}
                                                    @if (isset($rowErrors[$rowKey]) && isset($rowErrors[$rowKey][$convertedHeader]))
                                                        <div class="errormsg p-1" style="background-color: #ff2137">
                                                            <span class="text-white small d-block p-0">
                                                                @foreach ($rowErrors[$rowKey][$convertedHeader] as $error)
                                                                    {{ $error }}<br>
                                                                @endforeach
                                                            </span>
                                                        </div>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Process Selected
                                Data</button>
                            <button type="button" class="btn btn-secondary"
                                x-on:click="open = false; @this.closeModal()">Close</button>
                        </div>
                    </form>
                @else
                    <p>No data available to display.</p>
                @endif
            </div>
        </div>
    </div>
    <!-- accessCodePreviewModal  Modal Structure -->
    <div x-data="{ open: @entangle('isPreviewModalOpen') }" x-show="open" x-transition @click.away="open = false"
        @keydown.escape.window="open = false" class="modal fade" tabindex="-1"
        aria-labelledby="accessCodePreviewModalLabel" aria-hidden="true" x-init="() => {
            $watch('open', value => {
                if (value) {
                    document.body.classList.add('modal-open');
                    let backdrop = document.createElement('div');
                    backdrop.className = 'modal-backdrop fade show';
                    document.body.appendChild(backdrop);
                } else {
                    document.body.classList.remove('modal-open');
                    document.querySelector('.modal-backdrop')?.remove();
                }
            })
        }"
        :class="{ 'show d-block': open }">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title w-100 fw-bold" id="accessCodePreviewModalLabel">
                        Preview Access Codes
                    </h5>
                    <button type="button" class="btn-close" x-on:click="open = false; @this.closeModal()"></button>

                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-between">
                        <p class="mb-0 fs-7"><b class="fw-semibold">Type:</b>
                            {{ $accessCodes[0]['series_name'] ?? 'N/A' }}</p>
                        <p class="mb-0 fs-7"><b class="fw-semibold">School Name:</b>
                            {{ $accessCodes[0]['school_name'] ?? 'N/A' }}</p>
                    </div>
                    <hr class="form-divider">
                    <div class="d-flex justify-content-between">
                        <p class="mb-0 fs-7"><b class="fw-semibold">Class:</b>
                            {{ $accessCodes[0]['class_name'] ?? 'N/A' }}</p>
                        <p class="mb-0 fs-7"><b class="fw-semibold">Start Date:</b>
                            {{ $accessCodes[0]['generation_date'] ?? 'N/A' }}</p>
                    </div>
                    <hr class="form-divider">
                    <div class="d-flex justify-content-between">
                        <p class="mb-0  fs-7"><b class="fw-semibold">Expiration Date:</b>
                            {{ $accessCodes[0]['expiration_date'] ?? 'N/A' }}</p>
                    </div>
                    <hr class="form-divider">
                    @if (!empty($accessCodes))
                        <div class="table-responsive tbleDiv">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Code</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($accessCodes as $code)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $code['code'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p>No access codes available for preview.</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="open = false">Close</button>
                    <button type="button" class="btn btn-success" wire:click="saveCodes"
                        wire:loading.attr="disabled" wire:target="saveCodes">
                        <span wire:loading.remove wire:target="saveCodes">Save Codes</span>
                        <span wire:loading wire:target="saveCodes">
                            <div class="spinner-border spinner-border-sm text-light me-2" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            Saving...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <!-- accessCode olmpiad PreviewModal  Modal Structure -->
    <div x-data="{ open: @entangle('isPreviewModalOpenOlympiad') }" x-show="open" x-transition @click.away="open = false"
        @keydown.escape.window="open = false" class="modal fade" tabindex="-1"
        aria-labelledby="accessCodePreviewModalLabel" aria-hidden="true" x-init="() => {
            $watch('open', value => {
                if (value) {
                    document.body.classList.add('modal-open');
                    let backdrop = document.createElement('div');
                    backdrop.className = 'modal-backdrop fade show';
                    document.body.appendChild(backdrop);
                } else {
                    document.body.classList.remove('modal-open');
                    document.querySelector('.modal-backdrop')?.remove();
                }
            })
        }"
        :class="{ 'show d-block': open }">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title w-100 fw-bold" id="accessCodePreviewModalLabel">
                        Preview Access Codes
                    </h5>
                    <button type="button" class="btn-close" x-on:click="open = false; @this.closeModal()"></button>

                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-between">
                        <p class="mb-0 fs-7"><b class="fw-semibold">Type:</b>
                            {{ $accessCodes[0]['series_name'] ?? 'N/A' }}</p>
                        <p class="mb-0 fs-7"><b class="fw-semibold">Class:</b>
                            {{ $accessCodes[0]['class_name'] ?? 'N/A' }}</p>
                    </div>
                    <hr class="form-divider">
                    <div class="d-flex justify-content-between">
                        <p class="mb-0 fs-7"><b class="fw-semibold">Subject:</b>
                            {{ $accessCodes[0]['subject_name'] ?? 'N/A' }}</p>
                        <p class="mb-0  fs-7"><b class="fw-semibold">Expiration Date:</b>
                            {{ $accessCodes[0]['expiration_date'] ?? 'N/A' }}</p>
                    </div>

                    <hr class="form-divider">
                    @if (!empty($accessCodes))
                        <div class="table-responsive tbleDiv">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Code</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($accessCodes as $code)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $code['code'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p>No access codes available for preview.</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="open = false">Close</button>
                    <button type="button" class="btn btn-success" wire:click="saveOlympiadCodes"
                        wire:loading.attr="disabled" wire:target="saveOlympiadCodes">
                        <span wire:loading.remove wire:target="saveOlympiadCodes">Save Codes</span>
                        <span wire:loading wire:target="saveOlympiadCodes">
                            <div class="spinner-border spinner-border-sm text-light me-2" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            Saving...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="serviceUnAvailable" tabindex="-1" role="dialog"
        aria-labelledby="serviceUnAvailableLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="serviceUnAvailableLabel">🚫 Process Unavailable! 🚫</h5>
                    {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button> --}}
                </div>
                <div class="modal-body">
                    This feature isn't activated at the moment. 🌟 Feel free to explore other functionalities and enjoy
                    an exceptional experience! 🎉 </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


</div>


@push('scripts')
    <script>
        document.addEventListener('livewire:load', function() {
            Livewire.hook('message.processed', (message, component) => {
                $('.js-select2').select2();
            });
        });

        document.addEventListener('livewire:init', function() {
            initializeSelect2();
        });

        document.addEventListener('livewire:update', function() {
            initializeSelect2();
        });

        function initializeSelect2() {
            $(".js-select2").select2({
                closeOnSelect: false,
                placeholder: "--Select--",
                allowClear: false,
                tags: true
            });

            // Ensure Livewire can capture the selected value
            $(".js-select2").on('change', function(e) {
                let selectedValues = $(this).val();
                @this.set('selectedSubject', selectedValues); // Sync with Livewire
            });
        }
        // // Sync Select2 changes with Livewire

        document.addEventListener('openModal', () => {
            const modalElement = new bootstrap.Modal(document.getElementById('accessCodePreviewModal'));
            modalElement.show();
        });

        document.addEventListener('codeSaved', function() {
            // Use Bootstrap's modal method to hide the modal
            const modalElement = document.getElementById('accessCodePreviewModal');
            const modalInstance = bootstrap.Modal.getInstance(modalElement);
            if (modalInstance) {
                modalInstance.hide();
            }
        });
        document.addEventListener('codeSavedOlympiad', function() {
            // Use Bootstrap's modal method to hide the modal
            const modalElement = document.getElementById('accessCodePreviewModal');
            const modalInstance = bootstrap.Modal.getInstance(modalElement);
            if (modalInstance) {
                modalInstance.hide();
            }
        });
    </script>
@endpush
