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
    <div class="pagetitle">
        <h1>ERP Dump Data List</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item active">ERP</li>
            </ol>
        </nav>
    </div>


    @livewire('erp-add-school-form', [
        'roles' => $roles,
        'schoolList' => $schoolList,
        'schools' => $schools,
        'users' => $users,
        'salesman' => $salesman,
        'distributors' => $distributors,
        'boards' => $boards,
        'mediums' => $mediums,
        'classes' => $classes,
        'subjects' => $subjects,
        'cities' => $cities,
        'states' => $states,
        'userData' => null, // Pass null for new school
        'school_classes' => null, // Pass null for new school
        'erpData' => $erpData, // Pass the ERP data
        // 'schoolClasses' => $schoolClasses, 
    ])


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
            if (event.target.matches("[wire\\:model='selectedRole']")) {
                setTimeout(initSelect2, 1000); // Small delay to allow Livewire to update the DOM
            }
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
    </script>
@endsection
