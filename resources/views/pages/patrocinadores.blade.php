@extends('layouts.app')

@section('content')
@php
    $tipos = [
        'Diamante' => [
            'descricao' => 'Parceiros principais do evento — recebem o destaque visual e maior visibilidade.',
            'grid' => 'grid-cols-1 lg:grid-cols-2',
            'size' => 'diamante',
        ],
        'Ouro' => [
            'descricao' => 'Apoio importante — empresas com grande participação no evento e visibilidade ampliada.',
            'grid' => 'grid-cols-1 md:grid-cols-2 xl:grid-cols-3',
            'size' => 'ouro',
        ],
        'Prata' => [
            'descricao' => 'Apoiadores que contribuem de forma relevante e têm boa presença nas comunicações do encontro.',
            'grid' => 'grid-cols-1 md:grid-cols-2 xl:grid-cols-3',
            'size' => 'prata',
        ],
        'Bronze' => [
            'descricao' => 'Apoio institucional — parceiros com presença mais discreta, mas importante para o evento.',
            'grid' => 'grid-cols-1 sm:grid-cols-2 xl:grid-cols-4',
            'size' => 'bronze',
        ],
    ];
@endphp

<div class="mx-auto max-w-6xl px-4 pb-16 pt-10 sm:px-6">
    <section class="relative overflow-hidden rounded-[2rem] border border-base-300 bg-base-100 px-6 py-8 shadow-xl scroll-reveal md:px-10 md:py-12" data-reveal="fadeIn">
        <div class="absolute inset-0 bg-gradient-to-br from-primary/10 via-base-100 to-secondary/10"></div>
        <div class="absolute -right-16 top-0 h-48 w-48 rounded-full bg-primary/10 blur-3xl"></div>
        <div class="absolute -bottom-16 left-0 h-48 w-48 rounded-full bg-secondary/10 blur-3xl"></div>

        <div class="relative max-w-3xl space-y-4">
            <p class="text-sm font-semibold uppercase tracking-[0.28em] text-primary">Patrocinadores</p>
            <h1 class="text-3xl font-black text-base-content md:text-5xl">Marcas e apoiadores que fortalecem o ERAC 61</h1>
            <p class="text-sm leading-7 text-base-content/75 md:text-base">
                Conheça as empresas e organizações que apoiam o ERAC 61. Estão organizadas por nível de parceria para você
                identificar rapidamente quem contribui para o evento.
            </p>
        </div>
    </section>

    <div class="mt-10 space-y-10">
        @foreach($tipos as $tipo => $config)
            @php($grupo = $patrocinadoresPorTipo->get($tipo, collect()))

            @if($grupo->isNotEmpty())
                <section class="space-y-5">
                    <div class="space-y-2 scroll-reveal" data-reveal="fadeInUp">
                        <p class="inline-block text-sm font-bold uppercase tracking-[0.24em] text-primary bg-primary/10 border border-primary/20 px-3 py-1 rounded-full shadow-sm">{{ $tipo }}</p>
                        <h2 class="text-2xl md:text-3xl font-extrabold text-base-content">Patrocinadores {{ strtolower($tipo) }}</h2>
                        <p class="max-w-3xl text-sm leading-6 text-base-content/70">{{ $config['descricao'] }}</p>
                    </div>

                    <div class="grid gap-5 {{ $config['grid'] }}">
                        @foreach($grupo as $index => $patrocinador)
                            <div
                                class="scroll-reveal"
                                data-reveal="fadeInUp"
                                data-reveal-delay="{{ 80 + (($index % 6) * 70) }}"
                            >
                                @include('componentes.patrocinador-card', [
                                    'patrocinador' => $patrocinador,
                                    'size' => $config['size'],
                                ])
                            </div>
                        @endforeach
                    </div>
                </section>
            @endif
        @endforeach

        @php($apoios = $patrocinadoresPorTipo->get('Apoio', collect()))

        @if($apoios->isNotEmpty())
            <section class="space-y-5">
                <div class="space-y-2 scroll-reveal" data-reveal="fadeInUp">
                    <p class="text-sm font-semibold uppercase tracking-[0.24em] text-primary">Apoio</p>
                    <h2 class="text-2xl font-bold text-base-content">Apoiadores</h2>
                    <p class="max-w-3xl text-sm leading-6 text-base-content/70">
                        Organizações que oferecem apoio e colaboração ao encontro. Agradecemos a todas pelo suporte.
                    </p>
                </div>

                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($apoios as $index => $apoiador)
                        <div
                            class="sponsor-name-chip scroll-reveal rounded-[1.2rem] border border-base-300 bg-base-100/80 px-5 py-4 text-sm font-semibold text-base-content shadow-sm"
                            data-reveal="fadeInUp"
                            data-reveal-delay="{{ 90 + (($index % 6) * 60) }}"
                        >
                            {{ $apoiador->name }}
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
    </div>
</div>
@endsection
