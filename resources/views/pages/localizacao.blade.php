@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4 sm:px-6 py-12 space-y-8 animate__animated animate__fadeIn">
    <div class="space-y-2">
        <p class="text-sm font-semibold text-primary uppercase tracking-widest">Localização</p>
        <h1 class="text-3xl font-bold">Como chegar ao templo anfitrião</h1>
        <p class="text-base-content/80">Endereço, mapa e orientações para que Aprendizes e Companheiros cheguem com antecedência e em segurança.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-4 animate__animated animate__fadeInUp">
            <div>
                <div class="text-sm font-semibold">Endereço</div>
                <div class="text-base">Informe o templo anfitrião, cidade e a Loja/Capítulo responsável pela acolhida.</div>
            </div>
            <div>
                <div class="text-sm font-semibold">Como chegar</div>
                <ul class="list-disc list-inside text-base-content/80 space-y-2">
                    <li>Transporte público: linhas ou estações próximas ao templo.</li>
                    <li>Estacionamento e chegada: vagas, acesso e orientação para o credenciamento.</li>
                    <li>Pontos de referência e horário recomendado para ingresso ao Átrio.</li>
                </ul>
            </div>
        </div>
        <div>
            <div class="aspect-video w-full overflow-hidden rounded-2xl border border-base-300 bg-base-100 flex items-center justify-center text-base-content/60 animate__animated animate__fadeInUp" style="--animate-delay: 0.1s;">
                <span class="text-sm">(Mapa do templo ou embed do mapa aqui)</span>
            </div>
        </div>
    </div>
</div>
@endsection
