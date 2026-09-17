<?php

namespace Database\Factories;

use App\Models\Categoria;
use App\Models\Colecao;
use App\Models\Marca;
use App\Models\Produto;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProdutoFactory extends Factory
{
    protected $model = Produto::class;

    public function definition(): array
    {
        $nome = fake()->words(3, true).' '.fake()->randomElement(['100ml', '50ml', '200g', 'Kit', 'Original']);
        $preco = fake()->randomFloat(2, 49.90, 459.90);
        $temPromo = fake()->boolean(40);
        $precoPromo = $temPromo ? round($preco * fake()->randomFloat(2, 0.70, 0.90), 2) : null;

        return [
            'marca_id' => Marca::factory(),
            'colecao_id' => Colecao::factory(),
            'categoria_id' => Categoria::factory(),
            'nome' => ucfirst($nome),
            'slug' => Str::slug($nome).'-'.fake()->unique()->numberBetween(100, 9999),
            'descricao' => fake()->paragraph(),
            'detalhes' => fake()->paragraph(),
            'preco' => $preco,
            'preco_promocional' => $precoPromo,
            'estoque' => fake()->numberBetween(3, 50),
            'sku' => 'DFV-'.fake()->unique()->numerify('#####'),
            'imagens' => [
                'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?q=80&w=600&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1547887537-6158d64c35b3?q=80&w=600&auto=format&fit=crop',
            ],
            'ativo' => true,
            'destaque' => fake()->boolean(30),
            'escolhido' => fake()->boolean(20),
            'presente' => fake()->boolean(20),
            'cabelo' => fake()->boolean(20),
            'flash_deal' => false,
            'ordem' => fake()->numberBetween(1, 20),
        ];
    }
}
