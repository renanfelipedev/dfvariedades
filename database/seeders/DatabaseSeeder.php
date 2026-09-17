<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Cliente padrão
        User::updateOrCreate(
            ['email' => 'cliente@dfvariedades.com.br'],
            [
                'name' => 'Cliente DF Variedades',
                'role' => User::ROLE_CLIENTE,
                'password' => bcrypt('password'),
            ]
        );

        // 2. Administrador principal
        User::updateOrCreate(
            ['email' => 'admin@email.com'],
            [
                'name' => 'Administrador DF Variedades',
                'role' => User::ROLE_ADMIN,
                'password' => bcrypt('admin@123'),
            ]
        );

        // 3. Gerente de catálogo
        User::updateOrCreate(
            ['email' => 'gerente@dfvariedades.com.br'],
            [
                'name' => 'Gerente de Catálogo',
                'role' => User::ROLE_GERENTE,
                'password' => bcrypt('gerente@123'),
            ]
        );

        // 4. Atendente de pedidos
        User::updateOrCreate(
            ['email' => 'atendente@dfvariedades.com.br'],
            [
                'name' => 'Atendente de Vendas',
                'role' => User::ROLE_ATENDENTE,
                'password' => bcrypt('atendente@123'),
            ]
        );

        // Para popular com dados fictícios de demonstração, execute:
        // php artisan db:seed --class=StorefrontSeeder
        // $this->call(StorefrontSeeder::class);
    }
}
