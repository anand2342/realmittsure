<div>
    @if (!$flag)
        <div class="d-md-flex justify-content-between align-items-center">
            <h5 class="card-title mb-0">User Info</h5>
        </div>
        <hr class="form-divider">
        <div class="col-md-4 col-sm-3 col-xs-12 mb-4">
            {!! Form::label('role', 'Role', ['class' => 'form-label required']) !!}
            <select wire:model="selectedRole" name="role" wire:change="roleChanged" class="form-control form-select fs-8"
                {{ $flag ? 'disabled' : '' }}>
                <option value="">--Select--</option>
                @foreach ($roles as $roleKey => $roleValue)
                    <option value="{{ $roleKey }}"
                        {{ isset($selectedRole) && $roleKey == $selectedRole ? 'selected' : '' }}>
                        {{ $roleValue }}
                    </option>
                @endforeach
            </select>
            @if ($flag)
                {{ Form::hidden('selectedRole', isset($selectedRole) ? $selectedRole->role_slug : '') }}
            @endif
        </div>
    @endif

    {{-- Conditional form inclusion --}}
    @if (
        ($selectedRole && $selectedRole === 'super_admin') ||
            (isset($selectedRole->role_slug) && $selectedRole->role_slug === 'super_admin'))
        @include('admin.user.admin-form', ['viewOnly' => $viewOnly])
    @elseif (
        ($selectedRole && $selectedRole === 'parent') ||
            (isset($selectedRole->role_slug) && $selectedRole->role_slug === 'parent'))
        @include('admin.user.parent-form', ['viewOnly' => $viewOnly])
    @elseif (
        ($selectedRole && $selectedRole === 'school_admin') ||
            (isset($selectedRole->role_slug) && $selectedRole->role_slug === 'school_admin'))
        @include('admin.user.schoolAdmin-form', [
            'viewOnly' => $viewOnly,
            'classes' => $classes,
            'schoolList' => $schoolList,
            'uniqueId' => $uniqueId,
            'verify' => $verify ?? null,
            // 'academicSessions' => $academicSessions,
        ])
    @elseif (
        ($selectedRole && $selectedRole === 'school_student') ||
            (isset($selectedRole->role_slug) && $selectedRole->role_slug === 'school_student'))
        @include('admin.user.student-form', [
            'viewOnly' => $viewOnly,
            'schoolList' => $schoolList,
            'sections' => $sections,
        ])
    @elseif (
        ($selectedRole && $selectedRole === 'school_teacher') ||
            (isset($selectedRole->role_slug) && $selectedRole->role_slug === 'school_teacher'))
        @include('admin.user.teacher-form', [
            'viewOnly' => $viewOnly,
            'schoolList' => $schoolList,
            'data' => $userData,
        ])
    @elseif (
        ($selectedRole && $selectedRole === 'instructor') ||
            (isset($selectedRole->role_slug) && $selectedRole->role_slug === 'instructor'))
        @include('admin.user.instructor-form', ['viewOnly' => $viewOnly, 'role' => 'instructor'])
    @elseif (
        ($selectedRole && in_array($selectedRole, ['leader', 'leaders'])) ||
            (isset($selectedRole->role_slug) && in_array($selectedRole->role_slug, ['leader', 'leaders'])))
        @include('admin.user.leaders-form', ['viewOnly' => $viewOnly])
    @elseif (
        ($selectedRole && $selectedRole === 'b2c_student') ||
            (isset($selectedRole->role_slug) && $selectedRole->role_slug === 'b2c_student'))
        @include('admin.user.user-form', ['viewOnly' => $viewOnly, 'courseData' => $courseData])
    @elseif (
        ($selectedRole && $selectedRole === 'salesman') ||
            (isset($selectedRole->role_slug) && $selectedRole->role_slug === 'salesman'))
        @include('admin.user.instructor-form', ['viewOnly' => $viewOnly, 'role' => 'salesman'])
    @elseif (
        ($selectedRole && $selectedRole === 'distributors') ||
            (isset($selectedRole->role_slug) && $selectedRole->role_slug === 'distributors'))
        @include('admin.user.instructor-form', ['viewOnly' => $viewOnly, 'role' => 'distributors'])
    @elseif (
        ($selectedRole && $selectedRole === 'd2c_user') ||
            (isset($selectedRole->role_slug) && $selectedRole->role_slug === 'd2c_user'))
        @include('admin.user.d2c-user-form', ['viewOnly' => $viewOnly, 'role' => 'd2c_user'])
    @elseif (isset($selectedRole) &&
            !in_array($selectedRole, [
                'super_admin',
                'parent',
                'school_admin',
                'school_student',
                'school_teacher',
                'instructor',
                'leader',
                'leaders',
                'b2c_student',
                'salesman',
            ]))
        @include('admin.user.new-user-form', ['viewOnly' => $viewOnly])
    @endif

</div>
