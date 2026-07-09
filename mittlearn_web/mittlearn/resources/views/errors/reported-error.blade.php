@extends('frontend.layouts.master')

@section('content')
   
    <section class="section mt-5">
        <div class="row mt-5">
            <div class="col-lg-12 mt-5">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="row align-items-center mb-3">
                                    <div class="col-md-4">
                                        <h5 class="card-title mb-0">All Reported Errors</h5>
                                    </div>

                                    <div class="col-md-4 text-md-end mt-2 mt-md-0">
                                        <div class="d-inline-flex align-items-center gap-2">
                                            <label for="paginationSelectOnpage" class="mb-0 me-2">Per Page Records:</label>
                                            <select id="paginationSelectOnpage" class="form-select form-select-sm"
                                                style="width: 80px;">
                                                <option value="" disabled
                                                    {{ session('per_page_records') ? '' : 'selected' }}>--Select--</option>
                                                @foreach ([40, 80, 120, 160, 200, 400, 800, 1200, 1600, 3200, 5000] as $option)
                                                    <option value="{{ $option }}"
                                                        {{ session('per_page_records') == $option ? 'selected' : '' }}>
                                                        {{ $option }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <hr class="formdivider">
                                <form id="exportForm" method="POST" action="{{ route('access.code.olympiad.export') }}">
                                    @csrf
                                    <input type="hidden" name="ids" id="selectedIds" value="">
                                    <input type="hidden" name="type" id="exportType" value="">
                                </form>
                                <div class="table-responsive tbleDiv">
                                    <table class="table table-striped table-bordered ">
                                        <thead>
                                            <tr>
                                                <th>S.No</th>
                                                <th><b>URL</b></th>
                                                <th><b>Note</b></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($data as $item)
                                                <tr>

                                                    <td>{{ $data->currentPage() * $data->perPage() - $data->perPage() + $loop->iteration . '.' }}
                                                    </td>
                                                    <td>{{ $item->url ?? 'NA' }}</td>
                                                    <td>{{ $item->user_note ?? 'NA' }}
                                                    <td>{{ $item->user_id ?? 'NA' }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-right text-right">
                                    {!! $data->appends(
                                            array_merge(request()->query(), [
                                                'per_page' => request('per_page', Cookie::get('perPage')),
                                            ]),
                                        )->links('pagination::bootstrap-4') !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </section>
@endsection
