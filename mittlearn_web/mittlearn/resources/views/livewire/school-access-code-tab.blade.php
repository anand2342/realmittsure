<div>
    <!-- Navigation Tabs -->
    <div class="card mt-3">
        <div class="card-body">
            <ul class="nav nav-tabs mt-3">
                <li class="nav-item">
                    <a class="nav-link {{ $tab === 'schoolList' ? 'active' : '' }}" style="cursor: pointer;"
                        wire:click="$set('tab', 'schoolList')">School List</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $tab === 'accessCode' ? 'active' : '' }}" style="cursor: pointer;"
                        wire:click="$set('tab', 'accessCode')">Access Code</a>
                </li>
            </ul>

            <!-- Tab Content -->
            @if ($tab === 'schoolList')
                <div class="card-title">School List</div>
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th><b>School Name</b></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($schools as $index => $school)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    <a href="javascript:void(0)" wire:click="showAccessCodes({{ $school->id }})">
                                        {{ $school->name ?? 'NA' }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @elseif ($tab === 'accessCode')
                <div class="card-title">Access Code for School:
                    {{ $selectedSchool ? $schools->find($selectedSchool)->name : 'N/A' }}</div>
                @if ($accessCodes && $accessCodes->isNotEmpty())
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>S.No</th>
                                <th><b>Access Code</b></th>
                                <th><b>Start Date</b></th>
                                <th><b>Expired Date</b></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($accessCodes as $index => $code)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $code->access_code ?? 'NA' }}</td>
                                    <td>{{ $code->start_date ?? 'NA' }}</td>
                                    <td>{{ $code->end_date ?? 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <p>No access codes found for the selected school.</p>
                @endif
            @endif
        </div>
    </div>
</div>
