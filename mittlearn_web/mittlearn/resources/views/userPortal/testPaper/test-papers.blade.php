@extends('userPortal.layouts.master')
@section('content')
    <div class="dashboardMain p-4">
        @include('admin.layouts.flash-messages')
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">My Tests</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tests</li>
            </ol>
        </nav>
        <style>
            .badge {
                color: #ffffff !important;
            }
        </style>
        <div class="row">
            <div class="col-lg-12 mb-3">
                <div class="cardBox">
                    <h2 class="fs-6 fw-semibold mb-4">Test Paper List</h2>
                    <ul class="nav nav-tabs tbs border-0 onlineTabs widthFit mb-4 ">
                        <li class="nav-item">
                            <button class="nav-link @if ($isActiveTab == 'ongoingTest' && request()->query('isActiveTab') == '') active @endif" data-bs-toggle="tab"
                                data-bs-target="#ongoingTest" type="button">Ongoing Test</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#upcomingTest"
                                type="button">Upcoming Test</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link @if (request()->query('isActiveTab') == 'completedTest') active @endif" data-bs-toggle="tab"
                                data-bs-target="#completedTest" type="button">Completed Test</button>
                        </li>
                        <li class="nav-item">
                            <button class="nav-link @if (request()->query('isActiveTab') == 'expiredTest') active @endif" data-bs-toggle="tab"
                                data-bs-target="#expiredTest" type="button">Expired Test <small> (time over & not
                                    attempted)</small></button>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane fade @if ($isActiveTab == 'ongoingTest' && request()->query('isActiveTab') == '') show active @endif" id="ongoingTest">
                            <div class="table-responsive tbleDiv paperListtbl">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Test Name</th>
                                            <th>Subject</th>
                                            <th>Start Date Time</th>
                                            <th>End Date Time</th>
                                            <th>Passing percentage</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!$ongoingTests->isEmpty())
                                            @foreach ($ongoingTests as $data)
                                                <tr>
                                                    <td>{{ $data->testPaper->title }}</td>
                                                    <td>{{ $data->testPaper->subject->name }}</td>
                                                    <td>{{ $data->testPaper->indian_start_date_time ?? 'N/A' }}</td>
                                                    <td>{{ $data->testPaper->indian_end_date_time ?? 'N/A' }}</td>
                                                    <td>{{ $data->testPaper->min_passing_percentage }}</td>
                                                    <td>
                                                        <a href="{{ route('up.test.paper.question', ['id' => $data->test_id]) }}"
                                                            class="btn btn-primary-gradient rounded-1">Attempt</a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="6" class="text-center">No ongoing tests found</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class=" tab-pane fade" id="upcomingTest">
                            <div class="table-responsive tbleDiv paperListtbl">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Test Name</th>
                                            <th>Subject</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                            <th>Passing percentage</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!$upcomingTests->isEmpty())
                                            @foreach ($upcomingTests as $data)
                                                <tr>
                                                    <td>{{ $data->testPaper->title }}</td>
                                                    <td>{{ $data->testPaper->subject->name }}</td>
                                                    <td>{{ $data->testPaper->indian_start_date_time ?? 'N/A' }}</td>
                                                    <td>{{ $data->testPaper->indian_end_date_time ?? 'N/A' }}</td>
                                                    <td>{{ $data->testPaper->min_passing_percentage }}</td>
                                                    <td><button type="button"
                                                            class="btn btn-secondary py-2 rounded-1">Attempt</button></td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="6" class="text-center">No ongoing tests found</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade @if (request()->query('isActiveTab') == 'completedTest') show active @endif" id="completedTest">
                            <div class="table-responsive tbleDiv paperListtbl">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Test Name</th>
                                            <th>Subject</th>
                                            <th>Test Time</th>
                                            <th>Submitted Time</th>
                                            <th>Passing percentage</th>
                                            <th>Obtained percentage</th>
                                            <th>Result</th>
                                            {{-- <th>Action</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!$completedTests->isEmpty())
                                            @foreach ($completedTests as $data)
                                                @php
                                                    $result = $data->result;
                                                    $isPending = \App\Models\TestAnswer::where(
                                                        'test_id',
                                                        $data->test_id,
                                                    )
                                                        ->where('user_id', $data->user_id)
                                                        ->where('is_checked', 0)
                                                        ->exists();
                                                @endphp
                                                <tr>
                                                    <td>{{ $data->testPaper->title ?? 'N/A' }}</td>
                                                    <td>{{ $data->testPaper->subject->name ?? 'N/A' }}</td>
                                                    <td>{{ $data->testPaper->indian_start_date_time ?? 'N/A' }} -
                                                        {{ $data->testPaper->indian_end_date_time ?? 'N/A' }}</td>
                                                    <td>{{ $result->created_at ?? 'N/A' }}</td>
                                                    <td>{{ $data->result->min_passing_percentage ?? 'N/A' }}</td>
                                                    {{-- Obtained Percentage --}}
                                                    <td>
                                                        @if ($isPending)
                                                            <span class="text-warning">Checking Pending</span>
                                                        @elseif ($result)
                                                            {{ $result->obtained_percentage }}%
                                                        @else
                                                            <span class="text-muted">N/A</span>
                                                        @endif
                                                    </td>
                                                    {{-- Result --}}
                                                    <td>
                                                        @if ($isPending)
                                                            <span class="badge bg-warning text-dark">Pending</span>
                                                        @elseif ($result)
                                                            <span
                                                                class="badge bg-{{ $result->result == 'Pass' ? 'success' : 'danger' }} text-white">
                                                                {{ $result->result }}
                                                            </span>
                                                        @else
                                                            <span class="badge bg-secondary">N/A</span>
                                                        @endif
                                                    </td>
                                                    {{-- <td><button type="button"
                                                            class="btn btn-primary-gradient py-2 rounded-1">View
                                                            view</button></td> --}}
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="6" class="text-center">No completed tests found</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade @if (request()->query('isActiveTab') == 'expiredTest') show active @endif" id="expiredTest">
                            <div class="table-responsive tbleDiv paperListtbl">
                                <table class="table mb-0">
                                    <thead>
                                        <tr>
                                            <th>Test Name</th>
                                            <th>Subject</th>
                                            <th>Start Time</th>
                                            <th>End Time</th>
                                            <th>Passing percentage</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if (!$expiredTests->isEmpty())
                                            @foreach ($expiredTests as $data)
                                                <tr>
                                                    <td>{{ $data->testPaper->title }}</td>
                                                    <td>{{ $data->testPaper->subject->name }}</td>
                                                    <td>{{ $data->testPaper->indian_start_date_time ?? 'N/A' }}</td>
                                                    <td>{{ $data->testPaper->indian_end_date_time ?? 'N/A' }}</td>
                                                    <td>{{ $data->testPaper->min_passing_percentage }}</td>
                                                    <td><span class="badge bg-danger">Expired</span></td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="6" class="text-center">No expired tests found</td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
