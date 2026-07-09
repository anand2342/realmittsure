<div>
    <!-- Trigger Button -->
    <button class="btn btn-sm btn-primary" @if (count($users) === 0) disabled @endif wire:click="openModel">
        Process Data
    </button>

    <!-- Modal -->
    <div x-data="{ open: @entangle('isOpenModal') }" x-show="open" x-transition @click.away="open = false"
        @keydown.escape.window="open = false" class="modal fade" tabindex="-1" :class="{ 'show d-block': open }"
        style="background: rgba(0, 0, 0, 0.5);">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Assign Access Code To Students</h5>
                    <button type="button" class="btn-close" x-on:click="open = false; @this.closeModal()"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="assignAccessCode">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Select Users</th>
                                    <th>User Name</th>
                                    <th>Access Code</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($remainingAccessCodes as $index => $code)
                                    <tr>
                                        @if (isset($users[$index]->name))
                                            <td>
                                                <input type="checkbox" wire:model="selectedCodes"
                                                    value="{{ serialize(['code_id' => $code->id, 'user_id' => $users[$index]->id]) }}">
                                            </td>
                                            <td>{{ $users[$index]->name }}</td>
                                        @else
                                            <td></td>
                                            <td>No User</td>
                                        @endif
                                        <td>{{ $code->access_code ?? 'No Access Code' }}</td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Assign Access Codes</button>
                            <button type="button" class="btn btn-secondary"
                                x-on:click="open = false; @this.closeModal()">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        th input[type="checkbox"],
        td input[type="checkbox"] {
            transform: scale(1.2);
            margin: 0;
        }
    </style>
</div>
