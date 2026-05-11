# 🗺️ Roadmap del Proyecto

## Fase 1: MVP (Completado ✅)

**Objetivo:** Aplicación funcional con autenticación y procesamiento de notas con IA

**Sprint 1 - Setup (4/16/2026)**
- [x] Crear proyecto Laravel con Breeze
- [x] Configurar .env con SQLite
- [x] Verificar `php artisan serve` funcionando
- [x] Subir a GitHub

**Sprint 2 - Modelos (4/18/2026)**
- [x] Crear migraciones: subjects, notes, ai_logs
- [x] Crear modelos con relaciones correctas
- [x] Implementar NotePolicy (view, update, delete)
- [x] Verificar relaciones funcionales

**Sprint 3 - Queue & Jobs (4/18/2026)**
- [x] Configurar QUEUE_CONNECTION=database
- [x] Crear Job ProcessNoteWithAI
- [x] Integración con Claude API
- [x] Sistema de logging de operaciones

**Sprint 4 - Frontend (4/19/2026)**
- [x] Crear layout app.blade.php con navbar
- [x] Vista notes/index (listar notas)
- [x] Vista notes/create (crear notas)
- [x] Formularios con validación

**Sprint 5 - Cierre (5/4/2026)**
- [x] Documentación de setup (INSTALACION.md)
- [x] Script de instalación automática (install.bat)
- [x] Subir base de datos de prueba al repo
- [x] Crear sistema de documentación (Brain)

---

## Fase 2: Mejoras y Features (Planeado)

**Sprint 6 - Vistas adicionales**
- [ ] Vista de detalle de nota (show.blade.php)
- [ ] Editar nota (edit.blade.php)
- [ ] Eliminar nota con confirmación
- [ ] Modal para ver estado del procesamiento

**Sprint 7 - Dashboard y estadísticas**
- [ ] Dashboard principal con resumen
- [ ] Gráfico: notas creadas por materia
- [ ] Gráfico: procesadas vs pendientes
- [ ] Últimas notas recientes

**Sprint 8 - Búsqueda y filtrado**
- [ ] Búsqueda por título/contenido
- [ ] Filtrar por materia
- [ ] Ordenar por fecha, título, etc.
- [ ] Paginación

**Sprint 9 - Exportación**
- [ ] Exportar nota a PDF
- [ ] Exportar todas las notas como ZIP
- [ ] Copiar contenido AI al portapapeles
- [ ] Imprimir nota

**Sprint 10 - Social (Opcional)**
- [ ] Compartir notas con otros usuarios
- [ ] Sistema de permisos (lectura, edición)
- [ ] Comentarios en notas compartidas
- [ ] Notificaciones

---

## Timeline estimado

- **Fase 1:** ✅ Completada (5/4/2026)
- **Fase 2:** Estimado 2-3 semanas (inicio cuando el equipo lo decida)

## Criterios de definición (DoD - Definition of Done)

Para considerar una tarea como completada:

1. ✅ Código implementado según especificaciones
2. ✅ Migraciones ejecutadas exitosamente
3. ✅ Modelos/Relaciones funcionando correctamente
4. ✅ Tests pasados (si aplica)
5. ✅ Sin errores en logs
6. ✅ Documentado en el código
7. ✅ Committed y pusheado a GitHub
8. ✅ Revisado por al menos un miembro del equipo

