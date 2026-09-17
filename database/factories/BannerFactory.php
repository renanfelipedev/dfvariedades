<?php

namespace Database\Factories;

use App\Models\Banner;
use Illuminate\Database\Eloquent\Factories\Factory;

class BannerFactory extends Factory
{
    protected $model = Banner::class;

    public function definition(): array
    {
        return [
            'titulo' => fake()->sentence(3),
            'tipo_midia' => 'imagem',
            'url_midia' => 'https://images.unsplash.com/photo-1547887537-6158d64c35b3?q=80&w=1200&auto=format&fit=crop',
            'link_tipo' => 'colecao',
            'link_id' => '1',
            'ordem' => fake()->numberBetween(1, 5),
            'ativo' => true,
        ];
    }
}
