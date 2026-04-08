<?php

namespace App\Livewire;

use App\Models\Inscrito;
use App\Models\Loja;
use App\Support\InscricaoCalendar;
use App\Support\InscritoEmailDispatcher;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;

class InscricaoModal extends Component
{
    private const TIPOS_ESPECIAIS = ['OT', 'VI', 'CU', 'SO'];

    public bool $showModal = false;
    public string $nome = '';
    public string $email = '';
    public string $telefone = '';
    public string $cpf = '';
    public string $cim = '';
    public string $grau = '';
    public string $lojaId = '';
    public string $lojaSearch = '';
    public $lojas;
    public ?string $flashMessage = null;
    public int $formKey = 0;
    public bool $inscricoesAbertas = false;
    public string $mensagemStatus = '';

    public function mount(): void
    {
        $this->syncInscricoesStatus();
        $this->loadLojas();
    }

    public function loadLojas(): void
    {
        $this->lojas = Schema::hasTable('lojas')
            ? Loja::query()->select('id', 'name')->orderBy('name')->get()
            : collect();
    }

    public function openModal(): void
    {
        if (! $this->ensureInscricoesAbertas()) {
            return;
        }

        $this->resetErrorBag();
        $this->resetValidation();
        $this->resetFormFields();
        $this->flashMessage = null;
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->resetErrorBag();
        $this->resetValidation();
        $this->resetFormFields();
        $this->showModal = false;
    }

    public function updatedGrau(string $value): void
    {
        if ($this->isTipoEspecial($value)) {
            $this->cim = '';
            $this->lojaId = $this->visitanteLojaId();
            $this->lojaSearch = $this->lojas->firstWhere('id', $this->lojaId)?->name ?? '';
            $this->resetValidation(['cim', 'lojaId']);

            return;
        }

        if ($this->lojaId === $this->visitanteLojaId()) {
            $this->lojaId = '';
            $this->lojaSearch = '';
        }

        if ($this->lojaId !== '' && ! $this->lojas->contains('id', $this->lojaId)) {
            $this->lojaId = '';
        }
    }

    public function updatedLojaSearch(string $value): void
    {
        $search = mb_strtolower(trim($value));

        if ($search === '') {
            $this->lojaId = '';
            return;
        }

        $loja = $this->lojas->first(function ($item) use ($search) {
            return mb_strtolower((string) $item->name) === $search;
        });

        $this->lojaId = (string) ($loja?->id ?? '');
    }

    public function submit(): void
    {
        if (! $this->ensureInscricoesAbertas()) {
            return;
        }

        $this->cpf = trim((string) $this->cpf);
        $this->telefone = trim((string) $this->telefone);
        $this->cim = trim((string) $this->cim);

        $isTipoEspecial = $this->isTipoEspecial();

        if ($isTipoEspecial && $this->lojaId === '') {
            $this->lojaId = $this->visitanteLojaId();
        }

        $validated = $this->validate([
            'grau' => ['required', 'in:AM,CM,MM,MI,OT,VI,CU,SO'],
            'nome' => ['required', 'string', 'min:3', 'max:150'],
            'email' => ['required', 'email', 'max:150', 'unique:inscritos,email'],
            'telefone' => ['required', 'string', 'max:50'],
            'cpf' => ['required', 'string', 'max:20', 'unique:inscritos,cpf'],
            'cim' => $isTipoEspecial
                ? ['nullable', 'string', 'max:50']
                : ['required', 'string', 'max:50', 'unique:inscritos,cim'],
            'lojaId' => ['required', 'exists:lojas,id'],
        ], [
            'grau.required' => 'Selecione o tipo de visitante.',
            'grau.in' => 'Selecione um tipo de visitante válido.',
            'nome.required' => 'Informe o nome completo.',
            'email.required' => 'Informe o e-mail.',
            'email.email' => 'E-mail inválido.',
            'email.unique' => 'E-mail já cadastrado.',
            'telefone.required' => 'Informe o telefone.',
            'cpf.required' => 'Informe o CPF.',
            'cpf.unique' => 'CPF já cadastrado.',
            'cim.required' => 'Informe o CIM.',
            'cim.unique' => 'CIM já cadastrado.',
            'lojaId.required' => 'Selecione a Loja.',
            'lojaId.exists' => 'Loja não encontrada.',
        ]);

        $cim = $isTipoEspecial ? $validated['cpf'] : $validated['cim'];

        $inscrito = Inscrito::query()->create([
            'name' => $validated['nome'],
            'email' => $validated['email'],
            'telefone' => $validated['telefone'],
            'cpf' => $validated['cpf'],
            'cim' => $cim,
            'grau' => $validated['grau'],
            'loja_id' => $validated['lojaId'],
            'is_paied' => false,
        ]);

        app(InscritoEmailDispatcher::class)->dispatchRegistrationConfirmation($inscrito);

        $this->flashMessage = 'Inscrição enviada com sucesso.';
        $this->dispatch('inscricao-alert', message: 'A inscrição individual foi enviada com sucesso.');

        $this->resetErrorBag();
        $this->resetValidation();
        $this->resetFormFields();
        $this->showModal = false;
    }

    private function resetFormFields(): void
    {
        $this->reset(['nome', 'email', 'telefone', 'cpf', 'cim', 'grau', 'lojaId', 'lojaSearch']);
        $this->formKey++;
    }

    private function visitanteLojaId(): string
    {
        $lojaVisitante = $this->lojas->first(function ($loja) {
            return mb_strtolower((string) $loja->name) === 'visitante';
        });

        return (string) ($lojaVisitante?->id ?? $this->lojas->first()?->id ?? '');
    }

    private function isTipoEspecial(?string $value = null): bool
    {
        return in_array($value ?? $this->grau, self::TIPOS_ESPECIAIS, true);
    }

    public function render()
    {
        return view('livewire.inscricao-modal');
    }

    private function syncInscricoesStatus(): void
    {
        $calendar = app(InscricaoCalendar::class);
        $this->inscricoesAbertas = $calendar->inscricoesAbertas();
        $this->mensagemStatus = $calendar->mensagemStatus();
    }

    private function ensureInscricoesAbertas(): bool
    {
        $this->syncInscricoesStatus();

        if ($this->inscricoesAbertas) {
            return true;
        }

        $this->flashMessage = $this->mensagemStatus;
        $this->addError('inscricoes', $this->mensagemStatus);
        $this->dispatch('inscricao-alert', message: $this->mensagemStatus);
        $this->showModal = false;

        return false;
    }
}
