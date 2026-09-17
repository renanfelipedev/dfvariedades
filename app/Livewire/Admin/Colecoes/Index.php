<?php

namespace App\Livewire\Admin\Colecoes;

use App\Models\Colecao;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Gerenciar Coleções')]
class Index extends Component
{
    use WithFileUploads, WithPagination;

    public string $search = '';

    public bool $modalOpen = false;

    public ?int $editingId = null;

    public string $nome = '';

    public string $slug = '';

    public string $imagem_url = '';

    /**
     * Upload de arquivo para a imagem/capa da coleção
     */
    public $arquivoImagem;

    public string $banner_url = '';

    /**
     * Upload de arquivo para o banner da coleção
     */
    public $arquivoBanner;

    public string $descricao = '';

    public int $ordem = 1;

    public bool $ativo = true;

    public bool $destaque = true;

    public function openModal(?int $id = null): void
    {
        $this->reset(['editingId', 'nome', 'slug', 'imagem_url', 'arquivoImagem', 'banner_url', 'arquivoBanner', 'descricao', 'ordem', 'ativo', 'destaque']);
        if ($id) {
            $this->editingId = $id;
            $colecao = Colecao::findOrFail($id);
            $this->nome = $colecao->nome;
            $this->slug = $colecao->slug;
            $this->imagem_url = $colecao->imagem_url ?? '';
            $this->banner_url = $colecao->banner_url ?? '';
            $this->descricao = $colecao->descricao ?? '';
            $this->ordem = $colecao->ordem;
            $this->ativo = $colecao->ativo;
            $this->destaque = $colecao->destaque;
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
            'slug' => 'required|unique:colecoes,slug,'.($this->editingId ?: 'NULL').',id',
            'arquivoImagem' => 'nullable|image|max:10240',
            'arquivoBanner' => 'nullable|image|max:15360',
        ]);

        $finalImagemUrl = $this->imagem_url;
        if ($this->arquivoImagem) {
            $path = $this->arquivoImagem->store('colecoes', 'public');
            $finalImagemUrl = Storage::disk('public')->url($path);
        }

        $finalBannerUrl = $this->banner_url;
        if ($this->arquivoBanner) {
            $pathBanner = $this->arquivoBanner->store('colecoes', 'public');
            $finalBannerUrl = Storage::disk('public')->url($pathBanner);
        }

        $data = [
            'nome' => $this->nome,
            'slug' => $this->slug ?: Str::slug($this->nome),
            'imagem_url' => $finalImagemUrl ?: null,
            'banner_url' => $finalBannerUrl ?: null,
            'descricao' => $this->descricao ?: null,
            'ordem' => $this->ordem,
            'ativo' => $this->ativo,
            'destaque' => $this->destaque,
        ];

        if ($this->editingId) {
            Colecao::findOrFail($this->editingId)->update($data);
            $msg = 'Coleção atualizada com sucesso!';
        } else {
            Colecao::create($data);
            $msg = 'Coleção criada com sucesso!';
        }

        $this->modalOpen = false;
        $this->dispatch('toast', message: $msg);
    }

    public function toggleAtivo(int $id): void
    {
        $colecao = Colecao::find($id);
        if ($colecao) {
            $colecao->ativo = ! $colecao->ativo;
            $colecao->save();
            $this->dispatch('toast', message: 'Status da coleção atualizado!');
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
            Colecao::findOrFail($this->deletingId)->delete();
            $this->deletingId = null;
            $this->dispatch('toast', message: 'Coleção removida com sucesso!');
        }
    }

    public function render(): View
    {
        $colecoes = Colecao::withCount('produtos')
            ->when($this->search, fn ($q) => $q->where('nome', 'like', "%{$this->search}%"))
            ->orderBy('ordem')
            ->paginate(10);

        return view('livewire.admin.colecoes.index', [
            'colecoes' => $colecoes,
        ]);
    }
}
