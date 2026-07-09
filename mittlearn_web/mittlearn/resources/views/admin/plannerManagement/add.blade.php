@extends('admin.layouts.master')
@section('content')
    <div>
        <div class="pagetitle">
            <h1>Add Planner</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Planner</li>
                </ol>
            </nav>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Bulk Planner</h5>
                            @livewire('planner-bulk')
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            @livewire('planner-form')
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
