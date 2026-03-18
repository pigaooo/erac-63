<div class="space-y-4">
    @if ($flashMessage)
        <div class="alert alert-success text-sm">
            {{ $flashMessage }}
        </div>
    @endif

    <button id="botao_modal_inscricao_multipla" class="btn btn-primary w-full" wire:click="openModal" type="button">
        Inscrever múltiplos
    </button>

    <div class="modal {{ $showModal ? 'modal-open' : '' }}" wire:keydown.escape="closeModal">
        <div class="modal-box max-w-5xl space-y-4">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-lg font-bold">Inscrição múltipla</h3>
                    <p class="text-sm text-base-content/70">
                        Escolha a Loja para inserir vários participantes, preencha os dados e adicione à tabela.
                    </p>
                </div>

                <button class="btn btn-ghost btn-sm" type="button" wire:click="closeModal">
                    x
                </button>
            </div>

            <form class="space-y-4" wire:submit.prevent="addToTable">
                <label class="form-control w-full">
                    <div class="label">
                        <span class="label-text font-semibold">Loja / Capítulo do lote</span>
                    </div>

                    @if ($lojas->count())
                        <select
                            class="select select-bordered w-full"
                            wire:model.defer="loja_id"
                            @if (count($inscritos) > 0) disabled @endif
                        >
                            <option value="">Selecione</option>
                            @foreach ($lojas as $loja)
                                <option value="{{ $loja->id }}">{{ $loja->name }}</option>
                            @endforeach
                        </select>
                    @else
                        <div class="text-sm text-base-content/70">
                            Nenhuma Loja/Capítulo cadastrada ainda.
                        </div>
                    @endif

                    @error('loja_id')
                        <span class="mt-1 text-xs text-error">{{ $message }}</span>
                    @enderror

                    @if (count($inscritos) > 0)
                        @php
                            $lojaSelecionada = $lojas->firstWhere('id', $loja_id);
                        @endphp
                        <div class="mt-1 text-xs text-base-content/60">
                            Loja vinculada a este lote:
                            <strong>{{ $lojaSelecionada->name ?? '-' }}</strong>
                        </div>
                    @endif
                </label>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text">Nome completo</span>
                        </div>
                        <input
                            wire:key="name-{{ $formKey }}"
                            type="text"
                            class="input input-bordered"
                            placeholder="Irmão / Aprendiz / Companheiro"
                            wire:model.defer="name"
                        >
                        @error('name')
                            <span class="mt-1 text-xs text-error">{{ $message }}</span>
                        @enderror
                    </label>

                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text">E-mail</span>
                        </div>
                        <input
                            wire:key="email-{{ $formKey }}"
                            type="email"
                            class="input input-bordered"
                            placeholder="irmao@exemplo.com"
                            wire:model.defer="email"
                        >
                        @error('email')
                            <span class="mt-1 text-xs text-error">{{ $message }}</span>
                        @enderror
                    </label>

                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text">Telefone (celular)</span>
                        </div>
                        <input
                            wire:key="telefone-{{ $formKey }}"
                            class="input input-bordered"
                            type="tel"
                            inputmode="tel"
                            wire:model.blur="telefone"
                            x-mask="(99) 99999-9999"
                            placeholder="(11) 99999-9999"
                        >
                        @error('telefone')
                            <span class="mt-1 text-xs text-error">{{ $message }}</span>
                        @enderror
                    </label>

                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text">CPF</span>
                        </div>
                        <input
                            wire:key="cpf-{{ $formKey }}"
                            type="text"
                            class="input input-bordered"
                            placeholder="000.000.000-00"
                            x-mask="999.999.999-99"
                            wire:model.blur="cpf"
                        >
                        @error('cpf')
                            <span class="mt-1 text-xs text-error">{{ $message }}</span>
                        @enderror
                    </label>

                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text">CIM</span>
                        </div>
                        <input
                            wire:key="cim-{{ $formKey }}"
                            type="text"
                            class="input input-bordered"
                            placeholder="CIM"
                            wire:model.defer="cim"
                        >
                        @error('cim')
                            <span class="mt-1 text-xs text-error">{{ $message }}</span>
                        @enderror
                    </label>

                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text">Grau maçônico</span>
                        </div>
                        <select
                            wire:key="grau-{{ $formKey }}"
                            class="select select-bordered"
                            wire:model.defer="grau"
                        >
                            <option value="">Selecione</option>
                            <option value="AM">A∴M∴</option>
                            <option value="CM">C∴M∴</option>
                            <option value="MM">M∴M∴</option>
                            <option value="MI">M∴I∴</option>
                            <option value="OT">Outros</option>
                        </select>
                        @error('grau')
                            <span class="mt-1 text-xs text-error">{{ $message }}</span>
                        @enderror
                    </label>
                </div>

                <div class="flex items-center justify-between gap-4">
                    <div class="text-sm text-base-content/70">
                        Preencha os dados acima e clique em "Adicionar à tabela" para listar os inscritos antes de enviar.
                    </div>

                    <button
                        type="submit"
                        class="btn btn-outline"
                        wire:loading.attr="disabled"
                        wire:target="addToTable"
                    >
                        <span wire:loading.remove wire:target="addToTable">+ Adicionar à tabela</span>
                        <span wire:loading wire:target="addToTable">Adicionando...</span>
                    </button>
                </div>
            </form>

            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <div class="text-sm font-semibold">
                        Inscritos adicionados ({{ count($inscritos) }})
                    </div>

                    @error('inscritos')
                        <span class="text-xs text-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="overflow-x-auto">
                    <table class="table table-sm">
                        <thead>
                            <tr class="text-xs uppercase text-base-content/70">
                                <th>Nome</th>
                                <th>E-mail</th>
                                <th>Telefone</th>
                                <th>CPF</th>
                                <th>CIM</th>
                                <th>Grau</th>
                                <th>Loja</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($inscritos as $index => $inscrito)
                                <tr>
                                    <td>{{ $inscrito['name'] }}</td>
                                    <td>{{ $inscrito['email'] }}</td>
                                    <td>{{ $inscrito['telefone'] }}</td>
                                    <td>{{ $inscrito['cpf'] }}</td>
                                    <td>{{ $inscrito['cim'] }}</td>
                                    <td>{{ $inscrito['grau'] }}</td>
                                    <td>
                                        @php
                                            $lojaTabela = $lojas->firstWhere('id', $inscrito['loja_id']);
                                        @endphp
                                        {{ $lojaTabela->name ?? '-' }}
                                    </td>
                                    <td class="text-right">
                                        <button
                                            type="button"
                                            class="btn btn-ghost btn-sm"
                                            wire:click="removeRow({{ $index }})"
                                        >
                                            x
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center text-sm text-base-content/70">
                                        Nenhum inscrito adicionado ainda.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal-action">
                <button type="button" class="btn btn-ghost" wire:click="closeModal">
                    Cancelar
                </button>

                <button
                    type="button"
                    class="btn btn-primary"
                    wire:click="submit"
                    wire:loading.attr="disabled"
                    wire:target="submit"
                    @if (count($inscritos) === 0) disabled @endif
                >
                    <span wire:loading.remove wire:target="submit">Enviar em lote</span>
                    <span wire:loading wire:target="submit">Enviando...</span>
                </button>
            </div>
        </div>

        <div class="modal-backdrop" wire:click="closeModal"></div>
    </div>
</div>