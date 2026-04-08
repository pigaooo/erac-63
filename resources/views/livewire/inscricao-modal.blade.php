<div class="space-y-4">
    @if ($flashMessage)
        <div class="alert {{ $inscricoesAbertas ? 'alert-success' : 'alert-warning' }} text-sm">{{ $flashMessage }}</div>
    @endif

    @error('inscricoes')
        <div class="alert alert-warning text-sm">{{ $message }}</div>
    @enderror

    <button
        id="botao_modal_inscricao"
        type="button"
        class="btn btn-primary w-full"
        wire:click="openModal"
        @disabled(! $inscricoesAbertas)
    >
        Inscrição individual
    </button>

    <div class="modal {{ $showModal ? 'modal-open' : '' }}" wire:keydown.escape="closeModal">
        <div class="modal-box max-w-2xl space-y-4">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="text-lg font-bold">Credenciamento</h3>
                    <p class="text-sm text-base-content/70">Preencha os dados do participante para o ERAC.</p>
                </div>
                <button class="btn btn-ghost btn-sm" type="button" wire:click="closeModal">X</button>
            </div>

            <form class="space-y-4" wire:submit.prevent="submit">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <label class="form-control w-full md:col-span-2">
                        <div class="label"><span class="label-text">Tipo de visitante</span></div>
                        <select
                            wire:key="grau-{{ $formKey }}"
                            class="select select-bordered"
                            wire:model.live="grau"
                            required
                        >
                            <option value="">Selecione</option>
                            <option value="AM">A∴M∴</option>
                            <option value="CM">C∴M∴</option>
                            <option value="MM">M∴M∴</option>
                            <option value="MI">M∴I∴</option>
                            <option value="OT">Outros</option>
                            <option value="VI">Visitante</option>
                            <option value="CU">Cunhada</option>
                            <option value="SO">Sobrinho</option>
                        </select>
                        @error('grau') <span class="mt-1 text-xs text-error">{{ $message }}</span> @enderror
                    </label>

                    <label class="form-control w-full">
                        <div class="label"><span class="label-text">Nome completo</span></div>
                        <input
                            wire:key="nome-{{ $formKey }}"
                            type="text"
                            class="input input-bordered"
                            placeholder="Nome do participante"
                            wire:model.blur="nome"
                            required
                        >
                        @error('nome') <span class="mt-1 text-xs text-error">{{ $message }}</span> @enderror
                    </label>

                    <label class="form-control w-full">
                        <div class="label"><span class="label-text">E-mail</span></div>
                        <input
                            wire:key="email-{{ $formKey }}"
                            type="email"
                            class="input input-bordered"
                            placeholder="irmao@exemplo.com"
                            wire:model.blur="email"
                            required
                        >
                        @error('email') <span class="mt-1 text-xs text-error">{{ $message }}</span> @enderror
                    </label>

                    <label class="form-control w-full">
                        <div class="label"><span class="label-text">Telefone (celular)</span></div>
                        <input
                            wire:key="telefone-{{ $formKey }}"
                            type="tel"
                            class="input input-bordered"
                            placeholder="(11) 99999-9999"
                            inputmode="tel"
                            wire:model.blur="telefone"
                            x-mask="(99) 99999-9999"
                            required
                        >
                        @error('telefone') <span class="mt-1 text-xs text-error">{{ $message }}</span> @enderror
                    </label>

                    <label class="form-control w-full">
                        <div class="label"><span class="label-text">CPF</span></div>
                        <input
                            wire:key="cpf-{{ $formKey }}"
                            type="text"
                            class="input input-bordered"
                            placeholder="000.000.000-00"
                            inputmode="numeric"
                            wire:model.blur="cpf"
                            x-mask="999.999.999-99"
                            required
                        >
                        @error('cpf') <span class="mt-1 text-xs text-error">{{ $message }}</span> @enderror
                    </label>

                    @if (! in_array($grau, ['OT', 'VI', 'CU', 'SO'], true))
                        <label class="form-control w-full">
                            <div class="label"><span class="label-text">CIM</span></div>
                            <input
                                wire:key="cim-{{ $formKey }}"
                                type="text"
                                class="input input-bordered"
                                placeholder="CIM"
                                wire:model.blur="cim"
                                required
                            >
                            @error('cim') <span class="mt-1 text-xs text-error">{{ $message }}</span> @enderror
                        </label>

                        <label class="form-control w-full md:col-span-2">
                            <div class="label"><span class="label-text">Loja</span></div>
                            @if ($lojas->count())
                                @php
                                    $lojaSelecionadaNome = $lojas->firstWhere('id', $lojaId)?->name ?? '';
                                    $lojaOptions = $lojas->map(fn ($loja) => ['id' => (string) $loja->id, 'name' => $loja->name])->values();
                                @endphp
                                <div
                                    class="dropdown w-full"
                                    x-data="{
                                        open: false,
                                        search: @js($lojaSelecionadaNome),
                                        selectedId: @js($lojaId),
                                        options: @js($lojaOptions),
                                        normalize(value) { return (value || '').toLowerCase().trim() },
                                        get filteredOptions() {
                                            return this.options.filter(option => this.normalize(option.name).includes(this.normalize(this.search)))
                                        },
                                        handleInput() {
                                            this.open = true
                                            const match = this.options.find(option => this.normalize(option.name) === this.normalize(this.search))
                                            this.selectedId = match ? match.id : ''
                                            $wire.set('lojaSearch', this.search)
                                            $wire.set('lojaId', this.selectedId)
                                        },
                                        selectOption(option) {
                                            this.search = option.name
                                            this.selectedId = option.id
                                            this.open = false
                                            $wire.set('lojaSearch', option.name)
                                            $wire.set('lojaId', option.id)
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
                                            placeholder="Pesquisar loja..."
                                            class="input input-bordered w-full pr-10"
                                            x-model="search"
                                            @focus="open = true"
                                            @input="handleInput()"
                                            required
                                        />
                                        <span class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-base-content/45">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.512a.75.75 0 01-1.08 0L5.21 8.27a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </div>

                                    <ul
                                        x-show="open"
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

                                @if ($lojaSearch !== '' && $lojaId === '')
                                    <div class="mt-1 text-xs text-base-content/60">Selecione uma loja válida na lista.</div>
                                @endif
                            @else
                                <div class="text-sm text-base-content/70">Nenhuma Loja cadastrada ainda.</div>
                            @endif
                            @error('lojaId') <span class="mt-1 text-xs text-error">{{ $message }}</span> @enderror
                        </label>
                    @endif
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
