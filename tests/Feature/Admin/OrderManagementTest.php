<?php

use App\Livewire\Admin\Pedidos\Index;
use App\Livewire\Admin\Pedidos\Show;
use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\User;
use Livewire\Livewire;

test('atendente can view orders list and filter by status', function () {
    $atendente = User::factory()->create(['role' => User::ROLE_ATENDENTE]);

    $p1 = Pedido::create([
        'codigo' => 'DFV-TEST01',
        'nome_cliente' => 'Maria Silva',
        'whatsapp_cliente' => '61988887777',
        'cpf_cliente' => '12345678900',
        'email_cliente' => 'maria@teste.com',
        'status' => 'pendente',
        'subtotal' => 500,
        'total' => 500,
        'tipo_entrega' => 'entrega',
    ]);

    $p2 = Pedido::create([
        'codigo' => 'DFV-TEST02',
        'nome_cliente' => 'Carlos Souza',
        'whatsapp_cliente' => '61977776666',
        'cpf_cliente' => '98765432100',
        'email_cliente' => 'carlos@teste.com',
        'status' => 'pago',
        'subtotal' => 1200,
        'total' => 1200,
        'tipo_entrega' => 'retirada',
    ]);

    Livewire::actingAs($atendente)
        ->test(Index::class)
        ->assertSee('DFV-TEST01')
        ->assertSee('DFV-TEST02')
        ->set('statusFilter', 'pago')
        ->assertSee('DFV-TEST02')
        ->assertDontSee('DFV-TEST01')
        ->set('statusFilter', 'pendente')
        ->assertSee('DFV-TEST01')
        ->assertDontSee('DFV-TEST02');
});

test('atendente can update order status and notes from details view with toasts', function () {
    $atendente = User::factory()->create(['role' => User::ROLE_ATENDENTE]);

    $pedido = Pedido::create([
        'codigo' => 'DFV-TEST03',
        'nome_cliente' => 'Fernanda Lima',
        'whatsapp_cliente' => '61999998888',
        'cpf_cliente' => '11122233344',
        'email_cliente' => 'fernanda@teste.com',
        'status' => 'pendente',
        'subtotal' => 350,
        'total' => 350,
        'tipo_entrega' => 'retirada',
    ]);

    PedidoItem::create([
        'pedido_id' => $pedido->id,
        'nome_produto' => 'Kit Perfume VIP',
        'preco_unitario' => 350,
        'quantidade' => 1,
        'subtotal' => 350,
    ]);

    Livewire::actingAs($atendente)
        ->test(Show::class, ['pedido' => $pedido])
        ->assertSee('DFV-TEST03')
        ->assertSee('Kit Perfume VIP')
        ->assertSee('Pendente')
        ->call('updateStatus', 'pago')
        ->assertDispatched('toast', message: 'Status do pedido atualizado para: Pago!')
        ->assertSee('Pago')
        ->call('updateStatus', 'em_separacao')
        ->assertDispatched('toast', message: 'Status do pedido atualizado para: Em Separação!')
        ->call('updateStatus', 'enviado')
        ->assertDispatched('toast', message: 'Status do pedido atualizado para: Enviado!')
        ->set('observacoes', 'Cliente confirmou comprovante Pix via WhatsApp')
        ->call('saveObservacoes')
        ->assertDispatched('toast', message: 'Observações do pedido salvas com sucesso!');

    $pedido->refresh();
    expect($pedido->status)->toBe('enviado')
        ->and($pedido->observacoes)->toBe('Cliente confirmou comprovante Pix via WhatsApp');
});

test('invalid status in show view is rejected with toast', function () {
    $atendente = User::factory()->create(['role' => User::ROLE_ATENDENTE]);

    $pedido = Pedido::create([
        'codigo' => 'DFV-TEST04',
        'nome_cliente' => 'Lucas Rocha',
        'whatsapp_cliente' => '61999990000',
        'cpf_cliente' => '12312312300',
        'email_cliente' => 'lucas@teste.com',
        'status' => 'pendente',
        'subtotal' => 100,
        'total' => 100,
        'tipo_entrega' => 'retirada',
    ]);

    Livewire::actingAs($atendente)
        ->test(Show::class, ['pedido' => $pedido])
        ->call('updateStatus', 'status_invalido_xyz')
        ->assertDispatched('toast', message: 'Status inválido informado.');

    $pedido->refresh();
    expect($pedido->status)->toBe('pendente');
});
