@props(['fluid' => false])

<div {{ $attributes->merge(['class' => 'px-2']) }}>
    @isset($header)
        <div
            class="d-flex align-items-center px-1 flex-column flex-lg-row justify-content-between js-breadcrumb-wrapper"
        >
            {{ $header }}
        </div>
    @endisset

    <div class="{{ $fluid ? 'container-fluid' : 'container-lg' }}">
        {{ $slot }}
    </div>
</div>
