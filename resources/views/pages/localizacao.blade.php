@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 pt-8 md:pt-10">
    @include('componentes.patrocinadores-carousel', ['patrocinadores' => $patrocinadores ?? collect()])
</div>
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-10 md:py-14 space-y-8 scroll-reveal" data-reveal="fadeIn">
    <section class="grid gap-4 lg:grid-cols-12">
        <div class="lg:col-span-8 rounded-[2rem] border border-base-300 bg-gradient-to-br from-base-100 via-base-100 to-primary/10 shadow-sm p-6 md:p-8 overflow-hidden relative transition duration-500 hover:-translate-y-1 hover:shadow-2xl scroll-reveal" data-reveal="fadeInUp">
            <div class="absolute -top-16 -right-16 h-48 w-48 rounded-full bg-primary/10 blur-3xl animate-pulse"></div>
            <div class="absolute -bottom-16 -left-10 h-40 w-40 rounded-full bg-secondary/10 blur-3xl animate-pulse"></div>
            <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-primary/40 to-transparent"></div>

            <div class="relative space-y-4">
                <div class="inline-flex items-center gap-2 rounded-full border border-primary/20 bg-primary/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-primary">
                    <span class="h-2 w-2 rounded-full bg-primary animate-pulse"></span>
                    Localização
                </div>

                <div class="space-y-2">
                    <h1 class="text-3xl md:text-5xl font-black leading-tight scroll-reveal" data-reveal="fadeInUp" data-reveal-delay="80">
                        O ERAC 61 será realizado no Espaço de Eventos Santa Eufrásia
                    </h1>
                    <p class="max-w-3xl text-base md:text-lg text-base-content/70 scroll-reveal" data-reveal="fadeInUp" data-reveal-delay="140">
                        Um lugar agradável, cercado pela natureza e preparado para receber o público com conforto,
                        boa organização e acesso fácil para quem vem de Santa Branca e região.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3 pt-2 scroll-reveal" data-reveal="fadeInUp" data-reveal-delay="200">
                    <a
                        href="https://www.google.com/maps/search/?api=1&query=Espa%C3%A7o+Santa+Eufr%C3%A1sia+Entrada+Jequitib%C3%A1"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="btn btn-primary rounded-xl px-6 transition duration-300 hover:scale-[1.03] hover:shadow-lg"
                    >
                        Abrir no Google Maps
                    </a>
                    <a
                        href="https://www.waze.com/ul?q=Espa%C3%A7o%20Santa%20Eufr%C3%A1sia%20Entrada%20Jequitib%C3%A1"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="btn btn-outline rounded-xl px-6 transition duration-300 hover:scale-[1.03] hover:border-primary/50"
                    >
                        Abrir no Waze
                    </a>
                </div>
            </div>
        </div>

        <div class="lg:col-span-4 grid gap-4">
            <div class="rounded-[2rem] border border-base-300 bg-base-100 shadow-sm p-6 space-y-3 transition duration-500 hover:-translate-y-1 hover:shadow-xl scroll-reveal" data-reveal="fadeInRight" data-reveal-delay="120">
                <div class="text-xs font-bold uppercase tracking-[0.2em] text-base-content/60">Endereço do evento</div>
                <div class="text-lg font-black leading-snug">
                    Entrada Jequitibá
                </div>
                <p class="text-sm text-base-content/75">
                    Espaço Santa Eufrásia
                    <br>
                    Santa Branca - SP
                    <br>
                    Acesso recomendado pelo Maps e Waze
                </p>
            </div>

            <div class="rounded-[2rem] border border-amber-400/80 bg-gradient-to-br from-amber-200 via-amber-50 to-base-100 shadow-lg p-6 space-y-3 transition duration-500 hover:-translate-y-1 hover:shadow-2xl scroll-reveal" data-reveal="fadeInRight" data-reveal-delay="170">
                <div class="inline-flex w-fit items-center rounded-full border border-amber-700/70 bg-amber-300 px-3 py-1 text-[11px] font-black uppercase tracking-[0.22em] text-amber-950">
                    Atenção na entrada
                </div>
                <div class="text-xl font-black leading-snug text-amber-950">Prefira a Entrada Jequitibá</div>
                <p class="text-sm leading-relaxed text-stone-900">
                    A entrada Jequitibá é mais segura e recomendada para chegada ao evento do que a entrada Eucaliptos.
                </p>
            </div>

        </div>
    </section>

    <section class="grid gap-4 md:grid-cols-12">
        <div class="md:col-span-7 rounded-[2rem] border border-base-300 bg-base-100 shadow-sm overflow-hidden transition duration-500 hover:-translate-y-1 hover:shadow-2xl scroll-reveal" data-reveal="fadeInUp" data-reveal-delay="120">
            <div class="border-b border-base-300 px-6 py-4">
                <div class="text-sm font-semibold text-primary uppercase tracking-widest">Mapa</div>
                <h2 class="text-2xl font-black">Como chegar ao local do evento</h2>
            </div>
            <div class="aspect-[16/10] w-full">
                <iframe
                    title="Mapa do Espaço Santa Eufrásia - Entrada Jequitibá"
                    class="h-full w-full"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    src="https://www.google.com/maps?q=Espa%C3%A7o+Santa+Eufr%C3%A1sia+Entrada+Jequitib%C3%A1&output=embed">
                </iframe>
            </div>
        </div>

        <div class="md:col-span-5 grid gap-4">
            <div class="rounded-[2rem] border border-base-300 bg-base-100 shadow-sm p-6 space-y-4 transition duration-500 hover:-translate-y-1 hover:shadow-xl scroll-reveal" data-reveal="fadeInUp" data-reveal-delay="180">
                <div class="text-sm font-semibold text-primary">Pontos de referência</div>
                <ul class="space-y-3 text-sm text-base-content/80">
                    <li class="flex items-start gap-3 transition duration-300 hover:translate-x-1">
                        <span class="mt-1 h-2.5 w-2.5 rounded-full bg-primary animate-pulse"></span>
                        O evento acontecerá em Santa Branca, no Espaço de Eventos Santa Eufrásia.
                    </li>
                    <li class="flex items-start gap-3 transition duration-300 hover:translate-x-1">
                        <span class="mt-1 h-2.5 w-2.5 rounded-full bg-primary animate-pulse"></span>
                        Use o GPS com a rota para a Entrada Jequitibá para chegar com mais segurança.
                    </li>
                    <li class="flex items-start gap-3 transition duration-300 hover:translate-x-1">
                        <span class="mt-1 h-2.5 w-2.5 rounded-full bg-primary animate-pulse"></span>
                        Saia com antecedência para chegar com tranquilidade ao credenciamento.
                    </li>
                </ul>
            </div>

            <div class="rounded-[2rem] border border-base-300 bg-gradient-to-br from-base-100 to-secondary/10 shadow-sm p-6 space-y-3 transition duration-500 hover:-translate-y-1 hover:shadow-xl scroll-reveal" data-reveal="fadeInUp" data-reveal-delay="240">
                <div class="text-sm font-semibold text-primary">Organize sua chegada</div>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div class="rounded-xl border border-base-300 bg-base-100/80 p-4 transition duration-300 hover:scale-[1.02] hover:border-primary/40 hover:shadow-md">
                        <div class="font-semibold">Credenciamento</div>
                        <div class="text-base-content/70">Chegue com documento e confirmação.</div>
                    </div>
                    <div class="rounded-xl border border-base-300 bg-base-100/80 p-4 transition duration-300 hover:scale-[1.02] hover:border-primary/40 hover:shadow-md">
                        <div class="font-semibold">Estacionamento</div>
                        <div class="text-base-content/70">Espaço amplo para estacionamento.</div>
                    </div>
                    <div class="rounded-xl border border-base-300 bg-base-100/80 p-4 transition duration-300 hover:scale-[1.02] hover:border-primary/40 hover:shadow-md">
                        <div class="font-semibold">Rota</div>
                        <div class="text-base-content/70">Use Google Maps ou Waze para o trajeto.</div>
                    </div>
                    <div class="rounded-xl border border-base-300 bg-base-100/80 p-4 transition duration-300 hover:scale-[1.02] hover:border-primary/40 hover:shadow-md">
                        <div class="font-semibold">Antecedência</div>
                        <div class="text-base-content/70">Evite filas e aproveite melhor a abertura.</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="rounded-[2rem] border border-base-300 bg-base-100 shadow-sm p-6 md:p-7 space-y-5 transition duration-500 hover:shadow-2xl scroll-reveal" data-reveal="fadeInUp" data-reveal-delay="280">
        <div class="space-y-2">
            <div class="inline-flex items-center gap-2 text-sm uppercase tracking-wide text-primary font-semibold">
                <span class="h-2.5 w-2.5 rounded-full bg-primary animate-pulse"></span>
                Pontos positivos do local
            </div>
            <h2 class="text-2xl md:text-3xl font-black">Um espaço que combina com o ERAC 61</h2>
            <p class="text-sm md:text-base text-base-content/70 max-w-3xl">
                O local oferece uma estrutura acolhedora para receber os participantes ao longo de todo o evento.
            </p>
        </div>

        <div class="grid gap-4 md:grid-cols-12">
            <div class="md:col-span-4 rounded-[1.75rem] border border-base-300 bg-base-200/50 p-6 space-y-3 transition duration-300 hover:-translate-y-1 hover:border-primary/40 hover:bg-base-200/80 hover:shadow-xl">
                <div class="badge badge-primary badge-outline">Conforto</div>
                <h3 class="text-xl font-bold">Espaço amplo para estacionamento</h3>
                <p class="text-sm text-base-content/70">
                    Mais praticidade para a chegada dos participantes e melhor fluxo logo no início do encontro.
                </p>
            </div>

            <div class="md:col-span-4 rounded-[1.75rem] border border-base-300 bg-base-200/50 p-6 space-y-3 transition duration-300 hover:-translate-y-1 hover:border-primary/40 hover:bg-base-200/80 hover:shadow-xl">
                <div class="badge badge-primary badge-outline">Ambiente</div>
                <h3 class="text-xl font-bold">Área verde e clima agradável</h3>
                <p class="text-sm text-base-content/70">
                    Um ambiente mais leve e acolhedor para recepção, convivência e confraternização.
                </p>
            </div>

            <div class="md:col-span-4 rounded-[1.75rem] border border-base-300 bg-base-200/50 p-6 space-y-3 transition duration-300 hover:-translate-y-1 hover:border-primary/40 hover:bg-base-200/80 hover:shadow-xl">
                <div class="badge badge-primary badge-outline">Estrutura</div>
                <h3 class="text-xl font-bold">Boa estrutura para eventos</h3>
                <p class="text-sm text-base-content/70">
                    Um espaço preparado para receber o público com organização, circulação e conforto.
                </p>
            </div>
        </div>
    </section>

    <section class="grid gap-4 md:grid-cols-12">
        <div class="md:col-span-5 rounded-[2rem] border border-base-300 bg-gradient-to-br from-primary/10 to-base-100 shadow-sm p-6 space-y-4 transition duration-500 hover:-translate-y-1 hover:shadow-xl scroll-reveal" data-reveal="fadeInUp" data-reveal-delay="340">
            <div class="text-sm font-semibold text-primary uppercase tracking-widest">Resumo rápido</div>
            <h2 class="text-2xl font-black">Local bonito, acessível e acolhedor</h2>
            <p class="text-sm text-base-content/75">
                O Espaço Santa Eufrásia reúne boa estrutura, área verde e praticidade para receber os participantes
                do ERAC 61 com conforto ao longo de todo o dia.
            </p>
            <div class="rounded-2xl border border-primary/20 bg-base-100/70 px-4 py-3 text-sm text-base-content/70 transition duration-300 hover:border-primary/40">
                Isso ajuda a tornar a chegada mais tranquila, o ambiente mais agradável e a experiência do evento mais especial.
            </div>
        </div>

        <div class="md:col-span-7 rounded-[2rem] border border-base-300 bg-base-100 shadow-sm p-6 space-y-4 transition duration-500 hover:-translate-y-1 hover:shadow-xl scroll-reveal" data-reveal="fadeInUp" data-reveal-delay="400">
            <div class="text-sm font-semibold text-primary">Informações úteis para o dia</div>
            <div class="grid gap-3 sm:grid-cols-2">
                <div class="rounded-2xl border border-base-300 bg-base-200/40 p-4 transition duration-300 hover:scale-[1.02] hover:border-primary/40 hover:shadow-md">
                    <div class="font-semibold">Endereço completo</div>
                    <div class="text-sm text-base-content/70">Espaço Santa Eufrásia - Entrada Jequitibá - Santa Branca - SP</div>
                </div>
                <div class="rounded-2xl border border-base-300 bg-base-200/40 p-4 transition duration-300 hover:scale-[1.02] hover:border-primary/40 hover:shadow-md">
                    <div class="font-semibold">Local</div>
                    <div class="text-sm text-base-content/70">Espaço de Eventos Santa Eufrásia</div>
                </div>
                <div class="rounded-2xl border border-base-300 bg-base-200/40 p-4 transition duration-300 hover:scale-[1.02] hover:border-primary/40 hover:shadow-md">
                    <div class="font-semibold">Melhor prática</div>
                    <div class="text-sm text-base-content/70">Saia com antecedência e confira o trajeto antes do evento.</div>
                </div>
                <div class="rounded-2xl border border-base-300 bg-base-200/40 p-4 transition duration-300 hover:scale-[1.02] hover:border-primary/40 hover:shadow-md">
                    <div class="font-semibold">Navegação</div>
                    <div class="text-sm text-base-content/70">Use os botões desta página para abrir a rota direto no app.</div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
