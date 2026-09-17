<?php

namespace App\Console\Commands;

use App\Models\Banner;
use App\Models\Categoria;
use App\Models\Colecao;
use App\Models\ListaEspera;
use App\Models\Marca;
use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\Produto;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class ResetEmptyCatalog extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset-empty {--force : Executar sem pedir confirmação}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Limpa todos os dados de demonstração (produtos, marcas, banners, coleções, pedidos) e mantém o sistema pronto para cadastro do zero';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if (! $this->option('force') && ! $this->confirm('Deseja realmente limpar todo o catálogo e começar do zero com banco limpo?', true)) {
            $this->warn('Operação cancelada pelo usuário.');

            return self::SUCCESS;
        }

        $this->info('Limpando catálogo e dados de demonstração...');

        Schema::disableForeignKeyConstraints();

        PedidoItem::query()->forceDelete();
        Pedido::query()->forceDelete();
        Produto::query()->forceDelete();
        Categoria::query()->forceDelete();
        Colecao::query()->forceDelete();
        Marca::query()->forceDelete();
        Banner::query()->forceDelete();
        ListaEspera::query()->forceDelete();

        Schema::enableForeignKeyConstraints();

        // Limpar uploads antigos de teste na pasta pública
        if (Storage::disk('public')->exists('produtos')) {
            Storage::disk('public')->deleteDirectory('produtos');
        }
        if (Storage::disk('public')->exists('banners')) {
            Storage::disk('public')->deleteDirectory('banners');
        }
        if (Storage::disk('public')->exists('marcas')) {
            Storage::disk('public')->deleteDirectory('marcas');
        }
        if (Storage::disk('public')->exists('colecoes')) {
            Storage::disk('public')->deleteDirectory('colecoes');
        }

        // Garantir que os usuários de administração e equipe estejam criados
        $this->ensureUsersExist();

        $this->newLine();
        $this->info('✨ Sistema limpo com sucesso! Pronto para cadastro do zero.');
        $this->table(
            ['Usuário', 'E-mail', 'Senha', 'Papel'],
            [
                ['Administrador', 'admin@email.com', 'admin@123', 'admin (Acesso Total)'],
                ['Gerente', 'gerente@dfvariedades.com.br', 'gerente@123', 'gerente (Catálogo & Pedidos)'],
                ['Atendente', 'atendente@dfvariedades.com.br', 'atendente@123', 'atendente (Pedidos & Leads)'],
                ['Cliente VIP', 'cliente@dfvariedades.com.br', 'password', 'cliente (Vitrine)'],
            ]
        );

        $this->newLine();
        $this->line('👉 Acesse <comment>/login</comment> ou <comment>/admin</comment> para iniciar o cadastro dos seus produtos reais.');

        return self::SUCCESS;
    }

    private function ensureUsersExist(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@email.com'],
            [
                'name' => 'Administrador DF Variedades',
                'role' => User::ROLE_ADMIN,
                'password' => bcrypt('admin@123'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'gerente@dfvariedades.com.br'],
            [
                'name' => 'Gerente de Catálogo',
                'role' => User::ROLE_GERENTE,
                'password' => bcrypt('gerente@123'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'atendente@dfvariedades.com.br'],
            [
                'name' => 'Atendente de Vendas',
                'role' => User::ROLE_ATENDENTE,
                'password' => bcrypt('atendente@123'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'cliente@dfvariedades.com.br'],
            [
                'name' => 'Cliente DF Variedades',
                'role' => User::ROLE_CLIENTE,
                'password' => bcrypt('password'),
            ]
        );
    }
}
