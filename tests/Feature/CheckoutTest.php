<?php

use App\Livewire\Storefront\CheckoutWizard;
use App\Models\Pedido;
use App\Models\Produto;
use App\Services\CartService;
use Database\Seeders\StorefrontSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(StorefrontSeeder::class);
});

test('checkout wizard creates order in database and clears cart', function () {
    $produto = Produto::first();
    $cartService = app(CartService::class);
    $cartService->addItem($produto->id, 2);

    Livewire::test(CheckoutWizard::class)
        ->call('open')
        ->set('nome', 'Renan Silva')
        ->set('whatsapp', '(74) 99999-9999')
        ->set('cpf', '123.456.789-00')
        ->set('email', 'renan@example.com')
        ->set('tipo_entrega', 'entrega')
        ->set('cep', '44870-000')
        ->set('rua', 'Rua das Flores')
        ->set('numero', '100')
        ->set('bairro', 'Centro')
        ->set('cidade', 'Irecê')
        ->set('estado', 'BA')
        ->call('goToStep', 2)
        ->call('goToStep', 3)
        ->set('forma_pagamento', 'pix')
        ->call('goToStep', 4)
        ->call('finishOrder')
        ->assertSet('pedidoCriado', fn ($pedido) => $pedido instanceof Pedido);

    expect(Pedido::where('email_cliente', 'renan@example.com')->count())->toBe(1);
    expect($cartService->getCount())->toBe(0);
});
