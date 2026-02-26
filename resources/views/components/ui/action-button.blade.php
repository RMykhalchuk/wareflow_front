@props([
    'id' => null,
    'type' => 'submit',
    'class' => 'btn btn-primary',
    'text',
])

<button
    {{
        $attributes->merge([
            'type' => $type,
            'id' => $id,
            'class' => $class,
        ])
    }}
>
    {{ $text }}
</button>
