<?php

namespace App\Livewire\Storefront;

use App\Models\Banner;
use App\Models\Colecao;
use App\Models\Marca;
use App\Models\Produto;
use App\Services\CartService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.storefront')]
class Home extends Component
{
    public int $exploreLimit = 8;

    public function addToCart(int $produtoId, CartService $cartService): void
    {
        $cartService->addItem($produtoId, 1);
        $this->dispatch('cart-updated');
        $this->dispatch('toast', message: 'Produto adicionado à sacola!');
    }

    public function toggleFavorite(int $produtoId): void
    {
        $favorites = Session::get('dfv_favorites', []);
        if (in_array($produtoId, $favorites)) {
            $favorites = array_values(array_diff($favorites, [$produtoId]));
            $msg = 'Removido dos favoritos';
        } else {
            $favorites[] = $produtoId;
            $msg = 'Adicionado aos favoritos!';
        }
        Session::put('dfv_favorites', $favorites);
        $this->dispatch('favorites-updated');
        $this->dispatch('toast', message: $msg);
    }

    public function loadMore(): void
    {
        $this->exploreLimit += 8;
    }

    public function render(): View
    {
        $marcas = Marca::ativo()->get();
        $banners = Banner::ativo()->get();
        $flashDeal = Produto::with('marca')->flashDeal()->first();

        $escolhidos = Produto::with('marca')->escolhidos()->limit(10)->get();
        $presentear = Produto::with('marca')->presentear()->limit(10)->get();
        $cabelos = Produto::with('marca')->cabelo()->limit(10)->get();
        $ofertasEspeciais = Produto::with('marca')->promocoes()->limit(10)->get();

        $colecoes = Colecao::ativo()->withCount('produtos')->get();

        // Seções por marca
        $brandSections = Marca::ativo()
            ->whereHas('produtos', fn ($q) => $q->where('ativo', true))
            ->with(['produtos' => fn ($q) => $q->where('ativo', true)->limit(6)])
            ->get();

        $exploreProdutos = Produto::with('marca')
            ->where('ativo', true)
            ->orderBy('id', 'desc')
            ->limit($this->exploreLimit)
            ->get();

        $totalExplore = Produto::where('ativo', true)->count();

        return view('livewire.storefront.home', [
            'marcas' => $marcas,
            'banners' => $banners,
            'flashDeal' => $flashDeal,
            'escolhidos' => $escolhidos,
            'presentear' => $presentear,
            'cabelos' => $cabelos,
            'ofertasEspeciais' => $ofertasEspeciais,
            'colecoes' => $colecoes,
            'brandSections' => $brandSections,
            'exploreProdutos' => $exploreProdutos,
            'hasMore' => $totalExplore > $this->exploreLimit,
            'userFavorites' => Session::get('dfv_favorites', []),
        ]);
    }
}
