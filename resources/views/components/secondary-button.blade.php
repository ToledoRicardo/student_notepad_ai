<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center whitespace-nowrap px-4 py-2 bg-transparent border border-border/10 text-foreground font-medium rounded-xl text-sm hover:bg-foreground/5 hover:border-border/20 hover:shadow-[0_0_15px_rgba(255,255,255,0.05)] focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 focus:ring-offset-background disabled:opacity-50 disabled:pointer-events-none transition-all duration-300']) }}>
    {{ $slot }}
</button>
