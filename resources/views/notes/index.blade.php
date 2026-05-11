<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-foreground glow-text leading-tight tracking-tight">
                Mis Notas
            </h2>
            <a href="{{ route('notes.create') }}" class="inline-flex items-center justify-center whitespace-nowrap px-5 py-2.5 bg-primary text-primary-foreground font-medium rounded-xl text-sm transition-all duration-300 hover:bg-primary/90 hover:shadow-[0_0_20px_rgba(139,92,246,0.4)] hover:-translate-y-0.5">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Nueva Nota
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-6 rounded-xl bg-green-500/10 border border-green-500/20 p-4 text-green-400 backdrop-blur-md animate-in fade-in slide-in-from-top-4 duration-500">
                    {{ session('status') }}
                </div>
            @endif

            <div class="space-y-6">
                @if ($notes->isEmpty())
                    <div class="glass-card p-12 text-center">
                        <div class="w-16 h-16 rounded-full bg-foreground/5 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-muted-foreground" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h3 class="text-xl font-medium text-foreground mb-2">Aún no tienes notas</h3>
                        <p class="text-muted-foreground mb-6">Comienza creando tu primera nota para organizar tus ideas con IA.</p>
                        <a href="{{ route('notes.create') }}" class="text-primary hover:text-primary/80 transition-colors underline underline-offset-4">Crear primera nota</a>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach ($notes as $note)
                            @php
                                $rawMarkdown = $note->raw_content ?? $note->content ?? '';

                                if ($note->status === 'ready' && !empty($note->ai_content)) {
                                    $aiMarkdown = $note->ai_content;
                                    $statusClass = 'bg-green-500/10 text-green-600 dark:text-green-400 border-green-500/20';
                                    $statusText = 'Procesado';
                                    $statusIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>';
                                } elseif ($note->status === 'ready' && empty($note->ai_content)) {
                                    $aiMarkdown = '';
                                    $statusClass = 'bg-muted border-border text-muted-foreground';
                                    $statusText = 'Sin Procesar';
                                    $statusIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>';
                                } elseif ($note->status === 'failed') {
                                    $aiMarkdown = 'Ocurrió un error al procesar la nota.';
                                    $statusClass = 'bg-destructive/10 text-destructive-foreground border-destructive/20';
                                    $statusText = 'Error';
                                    $statusIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>';
                                } else {
                                    $aiMarkdown = 'Procesando...';
                                    $statusClass = 'bg-primary/10 text-primary border-primary/20 animate-pulse';
                                    $statusText = 'Procesando';
                                    $statusIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>';
                                }
                            @endphp
                            
                            <article class="glass-card p-6 flex flex-col h-full hover:-translate-y-1 transition-transform duration-300">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $statusClass }} mb-3">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $statusIcon !!}</svg>
                                            {{ $statusText }}
                                        </span>
                                        <h3 class="text-xl font-bold tracking-tight">
                                            <a href="{{ route('notes.show', $note) }}" class="text-foreground hover:text-primary transition-colors">
                                                {{ $note->title }}
                                            </a>
                                        </h3>
                                        <p class="mt-1 text-sm text-muted-foreground flex items-center gap-1.5">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                            {{ $note->subject?->name ?? 'Sin materia' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex-grow space-y-4 mt-2">
                                    <!-- Contenido Original (Truncado) -->
                                    <div>
                                        <h4 class="text-xs font-semibold text-muted-foreground uppercase tracking-wider mb-2">Original</h4>
                                        <div class="text-sm text-foreground/80 line-clamp-3 overflow-hidden relative prose prose-sm dark:prose-invert max-w-none prose-p:my-0 prose-headings:my-0">
                                            {!! \Illuminate\Support\Str::markdown($rawMarkdown) !!}
                                            <div class="absolute bottom-0 left-0 right-0 h-8 bg-gradient-to-t from-card/60 to-transparent"></div>
                                        </div>
                                    </div>

                                    @if($note->status === 'ready' && !empty($note->ai_content))
                                    <!-- Contenido Procesado (Truncado) -->
                                    <div class="bg-primary/5 border border-primary/10 rounded-xl p-4 relative overflow-hidden group">
                                        <!-- Decorative glow inside -->
                                        <div class="absolute top-0 right-0 w-20 h-20 bg-primary/20 blur-2xl rounded-full transition-opacity group-hover:opacity-100 opacity-50"></div>
                                        
                                        <h4 class="text-xs font-semibold text-primary uppercase tracking-wider mb-2 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                            Resumen IA
                                        </h4>
                                        <div class="text-sm text-foreground/90 line-clamp-4 relative z-10 prose prose-sm dark:prose-invert max-w-none prose-p:my-0 prose-headings:my-0">
                                            {!! \Illuminate\Support\Str::markdown($aiMarkdown) !!}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                                
                                <div class="mt-6 pt-4 border-t border-border/5 flex justify-end relative z-20">
                                    <a href="{{ route('notes.show', $note) }}" class="text-sm text-primary font-medium hover:text-primary/80 transition-colors inline-flex items-center">
                                        Ver detalles
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
