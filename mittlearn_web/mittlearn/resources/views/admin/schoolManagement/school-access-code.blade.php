@extends('admin.layouts.master')

@section('content')
    <div class="pagetitle">
        <h1>{{ $school->name ?? '' }} Access Codes</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item active">School Access Codes</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-md-12 col-lg-12 col-sm-12 col-xs-12">
                <!-- Create Tabs Header -->
                <div class="card">
                    <ul class="nav nav-tabs tbs " id="classTabs" role="tablist">
                        @foreach ($accessCodes->sortByDesc(function ($classItems) {
            return (int) str_replace('Class ', '', $classItems->first()->class->name);
        }) as $classId => $classItems)
                            <li class="nav-item" role="presentation">
                                <a class="nav-link tab-btn {{ $loop->first ? 'active' : '' }}" id="tab-{{ $classId }}"
                                    data-bs-toggle="tab" href="#class-{{ $classId }}" role="tab"
                                    aria-controls="class-{{ $classId }}" aria-selected="true">
                                    {{ $classItems->first()->class->name ?? 'Class ' . $classId }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <!-- Tab Content Section -->
                    <div class="tab-content" id="classTabContent">
                        @foreach ($accessCodes->sortByDesc(function ($classItems) {
            return (int) str_replace('Class ', '', $classItems->first()->class->name);
        }) as $classId => $classItems)
                            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                                id="class-{{ $classId }}" role="tabpanel" aria-labelledby="tab-{{ $classId }}">
                                <div class=" info-card sales-card">
                                    <div class="card-body">
                                        <h5 class="card-title d-flex justify-content-between align-items-center">
                                            <span class="cardtitleName"><b class="fw-semibold">Board:</b>
                                                {{ $classItems->first()->board->name ?? 'N/A' }}</span>
                                            <span class="cardtitleName"><b class="fw-semibold">Medium:</b>
                                                {{ $classItems->first()->medium->name ?? 'N/A' }}</span>
                                            <span class="cardtitleName"><b class="fw-semibold">Class:</b>
                                                {{ $classItems->first()->class->name ?? 'N/A' }}</span>
                                            <div class="dropdown">
                                                <a href="#" class="btn btn-sm btn-success dropdown-toggle"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    Export {{ $classItems->first()->class->name ?? 'N/A' }} Code
                                                </a>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item"
                                                            href="{{ route('class.access.code.export.excel', $classId) }}">Export
                                                            as
                                                            Excel</a></li>
                                                    <li><a class="dropdown-item"
                                                            href="{{ route('class.access.code.export.csv', $classId) }}">Export as
                                                            CSV</a>
                                                    </li>
                                                    <li><a class="dropdown-item"
                                                            href="{{ route('class.access.code.export.print', $classId) }}"
                                                            target="_blank">Export as Print</a></li>
                                                </ul>
                                            </div>
                                        </h5>
                                        <div class="table-responsive tbleDiv ">
                                            <table class="table table-striped table-bordered datatable ">
                                                <thead>
                                                    <tr>
                                                        <th>S.No</th>
                                                        <th><b>Access Code</b></th>
                                                        <th><b>Status</b></th>
                                                        <th><b>Used By</b></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($classItems as $index => $data)
                                                        <tr>
                                                            <td>{{ $loop->iteration }}.</td>
                                                            <td>{{ $data->access_code ?? 'N/A' }}</td>
                                                            <td>{{ ucwords($data->status) }}</td>
                                                            <td>{{ $data->usedBy->name ?? '-' }}</td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

    </section>
@endsection

