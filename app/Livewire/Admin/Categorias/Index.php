<?php

namespace App\Livewire\Admin\Categorias;

use App\Models\Categoria;
use App\Models\Colecao;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Gerenciar Categorias')]
class Index extends Component
{
    use WithPagination;

    public string $search = '';

    public ?int $filterColecaoId = null;

    public bool $modalOpen = false;

    public ?int $editingId = null;

    public ?int $colecao_id = null;

    public string $nome = '';

    public string $slug = '';

    public string $descricao = '';

    public int $ordem = 1;

    public bool $ativo = true;

    public function openModal(?int $id = null): void
    {
        $this->reset(['editingId', 'colecao_id', 'nome', 'slug', 'descricao', 'ordem', 'ativo']);
        if ($id) {
            $this->editingId = $id;
            $cat = Categoria::findOrFail($id);
            $this->colecao_id = $cat->colecao_id;
            $this->nome = $cat->nome;
            $this->slug = $cat->slug;
            $this->descricao = $cat->descricao ?? '';
            $this->ordem = $cat->ordem;
            $this->ativo = $cat->ativo;
        }
        $this->modalOpen = true;
    }

    public function closeModal(): void
    {
        $this->modalOpen = false;
        $this->deletingId = null;
    }

    public function updatedNome(string $value): void
    {
        if (! $this->editingId) {
            $this->slug = Str::slug($value);
        }
    }

    public function save(): void
    {
        $this->validate([
            'nome' => 'required|min:2',
            'slug' => 'required|unique:categorias,slug,'.($this->editingId ?: 'NULL').',id',
        ]);

        $data = [
            'colecao_id' => $this->colecao_id ?: null,
            'nome' => $this->nome,
            'slug' => $this->slug ?: Str::slug($this->nome),
            'descricao' => $this->descricao ?: null,
            'ordem' => $this->ordem,
            'ativo' => $this->ativo,
        ];

        if ($this->editingId) {
            Categoria::findOrFail($this->editingId)->update($data);
            $msg = 'Categoria atualizada com sucesso!';
        } else {
            Categoria::create($data);
            $msg = 'Categoria criada com sucesso!';
        }

        $this->modalOpen = false;
        $this->dispatch('toast', message: $msg);
    }

    public function toggleAtivo(int $id): void
    {
        $cat = Categoria::find($id);
        if ($cat) {
            $cat->ativo = ! $cat->ativo;
            $cat->save();
            $this->dispatch('toast', message: 'Status da categoria atualizado!');
        }
    }

    public ?int $deletingId = null;

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
    }

    public function cancelDelete(): void
    {
        $this->deletingId = null;
    }

    public function delete(): void
    {
        if ($this->deletingId) {
            Categoria::findOrFail($this->deletingId)->delete();
            $this->deletingId = null;
            $this->dispatch('toast', message: 'Categoria removida com sucesso!');
        }
    }

    public function render(): View
    {
        $categorias = Categoria::with('colecao')
            ->withCount('produtos')
            ->when($this->search, fn ($q) => $q->where('nome', 'like', "%{$this->search}%"))
            ->when($this->filterColecaoId, fn ($q) => $q->where('colecao_id', $this->filterColecaoId))
            ->orderBy('ordem')
            ->paginate(12);

        $colecoes = Colecao::orderBy('nome')->get();

        return view('livewire.admin.categorias.index', [
            'categorias' => $categorias,
            'colecoes' => $colecoes,
        ]);
    }
}
