@extends('admin.layouts.master')

@section('content')
@section('breadcrumb')
    <div class="pagetitle">
        <h1>Assign Permissions</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Permissions</li>
                <li class="breadcrumb-item active">Assign</li>
            </ol>
        </nav>
    </div>
@endsection

<section class="section">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-body">
                    @livewire('assign-permission-form', ['permissions' => $permissions])
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
