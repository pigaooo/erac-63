@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 pt-8 md:pt-10">
    @include('componentes.patrocinadores-carousel', ['patrocinadores' => $patrocinadores ?? collect()])
</div>
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-12 space-y-6">
    <section class="relative overflow-hidden rounded-[2rem] border border-base-300 bg-base-100 p-6 shadow-xl md:p-10 scroll-reveal" data-reveal="fadeIn">
        <div class="absolute inset-y-0 left-0 w-2 bg-gradient-to-b from-primary via-primary/70 to-transparent"></div>
        <div class="absolute -right-16 -top-20 h-48 w-48 rounded-full bg-primary/10 blur-3xl"></div>

        <div class="relative max-w-4xl space-y-4">
            <p class="text-sm font-semibold uppercase tracking-widest text-primary">Sobre</p>
            <h1 class="text-3xl font-black leading-tight text-base-content md:text-5xl">Sobre o ERAC 61</h1>
            <p class="text-base leading-7 text-base-content/78 md:text-lg">
                <strong class="font-bold text-base-content">ERAC - Encontro Regional de Aprendizes e Companheiros</strong>
                é um evento institucional voltado ao fortalecimento da formação maçônica e ao desenvolvimento de lideranças dentro da Ordem.
            </p>
        </div>
    </section>

    <section class="grid gap-4 md:grid-cols-12 scroll-reveal" data-reveal="fadeInUp" data-reveal-delay="120">
        <div class="rounded-[1.8rem] border border-base-300 bg-base-100 p-6 shadow-lg md:col-span-5">
            <div class="text-xs font-semibold uppercase tracking-[0.22em] text-primary">Propósito</div>
            <h2 class="mt-3 text-2xl font-bold text-base-content">Um encontro para formação, união e liderança</h2>
            <p class="mt-3 text-sm leading-6 text-base-content/72">
                O encontro reúne membros das Lojas Maçônicas da região para promover aprendizado, convivência fraterna e visão estratégica para o futuro da Ordem.
            </p>
        </div>

        <div class="rounded-[1.8rem] border border-base-300 bg-base-100 p-6 shadow-lg md:col-span-7">
            <div class="text-xs font-semibold uppercase tracking-[0.22em] text-primary">O encontro promove</div>
            <ul class="mt-5 grid gap-3 text-sm font-medium text-base-content/78 sm:grid-cols-2">
                <li class="flex items-start gap-3 rounded-2xl border border-base-300 bg-base-200/50 p-4">
                    <span class="mt-1 h-2.5 w-2.5 rounded-full bg-primary"></span>
                    Formação intelectual
                </li>
                <li class="flex items-start gap-3 rounded-2xl border border-base-300 bg-base-200/50 p-4">
                    <span class="mt-1 h-2.5 w-2.5 rounded-full bg-primary"></span>
                    Aprofundamento dos valores maçônicos
                </li>
                <li class="flex items-start gap-3 rounded-2xl border border-base-300 bg-base-200/50 p-4">
                    <span class="mt-1 h-2.5 w-2.5 rounded-full bg-primary"></span>
                    Integração entre Lojas
                </li>
                <li class="flex items-start gap-3 rounded-2xl border border-base-300 bg-base-200/50 p-4">
                    <span class="mt-1 h-2.5 w-2.5 rounded-full bg-primary"></span>
                    Networking entre irmãos
                </li>
                <li class="flex items-start gap-3 rounded-2xl border border-base-300 bg-base-200/50 p-4 sm:col-span-2">
                    <span class="mt-1 h-2.5 w-2.5 rounded-full bg-primary"></span>
                    Desenvolvimento de novas lideranças
                </li>
            </ul>
        </div>
    </section>

    <section class="rounded-[1.8rem] border border-primary/25 bg-gradient-to-br from-primary/18 via-primary/10 to-base-100 p-6 shadow-lg shadow-primary/10 md:p-8 scroll-reveal" data-reveal="fadeInUp" data-reveal-delay="180">
        <div class="max-w-4xl">
            <div class="text-xs font-semibold uppercase tracking-[0.22em] text-primary">Visão</div>
            <p class="mt-3 text-xl font-bold leading-8 text-base-content md:text-2xl">
                Mais do que um encontro, o ERAC é um espaço de aprendizado, união e construção de visão estratégica para o futuro da Ordem.
            </p>
        </div>
    </section>
</div>
@endsection
