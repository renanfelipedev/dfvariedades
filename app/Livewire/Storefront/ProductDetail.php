<?php

namespace App\Livewire\Storefront;

use App\Models\Produto;
use App\Services\CartService;
use App\Services\ShippingService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.storefront')]
class ProductDetail extends Component
{
    public Produto $produto;

    public int $quantidade = 1;

    public string $cep = '';

    public ?array $shippingOptions = null;

    public bool $isShippingCalculated = false;

    public string $selectedImage = '';

    public function mount(string $slug): void
    {
        $this->produto = Produto::with('marca', 'colecao', 'categoria')
            ->where('slug', $slug)
            ->where('ativo', true)
            ->firstOrFail();

        $this->selectedImage = $this->produto->primeira_imagem;
    }

    public function incrementQty(): void
    {
        if ($this->quantidade < $this->produto->estoque) {
            $this->quantidade++;
        }
    }

    public function decrementQty(): void
    {
        if ($this->quantidade > 1) {
            $this->quantidade--;
        }
    }

    public function selectImage(string $imgUrl): void
    {
        $this->selectedImage = $imgUrl;
    }

    public function calculateShipping(ShippingService $shippingService): void
    {
        $cleanCep = preg_replace('/\D/', '', $this->cep);
        if (strlen($cleanCep) < 8) {
            $this->dispatch('toast', message: 'Digite um CEP válido com 8 dígitos');

            return;
        }

        $subtotal = $this->produto->preco_final * $this->quantidade;
        $this->shippingOptions = $shippingService->calculateShipping($cleanCep, 'Irecê', $subtotal);
        $this->isShippingCalculated = true;
    }

    public function addToCart(CartService $cartService): void
    {
        $cartService->addItem($this->produto->id, $this->quantidade);
        $this->dispatch('cart-updated');
        $this->dispatch('toast', message: "{$this->quantidade}x {$this->produto->nome} adicionado(s) à sacola!");
    }

    public function buyNow(CartService $cartService): void
    {
        $cartService->addItem($this->produto->id, $this->quantidade);
        $this->dispatch('cart-updated');
        $this->dispatch('open-cart');
    }

    public function toggleFavorite(): void
    {
        $favorites = Session::get('dfv_favorites', []);
        if (in_array($this->produto->id, $favorites)) {
            $favorites = array_values(array_diff($favorites, [$this->produto->id]));
            $msg = 'Removido dos favoritos';
        } else {
            $favorites[] = $this->produto->id;
            $msg = 'Adicionado aos favoritos!';
        }
        Session::put('dfv_favorites', $favorites);
        $this->dispatch('favorites-updated');
        $this->dispatch('toast', message: $msg);
    }

    public function render(): View
    {
        $recommendations = Produto::with('marca')
            ->where('ativo', true)
            ->where('id', '!=', $this->produto->id)
            ->where(function ($q) {
                if ($this->produto->marca_id) {
                    $q->orWhere('marca_id', $this->produto->marca_id);
                }
                if ($this->produto->colecao_id) {
                    $q->orWhere('colecao_id', $this->produto->colecao_id);
                }
            })
            ->limit(6)
            ->get();

        $favorites = Session::get('dfv_favorites', []);
        $isFav = in_array($this->produto->id, $favorites);

        return view('livewire.storefront.product-detail', [
            'recommendations' => $recommendations,
            'isFav' => $isFav,
            'userFavorites' => $favorites,
        ]);
    }
}
