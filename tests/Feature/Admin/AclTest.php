<?php

use App\Models\User;

test('guests are redirected to login when accessing admin dashboard', function () {
    $response = $this->get(route('admin.dashboard'));
    $response->assertRedirect(route('login'));
});

test('cliente role is forbidden from accessing admin panel', function () {
    $cliente = User::factory()->create(['role' => User::ROLE_CLIENTE]);
    $this->actingAs($cliente);

    $response = $this->get(route('admin.dashboard'));
    $response->assertForbidden();
});

test('atendente can access dashboard, orders, and waitlist leads, but is forbidden from catalog and users', function () {
    $atendente = User::factory()->create(['role' => User::ROLE_ATENDENTE]);
    $this->actingAs($atendente);

    $this->get(route('admin.dashboard'))->assertOk();
    $this->get(route('admin.pedidos.index'))->assertOk();
    $this->get(route('admin.lista-espera.index'))->assertOk();

    // Forbidden from catalog
    $this->get(route('admin.produtos.index'))->assertForbidden();
    $this->get(route('admin.marcas.index'))->assertForbidden();

    // Forbidden from user management
    $this->get(route('admin.users.index'))->assertForbidden();
});

test('gerente can access dashboard, catalog, and orders, but is forbidden from waitlist and users', function () {
    $gerente = User::factory()->create(['role' => User::ROLE_GERENTE]);
    $this->actingAs($gerente);

    $this->get(route('admin.dashboard'))->assertOk();
    $this->get(route('admin.produtos.index'))->assertOk();
    $this->get(route('admin.marcas.index'))->assertOk();
    $this->get(route('admin.colecoes.index'))->assertOk();
    $this->get(route('admin.categorias.index'))->assertOk();
    $this->get(route('admin.banners.index'))->assertOk();
    $this->get(route('admin.pedidos.index'))->assertOk();

    // Forbidden from waitlist
    $this->get(route('admin.lista-espera.index'))->assertForbidden();

    // Forbidden from user management
    $this->get(route('admin.users.index'))->assertForbidden();
});

test('admin has full access to all admin panel modules', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $this->actingAs($admin);

    $this->get(route('admin.dashboard'))->assertOk();
    $this->get(route('admin.produtos.index'))->assertOk();
    $this->get(route('admin.produtos.create'))->assertOk();
    $this->get(route('admin.marcas.index'))->assertOk();
    $this->get(route('admin.colecoes.index'))->assertOk();
    $this->get(route('admin.categorias.index'))->assertOk();
    $this->get(route('admin.banners.index'))->assertOk();
    $this->get(route('admin.pedidos.index'))->assertOk();
    $this->get(route('admin.lista-espera.index'))->assertOk();
    $this->get(route('admin.users.index'))->assertOk();
});
