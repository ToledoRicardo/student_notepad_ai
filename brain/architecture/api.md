# 🔌 API y Jobs

## Rutas Web (Controladas por Breeze + Custom)

### Autenticación (Breeze)

```
GET  /login                    AuthenticatedSessionController@create
POST /login                    AuthenticatedSessionController@store
GET  /register                 RegisteredUserController@create
POST /register                 RegisteredUserController@store
POST /logout                   AuthenticatedSessionController@destroy

GET  /forgot-password          PasswordResetLinkController@create
POST /forgot-password          PasswordResetLinkController@store
GET  /reset-password/{token}   NewPasswordController@create
POST /reset-password           NewPasswordController@store

GET  /verify-email             EmailVerificationPromptController@__invoke
GET  /verify-email/{id}/{hash} VerifyEmailController@__invoke
POST /email/verification-notification  EmailVerificationNotificationController@store

GET  /profile                  ProfileController@edit
PATCH /profile                 ProfileController@update
DELETE /profile                ProfileController@destroy
```

### Notas (Custom)

```
GET  /notes                    NoteController@index      (Listar notas del user)
POST /notes                    NoteController@store      (Crear nota)
GET  /notes/create             NoteController@create     (Formulario crear)
GET  /notes/{id}               NoteController@show       (Ver nota detallada)
GET  /notes/{id}/edit          NoteController@edit       (Formulario editar) - NO IMPLEMENTADO
PATCH /notes/{id}              NoteController@update     (Actualizar) - NO IMPLEMENTADO
DELETE /notes/{id}             NoteController@destroy    (Eliminar) - NO IMPLEMENTADO
```

### Materias (Custom)

```
GET  /subjects                 SubjectController@index   (Listar materias)
GET  /subjects/create          SubjectController@create  (Formulario crear)
POST /subjects                 SubjectController@store   (Crear materia)
```

**Middleware de protección:**
- `auth` - Solo usuarios autenticados
- `verified` - Email verificado (opcional en Breeze)

---

## Jobs Asincronicos

### ProcessNoteWithAI

**Ubicación:** `app/Jobs/ProcessNoteWithAI.php`

**Propósito:** Procesar nota con Claude API de forma asincrónica

**Entrada:**
```php
new ProcessNoteWithAI($noteId)
```

**Configuración:**
```php
public int $tries = 3;  // Reintentar 3 veces en caso de fallar
```

**Flujo:**

1. **Recuperar nota:**
   ```
   Note::with('user')->find($noteId)
   ```

2. **Obtener API key:**
   ```
   config('services.anthropic.key')
   ```
   
   Si no existe → Crear log con error "API_KEY no configurada"

3. **Construir prompt:**
   ```
   "Eres un asistente académico. Organiza, complementa y reestructura 
    estos apuntes de estudiante en español. Devuelve secciones claras, 
    puntos clave y resumen final.\n\n{contenido_nota}"
   ```

4. **Llamar Claude API:**
   ```
   POST https://api.anthropic.com/v1/messages
   Headers:
     x-api-key: ANTHROPIC_API_KEY
     anthropic-version: 2023-06-01
     content-type: application/json
   
   Body:
     {
       "model": "claude-3-5-sonnet-latest",
       "max_tokens": 1200,
       "messages": [
         {
           "role": "user",
           "content": prompt
         }
       ]
     }
   ```

5. **Procesar respuesta:**
   - Si falla HTTP → Log con error
   - Si respuesta vacía → Log con error
   - Si exitosa → Guardar en `note.ai_content`

6. **Registrar en ai_logs:**
   ```php
   AiLog::create([
     'user_id' => $note->user_id,
     'note_id' => $note->id,
     'prompt' => $prompt,
     'response' => $aiText,
     'status' => 'success'  // o 'failed'
     'error_message' => null  // o descripción del error
   ])
   ```

**Errores manejados:**

| Error | Código | Acción |
|-------|--------|--------|
| API key no configurada | - | Log failed, retorna |
| HTTP error | 400, 401, 500, etc | Log failed, retorna |
| Respuesta sin contenido | - | Log failed, retorna |
| Excepción general | Throwable | Log failed con mensaje |

---

## Controladores

### NoteController

**Métodos necesarios:**

```php
public function index()              // GET /notes
public function create()             // GET /notes/create
public function store(Request $req)  // POST /notes
public function show(Note $note)     // GET /notes/{id}
public function edit(Note $note)     // GET /notes/{id}/edit - NO IMPLEMENTADO
public function update(Request $req) // PATCH /notes/{id} - NO IMPLEMENTADO
public function destroy(Note $note)  // DELETE /notes/{id} - NO IMPLEMENTADO
```

**Métodos implementados:**

### index()
```php
function index(Request $request) {
  $notes = $request->user()->notes()
    ->with('subject', 'aiLogs')
    ->latest()
    ->get();
  
  return view('notes.index', compact('notes'));
}
```

### create()
```php
function create(Request $request) {
  $subjects = $request->user()->subjects;
  return view('notes.create', compact('subjects'));
}
```

### store()
```php
function store(StoreNoteRequest $request) {
  $note = $request->user()->notes()->create(
    $request->validated()
  );
  
  ProcessNoteWithAI::dispatch($note->id);
  
  return redirect('/notes')
    ->with('status', 'Nota creada. Procesando con IA...');
}
```

---

## Validación de Requests

### StoreNoteRequest (para crear nota)

```php
public function rules(): array {
  return [
    'title' => ['required', 'string', 'max:255'],
    'content' => ['required', 'string'],
    'subject_id' => ['nullable', 'exists:subjects,id']
  ];
}

public function authorize(): bool {
  return true;  // Usuario debe estar autenticado
}
```

---

## Ejemplos de requests

### Crear nota (desde formulario)

```
POST /notes
Content-Type: application/x-www-form-urlencoded

title=Cálculo+Diferencial
&content=Derivadas...
&subject_id=1
```

**Respuesta esperada:**
```
Redirect a /notes
Set session('status') = 'Nota creada. Procesando con IA...'
```

### Listar notas

```
GET /notes
```

**Respuesta:**
```
Vista notes/index.blade.php con:
- $notes: Collection de notas del usuario
- Mostrando título, materia, contenido original y ai_content
```

---

## Políticas de Autorización

### NotePolicy

Archivo: `app/Policies/NotePolicy.php`

```php
public function view(User $user, Note $note): bool {
  return $user->id === $note->user_id;
}

public function update(User $user, Note $note): bool {
  return $user->id === $note->user_id;
}

public function delete(User $user, Note $note): bool {
  return $user->id === $note->user_id;
}
```

**Uso en controlador:**
```php
$this->authorize('view', $note);     // Lanza 403 si no autorizado
$this->authorize('update', $note);
$this->authorize('delete', $note);
```

---

## Estado de implementación

| Endpoint | Método | Status | Responsable |
|----------|--------|--------|------------|
| /notes | GET | ✅ Hecho | Avril |
| /notes | POST | ✅ Hecho | Avril |
| /notes/create | GET | ✅ Hecho | Avril |
| /notes/{id} | GET | ✅ Hecho | - |
| /notes/{id}/edit | GET | ⏳ TODO | - |
| /notes/{id} | PATCH | ⏳ TODO | - |
| /notes/{id} | DELETE | ⏳ TODO | - |
