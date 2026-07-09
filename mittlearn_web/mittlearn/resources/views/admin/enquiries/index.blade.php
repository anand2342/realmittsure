@extends('admin.layouts.master')

@section('content')
    <div class="pagetitle">
        <h1>Contact Enquiries</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item active">Enquiries</li>
            </ol>
        </nav>
    </div>

    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body p-3">
                        <form method="GET" action="{{ route('enquiries') }}">
                            <div class="row">
                                <div class="col mb-3">
                                    <input type="text" class="form-control" placeholder="Search by Name" name="name"
                                        value="{{ request('name') }}">
                                </div>
                                <div class="col mb-3">
                                    <input type="text" class="form-control" placeholder="Search by Email" name="email"
                                        value="{{ request('email') }}">
                                </div>
                                <div class="col mb-3">
                                    <input type="text" class="form-control" placeholder="Search by Mobile" name="mobile"
                                        value="{{ request('mobile') }}">
                                </div>
                                <div class="col mb-3">
                                    {{-- @dd(request($status)); --}}
                                    <select name="status" class="form-select">
                                        <option value="">Search by Status</option>
                                        @foreach (config('constants.REPLIED_STATUS') as $status => $label)
                                            <option value="{{ $status }}"
                                                {{ $status == request('status') ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col mb-3">
                                    <button type="submit" class="btn btn-primary">Search</button>
                                    <a href="{{ route('enquiries') }}" class="btn btn-secondary">Clear</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                            <h5 class="card-title mb-0">All Enquiries</h5>

                            <div class="d-flex align-items-center gap-2">
                                <label for="paginationSelectOnpage" class="mb-0">Per Page Records:</label>
                                <select id="paginationSelectOnpage" class="form-select form-select-sm" style="width: 80px;">
                                    <option value="" disabled {{ session('per_page_records') ? '' : 'selected' }}>
                                        --Select--
                                    </option>
                                    @foreach ([10, 20, 30, 40, 50] as $option)
                                        <option value="{{ $option }}"
                                            {{ session('per_page_records') == $option ? 'selected' : '' }}>
                                            {{ $option }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <hr class="form-divider">
                        <div class="table-responsive tbleDiv ">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>S.No</th>
                                        <th><b>Name</b></th>
                                        <th><b>Email</b></th>
                                        <th><b>Mobile</b></th>
                                        <th><b>Subject</b></th>
                                        <th><b>Submitted Date</b></th>
                                        <th><b>Status</b></th>
                                        <th><b>Replied Date</b></th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($enquiry as $item)
                                        {{-- @dd($item->status) --}}
                                        <tr>
                                            <td>{{ $enquiry->currentPage() * $enquiry->perPage() - $enquiry->perPage() + $loop->iteration . '.' }}
                                            </td>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->email }}</td>
                                            <td>{{ $item->mobile_no }}</td>
                                            <td>{{ $item->subject }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y') ?? 'N/A' }}
                                            </td>
                                            <td>
                                                <span class="badge {{ $item->status ? 'text-success' : 'text-danger' }}">
                                                    {{ config('constants.REPLIED_STATUS')[$item->status] ?? 'Unknown Status' }}
                                                </span>
                                            </td>
                                            <td>{{ isset($item->resolved_at) ? \Carbon\Carbon::parse($item->resolved_at)->format('d/m/Y') : ' ' }}
                                            </td>
                                            <td>
                                                @isPermission('enquiry.view')
                                                    <a class="btn btn-sm btn-info"
                                                        href="{{ route('enquiry.view', $item->id) }}"><i
                                                            class="fa fa-eye"></i></a>
                                                @endisPermission
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-right text-right">
                            {!! $enquiry->links('pagination::bootstrap-4') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
