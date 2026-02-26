@props([
    "title" => null,
    "titleRight" => null,
    "headerRight" => null,
    "buttonText" => null,
    "buttonRoute" => null,
    "buttonModalToggle" => null,
    "buttonModalTarget" => null,
    "tableId" => null,
    "idOne" => null,
    "idTwo" => null,
    "idThree" => null,
    "idFour" => null,
])

@php
    $hasHeader =
        ! empty($title) ||
        (isset($titleRight) && trim($titleRight) !== "") ||
        (isset($headerRight) && trim($headerRight) !== "") ||
        $buttonText ||
        $buttonRoute ||
        $buttonModalToggle ||
        $buttonModalTarget;
@endphp

<div
    {{
        $attributes->class([
            "card",
            "mx-2" => ! $attributes->has("class"),
        ])
    }}
>
    {{-- card-header показується лише якщо є хоч щось із умов --}}
    @if ($hasHeader)
        <div
            class="card-header border-top-secondary-500 border-start-secondary-500 border-end-secondary-500 border-bottom-0 mx-0 gap-1"
        >
            <div class="col-auto d-flex gap-1 align-items-center">
                {{-- показуємо title лише якщо він є --}}
                @if (! empty($title))
                    <h4 class="card-title text-dark fw-bolder">{{ $title }}</h4>
                @endif

                {{-- новий слот праворуч від title --}}
                @if (isset($titleRight) && trim($titleRight) !== "")
                    <div>
                        {{ $titleRight }}
                    </div>
                @endif
            </div>

            <div
                class="col-auto d-flex flex-grow-1 justify-content-end align-items-center gap-1 pe-0"
            >
                {{-- Якщо слот headerRight заданий і не порожній --}}
                @if (isset($headerRight) && trim($headerRight) !== "")
                    {{ $headerRight }}
                    {{-- Інакше, якщо є кнопка - виводимо кнопку --}}
                @elseif ($buttonText || $buttonRoute || $buttonModalToggle || $buttonModalTarget)
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
                @else
                    {{ $slot }}
                @endif
            </div>
        </div>
    @endif

    <div class="card-grid" style="position: relative">
        @if ($idOne || $idTwo || $idThree || $idFour)
            @include(
                "layouts.table-setting",
                [
                    "idOne" => $idOne,
                    "idTwo" => $idTwo,
                    "idThree" => $idThree,
                    "idFour" => $idFour,
                ]
            )
        @else
            @include("layouts.table-setting")
        @endif

        {{-- Якщо слот grid заданий і не порожній --}}
        @if (isset($grid) && trim($grid) !== "")
            {{ $grid }}
        @elseif ($tableId)
            <div class="table-block" id="{{ $tableId }}"></div>
        @else
            {{ $slot }}
        @endif

        <div
            id="table-loader"
            style="
                display: none;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                z-index: 9999;
                text-align: center;
            "
        >
            <div class="loader-spinner"></div>
        </div>
    </div>
</div>
