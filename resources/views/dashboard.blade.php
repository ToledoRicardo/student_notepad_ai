<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-foreground glow-text leading-tight tracking-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="glass-card">
                <div class="p-8 text-foreground">
                    <h3 class="text-xl font-semibold mb-4 text-foreground">¡Bienvenido de nuevo, {{ Auth::user()->name }}!</h3>
                    <p class="text-muted-foreground">{{ __("You're logged in!") }} Has iniciado sesión correctamente.</p>
                    
                    <div class="mt-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                        <a href="{{ route('notes.index') }}" class="block p-6 rounded-xl border border-border bg-foreground/5 hover:bg-foreground/10 hover:border-primary/30 transition-all duration-300 group">
                            <h4 class="text-lg font-medium text-foreground group-hover:text-primary transition-colors flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                Mis Notas
                            </h4>
                            <p class="text-sm text-muted-foreground mt-2">Accede a tus apuntes guardados y revisa el estado de procesamiento de la IA.</p>
                        </a>
                        <a href="{{ route('notes.create') }}" class="block p-6 rounded-xl border border-border bg-foreground/5 hover:bg-foreground/10 hover:border-primary/30 transition-all duration-300 group">
                            <h4 class="text-lg font-medium text-foreground group-hover:text-primary transition-colors flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                Crear Nota
                            </h4>
                            <p class="text-sm text-muted-foreground mt-2">Inicia un nuevo documento y deja que la IA organice tu conocimiento.</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
