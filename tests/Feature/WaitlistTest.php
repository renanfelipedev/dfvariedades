<?php

use App\Livewire\Storefront\WaitlistModal;
use App\Models\ListaEspera;
use Livewire\Livewire;

test('waitlist modal saves customer interest to database', function () {
    Livewire::test(WaitlistModal::class)
        ->set('nome', 'Ana Oliveira')
        ->set('whatsapp', '(74) 98888-7777')
        ->set('email', 'ana@example.com')
        ->set('cep', '44900-000')
        ->set('cidade', 'Lapão')
        ->set('estado', 'BA')
        ->call('submit')
        ->assertSet('isOpen', false)
        ->assertDispatched('toast');

    expect(ListaEspera::where('email', 'ana@example.com')->count())->toBe(1);
});
