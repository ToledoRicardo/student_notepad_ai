<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('notes.index') }}" class="w-8 h-8 flex items-center justify-center rounded-full bg-foreground/5 border border-border text-muted-foreground hover:text-foreground hover:bg-foreground/10 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-bold text-2xl text-foreground glow-text leading-tight tracking-tight">
                Crear Nueva Nota
            </h2>
        </div>
    </x-slot>

    <div class="py-12" x-data="aiEditor()">
        <div class="mx-auto sm:px-6 lg:px-8 transition-all duration-500" :class="hasGenerated ? 'max-w-7xl' : 'max-w-4xl'">
            <div class="glass-card overflow-hidden">
                <div class="p-8">
                    <form method="POST" action="{{ route('notes.store') }}" class="space-y-6" id="noteForm">
                        @csrf
                        
                        <input type="hidden" name="ai_content" :value="aiContent">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <x-input-label for="title" value="Título de la Nota" />
                                <x-text-input id="title" name="title" type="text" class="block w-full text-lg" :value="old('title')" required autofocus placeholder="Ej. Conceptos clave de la Revolución Francesa..." />
                                <x-input-error :messages="$errors->get('title')" class="mt-2" />
                            </div>

                            <div class="md:col-span-1">
                                <x-input-label for="subject_id" value="Materia (opcional)" />
                                <select id="subject_id" name="subject_id" class="flex h-11 w-full rounded-xl border border-input bg-background px-3 py-2 text-sm text-foreground ring-offset-background focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 focus:border-primary/50 transition-all duration-300">
                                    <option value="" class="bg-background text-foreground">Sin materia (General)</option>
                                    @foreach ($subjects as $subject)
                                        <option value="{{ $subject->id }}" class="bg-background text-foreground" @selected(old('subject_id') == $subject->id)>
                                            {{ $subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('subject_id')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid transition-all duration-500 gap-6" :class="hasGenerated ? 'grid-cols-1 lg:grid-cols-2' : 'grid-cols-1'">
                            
                            <!-- Panel Izquierdo: Crudo -->
                            <div class="flex flex-col relative">
                                <div class="flex justify-between items-end mb-2">
                                    <x-input-label for="content" value="Apuntes (Crudos)" />
                                    
                                    <button type="button" @click="generateAI" x-show="!hasGenerated" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border border-primary/20 bg-primary/10 text-primary text-xs font-medium hover:bg-primary/20 transition-colors disabled:opacity-50" :disabled="isGenerating">
                                        <svg x-show="!isGenerating" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                        <svg x-show="isGenerating" class="w-3.5 h-3.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        <span x-text="isGenerating ? 'La IA está organizando...' : '✨ Organizar con IA'"></span>
                                    </button>
                                </div>

                                <textarea id="content" x-model="rawContent" name="content" rows="16" class="flex-grow block w-full rounded-xl border border-input bg-background px-4 py-3 text-sm text-foreground placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:border-primary/50 transition-all duration-300 font-mono resize-none relative z-10" required placeholder="Escribe tus apuntes rápidos aquí..."></textarea>
                                <x-input-error :messages="$errors->get('content')" class="mt-2" />
                            </div>

                            <!-- Panel Derecho: IA -->
                            <div class="flex flex-col relative" x-show="hasGenerated" style="display: none;" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0">
                                
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-2 gap-2">
                                    <x-input-label value="Versión Estructurada (IA)" class="text-primary" />
                                    
                                    <!-- Refinement Input -->
                                    <div class="relative w-full sm:w-64">
                                        <input type="text" x-model="prompt" @keydown.enter.prevent="refineAI" placeholder="Ej. Haz un resumen más corto..." class="w-full h-8 pl-3 pr-8 rounded-lg border border-primary/30 bg-primary/5 text-xs text-foreground focus:outline-none focus:ring-1 focus:ring-primary/50 placeholder:text-muted-foreground" :disabled="isRefining" />
                                        <button type="button" @click="refineAI" :disabled="isRefining || prompt.trim() === ''" class="absolute right-1 top-1 w-6 h-6 flex items-center justify-center rounded text-primary hover:bg-primary/20 transition-colors disabled:opacity-50">
                                            <svg x-show="!isRefining" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                                            <svg x-show="isRefining" class="w-3.5 h-3.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="relative flex-grow rounded-xl border border-primary/20 bg-primary/5 p-6 prose prose-sm sm:prose-base dark:prose-invert prose-headings:font-bold prose-a:text-primary hover:prose-a:text-primary/80 prose-p:leading-relaxed max-w-none overflow-y-auto max-h-[500px] group">
                                    <div class="absolute top-0 right-0 w-32 h-32 bg-primary/10 blur-3xl rounded-full pointer-events-none transition-opacity opacity-50 group-hover:opacity-100"></div>
                                    <div class="relative z-10" x-html="aiContent ? marked.parse(aiContent) : ''"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Error Banner -->
                        <div x-show="error" style="display: none;" class="p-3 rounded-lg bg-destructive/10 border border-destructive/20 text-destructive text-sm" x-text="error"></div>

                        <div class="flex items-center gap-4 pt-4 border-t border-border">
                            <x-primary-button>
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                Guardar Nota
                            </x-primary-button>
                            <a href="{{ route('notes.index') }}" class="text-sm font-medium text-muted-foreground hover:text-foreground transition-colors">
                                Cancelar
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
    <script>
        function aiEditor() {
            return {
                rawContent: @json(old('content', '')),
                aiContent: @json(old('ai_content', '')),
                prompt: '',
                isGenerating: false,
                isRefining: false,
                hasGenerated: false,
                error: null,

                init() {
                    if (this.aiContent && this.aiContent.trim() !== '') {
                        this.hasGenerated = true;
                    }
                },

                async generateAI() {
                    // Sincronizar el valor en caso de que EasyMDE no dispare el evento input
                    const rawTextarea = document.getElementById('content');
                    if (rawTextarea) {
                        this.rawContent = rawTextarea.value;
                    }
                    
                    if (!this.rawContent || this.rawContent.trim() === '') {
                        alert('Escribe algunos apuntes primero.');
                        return;
                    }
                    
                    this.isGenerating = true;
                    this.error = null;
                    
                    try {
                        const response = await window.axios.post('/ai/generate', {
                            content: this.rawContent
                        });
                        
                        this.aiContent = response.data.ai_content;
                        this.hasGenerated = true;
                    } catch (e) {
                        this.error = e.response?.data?.error || 'Ocurrió un error al contactar a la IA.';
                        console.error(e);
                    } finally {
                        this.isGenerating = false;
                    }
                },

                async refineAI() {
                    if (!this.prompt || this.prompt.trim() === '' || !this.aiContent || this.aiContent.trim() === '') return;
                    
                    this.isRefining = true;
                    this.error = null;
                    
                    try {
                        const response = await window.axios.post('/ai/refine', {
                            raw_content: this.rawContent,
                            ai_content: this.aiContent,
                            prompt: this.prompt
                        });
                        
                        this.aiContent = response.data.ai_content;
                        this.prompt = ''; // Clear prompt on success
                    } catch (e) {
                        this.error = e.response?.data?.error || 'Ocurrió un error al refinar con la IA.';
                        console.error(e);
                    } finally {
                        this.isRefining = false;
                    }
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
