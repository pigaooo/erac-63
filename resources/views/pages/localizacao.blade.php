@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-10 md:py-14 space-y-8 animate__animated animate__fadeIn">
    <section class="grid gap-4 lg:grid-cols-12">
        <div class="lg:col-span-8 rounded-[2rem] border border-base-300 bg-gradient-to-br from-base-100 via-base-100 to-primary/10 shadow-sm p-6 md:p-8 overflow-hidden relative">
            <div class="absolute -top-16 -right-16 h-48 w-48 rounded-full bg-primary/10 blur-3xl"></div>
            <div class="absolute -bottom-16 -left-10 h-40 w-40 rounded-full bg-secondary/10 blur-3xl"></div>

            <div class="relative space-y-4">
                <div class="inline-flex items-center gap-2 rounded-full border border-primary/20 bg-primary/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-primary">
                    <span class="h-2 w-2 rounded-full bg-primary"></span>
                    Localização
                </div>

                <div class="space-y-2">
                    <h1 class="text-3xl md:text-5xl font-black leading-tight">
                        O ERAC 63 será realizado no Espaço de Eventos Santa Eufrásia
                    </h1>
                    <p class="max-w-3xl text-base md:text-lg text-base-content/70">
                        Um lugar agradável, cercado pela natureza e preparado para receber o público com conforto,
                        boa organização e acesso fácil para quem vem de Santa Branca e região.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3 pt-2">
                    <a
                        href="https://www.google.com/maps/search/?api=1&query=Av.+Roberto+Ugolini,+2900+-+Santa+Branca+-+SP+-+12380-000"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="btn btn-primary rounded-xl px-6"
                    >
                        Abrir no Google Maps
                    </a>
                    <a
                        href="https://www.waze.com/ul?q=Av.%20Roberto%20Ugolini%2C%202900%2C%20Santa%20Branca%20SP"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="btn btn-outline rounded-xl px-6"
                    >
                        Abrir no Waze
                    </a>
                </div>
            </div>
        </div>

        <div class="lg:col-span-4 grid gap-4">
            <div class="rounded-[2rem] border border-base-300 bg-base-100 shadow-sm p-6 space-y-3">
                <div class="text-xs font-bold uppercase tracking-[0.2em] text-base-content/60">Endereço do evento</div>
                <div class="text-lg font-black leading-snug">
                    Av. Roberto Ugolini, 2900
                </div>
                <p class="text-sm text-base-content/75">
                    Espaço de Eventos Santa Eufrásia
                    <br>
                    Santa Branca - SP
                    <br>
                    CEP 12380-000
                </p>
            </div>

            <div class="rounded-[2rem] border border-primary/20 bg-primary/10 shadow-sm p-6 space-y-3">
                <div class="text-xs font-bold uppercase tracking-[0.2em] text-primary">Chegada recomendada</div>
                <div class="text-2xl font-black">Antecedência de 30 a 45 min</div>
                <p class="text-sm text-base-content/75">
                    Assim você estaciona com calma, faz o credenciamento e se organiza melhor para o início do evento.
                </p>
            </div>
        </div>
    </section>

    <section class="grid gap-4 md:grid-cols-12">
        <div class="md:col-span-7 rounded-[2rem] border border-base-300 bg-base-100 shadow-sm overflow-hidden">
            <div class="border-b border-base-300 px-6 py-4">
                <div class="text-sm font-semibold text-primary uppercase tracking-widest">Mapa</div>
                <h2 class="text-2xl font-black">Como chegar ao local do evento</h2>
            </div>
            <div class="aspect-[16/10] w-full">
                <iframe
                    title="Mapa do Espaço Santa Eufrásia"
                    class="h-full w-full"
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"
                    src="https://www.google.com/maps?q=Av.+Roberto+Ugolini,+2900,+Santa+Branca,+SP,+12380-000&output=embed">
                </iframe>
            </div>
        </div>

        <div class="md:col-span-5 grid gap-4">
            <div class="rounded-[2rem] border border-base-300 bg-base-100 shadow-sm p-6 space-y-4">
                <div class="text-sm font-semibold text-primary">Pontos de referência</div>
                <ul class="space-y-3 text-sm text-base-content/80">
                    <li class="flex items-start gap-3">
                        <span class="mt-1 h-2.5 w-2.5 rounded-full bg-primary"></span>
                        O evento acontecerá em Santa Branca, no Espaço de Eventos Santa Eufrásia.
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-1 h-2.5 w-2.5 rounded-full bg-primary"></span>
                        Use o GPS com o endereço completo para chegar direto à entrada.
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="mt-1 h-2.5 w-2.5 rounded-full bg-primary"></span>
                        Saia com antecedência para chegar com tranquilidade ao credenciamento.
                    </li>
                </ul>
            </div>

            <div class="rounded-[2rem] border border-base-300 bg-gradient-to-br from-base-100 to-secondary/10 shadow-sm p-6 space-y-3">
                <div class="text-sm font-semibold text-primary">Organize sua chegada</div>
                <div class="grid grid-cols-2 gap-3 text-sm">
                    <div class="rounded-xl border border-base-300 bg-base-100/80 p-4">
                        <div class="font-semibold">Credenciamento</div>
                        <div class="text-base-content/70">Chegue com documento e confirmação.</div>
                    </div>
                    <div class="rounded-xl border border-base-300 bg-base-100/80 p-4">
                        <div class="font-semibold">Estacionamento</div>
                        <div class="text-base-content/70">Espaço amplo para estacionamento.</div>
                    </div>
                    <div class="rounded-xl border border-base-300 bg-base-100/80 p-4">
                        <div class="font-semibold">Rota</div>
                        <div class="text-base-content/70">Use Google Maps ou Waze para o trajeto.</div>
                    </div>
                    <div class="rounded-xl border border-base-300 bg-base-100/80 p-4">
                        <div class="font-semibold">Antecedência</div>
                        <div class="text-base-content/70">Evite filas e aproveite melhor a abertura.</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="rounded-[2rem] border border-base-300 bg-base-100 shadow-sm p-6 md:p-7 space-y-5">
        <div class="space-y-2">
            <div class="inline-flex items-center gap-2 text-sm uppercase tracking-wide text-primary font-semibold">
                <span class="h-2.5 w-2.5 rounded-full bg-primary"></span>
                Pontos positivos do local
            </div>
            <h2 class="text-2xl md:text-3xl font-black">Um espaço que combina com o ERAC 63</h2>
            <p class="text-sm md:text-base text-base-content/70 max-w-3xl">
                O local oferece uma estrutura acolhedora para receber os participantes ao longo de todo o evento.
            </p>
        </div>

        <div class="grid gap-4 md:grid-cols-12">
            <div class="md:col-span-4 rounded-[1.75rem] border border-base-300 bg-base-200/50 p-6 space-y-3">
                <div class="badge badge-primary badge-outline">Conforto</div>
                <h3 class="text-xl font-bold">Espaço amplo para estacionamento</h3>
                <p class="text-sm text-base-content/70">
                    Mais praticidade para a chegada dos participantes e melhor fluxo logo no início do encontro.
                </p>
            </div>

            <div class="md:col-span-4 rounded-[1.75rem] border border-base-300 bg-base-200/50 p-6 space-y-3">
                <div class="badge badge-primary badge-outline">Ambiente</div>
                <h3 class="text-xl font-bold">Área verde e clima agradável</h3>
                <p class="text-sm text-base-content/70">
                    Um ambiente mais leve e acolhedor para recepção, convivência e confraternização.
                </p>
            </div>

            <div class="md:col-span-4 rounded-[1.75rem] border border-base-300 bg-base-200/50 p-6 space-y-3">
                <div class="badge badge-primary badge-outline">Estrutura</div>
                <h3 class="text-xl font-bold">Boa estrutura para eventos</h3>
                <p class="text-sm text-base-content/70">
                    Um espaço preparado para receber o público com organização, circulação e conforto.
                </p>
            </div>
        </div>
    </section>

    <section class="grid gap-4 md:grid-cols-12">
        <div class="md:col-span-5 rounded-[2rem] border border-base-300 bg-gradient-to-br from-primary/10 to-base-100 shadow-sm p-6 space-y-4">
            <div class="text-sm font-semibold text-primary uppercase tracking-widest">Resumo rápido</div>
            <h2 class="text-2xl font-black">Local bonito, acessível e acolhedor</h2>
            <p class="text-sm text-base-content/75">
                O Espaço Santa Eufrásia reúne boa estrutura, área verde e praticidade para receber os participantes
                do ERAC 63 com conforto ao longo de todo o dia.
            </p>
            <div class="rounded-2xl border border-primary/20 bg-base-100/70 px-4 py-3 text-sm text-base-content/70">
                Isso ajuda a tornar a chegada mais tranquila, o ambiente mais agradável e a experiência do evento mais especial.
            </div>
        </div>

        <div class="md:col-span-7 rounded-[2rem] border border-base-300 bg-base-100 shadow-sm p-6 space-y-4">
            <div class="text-sm font-semibold text-primary">Informações úteis para o dia</div>
            <div class="grid gap-3 sm:grid-cols-2">
                <div class="rounded-2xl border border-base-300 bg-base-200/40 p-4">
                    <div class="font-semibold">Endereço completo</div>
                    <div class="text-sm text-base-content/70">Av. Roberto Ugolini, 2900 - Santa Branca - SP - CEP 12380-000</div>
                </div>
                <div class="rounded-2xl border border-base-300 bg-base-200/40 p-4">
                    <div class="font-semibold">Local</div>
                    <div class="text-sm text-base-content/70">Espaço de Eventos Santa Eufrásia</div>
                </div>
                <div class="rounded-2xl border border-base-300 bg-base-200/40 p-4">
                    <div class="font-semibold">Melhor prática</div>
                    <div class="text-sm text-base-content/70">Saia com antecedência e confira o trajeto antes do evento.</div>
                </div>
                <div class="rounded-2xl border border-base-300 bg-base-200/40 p-4">
                    <div class="font-semibold">Navegação</div>
                    <div class="text-sm text-base-content/70">Use os botões desta página para abrir a rota direto no app.</div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
