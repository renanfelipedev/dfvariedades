<?php

namespace Database\Factories;

use App\Models\Marca;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MarcaFactory extends Factory
{
    protected $model = Marca::class;

    public function definition(): array
    {
        $nome = fake()->unique()->company();

        return [
            'nome' => $nome,
            'slug' => Str::slug($nome),
            'logo_url' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?q=80&w=200&auto=format&fit=crop',
            'descricao' => fake()->sentence(),
            'cor' => '#C9A84C',
            'ordem' => fake()->numberBetween(1, 10),
            'ativo' => true,
            'destaque' => fake()->boolean(60),
        ];
    }
}
