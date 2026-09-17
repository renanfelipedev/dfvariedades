<?php

namespace App\Livewire\Storefront;

use App\Models\Produto;
use App\Services\CartService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\On;
use Livewire\Component;

class FavoritesDrawer extends Component
{
    public bool $isOpen = false;

    public Collection $favoriteProducts;

    public function mount(): void
    {
        $this->loadFavorites();
    }

    #[On('open-favorites')]
    public function open(): void
    {
        $this->loadFavorites();
        $this->isOpen = true;
    }

    #[On('close-favorites')]
    public function close(): void
    {
        $this->isOpen = false;
    }

    #[On('favorites-updated')]
    public function loadFavorites(): void
    {
        $favIds = Session::get('dfv_favorites', []);
        $this->favoriteProducts = empty($favIds)
            ? collect()
            : Produto::with('marca')->whereIn('id', $favIds)->where('ativo', true)->get();
    }

    public function removeFavorite(int $produtoId): void
    {
        $favorites = Session::get('dfv_favorites', []);
        $favorites = array_values(array_diff($favorites, [$produtoId]));
        Session::put('dfv_favorites', $favorites);
        $this->loadFavorites();
        $this->dispatch('favorites-updated');
        $this->dispatch('toast', message: 'Removido dos favoritos');
    }

    public function moveToCart(int $produtoId, CartService $cartService): void
    {
        $cartService->addItem($produtoId, 1);
        $this->dispatch('cart-updated');
        $this->dispatch('toast', message: 'Item adicionado à sacola!');
    }

    public function render(): View
    {
        return view('livewire.storefront.favorites-drawer');
    }
}
