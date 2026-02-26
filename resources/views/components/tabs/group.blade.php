@props([
    'class' => 'card',
    'navClass' => '',
    //додатковікласидляul.nav-tabs,
])

<div class="{{ $class }} px-0 mt-1">
    <div class="card-body p-0">
        {{-- Nav tabs --}}
        <ul class="nav nav-tabs px-1 {{ $navClass }}" role="tablist">
            {{ $items ?? '' }}
            {{-- {{ $slot->filter(fn($el) => $el->isComponent && $el->componentName === 'tabs.item') }} --}}
        </ul>

        <hr class="mt-0" />

        {{-- Tab content --}}
        <div class="tab-content">
            {{ $content ?? '' }}
            {{-- {{ $slot->filter(fn($el) => $el->isComponent && $el->componentName === 'tabs.content') }} --}}
        </div>
    </div>
</div>
