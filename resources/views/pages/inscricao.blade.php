@extends('layouts.app')

@section('content')
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
                        Credenciamento para o Evento
                    </h1>
                    <p class="max-w-2xl text-base md:text-lg text-base-content/70 scroll-reveal" data-reveal="fadeInUp" data-reveal-delay="140">
                        Finalize sua inscrição com poucos passos. Valores claros, pagamento simples via PIX.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3 pt-2 scroll-reveal" data-reveal="fadeInUp" data-reveal-delay="200">
                    <a href="#inscricao-individual" class="btn btn-outline rounded-xl px-6 transition duration-300 hover:scale-[1.03] hover:border-primary/50">
                        Inscrição individual
                    </a>
                    <a href="#inscricao-multipla" class="btn btn-ghost rounded-xl px-6 transition duration-300 hover:scale-[1.03]">
                        Inscrição em lote
                    </a>
                </div>
            </div>
        </div>

        <div class="lg:col-span-4 grid gap-4">
            <div class="rounded-[2rem] border border-primary/20 bg-primary/10 shadow-sm p-6 space-y-3 transition duration-500 hover:-translate-y-1 hover:shadow-xl scroll-reveal" data-reveal="fadeInRight" data-reveal-delay="120">
                <div class="text-xs font-bold uppercase tracking-[0.2em] text-primary">Prazo</div>
                <div class="text-2xl font-black">Inscrições online até DD/MM</div>
                <p class="text-sm text-base-content/75">
                    Após essa data, as inscrições ficam disponíveis apenas no presencial, sujeito à disponibilidade.
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

    <section class="grid gap-4 md:grid-cols-12">
        <div class="md:col-span-7 rounded-[2rem] border border-base-300 bg-base-100 shadow-sm p-6 md:p-7 space-y-5 transition duration-500 hover:-translate-y-1 hover:shadow-xl scroll-reveal" data-reveal="fadeInUp" data-reveal-delay="120">
            <div class="flex items-center gap-2 text-sm uppercase tracking-wide text-primary font-semibold">
                <span class="h-2.5 w-2.5 rounded-full bg-primary animate-pulse"></span>
                Valores de inscrição
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="rounded-[1.5rem] border border-base-300 bg-base-200/60 p-5 shadow-xs space-y-3 transition duration-300 hover:scale-[1.02] hover:border-primary/40 hover:bg-base-200/90 hover:shadow-lg">
                    <div class="flex items-center justify-between">
                        <div class="badge badge-primary badge-lg">Antecipado</div>
                    </div>
                    <div class="text-3xl md:text-4xl font-black">R$ 100,00</div>
                    <p class="text-sm text-base-content/70">
                        Até DD/MM via site. A confirmação é enviada após a validação do pagamento.
                    </p>
                </div>

                <div class="rounded-[1.5rem] border border-base-300 bg-base-200/60 p-5 shadow-xs space-y-3 transition duration-300 hover:scale-[1.02] hover:border-primary/40 hover:bg-base-200/90 hover:shadow-lg">
                    <div class="flex items-center justify-between">
                        <div class="badge badge-secondary badge-lg">No dia</div>
                    </div>
                    <div class="text-3xl md:text-4xl font-black">R$ 120,00</div>
                    <p class="text-sm text-base-content/70">
                        Pagamento presencial por PIX, sujeito à disponibilidade de vagas no evento.
                    </p>
                </div>
            </div>

            <div class="rounded-2xl border border-dashed border-base-300 bg-base-200/40 px-4 py-3 text-xs text-base-content/60 transition duration-300 hover:border-primary/40">
                * Ajuste datas, valores e textos conforme a definição oficial do evento.
            </div>
        </div>

        <div class="md:col-span-5 grid gap-4">
            <div class="rounded-[2rem] border border-base-300 bg-base-100 shadow-sm p-6 space-y-3 transition duration-500 hover:-translate-y-1 hover:shadow-xl scroll-reveal" data-reveal="fadeInUp" data-reveal-delay="180">
                <div class="text-xs font-bold uppercase tracking-[0.2em] text-base-content/60">Pagamento</div>
                <div class="text-xl font-black">PIX rápido e prático</div>
                <p class="text-sm text-base-content/70">
                    Abra o QR Code ou a chave PIX, realize o pagamento e envie o comprovante para validação.
                </p>
                <button type="button" class="btn btn-primary rounded-xl w-full transition duration-300 hover:scale-[1.03] hover:shadow-lg" data-pix-trigger>
                    Abrir PIX / QR
                </button>
            </div>
        </div>
    </section>

    <section id="inscricao-individual" class="rounded-[2rem] border border-base-300 bg-base-100 shadow-sm p-6 md:p-7 space-y-5 transition duration-500 hover:shadow-2xl">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-3">
            <div class="space-y-2">
                <div class="inline-flex items-center gap-2 text-sm uppercase tracking-wide text-primary font-semibold">
                    <span class="h-2.5 w-2.5 rounded-full bg-primary animate-pulse"></span>
                    Inscrição individual
                </div>
                <h2 class="text-2xl md:text-3xl font-black">Faça sua inscrição em 3 passos</h2>
                <p class="text-sm md:text-base text-base-content/70 max-w-3xl">
                    Um fluxo simples para participantes individuais: cadastro, pagamento por PIX e confirmação.
                </p>
            </div>
        </div>

        <div class="grid gap-4 lg:grid-cols-12">
            <div class="lg:col-span-5 rounded-[1.75rem] border border-base-300 bg-base-200/50 p-6 flex flex-col gap-4 transition duration-300 hover:border-primary/40 hover:bg-base-200/80 hover:shadow-xl">
                <div class="flex items-center justify-between">
                    <div class="badge badge-primary badge-outline">Passo 1</div>
                    <span class="text-xs uppercase tracking-wide text-base-content/50">Cadastro</span>
                </div>

                <div class="space-y-2">
                    <h3 class="text-xl font-bold">Dados pessoais</h3>
                    <p class="text-sm text-base-content/70">
                        Preencha nome completo, Loja, grau maçônico e informações de contato.
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
                        Abra o QR/Chave PIX, realize o pagamento e envie o comprovante para
                        <strong>comprovante@fontedevida.com</strong>.
                    </p>
                </div>

                <button type="button" class="btn btn-outline btn-primary rounded-xl mt-auto transition duration-300 hover:scale-[1.03]" data-pix-trigger>
                    Ver PIX / QR
                </button>
            </div>

            <div class="lg:col-span-3 rounded-[1.75rem] border border-base-300 bg-gradient-to-br from-base-100 to-primary/5 p-6 flex flex-col gap-4 transition duration-300 hover:-translate-y-1 hover:border-primary/40 hover:shadow-xl">
                <div class="flex items-center justify-between">
                    <div class="badge badge-primary badge-outline">Passo 3</div>
                    <span class="text-xs uppercase tracking-wide text-base-content/50">Check-in</span>
                </div>

                <div class="space-y-2">
                    <h3 class="text-xl font-bold">Confirmação</h3>
                    <p class="text-sm text-base-content/70">
                        Aguarde a validação da Loja Fonte de Vida e apresente a confirmação no check-in.
                    </p>
                </div>

                <div class="mt-auto rounded-xl bg-base-100/80 border border-base-300 px-4 py-3 text-xs text-base-content/60 transition duration-300 hover:border-primary/40">
                    Dica: mantenha o comprovante e a confirmação salvos no celular.
                </div>
            </div>
        </div>
    </section>

    <section id="inscricao-multipla" class="rounded-[2rem] border border-base-300 bg-base-100 shadow-sm p-6 md:p-7 space-y-5 transition duration-500 hover:shadow-2xl">
        <div class="flex flex-col md:flex-row md:items-end md:justify-between gap-3">
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
        </div>

        <div class="grid gap-4 lg:grid-cols-12">
            <div class="lg:col-span-5 rounded-[1.75rem] border border-base-300 bg-base-200/50 p-6 flex flex-col gap-4 transition duration-300 hover:border-primary/40 hover:bg-base-200/80 hover:shadow-xl">
                <div class="flex items-center justify-between">
                    <div class="badge badge-primary badge-outline">Passo 1</div>
                    <span class="text-xs uppercase tracking-wide text-base-content/50">Lote</span>
                </div>

                <div class="space-y-2">
                    <h3 class="text-xl font-bold">Dados em lote</h3>
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
                        Efetue o pagamento total via PIX e envie um único comprovante para
                        <strong>comprovante@fontedevida.com</strong>.
                    </p>
                </div>

                <button type="button" class="btn btn-outline btn-primary rounded-xl mt-auto transition duration-300 hover:scale-[1.03]" data-pix-trigger>
                    Ver PIX / QR
                </button>
            </div>

            <div class="lg:col-span-3 rounded-[1.75rem] border border-base-300 bg-gradient-to-br from-base-100 to-secondary/10 p-6 flex flex-col gap-4 transition duration-300 hover:-translate-y-1 hover:border-primary/40 hover:shadow-xl">
                <div class="flex items-center justify-between">
                    <div class="badge badge-primary badge-outline">Passo 3</div>
                    <span class="text-xs uppercase tracking-wide text-base-content/50">Validação</span>
                </div>

                <div class="space-y-2">
                    <h3 class="text-xl font-bold">Confirmação por inscrito</h3>
                    <p class="text-sm text-base-content/70">
                        Cada participante será validado nominalmente pela organização após a conferência.
                    </p>
                </div>

                <div class="mt-auto rounded-xl bg-base-100/80 border border-base-300 px-4 py-3 text-xs text-base-content/60 transition duration-300 hover:border-primary/40">
                    A inscrição em lote ajuda a centralizar o pagamento e a conferência da Loja.
                </div>
            </div>
        </div>
    </section>
</div>

@include('componentes.pix-modal')
@endsection
