@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-12 space-y-10">
    <div>
        @include('componentes.patrocinadores-carousel', ['patrocinadores' => $patrocinadores ?? collect()])
    </div>

    <section class="rounded-[2rem] border border-base-300 bg-gradient-to-br from-base-100 via-base-100 to-primary/10 shadow-sm p-6 md:p-8 relative overflow-hidden transition duration-500 hover:-translate-y-1 hover:shadow-2xl scroll-reveal" data-reveal="fadeInUp">
        <div class="absolute -top-16 -right-16 h-48 w-48 rounded-full bg-primary/10 blur-3xl animate-pulse"></div>
        <div class="absolute -bottom-16 -left-10 h-40 w-40 rounded-full bg-secondary/10 blur-3xl animate-pulse"></div>
        <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-primary/40 to-transparent"></div>

        <div class="relative space-y-2">
            <p class="text-sm font-semibold text-primary uppercase tracking-widest">Programação</p>
            <h1 class="text-3xl md:text-5xl font-black leading-tight">Agenda do ERAC</h1>
            <p class="text-base-content/80 max-w-3xl">
                Linha do tempo e atividades do encontro, com uma visão clara do que acontece ao longo do dia.
            </p>
        </div>
    </section>

    <div class="grid gap-4 md:grid-cols-12">
        <div class="md:col-span-7 rounded-2xl border border-base-300 bg-base-100/80 shadow-sm p-6 space-y-4 transition duration-500 hover:-translate-y-1 hover:shadow-2xl scroll-reveal" data-reveal="fadeInUp" data-reveal-delay="220">
            <div class="flex items-center gap-2 text-sm uppercase tracking-wide text-primary font-semibold">
                <span class="h-2 w-2 rounded-full bg-primary animate-pulse"></span>
                Linha do tempo
            </div>
            <div>
                @php
                    $cronograma = [
                        ['hora' => '07:30 às 08:20', 'titulo' => 'Café da manhã e credenciamento', 'descricao' => 'Recepção dos participantes, identificação por Loja e acolhimento inicial.'],
                        ['hora' => '08:30 às 09:10', 'titulo' => 'Abertura institucional', 'descricao' => 'Palavra da Mesa, composição da mesa e apresentação dos patrocinadores.'],
                        ['hora' => '09:10 às 11:30', 'titulo' => 'Trabalhos em salas', 'descricao' => 'Apresentações temáticas por sala, com tempo padronizado por Loja, perguntas e debate coletivo.'],
                        ['hora' => '11:30 às 11:40', 'titulo' => 'Retorno ao plenário', 'descricao' => 'Reorganização dos participantes para a etapa final conjunta.'],
                        ['hora' => '11:40 às 12:00', 'titulo' => 'Encerramento oficial', 'descricao' => 'Síntese geral do encontro e palavra final da coordenação regional.'],
                        ['hora' => '12:00 às 13:00', 'titulo' => 'Almoço fraterno', 'descricao' => 'Intervalo para refeição e convivência.'],
                        ['hora' => '13:00 às 16:00', 'titulo' => 'Confraternização', 'descricao' => 'Momentos de lazer e convivência.'],
                    ];
                @endphp

                <ul class="timeline timeline-snap-icon timeline-compact timeline-vertical w-full">
                    @foreach($cronograma as $index => $item)
                        <li>
                            @if($index > 0)
                                <hr class="bg-primary/30" />
                            @endif

                            <div class="timeline-middle">
                                <span class="flex h-8 w-8 items-center justify-center rounded-full border border-primary/30 bg-primary/10 text-primary shadow-sm">
                                    <span class="h-2.5 w-2.5 rounded-full bg-primary"></span>
                                </span>
                            </div>

                            <div class="timeline-end mb-8 w-full rounded-2xl border border-base-300 bg-base-200/50 p-4 shadow-xs transition duration-300 hover:border-primary/40 hover:bg-base-200/80 hover:shadow-lg">
                                <time class="text-sm font-semibold text-primary">{{ $item['hora'] }}</time>
                                <div class="mt-1 text-base font-semibold">{{ $item['titulo'] }}</div>
                                @if(isset($item['hora']) && $item['hora'] === '09:10 às 11:30')
                                    <ul class="list-disc list-inside mt-2 text-sm text-base-content/70 space-y-1">
                                        <li>{{ $item['descricao'] }}</li>
                                        <li class="font-bold">Para as cunhadas atividades como, palestras e ou oficinas.</li>
                                    </ul>
                                @else
                                    <div class="text-sm text-base-content/70">{{ $item['descricao'] }}</div>
                                @endif
                            </div>

                            @if($index < count($cronograma) - 1)
                                <hr class="bg-primary/30" />
                            @endif
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="md:col-span-5 grid gap-4">
            <div class="rounded-2xl border border-base-300 bg-gradient-to-br from-primary/10 to-base-100 p-6 shadow-sm transition duration-500 hover:-translate-y-1 hover:shadow-xl scroll-reveal" data-reveal="fadeInUp" data-reveal-delay="280">
                <div class="text-sm font-semibold text-primary">Visão geral</div>
                    <div class="text-2xl font-bold">Grande Secretaria de Cultura do GOSP</div>
                    <p class="mt-2 text-sm text-base-content/80">A seguir, um resumo da missão e das ações de curadoria que orientam a construção da programação e dos conteúdos apresentados.</p>

                    <div class="mt-4 text-sm text-base-content/80">
                        <div class="rounded-2xl border border-base-300 bg-base-100 p-4 shadow-sm">
                            <div class="font-semibold">Resumo</div>
                            <p class="mt-1">A Grande Secretaria de Cultura do GOSP organiza e seleciona os trabalhos apresentados para garantir coerência pedagógica, padronização de conteúdos e qualidade metodológica, visando aprimorar a formação de aprendizes e companheiros.</p>
                        </div>

                        <div class="mt-4 grid gap-4 md:grid-cols-2">
                            <div class="rounded-2xl border border-base-300 bg-base-100 p-4 shadow-sm">
                                <div class="font-semibold">Estratégia</div>
                                <p class="mt-1">Curadoria temática, seleção de conteúdos e orientação metodológica para palestras e oficinas.</p>
                            </div>

                            <div class="rounded-2xl border border-base-300 bg-base-100 p-4 shadow-sm">
                                <div class="font-semibold">Impacto</div>
                                <p class="mt-1">Formação mais sólida, integração entre Lojas e promoção de práticas que favoreçam o desenvolvimento intelectual e prático dos membros.</p>
                            </div>
                        </div>
                    </div>
            </div>

            <div class="rounded-2xl border border-base-300 bg-base-100/80 p-6 shadow-sm space-y-3 transition duration-500 hover:-translate-y-1 hover:shadow-xl scroll-reveal" data-reveal="fadeInUp" data-reveal-delay="340">
                <div class="text-sm font-semibold text-primary">Pontos-chave</div>
                <ul class="space-y-2 text-sm text-base-content/80">
                    <li class="flex items-center gap-2 transition duration-300 hover:translate-x-1"><span class="h-2 w-2 rounded-full bg-primary animate-pulse"></span> Início e fim claros para todos</li>
                    <li class="flex items-center gap-2 transition duration-300 hover:translate-x-1"><span class="h-2 w-2 rounded-full bg-primary animate-pulse"></span> Alimentação organizada, com café da manhã e almoço</li>
                    <li class="flex items-center gap-2 transition duration-300 hover:translate-x-1"><span class="h-2 w-2 rounded-full bg-primary animate-pulse"></span> Janela oficial das salas: 09h10 às 11h30 (2h20)</li>
                    <li class="flex items-center gap-2 transition duration-300 hover:translate-x-1"><span class="h-2 w-2 rounded-full bg-primary animate-pulse"></span> Trabalhos em salas temáticas com tempo padronizado por Loja</li>
                    <li class="flex items-center gap-2 transition duration-300 hover:translate-x-1"><span class="h-2 w-2 rounded-full bg-primary animate-pulse"></span> Curadoria realizada pela Grande Secretaria de Cultura do GOSP</li>
                    <li class="flex items-center gap-2 transition duration-300 hover:translate-x-1"><span class="h-2 w-2 rounded-full bg-primary animate-pulse"></span> Unificação e padronização de temas e conteúdos essenciais</li>
                    <li class="flex items-center gap-2 transition duration-300 hover:translate-x-1"><span class="h-2 w-2 rounded-full bg-primary animate-pulse"></span> Palestras e oficinas dedicadas às Cunhadas (Palestras, oficinas)</li>
                    <li class="flex items-center gap-2 transition duration-300 hover:translate-x-1"><span class="h-2 w-2 rounded-full bg-primary animate-pulse"></span> Síntese final em plenário para integração das salas</li>
                    <li class="flex items-center gap-2 transition duration-300 hover:translate-x-1"><span class="h-2 w-2 rounded-full bg-primary animate-pulse"></span> Diversidade de abordagens com foco em aplicabilidade prática</li>
                    <li class="flex items-center gap-2 transition duration-300 hover:translate-x-1"><span class="h-2 w-2 rounded-full bg-primary animate-pulse"></span> Integração entre Lojas e promoção do debate coletivo</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="grid gap-4 md:grid-cols-12">
        <div class="md:col-span-4 rounded-2xl border border-base-300 bg-base-100/80 p-5 shadow-sm space-y-3 transition duration-300 hover:-translate-y-1 hover:border-primary/40 hover:shadow-xl">
            <div class="text-sm font-semibold text-primary">Atividades</div>
            <div class="space-y-2 text-sm text-base-content/80">
                <div class="font-semibold text-base">Abertura institucional</div>
                <p>Abertura institucional, palavras dos patrocinadores.</p>
                <p class="mt-2 font-semibold">Para as Cunhadas: Palestras, oficinas</p>
                <div class="font-semibold text-base">Trabalhos em salas</div>
                <p>Apresentações temáticas com perguntas, debate coletivo e síntese por sala.</p>
            </div>
        </div>

        <div class="md:col-span-4 rounded-2xl border border-base-300 bg-base-100/80 p-5 shadow-sm space-y-3 transition duration-300 hover:-translate-y-1 hover:border-primary/40 hover:shadow-xl">
            <div class="text-sm font-semibold text-primary">Temas e palestras</div>
            <div class="space-y-2 text-sm text-base-content/80">
                <div class="font-semibold text-base">Temas dos trabalhos</div>
                <p>Pensamento crítico, autonomia, posicionamento moral, intervenção social e missão maçônica.</p>
                <div class="font-semibold text-base">Formato</div>
                <p>Apresentações por Loja, perguntas objetivas, debate coletivo e síntese final por relator.</p>
            </div>
        </div>

        <div class="md:col-span-4 rounded-2xl border border-base-300 bg-base-100/80 p-5 shadow-sm space-y-3 transition duration-300 hover:-translate-y-1 hover:border-primary/40 hover:shadow-xl">
            <div class="text-sm font-semibold text-primary">Tempo & logística</div>
            <div class="space-y-2 text-sm text-base-content/80">
                <div class="font-semibold text-base">Janela oficial das salas</div>
                <p>Das 09h10 às 11h30, com margem técnica para atrasos e fechamento por sala.</p>
                <div class="font-semibold text-base">Alimentação</div>
                <p>Café da manhã no acolhimento e almoço fraterno após o encerramento oficial.</p>
            </div>
        </div>
    </div>

    <div class="rounded-3xl border border-base-300 bg-base-100/90 shadow-sm p-6 space-y-6 transition duration-500 hover:shadow-2xl scroll-reveal" data-reveal="fadeInUp" data-reveal-delay="120">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-3">
            <div class="space-y-1">
                <div class="text-sm font-semibold text-primary uppercase tracking-widest">Tema Central do Encontro</div>
                <h2 class="text-2xl font-bold">Pensamento, Ação e Fundamento Maçônico</h2>
                <p class="text-sm text-base-content/70">ERAC 27/06/2026 • Loja Fonte de Vida nº 2647 • Programação distribuída em 5 salas temáticas</p>
            </div>
            <div class="rounded-2xl bg-blue-500/10 border border-blue-500/30 px-4 py-3 text-sm text-blue-600 font-semibold transition duration-300 hover:border-blue-500/50 hover:bg-blue-500/15">
                5 Salas • 22 Lojas
            </div>
        </div>

        <div class="rounded-2xl border border-base-300 bg-gradient-to-br from-base-100 to-base-200/70 p-6 shadow-sm space-y-5">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                <div class="space-y-1">
                    <div class="text-sm font-semibold text-primary uppercase tracking-widest">Modelo de Tempo das Apresentações</div>
                    <h3 class="text-xl font-bold">Janela oficial dos trabalhos em salas: 09h10 - 11h30</h3>
                    <p class="text-sm text-base-content/70">O período total das salas tem 2h20, distribuídos entre apresentações, síntese interna e margem técnica.</p>
                </div>
                <div class="rounded-2xl bg-primary/10 border border-primary/30 px-4 py-3 text-sm font-semibold text-primary">
                    2h20 totais
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2 text-sm">
                <div class="rounded-2xl border border-base-300 bg-base-100 p-4">
                    <div class="font-semibold text-base">1h40 de apresentações</div>
                    <p class="mt-1 text-base-content/70">Tempo principal reservado para exposições por Loja, perguntas e debate coletivo.</p>
                </div>
                <div class="rounded-2xl border border-base-300 bg-base-100 p-4">
                    <div class="font-semibold text-base">20 min de síntese da sala</div>
                    <p class="mt-1 text-base-content/70">Fechamento interno para consolidar os principais pontos antes do retorno ao plenário.</p>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2">
                <div class="rounded-2xl border border-primary/30 bg-primary/5 p-5 space-y-2">
                    <div class="text-sm font-semibold uppercase tracking-wide text-primary">Salas com 4 Lojas</div>
                    <div class="text-base font-semibold">20 min por Loja</div>
                    <p class="text-sm text-base-content/75">15 min de exposição + 5 min de perguntas. Total de 1h20 de apresentações, com 20 min de debate coletivo.</p>
                </div>
                <div class="rounded-2xl border border-secondary/30 bg-secondary/8 p-5 space-y-2">
                    <div class="text-sm font-semibold uppercase tracking-wide text-secondary">Salas com 5 Lojas</div>
                    <div class="text-base font-semibold">15 min por Loja</div>
                    <p class="text-sm text-base-content/75">12 min de exposição + 3 min de perguntas. Total de 1h15 de apresentações, com 20 min de debate coletivo.</p>
                </div>
            </div>

            <div class="rounded-2xl border border-base-300 bg-base-100 p-4 text-sm text-base-content/80">
                <span class="font-semibold text-base-content">Síntese no plenário:</span> cada sala terá 2 minutos para apresentação final do relator, totalizando 10 minutos para as 5 salas dentro do encerramento conjunto.
            </div>
        </div>

        <!-- SALA 1 -->
        <div class="rounded-2xl border-2 border-blue-400/40 bg-gradient-to-br from-blue-50 to-blue-100/20 overflow-hidden transition duration-500 hover:shadow-xl hover:border-blue-400/60 dark:from-blue-900/20 dark:to-blue-950/10">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 px-6 py-3">
                <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                    <div class="flex items-center gap-2">
                        <div class="h-3 w-3 rounded-full bg-white"></div>
                        <h3 class="text-lg font-bold text-white">SALA 1: Pensamento Crítico e Maturidade Intelectual</h3>
                    </div>
                    <div class="rounded-full bg-white/15 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-white">
                        4 lojas • 20 min por loja
                    </div>
                </div>
            </div>
            <div class="p-6 grid gap-3 sm:grid-cols-2 md:grid-cols-4">
                @php
                    $sala1 = [
                        ['loja' => 'Acácia de Ubatuba', 'tema' => 'O que significa pensar como um iniciado?'],
                        ['loja' => 'Amor à Ordem Respeitada', 'tema' => 'Conhecimento x Sabedoria'],
                        ['loja' => 'Fraternidade Acadêmica Sementes do Amanhã', 'tema' => 'Pensamento crítico x simples crítica'],
                        ['loja' => 'Estrela Vega', 'tema' => 'Honestidade intelectual e responsabilidade do grau'],
                    ];
                @endphp
                @foreach($sala1 as $item)
                    <div class="rounded-xl border border-blue-200 bg-white p-4 shadow-xs transition duration-300 hover:-translate-y-1 hover:shadow-lg dark:bg-blue-950/30 dark:border-blue-700 max-md:!bg-white max-md:!border-slate-200 max-md:shadow-md">
                        <div class="text-xs font-semibold text-blue-600 uppercase tracking-wide">{{ $item['loja'] }}</div>
                        <div class="text-sm font-bold text-base-content/95 dark:text-base-content mt-2 leading-snug max-md:text-base max-md:!text-slate-900">{{ $item['tema'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- SALA 2 -->
        <div class="rounded-2xl border-2 border-green-400/40 bg-gradient-to-br from-green-50 to-green-100/20 overflow-hidden transition duration-500 hover:shadow-xl hover:border-green-400/60 dark:from-green-900/20 dark:to-green-950/10">
            <div class="bg-gradient-to-r from-green-500 to-green-600 px-6 py-3">
                <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                    <div class="flex items-center gap-2">
                        <div class="h-3 w-3 rounded-full bg-white"></div>
                        <h3 class="text-lg font-bold text-white">SALA 2: Riscos Intelectuais e Autonomia</h3>
                    </div>
                    <div class="rounded-full bg-white/15 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-white">
                        5 lojas • 15 min por loja
                    </div>
                </div>
            </div>
            <div class="p-6 grid gap-3 sm:grid-cols-2 md:grid-cols-5">
                @php
                    $sala2 = [
                        ['loja' => 'Vigilantes de Taubaté', 'tema' => 'Idolatria de ideias, pessoas e cargos'],
                        ['loja' => 'Renascer Caçapava', 'tema' => 'Dogmatização na Maçonaria'],
                        ['loja' => 'Fraternidade Acadêmica Luciano Alfredo Vianna do Rio', 'tema' => 'Tradição: herança viva ou dogma?'],
                        ['loja' => 'Arquitetos da Harmonia', 'tema' => 'Câmaras de eco e viés de conforto'],
                        ['loja' => 'Harmonia e Trabalho', 'tema' => 'Dogmatização e Maçonaria'],
                    ];
                @endphp
                @foreach($sala2 as $item)
                    <div class="rounded-xl border border-green-200 bg-white p-4 shadow-xs transition duration-300 hover:-translate-y-1 hover:shadow-lg dark:bg-green-950/30 dark:border-green-700 max-md:!bg-white max-md:!border-slate-200 max-md:shadow-md">
                        <div class="text-xs font-semibold text-green-600 uppercase tracking-wide">{{ $item['loja'] }}</div>
                        <div class="text-sm font-bold text-base-content/95 dark:text-base-content mt-2 leading-snug max-md:text-base max-md:!text-slate-900">{{ $item['tema'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- SALA 3 -->
        <div class="rounded-2xl border-2 border-yellow-400/40 bg-gradient-to-br from-yellow-50 to-yellow-100/20 overflow-hidden transition duration-500 hover:shadow-xl hover:border-yellow-400/60 dark:from-yellow-900/20 dark:to-yellow-950/10">
            <div class="bg-gradient-to-r from-yellow-500 to-yellow-600 px-6 py-3">
                <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                    <div class="flex items-center gap-2">
                        <div class="h-3 w-3 rounded-full bg-white"></div>
                        <h3 class="text-lg font-bold text-white">SALA 3: Posicionamento Moral e Vida Pública</h3>
                    </div>
                    <div class="rounded-full bg-white/15 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-white">
                        4 lojas • 20 min por loja
                    </div>
                </div>
            </div>
            <div class="p-6 grid gap-3 sm:grid-cols-2 md:grid-cols-4">
                @php
                    $sala3 = [
                        ['loja' => 'Independência e Lealdade', 'tema' => 'O maçom deve posicionar-se diante dos conflitos sociais?'],
                        ['loja' => 'Integridade e Justiça', 'tema' => 'Coragem moral diante da injustiça'],
                        ['loja' => 'Luz do Oriente', 'tema' => 'Papel do maçom frente à desinformação'],
                        ['loja' => 'Luz, Vida e Amor', 'tema' => 'Quando não agir também é escolha'],
                    ];
                @endphp
                @foreach($sala3 as $item)
                    <div class="rounded-xl border border-yellow-200 bg-white p-4 shadow-xs transition duration-300 hover:-translate-y-1 hover:shadow-lg dark:bg-yellow-950/30 dark:border-yellow-700 max-md:!bg-white max-md:!border-slate-200 max-md:shadow-md">
                        <div class="text-xs font-semibold text-yellow-600 uppercase tracking-wide">{{ $item['loja'] }}</div>
                        <div class="text-sm font-bold text-base-content/95 dark:text-base-content mt-2 leading-snug max-md:text-base max-md:!text-slate-900">{{ $item['tema'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- SALA 4 -->
        <div class="rounded-2xl border-2 border-red-400/40 bg-gradient-to-br from-red-50 to-red-100/20 overflow-hidden transition duration-500 hover:shadow-xl hover:border-red-400/60 dark:from-red-900/20 dark:to-red-950/10">
            <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-3">
                <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                    <div class="flex items-center gap-2">
                        <div class="h-3 w-3 rounded-full bg-white"></div>
                        <h3 class="text-lg font-bold text-white">SALA 4: Intervenção Social Concreta</h3>
                    </div>
                    <div class="rounded-full bg-white/15 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-white">
                        5 lojas • 15 min por loja
                    </div>
                </div>
            </div>
            <div class="p-6 grid gap-3 sm:grid-cols-2 md:grid-cols-5">
                @php
                    $sala4 = [
                        ['loja' => 'Solidariedade do Paraitinga', 'tema' => 'Decisões profissionais que impactam terceiros'],
                        ['loja' => 'Templários da Paz', 'tema' => 'Fazer o certo quando custa caro'],
                        ['loja' => 'União das Américas', 'tema' => 'Autoridade sem abuso'],
                        ['loja' => 'União, Força e Vigor', 'tema' => 'Mérito ou favorecimento'],
                        ['loja' => 'Universitária Cavaleiros do Sol', 'tema' => 'Solidariedade sem assistencialismo'],
                    ];
                @endphp
                @foreach($sala4 as $item)
                    <div class="rounded-xl border border-red-200 bg-white p-4 shadow-xs transition duration-300 hover:-translate-y-1 hover:shadow-lg dark:bg-red-950/30 dark:border-red-700 max-md:!bg-white max-md:!border-slate-200 max-md:shadow-md">
                        <div class="text-xs font-semibold text-red-600 uppercase tracking-wide">{{ $item['loja'] }}</div>
                        <div class="text-sm font-bold text-base-content/95 dark:text-base-content mt-2 leading-snug max-md:text-base max-md:!text-slate-900">{{ $item['tema'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- SALA 5 -->
        <div class="rounded-2xl border-2 border-purple-400/40 bg-gradient-to-br from-purple-50 to-purple-100/20 overflow-hidden transition duration-500 hover:shadow-xl hover:border-purple-400/60 dark:from-purple-900/20 dark:to-purple-950/10">
            <div class="bg-gradient-to-r from-purple-500 to-purple-600 px-6 py-3">
                <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                    <div class="flex items-center gap-2">
                        <div class="h-3 w-3 rounded-full bg-white"></div>
                        <h3 class="text-lg font-bold text-white">SALA 5: Fundamentos Doutrinários e Missão</h3>
                    </div>
                    <div class="rounded-full bg-white/15 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-white">
                        5 lojas • 15 min por loja
                    </div>
                </div>
            </div>
            <div class="p-6 grid gap-3 sm:grid-cols-2 md:grid-cols-5">
                @php
                    $sala5 = [
                        ['loja' => 'Fonte de Vida', 'tema' => 'Para que nos reunimos em Loja?'],
                        ['loja' => 'Vinte e Um de Abril', 'tema' => 'Deveres do homem para com a Pátria'],
                        ['loja' => 'Natureza e Fraternidade', 'tema' => 'O maçom como agente de transformação da sociedade'],
                        ['loja' => 'Fraternidade e Integridade Taubateana', 'tema' => 'Sair do conforto em nome da Fraternidade'],
                        ['loja' => 'Colunas de Luz', 'tema' => 'O que significa "Glorificar a verdade e a justiça"?'],
                    ];
                @endphp
                @foreach($sala5 as $item)
                    <div class="rounded-xl border border-purple-200 bg-white p-4 shadow-xs transition duration-300 hover:-translate-y-1 hover:shadow-lg dark:bg-purple-950/30 dark:border-purple-700 max-md:!bg-white max-md:!border-slate-200 max-md:shadow-md">
                        <div class="text-xs font-semibold text-purple-600 uppercase tracking-wide">{{ $item['loja'] }}</div>
                        <div class="text-sm font-bold text-base-content/95 dark:text-base-content mt-2 leading-snug max-md:text-base max-md:!text-slate-900">{{ $item['tema'] }}</div>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
</div>
@endsection
