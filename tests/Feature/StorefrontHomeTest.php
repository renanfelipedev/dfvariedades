<?php

use App\Livewire\Storefront\Home;
use App\Models\Marca;
use App\Models\Produto;
use Database\Seeders\StorefrontSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(StorefrontSeeder::class);
});

test('storefront home renders successfully', function () {
    $response = $this->get(route('home'));

    $response->assertOk();
    $response->assertSee('DF');
    $response->assertSee('VARIEDADES');
});

test('home livewire component loads brands and products', function () {
    $marca = Marca::first();
    $produto = Produto::first();

    Livewire::test(Home::class)
        ->assertSee($marca->nome)
        ->assertSee($produto->nome);
});

test('home livewire component can add product to cart', function () {
    $produto = Produto::first();

    Livewire::test(Home::class)
        ->call('addToCart', $produto->id)
        ->assertDispatched('cart-updated')
        ->assertDispatched('toast');
});
