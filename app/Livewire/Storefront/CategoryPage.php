<?php

namespace App\Livewire\Storefront;

use App\Models\Categoria;
use App\Models\Colecao;
use App\Models\Marca;
use App\Models\Produto;
use App\Services\CartService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.storefront')]
class CategoryPage extends Component
{
    public ?string $tipo = null; // 'marca', 'colecao', 'categoria', or null for all

    public ?string $slug = null;

    #[Url(as: 'q')]
    public string $search = '';

    #[Url(as: 'sub')]
    public ?int $subcategoriaId = null;

    #[Url(as: 'ordem')]
    public string $sort = 'padrao'; // 'padrao', 'menor_maior', 'maior_menor', 'promocoes'

    public int $limit = 12;

    public function mount(?string $tipo = null, ?string $slug = null): void
    {
        $this->tipo = $tipo;
        $this->slug = $slug;
    }

    public function filterSubcategory(?int $catId): void
    {
        $this->subcategoriaId = $this->subcategoriaId === $catId ? null : $catId;
    }

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
        $this->limit += 12;
    }

    public function render(): View
    {
        $title = 'Todos os Produtos';
        $subcategories = collect();
        $query = Produto::with('marca', 'colecao', 'categoria')->where('ativo', true);

        if ($this->tipo === 'marca' && $this->slug) {
            $marca = Marca::where('slug', $this->slug)->first();
            if ($marca) {
                $title = $marca->nome;
                $query->where('marca_id', $marca->id);
            }
        } elseif ($this->tipo === 'colecao' && $this->slug) {
            $colecao = Colecao::with('categorias')->where('slug', $this->slug)->first();
            if ($colecao) {
                $title = $colecao->nome;
                $query->where('colecao_id', $colecao->id);
                $subcategories = $colecao->categorias()->where('ativo', true)->get();
            }
        } elseif ($this->tipo === 'categoria' && $this->slug) {
            $categoria = Categoria::where('slug', $this->slug)->first();
            if ($categoria) {
                $title = $categoria->nome;
                $query->where('categoria_id', $categoria->id);
            }
        } else {
            $subcategories = Categoria::where('ativo', true)->orderBy('ordem')->get();
        }

        if ($this->subcategoriaId) {
            $query->where('categoria_id', $this->subcategoriaId);
        }

        if ($this->search) {
            $term = $this->search;
            $query->where(function ($q) use ($term) {
                $q->where('nome', 'like', "%{$term}%")
                    ->orWhere('descricao', 'like', "%{$term}%");
            });
        }

        // Ordenação
        match ($this->sort) {
            'menor_maior' => $query->orderByRaw('COALESCE(preco_promocional, preco) ASC'),
            'maior_menor' => $query->orderByRaw('COALESCE(preco_promocional, preco) DESC'),
            'promocoes' => $query->whereNotNull('preco_promocional')->whereColumn('preco_promocional', '<', 'preco')->orderByRaw('(preco - preco_promocional) DESC'),
            default => $query->orderBy('ordem')->orderBy('id', 'desc'),
        };

        $total = (clone $query)->count();
        $produtos = $query->limit($this->limit)->get();

        return view('livewire.storefront.category-page', [
            'pageTitle' => $title,
            'produtos' => $produtos,
            'subcategories' => $subcategories,
            'hasMore' => $total > $this->limit,
            'total' => $total,
            'userFavorites' => Session::get('dfv_favorites', []),
        ]);
    }
}
