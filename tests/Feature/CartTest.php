<?php

use App\Livewire\Storefront\CartDrawer;
use App\Models\Produto;
use App\Services\CartService;
use Database\Seeders\StorefrontSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(StorefrontSeeder::class);
});

test('cart service manages items correctly', function () {
    $cartService = new CartService;
    $produto = Produto::first();

    $cartService->addItem($produto->id, 2);
    expect($cartService->getCount())->toBe(2);
    expect($cartService->getSubtotal())->toBe($produto->preco_final * 2);

    $cartService->updateQuantity($produto->id, 1);
    expect($cartService->getCount())->toBe(1);

    $cartService->removeItem($produto->id);
    expect($cartService->getCount())->toBe(0);
});

test('cart drawer livewire component updates reactively', function () {
    $produto = Produto::first();
    $cartService = app(CartService::class);
    $cartService->addItem($produto->id, 1);

    Livewire::test(CartDrawer::class)
        ->call('open')
        ->assertSet('isOpen', true)
        ->assertSee($produto->nome)
        ->call('incrementQty', $produto->id)
        ->assertDispatched('cart-updated');
});
