@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 py-12 space-y-10">
    @include('componentes.patrocinadores-carousel', ['patrocinadores' => $patrocinadores ?? collect()])

    <section class="relative overflow-hidden rounded-3xl border border-base-300 bg-base-100/60 shadow-xl animate__animated animate__fadeIn">
        <div class="absolute inset-0 bg-cover bg-center" style="background-image: url('/images/foto-de-eventos.jpg');"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-base-200/95 via-base-200/90 to-base-200/75"></div>
        <div class="relative">
            {{-- Mobile: cards rápidos com ícones e texto intuitivo --}}
            <div class="md:hidden p-6 space-y-4 animate__animated animate__fadeInUp animate__faster">
                <div class="grid grid-cols-1 gap-3">
                    <a href="{{ route('localizacao') }}" class="flex items-center gap-4 rounded-2xl border border-base-300 bg-base-100/90 p-4 shadow-sm">
                        <div class="h-12 w-12 rounded-xl bg-primary/10 text-primary flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 12a4 4 0 100-8 4 4 0 000 8z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 22s7-4.35 7-10a7 7 0 10-14 0c0 5.65 7 10 7 10z"/></svg>
                        </div>
                        <div>
                            <div class="font-semibold">Localização</div>
                            <div class="text-sm text-base-content/70">Como chegar e mapa do evento.</div>
                        </div>
                    </a>

                    <a href="{{ route('programacao') }}" class="flex items-center gap-4 rounded-2xl border border-base-300 bg-base-100/90 p-4 shadow-sm">
                        <div class="h-12 w-12 rounded-xl bg-secondary/10 text-secondary flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6.75 3.75v2.5m10.5-2.5v2.5m-12 3h13.5m-12 4h5m-5 4h5m-6.5-11.5h13a1.5 1.5 0 011.5 1.5v11a1.5 1.5 0 01-1.5 1.5h-13a1.5 1.5 0 01-1.5-1.5v-11a1.5 1.5 0 011.5-1.5z"/></svg>
                        </div>
                        <div>
                            <div class="font-semibold">Programação</div>
                            <div class="text-sm text-base-content/70">Agenda completa e horários.</div>
                        </div>
                    </a>

                    <a href="{{ route('inscricao') }}" class="flex items-center gap-4 rounded-2xl border border-base-300 bg-base-100/90 p-4 shadow-sm">
                        <div class="h-12 w-12 rounded-xl bg-accent/10 text-accent flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16.5 4.5l-9 9m0 0V7.5m0 6L13.5 18"/></svg>
                        </div>
                        <div>
                            <div class="font-semibold">Inscrição</div>
                            <div class="text-sm text-base-content/70">Garanta sua vaga no encontro.</div>
                        </div>
                    </a>

                    <a href="{{ route('sobre') }}" class="flex items-center gap-4 rounded-2xl border border-base-300 bg-base-100/90 p-4 shadow-sm">
                        <div class="h-12 w-12 rounded-xl bg-info/10 text-info flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18v-7.5"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 21a9 9 0 100-18 9 9 0 000 18z"/></svg>
                        </div>
                        <div>
                            <div class="font-semibold">Sobre</div>
                            <div class="text-sm text-base-content/70">Conheça o evento e quem organiza.</div>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Desktop: hero + destaques das páginas --}}
            <div id="hero-inicial-home" class="hidden md:grid grid-cols-12 gap-10 p-12 lg:p-16 items-center">
                <div class="col-span-7 space-y-4 animate__animated animate__fadeInLeft">
                    <h1 class="text-4xl lg:text-5xl font-bold leading-tight text-center">Bem-vindos ao 63ª E.R.A.C.</h1>
                    <p class="text-lg text-base-content/80 max-w-2xl">Encontro regional de aprendizes e companheiros da noma Região pertecente ao Grande Oriente de São Paulo(GOSP). Participe deste grande evento dedicado ao aperfeiçoamento acadêmico dos aprendizes e companheiros e na fraternidade entre todos II∴.</p>
                    <div class="flex items-center gap-3">
                        <a href="{{ route('inscricao') }}" class="btn btn-primary">Inscreva-se agora</a>
                        <a href="{{ route('programacao') }}" class="btn btn-outline">Ver programação</a>
                    </div>
                </div>
                <div id="navegue_home" class="col-span-5 animate__animated animate__fadeInRight">
                    <div class="rounded-2xl bg-base-100/90 border border-base-300 shadow-lg p-6 space-y-4">
                        <div class="text-sm font-semibold text-primary">Navegue</div>
                        <div class="space-y-3 text-base-content/80">
                            <a href="{{ route('localizacao') }}" class="flex items-start gap-3 rounded-lg p-2 -mx-2 hover:bg-base-200 transition">
                                <span class="mt-1 h-2 w-2 rounded-full bg-primary"></span>
                                <div>
                                    <div class="font-semibold">Localização</div>
                                    <div class="text-sm">Local onde será realizado todas as atividades.</div>
                                </div>
                            </a>
                            <a href="{{ route('programacao') }}" class="flex items-start gap-3 rounded-lg p-2 -mx-2 hover:bg-base-200 transition">
                                <span class="mt-1 h-2 w-2 rounded-full bg-primary"></span>
                                <div>
                                    <div class="font-semibold">Programação</div>
                                    <div class="text-sm">Trabalhos ritualísticos, instruções e momentos de fraternidade.</div>
                                </div>
                            </a>
                            <a href="{{ route('inscricao') }}" class="flex items-start gap-3 rounded-lg p-2 -mx-2 hover:bg-base-200 transition">
                                <span class="mt-1 h-2 w-2 rounded-full bg-primary"></span>
                                <div>
                                    <div class="font-semibold">Inscrição</div>
                                    <div class="text-sm">Inscrição para o evento.</div>
                                </div>
                            </a>
                            <a href="{{ route('sobre') }}" class="flex items-start gap-3 rounded-lg p-2 -mx-2 hover:bg-base-200 transition">
                                <span class="mt-1 h-2 w-2 rounded-full bg-primary"></span>
                                <div>
                                    <div class="font-semibold">Sobre</div>
                                    <div class="text-sm">Conheça um pouco sobre nosso templo.</div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
