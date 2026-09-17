<?php

use App\Livewire\Admin\Banners\Index as AdminBannersIndex;
use App\Livewire\Admin\Categorias\Index as AdminCategoriasIndex;
use App\Livewire\Admin\Colecoes\Index as AdminColecoesIndex;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\ListaEspera\Index as AdminListaEsperaIndex;
use App\Livewire\Admin\Marcas\Index as AdminMarcasIndex;
use App\Livewire\Admin\Pedidos\Index as AdminPedidosIndex;
use App\Livewire\Admin\Pedidos\Show as AdminPedidosShow;
use App\Livewire\Admin\Produtos\Form as AdminProdutosForm;
use App\Livewire\Admin\Produtos\Index as AdminProdutosIndex;
use App\Livewire\Admin\Users\Index as AdminUsersIndex;
use App\Livewire\Customer\Dashboard;
use App\Livewire\Storefront\CategoryPage;
use App\Livewire\Storefront\Home;
use App\Livewire\Storefront\ProductDetail;
use Illuminate\Support\Facades\Route;

// Storefront Public Routes
Route::get('/', Home::class)->name('home');
Route::get('/catalogo', CategoryPage::class)->name('catalogo');
Route::get('/catalogo/{tipo}/{slug}', CategoryPage::class)->name('catalogo.tipo');
Route::get('/produto/{slug}', ProductDetail::class)->name('produto.show');

// Authenticated Client Dashboard & Customer Portal
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');
});

// Admin Panel Routes with Access Control (ACL)
Route::prefix('admin')
    ->name('admin.')
    ->middleware(['auth', 'role:admin,gerente,atendente'])
    ->group(function () {
        // Main Dashboard
        Route::get('/', AdminDashboard::class)->name('dashboard');

        // Catalog Management (Admin & Gerente)
        Route::middleware('role:admin,gerente')->group(function () {
            Route::get('/produtos', AdminProdutosIndex::class)->name('produtos.index');
            Route::get('/produtos/novo', AdminProdutosForm::class)->name('produtos.create');
            Route::get('/produtos/{produto}/editar', AdminProdutosForm::class)->name('produtos.edit');

            Route::get('/marcas', AdminMarcasIndex::class)->name('marcas.index');
            Route::get('/colecoes', AdminColecoesIndex::class)->name('colecoes.index');
            Route::get('/categorias', AdminCategoriasIndex::class)->name('categorias.index');
            Route::get('/banners', AdminBannersIndex::class)->name('banners.index');
        });

        // Orders Management (Admin, Gerente & Atendente)
        Route::get('/pedidos', AdminPedidosIndex::class)->name('pedidos.index');
        Route::get('/pedidos/{pedido}', AdminPedidosShow::class)->name('pedidos.show');

        // Waitlist / Leads (Admin & Atendente)
        Route::middleware('role:admin,atendente')->group(function () {
            Route::get('/lista-espera', AdminListaEsperaIndex::class)->name('lista-espera.index');
        });

        // Team & Roles Management (Admin only)
        Route::middleware('role:admin')->group(function () {
            Route::get('/usuarios', AdminUsersIndex::class)->name('users.index');
        });
    });

require __DIR__.'/settings.php';
