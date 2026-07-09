@extends('admin.layouts.master')

@section('content')
    <div class="pagetitle">
        <h1>{{ $categoryName }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item">
                    <a href="{{ route('d2c-category.index', ['tab' => 'talent']) }}">Talent &amp; Skills</a>
                </li>
                <li class="breadcrumb-item active">{{ $categoryName }}</li>
            </ol>
        </nav>
    </div>

    <div class="row mt-4">
        <div class="col-lg-12">
            @livewire('talent-skill-digital-content', ['sub_category_id' => $sub_category_id])
        </div>
    </div>
@endsection
