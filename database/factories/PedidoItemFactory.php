<?php

namespace Database\Factories;

use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\Produto;
use Illuminate\Database\Eloquent\Factories\Factory;

class PedidoItemFactory extends Factory
{
    protected $model = PedidoItem::class;

    public function definition(): array
    {
        $preco = fake()->randomFloat(2, 50, 250);
        $qtd = fake()->numberBetween(1, 3);

        return [
            'pedido_id' => Pedido::factory(),
            'produto_id' => Produto::factory(),
            'nome_produto' => fake()->words(3, true),
            'preco_unitario' => $preco,
            'quantidade' => $qtd,
            'subtotal' => $preco * $qtd,
            'imagem_url' => 'https://images.unsplash.com/photo-1522335789203-aabd1fc54bc9?q=80&w=600&auto=format&fit=crop',
        ];
    }
}
