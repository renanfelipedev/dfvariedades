<?php

namespace App\Services;

use App\Models\Produto;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected const SESSION_KEY = 'dfv_cart';

    /**
     * Retorna a lista de itens no carrinho com os modelos de Produto carregados.
     */
    public function getCart(): array
    {
        $sessionItems = Session::get(self::SESSION_KEY, []);
        if (empty($sessionItems)) {
            return [];
        }

        $produtoIds = array_keys($sessionItems);
        $produtos = Produto::with('marca', 'colecao')->whereIn('id', $produtoIds)->get()->keyBy('id');

        $cart = [];
        foreach ($sessionItems as $id => $itemData) {
            /** @var Produto|null $produto */
            $produto = $produtos->get($id);
            if (! $produto || ! $produto->ativo) {
                continue;
            }

            $qtd = max(1, min((int) ($itemData['quantidade'] ?? 1), $produto->estoque));
            $precoUnitario = $produto->preco_final;
            $precoOriginal = (float) $produto->preco;
            $subtotal = $precoUnitario * $qtd;
            $subtotalOriginal = $precoOriginal * $qtd;
            $economia = max(0, $subtotalOriginal - $subtotal);

            $cart[$id] = [
                'id' => $produto->id,
                'produto' => $produto,
                'nome' => $produto->nome,
                'marca' => $produto->marca?->nome ?? '',
                'imagem' => $produto->primeira_imagem,
                'preco' => $precoUnitario,
                'preco_original' => $precoOriginal,
                'quantidade' => $qtd,
                'subtotal' => $subtotal,
                'economia' => $economia,
                'estoque' => $produto->estoque,
            ];
        }

        return $cart;
    }

    /**
     * Adiciona um item ao carrinho
     */
    public function addItem(int $produtoId, int $quantidade = 1): array
    {
        $sessionItems = Session::get(self::SESSION_KEY, []);
        $produto = Produto::find($produtoId);

        if (! $produto || ! $produto->ativo || $produto->estoque <= 0) {
            return $this->getCart();
        }

        $atual = $sessionItems[$produtoId]['quantidade'] ?? 0;
        $novaQtd = min($atual + $quantidade, $produto->estoque);

        $sessionItems[$produtoId] = [
            'quantidade' => max(1, $novaQtd),
        ];

        Session::put(self::SESSION_KEY, $sessionItems);

        return $this->getCart();
    }

    /**
     * Altera a quantidade de um item
     */
    public function updateQuantity(int $produtoId, int $quantidade): array
    {
        $sessionItems = Session::get(self::SESSION_KEY, []);

        if ($quantidade <= 0) {
            return $this->removeItem($produtoId);
        }

        $produto = Produto::find($produtoId);
        if ($produto) {
            $sessionItems[$produtoId] = [
                'quantidade' => min($quantidade, $produto->estoque),
            ];
            Session::put(self::SESSION_KEY, $sessionItems);
        }

        return $this->getCart();
    }

    /**
     * Remove um item do carrinho
     */
    public function removeItem(int $produtoId): array
    {
        $sessionItems = Session::get(self::SESSION_KEY, []);
        unset($sessionItems[$produtoId]);
        Session::put(self::SESSION_KEY, $sessionItems);

        return $this->getCart();
    }

    /**
     * Limpa todo o carrinho
     */
    public function clear(): void
    {
        Session::forget(self::SESSION_KEY);
    }

    /**
     * Retorna a contagem total de itens no carrinho
     */
    public function getCount(): int
    {
        $cart = $this->getCart();
        $count = 0;
        foreach ($cart as $item) {
            $count += $item['quantidade'];
        }

        return $count;
    }

    /**
     * Retorna o subtotal do carrinho
     */
    public function getSubtotal(): float
    {
        $cart = $this->getCart();
        $subtotal = 0.0;
        foreach ($cart as $item) {
            $subtotal += $item['subtotal'];
        }

        return $subtotal;
    }

    /**
     * Retorna a economia total do carrinho em promoções
     */
    public function getEconomia(): float
    {
        $cart = $this->getCart();
        $economia = 0.0;
        foreach ($cart as $item) {
            $economia += $item['economia'];
        }

        return $economia;
    }

    /**
     * Calcula o resumo final de valores
     */
    public function getTotals(float $frete = 0.0, float $taxaPagamento = 0.0, float $desconto = 0.0): array
    {
        $subtotal = $this->getSubtotal();
        $total = max(0, $subtotal + $frete + $taxaPagamento - $desconto);

        return [
            'subtotal' => $subtotal,
            'frete' => $frete,
            'taxa_pagamento' => $taxaPagamento,
            'desconto' => $desconto,
            'total' => $total,
            'economia' => $this->getEconomia(),
        ];
    }
}
