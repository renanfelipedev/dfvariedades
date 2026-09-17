<?php

use App\Livewire\Storefront\Home;
use App\Models\Banner;
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

test('home livewire component renders hero banners correctly', function () {
    $banner = Banner::create([
        'titulo' => 'Super Ofertas Exclusivas',
        'tipo_midia' => 'imagem',
        'url_midia' => 'https://example.com/banner.jpg',
        'link_tipo' => 'colecao',
        'link_id' => '1',
        'ordem' => 1,
        'ativo' => true,
    ]);

    expect($banner->target_url)->not->toBeNull();

    Livewire::test(Home::class)
        ->assertSee('Super Ofertas Exclusivas')
        ->assertSee('https://example.com/banner.jpg');
});

test('home livewire component renders multiple flash deals', function () {
    $marca = Marca::first();

    $deal1 = Produto::create([
        'marca_id' => $marca->id,
        'nome' => 'Perfume Flash Deal 1',
        'slug' => 'perfume-flash-deal-1',
        'preco' => 299.90,
        'preco_promocional' => 199.90,
        'estoque' => 10,
        'flash_deal' => true,
        'flash_deal_fim' => now()->addHours(8),
        'ativo' => true,
    ]);

    $deal2 = Produto::create([
        'marca_id' => $marca->id,
        'nome' => 'Perfume Flash Deal 2',
        'slug' => 'perfume-flash-deal-2',
        'preco' => 399.90,
        'preco_promocional' => 249.90,
        'estoque' => 5,
        'flash_deal' => true,
        'flash_deal_fim' => now()->addHours(12),
        'ativo' => true,
    ]);

    Livewire::test(Home::class)
        ->assertSee('Perfume Flash Deal 1')
        ->assertSee('Perfume Flash Deal 2')
        ->assertSee('Ofertas');
});
