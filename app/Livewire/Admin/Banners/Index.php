<?php

namespace App\Livewire\Admin\Banners;

use App\Models\Banner;
use App\Models\Colecao;
use App\Models\Marca;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
#[Title('Gerenciar Banners & Destaques')]
class Index extends Component
{
    use WithFileUploads;

    public bool $modalOpen = false;

    public ?int $editingId = null;

    public string $titulo = '';

    public string $tipo_midia = 'imagem';

    public string $url_midia = '';

    /**
     * Upload de arquivo de imagem/mídia para o banner
     */
    public $arquivoMidia;

    public string $link_tipo = 'colecao'; // colecao, marca, produto, url

    public string $link_id = '';

    public string $link_url = '';

    public int $ordem = 1;

    public bool $ativo = true;

    public function openModal(?int $id = null): void
    {
        $this->reset(['editingId', 'titulo', 'tipo_midia', 'url_midia', 'arquivoMidia', 'link_tipo', 'link_id', 'link_url', 'ordem', 'ativo']);
        if ($id) {
            $this->editingId = $id;
            $b = Banner::findOrFail($id);
            $this->titulo = $b->titulo ?? '';
            $this->tipo_midia = $b->tipo_midia;
            $this->url_midia = $b->url_midia;
            $this->link_tipo = $b->link_tipo ?? 'colecao';
            $this->link_id = (string) ($b->link_id ?? '');
            $this->link_url = $b->link_url ?? '';
            $this->ordem = $b->ordem;
            $this->ativo = $b->ativo;
        }
        $this->modalOpen = true;
    }

    public function closeModal(): void
    {
        $this->modalOpen = false;
        $this->deletingId = null;
    }

    public function save(): void
    {
        $rules = [
            'arquivoMidia' => 'nullable|file|max:15360', // max 15MB
        ];

        if (! $this->arquivoMidia && empty($this->url_midia)) {
            $rules['url_midia'] = 'required';
        }

        $this->validate($rules, [
            'url_midia.required' => 'Envie uma imagem do banner ou informe uma URL',
            'arquivoMidia.max' => 'O arquivo não pode ultrapassar 15MB',
        ]);

        $finalUrl = $this->url_midia;

        if ($this->arquivoMidia) {
            $path = $this->arquivoMidia->store('banners', 'public');
            $finalUrl = Storage::disk('public')->url($path);
        }

        $data = [
            'titulo' => $this->titulo ?: null,
            'tipo_midia' => $this->tipo_midia,
            'url_midia' => $finalUrl,
            'link_tipo' => $this->link_tipo,
            'link_id' => $this->link_id ?: null,
            'link_url' => $this->link_url ?: null,
            'ordem' => $this->ordem,
            'ativo' => $this->ativo,
        ];

        if ($this->editingId) {
            Banner::findOrFail($this->editingId)->update($data);
            $msg = 'Banner atualizado com sucesso!';
        } else {
            Banner::create($data);
            $msg = 'Banner criado com sucesso!';
        }

        $this->modalOpen = false;
        $this->dispatch('toast', message: $msg);
    }

    public function toggleAtivo(int $id): void
    {
        $banner = Banner::find($id);
        if ($banner) {
            $banner->ativo = ! $banner->ativo;
            $banner->save();
            $this->dispatch('toast', message: 'Status do banner atualizado!');
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
            Banner::findOrFail($this->deletingId)->delete();
            $this->deletingId = null;
            $this->dispatch('toast', message: 'Banner removido com sucesso!');
        }
    }

    public function render(): View
    {
        $banners = Banner::orderBy('ordem')->get();
        $marcas = Marca::ativo()->get();
        $colecoes = Colecao::ativo()->get();

        return view('livewire.admin.banners.index', [
            'banners' => $banners,
            'marcas' => $marcas,
            'colecoes' => $colecoes,
        ]);
    }
}
