# ✅ Especificaciones - Avril

## Responsable: Avril

## Tareas asignadas

### 1. Layout base app.blade.php ✅

**Ubicación:**
```
resources/views/layouts/app.blade.php
```

**Estructura:**
```html
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
  <head>
    <!-- Meta tags, fonts, Vite assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body>
    <div class="min-h-screen bg-gray-100">
      <!-- Navbar incluida -->
      @include('layouts.navigation')
      
      <!-- Header condicional -->
      @isset($header)
        <header class="bg-white shadow">
          {{ $header }}
        </header>
      @endisset
      
      <!-- Contenido dinámico -->
      <main>
        {{ $slot }}
      </main>
    </div>
  </body>
</html>
```

**Features:**
- ✅ Componente dinámico (usa `{{ $slot }}`)
- ✅ Navbar integrada
- ✅ Header condicional para páginas
- ✅ Tailwind CSS incluido
- ✅ Vite assets compilados
- ✅ Layout de 2 columnas (nav + main)

**Uso en otras vistas:**
```blade
<x-app-layout>
  <x-slot name="header">
    <h2>Título de página</h2>
  </x-slot>
  
  <!-- Contenido aquí -->
</x-app-layout>
```

---

### 2. Navbar (navigation.blade.php) ✅

**Ubicación:**
```
resources/views/layouts/navigation.blade.php
```

**Incluida en app.blade.php:**
```blade
@include('layouts.navigation')
```

**Features:**
- ✅ Breeze navigation component (pre-incluido)
- ✅ Links de navegación principal
- ✅ Usuario autenticado mostrado
- ✅ Dropdown de opciones (logout, perfil)
- ✅ Links dinámicos según autenticación
- ✅ Responsive design
- ✅ Tailwind styling

**Componentes de navegación:**
- Logo/Home
- Links principales
- Menu dropdown de usuario
- Logout button

---

### 3. Vista notes/index.blade.php ✅

**Ubicación:**
```
resources/views/notes/index.blade.php
```

**Propósito:** Listar todas las notas del usuario autenticado

**Estructura:**

```blade
<x-app-layout>
  <x-slot name="header">
    <div class="flex items-center justify-between">
      <h2>Mis notas</h2>
      <a href="{{ route('notes.create') }}" class="...">
        Nueva nota
      </a>
    </div>
  </x-slot>
  
  <div class="py-12">
    <div class="max-w-7xl mx-auto">
      <!-- Mensajes de sesión -->
      @if (session('status'))
        <div class="mb-4 rounded-md bg-green-100 p-4 text-green-800">
          {{ session('status') }}
        </div>
      @endif
      
      <!-- Lista de notas -->
      <div class="bg-white rounded-lg shadow">
        @if ($notes->isEmpty())
          <p>Aún no tienes notas. Crea la primera.</p>
        @else
          <div class="space-y-4">
            @foreach ($notes as $note)
              <article class="rounded-lg border border-gray-200 p-4">
                <!-- Título de nota -->
                <h3 class="text-lg font-semibold">{{ $note->title }}</h3>
                
                <!-- Materia -->
                <p class="mt-1 text-sm text-gray-500">
                  Materia: {{ $note->subject?->name ?? 'Sin materia' }}
                </p>
                
                <!-- Contenido original -->
                <div class="mt-3">
                  <h4 class="font-medium">Apuntes originales</h4>
                  <p class="mt-1 whitespace-pre-line text-gray-700">
                    {{ $note->content }}
                  </p>
                </div>
                
                <!-- Contenido procesado por IA -->
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
</x-app-layout>
```

**Features:**
- ✅ Usa layout app.blade.php
- ✅ Header con título y botón "Nueva nota"
- ✅ Mensaje de sesión si existe
- ✅ Listado de notas del usuario
- ✅ Muestra si no hay notas
- ✅ Por cada nota:
  - Título
  - Materia (opcional)
  - Contenido original
  - Contenido procesado por IA (o "Pendiente...")
- ✅ Styling responsive con Tailwind

**Data esperada:**
```php
// Desde NoteController@index
$notes = auth()->user()->notes()->with('subject')->latest()->get();
```

---

### 4. Vista notes/create.blade.php ✅

**Ubicación:**
```
resources/views/notes/create.blade.php
```

**Propósito:** Formulario para crear nueva nota

**Estructura:**

