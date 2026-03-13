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
            'grau' => ['required', 'in:AM,CM,MM,MI'],
            'lojaId' => ['required', 'exists:lojas,id'],
        ], [
            'nome.required' => 'Informe o nome completo.',
            'email.required' => 'Informe o e-mail.',
            'email.email' => 'E-mail invalido.',
            'email.unique' => 'E-mail ja cadastrado.',
            'telefone.required' => 'Informe o telefone.',
            'cpf.required' => 'Informe o CPF.',
            'cpf.unique' => 'CPF ja cadastrado.',
            'grau.required' => 'Selecione o grau masonico.',
            'lojaId.required' => 'Selecione a Loja/Capitulo.',
            'lojaId.exists' => 'Loja nao encontrada.',
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

        $this->flashMessage = 'Inscricao enviada com sucesso.';
        $this->dispatch('inscricao-alert', message: 'A inscricao individual foi enviada com sucesso.');

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
