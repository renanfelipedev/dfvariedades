<?php

namespace App\Livewire\Admin\Users;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
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
    public string $role = '';

    public bool $showModal = false;

    public ?int $editingId = null;

    public ?int $deletingId = null;

    public string $name = '';

    public string $email = '';

    public string $userRole = User::ROLE_ATENDENTE;

    public string $password = '';

    public string $password_confirmation = '';

    public function mount(): void
    {
        // Enforce admin-only access
        if (! Auth::user()?->isAdmin()) {
            abort(403, 'Acesso restrito a Administradores.');
        }
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingRole(): void
    {
        $this->resetPage();
    }

    public function openCreateModal(): void
    {
        $this->reset(['editingId', 'name', 'email', 'userRole', 'password', 'password_confirmation']);
        $this->userRole = User::ROLE_ATENDENTE;
        $this->showModal = true;
    }

    public function openEditModal(int $id): void
    {
        $user = User::findOrFail($id);
        $this->editingId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->userRole = $user->role;
        $this->password = '';
        $this->password_confirmation = '';
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->reset(['editingId', 'name', 'email', 'userRole', 'password', 'password_confirmation']);
    }

    public function save(): void
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->editingId),
            ],
            'userRole' => ['required', Rule::in([User::ROLE_ADMIN, User::ROLE_GERENTE, User::ROLE_ATENDENTE, User::ROLE_CLIENTE])],
        ];

        if (! $this->editingId || ! empty($this->password)) {
            $rules['password'] = [$this->editingId ? 'nullable' : 'required', 'string', 'min:6', 'confirmed'];
        }

        $this->validate($rules);

        if ($this->editingId) {
            $user = User::findOrFail($this->editingId);

            // Prevent changing the role of oneself if it would revoke admin status
            if ($user->id === Auth::id() && $this->userRole !== User::ROLE_ADMIN) {
                session()->flash('error', 'Você não pode remover suas próprias permissões de Administrador.');

                return;
            }

            $updateData = [
                'name' => $this->name,
                'email' => $this->email,
                'role' => $this->userRole,
            ];

            if (! empty($this->password)) {
                $updateData['password'] = Hash::make($this->password);
            }

            $user->update($updateData);
            session()->flash('message', 'Usuário atualizado com sucesso!');
        } else {
            User::create([
                'name' => $this->name,
                'email' => $this->email,
                'role' => $this->userRole,
                'password' => Hash::make($this->password),
            ]);
            session()->flash('message', 'Novo usuário criado com sucesso!');
        }

        $this->closeModal();
    }

    public function confirmDelete(int $id): void
    {
        if ($id === Auth::id()) {
            session()->flash('error', 'Você não pode excluir sua própria conta de usuário.');

            return;
        }

        $this->deletingId = $id;
    }

    public function cancelDelete(): void
    {
        $this->deletingId = null;
    }

    public function delete(): void
    {
        if ($this->deletingId) {
            if ($this->deletingId === Auth::id()) {
                session()->flash('error', 'Você não pode excluir sua própria conta.');
                $this->deletingId = null;

                return;
            }

            User::findOrFail($this->deletingId)->delete();
            $this->deletingId = null;
            session()->flash('message', 'Usuário removido com sucesso!');
        }
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($q) {
                $s = '%'.$this->search.'%';
                $q->where(function ($sub) use ($s) {
                    $sub->where('name', 'like', $s)
                        ->orWhere('email', 'like', $s);
                });
            })
            ->when($this->role, function ($q) {
                $q->where('role', $this->role);
            })
            ->orderBy('id', 'desc')
            ->paginate(15);

        $counts = [
            'total' => User::count(),
            'admin' => User::where('role', User::ROLE_ADMIN)->count(),
            'gerente' => User::where('role', User::ROLE_GERENTE)->count(),
            'atendente' => User::where('role', User::ROLE_ATENDENTE)->count(),
            'cliente' => User::where('role', User::ROLE_CLIENTE)->count(),
        ];

        return view('livewire.admin.users.index', [
            'users' => $users,
            'counts' => $counts,
        ])->title('Usuários & Permissões (ACL) - Painel DF Variedades');
    }
}
