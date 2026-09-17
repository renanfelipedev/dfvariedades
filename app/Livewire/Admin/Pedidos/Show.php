<?php

namespace App\Livewire\Admin\Pedidos;

use App\Models\Pedido;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Show extends Component
{
    public const STATUS_LABELS = [
        'pendente' => 'Pendente',
        'aguardando_pagamento' => 'Aguardando Pagamento',
        'pago' => 'Pago',
        'em_separacao' => 'Em Separação',
        'pronto_retirada' => 'Pronto para Retirada',
        'enviado' => 'Enviado',
        'entregue' => 'Entregue',
        'cancelado' => 'Cancelado',
    ];

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
        if (! array_key_exists($newStatus, self::STATUS_LABELS)) {
            $this->dispatch('toast', message: 'Status inválido informado.');

            return;
        }

        $this->pedido->status = $newStatus;
        $this->pedido->save();
        $this->pedido->refresh();
        $this->status = $newStatus;

        $label = self::STATUS_LABELS[$newStatus] ?? $newStatus;
        $this->dispatch('toast', message: "Status do pedido atualizado para: {$label}!");
    }

    public function saveObservacoes(): void
    {
        $this->pedido->observacoes = $this->observacoes;
        $this->pedido->save();
        $this->pedido->refresh();

        $this->dispatch('toast', message: 'Observações do pedido salvas com sucesso!');
    }

    public function getWhatsappLinkProperty(): string
    {
        $phone = preg_replace('/\D/', '', $this->pedido->whatsapp_cliente ?? '');
        if (strlen($phone) === 10 || strlen($phone) === 11) {
            $phone = '55'.$phone;
        }

        $statusLabel = self::STATUS_LABELS[$this->pedido->status] ?? $this->pedido->status;
        $msg = "Olá {$this->pedido->nome_cliente}! Somos da *DF Variedades*. Entramos em contato referente ao seu pedido *#{$this->pedido->codigo}* (Status atual: {$statusLabel}).";

        return "https://api.whatsapp.com/send?phone={$phone}&text=".urlencode($msg);
    }

    public function render()
    {
        return view('livewire.admin.pedidos.show')
            ->title("Pedido #{$this->pedido->codigo} - Painel DF Variedades");
    }
}
