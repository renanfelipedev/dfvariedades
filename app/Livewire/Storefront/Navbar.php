<?php

namespace App\Livewire\Storefront;

use App\Models\Colecao;
use App\Models\Marca;
use App\Models\Produto;
use App\Services\CartService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\On;
use Livewire\Component;

class Navbar extends Component
{
    public string $search = '';

    public array $searchResults = [];

    public int $cartCount = 0;

    public int $favoritesCount = 0;

    public bool $menuOpen = false;

    public function mount(CartService $cartService): void
    {
        $this->cartCount = $cartService->getCount();
        $this->favoritesCount = count(Session::get('dfv_favorites', []));
    }

    #[On('cart-updated')]
    public function updateCartCount(CartService $cartService): void
    {
        $this->cartCount = $cartService->getCount();
    }

    #[On('favorites-updated')]
    public function updateFavoritesCount(): void
    {
        $this->favoritesCount = count(Session::get('dfv_favorites', []));
    }

    public function updatedSearch(): void
    {
        $term = trim($this->search);
        if (strlen($term) < 2) {
            $this->searchResults = [];

            return;
        }

        $this->searchResults = Produto::with('marca')
            ->where('ativo', true)
            ->where(function ($q) use ($term) {
                $q->where('nome', 'like', "%{$term}%")
                    ->orWhere('descricao', 'like', "%{$term}%");
            })
            ->limit(6)
            ->get()
            ->map(fn ($p) => [
                'id' => $p->id,
                'nome' => $p->nome,
                'slug' => $p->slug,
                'marca' => $p->marca?->nome ?? '',
                'preco' => $p->preco_final,
                'imagem' => $p->primeira_imagem,
            ])
            ->toArray();
    }

    public function clearSearch(): void
    {
        $this->search = '';
        $this->searchResults = [];
    }

    public function openCart(): void
    {
        $this->dispatch('open-cart');
    }

    public function openFavorites(): void
    {
        $this->dispatch('open-favorites');
    }

    public function toggleMenu(): void
    {
        $this->menuOpen = ! $this->menuOpen;
    }

    public function render(): View
    {
        $marcas = Marca::ativo()->limit(8)->get();
        $colecoes = Colecao::ativo()->limit(6)->get();

        return view('livewire.storefront.navbar', [
            'marcas' => $marcas,
            'colecoes' => $colecoes,
        ]);
    }
}
