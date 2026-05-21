@props([
    'url' => null,
    'href' => null,
    'label' => 'Kembali',
])

@php
    $targetUrl = $url ?? $href ?? url()->previous();
@endphp

<a
    href="{{ $targetUrl }}"
    {{ $attributes->merge([
        'class' => 'mb-5 inline-flex items-center gap-2 rounded-xl border border-slate-200 bg-white px-4 py-2.5 text-sm font-extrabold text-slate-700 shadow-sm transition hover:border-blue-200 hover:bg-blue-50 hover:text-blue-600'
    ]) }}
>
    <svg xmlns="http://www.w3.org/2000/svg"
         class="h-4 w-4"
         fill="none"
         viewBox="0 0 24 24"
         stroke="currentColor"
         stroke-width="2">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
    </svg>

    <span>{{ $label }}</span>
</a>
