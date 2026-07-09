@extends('admin.layouts.master')
@section('content')
    @php
        $flag = 0;
        $heading = 'Add';
        if (isset($question) && !empty($question)) {
            $flag = 1;
            $heading = 'Edit';
        }
    @endphp
    <div>
        <div class="pagetitle">
            <h1>{{ $heading }} Question Bank</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Question Bank</li>
                </ol>
            </nav>
        </div>
        {{-- @dd($questions) --}}
        <section class="section">
            @livewire('question-bank', ['question' => isset($question) ? $question : null])
        </section>
    </div>


    {{--  @push('scripts')
        <script>
            function initSelect2() {
                console.log('on page load');

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
                console.log('on page livewireload');

                Livewire.hook('message.processed', (message, component) => {
                    initSelect2();
                });
            });
            document.addEventListener("change", function(event) {
                if (event.target.matches("[wire\\:model='updateChapterName']")) {
                    console.log('after updateChapterName');

                    setTimeout(initSelect2, 2000); // Small delay to allow Livewire to update the DOM
                }
            });
        </script>
    @endpush  --}}
@endsection
