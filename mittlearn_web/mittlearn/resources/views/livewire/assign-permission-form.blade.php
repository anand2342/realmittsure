<div>
    @php
        $i = 0;
    @endphp
    {{ Form::open(['url' => route('permissions.save'), 'id' => 'update-permission-form', 'class' => 'row g-3']) }}
    <h5 class="card-title pb-0">Assign Permission To {{ ucfirst($selectedAssignTo ?? '') }}</h5>
    <hr class="form-divider">
    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="assign_to" id="gridRadios1" value="role"
                wire:model="selectedAssignTo" wire:change='handleAssignToTypeChange($event.target.value)'>
            <label class="form-check-label" for="gridRadios1">
                Roles
            </label>
        </div>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-12">
        <div class="form-check">
            <input class="form-check-input" type="radio" name="assign_to" id="gridRadios2" value="user"
                wire:model="selectedAssignTo" wire:change='handleAssignToTypeChange($event.target.value)'>
            <label class="form-check-label" for="gridRadios2">
                Users
            </label>
        </div>
    </div>

    @if ($selectedAssignTo)
        <div class="col-md-4 col-sm-3 col-xs-12">
            <label class="form-label">Role</label>
            {{ Form::select('role_id', $rolesList, null, ['class' => 'form-select', 'placeholder' => '--Select--', 'wire:change' => 'handleChangeRole($event.target.value)']) }}
        </div>
    @endif

    @if ($selectedAssignTo == 'user' && $selectedRole == 'd2c_user')
        <div class="col-md-4 col-sm-3 col-xs-12">
            <label class="form-label">Category</label>
            {{ Form::select('category_id', $categoryList, null, ['class' => 'form-select', 'placeholder' => '--Select--', 'wire:change' => 'handleChangeCategory($event.target.value)']) }}
        </div>
    @endif

    @if ($selectedAssignTo == 'user' && $usersList)
        <div class="col-md-4 col-sm-3 col-xs-12">
            <label class="form-label">User</label>
            {{ Form::select('user_id', $usersList, null, ['class' => 'form-select', 'placeholder' => '--Select--', 'wire:change' => 'handleChangeUser($event.target.value)']) }}
        </div>
    @endif

    @if ($selectedAssignTo && $isVisibleAllPermission == true)
        <h5 class="card-title">All Permissions</h5>
        <hr class="form-divider">

        {{-- <div class="col-sm-12 d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">Save Permissions</button>
        </div> --}}

        <div class="row col-sm-12">
            <div class="col-md-3 col-sm-3 col-xs-12">
                {{ Form::select('permission_group', config('constants.PERMISSION_CATEGORIES'), $filterPermissionGroup, ['class' => 'form-select', 'placeholder' => '--Select Group--', 'wire:change' => 'handleFilterPermissions($event.target.value, "permission_group")']) }}
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12">
                {!! Form::text('permission_name', $filterPermissionName, [
                    'class' => 'form-control',
                    'id' => 'permission_name',
                    'placeholder' => 'Enter Search Text',
                    'oninput' => 'debouncedFilter(this.value)',
                ]) !!}
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12">
                {{ Form::select('accessable_for', config('constants.PERMISSION_FOR_LIST'), $filterPermissionAccessible, ['class' => 'form-select', 'placeholder' => '--Select Accessible--', 'wire:change' => 'handleFilterPermissions($event.target.value, "accessable_for")']) }}
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12">
                <button type="button" class="btn btn-danger clear-filter"
                    wire:click="handleFilterPermissions('', 'clear')">Clear</button>
            </div>
        </div>
        {{ Form::open(['url' => "route('save-permissions')"]) }}
        @php
            $groupedPermissions = [
                'SUPER ADMIN Portal' => [],
                'School Admin Portal' => [],
                'Mittlearn Student Portal' => [],
                'Pre-primary Student Portal' => [],
            ];

            foreach ($permissions as $permissionKey => $permissionList) {
                // Get first permission ID in group to decide the category
                $firstId = $permissionList[0]['id'] ?? 0;

                if ($firstId >= 1 && $firstId <= 199) {
                    $groupedPermissions['Super Admin Portal Permissions'][$permissionKey] = $permissionList;
                } elseif ($firstId >= 200 && $firstId <= 260) {
                    $groupedPermissions['School Admin Portal Permissions'][$permissionKey] = $permissionList;
                } elseif ($firstId >= 261 && $firstId <= 278) {
                    $groupedPermissions['Mittlearn Student Portal Permissions'][$permissionKey] = $permissionList;
                } elseif ($firstId >= 278 && $firstId <= 292) {
                    $groupedPermissions['Pre-Primary Student Portal Permissions'][$permissionKey] = $permissionList;
                } else {
                    $groupedPermissions['App Menu Permissions'][$permissionKey] = $permissionList;
                }
            }

            $i = 0;
        @endphp

        <style>
            .accordion-section-title {
                font-size: 1.25rem;
                font-weight: bold;
                margin-top: 1rem;
                color: #1D4ED8;
            }

            .accordion-item {
                border-radius: 0.5rem;
                overflow: hidden;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.08);
            }

            .accordion-button {
                background: #f3f4f6;
                font-weight: 600;
                color: #111827;
            }

            .accordion-button:not(.collapsed) {
                background: #e0f2fe;
                color: #0284c7;
            }

            .table th {
                background-color: #f9fafb;
                color: #111827;
                font-weight: 600;
            }

            .table td {
                vertical-align: middle;
            }

            .form-check-input {
                cursor: pointer;
            }

            .disable-checkbox {
                cursor: not-allowed;
                opacity: 0.5;
            }
        </style>

        <div class="accordion" id="permissionAccordion">
            @foreach ($groupedPermissions as $categoryTitle => $categoryPermissions)
                @if (count($categoryPermissions))
                    <div class="accordion-section">
                        <h4 class="accordion-section-title">{{ $categoryTitle }}</h4>
                        <hr class="form-divider">

                        @foreach ($categoryPermissions as $permissionKey => $permissionList)
                            @php $groupId = 'group_' . Str::slug($categoryTitle . '_' . $permissionKey); @endphp

                            <div class="accordion-item mb-3">
                                <h2 class="accordion-header" id="heading_{{ $groupId }}">
                                    @php
                                        $words = explode(' ', $permissionKey);
                                        if (strtolower($words[0]) === 'mittbunny') {
                                            $words[0] = 'Pre-Primary';
                                        }
                                        $displayKey = implode(' ', $words);
                                    @endphp

                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#collapse_{{ $groupId }}" aria-expanded="false"
                                        aria-controls="collapse_{{ $groupId }}">
                                        <strong>{{ strtoupper($displayKey) }} Routes</strong>
                                    </button>

                                </h2>
                                <div id="collapse_{{ $groupId }}" class="accordion-collapse collapse"
                                    aria-labelledby="heading_{{ $groupId }}"
                                    data-bs-parent="#permissionAccordion">
                                    <div class="accordion-body p-0">
                                        <table class="table table-bordered table-striped mb-0">
                                            <thead class="table-light">
                                                <tr>
                                                    <th style="width: 10%">S.No</th>
                                                    <th style="width: 10%">Permission</th>
                                                    <th>Title</th>
                                                    <th>Description</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($permissionList as $key => $val)
                                                    @php
                                                        $i++;
                                                        $isPermissionsAssigned = in_array(
                                                            $val['id'],
                                                            $assignedPermissions,
                                                        );
                                                    @endphp
                                                    <tr>
                                                        <td class="text-center">
                                                            {{ $i }}.{{ $key + 1 }}</td>
                                                        <td class="text-center">
                                                            {{ Form::hidden('ids[]', $val['id']) }}
                                                            {{ Form::checkbox('permissions[' . $val['id'] . ']', null, $isPermissionsAssigned, [
                                                                'class' => ($key == 'super_admin' ? 'disable-checkbox' : '') . ' form-check-input',
                                                                'title' => $val['title'],
                                                            ]) }}
                                                        </td>
                                                        <td>{{ $val['title'] }}</td>
                                                        <td>{{ $val['description'] }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endforeach
        </div>




        <div class="col-sm-12 d-flex justify-content-end">
            <button type="submit" class="btn btn-primary">Save Permissions</button>
        </div>
        {{ Form::close() }}

    @endif
    {{ Form::close() }}
    <script>
        let debounceTimeout;

        function debouncedFilter(value) {
            clearTimeout(debounceTimeout);
            debounceTimeout = setTimeout(function() {
                @this.call('handleFilterPermissions', value, 'permission_name');
            }, 500); // 500ms debounce time
        }
    </script>
</div>
