@extends('admin.layouts.master')

@section('content')
    <div class="pagetitle">
        <h1>ERP Dump Data List</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item active">ERP</li>
            </ol>
        </nav>
    </div>

    {{-- Tab Navigation --}}
    <ul class="nav nav-tabs nav-tabs-bordered mb-3">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('erp-data.schools.index') ? 'active' : '' }}"
                href="{{ route('erp-data.schools.index') }}">Schools</a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('erp-data.students.index') ? 'active' : '' }}"
                href="{{ route('erp-data.students.index') }}">Students</a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('erp-data.teachers.index') ? 'active' : '' }}"
                href="{{ route('erp-data.teachers.index') }}">Teachers</a>
        </li>
    </ul>

    {{-- Tab Content --}}
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        @if (request()->routeIs('erp-data.schools.index'))
                            @include('admin.erpData.school-index', ['datalist' => $datalist])
                        @elseif(request()->routeIs('erp-data.teachers.index'))
                            @include('admin.erpData.teacher-index', ['datalist' => $datalist])
                        @elseif(request()->routeIs('erp-data.students.index'))
                            @include('admin.erpData.student-index', [
                                'datalist' => $datalist,
                                'classes' => $classes,
                            ])
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
