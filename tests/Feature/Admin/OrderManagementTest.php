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
        'status' => 'aguardando_pagamento',
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
        ->assertDontSee('DFV-TEST01');
});

test('atendente can update order status and notes', function () {
    $atendente = User::factory()->create(['role' => User::ROLE_ATENDENTE]);

    $pedido = Pedido::create([
        'codigo' => 'DFV-TEST03',
        'nome_cliente' => 'Fernanda Lima',
        'whatsapp_cliente' => '61999998888',
        'cpf_cliente' => '11122233344',
        'email_cliente' => 'fernanda@teste.com',
        'status' => 'aguardando_pagamento',
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
        ->call('updateStatus', 'pago')
        ->set('observacoes', 'Cliente confirmou comprovante Pix via WhatsApp')
        ->call('saveObservacoes');

    $pedido->refresh();
    expect($pedido->status)->toBe('pago')
        ->and($pedido->observacoes)->toBe('Cliente confirmou comprovante Pix via WhatsApp');
});
