<?php

namespace Database\Factories;

use App\Models\ListaEspera;
use Illuminate\Database\Eloquent\Factories\Factory;

class ListaEsperaFactory extends Factory
{
    protected $model = ListaEspera::class;

    public function definition(): array
    {
        return [
            'nome' => fake()->name(),
            'whatsapp' => fake()->phoneNumber(),
            'email' => fake()->safeEmail(),
            'cep' => fake()->numerify('#####-###'),
            'cidade' => fake()->city(),
            'estado' => 'BA',
        ];
    }
}
