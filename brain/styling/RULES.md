# 🎨 Reglas de Estilo - Shadcn Futurista

Este documento define las reglas de diseño (Design Tokens y Patrones) a seguir para toda la interfaz de usuario del proyecto "Student Notepad AI".

## 1. Concepto General
- **Modo Oscuro Permanente (Dark Mode First):** Interfaz basada en tonos oscuros para dar una sensación premium y tecnológica.
- **Glassmorphism:** Uso intensivo de fondos semi-transparentes (`bg-opacity`, `backdrop-blur`) en lugar de colores sólidos opacos para componentes flotantes.
- **Micro-interacciones:** Elementos interactivos (botones, tarjetas, inputs) deben tener transiciones suaves (`duration-300`, `ease-out`) y efectos al hacer hover/focus (sutil glow, escalar ligeramente).
- **Contraste Limpio:** Textos primarios en blanco brillante, secundarios en gris/plata para no cansar la vista.

## 2. Paleta de Colores (Variables CSS)

Definiremos en `:root` (o `.dark`) las variables al estilo Shadcn UI:

- `--background`: `0 0% 3.9%` (Casi negro). Fondo principal de la app.
- `--foreground`: `0 0% 98%` (Blanco puro). Texto principal.
- `--card`: `0 0% 3.9%` a `240 10% 3.9%`. Fondo de tarjetas, generalmente se aplicará con cierta transparencia (`bg-card/50`).
- `--card-foreground`: `0 0% 98%`.
- `--popover`: Fondo para dropdowns y modales.
- `--popover-foreground`: Texto para popovers.
- `--primary`: `252 100% 64%` (Un púrpura/violeta eléctrico). Color de acento principal que representa la IA.
- `--primary-foreground`: `0 0% 98%`.
- `--secondary`: `240 3.7% 15.9%`. Fondos secundarios o acentos menores.
- `--secondary-foreground`: `0 0% 98%`.
- `--muted`: `240 3.7% 15.9%`. Textos o fondos apagados.
- `--muted-foreground`: `240 5% 64.9%`.
- `--accent`: `240 3.7% 15.9%` o un cyan futurista.
- `--accent-foreground`: `0 0% 98%`.
- `--destructive`: `0 62.8% 30.6%`.
- `--border`: `240 3.7% 15.9%`. Bordes finos.
- `--input`: `240 3.7% 15.9%`. Bordes de inputs.
- `--ring`: `252 100% 64%`. El brillo al hacer focus.

## 3. Tipografía

- **Fuente Principal:** `Inter`, `Outfit`, o equivalente sans-serif geométrica/moderna.
- Los títulos (`h1`, `h2`) usarán `font-extrabold` y un espaciado de letras ajustado (`tracking-tight`).

## 4. Patrones de UI (Componentes)

### 4.1. Tarjetas (Cards)
- `bg-gray-900/40 backdrop-blur-xl border border-white/10 rounded-2xl`
- En hover: `border-white/20 shadow-[0_0_15px_rgba(139,92,246,0.15)]`

### 4.2. Botones Primarios
- `bg-primary text-primary-foreground font-medium rounded-xl`
- Transición: `transition-all duration-300 hover:bg-primary/90 hover:shadow-[0_0_20px_rgba(139,92,246,0.4)]`

### 4.3. Inputs
- Fondo muy oscuro o negro: `bg-black/50`
- Borde sutil: `border border-white/10 rounded-xl`
- Focus: `focus:ring-2 focus:ring-primary/50 focus:border-primary/50 transition-all`

### 4.4. Fondos Globales
- Para la pantalla completa, un fondo base muy oscuro (`bg-background`) y usar `<div>` radiales en el fondo absoluto para dar efectos de "Glow" púrpuras difusos.

---
Estas reglas deben ser aplicadas rigurosamente en cada componente o vista que se modifique o cree.
