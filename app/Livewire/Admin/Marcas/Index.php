<?php

namespace App\Livewire\Admin\Marcas;

use App\Models\Marca;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
#[Title('Gerenciar Marcas')]
class Index extends Component
{
    use WithFileUploads, WithPagination;

    public string $search = '';

    public bool $modalOpen = false;

    public ?int $editingId = null;

    public string $nome = '';

    public string $slug = '';

    public string $logo_url = '';

    /**
     * Upload de arquivo para a logo/imagem da marca
     */
    public $arquivoLogo;

    public string $descricao = '';

    public string $cor = '#C9A84C';

    public int $ordem = 1;

    public bool $ativo = true;

    public bool $destaque = false;

    public function openModal(?int $id = null): void
    {
        $this->reset(['editingId', 'nome', 'slug', 'logo_url', 'arquivoLogo', 'descricao', 'cor', 'ordem', 'ativo', 'destaque']);
        if ($id) {
            $this->editingId = $id;
            $marca = Marca::findOrFail($id);
            $this->nome = $marca->nome;
            $this->slug = $marca->slug;
            $this->logo_url = $marca->logo_url ?? '';
            $this->descricao = $marca->descricao ?? '';
            $this->cor = $marca->cor ?? '#C9A84C';
            $this->ordem = $marca->ordem;
            $this->ativo = $marca->ativo;
            $this->destaque = $marca->destaque;
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
        $this->slug = $this->slug ? Str::slug($this->slug) : Str::slug($this->nome);

        $this->validate([
            'nome' => 'required|min:2',
            'slug' => ['required', Rule::unique('marcas', 'slug')->ignore($this->editingId)],
            'arquivoLogo' => 'nullable|image|max:10240',
        ], [
            'nome.required' => 'Informe o nome da marca',
            'slug.unique' => 'Este slug já está em uso por outra marca.',
            'arquivoLogo.image' => 'O arquivo enviado deve ser uma imagem válida',
        ]);

        $finalLogoUrl = $this->logo_url;

        if ($this->arquivoLogo) {
            $path = $this->arquivoLogo->store('marcas', 'public');
            $finalLogoUrl = Storage::disk('public')->url($path);
        }

        $data = [
            'nome' => $this->nome,
            'slug' => $this->slug ?: Str::slug($this->nome),
            'logo_url' => $finalLogoUrl ?: null,
            'descricao' => $this->descricao ?: null,
            'cor' => $this->cor ?: '#C9A84C',
            'ordem' => $this->ordem,
            'ativo' => $this->ativo,
            'destaque' => $this->destaque,
        ];

        if ($this->editingId) {
            Marca::findOrFail($this->editingId)->update($data);
            $msg = 'Marca atualizada com sucesso!';
        } else {
            Marca::create($data);
            $msg = 'Marca criada com sucesso!';
        }

        $this->modalOpen = false;
        $this->dispatch('toast', message: $msg);
    }

    public function toggleAtivo(int $id): void
    {
        $marca = Marca::find($id);
        if ($marca) {
            $marca->ativo = ! $marca->ativo;
            $marca->save();
            $this->dispatch('toast', message: 'Status da marca atualizado!');
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
            Marca::findOrFail($this->deletingId)->delete();
            $this->deletingId = null;
            $this->dispatch('toast', message: 'Marca removida com sucesso!');
        }
    }

    public function render(): View
    {
        $marcas = Marca::withCount('produtos')
            ->when($this->search, fn ($q) => $q->where('nome', 'like', "%{$this->search}%"))
            ->orderBy('ordem')
            ->paginate(10);

        return view('livewire.admin.marcas.index', [
            'marcas' => $marcas,
        ]);
    }
}
