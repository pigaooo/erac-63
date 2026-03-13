<div class="space-y-4">
    @if ($flashMessage)
        <div class="alert alert-success text-sm">{{ $flashMessage }}</div>
    @endif

    <button
        id="botao_modal_inscricao"
        type="button"
        class="btn btn-primary w-full"
        wire:click="openModal"
    >
        Inscrição individual
    </button>

    <div class="modal {{ $showModal ? 'modal-open' : '' }}" wire:keydown.escape="closeModal">
        <div class="modal-box max-w-2xl space-y-4">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="font-bold text-lg">Credenciamento</h3>
                    <p class="text-sm text-base-content/70">Preencha os dados do irmão para o ERAC.</p>
                </div>
                <button class="btn btn-ghost btn-sm" type="button" wire:click="closeModal">×</button>
            </div>

            <form class="space-y-4" wire:submit.prevent="submit">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <label class="form-control w-full">
                        <div class="label"><span class="label-text">Nome completo</span></div>
                        <input
                            wire:key="nome-{{ $formKey }}"
                            type="text"
                            class="input input-bordered"
                            placeholder="Irmão / Aprendiz / Companheiro"
                            wire:model.defer="nome"
                            required
                        >
                        @error('nome') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </label>

                    <label class="form-control w-full">
                        <div class="label"><span class="label-text">E-mail</span></div>
                        <input
                            wire:key="email-{{ $formKey }}"
                            type="email"
                            class="input input-bordered"
                            placeholder="irmao@exemplo.com"
                            wire:model.defer="email"
                            required
                        >
                        @error('email') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </label>

                    <label class="form-control w-full" x-data>
                        <div class="label"><span class="label-text">Telefone (celular)</span></div>
                        <input
                            wire:key="telefone-{{ $formKey }}"
                            type="tel"
                            class="input input-bordered"
                            placeholder="(11) 99999-9999"
                            inputmode="tel"
                            wire:model.defer="telefone"
                            x-mask="(99) 99999-9999"
                            required
                        >
                        @error('telefone') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </label>

                    <label class="form-control w-full" x-data>
                        <div class="label"><span class="label-text">CPF</span></div>
                        <input
                            wire:key="cpf-{{ $formKey }}"
                            type="text"
                            class="input input-bordered"
                            placeholder="000.000.000-00"
                            inputmode="numeric"
                            wire:model.defer="cpf"
                            x-mask="999.999.999-99"
                            required
                        >
                        @error('cpf') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </label>

                    <label class="form-control w-full">
                        <div class="label"><span class="label-text">Grau maçônico</span></div>
                        <select
                            wire:key="grau-{{ $formKey }}"
                            class="select select-bordered"
                            wire:model.defer="grau"
                            required
                        >
                            <option value="">Selecione</option>
                            <option value="AM">A∴M∴</option>
                            <option value="CM">C∴M∴</option>
                            <option value="MM">M∴M∴</option>
                            <option value="MI">M∴I∴</option>
                        </select>
                        @error('grau') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </label>

                    <label class="form-control w-full">
                        <div class="label"><span class="label-text">Loja / Capítulo</span></div>
                        @if ($lojas->count())
                            <select
                                wire:key="loja-{{ $formKey }}"
                                class="select select-bordered"
                                wire:model.defer="lojaId"
                                required
                            >
                                <option value="">Selecione</option>
                                @foreach ($lojas as $loja)
                                    <option value="{{ $loja->id }}">{{ $loja->name }}</option>
                                @endforeach
                            </select>
                        @else
                            <div class="text-sm text-base-content/70">Nenhuma Loja/Capítulo cadastrada ainda.</div>
                        @endif
                        @error('lojaId') <span class="text-error text-xs mt-1">{{ $message }}</span> @enderror
                    </label>
                </div>

                <div class="modal-action">
                    <button type="button" class="btn btn-ghost" wire:click="closeModal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="submit">
                        <span wire:loading.remove wire:target="submit">Enviar</span>
                        <span wire:loading wire:target="submit">Enviando...</span>
                    </button>
                </div>
            </form>
        </div>
        <div class="modal-backdrop" wire:click="closeModal"></div>
    </div>
</div>
