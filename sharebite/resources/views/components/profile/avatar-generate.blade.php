@props(['name'])

<div {{ $attributes->merge(['class' => 'w-full h-full flex items-center justify-center']) }}>
    {!! Avatar::create($name)->toSvg() !!}
</div>