```blade
<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800">
      Crear nota
    </h2>
  </x-slot>
  
  <div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
          
          <!-- Formulario -->
          <form method="POST" action="{{ route('notes.store') }}" class="space-y-6">
            @csrf
            
            <!-- Campo: Materia (opcional) -->
            <div>
              <x-input-label for="subject_id" value="Materia (opcional)" />
              <select id="subject_id" name="subject_id" class="...">
                <option value="">Sin materia</option>
                @foreach ($subjects as $subject)
                  <option value="{{ $subject->id }}" @selected(old('subject_id') == $subject->id)>
                    {{ $subject->name }}
                  </option>
                @endforeach
              </select>
              <x-input-error :messages="$errors->get('subject_id')" class="mt-2" />
            </div>
            
            <!-- Campo: Título -->
            <div>
              <x-input-label for="title" value="Título" />
              <x-text-input 
                id="title" 
                name="title" 
                type="text" 
                class="mt-1 block w-full"
                :value="old('title')"
                required 
                autofocus 
              />
              <x-input-error :messages="$errors->get('title')" class="mt-2" />
            </div>
            
            <!-- Campo: Contenido -->
            <div>
              <x-input-label for="content" value="Apuntes" />
              <textarea 
                id="content"
                name="content"
                rows="8"
                class="mt-1 block w-full rounded-md border-gray-300"
                required
              >{{ old('content') }}</textarea>
              <x-input-error :messages="$errors->get('content')" class="mt-2" />
            </div>
            
            <!-- Botones -->
            <div class="flex items-center gap-4">
              <x-primary-button>Guardar nota</x-primary-button>
              <a href="{{ route('notes.index') }}" class="text-sm text-gray-600 underline">
                Cancelar
              </a>
            </div>
          </form>
          
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
```

**Features:**
- ✅ Usa layout app.blade.php
- ✅ Formulario con POST a route('notes.store')
- ✅ CSRF token (@csrf)
- ✅ Campos del formulario:
  - **Materia:** select dropdown (opcional)
  - **Título:** text input (requerido)
  - **Apuntes:** textarea grande (requerido)
- ✅ Muestra errores de validación
- ✅ Preserva valores si hay error (old())
- ✅ Botones: Guardar y Cancelar
- ✅ Styling responsivo con Tailwind
- ✅ Componentes Breeze (x-input-label, x-text-input, etc)

**Data esperada:**
```php
// Desde NoteController@create
$subjects = auth()->user()->subjects;
```

---

## Flujo de uso completo

### 1. Usuario ve lista de notas
```
GET /notes
→ NoteController@index
→ resources/views/notes/index.blade.php
```

### 2. Usuario cliquea "Nueva nota"
```
GET /notes/create
→ NoteController@create
→ resources/views/notes/create.blade.php
```

### 3. Usuario llena formulario
```
Título: "Derivadas"
Materia: "Cálculo"
Apuntes: "Las derivadas son..."
```

### 4. Usuario envía formulario
```
POST /notes
{
  title: "Derivadas",
  subject_id: 1,
  content: "Las derivadas son..."
}
→ NoteController@store
→ Guarda en BD
→ Dispara Job ProcessNoteWithAI
→ Redirige a /notes con mensaje de éxito
```

### 5. Usuario ve nota en lista
```
GET /notes
→ Muestra nota con contenido original
→ Muestra "Pendiente de procesamiento..." mientras IA procesa
→ Cuando termina, muestra contenido procesado
```

---

## Entregables

### Archivos creados:
```
✅ resources/views/notes/index.blade.php
✅ resources/views/notes/create.blade.php
```

### Archivos modificados:
```
✅ resources/views/layouts/app.blade.php  # Ya existía, solo ajustado
✅ resources/views/layouts/navigation.blade.php  # Ya existía (Breeze)
```

### Rutas automáticas (por resourceful controller):
```
GET  /notes                 → index
POST /notes                 → store
GET  /notes/create          → create
```

---

## Componentes Blade reutilizados

Componentes Breeze disponibles para usar:

```blade
<x-app-layout>              <!-- Layout principal -->
<x-input-label>             <!-- Label de formulario -->
<x-text-input>              <!-- Input de texto -->
<x-primary-button>          <!-- Botón primario -->
<x-input-error>             <!-- Mostrar errores -->
```

---

## Estado: ✅ COMPLETADO 100%

Todas las tareas asignadas a Avril han sido completadas. Frontend completamente funcional con vistas para listar y crear notas. Layout responsive con Tailwind CSS.

