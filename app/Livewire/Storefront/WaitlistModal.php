<?php

namespace App\Livewire\Storefront;

use App\Models\ListaEspera;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class WaitlistModal extends Component
{
    public bool $isOpen = false;

    public string $nome = '';

    public string $whatsapp = '';

    public string $email = '';

    public string $cep = '';

    public string $cidade = '';

    public string $estado = 'BA';

    #[On('open-waitlist')]
    public function open(array $data = []): void
    {
        $this->nome = $data['nome'] ?? '';
        $this->whatsapp = $data['whatsapp'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->cep = $data['cep'] ?? '';
        $this->cidade = $data['cidade'] ?? '';
        $this->estado = $data['estado'] ?? 'BA';
        $this->isOpen = true;
    }

    #[On('close-waitlist')]
    public function close(): void
    {
        $this->isOpen = false;
    }

    public function submit(): void
    {
        $this->validate([
            'nome' => 'required|min:3',
            'whatsapp' => 'required|min:9',
            'cep' => 'required',
            'cidade' => 'required',
        ], [
            'nome.required' => 'Informe seu nome',
            'whatsapp.required' => 'Informe seu WhatsApp',
            'cep.required' => 'Informe seu CEP',
            'cidade.required' => 'Informe sua cidade',
        ]);

        ListaEspera::create([
            'nome' => $this->nome,
            'whatsapp' => $this->whatsapp,
            'email' => $this->email ?: null,
            'cep' => $this->cep,
            'cidade' => $this->cidade,
            'estado' => $this->estado ?: 'BA',
        ]);

        $this->isOpen = false;
        $this->reset(['nome', 'whatsapp', 'email', 'cep', 'cidade']);
        $this->dispatch('toast', message: 'Obrigado! Avisaremos você assim que liberarmos entregas na sua cidade.');
    }

    public function render(): View
    {
        return view('livewire.storefront.waitlist-modal');
    }
}
