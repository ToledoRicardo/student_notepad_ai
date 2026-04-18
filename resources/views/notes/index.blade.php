<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Mis notas
            </h2>
            <a href="{{ route('notes.create') }}" class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-indigo-500">
                Nueva nota
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded-md bg-green-100 p-4 text-green-800">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($notes->isEmpty())
                        <p>Aún no tienes notas. Crea la primera.</p>
                    @else
                        <div class="space-y-4">
                            @foreach ($notes as $note)
                                <article class="rounded-lg border border-gray-200 p-4">
                                    <h3 class="text-lg font-semibold">{{ $note->title }}</h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Materia: {{ $note->subject?->name ?? 'Sin materia' }}
                                    </p>

                                    <div class="mt-3">
                                        <h4 class="font-medium">Apuntes originales</h4>
                                        <p class="mt-1 whitespace-pre-line text-gray-700">{{ $note->content }}</p>
                                    </div>

                                    <div class="mt-3 rounded-md bg-gray-50 p-3">
                                        <h4 class="font-medium">Versión organizada por IA</h4>
                                        <p class="mt-1 whitespace-pre-line text-gray-700">
                                            {{ $note->ai_content ?: 'Pendiente de procesamiento...' }}
                                        </p>
                                    </div>
                                </article>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
