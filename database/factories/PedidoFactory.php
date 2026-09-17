<?php

namespace Database\Factories;

use App\Models\Pedido;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PedidoFactory extends Factory
{
    protected $model = Pedido::class;

    public function definition(): array
    {
        $subtotal = fake()->randomFloat(2, 80, 500);
        $frete = 8.00;

        return [
            'codigo' => 'DFV-'.strtoupper(Str::random(6)),
            'user_id' => User::factory(),
            'nome_cliente' => fake()->name(),
            'whatsapp_cliente' => fake()->phoneNumber(),
            'cpf_cliente' => fake()->numerify('###.###.###-##'),
            'email_cliente' => fake()->safeEmail(),
            'tipo_entrega' => 'entrega',
            'loja_retirada' => null,
            'cep' => '44870-000',
            'rua' => fake()->streetName(),
            'numero' => (string) fake()->buildingNumber(),
            'complemento' => 'Apto 101',
            'bairro' => 'Centro',
            'cidade' => 'Irecê',
            'estado' => 'BA',
            'opcao_frete' => 'Motoboy Local',
            'valor_frete' => $frete,
            'subtotal' => $subtotal,
            'desconto' => 0,
            'taxa_pagamento' => 0,
            'total' => $subtotal + $frete,
            'forma_pagamento' => 'pix',
            'status' => 'pendente',
            'observacoes' => null,
        ];
    }
}
