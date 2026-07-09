<div>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Users Search</h5>
            <hr class="form-divider">
            <!-- Search Bar -->
            <div class="form-group mb-3">
                <input type="text" class="form-control" placeholder="Search "
                    wire:model.live.debounce.1ms="search">
            </div>

            <!-- Search Results -->
            @if (!empty($search) && $userNames->isNotEmpty())
                <ul class="list-group">
                    @foreach ($userNames as $user)
                        <li class="list-group-item d-flex align-items-center justify-content-between">
                            <span>{{ $user->name }}</span>
                            <span>{{ $user->email }}</span>
                            <div>
                                @isPermission('user.view')
                                    <a class="btn btn-sm btn-info me-1" href="{{ route('user.view', $user->id) }}">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                @endisPermission
                                @isPermission('user.edit')
                                    <a class="btn btn-sm btn-warning me-1" href="{{ route('user.edit', $user->id) }}">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                @endisPermission
                                @isPermission('user.delete')
                                    <a class="btn btn-sm btn-danger"
                                        onclick="confirmDelete('{{ route('user.delete', $user->id) }}')">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                @endisPermission
                            </div>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</div>
