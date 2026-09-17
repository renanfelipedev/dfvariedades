<?php

namespace Database\Factories;

use App\Models\Colecao;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ColecaoFactory extends Factory
{
    protected $model = Colecao::class;

    public function definition(): array
    {
        $nome = fake()->unique()->words(2, true);

        return [
            'nome' => ucfirst($nome),
            'slug' => Str::slug($nome),
            'descricao' => fake()->paragraph(),
            'imagem_url' => 'https://images.unsplash.com/photo-1547887537-6158d64c35b3?q=80&w=600&auto=format&fit=crop',
            'banner_url' => 'https://images.unsplash.com/photo-1547887537-6158d64c35b3?q=80&w=1200&auto=format&fit=crop',
            'ordem' => fake()->numberBetween(1, 10),
            'ativo' => true,
            'destaque' => fake()->boolean(70),
        ];
    }
}
