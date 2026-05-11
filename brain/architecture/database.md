# 🗄️ Arquitectura de Base de Datos

## Diagrama ER

```
┌──────────────────┐
│      Users       │
├──────────────────┤
│ id (PK)          │
│ name             │
│ email (UNIQUE)   │
│ password         │
│ created_at       │
│ updated_at       │
└──────────────────┘
        ▲
        │ 1:N
        ├─────────────────────┐
        │                     │
┌──────────────────┐   ┌──────────────────┐
│   Subjects       │   │      Notes       │
├──────────────────┤   ├──────────────────┤
│ id (PK)          │   │ id (PK)          │
│ user_id (FK)     │   │ user_id (FK)     │
│ name             │   │ subject_id (FK)  │
│ description      │   │ title            │
│ created_at       │   │ raw_content      │
│ updated_at       │   │ ai_content       │
└──────────────────┘   │ status           │
        ▲              │ created_at       │
        │ 1:N          │ updated_at       │
        │              └──────────────────┘
        │                     ▲
        │                     │ 1:N
        │              ┌──────────────────┐
        │              │     AiLogs       │
        │              ├──────────────────┤
        └──────────────┤ id (PK)          │
                       │ user_id (FK)     │
                       │ note_id (FK)     │
                       │ prompt           │
                       │ response         │
                       │ status           │
                       │ error_message    │
                       │ created_at       │
                       └──────────────────┘
```

## Tablas

### Users
Información de usuarios del sistema.

| Columna | Tipo | Constraints | Descripción |
|---------|------|-------------|-------------|
| id | bigint | PK, Auto-increment | Identificador único |
| name | varchar | NOT NULL | Nombre del usuario |
| email | varchar | NOT NULL, UNIQUE | Email para login |
| email_verified_at | timestamp | Nullable | Fecha de verificación |
| password | varchar | NOT NULL | Contraseña hasheada |
| remember_token | varchar | Nullable | Token para "recuérdame" |
| created_at | timestamp | - | Fecha de creación |
| updated_at | timestamp | - | Última actualización |

### Subjects
Asignaturas/materias del usuario.

| Columna | Tipo | Constraints | Descripción |
|---------|------|-------------|-------------|
| id | bigint | PK, Auto-increment | Identificador único |
| user_id | bigint | FK → users | Usuario propietario |
| name | varchar | NOT NULL | Nombre de la materia |
| description | text | Nullable | Descripción de la materia |
| created_at | timestamp | - | Fecha de creación |
| updated_at | timestamp | - | Última actualización |

### Notes
Apuntes creados por usuarios.

| Columna | Tipo | Constraints | Descripción |
|---------|------|-------------|-------------|
| id | bigint | PK, Auto-increment | Identificador único |
| user_id | bigint | FK → users | Propietario de la nota |
| subject_id | bigint | FK → subjects, Nullable | Materia relacionada |
| title | varchar | NOT NULL | Título de la nota |
| raw_content | longtext | NOT NULL | Contenido original (apuntes) |
| ai_content | longtext | Nullable | Contenido procesado por IA |
| status | varchar | NOT NULL | Estado: pending, processing, ready, failed |
| created_at | timestamp | - | Fecha de creación |
| updated_at | timestamp | - | Última actualización |

### AiLogs
Registro de todas las interacciones con Claude API.

| Columna | Tipo | Constraints | Descripción |
|---------|------|-------------|-------------|
| id | bigint | PK, Auto-increment | Identificador único |
| user_id | bigint | FK → users | Usuario que solicitó |
| note_id | bigint | FK → notes | Nota procesada |
| prompt | longtext | NOT NULL | Prompt enviado a Claude |
| response | longtext | Nullable | Respuesta de Claude |
| status | varchar | NOT NULL | 'success' o 'failed' |
| error_message | text | Nullable | Descripción del error |
| created_at | timestamp | - | Fecha de creación |
| updated_at | timestamp | - | Última actualización |

### Jobs
Tabla del queue system (Laravel internos).

| Columna | Tipo | Descripción |
|---------|------|-------------|
| id | bigint | ID único del job |
| queue | varchar | Nombre de la queue |
| payload | longtext | Datos del job serializado |
| attempts | tinyint | Intentos realizados |
| reserved_at | int | Timestamp de reserva |
| available_at | int | Timestamp de disponibilidad |
| created_at | int | Timestamp de creación |

## Migraciones

```bash
database/migrations/
├── 0001_01_01_000000_create_users_table.php
├── 0001_01_01_000001_create_cache_table.php
├── 0001_01_01_000002_create_jobs_table.php
├── 2026_04_18_000003_create_subjects_table.php
├── 2026_04_18_000004_create_notes_table.php
└── 2026_04_18_000005_create_ai_logs_table.php
```

## Relaciones Eloquent

### User
```php
User::notes()        // 1:N - notas del usuario
User::subjects()     // 1:N - materias del usuario
User::aiLogs()       // 1:N - logs de interacciones IA
```

### Note
```php
Note::user()         // N:1 - propietario de la nota
Note::subject()      // N:1 - materia de la nota (opcional)
Note::aiLogs()       // 1:N - historial de procesamiento
```

### Subject
```php
Subject::user()      // N:1 - propietario de la materia
Subject::notes()     // 1:N - notas de esta materia
```

### AiLog
```php
AiLog::user()        // N:1 - usuario que solicitó
AiLog::note()        // N:1 - nota procesada
```

## Indexes

Para optimización de queries:

```sql
-- Ya existen (por defecto en Laravel):
UNIQUE INDEX users_email_unique ON users(email);
INDEX subjects_user_id ON subjects(user_id);
INDEX notes_user_id ON notes(user_id);
INDEX notes_subject_id ON notes(subject_id);
INDEX ai_logs_user_id ON ai_logs(user_id);
INDEX ai_logs_note_id ON ai_logs(note_id);
```

## Backups

- **Ubicación:** `database/database.sqlite`
- **Estrategia:** Incluida en repositorio (para desarrollo)
- **Producción:** Implementar backup automático antes de ir a prod
