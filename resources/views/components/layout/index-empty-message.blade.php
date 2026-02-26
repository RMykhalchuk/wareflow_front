{{--
    // Якщо є, використовуємо як href
    // Значення для data-bs-toggle, наприклад 'modal'
    // Значення для data-bs-target, наприклад '#modalAddUser'
--}}

@props([
    "title",
    "message" => null,
    "buttonText" => null,
    "buttonRoute" => null,
    "buttonModalToggle" => null,
    "buttonModalTarget" => null,
])

<div style="margin-top: 253px">
    <h1 class="fw-bolder font-large-1 text-dark text-center">
        {{ $title }}
    </h1>

    @if ($message)
        <div class="text-center">
            {{ $message }}
        </div>
    @endif

    @if ($buttonText)
        <div class="text-center mt-2">
            <div class="col-auto d-flex flex-grow-1 justify-content-center align-items-center pe-0">
                <a
                    class="btn btn-primary d-flex align-items-center"
                    @if ($buttonRoute)
                        href="{{ $buttonRoute }}"
                    @endif
                    @if ($buttonModalToggle)
                        data-bs-toggle="{{ $buttonModalToggle }}"
                    @endif
                    @if ($buttonModalTarget)
                        data-bs-target="{{ $buttonModalTarget }}"
                    @endif
                    href="{{ $buttonRoute ?? "#" }}"
                    style="{{ ! $buttonRoute && ($buttonModalToggle || $buttonModalTarget) ? "cursor:pointer;" : "" }}"
                >
                    <img
                        class="plus-icon"
                        src="{{ asset("assets/icons/utils/plus.svg") }}"
                        alt="plus"
                    />
                    {{ $buttonText }}
                </a>
            </div>
        </div>
    @endif
</div>
