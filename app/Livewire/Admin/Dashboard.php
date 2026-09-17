<?php

namespace App\Livewire\Admin;

use App\Models\ListaEspera;
use App\Models\Pedido;
use App\Models\Produto;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.admin')]
#[Title('Dashboard Geral')]
class Dashboard extends Component
{
    public int $limiteEstoque = 5;

    public function mount(): void
    {
        $this->limiteEstoque = (int) session('dfv_estoque_limite', 5);
    }

    public function setLimiteEstoque(int $valor): void
    {
        $this->limiteEstoque = max(1, $valor);
        session(['dfv_estoque_limite' => $this->limiteEstoque]);
        $this->dispatch('toast', message: "Alerta de estoque atualizado para ≤ {$this->limiteEstoque} unidades!");
    }

    public function updatedLimiteEstoque(mixed $value): void
    {
        $this->limiteEstoque = max(1, (int) $value);
        session(['dfv_estoque_limite' => $this->limiteEstoque]);
        $this->dispatch('toast', message: "Alerta de estoque atualizado para ≤ {$this->limiteEstoque} unidades!");
    }

    public function render(): View
    {
        $faturamentoTotal = Pedido::where('status', '!=', 'cancelado')->sum('total');
        $totalPedidos = Pedido::count();
        $pedidosPendentes = Pedido::where('status', 'pendente')->count();

        $totalProdutos = Produto::where('ativo', true)->count();
        $estoqueBaixo = Produto::estoqueBaixo($this->limiteEstoque)->count();
        $produtosEstoqueBaixo = Produto::with('marca')
            ->estoqueBaixo($this->limiteEstoque)
            ->limit(8)
            ->get();

        $totalListaEspera = ListaEspera::count();
        $ultimosPedidos = Pedido::with('itens')->latest()->limit(6)->get();

        return view('livewire.admin.dashboard', [
            'faturamentoTotal' => $faturamentoTotal,
            'totalPedidos' => $totalPedidos,
            'pedidosPendentes' => $pedidosPendentes,
            'totalProdutos' => $totalProdutos,
            'estoqueBaixo' => $estoqueBaixo,
            'produtosEstoqueBaixo' => $produtosEstoqueBaixo,
            'totalListaEspera' => $totalListaEspera,
            'ultimosPedidos' => $ultimosPedidos,
            'limiteEstoque' => $this->limiteEstoque,
        ]);
    }
}
