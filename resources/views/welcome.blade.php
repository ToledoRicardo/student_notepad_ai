<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Student Notepad AI') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script>
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            window.toggleTheme = function() {
                if (document.documentElement.classList.contains('dark')) {
                    document.documentElement.classList.remove('dark');
                    localStorage.theme = 'light';
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.theme = 'dark';
                }
            }
        </script>
    </head>
    <body class="bg-background text-foreground font-sans antialiased relative overflow-hidden min-h-screen flex flex-col selection:bg-primary/30">
        
        <!-- Background Glow Effects -->
        <div class="fixed top-[10%] left-[20%] w-[40vw] h-[40vw] rounded-full bg-primary/10 blur-[150px] pointer-events-none"></div>
        <div class="fixed bottom-[-10%] right-[-10%] w-[50vw] h-[50vw] rounded-full bg-primary/5 blur-[150px] pointer-events-none"></div>
        <div class="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full bg-[radial-gradient(circle_at_center,rgba(255,255,255,0.03)_1px,transparent_1px)] bg-[size:24px_24px] pointer-events-none opacity-50 z-0"></div>

        <header class="relative z-10 w-full py-6 px-6 sm:px-12 flex justify-between items-center glass-nav border-b-0">
            <div class="flex items-center gap-3">
                <x-application-logo class="w-8 h-8 text-primary drop-shadow-[0_0_10px_rgba(139,92,246,0.6)]" />
                <span class="font-bold text-lg tracking-tight text-foreground glow-text">Notepad AI</span>
            </div>
            
            @if (Route::has('login'))
                <nav class="flex items-center gap-4">
                    <button onclick="window.toggleTheme()" class="p-2 rounded-xl text-muted-foreground hover:text-foreground hover:bg-black/5 dark:hover:bg-foreground/5 transition-colors duration-300" aria-label="Toggle Theme">
                        <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        <svg class="w-5 h-5 block dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                    </button>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm font-medium hover:text-primary transition-colors duration-300">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-medium text-muted-foreground hover:text-foreground transition-colors duration-300">
                            Log in
                        </a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="text-sm font-medium px-4 py-2 bg-foreground/5 border border-border rounded-full hover:bg-foreground/10 transition-all duration-300">
                                Register
                            </a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        <main class="relative z-10 flex-grow flex items-center justify-center px-4">
            <div class="max-w-4xl text-center space-y-8 animate-in fade-in slide-in-from-bottom-8 duration-1000">
                
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-primary/20 bg-primary/10 text-primary text-sm font-medium mb-4">
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
                    </span>
                    Inteligencia Artificial Integrada
                </div>

                <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight text-foreground drop-shadow-2xl">
                    Revoluciona tus <br/>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-blue-400">
                        Apuntes de Clase
                    </span>
                </h1>
                
                <p class="text-lg md:text-xl text-muted-foreground max-w-2xl mx-auto leading-relaxed">
                    Student Notepad AI organiza, resume y mejora tus notas automáticamente usando el poder de Claude AI. Aprende más rápido y mejor.
                </p>

                <div class="flex flex-col sm:flex-row items-center justify-center gap-4 pt-4">
                    <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-3 text-sm font-medium rounded-xl bg-primary text-primary-foreground transition-all duration-300 hover:bg-primary/90 hover:shadow-[0_0_25px_rgba(139,92,246,0.5)] hover:-translate-y-1 w-full sm:w-auto">
                        Comenzar Gratis
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                    <a href="{{ route('login') }}" class="inline-flex items-center justify-center px-8 py-3 text-sm font-medium rounded-xl bg-transparent border border-border text-foreground transition-all duration-300 hover:bg-foreground/5 hover:shadow-[0_0_15px_rgba(255,255,255,0.05)] hover:-translate-y-1 w-full sm:w-auto">
                        Iniciar Sesión
                    </a>
                </div>

                <!-- Feature Grid Preview -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-16 text-left">
                    <div class="glass-card p-6 border-t-2 border-t-primary/50">
                        <div class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center mb-4">
                            <svg class="w-5 h-5 text-primary" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-foreground mb-2">Captura Rápida</h3>
                        <p class="text-sm text-muted-foreground">Toma notas de tus clases sin distracciones. Un editor limpio y directo.</p>
                    </div>
                    <div class="glass-card p-6 border-t-2 border-t-blue-400/50">
                        <div class="w-10 h-10 rounded-full bg-blue-400/20 flex items-center justify-center mb-4">
                            <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-foreground mb-2">Procesamiento IA</h3>
                        <p class="text-sm text-muted-foreground">Claude AI estructura y mejora tus apuntes automáticamente en segundo plano.</p>
                    </div>
                    <div class="glass-card p-6 border-t-2 border-t-purple-400/50">
                        <div class="w-10 h-10 rounded-full bg-purple-400/20 flex items-center justify-center mb-4">
                            <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                        </div>
                        <h3 class="text-lg font-semibold text-foreground mb-2">Organización</h3>
                        <p class="text-sm text-muted-foreground">Mantén todo ordenado por materias. Encuentra lo que necesitas, cuando lo necesitas.</p>
                    </div>
                </div>

            </div>
        </main>
        
        <footer class="relative z-10 py-8 text-center text-sm text-muted-foreground">
            &copy; {{ date('Y') }} Student Notepad AI. Creado para el futuro de la educación.
        </footer>
    </body>
</html>
