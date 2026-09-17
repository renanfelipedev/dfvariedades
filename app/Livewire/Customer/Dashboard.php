<?php

namespace App\Livewire\Customer;

use App\Models\Pedido;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.storefront')]
class Dashboard extends Component
{
    public function mount()
    {
        $user = Auth::user();

        // Se for admin, gerente ou atendente, redireciona para o painel de gestão
        if ($user && $user->canAccessAdmin()) {
            return redirect()->route('admin.dashboard');
        }
    }

    public function render()
    {
        $user = Auth::user();

        $pedidos = Pedido::with('itens')
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhere('email_cliente', $user->email);
            })
            ->latest()
            ->get();

        $totalGasto = $pedidos->whereNotIn('status', ['cancelado'])->sum('total');
        $pedidosEmAndamento = $pedidos->whereIn('status', ['pendente', 'aguardando_pagamento', 'pago', 'em_separacao', 'pronto_retirada', 'enviado'])->count();

        return view('livewire.customer.dashboard', [
            'user' => $user,
            'pedidos' => $pedidos,
            'totalGasto' => $totalGasto,
            'pedidosEmAndamento' => $pedidosEmAndamento,
        ])->title('Minha Conta & Meus Pedidos — DF Variedades');
    }
}
