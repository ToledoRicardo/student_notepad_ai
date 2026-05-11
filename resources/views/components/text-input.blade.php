@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'flex h-11 w-full rounded-xl border border-input bg-background px-3 py-2 text-sm text-foreground ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus-visible:ring-offset-background focus:border-primary/50 disabled:cursor-not-allowed disabled:opacity-50 transition-all duration-300']) }}>
