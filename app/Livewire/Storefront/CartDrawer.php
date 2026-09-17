<?php

namespace App\Livewire\Storefront;

use App\Services\CartService;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\On;
use Livewire\Component;

class CartDrawer extends Component
{
    public bool $isOpen = false;

    public array $cart = [];

    public float $subtotal = 0.0;

    public float $economia = 0.0;

    public int $count = 0;

    public function mount(CartService $cartService): void
    {
        $this->refreshCart($cartService);
    }

    #[On('open-cart')]
    public function open(CartService $cartService): void
    {
        $this->refreshCart($cartService);
        $this->isOpen = true;
    }

    #[On('close-cart')]
    public function close(): void
    {
        $this->isOpen = false;
    }

    #[On('cart-updated')]
    public function refreshCart(CartService $cartService): void
    {
        $this->cart = $cartService->getCart();
        $this->subtotal = $cartService->getSubtotal();
        $this->economia = $cartService->getEconomia();
        $this->count = $cartService->getCount();
    }

    public function incrementQty(int $produtoId, CartService $cartService): void
    {
        $current = $this->cart[$produtoId]['quantidade'] ?? 1;
        $cartService->updateQuantity($produtoId, $current + 1);
        $this->refreshCart($cartService);
        $this->dispatch('cart-updated');
    }

    public function decrementQty(int $produtoId, CartService $cartService): void
    {
        $current = $this->cart[$produtoId]['quantidade'] ?? 1;
        $cartService->updateQuantity($produtoId, $current - 1);
        $this->refreshCart($cartService);
        $this->dispatch('cart-updated');
    }

    public function removeItem(int $produtoId, CartService $cartService): void
    {
        $cartService->removeItem($produtoId);
        $this->refreshCart($cartService);
        $this->dispatch('cart-updated');
        $this->dispatch('toast', message: 'Item removido da sacola');
    }

    public function proceedToCheckout(): void
    {
        if (empty($this->cart)) {
            $this->dispatch('toast', message: 'Sua sacola está vazia!');

            return;
        }

        $this->isOpen = false;
        $this->dispatch('open-checkout');
    }

    public function render(): View
    {
        return view('livewire.storefront.cart-drawer');
    }
}
