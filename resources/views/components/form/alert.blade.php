@php
    $wrapperClass = $class ?? 'col-12';
    $typeClass = $type ?? 'danger';
@endphp

<div class="{{ $wrapperClass }}">
    <div id="{{ $id }}" class="alert alert-{{ $typeClass }} d-none" role="alert">
        {{ $slot ?? __('' . $message) }}
    </div>
</div>
