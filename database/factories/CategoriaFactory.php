<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\Colecao;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoriaFactory extends Factory
{
    protected $model = Categoria::class;

    public function definition(): array
    {
        $nome = fake()->unique()->words(2, true);

        return [
            'colecao_id' => Colecao::factory(),
            'nome' => ucfirst($nome),
            'slug' => Str::slug($nome),
            'descricao' => fake()->sentence(),
            'ordem' => fake()->numberBetween(1, 10),
            'ativo' => true,
        ];
    }
}
