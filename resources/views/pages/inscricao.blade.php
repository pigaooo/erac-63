@extends('layouts.app')

@section('content')
@php
    $inscricaoResumo ??= [];
    $inscricoesAbertas = $inscricaoResumo['inscricoes_abertas'] ?? false;
    $lotesVisiveis = $inscricaoResumo['lotes_visiveis'] ?? [];
    $loteAtual = $inscricaoResumo['lote_atual'] ?? null;
    $mensagemStatus = $inscricaoResumo['mensagem_status'] ?? '';
    $encerramentoOnline = $inscricaoResumo['encerramento_online'] ?? null;
    $timezoneInscricoes = $inscricaoResumo['timezone'] ?? config('app.timezone');
@endphp

<div class="max-w-7xl mx-auto px-4 sm:px-6 pt-8 md:pt-10">
    @include('componentes.patrocinadores-carousel', ['patrocinadores' => $patrocinadores ?? collect()])
</div>
<div class="max-w-7xl mx-auto px-4 sm:px-6 py-10 md:py-14 space-y-8">
    <section class="grid gap-4 lg:grid-cols-12">
        <div class="lg:col-span-8 rounded-[2rem] border border-base-300 bg-gradient-to-br from-base-100 via-base-100 to-primary/5 shadow-sm p-6 md:p-8 overflow-hidden relative transition duration-500 hover:-translate-y-1 hover:shadow-2xl scroll-reveal" data-reveal="fadeInUp">
            <div class="absolute -top-16 -right-16 h-48 w-48 rounded-full bg-primary/10 blur-3xl animate-pulse"></div>
            <div class="absolute -bottom-20 -left-10 h-40 w-40 rounded-full bg-secondary/10 blur-3xl animate-pulse"></div>
            <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-primary/40 to-transparent"></div>

            <div class="relative space-y-4">
                <div class="inline-flex items-center gap-2 rounded-full border border-primary/20 bg-primary/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.2em] text-primary">
                    <span class="h-2 w-2 rounded-full bg-primary animate-pulse"></span>
                    Inscrição
                </div>

                <div class="space-y-2">
                    <h1 class="text-3xl md:text-5xl font-black leading-tight scroll-reveal" data-reveal="fadeInUp" data-reveal-delay="80">
                        Credenciamento para o evento
                    </h1>
                    <p class="max-w-2xl text-base md:text-lg text-base-content/70 scroll-reveal" data-reveal="fadeInUp" data-reveal-delay="140">
                        Finalize sua inscrição com poucos passos. Valores claros e pagamento simples via PIX.
                    </p>
                </div>

                @if ($inscricoesAbertas)
                    <div class="flex flex-wrap gap-3 pt-2 scroll-reveal" data-reveal="fadeInUp" data-reveal-delay="200">
                        <a href="#inscricao-individual" class="btn btn-outline rounded-xl px-6 transition duration-300 hover:scale-[1.03] hover:border-primary/50">
                            Inscrição individual
                        </a>
                        <a href="#inscricao-multipla" class="btn btn-ghost rounded-xl px-6 transition duration-300 hover:scale-[1.03]">
                            Inscrição em lote
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <div class="lg:col-span-4 grid gap-4">
            <div class="rounded-[2rem] border {{ $inscricoesAbertas ? 'border-primary/20 bg-primary/10' : 'border-warning/20 bg-warning/10' }} shadow-sm p-6 space-y-3 transition duration-500 hover:-translate-y-1 hover:shadow-xl scroll-reveal" data-reveal="fadeInRight" data-reveal-delay="120">
                <div class="text-xs font-bold uppercase tracking-[0.2em] {{ $inscricoesAbertas ? 'text-primary' : 'text-warning' }}">Prazo</div>
                <div class="text-2xl font-black">
                    {{ $inscricoesAbertas ? 'Inscrições on-line abertas' : 'Inscrições on-line encerradas' }}
                </div>
                <p class="text-sm text-base-content/75">
                    {{ $mensagemStatus }}
                </p>
            </div>

            <div class="rounded-[2rem] border border-base-300 bg-base-100 shadow-sm p-6 space-y-3 transition duration-500 hover:-translate-y-1 hover:shadow-xl scroll-reveal" data-reveal="fadeInRight" data-reveal-delay="200">
                <div class="text-xs font-bold uppercase tracking-[0.2em] text-base-content/60">Check-in</div>
                <div class="text-lg font-bold">Leve sua confirmação no dia</div>
                <p class="text-sm text-base-content/70">
                    A confirmação enviada pela Loja Fonte de Vida agiliza a entrada e a organização no evento.
                </p>
            </div>
        </div>
    </section>

    <div id="inscricao-alert" class="hidden rounded-2xl border border-success/40 bg-success/10 text-success px-4 py-3 text-sm shadow-sm animate__animated animate__fadeIn" role="status"></div>

    <section>
        <div class="rounded-[2rem] border border-base-300 bg-base-100 shadow-sm p-6 md:p-7 transition duration-500 hover:-translate-y-1 hover:shadow-xl scroll-reveal" data-reveal="fadeInUp" data-reveal-delay="120">
            <div class="space-y-5">
                <div class="flex items-center gap-2 text-sm uppercase tracking-wide text-primary font-semibold">
                    <span class="h-2.5 w-2.5 rounded-full bg-primary animate-pulse"></span>
                    Valores de inscrição
                </div>

                @if (count($lotesVisiveis))
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($lotesVisiveis as $lote)
                            @php
                                $isAtual = $loteAtual && $loteAtual['id'] === $lote['id'];
                                $countdown = $lote['countdown'] ?? null;
                            @endphp
                            <div class="rounded-[1.5rem] border p-5 shadow-xs space-y-3 transition duration-300 hover:scale-[1.02] hover:shadow-lg {{ $isAtual ? 'border-primary/40 bg-primary/10 ring-1 ring-primary/30' : 'border-base-300 bg-base-200/60 hover:border-primary/40 hover:bg-base-200/90' }}">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="space-y-2">
                                        <div class="badge badge-{{ $lote['badge'] }} badge-lg">{{ $lote['label'] }}</div>
                                        @if ($isAtual)
                                            <div class="badge badge-outline badge-primary">Lote vigente</div>
                                        @endif
                                    </div>

                                    @if ($countdown)
                                        <div
                                            class="text-right text-[10px] uppercase tracking-[0.18em] text-base-content/45"
                                            data-lote-countdown
                                            data-countdown-expires-at="{{ $countdown['expires_at_iso'] }}"
                                        >
                                            <div class="leading-none">Encerra em</div>
                                            <div class="mt-1 flex items-baseline justify-end gap-1">
                                                <span class="countdown font-semibold text-sm text-base-content/70">
                                                    <span
                                                        data-countdown-value
                                                        style="--value:{{ $countdown['value'] }}; --digits:{{ max(strlen((string) $countdown['value']), 2) }};"
                                                        aria-live="polite"
                                                        aria-label="{{ $countdown['value'] }}"
                                                    ></span>
                                                </span>
                                                <span data-countdown-unit>{{ $countdown['unit'] }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="text-3xl md:text-4xl font-black">{{ $lote['valor'] }}</div>
                                <p class="text-sm text-base-content/70">
                                    De {{ $lote['periodo'] }}. {{ $lote['descricao'] }}
                                </p>
                            </div>
                        @endforeach

                        <div class="rounded-[1.5rem] border border-error/30 bg-error/5 p-5 shadow-xs space-y-3 transition duration-300 hover:scale-[1.02] hover:border-error/50 hover:shadow-lg">
                            <div class="flex items-center justify-between">
                                <div class="badge badge-error badge-lg">Prazo final</div>
                            </div>
                            <div class="text-2xl md:text-3xl font-black">Após {{ $encerramentoOnline?->translatedFormat('d/m') }}</div>
                            <p class="text-sm text-base-content/70">
                                Somente no local do evento. O encerramento é apenas para a inscrição online.
                            </p>
                        </div>
                    </div>
                @else
                    <div class="rounded-[1.5rem] border border-warning/30 bg-warning/10 p-5 text-sm text-base-content/75">
                        Nenhum lote está disponível no momento. {{ $mensagemStatus }}
                    </div>
                @endif
            </div>
        </div>
    </section>

    @if ($inscricoesAbertas)
        <section id="inscricao-individual" class="rounded-[2rem] border border-base-300 bg-base-100 shadow-sm p-6 md:p-7 space-y-5 transition duration-500 hover:shadow-2xl">
            <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-3">
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-2 text-sm uppercase tracking-wide text-primary font-semibold">
                        <span class="h-2.5 w-2.5 rounded-full bg-primary animate-pulse"></span>
                        Inscrição individual
                    </div>
                    <h2 class="text-2xl md:text-3xl font-black">Faça sua inscrição em 3 passos</h2>
                    <p class="text-sm md:text-base text-base-content/70 max-w-3xl">
                        Um fluxo simples para participantes individuais, incluindo inscrições de não irmãos: cadastro, pagamento por PIX e confirmação.
                    </p>
                </div>
            </div>

            <div class="grid gap-4 lg:grid-cols-12">
                <div class="lg:col-span-4 rounded-[1.75rem] border border-base-300 bg-base-200/50 p-6 flex flex-col gap-4 transition duration-300 hover:border-primary/40 hover:bg-base-200/80 hover:shadow-xl">
                    <div class="flex items-center justify-between">
                        <div class="badge badge-primary badge-outline">Passo 1</div>
                        <span class="text-xs uppercase tracking-wide text-base-content/50">Cadastro</span>
                    </div>

                    <div class="space-y-2">
                        <h3 class="text-xl font-bold">Dados pessoais</h3>
                        <p class="text-sm text-base-content/70">
                            Neste bloco também podem ser feitas inscrições de não irmãos, com preenchimento simples dos dados e contatos.
                        </p>
                    </div>

                    <div class="mt-auto pt-2" id="botao_modal_inscricao">
                        <livewire:inscricao-modal />
                    </div>
                </div>

                <div class="lg:col-span-4 rounded-[1.75rem] border border-base-300 bg-base-200/50 p-6 flex flex-col gap-4 transition duration-300 hover:-translate-y-1 hover:border-primary/40 hover:bg-base-200/80 hover:shadow-xl">
                    <div class="flex items-center justify-between">
                        <div class="badge badge-primary badge-outline">Passo 2</div>
                        <span class="text-xs uppercase tracking-wide text-base-content/50">Pagamento</span>
                    </div>

                    <div class="space-y-2">
                        <h3 class="text-xl font-bold">Pagamento via PIX</h3>
                        <p class="text-sm text-base-content/70">
                            Abra o QR/Chave PIX, realize o pagamento e envie o comprovante.
                        </p>
                    </div>

                    <button type="button" class="btn btn-outline btn-primary rounded-xl mt-auto transition duration-300 hover:scale-[1.03]" data-pix-trigger>
                        Ver PIX / QR
                    </button>
                </div>

                <div class="lg:col-span-4 rounded-[1.75rem] border border-base-300 bg-gradient-to-br from-base-100 to-primary/5 p-6 flex flex-col gap-4 transition duration-300 hover:-translate-y-1 hover:border-primary/40 hover:shadow-xl">
                    <div class="flex items-center justify-between">
                        <div class="badge badge-primary badge-outline">Passo 3</div>
                        <span class="text-xs uppercase tracking-wide text-base-content/50">Check-in</span>
                    </div>

                    <div class="space-y-2">
                        <h3 class="text-xl font-bold">Confirmação</h3>
                        <p class="text-sm text-base-content/70">
                            A confirmação do pagamento será via e-mail cadastrado apos o envio do comprovante, para o e-mail do passo 2.
                        </p>
                    </div>

                    <div class="mt-auto rounded-xl bg-base-100/80 border border-base-300 px-4 py-3 text-xs text-base-content/60 transition duration-300 hover:border-primary/40">
                        Dica: mantenha o comprovante e a confirmação salvos no celular.
                    </div>
                </div>
            </div>
        </section>

        <section id="inscricao-multipla" class="rounded-[2rem] border border-base-300 bg-base-100 shadow-sm p-6 md:p-7 space-y-5 transition duration-500 hover:shadow-2xl">
            <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                <div class="space-y-2">
                    <div class="inline-flex items-center gap-2 text-sm uppercase tracking-wide text-primary font-semibold">
                        <span class="h-2.5 w-2.5 rounded-full bg-primary animate-pulse"></span>
                        Inscrição em lote
                    </div>
                    <h2 class="text-2xl md:text-3xl font-black">Cadastre vários participantes com mais agilidade</h2>
                    <p class="text-sm md:text-base text-base-content/70 max-w-3xl">
                        Ideal para inscrições por Loja ou grupo, com envio unificado e conferência organizada.
                    </p>
                </div>

                <div class="w-full md:w-auto md:max-w-xs rounded-[2rem] border border-amber-400/80 bg-gradient-to-br from-amber-200 via-amber-50 to-base-100 shadow-sm px-5 py-4 space-y-2 md:self-start">
                    <div class="inline-flex w-fit items-center rounded-full border border-amber-700/70 bg-amber-300 px-3 py-1 text-[11px] font-black uppercase tracking-[0.22em] text-amber-950">
                        Atenção
                    </div>
                    <div class="text-xl font-black leading-snug text-amber-950">Exclusivo para irmãos</div>
                    <p class="text-sm leading-relaxed text-stone-900">
                        As inscrições em lote deste bloco são exclusivas para irmãos.
                    </p>
                </div>
            </div>

            <div class="grid gap-4 lg:grid-cols-12">
                <div class="lg:col-span-4 rounded-[1.75rem] border border-base-300 bg-base-200/50 p-6 flex flex-col gap-4 transition duration-300 hover:border-primary/40 hover:bg-base-200/80 hover:shadow-xl">
                    <div class="flex items-center justify-between">
                        <div class="badge badge-primary badge-outline">Passo 1</div>
                        <span class="text-xs uppercase tracking-wide text-base-content/50">Lote</span>
                    </div>

                    <div class="space-y-2">
                        <h3 class="text-xl font-bold">Inscrições em lote</h3>
                        <p class="text-sm text-base-content/70">
                            Adicione vários participantes da mesma Loja/Capítulo com nome, contato, CIM e grau.
                        </p>
                    </div>

                    <div class="mt-auto pt-2" id="botao_modal_inscricao_multipla">
                        <livewire:inscricao-multiplos />
                    </div>
                </div>

                <div class="lg:col-span-4 rounded-[1.75rem] border border-base-300 bg-base-200/50 p-6 flex flex-col gap-4 transition duration-300 hover:-translate-y-1 hover:border-primary/40 hover:bg-base-200/80 hover:shadow-xl">
                    <div class="flex items-center justify-between">
                        <div class="badge badge-primary badge-outline">Passo 2</div>
                        <span class="text-xs uppercase tracking-wide text-base-content/50">PIX único</span>
                    </div>

                    <div class="space-y-2">
                        <h3 class="text-xl font-bold">Pagamento único</h3>
                        <p class="text-sm text-base-content/70">
                            Efetue o pagamento total via PIX e envie um único comprovante.
                        </p>
                    </div>

                    <button type="button" class="btn btn-outline btn-primary rounded-xl mt-auto transition duration-300 hover:scale-[1.03]" data-pix-trigger>
                        Ver PIX / QR
                    </button>
                </div>

                <div class="lg:col-span-4 rounded-[1.75rem] border border-base-300 bg-gradient-to-br from-base-100 to-secondary/10 p-6 flex flex-col gap-4 transition duration-300 hover:-translate-y-1 hover:border-primary/40 hover:shadow-xl">
                    <div class="flex items-center justify-between">
                        <div class="badge badge-primary badge-outline">Passo 3</div>
                        <span class="text-xs uppercase tracking-wide text-base-content/50">Validação</span>
                    </div>

                    <div class="space-y-2">
                        <h3 class="text-xl font-bold">Confirmação por inscrito</h3>
                        <p class="text-sm text-base-content/70">
                            Após o envio do comprovante com a nossa confirmação, será enviado um e-mail para cada inscrito individualmente. Portanto é de extrema importância colocar um e-mail válido.
                        </p>
                    </div>

                    <div class="mt-auto rounded-xl bg-base-100/80 border border-base-300 px-4 py-3 text-xs text-base-content/60 transition duration-300 hover:border-primary/40">
                        A inscrição em lote ajuda a centralizar o pagamento e a conferência da Loja.
                    </div>
                </div>
            </div>
        </section>
    @else
        <section class="rounded-[2rem] border border-warning/30 bg-warning/10 shadow-sm p-6 md:p-7 space-y-3">
            <div class="inline-flex items-center gap-2 text-sm uppercase tracking-wide text-warning font-semibold">
                <span class="h-2.5 w-2.5 rounded-full bg-warning"></span>
                Encerramento on-line
            </div>
            <h2 class="text-2xl md:text-3xl font-black">Inscrições on-line encerradas</h2>
            <p class="text-sm md:text-base text-base-content/75">
                Novas inscrições serão feitas somente no local do evento.
            </p>
        </section>
    @endif
</div>

@include('componentes.pix-modal')
@endsection
