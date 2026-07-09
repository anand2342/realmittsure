@extends('admin.layouts.master')

@section('content')
    <div>
        <div class="pagetitle">
            <h1>Olympiad Print Settings</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Olympiad Print Settings</li>
                </ol>
            </nav>
        </div>

        @livewire('olympiad-print-setting', ['settings' => $settings])
    @endsection
