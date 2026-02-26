<h1
    {{
        $attributes->merge([
            'class' => ($attributes->get('class') ? $attributes->get('class') : 'mt-2') . ' mb-0 fw-bolder text-dark',
        ])
    }}
>
    {!! $title !!}
</h1>
