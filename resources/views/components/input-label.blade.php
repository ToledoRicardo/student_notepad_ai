@props(['value'])

<label {{ $attributes->merge(['class' => 'block text-sm font-medium leading-none text-foreground peer-disabled:cursor-not-allowed peer-disabled:opacity-70 mb-2']) }}>
    {{ $value ?? $slot }}
</label>
