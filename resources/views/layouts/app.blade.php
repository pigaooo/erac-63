<!DOCTYPE html>
<html lang="pt-BR" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="ERAC 61 - Encontro Regional de Aprendizes e Companheiros (maçônico)">
    <title>ERAC 61</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-base-200 text-base-content">
    <header class="sticky top-0 z-40 border-b border-base-300 bg-base-100/80 backdrop-blur">
        <div class="mx-auto flex max-w-6xl items-center px-4 py-4 sm:px-6">
            <a id="logo_link" href="{{ route('home') }}" class="flex flex-none items-center gap-2 text-lg font-semibold">
                <img src="{{ asset('images/logo-2647.png') }}" alt="Logo ERAC" class="h-10 w-auto">
                <span>ERAC</span>
            </a>

            <nav class="hidden flex-1 items-center justify-center gap-5 text-base font-medium md:flex">
                <a href="{{ route('localizacao') }}" class="hover:text-primary">Localização</a>
                <a href="{{ route('programacao') }}" class="hover:text-primary">Programação</a>
                <a href="{{ route('inscricao') }}" class="hover:text-primary">Inscrição</a>
                <a href="{{ route('sobre') }}" class="hover:text-primary">Sobre</a>
            </nav>
        </div>
    </header>

    <main class="min-h-screen">
        @yield('content')
    </main>

    <footer class="relative overflow-hidden border-t border-base-300 bg-base-100">
        <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-primary/50 to-transparent"></div>
        <div class="absolute -top-24 right-0 h-44 w-44 rounded-full bg-primary/10 blur-3xl"></div>
        <div class="absolute -bottom-24 left-0 h-44 w-44 rounded-full bg-secondary/10 blur-3xl"></div>

        <div class="relative mx-auto max-w-6xl space-y-8 px-4 py-10 sm:px-6">
            <div class="grid gap-8 md:grid-cols-12 md:items-start">
                <div class="space-y-4 md:col-span-5">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-2xl border border-primary/20 bg-primary/10">
                            <img src="{{ asset('images/logo-2647.png') }}" alt="Logo ERAC" class="h-8 w-auto">
                        </div>
                        <div>
                            <div class="text-lg font-semibold">ERAC 61</div>
                            <div class="text-sm text-base-content/70">Encontro Regional de Aprendizes e Companheiros</div>
                        </div>
                    </div>

                    <p class="max-w-sm text-sm leading-6 text-base-content/70">
                        Um encontro dedicado à formação, fraternidade e convivência entre irmãos da 9ª Região Administrativa do GOSP.
                    </p>
                </div>

                <div class="space-y-3 md:col-span-4 md:px-4 md:mx-auto">
                    <div class="text-xs font-semibold uppercase tracking-[0.24em] text-primary">Navegação</div>
                    <div class="grid grid-cols-2 gap-x-8 gap-y-3 text-sm text-base-content/72">
                        <a href="{{ route('home') }}" class="transition hover:text-primary">Início</a>
                        <a href="{{ route('localizacao') }}" class="transition hover:text-primary">Localização</a>
                        <a href="{{ route('programacao') }}" class="transition hover:text-primary">Programação</a>
                        <a href="{{ route('inscricao') }}" class="transition hover:text-primary">Inscrição</a>
                        <a href="{{ route('sobre') }}" class="transition hover:text-primary">Sobre</a>
                    </div>
                </div>
            </div>

            <div class="border-t border-base-300/80 pt-5 text-xs text-base-content/60">
                <div>ERAC 61. Formação, integração e fraternidade entre Aprendizes e Companheiros.</div>
            </div>
        </div>
    </footer>
</body>
</html>