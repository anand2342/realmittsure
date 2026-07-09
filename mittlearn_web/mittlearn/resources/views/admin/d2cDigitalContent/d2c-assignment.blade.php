@extends('admin.layouts.master')

@section('content')
    <div class="pagetitle">
        <h1>{{ $categoryName }}</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item active">{{ $categoryName }}</li>
            </ol>
        </nav>
    </div>
    @if ($category_id == 37)
        @livewire('d2-c-digital-content-act-worksheets', ['id' => $category_id])
    @else
        @livewire('d2-c-digital-content', ['id' => $category_id])
    @endif
@endsection
