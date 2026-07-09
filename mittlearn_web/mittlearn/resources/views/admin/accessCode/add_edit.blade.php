@extends('admin.layouts.master')

@section('content')
    @php
        $isEditMode = 0;
        $heading = 'Add';
        if (isset($data_row) && !empty($data_row)) {
            $isEditMode = 1;
            $heading = 'Update';
        }
    @endphp
    <div>
        <div class="pagetitle">
            <h1>Access Code Management</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Access Code Management</li>
                </ol>
            </nav>
        </div>

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title pb-0">Access Code Management</h5>
                            <hr class="form-divider">
                            {{-- Create access code process using livewire --}}
                            @livewire('access-code-form')
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
