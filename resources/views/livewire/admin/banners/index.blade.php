<div class="space-y-6" @keydown.window.escape="$wire.closeModal(); $wire.cancelDelete()">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-zinc-900 dark:text-zinc-100">Banners & Destaques</h2>
            <p class="text-xs text-zinc-500">Gerencie os banners rotativos principais exibidos no topo da loja.</p>
        </div>

        <button wire:click="openModal()" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-[#C9A84C] text-black font-bold text-xs shadow hover:opacity-90 transition cursor-pointer self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Novo Banner</span>
        </button>
    </div>

    <!-- Cards Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        @forelse($banners as $b)
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl overflow-hidden shadow-xs flex flex-col justify-between group">
                <div class="relative aspect-[21/9] bg-black overflow-hidden">
                    <img src="{{ $b->url_midia }}" alt="" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                    <div class="absolute top-3 right-3 flex gap-2">
                        <button wire:click="toggleAtivo({{ $b->id }})" class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase backdrop-blur-md shadow cursor-pointer {{ $b->ativo ? 'bg-emerald-500 text-white' : 'bg-black/60 text-zinc-300' }}">
                            {{ $b->ativo ? 'Ativo' : 'Inativo' }}
                        </button>
                    </div>
                </div>

                <div class="p-5 space-y-3">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-[#C9A84C]">Ordem: #{{ $b->ordem }}</span>
                        <h4 class="text-sm font-bold text-zinc-900 dark:text-zinc-100">{{ $b->titulo ?: 'Sem título' }}</h4>
                        <div class="text-[11px] text-zinc-400 truncate mt-0.5">Link: {{ $b->link_tipo }} #{{ $b->link_id }}</div>
                    </div>

                    <div class="flex items-center justify-end gap-2 pt-2 border-t border-zinc-100 dark:border-zinc-800">
                        <button wire:click="openModal({{ $b->id }})" class="px-3 py-1.5 rounded-lg bg-zinc-100 dark:bg-zinc-800 hover:bg-[#C9A84C] hover:text-black transition text-xs font-semibold cursor-pointer">
                            Editar
                        </button>
                        <button wire:click="confirmDelete({{ $b->id }})" class="px-3 py-1.5 rounded-lg text-red-500 hover:bg-red-500/10 transition text-xs cursor-pointer">
                            Excluir
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="sm:col-span-2 py-12 text-center text-zinc-500 bg-white dark:bg-zinc-900 rounded-3xl border border-zinc-200 dark:border-zinc-800">
                Nenhum banner cadastrado ainda.
            </div>
        @endforelse
    </div>

    <!-- Modal Confirmação Exclusão -->
    @if($deletingId)
        <div class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl max-w-sm w-full p-6 space-y-4 shadow-2xl">
                <h3 class="text-base font-bold text-zinc-900 dark:text-zinc-100">Excluir Banner?</h3>
                <p class="text-xs text-zinc-500 leading-relaxed">Este banner será arquivado e deixará de rodar no carrossel da vitrine.</p>
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
                    <h3 class="text-base font-bold text-zinc-900 dark:text-zinc-100">{{ $editingId ? 'Editar Banner' : 'Novo Banner' }}</h3>
                    <button wire:click="$set('modalOpen', false)" class="text-zinc-400 hover:text-zinc-600">✕</button>
                </div>

                <form wire:submit="save" class="space-y-4 text-xs">
                    <div>
                        <label for="banner-titulo" class="block font-semibold text-zinc-700 dark:text-zinc-300 mb-1 cursor-pointer">Título do Banner</label>
                        <input id="banner-titulo" type="text" wire:model="titulo" placeholder="Ex: Lançamentos de Perfumaria" class="w-full bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2 text-zinc-900 dark:text-zinc-100 outline-none focus:border-[#C9A84C]">
                    </div>

                    <!-- Imagem / Upload -->
                    <div class="space-y-2">
                        <label for="banner-arquivo-midia" class="block font-semibold text-zinc-700 dark:text-zinc-300 cursor-pointer">
                            Imagem do Banner (Upload do Computador ou URL) *
                        </label>
                        
                        <div class="relative border-2 border-dashed border-zinc-300 dark:border-zinc-700 hover:border-[#C9A84C] rounded-2xl p-4 text-center transition bg-zinc-50/50 dark:bg-zinc-800/30 group">
                            <input 
                                id="banner-arquivo-midia"
                                type="file" 
                                wire:model="arquivoMidia" 
                                accept="image/*,video/*"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                            >
                            <div class="space-y-1 pointer-events-none">
                                <div class="w-8 h-8 mx-auto rounded-xl bg-[#C9A84C]/10 text-[#C9A84C] flex items-center justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                                <span class="text-xs font-bold text-zinc-900 dark:text-white group-hover:text-[#C9A84C]">
                                    Clique para enviar arquivo do computador
                                </span>
                            </div>
                        </div>

                        <!-- Preview -->
                        @if($arquivoMidia)
                            <div class="relative rounded-xl overflow-hidden aspect-[21/9] bg-black border border-emerald-500/40">
                                <img src="{{ $arquivoMidia->temporaryUrl() }}" class="w-full h-full object-cover" alt="Preview">
                                <span class="absolute top-2 left-2 bg-emerald-500 text-black text-[10px] font-bold px-2 py-0.5 rounded">Novo Arquivo</span>
                            </div>
                        @elseif($url_midia)
                            <div class="relative rounded-xl overflow-hidden aspect-[21/9] bg-black border border-zinc-700">
                                <img src="{{ $url_midia }}" class="w-full h-full object-cover" alt="Banner Atual">
                            </div>
                        @endif

                        <div class="pt-1">
                            <input id="banner-url-midia" type="text" wire:model="url_midia" placeholder="Ou digite/cole a URL externa da imagem..." class="w-full bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2 text-zinc-900 dark:text-zinc-100 outline-none focus:border-[#C9A84C]">
                        </div>
                        @error('url_midia') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                        @error('arquivoMidia') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label for="banner-link-tipo" class="block font-semibold text-zinc-700 dark:text-zinc-300 mb-1 cursor-pointer">Tipo de Link</label>
                            <select id="banner-link-tipo" wire:model.live="link_tipo" class="w-full bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2 text-zinc-900 dark:text-zinc-100 outline-none focus:border-[#C9A84C]">
                                <option value="colecao">Coleção</option>
                                <option value="marca">Marca</option>
                                <option value="url">Link Externo</option>
                            </select>
                        </div>
                        <div>
                            <label for="banner-link-destino" class="block font-semibold text-zinc-700 dark:text-zinc-300 mb-1 cursor-pointer">Destino</label>
                            @if($link_tipo === 'colecao')
                                <select id="banner-link-destino" wire:model="link_id" class="w-full bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2 text-zinc-900 dark:text-zinc-100 outline-none">
                                    <option value="">Selecione a Coleção...</option>
                                    @foreach($colecoes as $col)
                                        <option value="{{ $col->id }}">{{ $col->nome }}</option>
                                    @endforeach
                                </select>
                            @elseif($link_tipo === 'marca')
                                <select id="banner-link-destino" wire:model="link_id" class="w-full bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2 text-zinc-900 dark:text-zinc-100 outline-none">
                                    <option value="">Selecione a Marca...</option>
                                    @foreach($marcas as $m)
                                        <option value="{{ $m->id }}">{{ $m->nome }}</option>
                                    @endforeach
                                </select>
                            @else
                                <input id="banner-link-destino" type="text" wire:model="link_url" placeholder="https://..." class="w-full bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2 text-zinc-900 dark:text-zinc-100 outline-none">
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 items-center">
                        <div>
                            <label for="banner-ordem" class="block font-semibold text-zinc-700 dark:text-zinc-300 mb-1 cursor-pointer">Ordem de Exibição</label>
                            <input id="banner-ordem" type="number" wire:model="ordem" min="1" class="w-full bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2 text-zinc-900 dark:text-zinc-100 outline-none">
                        </div>
                        <div class="pt-4">
                            <label for="banner-ativo" class="flex items-center gap-2 cursor-pointer">
                                <input id="banner-ativo" type="checkbox" wire:model="ativo" class="text-[#C9A84C] rounded">
                                <span class="font-semibold text-emerald-600">Banner Ativo</span>
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
