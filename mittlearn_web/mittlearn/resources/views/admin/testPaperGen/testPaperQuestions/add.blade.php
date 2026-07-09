@extends('admin.layouts.master')
@section('content')
    <div>
        <div class="pagetitle">
            <h1>Add Test Paper Question</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">Test Paper Question</li>
                </ol>
            </nav>
        </div>
        <section class="section">
            @livewire('test-paper', ['testPaperId' => $id])
        </section>
    </div>
@endsection
