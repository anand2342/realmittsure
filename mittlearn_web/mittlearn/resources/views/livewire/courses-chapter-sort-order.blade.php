<td>
    @if ($isEditing)
        <input type="number" wire:model="sortOrder" wire:blur="updateSortOrder" class="border px-2 py-1 w-16">
    @else
        <span>{{ $sortOrder }}</span>
        <button wire:click="$set('isEditing', true)" class="btn btn btn-sm me-4"><i class="fa fa-edit"></i></button>
    @endif
</td>
