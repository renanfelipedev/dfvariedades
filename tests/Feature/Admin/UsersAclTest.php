<?php

use App\Livewire\Admin\Users\Index;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Livewire;

test('admin can create a new team member with specific role', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);

    Livewire::actingAs($admin)
        ->test(Index::class)
        ->set('name', 'Novo Atendente DF')
        ->set('email', 'atendente.novo@dfvariedades.com.br')
        ->set('userRole', User::ROLE_ATENDENTE)
        ->set('password', 'secret123')
        ->set('password_confirmation', 'secret123')
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('users', [
        'email' => 'atendente.novo@dfvariedades.com.br',
        'role' => User::ROLE_ATENDENTE,
    ]);

    $newUser = User::where('email', 'atendente.novo@dfvariedades.com.br')->first();
    expect(Hash::check('secret123', $newUser->password))->toBeTrue();
});

test('admin can change user role and cannot delete self', function () {
    $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
    $teamUser = User::factory()->create(['role' => User::ROLE_ATENDENTE]);

    Livewire::actingAs($admin)
        ->test(Index::class)
        ->call('openEditModal', $teamUser->id)
        ->set('userRole', User::ROLE_GERENTE)
        ->call('save')
        ->assertHasNoErrors();

    $teamUser->refresh();
    expect($teamUser->role)->toBe(User::ROLE_GERENTE);

    // Attempting to delete own account should be blocked
    Livewire::actingAs($admin)
        ->test(Index::class)
        ->call('confirmDelete', $admin->id)
        ->call('delete');

    $this->assertDatabaseHas('users', ['id' => $admin->id]);
});
