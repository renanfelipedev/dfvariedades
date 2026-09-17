<div class="space-y-6" @keydown.window.escape="$wire.set('produtoToDelete', null)">

    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-zinc-900 dark:text-zinc-100">Catálogo de Produtos</h2>
            <p class="text-xs text-zinc-500">Gerencie todos os itens comercializados na loja online.</p>
        </div>

        <a 
            href="{{ route('admin.produtos.create') }}" 
            class="inline-flex items-center gap-2 px-5 py-2.5 rounded-2xl bg-[#C9A84C] text-black font-bold text-xs shadow hover:opacity-90 transition self-start sm:self-auto"
        >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>Novo Produto</span>
        </a>
    </div>

    <!-- Stock Status Tabs / Pills -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-2 overflow-x-auto py-1 scrollbar-none">
            <button 
                wire:click="$set('estoqueFilter', '')" 
                class="px-4 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition cursor-pointer {{ $estoqueFilter === '' ? 'bg-[#C9A84C] text-black font-bold shadow' : 'bg-white dark:bg-zinc-900 text-zinc-600 dark:text-zinc-400 border border-zinc-200 dark:border-zinc-800 hover:border-[#C9A84C]' }}"
            >
                Todos os Produtos ({{ $totalTodos }})
            </button>

            <button 
                wire:click="$set('estoqueFilter', 'baixo')" 
                class="px-4 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition cursor-pointer {{ $estoqueFilter === 'baixo' ? 'bg-amber-500 text-black font-bold shadow' : 'bg-white dark:bg-zinc-900 text-amber-600 dark:text-amber-400 border border-amber-500/30 hover:border-amber-500' }}"
            >
                ⚠️ Estoque Baixo &le; {{ $limiteEstoque }} un. ({{ $totalBaixo }})
            </button>

            <button 
                wire:click="$set('estoqueFilter', 'zerado')" 
                class="px-4 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition cursor-pointer {{ $estoqueFilter === 'zerado' ? 'bg-red-600 text-white font-bold shadow' : 'bg-white dark:bg-zinc-900 text-red-600 dark:text-red-400 border border-red-500/30 hover:border-red-500' }}"
            >
                🚫 Esgotados ({{ $totalZerado }})
            </button>
        </div>

        <!-- Ajuste Rápido do Limite de Alerta -->
        <div class="flex items-center gap-2 bg-white dark:bg-zinc-900 px-3 py-1.5 rounded-xl border border-zinc-200 dark:border-zinc-800 text-xs self-start sm:self-auto">
            <span class="text-zinc-500 text-[11px] font-medium">Alerta Geral: &le;</span>
            <input 
                type="number" 
                min="1" 
                max="999" 
                wire:model.live.debounce.400ms="limiteEstoque" 
                class="w-12 bg-zinc-100 dark:bg-zinc-800 text-xs font-black text-center text-zinc-900 dark:text-white rounded-lg px-1 py-0.5 outline-none"
                title="Ajustar limite geral de estoque baixo"
            >
            <span class="text-zinc-400 text-[10px]">un.</span>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="p-4 rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-xs grid grid-cols-1 sm:grid-cols-12 gap-3">
        <!-- Search -->
        <div class="sm:col-span-6 flex items-center bg-zinc-50 dark:bg-zinc-800/60 rounded-xl px-3 py-2 border border-zinc-200 dark:border-zinc-800">
            <svg class="w-4 h-4 text-zinc-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search" 
                placeholder="Buscar por nome, SKU ou descrição..." 
                class="w-full bg-transparent border-none outline-none text-xs text-zinc-800 dark:text-zinc-200"
            >
        </div>

        <!-- Filter Marca -->
        <div class="sm:col-span-3">
            <select wire:model.live="marcaId" class="w-full bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2 text-xs text-zinc-800 dark:text-zinc-200 outline-none">
                <option value="">Todas as Marcas</option>
                @foreach($marcas as $m)
                    <option value="{{ $m->id }}">{{ $m->nome }}</option>
                @endforeach
            </select>
        </div>

        <!-- Filter Coleção -->
        <div class="sm:col-span-3">
            <select wire:model.live="colecaoId" class="w-full bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2 text-xs text-zinc-800 dark:text-zinc-200 outline-none">
                <option value="">Todas as Coleções</option>
                @foreach($colecoes as $c)
                    <option value="{{ $c->id }}">{{ $c->nome }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-zinc-600 dark:text-zinc-300 divide-y divide-zinc-200 dark:divide-zinc-800">
                <thead>
                    <tr class="bg-zinc-50 dark:bg-zinc-950 text-zinc-400 uppercase text-[10px] font-bold tracking-wider">
                        <th class="py-3.5 px-4">Produto</th>
                        <th class="py-3.5 px-3">Marca / Coleção</th>
                        <th class="py-3.5 px-3">Preço</th>
                        <th class="py-3.5 px-3">Estoque</th>
                        <th class="py-3.5 px-3">Destaques</th>
                        <th class="py-3.5 px-3">Status</th>
                        <th class="py-3.5 px-4 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800/60 font-medium">
                    @forelse($produtos as $p)
                        <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/30 transition">
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-3">
                                    <img src="{{ $p->primeira_imagem }}" alt="{{ $p->nome }}" class="w-10 h-10 object-cover rounded-xl border border-zinc-200 dark:border-zinc-800 flex-shrink-0">
                                    <div class="min-w-0">
                                        <div class="font-bold text-zinc-900 dark:text-zinc-100 truncate max-w-[200px] sm:max-w-xs">{{ $p->nome }}</div>
                                        <div class="text-[10px] text-zinc-400 font-mono">{{ $p->sku ?: 'Sem SKU' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-3">
                                <div class="font-semibold text-zinc-800 dark:text-zinc-200">{{ $p->marca?->nome ?? '—' }}</div>
                                <div class="text-[10px] text-zinc-400">{{ $p->colecao?->nome ?? '—' }}</div>
                            </td>
                            <td class="py-3 px-3">
                                <div class="font-bold text-zinc-900 dark:text-zinc-100">
                                    R$ {{ number_format($p->preco_final, 2, ',', '.') }}
                                </div>
                                @if($p->tem_desconto)
                                    <div class="text-[10px] text-zinc-400 line-through">
                                        R$ {{ number_format($p->preco, 2, ',', '.') }}
                                    </div>
                                @endif
                            </td>
                            <td class="py-3 px-3">
                                @php
                                    $minAlerta = $p->estoque_minimo ?? $limiteEstoque;
                                    $isZerado = $p->estoque <= 0;
                                    $isBaixo = ! $isZerado && $p->estoque <= $minAlerta;
                                @endphp
                                @if($isZerado)
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-black bg-red-500/20 text-red-600 dark:text-red-400 border border-red-500/30 whitespace-nowrap">
                                        0 un. (Esgotado)
                                    </span>
                                @elseif($isBaixo)
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-black bg-amber-500/15 text-amber-700 dark:text-amber-400 border border-amber-500/30 whitespace-nowrap" title="Abaixo do limite de alerta (≤ {{ $minAlerta }} un.)">
                                        ⚠️ {{ $p->estoque }} un.
                                    </span>
                                @else
                                    <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20 whitespace-nowrap">
                                        {{ $p->estoque }} un.
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-3">
                                <div class="flex flex-wrap gap-1">
                                    @if($p->flash_deal) <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-red-500/10 text-red-500">Flash</span> @endif
                                    @if($p->destaque) <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-amber-500/10 text-amber-600">Destaque</span> @endif
                                    @if($p->escolhido) <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-blue-500/10 text-blue-500">Escolhido</span> @endif
                                    @if($p->presente) <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-purple-500/10 text-purple-500">Presente</span> @endif
                                    @if($p->cabelo) <span class="text-[9px] font-bold px-1.5 py-0.5 rounded bg-emerald-500/10 text-emerald-500">Cabelo</span> @endif
                                </div>
                            </td>
                            <td class="py-3 px-3">
                                <button 
                                    wire:click="toggleAtivo({{ $p->id }})" 
                                    class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase transition cursor-pointer {{ $p->ativo ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20' : 'bg-zinc-200 dark:bg-zinc-800 text-zinc-500' }}"
                                >
                                    {{ $p->ativo ? 'Ativo' : 'Inativo' }}
                                </button>
                            </td>
                            <td class="py-3 px-4 text-right space-x-2">
                                <a 
                                    href="{{ route('admin.produtos.edit', $p->id) }}" 
                                    class="inline-flex items-center px-2.5 py-1.5 rounded-lg bg-zinc-100 dark:bg-zinc-800 hover:bg-[#C9A84C] hover:text-black transition text-xs font-semibold"
                                >
                                    Editar
                                </a>
                                <button 
                                    wire:click="confirmDelete({{ $p->id }})" 
                                    class="inline-flex items-center px-2 py-1.5 rounded-lg text-red-500 hover:bg-red-500/10 transition text-xs cursor-pointer"
                                >
                                    Excluir
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-zinc-500">Nenhum produto encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($produtos->hasPages())
            <div class="p-4 border-t border-zinc-200 dark:border-zinc-800">
                {{ $produtos->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Confirmação Exclusão -->
    @if($produtoToDelete)
        <div class="fixed inset-0 z-50 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl max-w-sm w-full p-6 space-y-4 shadow-2xl">
                <h3 class="text-base font-bold text-zinc-900 dark:text-zinc-100">Excluir Produto?</h3>
                <p class="text-xs text-zinc-500 leading-relaxed">Esta ação removerá o produto permanentemente do catálogo.</p>
                <div class="flex gap-2 justify-end pt-2">
                    <button wire:click="$set('produtoToDelete', null)" class="px-4 py-2 rounded-xl text-xs font-semibold border border-zinc-300 dark:border-zinc-700 hover:bg-zinc-100 dark:hover:bg-zinc-800">Cancelar</button>
                    <button wire:click="delete" class="px-4 py-2 rounded-xl text-xs font-bold bg-red-600 text-white hover:bg-red-700 shadow">Excluir</button>
                </div>
            </div>
        </div>
    @endif

</div>
