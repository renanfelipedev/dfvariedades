<div class="space-y-6" @keydown.window.escape="$wire.closeModal(); $wire.cancelDelete()">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-zinc-900 dark:text-zinc-100">Subcategorias</h2>
            <p class="text-xs text-zinc-500">Gerencie as categorias associadas a cada coleção.</p>
        </div>

        <button wire:click="openModal()" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-[#C9A84C] text-black font-bold text-xs shadow hover:opacity-90 transition cursor-pointer self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Nova Categoria</span>
        </button>
    </div>

    <!-- Filters -->
    <div class="p-4 rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-xs grid grid-cols-1 sm:grid-cols-2 gap-3">
        <div class="flex items-center bg-zinc-50 dark:bg-zinc-800/60 rounded-xl px-3 py-2 border border-zinc-200 dark:border-zinc-800">
            <svg class="w-4 h-4 text-zinc-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Buscar por nome..." class="w-full bg-transparent border-none outline-none text-xs text-zinc-800 dark:text-zinc-200">
        </div>
        <div>
            <select wire:model.live="filterColecaoId" class="w-full bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2 text-xs text-zinc-800 dark:text-zinc-200 outline-none">
                <option value="">Filtrar por Coleção</option>
                @foreach($colecoes as $c)
                    <option value="{{ $c->id }}">{{ $c->nome }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-zinc-600 dark:text-zinc-300 divide-y divide-zinc-200 dark:divide-zinc-800">
                <thead>
                    <tr class="bg-zinc-50 dark:bg-zinc-950 text-zinc-400 uppercase text-[10px] font-bold tracking-wider">
                        <th class="py-3.5 px-4">Nome da Categoria</th>
                        <th class="py-3.5 px-3">Coleção Vinculada</th>
                        <th class="py-3.5 px-3">Produtos</th>
                        <th class="py-3.5 px-3">Ordem</th>
                        <th class="py-3.5 px-3">Status</th>
                        <th class="py-3.5 px-4 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800/60 font-medium">
                    @forelse($categorias as $cat)
                        <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/30 transition">
                            <td class="py-3 px-4">
                                <div class="font-bold text-zinc-900 dark:text-zinc-100">{{ $cat->nome }}</div>
                                <div class="text-[10px] text-zinc-400 font-mono">{{ $cat->slug }}</div>
                            </td>
                            <td class="py-3 px-3 font-semibold text-zinc-800 dark:text-zinc-200">
                                {{ $cat->colecao?->nome ?: '—' }}
                            </td>
                            <td class="py-3 px-3 font-bold text-zinc-800 dark:text-zinc-200">
                                {{ $cat->produtos_count }} produtos
                            </td>
                            <td class="py-3 px-3">
                                {{ $cat->ordem }}
                            </td>
                            <td class="py-3 px-3">
                                <button wire:click="toggleAtivo({{ $cat->id }})" class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase transition cursor-pointer {{ $cat->ativo ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20' : 'bg-zinc-200 dark:bg-zinc-800 text-zinc-500' }}">
                                    {{ $cat->ativo ? 'Ativa' : 'Inativa' }}
                                </button>
                            </td>
                            <td class="py-3 px-4 text-right space-x-2">
                                <button wire:click="openModal({{ $cat->id }})" class="px-2.5 py-1.5 rounded-lg bg-zinc-100 dark:bg-zinc-800 hover:bg-[#C9A84C] hover:text-black transition text-xs font-semibold cursor-pointer">
                                    Editar
                                </button>
                                <button wire:click="confirmDelete({{ $cat->id }})" class="px-2 py-1.5 rounded-lg text-red-500 hover:bg-red-500/10 transition text-xs cursor-pointer">
                                    Excluir
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-zinc-500">Nenhuma categoria cadastrada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Confirmação Exclusão -->
    @if($deletingId)
        <div class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl max-w-sm w-full p-6 space-y-4 shadow-2xl">
                <h3 class="text-base font-bold text-zinc-900 dark:text-zinc-100">Excluir Categoria?</h3>
                <p class="text-xs text-zinc-500 leading-relaxed">Esta categoria será arquivada e os produtos vinculados não serão perdidos.</p>
                <div class="flex gap-2 justify-end pt-2">
                    <button wire:click="cancelDelete" class="px-4 py-2 rounded-xl text-xs font-semibold border border-zinc-300 dark:border-zinc-700 hover:bg-zinc-100 dark:hover:bg-zinc-800">Cancelar</button>
                    <button wire:click="delete" class="px-4 py-2 rounded-xl text-xs font-bold bg-red-600 text-white hover:bg-red-700 shadow">Confirmar Exclusão</button>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal Form -->
    @if($modalOpen)
        <div class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl max-w-md w-full p-6 space-y-4 shadow-2xl">
                <div class="flex items-center justify-between border-b border-zinc-100 dark:border-zinc-800 pb-3">
                    <h3 class="text-base font-bold text-zinc-900 dark:text-zinc-100">{{ $editingId ? 'Editar Categoria' : 'Nova Categoria' }}</h3>
                    <button wire:click="$set('modalOpen', false)" class="text-zinc-400 hover:text-zinc-600">✕</button>
                </div>

                <form wire:submit="save" class="space-y-3 text-xs">
                    <div>
                        <label class="block font-semibold text-zinc-700 dark:text-zinc-300 mb-1">Coleção Vinculada</label>
                        <select wire:model="colecao_id" class="w-full bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2 text-zinc-900 dark:text-zinc-100 outline-none focus:border-[#C9A84C]">
                            <option value="">Selecione a coleção...</option>
                            @foreach($colecoes as $col)
                                <option value="{{ $col->id }}">{{ $col->nome }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-semibold text-zinc-700 dark:text-zinc-300 mb-1">Nome da Categoria *</label>
                        <input type="text" wire:model.live.debounce.300ms="nome" placeholder="Ex: Eau de Parfum" class="w-full bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2 text-zinc-900 dark:text-zinc-100 outline-none focus:border-[#C9A84C]">
                        @error('nome') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-zinc-700 dark:text-zinc-300 mb-1">Slug da URL *</label>
                        <input type="text" wire:model="slug" placeholder="eau-de-parfum" class="w-full bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2 text-zinc-900 dark:text-zinc-100 outline-none focus:border-[#C9A84C]">
                        @error('slug') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-zinc-700 dark:text-zinc-300 mb-1">Descrição</label>
                        <textarea wire:model="descricao" rows="2" placeholder="Resumo..." class="w-full bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 rounded-xl p-3 text-zinc-900 dark:text-zinc-100 outline-none focus:border-[#C9A84C]"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-3 items-center">
                        <div>
                            <label class="block font-semibold text-zinc-700 dark:text-zinc-300 mb-1">Ordem</label>
                            <input type="number" wire:model="ordem" min="1" class="w-full bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2 text-zinc-900 dark:text-zinc-100 outline-none">
                        </div>
                        <div class="pt-4">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" wire:model="ativo" class="text-[#C9A84C] rounded">
                                <span class="font-semibold text-emerald-600">Ativa</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-4 border-t border-zinc-100 dark:border-zinc-800">
                        <button type="button" wire:click="$set('modalOpen', false)" class="px-4 py-2 rounded-xl border border-zinc-300 dark:border-zinc-700 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition cursor-pointer text-xs font-semibold">Cancelar</button>
                        <button type="submit" class="px-6 py-2 rounded-xl bg-[#C9A84C] hover:bg-[#D4A843] text-black font-bold text-xs shadow transition cursor-pointer">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>
