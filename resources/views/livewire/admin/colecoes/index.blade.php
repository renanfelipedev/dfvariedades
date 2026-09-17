<div class="space-y-6" @keydown.window.escape="$wire.closeModal(); $wire.cancelDelete()">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-zinc-900 dark:text-zinc-100">Coleções & Categorias Gerais</h2>
            <p class="text-xs text-zinc-500">Gerencie as coleções principais da vitrine (Perfumaria, Cabelos, Skincare, Presentes).</p>
        </div>

        <button wire:click="openModal()" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-[#C9A84C] text-black font-bold text-xs shadow hover:opacity-90 transition cursor-pointer self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Nova Coleção</span>
        </button>
    </div>

    <!-- Table -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-zinc-600 dark:text-zinc-300 divide-y divide-zinc-200 dark:divide-zinc-800">
                <thead>
                    <tr class="bg-zinc-50 dark:bg-zinc-950 text-zinc-400 uppercase text-[10px] font-bold tracking-wider">
                        <th class="py-3.5 px-4">Coleção</th>
                        <th class="py-3.5 px-3">Produtos</th>
                        <th class="py-3.5 px-3">Ordem</th>
                        <th class="py-3.5 px-3">Status</th>
                        <th class="py-3.5 px-4 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800/60 font-medium">
                    @forelse($colecoes as $c)
                        <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/30 transition">
                            <td class="py-3 px-4 flex items-center gap-3">
                                <img src="{{ $c->imagem_url ?: 'https://via.placeholder.com/80' }}" class="w-12 h-12 rounded-xl object-cover border border-zinc-200 dark:border-zinc-800 flex-shrink-0">
                                <div>
                                    <div class="font-bold text-zinc-900 dark:text-zinc-100">{{ $c->nome }}</div>
                                    <div class="text-[10px] text-zinc-400 font-mono">{{ $c->slug }}</div>
                                </div>
                            </td>
                            <td class="py-3 px-3 font-bold text-zinc-800 dark:text-zinc-200">
                                {{ $c->produtos_count }} produtos
                            </td>
                            <td class="py-3 px-3">
                                {{ $c->ordem }}
                            </td>
                            <td class="py-3 px-3">
                                <button wire:click="toggleAtivo({{ $c->id }})" class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase transition cursor-pointer {{ $c->ativo ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20' : 'bg-zinc-200 dark:bg-zinc-800 text-zinc-500' }}">
                                    {{ $c->ativo ? 'Ativa' : 'Inativa' }}
                                </button>
                            </td>
                            <td class="py-3 px-4 text-right space-x-2">
                                <button wire:click="openModal({{ $c->id }})" class="px-2.5 py-1.5 rounded-lg bg-zinc-100 dark:bg-zinc-800 hover:bg-[#C9A84C] hover:text-black transition text-xs font-semibold cursor-pointer">
                                    Editar
                                </button>
                                <button wire:click="confirmDelete({{ $c->id }})" class="px-2.5 py-1.5 rounded-lg text-red-500 hover:bg-red-500/10 transition text-xs cursor-pointer">
                                    Excluir
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-zinc-500">Nenhuma coleção cadastrada.</td>
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
                <h3 class="text-base font-bold text-zinc-900 dark:text-zinc-100">Excluir Coleção?</h3>
                <p class="text-xs text-zinc-500 leading-relaxed">Esta coleção será arquivada e deixará de ser exibida no menu da loja.</p>
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
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl max-w-lg w-full p-6 space-y-4 shadow-2xl">
                <div class="flex items-center justify-between border-b border-zinc-100 dark:border-zinc-800 pb-3">
                    <h3 class="text-base font-bold text-zinc-900 dark:text-zinc-100">{{ $editingId ? 'Editar Coleção' : 'Nova Coleção' }}</h3>
                    <button wire:click="$set('modalOpen', false)" class="text-zinc-400 hover:text-zinc-600">✕</button>
                </div>

                <form wire:submit="save" class="space-y-4 text-xs">
                    <div>
                        <label for="col-nome" class="block font-semibold text-zinc-700 dark:text-zinc-300 mb-1 cursor-pointer">Nome da Coleção *</label>
                        <input id="col-nome" type="text" wire:model.live.debounce.300ms="nome" placeholder="Ex: Perfumaria Feminina" class="w-full bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2 text-zinc-900 dark:text-zinc-100 outline-none focus:border-[#C9A84C]">
                        @error('nome') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="col-slug" class="block font-semibold text-zinc-700 dark:text-zinc-300 mb-1 cursor-pointer">Slug da URL *</label>
                        <input id="col-slug" type="text" wire:model="slug" placeholder="perfumaria-feminina" class="w-full bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2 text-zinc-900 dark:text-zinc-100 outline-none focus:border-[#C9A84C]">
                        @error('slug') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <!-- Imagem de Capa -->
                    <div class="space-y-2">
                        <label for="col-imagem-url" class="block font-semibold text-zinc-700 dark:text-zinc-300 cursor-pointer">Foto / Imagem de Capa da Coleção</label>
                        <div class="flex items-center gap-3">
                            <div class="w-14 h-14 rounded-2xl bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 overflow-hidden flex-shrink-0 flex items-center justify-center">
                                @if($arquivoImagem)
                                    <img src="{{ $arquivoImagem->temporaryUrl() }}" class="w-full h-full object-cover">
                                @elseif($imagem_url)
                                    <img src="{{ $imagem_url }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-6 h-6 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                @endif
                            </div>

                            <div class="flex-1 space-y-1">
                                <input 
                                    type="file" 
                                    wire:model="arquivoImagem" 
                                    accept="image/*"
                                    class="w-full text-[11px] text-zinc-500 file:mr-2 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#C9A84C] file:text-black hover:file:opacity-90"
                                >
                                <input 
                                    id="col-imagem-url"
                                    type="text" 
                                    wire:model="imagem_url" 
                                    placeholder="Ou digite/cole a URL externa da foto..." 
                                    class="w-full bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 rounded-xl px-2.5 py-1.5 text-zinc-900 dark:text-zinc-100 outline-none text-[11px]"
                                >
                            </div>
                        </div>
                        @error('arquivoImagem') <span class="text-[10px] text-red-500 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Banner de Topo da Coleção -->
                    <div class="space-y-2">
                        <label for="col-banner-url" class="block font-semibold text-zinc-700 dark:text-zinc-300 cursor-pointer">Banner de Cabeçalho da Coleção</label>
                        <div class="flex items-center gap-3">
                            <div class="w-14 h-14 rounded-2xl bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 overflow-hidden flex-shrink-0 flex items-center justify-center">
                                @if($arquivoBanner)
                                    <img src="{{ $arquivoBanner->temporaryUrl() }}" class="w-full h-full object-cover">
                                @elseif($banner_url)
                                    <img src="{{ $banner_url }}" class="w-full h-full object-cover">
                                @else
                                    <svg class="w-6 h-6 text-zinc-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                @endif
                            </div>

                            <div class="flex-1 space-y-1">
                                <input 
                                    type="file" 
                                    wire:model="arquivoBanner" 
                                    accept="image/*"
                                    class="w-full text-[11px] text-zinc-500 file:mr-2 file:py-1 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-[#C9A84C] file:text-black hover:file:opacity-90"
                                >
                                <input 
                                    id="col-banner-url"
                                    type="text" 
                                    wire:model="banner_url" 
                                    placeholder="Ou digite/cole a URL externa do banner..." 
                                    class="w-full bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 rounded-xl px-2.5 py-1.5 text-zinc-900 dark:text-zinc-100 outline-none text-[11px]"
                                >
                            </div>
                        </div>
                        @error('arquivoBanner') <span class="text-[10px] text-red-500 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="col-descricao" class="block font-semibold text-zinc-700 dark:text-zinc-300 mb-1 cursor-pointer">Descrição</label>
                        <textarea id="col-descricao" wire:model="descricao" rows="2" placeholder="Resumo dos itens da coleção..." class="w-full bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 rounded-xl p-3 text-zinc-900 dark:text-zinc-100 outline-none focus:border-[#C9A84C]"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="col-ordem" class="block font-semibold text-zinc-700 dark:text-zinc-300 mb-1 cursor-pointer">Ordem</label>
                            <input id="col-ordem" type="number" wire:model="ordem" min="1" class="w-full bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2 text-zinc-900 dark:text-zinc-100 outline-none">
                        </div>
                        <div class="flex items-center gap-4 pt-4">
                            <label for="col-ativo" class="flex items-center gap-2 cursor-pointer">
                                <input id="col-ativo" type="checkbox" wire:model="ativo" class="text-[#C9A84C] rounded">
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
