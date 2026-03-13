@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 pt-8 pb-12">
    @include('componentes.patrocinadores-carousel', ['patrocinadores' => $patrocinadores ?? collect()])
</div>

<div class="max-w-6xl mx-auto px-4 sm:px-6 pb-14 space-y-6">
    <section class="relative overflow-hidden rounded-[2rem] border border-base-300 bg-base-100 shadow-xl animate__animated animate__fadeIn">
        <div class="absolute inset-0 bg-cover bg-center opacity-20" style="background-image: url('/images/fundo-banner.png');"></div>
        <div class="absolute inset-0 bg-gradient-to-br from-base-100 via-base-100/96 to-base-200/88"></div>
        <div class="absolute left-0 top-0 h-full w-2 bg-gradient-to-b from-primary via-primary/60 to-transparent"></div>

        <div class="relative p-6 md:p-10 lg:p-14">
            <div class="md:hidden space-y-4 animate__animated animate__fadeInUp animate__faster">
                <div class="inline-flex w-fit items-center gap-2 rounded-full border border-primary/20 bg-primary/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.28em] text-primary">
                    <span class="h-2 w-2 rounded-full bg-primary"></span>
                    ERAC 61
                </div>

                <h1 class="text-3xl font-black leading-tight text-base-content">
                    ERAC 61
                </h1>

                <p class="text-sm text-base-content/72">
                    Encontro Regional de Aprendizes e Companheiros.
                </p>
            </div>

            <div id="hero-inicial-home" class="hidden md:flex items-center">
                <div class="max-w-4xl space-y-6 animate__animated animate__fadeInLeft">
                    <div class="inline-flex w-fit items-center gap-2 rounded-full border border-primary/20 bg-primary/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.28em] text-primary">
                        <span class="h-2 w-2 rounded-full bg-primary"></span>
                        ERAC 61
                    </div>

                    <div class="space-y-4">
                        <h1 class="max-w-4xl text-4xl font-black leading-[1.05] text-base-content md:text-5xl lg:text-6xl">
                            Bem-vindos ao 61º Encontro Regional de Aprendizes e Companheiros
                        </h1>
                        <p class="max-w-3xl text-base text-base-content/75 md:text-lg">
                            Um encontro voltado à formação, à fraternidade e à convivência entre Aprendizes e Companheiros,
                            reunindo irmãos da 9ª Região Administrativa do GOSP em um dia de estudos, integração e celebração.
                        </p>
                    </div>

                    <div class="grid gap-4 md:grid-cols-2">
                        <div class="rounded-[1.5rem] border border-base-300 bg-base-200/55 p-5 shadow-sm">
                            <div class="text-xs font-semibold uppercase tracking-[0.22em] text-primary">Região</div>
                            <div class="mt-3 text-lg font-semibold text-base-content">9ª Região Administrativa do GOSP</div>
                            <p class="mt-2 text-sm text-base-content/70">
                                Representando uma região marcada pelo trabalho, pela união entre as Lojas e pelo compromisso com a formação maçônica.
                            </p>
                        </div>

                        <div class="rounded-[1.5rem] border border-base-300 bg-base-200/55 p-5 shadow-sm">
                            <div class="text-xs font-semibold uppercase tracking-[0.22em] text-primary">Propósito</div>
                            <div class="mt-3 text-lg font-semibold text-base-content">Aprendizado, fraternidade e convivência</div>
                            <p class="mt-2 text-sm text-base-content/70">
                                Um ambiente preparado para fortalecer vínculos, compartilhar conhecimento e viver um encontro memorável entre irmãos.
                            </p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3 pt-1">
                        <a href="{{ route('inscricao') }}" class="btn btn-primary rounded-xl px-6">Inscreva-se agora</a>
                        <a href="{{ route('programacao') }}" class="btn btn-outline rounded-xl px-6">Ver programação</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="grid grid-cols-1 gap-3 md:hidden">
        <a href="{{ route('localizacao') }}" class="rounded-[1.6rem] border border-base-300 bg-base-100 p-4 shadow-md transition duration-300 hover:border-primary/40 hover:shadow-xl animate__animated animate__fadeInLeft">
            <div class="flex items-start gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-primary/10 text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 12a4 4 0 100-8 4 4 0 000 8z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 22s7-4.35 7-10a7 7 0 10-14 0c0 5.65 7 10 7 10z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-sm font-semibold uppercase tracking-[0.18em] text-primary">Localização</div>
                    <div class="mt-1 text-base font-semibold text-base-content">Como chegar ao evento</div>
                    <div class="mt-1 text-sm text-base-content/70">Endereço, mapa e referências do local.</div>
                </div>
            </div>
        </a>

        <a href="{{ route('programacao') }}" class="rounded-[1.6rem] border border-base-300 bg-base-100 p-4 shadow-md transition duration-300 hover:border-primary/40 hover:shadow-xl animate__animated animate__fadeInRight">
            <div class="flex items-start gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-secondary/10 text-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3.75M16 7v-3.25M4.75 9.75h14.5M6 5.75h12A1.25 1.25 0 0119.25 7v11A1.25 1.25 0 0118 19.25H6A1.25 1.25 0 014.75 18V7A1.25 1.25 0 016 5.75z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-sm font-semibold uppercase tracking-[0.18em] text-primary">Programação</div>
                    <div class="mt-1 text-base font-semibold text-base-content">Agenda do ERAC</div>
                    <div class="mt-1 text-sm text-base-content/70">Horários, atividades e momentos do encontro.</div>
                </div>
            </div>
        </a>

        <a href="{{ route('inscricao') }}" class="rounded-[1.6rem] border border-base-300 bg-base-100 p-4 shadow-md transition duration-300 hover:border-primary/40 hover:shadow-xl animate__animated animate__fadeInLeft">
            <div class="flex items-start gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-accent/10 text-accent">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.5 4.5l-9 9m0 0V7.5m0 6L13.5 18"/>
                    </svg>
                </div>
                <div>
                    <div class="text-sm font-semibold uppercase tracking-[0.18em] text-primary">Inscrição</div>
                    <div class="mt-1 text-base font-semibold text-base-content">Garanta sua vaga</div>
                    <div class="mt-1 text-sm text-base-content/70">Inscrição individual ou em lote para a sua Loja.</div>
                </div>
            </div>
        </a>

        <a href="{{ route('sobre') }}" class="rounded-[1.6rem] border border-base-300 bg-base-100 p-4 shadow-md transition duration-300 hover:border-primary/40 hover:shadow-xl animate__animated animate__fadeInRight">
            <div class="flex items-start gap-4">
                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl bg-info/10 text-info">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18v-7.5"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 21a9 9 0 100-18 9 9 0 000 18z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-sm font-semibold uppercase tracking-[0.18em] text-primary">Sobre</div>
                    <div class="mt-1 text-base font-semibold text-base-content">Conheça o encontro</div>
                    <div class="mt-1 text-sm text-base-content/70">Contexto, organização e propósito do ERAC.</div>
                </div>
            </div>
        </a>
    </section>

    <section class="grid gap-4 md:grid-cols-12 animate__animated animate__fadeInUp">
        <a href="{{ route('inscricao') }}" class="hidden md:block md:col-span-5 rounded-[1.8rem] border border-base-300 bg-base-100 p-6 shadow-lg transition duration-300 hover:-translate-y-1 hover:border-primary/40 hover:shadow-2xl">
            <div class="text-xs font-semibold uppercase tracking-[0.22em] text-primary">Inscrição</div>
            <h2 class="mt-3 text-2xl font-bold text-base-content">Garanta sua participação no encontro</h2>
            <p class="mt-3 text-sm text-base-content/72">
                Faça sua inscrição individual ou em lote com um fluxo simples e organizado para a sua Loja.
            </p>
            <div class="mt-5 text-sm font-semibold text-primary">Acessar inscrições</div>
        </a>

        <a href="{{ route('programacao') }}" class="hidden md:block md:col-span-4 rounded-[1.8rem] border border-base-300 bg-base-100 p-6 shadow-lg transition duration-300 hover:-translate-y-1 hover:border-primary/40 hover:shadow-2xl">
            <div class="text-xs font-semibold uppercase tracking-[0.22em] text-primary">Programação</div>
            <h2 class="mt-3 text-xl font-bold text-base-content">Conheça a agenda do ERAC</h2>
            <p class="mt-3 text-sm text-base-content/72">
                Horários, trabalhos, momentos de confraternização e principais atividades do evento.
            </p>
        </a>

        <a href="{{ route('localizacao') }}" class="hidden md:block md:col-span-3 rounded-[1.8rem] border border-base-300 bg-base-100 p-6 shadow-lg transition duration-300 hover:-translate-y-1 hover:border-primary/40 hover:shadow-2xl">
            <div class="text-xs font-semibold uppercase tracking-[0.22em] text-primary">Localização</div>
            <h2 class="mt-3 text-xl font-bold text-base-content">Saiba como chegar</h2>
            <p class="mt-3 text-sm text-base-content/72">
                Veja o endereço, mapa e referências do local do evento.
            </p>
        </a>

        <a href="{{ route('sobre') }}" class="hidden md:block md:col-span-3 rounded-[1.8rem] border border-base-300 bg-base-100 p-6 shadow-lg transition duration-300 hover:-translate-y-1 hover:border-primary/40 hover:shadow-2xl">
            <div class="text-xs font-semibold uppercase tracking-[0.22em] text-primary">Sobre</div>
            <h2 class="mt-3 text-xl font-bold text-base-content">Entenda o espírito do encontro</h2>
            <p class="mt-3 text-sm text-base-content/72">
                Conheça mais sobre a proposta, a organização e o contexto do ERAC.
            </p>
        </a>

        <div class="hidden md:block md:col-span-5 rounded-[1.8rem] border border-base-300 bg-gradient-to-br from-base-100 to-base-200 p-6 shadow-lg">
            <div class="text-xs font-semibold uppercase tracking-[0.22em] text-primary">Mensagem</div>
            <h2 class="mt-3 text-2xl font-bold text-base-content">Um encontro para viver a maçonaria com profundidade</h2>
            <p class="mt-3 text-sm text-base-content/72">
                Mais do que uma agenda, o ERAC é uma oportunidade de estreitar laços, aprimorar a formação e fortalecer
                o senso de pertencimento entre Aprendizes e Companheiros.
            </p>
        </div>

        <div class="hidden md:block md:col-span-4 rounded-[1.8rem] border border-base-300 bg-base-100 p-6 shadow-lg">
            <div class="text-xs font-semibold uppercase tracking-[0.22em] text-primary">Destaques</div>
            <ul class="mt-4 space-y-3 text-sm text-base-content/75">
                <li class="flex items-start gap-3">
                    <span class="mt-1 h-2.5 w-2.5 rounded-full bg-primary"></span>
                    Integração entre irmãos da região
                </li>
                <li class="flex items-start gap-3">
                    <span class="mt-1 h-2.5 w-2.5 rounded-full bg-primary"></span>
                    Programação dedicada à formação
                </li>
                <li class="flex items-start gap-3">
                    <span class="mt-1 h-2.5 w-2.5 rounded-full bg-primary"></span>
                    Ambiente preparado para convivência e fraternidade
                </li>
            </ul>
        </div>
    </section>
</div>
@endsection
