<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Materias
            </h2>
            <a href="{{ route('subjects.create') }}" class="inline-flex items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-foreground transition hover:bg-indigo-500">
                Nueva materia
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            @if (session('status'))
                <div class="mb-4 rounded-md bg-green-100 p-4 text-green-800">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-foreground overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if ($subjects->isEmpty())
                        <p>Aún no tienes materias. Crea la primera.</p>
                    @else
                        <div class="space-y-4">
                            @foreach ($subjects as $subject)
                                <article class="rounded-lg border border-gray-200 p-4">
                                    <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                                        <h3 class="text-lg font-semibold">{{ $subject->name }}</h3>
                                        <span class="text-sm text-gray-500">
                                            {{ $subject->notes_count }} notas
                                        </span>
                                    </div>

                                    @if ($subject->description)
                                        <p class="mt-2 text-sm text-gray-600">
                                            {{ $subject->description }}
                                        </p>
                                    @endif
                                </article>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
