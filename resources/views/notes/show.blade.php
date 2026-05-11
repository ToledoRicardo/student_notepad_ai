@php
    $rawMarkdown = $note->raw_content ?? $note->content ?? '';

    if ($note->status === 'ready' && !empty($note->ai_content)) {
        $aiMarkdown = $note->ai_content;
        $statusClass = 'bg-green-500/10 text-green-600 dark:text-green-400 border-green-500/20';
        $statusText = 'Procesado';
        $statusIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>';
    } elseif ($note->status === 'failed') {
        $aiMarkdown = 'Ocurrió un error al procesar la nota.';
        $statusClass = 'bg-destructive/10 text-destructive border-destructive/20';
        $statusText = 'Error';
        $statusIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>';
    } elseif ($note->status === 'ready' && empty($note->ai_content)) {
        $aiMarkdown = '';
        $statusClass = 'bg-muted border-border text-muted-foreground';
        $statusText = 'Sin Procesar';
        $statusIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>';
    } else {
        $aiMarkdown = 'Procesando...';
        $statusClass = 'bg-primary/10 text-primary border-primary/20 animate-pulse';
        $statusText = 'Procesando';
        $statusIcon = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>';
    }
@endphp

<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('notes.index') }}" class="w-8 h-8 flex items-center justify-center rounded-full bg-foreground/5 border border-border text-muted-foreground hover:text-foreground hover:bg-foreground/10 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-bold text-2xl text-foreground glow-text leading-tight tracking-tight">
                {{ $note->title }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            <div class="glass-card overflow-hidden">
                <div class="p-6 md:p-8 space-y-6">
                    
                    <div class="flex flex-wrap items-center gap-3 border-b border-border pb-6">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium border {{ $statusClass }}">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $statusIcon !!}</svg>
                            {{ $statusText }}
                        </span>
                        
                        <p class="text-sm text-muted-foreground flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                            {{ $note->subject?->name ?? 'Sin materia' }}
                        </p>
                    </div>

                    <div class="grid grid-cols-1 gap-8 {{ !empty($note->ai_content) ? 'lg:grid-cols-2' : '' }}">
                        <!-- Apuntes Originales -->
                        <section class="flex flex-col">
                            <h3 class="text-xs font-semibold text-muted-foreground uppercase tracking-wider mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Apuntes Originales
                            </h3>
                            <div class="flex-grow rounded-xl border border-border bg-background/30 p-6 prose prose-sm sm:prose-base dark:prose-invert prose-headings:font-bold prose-a:text-primary hover:prose-a:text-primary/80 prose-p:leading-relaxed max-w-none">
                                {!! \Illuminate\Support\Str::markdown($rawMarkdown) !!}
                            </div>
                        </section>

                        @if(!empty($note->ai_content))
                        <!-- Versión IA -->
                        <section class="flex flex-col">
                            <h3 class="text-xs font-semibold text-primary uppercase tracking-wider mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                Versión Inteligencia Artificial
                            </h3>
                            <div class="flex-grow rounded-xl border border-primary/20 bg-primary/5 p-6 prose prose-sm sm:prose-base dark:prose-invert prose-headings:font-bold prose-a:text-primary hover:prose-a:text-primary/80 prose-p:leading-relaxed max-w-none relative overflow-hidden group">
                                <div class="absolute top-0 right-0 w-32 h-32 bg-primary/10 blur-3xl rounded-full pointer-events-none transition-opacity opacity-50 group-hover:opacity-100"></div>
                                <div class="relative z-10">
                                    {!! \Illuminate\Support\Str::markdown($aiMarkdown) !!}
                                </div>
                            </div>
                        </section>
                        @endif
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
