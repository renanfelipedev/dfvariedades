<?php

namespace App\Livewire\Admin\Produtos;

use App\Models\Colecao;
use App\Models\Marca;
use App\Models\Produto;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Gerenciar Produtos')]
class Index extends Component
{
    use WithPagination;

    #[Url(as: 'busca')]
    public string $search = '';

    #[Url(as: 'marca')]
    public ?int $marcaId = null;

    #[Url(as: 'colecao')]
    public ?int $colecaoId = null;

    #[Url(as: 'estoque')]
    public string $estoqueFilter = ''; // '' = todos, 'baixo' = estoque baixo, 'zerado' = esgotados

    public int $limiteEstoque = 5;

    public ?int $produtoToDelete = null;

    public function mount(): void
    {
        $this->limiteEstoque = (int) session('dfv_estoque_limite', 5);
    }

    public function setLimiteEstoque(int $valor): void
    {
        $this->limiteEstoque = max(1, $valor);
        session(['dfv_estoque_limite' => $this->limiteEstoque]);
        $this->dispatch('toast', message: "Alerta de estoque atualizado para ≤ {$this->limiteEstoque} unidades!");
    }

    public function updatedLimiteEstoque(mixed $value): void
    {
        $this->limiteEstoque = max(1, (int) $value);
        session(['dfv_estoque_limite' => $this->limiteEstoque]);
        $this->dispatch('toast', message: "Alerta de estoque atualizado para ≤ {$this->limiteEstoque} unidades!");
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingMarcaId(): void
    {
        $this->resetPage();
    }

    public function updatingColecaoId(): void
    {
        $this->resetPage();
    }

    public function updatingEstoqueFilter(): void
    {
        $this->resetPage();
    }

    public function toggleAtivo(int $id): void
    {
        $produto = Produto::find($id);
        if ($produto) {
            $produto->ativo = ! $produto->ativo;
            $produto->save();
            $this->dispatch('toast', message: 'Status do produto atualizado!');
        }
    }

    public function toggleDestaque(int $id): void
    {
        $produto = Produto::find($id);
        if ($produto) {
            $produto->destaque = ! $produto->destaque;
            $produto->save();
            $this->dispatch('toast', message: 'Destaque atualizado!');
        }
    }

    public function confirmDelete(int $id): void
    {
        $this->produtoToDelete = $id;
    }

    public function cancelDelete(): void
    {
        $this->produtoToDelete = null;
    }

    public function closeModal(): void
    {
        $this->produtoToDelete = null;
    }

    public function delete(): void
    {
        if ($this->produtoToDelete) {
            $produto = Produto::find($this->produtoToDelete);
            if ($produto) {
                $produto->delete();
                $this->dispatch('toast', message: 'Produto removido com sucesso!');
            }
            $this->produtoToDelete = null;
        }
    }

    public function render(): View
    {
        $query = Produto::with('marca', 'colecao', 'categoria');

        if ($this->search) {
            $term = $this->search;
            $query->where(function ($q) use ($term) {
                $q->where('nome', 'like', "%{$term}%")
                    ->orWhere('sku', 'like', "%{$term}%")
                    ->orWhere('descricao', 'like', "%{$term}%");
            });
        }

        if ($this->marcaId) {
            $query->where('marca_id', $this->marcaId);
        }

        if ($this->colecaoId) {
            $query->where('colecao_id', $this->colecaoId);
        }

        if ($this->estoqueFilter === 'baixo') {
            $query->estoqueBaixo($this->limiteEstoque)->where('estoque', '>', 0);
        } elseif ($this->estoqueFilter === 'zerado') {
            $query->where('estoque', '<=', 0);
        }

        $totalTodos = Produto::count();
        $totalBaixo = Produto::estoqueBaixo($this->limiteEstoque)->where('estoque', '>', 0)->count();
        $totalZerado = Produto::where('estoque', '<=', 0)->count();

        $produtos = $query->orderBy('id', 'desc')->paginate(12);
        $marcas = Marca::orderBy('nome')->get();
        $colecoes = Colecao::orderBy('nome')->get();

        return view('livewire.admin.produtos.index', [
            'produtos' => $produtos,
            'marcas' => $marcas,
            'colecoes' => $colecoes,
            'limiteEstoque' => $this->limiteEstoque,
            'totalTodos' => $totalTodos,
            'totalBaixo' => $totalBaixo,
            'totalZerado' => $totalZerado,
        ]);
    }
}
