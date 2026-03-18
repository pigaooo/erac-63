<?php

namespace App\Livewire;

use App\Models\Inscrito;
use App\Models\Loja;
use Illuminate\Support\Facades\Schema;
use Livewire\Component;

class InscricaoModal extends Component
{
    public bool $showModal = false;
    public string $nome = '';
    public string $email = '';
    public string $telefone = '';
    public string $cpf = '';
    public string $grau = '';
    public string $lojaId = '';
    public $lojas;
    public ?string $flashMessage = null;
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

    public function submit(): void
    {
        $this->cpf = trim((string) $this->cpf);
        $this->telefone = trim((string) $this->telefone);

        $validated = $this->validate([
            'nome' => ['required', 'string', 'min:3', 'max:150'],
            'email' => ['required', 'email', 'max:150', 'unique:inscritos,email'],
            'telefone' => ['required', 'string', 'max:50'],
            'cpf' => ['required', 'string', 'max:20', 'unique:inscritos,cpf'],
            'grau' => ['required', 'in:AM,CM,MM,MI,OT'],
            'lojaId' => ['required', 'exists:lojas,id'],
        ], [
            'nome.required' => 'Informe o nome completo.',
            'email.required' => 'Informe o e-mail.',
            'email.email' => 'E-mail inválido.',
            'email.unique' => 'E-mail já cadastrado.',
            'telefone.required' => 'Informe o telefone.',
            'cpf.required' => 'Informe o CPF.',
            'cpf.unique' => 'CPF já cadastrado.',
            'grau.required' => 'Selecione o grau maçônico.',
            'lojaId.required' => 'Selecione a Loja/Capítulo.',
            'lojaId.exists' => 'Loja não encontrada.',
        ]);

        Inscrito::query()->create([
            'name' => $validated['nome'],
            'email' => $validated['email'],
            'telefone' => $validated['telefone'],
            'cpf' => $validated['cpf'],
            'cim' => $validated['cpf'],
            'grau' => $validated['grau'],
            'loja_id' => $validated['lojaId'],
            'is_paied' => false,
        ]);

        $this->flashMessage = 'Inscrição enviada com sucesso.';
        $this->dispatch('inscricao-alert', message: 'A inscrição individual foi enviada com sucesso.');

        $this->resetErrorBag();
        $this->resetValidation();
        $this->resetFormFields();
        $this->showModal = false;
    }

    private function resetFormFields(): void
    {
        $this->reset(['nome', 'email', 'telefone', 'cpf', 'grau', 'lojaId']);
        $this->formKey++;
    }

    public function render()
    {
        return view('livewire.inscricao-modal');
    }
}