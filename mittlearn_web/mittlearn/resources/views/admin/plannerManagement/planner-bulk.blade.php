@extends('admin.layouts.master')
@section('content')
    <div>
        <div class="pagetitle">
            <h1>Planner Bulk Upload</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Planner Bulk Upload</li>
                </ol>
            </nav>
        </div>
        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            @livewire('planner-bulk')
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
