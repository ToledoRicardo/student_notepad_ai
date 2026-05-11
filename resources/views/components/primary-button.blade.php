<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center whitespace-nowrap px-4 py-2 bg-primary text-primary-foreground font-medium rounded-xl text-sm transition-all duration-300 hover:bg-primary/90 hover:shadow-[0_0_20px_rgba(139,92,246,0.4)] focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 focus:ring-offset-background disabled:opacity-50 disabled:pointer-events-none']) }}>
    {{ $slot }}
</button>
