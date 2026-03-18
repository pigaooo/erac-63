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
    <header class="bg-base-100/80 backdrop-blur border-b border-base-300 sticky top-0 z-40">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-4 flex items-center">
            <a id="logo_link" href="{{ route('home') }}" class="flex items-center gap-2 text-lg font-semibold flex-none">
                <img src="{{ asset('images/logo-2647.png') }}" alt="Logo ERAC" class="h-10 w-auto">
                <span>ERAC</span>
            </a>
            <nav class="hidden md:flex items-center gap-4 text-sm font-medium flex-1 justify-center">
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

    <footer class="border-t border-base-300 bg-base-100">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-6 text-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2">
            <div>
                <div class="font-semibold">ERAC 61</div>
                <div class="text-base-content/70">Encontro Regional de Aprendizes e Companheiros</div>
            </div>
            <div class="flex items-center gap-3 text-base-content/70">
                <a href="{{ route('sobre') }}" class="hover:text-primary">Sobre o evento</a>
                <a href="{{ route('inscricao') }}" class="hover:text-primary">Inscrição</a>
            </div>
        </div>
    </footer>
</body>
</html>
