@extends('admin.layouts.master')

@section('content')
    <div class="pagetitle">
        <h1>Jadui Pitara</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item active">Jadui Pitara</li>
            </ol>
        </nav>
    </div>
    @livewire('jadui-pitara')
    <script>
        $(document).ready(function() {
            document.addEventListener('DOMContentLoaded', function() {
                const multiSelect = document.getElementById('multiSelect');
            });
        });

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
        document.addEventListener("change", function(event) {
            if (event.target.matches("[wire\\:model^='selectedSeriesId']")) {
                setTimeout(initSelect2, 500);
            }
        });
        document.addEventListener("change", function(event) {
            if (event.target.matches("[wire\\:model^='rows'][wire\\:model*='.series_id']")) {
                setTimeout(initSelect2, 500);
            }
        });
        document.addEventListener("click", function(event) {
            if (event.target.closest("[wire\\:click^='addRow']")) {
                setTimeout(initSelect2, 500);
            }
        });
    </script>
@endsection
