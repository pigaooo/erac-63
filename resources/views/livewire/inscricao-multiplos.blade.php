<div class="space-y-4">
    @if ($flashMessage)
        <div class="alert {{ $inscricoesAbertas ? 'alert-success' : 'alert-warning' }} text-sm">
            {{ $flashMessage }}
        </div>
    @endif

    @error('inscricoes')
        <div class="alert alert-warning text-sm">{{ $message }}</div>
    @enderror

    <button id="botao_modal_inscricao_multipla" class="btn btn-primary w-full" wire:click="openModal" type="button" @disabled(! $inscricoesAbertas)>
        Inscrever múltiplos
    </button>

    <div class="modal {{ $showModal ? 'modal-open' : '' }}" wire:keydown.escape="closeModal">
        <div class="modal-box max-w-5xl space-y-4">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-lg font-bold">Inscrição múltipla</h3>
                    <p class="text-sm text-base-content/70">
                        Escolha a Loja para inserir vários participantes. Após adicionar o primeiro irmão à tabela, os próximos cadastros deste lote poderão ser feitos somente para irmãos da mesma Loja.
                    </p>
                </div>

                <button class="btn btn-ghost btn-sm" type="button" wire:click="closeModal">
                    X
                </button>
            </div>

            <form class="space-y-4" wire:submit.prevent="addToTable">
                <label class="form-control w-full">
                    <div class="label">
                        <span class="label-text font-semibold">Loja do lote</span>
                    </div>

                    @if ($lojas->count())
                        @php
                            $lojaSelecionadaNome = $lojas->firstWhere('id', $loja_id)?->name ?? '';
                            $lojaOptions = $lojas->map(fn ($loja) => ['id' => (string) $loja->id, 'name' => $loja->name])->values();
                        @endphp
                        <div
                            class="dropdown w-full"
                            x-data="{
                                open: false,
                                search: @js($lojaSelecionadaNome),
                                selectedId: @js($loja_id),
                                disabled: @js(count($inscritos) > 0),
                                options: @js($lojaOptions),
                                normalize(value) { return (value || '').toLowerCase().trim() },
                                get filteredOptions() {
                                    return this.options.filter(option => this.normalize(option.name).includes(this.normalize(this.search)))
                                },
                                handleInput() {
                                    if (this.disabled) return
                                    this.open = true
                                    const match = this.options.find(option => this.normalize(option.name) === this.normalize(this.search))
                                    this.selectedId = match ? match.id : ''
                                    $wire.set('lojaSearch', this.search)
                                    $wire.set('loja_id', this.selectedId)
                                },
                                selectOption(option) {
                                    if (this.disabled) return
                                    this.search = option.name
                                    this.selectedId = option.id
                                    this.open = false
                                    $wire.set('lojaSearch', option.name)
                                    $wire.set('loja_id', option.id)
                                },
                                closeDropdown() {
                                    this.open = false
                                }
                            }"
                            @click.outside="closeDropdown()"
                        >
                            <div class="relative">
                                <input
                                    type="text"
                                    class="input input-bordered w-full pr-10"
                                    placeholder="Pesquisar loja..."
                                    x-model="search"
                                    @focus="if (!disabled) open = true"
                                    @input="handleInput()"
                                    :disabled="disabled"
                                >
                                <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-base-content/45">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.512a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </div>

                            <ul
                                x-show="open && !disabled"
                                x-transition.opacity.duration.120ms
                                class="dropdown-content z-[70] mt-2 w-full rounded-box border border-base-300 bg-base-100 p-2 shadow-xl max-h-60 overflow-y-auto"
                            >
                                <template x-for="option in filteredOptions" :key="option.id">
                                    <li class="list-none">
                                        <button
                                            type="button"
                                            class="btn btn-ghost btn-sm justify-start w-full normal-case font-medium"
                                            @click="selectOption(option)"
                                            x-text="option.name"
                                        ></button>
                                    </li>
                                </template>
                                <li x-show="filteredOptions.length === 0" class="list-none px-3 py-2 text-sm text-base-content/60">
                                    Nenhuma loja encontrada.
                                </li>
                            </ul>
                        </div>

                        @if ($lojaSearch !== '' && $loja_id === '')
                            <div class="mt-1 text-xs text-base-content/60">Selecione uma loja válida na lista.</div>
                        @endif
                    @else
                        <div class="text-sm text-base-content/70">
                            Nenhuma Loja cadastrada ainda.
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
                            placeholder="Nome do participante"
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

                <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div class="alert alert-info border border-info/20 shadow-sm text-sm">
                        <span>Preencha os dados acima e clique em "Adicionar à tabela" para listar os inscritos antes de enviar.</span>
                    </div>

                    <button
                        type="submit"
                        class="btn btn-primary btn-wide shadow-lg"
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
                                            X
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
