<?php

use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Produtos\Form;
use App\Livewire\Admin\Produtos\Index;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Produto;
use App\Models\User;
use Livewire\Livewire;

test('admin can set custom estoque minimo per product', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $marca = Marca::create(['nome' => 'Apple', 'slug' => 'apple']);
    $cat = Categoria::create(['nome' => 'Acessórios', 'slug' => 'acessorios']);

    $produto = Produto::create([
        'nome' => 'Cabo USB-C',
        'slug' => 'cabo-usb-c',
        'preco' => 50,
        'estoque' => 12,
        'estoque_minimo' => 5,
        'ativo' => true,
        'marca_id' => $marca->id,
        'categoria_id' => $cat->id,
    ]);

    Livewire::actingAs($admin)
        ->test(Form::class, ['produto' => $produto])
        ->set('estoque_minimo', 15)
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('admin.produtos.index'));

    $this->assertDatabaseHas('produtos', [
        'id' => $produto->id,
        'estoque_minimo' => 15,
    ]);
});

test('dashboard allows dynamically altering low stock alert threshold and persists to session', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $marca = Marca::create(['nome' => 'Marca A', 'slug' => 'marca-a']);
    $cat = Categoria::create(['nome' => 'Cat A', 'slug' => 'cat-a']);

    Produto::create([
        'nome' => 'Produto 1',
        'slug' => 'prod-1',
        'preco' => 100,
        'estoque' => 8,
        'estoque_minimo' => 5,
        'ativo' => true,
        'marca_id' => $marca->id,
        'categoria_id' => $cat->id,
    ]);

    Livewire::actingAs($admin)
        ->test(Dashboard::class)
        ->set('limiteEstoque', 10)
        ->assertSet('limiteEstoque', 10);

    expect(session('dfv_estoque_limite'))->toBe(10);
});

test('product catalog filters correctly by low stock and out of stock', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $marca = Marca::create(['nome' => 'Marca B', 'slug' => 'marca-b']);
    $cat = Categoria::create(['nome' => 'Cat B', 'slug' => 'cat-b']);

    $pNormal = Produto::create([
        'nome' => 'Produto Abundante',
        'slug' => 'prod-abundante',
        'preco' => 100,
        'estoque' => 50,
        'estoque_minimo' => 5,
        'ativo' => true,
        'marca_id' => $marca->id,
        'categoria_id' => $cat->id,
    ]);

    $pBaixo = Produto::create([
        'nome' => 'Produto Quase Acabando',
        'slug' => 'prod-baixo',
        'preco' => 100,
        'estoque' => 4,
        'estoque_minimo' => 5,
        'ativo' => true,
        'marca_id' => $marca->id,
        'categoria_id' => $cat->id,
    ]);

    $pZerado = Produto::create([
        'nome' => 'Produto Esgotado Total',
        'slug' => 'prod-zerado',
        'preco' => 100,
        'estoque' => 0,
        'estoque_minimo' => 5,
        'ativo' => true,
        'marca_id' => $marca->id,
        'categoria_id' => $cat->id,
    ]);

    Livewire::actingAs($admin)
        ->test(Index::class)
        ->set('estoqueFilter', 'baixo')
        ->assertSee('Produto Quase Acabando')
        ->assertDontSee('Produto Abundante')
        ->set('estoqueFilter', 'zerado')
        ->assertSee('Produto Esgotado Total')
        ->assertDontSee('Produto Quase Acabando');
});
