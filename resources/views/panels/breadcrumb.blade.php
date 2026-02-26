@php
    $currentLocale = App\Http\Middleware\LocaleMiddleware::getLocale();
@endphp

<div class="align-self-start align-self-md-stretch d-md-flex align-items-center hidden visible">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-slash">
            @foreach ($options as $option)
                @php
                    $localizedUrl = isset($option['url']) ? url($currentLocale . '/' . ltrim($option['url'], '/')) : null;
                @endphp

                @if ($localizedUrl)
                    @if (array_key_exists('name2', $option))
                        <li class="breadcrumb-item">
                            <a class="link-secondary" href="{{ $localizedUrl }}">
                                {{ $option['name'] }}
                                <span class="breadcrumb-item-name-no-active-js">
                                    {{ $option['name2'] }}
                                </span>
                                @if (array_key_exists('name3', $option))
                                    {{ $option['name3'] }}
                                @endif
                            </a>
                        </li>
                    @else
                        <li class="breadcrumb-item">
                            <a class="link-secondary" href="{{ $localizedUrl }}">
                                <span class="breadcrumb-item-name-no-active-js">
                                    {{ $option['name'] }}
                                </span>
                            </a>
                        </li>
                    @endif
                @else
                    @if (array_key_exists('name2', $option))
                        <li class="breadcrumb-item fw-bolder active" aria-current="page">
                            {{ $option['name'] }}
                            <span class="breadcrumb-item-name-active-js">
                                {{ $option['name2'] }}
                            </span>
                            @if (array_key_exists('name3', $option))
                                {{ $option['name3'] }}
                            @endif
                        </li>
                    @else
                        <li class="breadcrumb-item fw-bolder active" aria-current="page">
                            <span class="breadcrumb-item-name-active-js">
                                {{ $option['name'] }}
                            </span>
                        </li>
                    @endif
                @endif
            @endforeach
        </ol>
    </nav>
</div>
