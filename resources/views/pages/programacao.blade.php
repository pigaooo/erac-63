@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-12 space-y-10 scroll-reveal" data-reveal="fadeIn">
    <section class="rounded-[2rem] border border-base-300 bg-gradient-to-br from-base-100 via-base-100 to-primary/10 shadow-sm p-6 md:p-8 relative overflow-hidden transition duration-500 hover:-translate-y-1 hover:shadow-2xl scroll-reveal" data-reveal="fadeInUp">
        <div class="absolute -top-16 -right-16 h-48 w-48 rounded-full bg-primary/10 blur-3xl animate-pulse"></div>
        <div class="absolute -bottom-16 -left-10 h-40 w-40 rounded-full bg-secondary/10 blur-3xl animate-pulse"></div>
        <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-primary/40 to-transparent"></div>

        <div class="relative space-y-2">
            <p class="text-sm font-semibold text-primary uppercase tracking-widest">Programação</p>
            <h1 class="text-3xl md:text-5xl font-black leading-tight scroll-reveal" data-reveal="fadeInUp" data-reveal-delay="80">Agenda do ERAC</h1>
            <p class="text-base-content/80 max-w-3xl scroll-reveal" data-reveal="fadeInUp" data-reveal-delay="140">
                Linha do tempo e atividades do encontro, com uma visão clara do que acontece ao longo do dia.
            </p>
        </div>
    </section>

    <div class="scroll-reveal" data-reveal="fadeInUp" data-reveal-delay="180">
        @include('componentes.patrocinadores-carousel', ['patrocinadores' => $patrocinadores ?? collect()])
    </div>

    <div class="grid gap-4 md:grid-cols-12">
        <div class="md:col-span-7 rounded-2xl border border-base-300 bg-base-100/80 shadow-sm p-6 space-y-4 transition duration-500 hover:-translate-y-1 hover:shadow-2xl scroll-reveal" data-reveal="fadeInUp" data-reveal-delay="220">
            <div class="flex items-center gap-2 text-sm uppercase tracking-wide text-primary font-semibold">
                <span class="h-2 w-2 rounded-full bg-primary animate-pulse"></span>
                Linha do tempo
            </div>
            <div class="space-y-3">
                @php
                    $cronograma = [
                        ['hora' => '08:00 09:00', 'titulo' => 'Credenciamento & Café', 'descricao' => 'Recepção, identificação por Loja e boas-vindas.'],
                        ['hora' => '09:00 09:30', 'titulo' => 'Abertura do Evento', 'descricao' => 'Saudação às colunas e apresentação das marcas patrocinadoras.'],
                        ['hora' => '09:30 12:00', 'titulo' => 'Apresentação dos Trabalhos', 'descricao' => 'Distribuição e apresentação dos trabalhos dos Aprendizes e Companheiros.'],
                        ['hora' => '12:00 13:00', 'titulo' => 'Almoço fraterno', 'descricao' => 'Intervalo para refeição e convivência.'],
                        ['hora' => '13:00 16:00', 'titulo' => 'Confraternização', 'descricao' => 'Momentos de lazer e convivência.'],
                    ];
                @endphp

                @foreach($cronograma as $index => $item)
                    <div class="flex gap-4 items-start transition duration-300 hover:translate-x-1">
                        <div class="w-16 text-sm font-semibold text-primary">{{ $item['hora'] }}</div>
                        <div class="flex-1 rounded-2xl border border-base-300 bg-base-200/50 p-4 shadow-xs transition duration-300 hover:border-primary/40 hover:bg-base-200/80 hover:shadow-lg">
                            <div class="text-base font-semibold">{{ $item['titulo'] }}</div>
                            <div class="text-sm text-base-content/70">{{ $item['descricao'] }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="md:col-span-5 grid gap-4">
            <div class="rounded-2xl border border-base-300 bg-gradient-to-br from-primary/10 to-base-100 p-6 shadow-sm transition duration-500 hover:-translate-y-1 hover:shadow-xl scroll-reveal" data-reveal="fadeInUp" data-reveal-delay="280">
                <div class="text-sm font-semibold text-primary">Visão geral</div>
                <div class="text-2xl font-bold">No dia do Evento</div>

                <div class="mt-4 grid grid-cols-2 gap-3 text-sm">
                    <div class="rounded-xl bg-base-100/70 border border-base-300 p-3 transition duration-300 hover:scale-[1.02] hover:border-primary/40 hover:shadow-md">
                        <div class="text-base font-semibold">Manhã</div>
                        <div class="text-xs text-base-content/70">Credenciamento individual, abertura formal, exposição de patrocinadores e apresentação dos trabalhos</div>
                    </div>
                    <div class="rounded-xl bg-base-100/70 border border-base-300 p-3 transition duration-300 hover:scale-[1.02] hover:border-primary/40 hover:shadow-md">
                        <div class="text-base font-semibold">Tarde</div>
                        <div class="text-xs text-base-content/70">Confraternização, exposição das marcas patrocinadoras e projetos sociais</div>
                    </div>
                </div>
            </div>

            <div class="rounded-2xl border border-base-300 bg-base-100/80 p-6 shadow-sm space-y-3 transition duration-500 hover:-translate-y-1 hover:shadow-xl scroll-reveal" data-reveal="fadeInUp" data-reveal-delay="340">
                <div class="text-sm font-semibold text-primary">Pontos-chave</div>
                <ul class="space-y-2 text-sm text-base-content/80">
                    <li class="flex items-center gap-2 transition duration-300 hover:translate-x-1"><span class="h-2 w-2 rounded-full bg-primary animate-pulse"></span> Início e fim claros para todos</li>
                    <li class="flex items-center gap-2 transition duration-300 hover:translate-x-1"><span class="h-2 w-2 rounded-full bg-primary animate-pulse"></span> Alimentação organizada, com café e almoço</li>
                    <li class="flex items-center gap-2 transition duration-300 hover:translate-x-1"><span class="h-2 w-2 rounded-full bg-primary animate-pulse"></span> Palestras e oficinas com tempo definido</li>
                    <li class="flex items-center gap-2 transition duration-300 hover:translate-x-1"><span class="h-2 w-2 rounded-full bg-primary animate-pulse"></span> Distribuição dos convidados por salas</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-12">
        <div class="md:col-span-4 rounded-2xl border border-base-300 bg-base-100/80 p-5 shadow-sm space-y-3 transition duration-300 hover:-translate-y-1 hover:border-primary/40 hover:shadow-xl scroll-reveal" data-reveal="fadeInUp" data-reveal-delay="380">
            <div class="text-sm font-semibold text-primary">Atividades</div>
            <div class="space-y-2 text-sm text-base-content/80">
                <div class="font-semibold text-base">Distribuição dos convidados</div>
                <p>Orientar colunas e lugares para Aprendizes e Companheiros.</p>
                <div class="font-semibold text-base">Modelo dos trabalhos</div>
                <p>Roteiro padronizado para fluidez e pontualidade.</p>
            </div>
        </div>

        <div class="md:col-span-4 rounded-2xl border border-base-300 bg-base-100/80 p-5 shadow-sm space-y-3 transition duration-300 hover:-translate-y-1 hover:border-primary/40 hover:shadow-xl scroll-reveal" data-reveal="fadeInUp" data-reveal-delay="420">
            <div class="text-sm font-semibold text-primary">Temas e palestras</div>
            <div class="space-y-2 text-sm text-base-content/80">
                <div class="font-semibold text-base">Temas dos trabalhos</div>
                <p>Valores maçônicos, ética e vivência ritualística.</p>
                <div class="font-semibold text-base">Formato</div>
                <p>Palestras curtas, painéis e Q&A.</p>
            </div>
        </div>

        <div class="md:col-span-4 rounded-2xl border border-base-300 bg-base-100/80 p-5 shadow-sm space-y-3 transition duration-300 hover:-translate-y-1 hover:border-primary/40 hover:shadow-xl scroll-reveal" data-reveal="fadeInUp" data-reveal-delay="460">
            <div class="text-sm font-semibold text-primary">Tempo & logística</div>
            <div class="space-y-2 text-sm text-base-content/80">
                <div class="font-semibold text-base">Tempo de estudo</div>
                <p>Blocos de 25 a 30 min com intervalo para perguntas.</p>
                <div class="font-semibold text-base">Alimentação</div>
                <p>Café da manhã na chegada e almoço fraterno ao meio-dia.</p>
            </div>
        </div>
    </div>

    <div class="rounded-3xl border border-base-300 bg-base-100/90 shadow-sm p-6 space-y-4 transition duration-500 hover:shadow-2xl scroll-reveal" data-reveal="fadeInUp" data-reveal-delay="500">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div class="space-y-1">
                <div class="text-sm font-semibold text-primary uppercase tracking-widest">Temas do GOSP</div>
                <h2 class="text-2xl font-bold">Seleção de temas (Secretaria de Cultura)</h2>
                <p class="text-sm text-base-content/70">Formatos ministrados pela Secretaria de Cultura do GOSP. As Lojas escolhem entre os temas listados.</p>
            </div>
            <div class="rounded-2xl bg-primary/10 border border-primary/30 px-4 py-3 text-sm text-primary font-semibold transition duration-300 hover:border-primary/50 hover:bg-primary/15">
                Formato: curadoria GOSP + escolha pelas Lojas
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-12">
            <div class="md:col-span-5 rounded-2xl border border-base-300 bg-base-100 p-5 shadow-xs space-y-3 transition duration-300 hover:-translate-y-1 hover:border-primary/40 hover:shadow-xl">
                <div class="text-sm font-semibold text-primary">Como funciona</div>
                <ul class="space-y-2 text-sm text-base-content/80 list-disc list-inside">
                    <li>Temas propostos pela Secretaria de Cultura do GOSP.</li>
                    <li>As Lojas escolhem os temas disponíveis para apresentar.</li>
                    <li>Formato ministrado e orientado pelo time da Cultura.</li>
                    <li>Duração sugerida: 20 a 25 min + Q&A.</li>
                </ul>
            </div>

            <div class="md:col-span-7 rounded-2xl border border-base-300 bg-base-100 p-5 shadow-xs space-y-3 transition duration-300 hover:-translate-y-1 hover:border-primary/40 hover:shadow-xl">
                <div class="text-sm font-semibold text-primary">Temas sugeridos (exemplo)</div>
                <div class="grid gap-3 sm:grid-cols-2">
                    @php
                        $temas = [
                            'Harmonia entre Colunas e Lideranças',
                            'Tradição & Inovação nos Ritos',
                            'Ética Maçônica na Era Digital',
                            'Rituais, Simbolismo e Prática',
                            'Fraternidade e Serviço Comunitário',
                        ];
                    @endphp
                    @foreach($temas as $tema)
                        <div class="rounded-2xl border border-base-300 bg-base-200/60 p-4 transition duration-300 hover:scale-[1.02] hover:border-primary/40 hover:bg-base-200/90 hover:shadow-md">
                            <div class="text-base font-semibold">{{ $tema }}</div>
                            <div class="text-xs text-base-content/70">Tema sugerido para escolha das Lojas.</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
