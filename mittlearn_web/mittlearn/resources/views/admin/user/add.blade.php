@extends('admin.layouts.master')
@section('content')
    @php
        $flag = 0;
        $hidebutton = null;
        $heading = 'Add';
        if (isset($data) && !empty($data)) {
            $flag = 1;
            $heading = isset($viewOnly) ? 'View' : 'Update';
            $hidebutton = '1';
            // dd($hidebutton);
        }

    @endphp

    <div>
        <div class="pagetitle">
            <h1>{{ $heading }} User</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">User</li>
                </ol>
            </nav>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    @if ($flag != 1)
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-0">Bulk Upload Users</h5>
                                <hr class="form-divider">

                                @livewire('user-bulk-upload', ['roles' => $roles])

                                <!-- Bulk Upload Status/Feedback -->
                                @if (session()->has('errorMsg'))
                                    <div class="alert alert-danger mt-3">
                                        {{ session('errorMsg')[0] }}
                                    </div>
                                @elseif (session()->has('successMsg'))
                                    <div class="alert alert-success mt-3">
                                        {{ session('successMsg') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <div class="card">
                        <div class="card-body">

                            @if ($flag == 1)
                                {{ Form::model($data, ['url' => route('user.save'), 'id' => 'edit-plan-form', 'class' => 'row g-3', 'enctype' => 'multipart/form-data']) }}
                                {{ Form::hidden('id', $data->id) }}
                            @else
                                {{ Form::open(['url' => route('user.save'), 'id' => 'add-plan-form', 'class' => 'row g-3', 'enctype' => 'multipart/form-data']) }}
                            @endif
                            @livewire('role-form', ['roles' => $roles, 'users' => $users, 'salesman' => isset($salesman) ? $salesman : null, 'distributors' => isset($distributors) ? $distributors : null, 'boards' => $boards, 'mediums' => $mediums, 'sections' => $sections, 'classes' => $classes,'courseData' => $courseData, 'subjects' => $subjects, 'cities' => $cities, 'states' => $states, 'schoolList' => $schoolList,'schools' => $schools, 'verify' => $verify ?? null, 'userData' => $data ?? null, 'school_classes' => $school_assigned_class ?? null, 'flag' => $flag, 'viewOnly' => $viewOnly ?? false])
                            {{-- @if ($hidebutton === '1') --}}
                            {{-- <div class="col-sm-12 text-end">
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                    <button type="reset" class="btn btn-secondary"
                                        onclick="window.location.reload();">Reset</button>
                                </div> --}}
                            {{-- @endif --}}

                            {{ Form::close() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script>
        function downloadSampleFile(roleKey) {

            let downloadUrl;
            if (roleKey === 'school_student' || roleKey === 'school_admin' || roleKey === 'school_teacher' || roleKey ===
                'b2c_student' || roleKey === 'd2c_user') {
                downloadUrl = `/admin/sample-files/${roleKey}-sample-file.xlsx`;
            } else {
                downloadUrl = `/admin/sample-files/user_file-sample-file.xlsx`;
            }

            window.location.href = downloadUrl; // Now it's accessible here
        }
    </script>
    <script>
        $(document).ready(function() {
            // Initialize Select2 with custom checkboxes
            document.addEventListener('DOMContentLoaded', function() {
                const multiSelect = document.getElementById('multiSelect');
            });

        });
    </script>
    <script>
        function initSelect2() {
            $(".js-select2").select2({
                closeOnSelect: false,
                placeholder: "Select",
                allowClear: false,
                tags: true
            });
        }
        document.addEventListener("DOMContentLoaded", function() {
            initSelect2();
        });
        document.addEventListener("livewire:load", function() {
            Livewire.hook('message.processed', (message, component) => {
                initSelect2();
            });
        });
 
        document.addEventListener("change", function(event) {
            if (event.target.matches("[wire\\:model='selectedState']")) {
                setTimeout(initSelect2, 1000); // Small delay to allow Livewire to update the DOM
            }
        });
        document.addEventListener("change", function(event) {
            if (event.target.matches("[wire\\:model='selectedSchool']")) {
                setTimeout(initSelect2, 1000); // Small delay to allow Livewire to update the DOM
            }
        });

        document.addEventListener("change", function(event) {
            if (event.target.matches("[wire\\:model='schoolType']")) {
                setTimeout(initSelect2, 1000); // Small delay to allow Livewire to update the DOM
            }
        });
        document.addEventListener("change", function(event) {
            if (event.target.matches("[wire\\:model='schoolRole']")) {
                setTimeout(initSelect2, 1000); // Small delay to allow Livewire to update the DOM
            }
        });
        document.addEventListener("change", function(event) {
            if (event.target.matches("[wire\\:model='selectedSession']")) {
                setTimeout(initSelect2, 1000); 
            }
        });
        document.addEventListener("change", function(event) {
            if (event.target.matches("[wire\\:model='selectedCategory']")) {
                setTimeout(initSelect2, 500); 
            }
        });
        document.addEventListener("change", function(event) {
            if (event.target.matches("[wire\\:model='selectedSubCategory']")) {
                setTimeout(initSelect2, 500); 
            }
        });
    </script>
@endsection
