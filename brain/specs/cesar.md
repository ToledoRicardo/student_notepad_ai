# ✅ Especificaciones - Cesar

## Responsable: Cesar Mar

## Tareas asignadas

### 1. Crear migraciones ✅

#### Migration: create_subjects_table ✅
```
database/migrations/2026_04_18_000003_create_subjects_table.php
```

**Columnas:**
- id (bigint, PK, auto-increment)
- user_id (bigint, FK → users, on delete cascade)
- name (varchar, NOT NULL)
- description (text, nullable)
- timestamps (created_at, updated_at)

**Executed:** ✅ `php artisan migrate`

#### Migration: create_notes_table ✅
```
database/migrations/2026_04_18_000004_create_notes_table.php
```

**Columnas:**
- id (bigint, PK, auto-increment)
- user_id (bigint, FK → users, on delete cascade)
- subject_id (bigint, FK → subjects, nullable, on delete set null)
- title (varchar, NOT NULL)
- content (longtext, NOT NULL)
- ai_content (longtext, nullable)
- timestamps (created_at, updated_at)

**Executed:** ✅ `php artisan migrate`

#### Migration: create_ai_logs_table ✅
```
database/migrations/2026_04_18_000005_create_ai_logs_table.php
```

**Columnas:**
- id (bigint, PK, auto-increment)
- user_id (bigint, FK → users, on delete cascade)
- note_id (bigint, FK → notes, on delete cascade)
- prompt (longtext, NOT NULL)
- response (longtext, nullable)
- status (varchar, NOT NULL) - 'success' o 'failed'
- error_message (text, nullable)
- timestamps (created_at, updated_at)

**Executed:** ✅ `php artisan migrate`

---

### 2. Crear modelos con relaciones ✅

#### Model: User ✅
```
app/Models/User.php
```

**Relaciones:**
```php
public function notes(): HasMany
public function subjects(): HasMany
public function aiLogs(): HasMany
```

**Status:** ✅ Incluido en Breeze, relaciones agregadas

#### Model: Note ✅
```
app/Models/Note.php
```

**Relaciones:**
```php
public function user(): BelongsTo      // N:1 → User
public function subject(): BelongsTo   // N:1 → Subject (nullable)
public function aiLogs(): HasMany      // 1:N → AiLog
```

**Fillable:**
```php
['user_id', 'subject_id', 'title', 'content', 'ai_content']
```

#### Model: Subject ✅
```
app/Models/Subject.php
```

**Relaciones:**
```php
public function user(): BelongsTo      // N:1 → User
public function notes(): HasMany       // 1:N → Note
```

**Fillable:**
```php
['user_id', 'name', 'description']
```

#### Model: AiLog ✅
```
app/Models/AiLog.php
```

**Relaciones:**
```php
public function user(): BelongsTo      // N:1 → User
public function note(): BelongsTo      // N:1 → Note
```

**Fillable:**
```php
['user_id', 'note_id', 'prompt', 'response', 'status', 'error_message']
```

---

### 3. Implementar NotePolicy ✅

**Ubicación:**
```
app/Policies/NotePolicy.php
```

**Métodos implementados:**

#### view(User $user, Note $note): bool
```php
public function view(User $user, Note $note): bool {
    return $user->id === $note->user_id;
}
```
- Solo el propietario puede ver la nota

#### update(User $user, Note $note): bool
```php
public function update(User $user, Note $note): bool {
    return $user->id === $note->user_id;
}
```
- Solo el propietario puede editar

#### delete(User $user, Note $note): bool
```php
public function delete(User $user, Note $note): bool {
    return $user->id === $note->user_id;
}
```
- Solo el propietario puede eliminar

**Registro en AuthServiceProvider:** ✅
```php
protected $policies = [
    Note::class => NotePolicy::class,
];
```

---

## Verificaciones realizadas ✅

### Migraciones
```bash
✅ php artisan migrate              # Todas ejecutadas sin errores
✅ php artisan migrate:status       # Todas corridas
```

### Relaciones
```bash
✅ User::find(1)->notes()           # Funciona
✅ Note::find(1)->user()            # Funciona
✅ Subject::find(1)->notes()        # Funciona
✅ AiLog::find(1)->note()           # Funciona
```

### Policies
```bash
✅ $user->can('view', $note)        # Funciona
✅ $user->can('update', $note)      # Funciona
✅ $user->can('delete', $note)      # Funciona
```

---

## Entregables

### Archivos creados:

```
✅ app/Models/Note.php
✅ app/Models/Subject.php
✅ app/Models/AiLog.php
✅ app/Policies/NotePolicy.php
✅ database/migrations/2026_04_18_000003_create_subjects_table.php
✅ database/migrations/2026_04_18_000004_create_notes_table.php
✅ database/migrations/2026_04_18_000005_create_ai_logs_table.php
```

### Archivos modificados:

```
✅ app/Models/User.php              # Agregadas relaciones
✅ app/Providers/AuthServiceProvider.php  # Registrada policy
```

---

## Estado: ✅ COMPLETADO 100%

Todas las tareas asignadas a Cesar han sido completadas exitosamente. Base de datos completamente estructurada con relaciones correctas y authorization policies funcionales.

