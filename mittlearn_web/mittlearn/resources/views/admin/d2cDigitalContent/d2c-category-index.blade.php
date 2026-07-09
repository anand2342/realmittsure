{{-- @extends('admin.layouts.master')

@section('content')
    <div class="pagetitle">
        <h1>D2C Ditial Content Category</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item active">D2C Ditial Content Category</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="card-title">D2C Ditial Content Category</div>
                            </div>
                            <div class="col-sm-6 text-end mt-3">
                            </div>
                        </div>
                        <hr class="formdivider">
                        <div class="table-responsive tbleDiv ">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th><b>Name</b></th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @isset($d2ccategory)
                                        @foreach ($d2ccategory as $item)
                                            <tr>
                                                <td>
                                                    {{ $d2ccategory->currentPage() * $d2ccategory->perPage() - $d2ccategory->perPage() + $loop->iteration . '.' }}
                                                </td>
                                                <td>{{ $item->name }}</td>
                                                <td>
                                                    <a class="btn btn-sm btn-info"
                                                        href="{{ route('d2c-content.assginment', $item->id) }}">
                                                        Digital Content Assign
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endisset

                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-right text-right">
                            @isset($d2ccategory)
                                {!! $d2ccategory->links('pagination::bootstrap-4') !!}
                            @endisset
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection --}}

@extends('admin.layouts.master')

@section('content')
    <div class="pagetitle">
        <h1>D2C Digital Content Category</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item active">D2C Digital Content Category</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="card-title">D2C Digital Content Category</div>
                            </div>
                        </div>
                        <hr class="formdivider">

                        {{-- TABS --}}
                        <ul class="nav nav-tabs nav-tabs-bordered mb-3">
                            <li class="nav-item">
                                <a class="nav-link {{ request('tab', 'academic') === 'academic' ? 'active' : '' }}"
                                    href="{{ request()->fullUrlWithQuery(['tab' => 'academic']) }}">
                                    Academic Digital Content
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request('tab') === 'talent' ? 'active' : '' }}"
                                    href="{{ request()->fullUrlWithQuery(['tab' => 'talent']) }}">
                                    Talent &amp; Skills
                                </a>
                            </li>
                        </ul>

                        {{-- ACADEMIC TAB --}}
                        @if (request('tab', 'academic') === 'academic')
                            <div class="table-responsive tbleDiv">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th><b>Name</b></th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @isset($d2ccategory)
                                            @foreach ($d2ccategory as $item)
                                                <tr>
                                                    <td>
                                                        {{ $d2ccategory->currentPage() * $d2ccategory->perPage() - $d2ccategory->perPage() + $loop->iteration . '.' }}
                                                    </td>
                                                    <td>{{ $item->name }}</td>
                                                    <td>
                                                        <a class="btn btn-sm btn-info"
                                                            href="{{ route('d2c-content.assginment', $item->id) }}">
                                                            Digital Content Assign
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endisset
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-right text-right">
                                @isset($d2ccategory)
                                    {!! $d2ccategory->links('pagination::bootstrap-4') !!}
                                @endisset
                            </div>
                        @endif

                        {{-- TALENT & SKILLS TAB --}}
                        @if (request('tab') === 'talent')
                            <div class="table-responsive tbleDiv">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                        <tr>
                                            <th>S.No</th>
                                            <th><b>Name</b></th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @isset($categories)
                                            @php $sno = 1; @endphp
                                            @foreach ($categories as $category)
                                                {{-- Find the Talent-Skills parent and loop its children --}}
                                                @if ($category->slug === 'talent-skills')
                                                    @foreach ($category->children as $child)
                                                        <tr>
                                                            <td>{{ $sno++ }}.</td>
                                                            <td>{{ $child->name }}</td>
                                                            <td>
                                                                <a class="btn btn-sm btn-info"
                                                                    href="{{ route('talent-skill.assginment', $child->id) }}">
                                                                    Digital Content Assign
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        @endisset
                                    </tbody>
                                </table>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
