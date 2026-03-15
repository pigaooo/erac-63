<?php

namespace App\Livewire;

use App\Models\Inscrito;
use App\Models\Loja;
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
    public int $formKey = 0;

    public function mount(): void
    {
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
        $this->resetErrorBag();
        $this->flashMessage = null;
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
    }

    public function addToTable(): void
    {
        $this->resetErrorBag();

        $row = $this->currentRow();

        logger()->info('ROW BEFORE VALIDATION', $row);

        $validated = Validator::validate(
            $row,
            $this->rowRules(),
            $this->rowMessages()
        );

        foreach (['email', 'cpf', 'cim'] as $field) {
            if (collect($this->inscritos)->contains(fn ($item) => $item[$field] === $validated[$field])) {
                $this->addError($field, strtoupper($field).' duplicado na lista.');
                return;
            }
        }

        $this->inscritos[] = $validated;
        $this->resetFormFields();
    }

    public function removeRow(int $index): void
    {
        unset($this->inscritos[$index]);
        $this->inscritos = array_values($this->inscritos);
    }

    public function submit(): void
    {
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
                'inscritos.*.cim' => ['required', 'string', 'max:50', 'distinct', 'unique:inscritos,cim'],
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

        $this->flashMessage = 'Inscritos cadastrados com sucesso.';
        $this->dispatch('inscricao-alert', message: 'As Inscrições foram enviadas com sucesso.');

        $this->inscritos = [];
        $this->resetFormFields();
        $this->loja_id = '';
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

    private function rowRules(): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:150'],
            'email' => ['required', 'email', 'max:150'],
            'telefone' => ['required', 'string', 'max:50'],
            'cpf' => ['required', 'string', 'max:20'],
            'cim' => ['required', 'string', 'max:50'],
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
            'grau.required' => 'Selecione o grau maçônico.',
            'loja_id.required' => 'Selecione a Loja/Capítulo.',
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
        ];
    }

    public function render()
    {
        return view('livewire.inscricao-multiplos');
    }
}
