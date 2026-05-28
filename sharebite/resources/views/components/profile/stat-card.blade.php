@props(['title', 'value', 'unit', 'theme' => 'emerald'])

@php
    $palette = [
        'emerald' => [
            'card' => 'from-emerald-100 via-white to-emerald-50 ring-emerald-100',
            'iconBg' => 'bg-emerald-200 text-emerald-700',
            'value' => 'text-emerald-600',
        ],
        'amber' => [
            'card' => 'from-amber-100 via-white to-amber-50 ring-amber-100',
            'iconBg' => 'bg-amber-200 text-amber-700',
            'value' => 'text-amber-500',
        ],
    ][$theme] ?? [
        'card' => 'from-slate-100 via-white to-slate-50 ring-slate-100',
        'iconBg' => 'bg-slate-200 text-slate-700',
        'value' => 'text-slate-700',
    ];
@endphp

<article class="rounded-3xl bg-gradient-to-br {{ $palette['card'] }} p-5 ring-1 shadow-sm">
    <div class="mb-3 flex h-8 w-8 items-center justify-center rounded-full {{ $palette['iconBg'] }}">
        {{ $icon }}
    </div>
    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">{{ $title }}</p>
    <div class="mt-2 flex items-end gap-2">
        <span class="text-4xl font-bold tracking-tight {{ $palette['value'] }}">{{ $value }}</span>
        <span class="pb-1 text-slate-600">{{ $unit }}</span>
    </div>
</article>
