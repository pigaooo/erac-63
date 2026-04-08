<?php

namespace App\Livewire;

use App\Models\Inscrito;
use App\Models\Loja;
use App\Support\InscricaoCalendar;
use App\Support\InscritoEmailDispatcher;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Livewire\Component;

class InscricaoMultiplos extends Component
{
    public bool $showModal = false;
    public array $inscritos = [];
    public Collection $lojas;
    public ?string $flashMessage = null;

    public string $name = '';
    public string $email = '';
    public string $telefone = '';
    public string $cpf = '';
    public string $cim = '';
    public string $grau = '';
    public string $loja_id = '';
    public string $lojaSearch = '';
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
        $this->flashMessage = null;
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
    }

    public function updatedLojaSearch(string $value): void
    {
        $search = mb_strtolower(trim($value));

        if ($search === '') {
            $this->loja_id = '';
            return;
        }

        $loja = $this->lojas->first(function ($item) use ($search) {
            return mb_strtolower((string) $item->name) === $search;
        });

        $this->loja_id = (string) ($loja?->id ?? '');
    }

    public function addToTable(): void
    {
        if (! $this->ensureInscricoesAbertas()) {
            return;
        }

        $this->resetErrorBag();

        $row = $this->currentRow();

        $validated = Validator::validate(
            $row,
            $this->rowRules(),
            $this->rowMessages()
        );

        foreach (['email', 'cpf', 'cim'] as $field) {
            if (collect($this->inscritos)->contains(fn ($item) => $item[$field] === $validated[$field])) {
                $this->addError($field, strtoupper($field) . ' duplicado na lista.');
                return;
            }
        }

        $annotated = $this->annotateDatabaseStatus($validated);

        if ($annotated['ja_cadastrado']) {
            $message = 'Participante já está cadastrado.';
            $this->flashMessage = $message;
            $this->addError('inscritos', $message);

            return;
        }

        $this->inscritos[] = $annotated;
        $this->resetFormFields();
    }

    public function removeRow(int $index): void
    {
        unset($this->inscritos[$index]);
        $this->inscritos = array_values($this->inscritos);

        if (count($this->inscritos) === 0) {
            $this->loja_id = '';
            $this->lojaSearch = '';
        }
    }

    public function submit(): void
    {
        if (! $this->ensureInscricoesAbertas()) {
            return;
        }

        $this->resetErrorBag();

        if (count($this->inscritos) === 0) {
            $this->addError('inscritos', 'Adicione ao menos um inscrito antes de enviar.');
            return;
        }

        $normalized = collect($this->inscritos)
            ->map(fn ($row) => $this->normalizeRow($row))
            ->toArray();

        $validated = Validator::validate(
            ['inscritos' => $normalized],
            [
                'inscritos' => ['required', 'array', 'min:1'],
                'inscritos.*.name' => ['required', 'string', 'min:3', 'max:150'],
                'inscritos.*.email' => ['required', 'email', 'max:150', 'distinct', 'unique:inscritos,email'],
                'inscritos.*.telefone' => ['required', 'string', 'max:50'],
                'inscritos.*.cpf' => ['required', 'string', 'max:20', 'distinct', 'unique:inscritos,cpf'],
                'inscritos.*.cim' => ['required', 'string', 'max:50', 'regex:/^[0-9]+$/', 'distinct', 'unique:inscritos,cim'],
                'inscritos.*.grau' => ['required', 'in:AM,CM,MM,MI,OT'],
                'inscritos.*.loja_id' => ['required', 'exists:lojas,id'],
            ],
            $this->batchMessages()
        );

        $timestamp = now();

        $payload = collect($validated['inscritos'])
            ->map(function ($row) use ($timestamp) {
                return [
                    'id' => (string) Str::ulid(),
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'telefone' => $row['telefone'],
                    'cpf' => $row['cpf'],
                    'cim' => $row['cim'],
                    'grau' => $row['grau'],
                    'loja_id' => $row['loja_id'],
                    'is_paied' => false,
                    'created_at' => $timestamp,
                    'updated_at' => $timestamp,
                ];
            })
            ->all();

        Inscrito::query()->insert($payload);

        $inscritos = Inscrito::query()
            ->with('loja')
            ->whereIn('id', collect($payload)->pluck('id'))
            ->get();

        app(InscritoEmailDispatcher::class)->dispatchRegistrationConfirmations($inscritos, true);

        $this->flashMessage = 'Inscritos cadastrados com sucesso.';
        $this->dispatch('inscricao-alert', message: 'As inscrições foram enviadas com sucesso.');

        $this->inscritos = [];
        $this->resetFormFields();
        $this->loja_id = '';
        $this->lojaSearch = '';
        $this->showModal = false;
    }

    private function currentRow(): array
    {
        return $this->normalizeRow([
            'name' => $this->name,
            'email' => $this->email,
            'telefone' => $this->telefone,
            'cpf' => $this->cpf,
            'cim' => $this->cim,
            'grau' => $this->grau,
            'loja_id' => $this->loja_id,
        ]);
    }

    private function resetFormFields(): void
    {
        $this->name = '';
        $this->email = '';
        $this->telefone = '';
        $this->cpf = '';
        $this->cim = '';
        $this->grau = '';
        $this->formKey++;
    }

    private function normalizeRow(array $row): array
    {
        return [
            'name' => trim((string) ($row['name'] ?? '')),
            'email' => trim((string) ($row['email'] ?? '')),
            'telefone' => trim((string) ($row['telefone'] ?? '')),
            'cpf' => trim((string) ($row['cpf'] ?? '')),
            'cim' => trim((string) ($row['cim'] ?? '')),
            'grau' => trim((string) ($row['grau'] ?? '')),
            'loja_id' => (string) ($row['loja_id'] ?? ''),
        ];
    }

    private function annotateDatabaseStatus(array $row): array
    {
        $normalized = $this->normalizeRow($row);
        $duplicateMessage = $this->findDatabaseDuplicateMessage($normalized);

        $normalized['ja_cadastrado'] = $duplicateMessage !== null;

        return $normalized;
    }

    private function findDatabaseDuplicateMessage(array $row): ?string
    {
        $existing = Inscrito::query()
            ->where('email', $row['email'])
            ->orWhere('cpf', $row['cpf'])
            ->orWhere('cim', $row['cim'])
            ->first(['email', 'cpf', 'cim']);

        if (! $existing) {
            return null;
        }

        $campos = [];

        if ((string) $existing->email === $row['email']) {
            $campos[] = 'e-mail';
        }

        if ((string) $existing->cpf === $row['cpf']) {
            $campos[] = 'CPF';
        }

        if ((string) $existing->cim === $row['cim']) {
            $campos[] = 'CIM';
        }

        $descricao = implode(', ', $campos);

        return $descricao !== ''
            ? "Já cadastrado no sistema ({$descricao})."
            : 'Já cadastrado no sistema.';
    }

    private function rowRules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'telefone' => ['required', 'string', 'max:50'],
            'cpf' => ['required', 'string', 'max:20'],
            'cim' => ['required', 'string', 'max:50', 'regex:/^[0-9]+$/'],
            'grau' => ['required', 'in:AM,CM,MM,MI,OT'],
            'loja_id' => ['required', 'exists:lojas,id'],
        ];
    }

    private function rowMessages(): array
    {
        return [
            'name.required' => 'Informe o nome completo.',
            'email.required' => 'Informe o e-mail.',
            'email.email' => 'E-mail inválido.',
            'telefone.required' => 'Informe o telefone.',
            'cpf.required' => 'Informe o CPF.',
            'cim.required' => 'Informe o CIM.',
            'cim.regex' => 'CIM deve conter apenas números (sem pontos).',
            'grau.required' => 'Selecione o grau maçônico.',
            'loja_id.required' => 'Selecione a Loja.',
            'loja_id.exists' => 'Loja não encontrada.',
        ];
    }

    private function batchMessages(): array
    {
        return [
            'inscritos.required' => 'Adicione ao menos um inscrito.',
            'inscritos.*.telefone.required' => 'Informe o telefone.',
            'inscritos.*.email.unique' => 'E-mail já cadastrado.',
            'inscritos.*.email.distinct' => 'E-mail duplicado na lista.',
            'inscritos.*.cpf.unique' => 'CPF já cadastrado.',
            'inscritos.*.cpf.distinct' => 'CPF duplicado na lista.',
            'inscritos.*.cim.unique' => 'CIM já cadastrado.',
            'inscritos.*.cim.distinct' => 'CIM duplicado na lista.',
            'inscritos.*.cim.regex' => 'CIM deve conter apenas números (sem pontos).',
        ];
    }

    public function render()
    {
        return view('livewire.inscricao-multiplos');
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
