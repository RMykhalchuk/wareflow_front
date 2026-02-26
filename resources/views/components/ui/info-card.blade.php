@props([
    'title' => '',
])

<div class="card mb-2 p-4 shadow-sm rounded">
    <h5 class="fw-bolder px-1">{{ $title }}</h5>
    <div class="row mx-0">
        {{ $slot }}
    </div>
</div>
