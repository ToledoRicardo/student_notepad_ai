# 📋 Visión General del Proyecto

## ¿Qué es Student Notepad AI?

**Student Notepad AI** es una aplicación web que permite a estudiantes crear, almacenar y organizar apuntes de clase. La aplicación integra inteligencia artificial (Claude API) para procesar y mejorar automáticamente los apuntes del usuario.

## Objetivos principales

1. **Captura de apuntes:** Permitir a estudiantes escribir y guardar apuntes organizados por materia
2. **Procesamiento con IA:** Enviar apuntes a Claude para organización, resumen y complementación automática
3. **Historial de procesamiento:** Mantener registro de todas las interacciones con la IA
4. **Interfaz intuitiva:** Diseño simple y amigable para uso académico

## Características principales

✅ **Autenticación:**
- Registro e inicio de sesión de usuarios
- Sesiones persistentes en base de datos
- Perfiles de usuario

✅ **Gestión de notas:**
- Crear, leer, actualizar y eliminar notas personales
- Organizar notas por materia
- Vista rápida de contenido original vs. versión procesada por IA

✅ **Procesamiento de IA:**
- Envío asincrónico de notas a Claude API mediante queue
- Procesamiento automático de contenido
- Almacenamiento de respuestas de IA
- Registro completo de logs de interacciones

✅ **Seguridad:**
- Authorization policy para que usuarios solo vean sus propias notas
- CSRF protection
- Validación de datos

## Estado actual

**Completado:** 100% ✅

Todas las tareas asignadas al equipo han sido implementadas:
- Ricardo: Setup + Breeze ✅
- Cesar: Migraciones + Modelos + Policies ✅
- Abelardo: Queue + Job ProcessNoteWithAI ✅
- Avril: Vistas (index, create) + Layout ✅

## Próximos pasos (Fase 2)

- [ ] Configurar API key de Anthropic en producción
- [ ] Crear más vistas (edit, delete, show detalles)
- [ ] Agregar dashboard con estadísticas
- [ ] Implementar búsqueda de notas
- [ ] Exportar notas a PDF
- [ ] Sistema de compartir notas entre usuarios (opcional)
- [ ] Notificaciones cuando la IA termina de procesar
