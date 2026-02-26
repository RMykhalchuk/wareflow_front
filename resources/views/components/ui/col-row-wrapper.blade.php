@props([
    'colClass' => 'col-12 px-0',
    'rowClass' => 'row mx-0',
])

<div class="{{ $colClass }}">
    <div class="{{ $rowClass }}">
        {{ $slot }}
    </div>
</div>
