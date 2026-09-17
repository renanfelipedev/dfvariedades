<?php

use App\Livewire\Admin\Produtos\Form;
use App\Livewire\Admin\Produtos\Index;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Produto;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

test('admin can view products index and search', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $marca = Marca::create(['nome' => 'Apple', 'slug' => 'apple']);
    $cat = Categoria::create(['nome' => 'Celulares', 'slug' => 'celulares']);

    $p1 = Produto::create([
        'nome' => 'iPhone 15 Pro Max',
        'slug' => 'iphone-15-pro-max',
        'preco' => 8000,
        'preco_promocional' => 7500,
        'estoque' => 10,
        'ativo' => true,
        'marca_id' => $marca->id,
        'categoria_id' => $cat->id,
    ]);

    $p2 = Produto::create([
        'nome' => 'Fone Bluetooth Air',
        'slug' => 'fone-bluetooth-air',
        'preco' => 150,
        'estoque' => 50,
        'ativo' => true,
        'marca_id' => $marca->id,
        'categoria_id' => $cat->id,
    ]);

    Livewire::actingAs($admin)
        ->test(Index::class)
        ->assertSee('iPhone 15 Pro Max')
        ->assertSee('Fone Bluetooth Air')
        ->set('search', 'iPhone')
        ->assertSee('iPhone 15 Pro Max')
        ->assertDontSee('Fone Bluetooth Air');
});

test('admin can create a product with validation and file upload', function () {
    Storage::fake('public');
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $marca = Marca::create(['nome' => 'Samsung', 'slug' => 'samsung']);
    $cat = Categoria::create(['nome' => 'Smartphones', 'slug' => 'smartphones']);

    $file = UploadedFile::fake()->image('galaxy.jpg');

    Livewire::actingAs($admin)
        ->test(Form::class)
        ->set('nome', 'Galaxy S24 Ultra')
        ->set('slug', 'galaxy-s24-ultra')
        ->set('preco', '7499.00')
        ->set('preco_promocional', '6999.00')
        ->set('estoque', 15)
        ->set('ativo', true)
        ->set('marca_id', $marca->id)
        ->set('categoria_id', $cat->id)
        ->set('descricao', 'O mais novo flagship da Samsung')
        ->set('novasImagens', [$file])
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('admin.produtos.index'));

    $this->assertDatabaseHas('produtos', [
        'slug' => 'galaxy-s24-ultra',
        'estoque' => 15,
    ]);

    $created = Produto::where('slug', 'galaxy-s24-ultra')->first();
    expect($created->imagens)->toBeArray()
        ->and(count($created->imagens))->toBe(1);
});

test('admin can edit an existing product', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $marca = Marca::create(['nome' => 'Xiaomi', 'slug' => 'xiaomi']);
    $cat = Categoria::create(['nome' => 'Smartphones', 'slug' => 'smartphones']);

    $produto = Produto::create([
        'nome' => 'Xiaomi 13T',
        'slug' => 'xiaomi-13t',
        'preco' => 3000,
        'estoque' => 5,
        'ativo' => true,
        'marca_id' => $marca->id,
        'categoria_id' => $cat->id,
    ]);

    Livewire::actingAs($admin)
        ->test(Form::class, ['produto' => $produto])
        ->set('nome', 'Xiaomi 13T Pro Updated')
        ->set('estoque', 25)
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('admin.produtos.index'));

    $this->assertDatabaseHas('produtos', [
        'id' => $produto->id,
        'nome' => 'Xiaomi 13T Pro Updated',
        'estoque' => 25,
    ]);
});

test('admin can delete a product and immediately reuse its slug', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $marca = Marca::create(['nome' => 'Sony', 'slug' => 'sony']);
    $cat = Categoria::create(['nome' => 'Consoles', 'slug' => 'consoles']);

    $produto = Produto::create([
        'nome' => 'PlayStation 5 Slim',
        'slug' => 'playstation-5-slim',
        'preco' => 3999,
        'estoque' => 10,
        'ativo' => true,
        'marca_id' => $marca->id,
        'categoria_id' => $cat->id,
    ]);

    Livewire::actingAs($admin)
        ->test(Index::class)
        ->call('confirmDelete', $produto->id)
        ->call('delete');

    $this->assertDatabaseMissing('produtos', [
        'id' => $produto->id,
    ]);

    // Ensure slug is immediately free to be reused without conflict
    $novoProduto = Produto::create([
        'nome' => 'PlayStation 5 Slim',
        'slug' => 'playstation-5-slim',
        'preco' => 3799,
        'estoque' => 5,
        'ativo' => true,
        'marca_id' => $marca->id,
        'categoria_id' => $cat->id,
    ]);

    $this->assertDatabaseHas('produtos', [
        'id' => $novoProduto->id,
        'slug' => 'playstation-5-slim',
    ]);
});

test('admin can save and immediately create another product without leaving the form', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $marca = Marca::create(['nome' => 'Natura', 'slug' => 'natura']);
    $cat = Categoria::create(['nome' => 'Perfumaria', 'slug' => 'perfumaria']);

    Livewire::actingAs($admin)
        ->test(Form::class)
        ->set('nome', 'Perfume Kaiak 100ml')
        ->set('slug', 'perfume-kaiak-100ml')
        ->set('preco', '159,90') // test comma format
        ->set('preco_promocional', '129,90')
        ->set('estoque', 20)
        ->set('marca_id', $marca->id)
        ->set('categoria_id', $cat->id)
        ->call('saveAndCreateAnother')
        ->assertHasNoErrors()
        ->assertDispatched('toast')
        ->assertSet('nome', '')
        ->assertSet('preco', '');

    $this->assertDatabaseHas('produtos', [
        'slug' => 'perfume-kaiak-100ml',
        'preco' => 159.90,
        'preco_promocional' => 129.90,
    ]);
});
