<?php

namespace App\Livewire\Admin\Pedidos;

use App\Models\Pedido;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Gerenciar Pedidos')]
class Index extends Component
{
    use WithPagination;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'status')]
    public string $statusFilter = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingStatusFilter(): void
    {
        $this->resetPage();
    }

    public function updateStatus(int $pedidoId, string $newStatus): void
    {
        $pedido = Pedido::find($pedidoId);
        if ($pedido) {
            $pedido->status = $newStatus;
            $pedido->save();
            $label = Show::STATUS_LABELS[$newStatus] ?? ucwords(str_replace('_', ' ', $newStatus));
            $this->dispatch('toast', message: "Status do pedido #{$pedido->codigo} atualizado para: {$label}!");
        }
    }

    public function closeModal(): void
    {
        // No-op for safety
    }

    public function render(): View
    {
        $query = Pedido::with('itens');

        if ($this->search) {
            $term = $this->search;
            $query->where(function ($q) use ($term) {
                $q->where('codigo', 'like', "%{$term}%")
                    ->orWhere('nome_cliente', 'like', "%{$term}%")
                    ->orWhere('whatsapp_cliente', 'like', "%{$term}%")
                    ->orWhere('cpf_cliente', 'like', "%{$term}%");
            });
        }

        if ($this->statusFilter) {
            if ($this->statusFilter === 'pendente') {
                $query->whereIn('status', ['pendente', 'aguardando_pagamento']);
            } else {
                $query->where('status', $this->statusFilter);
            }
        }

        $pedidos = $query->latest()->paginate(12);

        $contagem = [
            'todos' => Pedido::count(),
            'pendente' => Pedido::whereIn('status', ['pendente', 'aguardando_pagamento'])->count(),
            'pago' => Pedido::where('status', 'pago')->count(),
            'em_separacao' => Pedido::where('status', 'em_separacao')->count(),
            'pronto_retirada' => Pedido::where('status', 'pronto_retirada')->count(),
            'enviado' => Pedido::where('status', 'enviado')->count(),
            'entregue' => Pedido::where('status', 'entregue')->count(),
            'cancelado' => Pedido::where('status', 'cancelado')->count(),
        ];

        return view('livewire.admin.pedidos.index', [
            'pedidos' => $pedidos,
            'contagem' => $contagem,
        ]);
    }
}
