<div>
    @if (session()->has('success'))
        {{ session('success') }}
    @endif


    @foreach ($rows as $index => $row)
        <input type="name" wire:model="rows.{{ $index }}.name">

        <input type="email" wire:model="rows.{{ $index }}.email">

        <input type="tel" wire:model="rows.{{ $index }}.phone">


        <button type="button" class="btn" wire:click="removeRow({{ $index }})"></button>
    @endforeach

    <button  type="button" class="btn" wire:click="addRow"></button>

    <button type="submit" class="btn" wire:click="save"></button>

</div>
