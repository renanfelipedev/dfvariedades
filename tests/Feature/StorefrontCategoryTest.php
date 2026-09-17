<?php

use App\Livewire\Storefront\CategoryPage;
use App\Models\Marca;
use App\Models\Produto;
use Database\Seeders\StorefrontSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(StorefrontSeeder::class);
});

test('category page renders all products by default', function () {
    $response = $this->get(route('catalogo'));

    $response->assertOk();
    $response->assertSee('Todos os Produtos');
});

test('category page filters by marca', function () {
    $marca = Marca::where('slug', 'o-boticario')->first();

    $response = $this->get(route('catalogo.tipo', ['tipo' => 'marca', 'slug' => $marca->slug]));
    $response->assertOk();
    $response->assertSee($marca->nome);
});

test('category page searches products correctly', function () {
    $produto = Produto::first();

    Livewire::test(CategoryPage::class)
        ->set('search', $produto->nome)
        ->assertSee($produto->nome);
});
