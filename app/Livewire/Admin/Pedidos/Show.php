<?php

namespace App\Livewire\Admin\Pedidos;

use App\Models\Pedido;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Show extends Component
{
    public Pedido $pedido;

    public string $status = '';

    public string $observacoes = '';

    public function mount(Pedido $pedido): void
    {
        $this->pedido = $pedido->load(['itens.produto', 'user']);
        $this->status = $pedido->status;
        $this->observacoes = $pedido->observacoes ?? '';
    }

    public function updateStatus(string $newStatus): void
    {
        $allowedStatuses = [
            'aguardando_pagamento',
            'pago',
            'em_separacao',
            'pronto_retirada',
            'enviado',
            'entregue',
            'cancelado',
        ];

        if (! in_array($newStatus, $allowedStatuses, true)) {
            session()->flash('error', 'Status inválido informado.');

            return;
        }

        $this->pedido->update([
            'status' => $newStatus,
        ]);

        $this->status = $newStatus;
        session()->flash('message', 'Status do pedido atualizado para: '.ucwords(str_replace('_', ' ', $newStatus)));
    }

    public function saveObservacoes(): void
    {
        $this->pedido->update([
            'observacoes' => $this->observacoes,
        ]);

        session()->flash('message', 'Observações salvas com sucesso!');
    }

    public function getWhatsappLinkProperty(): string
    {
        $phone = preg_replace('/\D/', '', $this->pedido->whatsapp_cliente ?? '');
        if (strlen($phone) === 10 || strlen($phone) === 11) {
            $phone = '55'.$phone;
        }

        $msg = "Olá {$this->pedido->nome_cliente}! Somos da *DF Variedades*. Entramos em contato referente ao seu pedido *#{$this->pedido->codigo}* (Status atual: ".ucwords(str_replace('_', ' ', $this->pedido->status)).').';

        return "https://api.whatsapp.com/send?phone={$phone}&text=".urlencode($msg);
    }

    public function render()
    {
        return view('livewire.admin.pedidos.show')
            ->title("Pedido #{$this->pedido->codigo} - Painel DF Variedades");
    }
}
