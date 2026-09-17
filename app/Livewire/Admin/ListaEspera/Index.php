<?php

namespace App\Livewire\Admin\ListaEspera;

use App\Models\ListaEspera;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.admin')]
class Index extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public string $search = '';

    #[Url(history: true)]
    public string $estado = '';

    public ?int $deletingId = null;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingEstado(): void
    {
        $this->resetPage();
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
    }

    public function cancelDelete(): void
    {
        $this->deletingId = null;
    }

    public function closeModal(): void
    {
        $this->deletingId = null;
    }

    public function delete(): void
    {
        if ($this->deletingId) {
            ListaEspera::findOrFail($this->deletingId)->delete();
            $this->deletingId = null;
            session()->flash('message', 'Lead da lista de espera removido com sucesso.');
        }
    }

    public function render()
    {
        $query = ListaEspera::query()
            ->when($this->search, function ($q) {
                $s = '%'.$this->search.'%';
                $q->where(function ($sub) use ($s) {
                    $sub->where('nome', 'like', $s)
                        ->orWhere('whatsapp', 'like', $s)
                        ->orWhere('email', 'like', $s)
                        ->orWhere('cidade', 'like', $s);
                });
            })
            ->when($this->estado, function ($q) {
                $q->where('estado', $this->estado);
            })
            ->latest();

        $leads = $query->paginate(15);

        $totalLeads = ListaEspera::count();
        $totalDF = ListaEspera::where('estado', 'DF')->count();
        $estados = ListaEspera::select('estado')->whereNotNull('estado')->distinct()->pluck('estado')->filter();

        return view('livewire.admin.lista-espera.index', [
            'leads' => $leads,
            'totalLeads' => $totalLeads,
            'totalDF' => $totalDF,
            'estados' => $estados,
        ])->title('Lista de Espera & Leads - Painel DF Variedades');
    }
}
