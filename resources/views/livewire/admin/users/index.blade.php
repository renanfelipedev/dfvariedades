<div class="space-y-6" @keydown.window.escape="$wire.cancelDelete(); $wire.closeModal()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-zinc-900 dark:text-white">Usuários & Permissões (ACL)</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Gerencie a equipe da loja e defina os níveis de acesso ao sistema.</p>
        </div>
        <button 
            type="button" 
            wire:click="openCreateModal"
            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-2xl bg-[#C9A84C] hover:bg-[#D4A843] text-black font-bold text-xs shadow-md transition cursor-pointer self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            <span>Novo Usuário / Membro</span>
        </button>
    </div>

    <!-- Role Badges KPI Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-4 shadow-xs">
            <span class="text-xs font-bold uppercase tracking-wider text-rose-500">Administradores</span>
            <div class="text-2xl font-black text-zinc-900 dark:text-white mt-1">{{ $counts['admin'] }}</div>
            <p class="text-[11px] text-zinc-400 mt-0.5">Acesso total ao sistema</p>
        </div>
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-4 shadow-xs">
            <span class="text-xs font-bold uppercase tracking-wider text-indigo-500">Gerentes Catálogo</span>
            <div class="text-2xl font-black text-zinc-900 dark:text-white mt-1">{{ $counts['gerente'] }}</div>
            <p class="text-[11px] text-zinc-400 mt-0.5">Produtos, marcas e banners</p>
        </div>
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-4 shadow-xs">
            <span class="text-xs font-bold uppercase tracking-wider text-emerald-500">Atendentes</span>
            <div class="text-2xl font-black text-zinc-900 dark:text-white mt-1">{{ $counts['atendente'] }}</div>
            <p class="text-[11px] text-zinc-400 mt-0.5">Pedidos e lista de espera</p>
        </div>
        <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-4 shadow-xs">
            <span class="text-xs font-bold uppercase tracking-wider text-zinc-500">Clientes Registrados</span>
            <div class="text-2xl font-black text-zinc-900 dark:text-white mt-1">{{ $counts['cliente'] }}</div>
            <p class="text-[11px] text-zinc-400 mt-0.5">Sem acesso administrativo</p>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-4 shadow-xs">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="sm:col-span-2 relative">
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search"
                    placeholder="Buscar usuário por nome ou e-mail..." 
                    class="w-full pl-10 pr-4 py-2.5 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl text-sm text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-hidden focus:ring-2 focus:ring-primary-500 transition"
                >
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-zinc-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <div>
                <select 
                    wire:model.live="role"
                    class="w-full px-3 py-2.5 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl text-sm text-zinc-900 dark:text-white focus:outline-hidden focus:ring-2 focus:ring-primary-500 transition">
                    <option value="">Todos os Papéis / Funções</option>
                    <option value="admin">Administrador Geral</option>
                    <option value="gerente">Gerente de Catálogo</option>
                    <option value="atendente">Atendente de Pedidos</option>
                    <option value="cliente">Cliente da Loja</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Users Table -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/50 text-[11px] font-bold uppercase tracking-wider text-zinc-400">
                        <th class="py-3 px-4">Usuário</th>
                        <th class="py-3 px-4">E-mail</th>
                        <th class="py-3 px-4">Papel (ACL)</th>
                        <th class="py-3 px-4">Data Cadastro</th>
                        <th class="py-3 px-4 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800 text-sm">
                    @forelse($users as $u)
                        @php
                            $roleBadges = [
                                'admin' => 'bg-rose-500/10 text-rose-500 border-rose-500/20',
                                'gerente' => 'bg-indigo-500/10 text-indigo-500 border-indigo-500/20',
                                'atendente' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                                'cliente' => 'bg-zinc-500/10 text-zinc-500 border-zinc-500/20',
                            ];
                            $roleLabels = [
                                'admin' => '👑 Administrador',
                                'gerente' => '📦 Gerente Catálogo',
                                'atendente' => '🎧 Atendente Pedidos',
                                'cliente' => '👤 Cliente',
                            ];
                            $badge = $roleBadges[$u->role] ?? 'bg-zinc-500/10 text-zinc-500 border-zinc-500/20';
                            $label = $roleLabels[$u->role] ?? $u->role;
                        @endphp
                        <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/40 transition">
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-xl bg-primary-500/10 text-primary-600 dark:text-primary-400 font-black text-xs flex items-center justify-center flex-shrink-0">
                                        {{ $u->initials() }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-zinc-900 dark:text-white flex items-center gap-2">
                                            <span>{{ $u->name }}</span>
                                            @if($u->id === auth()->id())
                                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-md bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-300">Você</span>
                                            @endif
                                        </div>
                                        <span class="text-xs text-zinc-400 font-mono">ID: #{{ $u->id }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-4 text-xs font-mono text-zinc-600 dark:text-zinc-300">
                                {{ $u->email }}
                            </td>
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold border {{ $badge }}">
                                    {{ $label }}
                                </span>
                            </td>
                            <td class="py-4 px-4 text-xs text-zinc-500 dark:text-zinc-400 whitespace-nowrap">
                                {{ $u->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-4 px-4 text-right">
                                <div class="flex items-center justify-end gap-1">
                                    <button 
                                        type="button" 
                                        wire:click="openEditModal({{ $u->id }})"
                                        class="p-2 rounded-xl text-zinc-400 hover:text-primary-500 hover:bg-primary-500/10 transition"
                                        title="Editar Usuário / Papel">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>
                                    @if($u->id !== auth()->id())
                                        <button 
                                            type="button" 
                                            wire:click="confirmDelete({{ $u->id }})"
                                            class="p-2 rounded-xl text-zinc-400 hover:text-rose-500 hover:bg-rose-500/10 transition"
                                            title="Excluir Usuário">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center text-zinc-500 dark:text-zinc-400">
                                Nenhum usuário encontrado para os critérios pesquisados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="p-4 border-t border-zinc-100 dark:border-zinc-800">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- Create / Edit Modal -->
    @if($showModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl p-6 max-w-lg w-full shadow-2xl space-y-5">
                <div class="flex items-center justify-between pb-3 border-b border-zinc-100 dark:border-zinc-800">
                    <h3 class="text-lg font-bold text-zinc-900 dark:text-white">
                        {{ $editingId ? 'Editar Usuário & Permissão' : 'Novo Usuário do Sistema' }}
                    </h3>
                    <button type="button" wire:click="closeModal" class="text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-200">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form wire:submit.prevent="save" class="space-y-4">
                    <div>
                        <label for="user-name" class="block text-xs font-bold uppercase tracking-wider text-zinc-400 mb-1 cursor-pointer">Nome Completo *</label>
                        <input 
                            id="user-name"
                            type="text" 
                            wire:model="name"
                            placeholder="Ex: João Silva"
                            class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl text-sm text-zinc-900 dark:text-white focus:outline-hidden focus:ring-2 focus:ring-primary-500"
                        >
                        @error('name') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="user-email" class="block text-xs font-bold uppercase tracking-wider text-zinc-400 mb-1 cursor-pointer">E-mail de Acesso *</label>
                        <input 
                            id="user-email"
                            type="email" 
                            wire:model="email"
                            placeholder="usuario@dfvariedades.com.br"
                            class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl text-sm text-zinc-900 dark:text-white focus:outline-hidden focus:ring-2 focus:ring-primary-500"
                        >
                        @error('email') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="user-role" class="block text-xs font-bold uppercase tracking-wider text-zinc-400 mb-1 cursor-pointer">Papel / Nível de Acesso (ACL) *</label>
                        <select 
                            id="user-role"
                            wire:model="userRole"
                            class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl text-sm text-zinc-900 dark:text-white focus:outline-hidden focus:ring-2 focus:ring-primary-500">
                            <option value="admin">Administrador Geral (Acesso total)</option>
                            <option value="gerente">Gerente de Catálogo (Produtos, Marcas, Banners)</option>
                            <option value="atendente">Atendente (Pedidos e Lista de Espera)</option>
                            <option value="cliente">Cliente (Sem acesso ao painel)</option>
                        </select>
                        @error('userRole') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="user-password" class="block text-xs font-bold uppercase tracking-wider text-zinc-400 mb-1 cursor-pointer">
                                {{ $editingId ? 'Nova Senha (opcional)' : 'Senha de Acesso *' }}
                            </label>
                            <input 
                                id="user-password"
                                type="password" 
                                wire:model="password"
                                placeholder="{{ $editingId ? 'Deixe em branco p/ manter' : 'Mínimo 6 caracteres' }}"
                                class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl text-sm text-zinc-900 dark:text-white focus:outline-hidden focus:ring-2 focus:ring-primary-500"
                            >
                            @error('password') <span class="text-xs text-rose-500 font-semibold">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="user-password-confirmation" class="block text-xs font-bold uppercase tracking-wider text-zinc-400 mb-1 cursor-pointer">Confirmar Senha</label>
                            <input 
                                id="user-password-confirmation"
                                type="password" 
                                wire:model="password_confirmation"
                                placeholder="Confirme a senha"
                                class="w-full px-3.5 py-2.5 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl text-sm text-zinc-900 dark:text-white focus:outline-hidden focus:ring-2 focus:ring-primary-500"
                            >
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-3 border-t border-zinc-100 dark:border-zinc-800">
                        <button 
                            type="button" 
                            wire:click="closeModal"
                            class="px-5 py-2.5 rounded-xl border border-zinc-300 dark:border-zinc-700 text-zinc-700 dark:text-zinc-300 font-semibold text-xs hover:bg-zinc-100 dark:hover:bg-zinc-800 transition cursor-pointer">
                            Cancelar
                        </button>
                        <button 
                            type="submit" 
                            class="px-6 py-2.5 rounded-xl bg-[#C9A84C] hover:bg-[#D4A843] text-black font-bold text-xs shadow-md transition cursor-pointer">
                            {{ $editingId ? 'Salvar Alterações' : 'Criar Usuário' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($deletingId)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl p-6 max-w-md w-full shadow-2xl space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-500/10 text-rose-500 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-zinc-900 dark:text-white">Excluir Usuário</h3>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                        Tem certeza que deseja excluir este usuário? O acesso dele ao sistema será revogado imediatamente.
                    </p>
                </div>
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button 
                        type="button" 
                        wire:click="cancelDelete"
                        class="px-4 py-2 rounded-xl bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 font-semibold text-xs hover:bg-zinc-200 dark:hover:bg-zinc-700 transition">
                        Cancelar
                    </button>
                    <button 
                        type="button" 
                        wire:click="delete"
                        class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs transition shadow-xs">
                        Confirmar Exclusão
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
