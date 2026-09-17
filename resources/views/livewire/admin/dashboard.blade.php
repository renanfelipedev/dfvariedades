<div class="space-y-8">

    <!-- KPI Metric Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        
        <!-- Faturamento -->
        <div class="p-6 rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-xs flex flex-col justify-between space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-zinc-500">Faturamento Total</span>
                <div class="w-10 h-10 rounded-2xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
            <div>
                <div class="text-2xl font-extrabold text-zinc-900 dark:text-zinc-50">
                    R$ {{ number_format($faturamentoTotal, 2, ',', '.') }}
                </div>
                <div class="text-xs text-zinc-500 mt-1">Pedidos confirmados e ativos</div>
            </div>
        </div>

        <!-- Pedidos -->
        <div class="p-6 rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-xs flex flex-col justify-between space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-zinc-500">Total de Pedidos</span>
                <div class="w-10 h-10 rounded-2xl bg-[#C9A84C]/10 text-[#B8892E] dark:text-[#E0C068] flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
            </div>
            <div>
                <div class="text-2xl font-extrabold text-zinc-900 dark:text-zinc-50">
                    {{ $totalPedidos }}
                </div>
                <div class="text-xs text-amber-600 dark:text-amber-400 font-semibold mt-1">
                    {{ $pedidosPendentes }} aguardando atendimento
                </div>
            </div>
        </div>

        <!-- Produtos em Catálogo -->
        <div class="p-6 rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-xs flex flex-col justify-between space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-zinc-500">Catálogo de Produtos</span>
                <div class="w-10 h-10 rounded-2xl bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                </div>
            </div>
            <div>
                <div class="text-2xl font-extrabold text-zinc-900 dark:text-zinc-50">
                    {{ $totalProdutos }}
                </div>
                <div class="text-xs {{ $estoqueBaixo > 0 ? 'text-red-500 font-semibold' : 'text-zinc-500' }} mt-1">
                    {{ $estoqueBaixo }} com estoque baixo (&le; {{ $limiteEstoque }})
                </div>
            </div>
        </div>

        <!-- Lista de Espera -->
        <div class="p-6 rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-xs flex flex-col justify-between space-y-4">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold uppercase tracking-wider text-zinc-500">Lista de Espera</span>
                <div class="w-10 h-10 rounded-2xl bg-sky-500/10 text-sky-600 dark:text-sky-400 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
            </div>
            <div>
                <div class="text-2xl font-extrabold text-zinc-900 dark:text-zinc-50">
                    {{ $totalListaEspera }}
                </div>
                <div class="text-xs text-zinc-500 mt-1">Clientes aguardando cobertura</div>
            </div>
        </div>

    </div>

    <!-- Main Grids: Recent Orders & Stock Alert -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- Últimos Pedidos -->
        <div class="lg:col-span-8 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between">
                <h3 class="text-base font-bold text-zinc-900 dark:text-zinc-100">Últimos Pedidos</h3>
                <a href="{{ route('admin.pedidos.index') }}" class="text-xs font-bold text-[#B8892E] dark:text-[#E0C068] hover:underline">
                    Ver todos &rsaquo;
                </a>
            </div>

            @if($ultimosPedidos->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-zinc-600 dark:text-zinc-300 divide-y divide-zinc-200 dark:divide-zinc-800">
                        <thead>
                            <tr class="text-zinc-400 uppercase text-[10px] font-bold tracking-wider">
                                <th class="pb-3 px-2">Código</th>
                                <th class="pb-3 px-2">Cliente</th>
                                <th class="pb-3 px-2">Total</th>
                                <th class="pb-3 px-2">Status</th>
                                <th class="pb-3 px-2 text-right">Ação</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800/60 font-medium">
                            @foreach($ultimosPedidos as $ped)
                                <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/30 transition">
                                    <td class="py-3 px-2 font-mono font-bold text-[#B8892E] dark:text-[#E0C068]">
                                        #{{ $ped->codigo }}
                                    </td>
                                    <td class="py-3 px-2">
                                        <div class="font-bold text-zinc-900 dark:text-zinc-100">{{ $ped->nome_cliente }}</div>
                                        <div class="text-[10px] text-zinc-400">{{ $ped->cidade }}/{{ $ped->estado }}</div>
                                    </td>
                                    <td class="py-3 px-2 font-extrabold text-zinc-900 dark:text-zinc-100">
                                        R$ {{ number_format($ped->total, 2, ',', '.') }}
                                    </td>
                                    <td class="py-3 px-2">
                                        @php
                                            $statusColors = [
                                                'pendente' => 'bg-amber-500/10 text-amber-600 dark:text-amber-400',
                                                'pago' => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400',
                                                'enviado' => 'bg-blue-500/10 text-blue-600 dark:text-blue-400',
                                                'entregue' => 'bg-purple-500/10 text-purple-600 dark:text-purple-400',
                                                'cancelado' => 'bg-red-500/10 text-red-600 dark:text-red-400',
                                            ];
                                        @endphp
                                        <span class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase {{ $statusColors[$ped->status] ?? 'bg-zinc-100 text-zinc-600' }}">
                                            {{ $ped->status }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-2 text-right">
                                        <a href="{{ route('admin.pedidos.show', $ped->id) }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-zinc-100 dark:bg-zinc-800 hover:bg-[#C9A84C] hover:text-black font-semibold text-[11px] transition">
                                            Detalhes
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-xs text-zinc-500 py-6 text-center">Nenhum pedido registrado ainda.</p>
            @endif
        </div>

        <!-- Alerta de Estoque Baixo -->
        <div class="lg:col-span-4 bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl p-6 shadow-xs space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-zinc-900 dark:text-zinc-100">Alerta de Estoque</h3>
                    <p class="text-[11px] text-zinc-400">Produtos com quantidade crítica</p>
                </div>
                <div class="flex items-center gap-1 bg-zinc-100 dark:bg-zinc-800 p-1 rounded-xl border border-zinc-200 dark:border-zinc-700">
                    <span class="text-[10px] font-bold text-zinc-500 pl-1.5">&le;</span>
                    <input 
                        type="number" 
                        min="1" 
                        max="999" 
                        wire:model.live.debounce.400ms="limiteEstoque" 
                        class="w-12 bg-transparent text-xs font-black text-center text-zinc-900 dark:text-white outline-none"
                        title="Alterar limite do alerta de estoque"
                    >
                    <span class="text-[10px] text-zinc-400 pr-1.5 font-medium">un.</span>
                </div>
            </div>

            <!-- Botões de Ajuste Rápido do Limite -->
            <div class="flex items-center gap-1.5 pt-1">
                <span class="text-[10px] font-bold uppercase tracking-wider text-zinc-400">Ajuste rápido:</span>
                @foreach([3, 5, 10, 15, 20] as $val)
                    <button 
                        type="button" 
                        wire:click="setLimiteEstoque({{ $val }})"
                        class="px-2 py-0.5 rounded-lg text-[10px] font-bold transition cursor-pointer {{ $limiteEstoque === $val ? 'bg-[#C9A84C] text-black shadow-xs' : 'bg-zinc-100 dark:bg-zinc-800 text-zinc-600 dark:text-zinc-400 hover:bg-zinc-200 dark:hover:bg-zinc-700' }}"
                    >
                        {{ $val }} un
                    </button>
                @endforeach
            </div>

            @if($produtosEstoqueBaixo->count() > 0)
                <div class="divide-y divide-zinc-100 dark:divide-zinc-800/60 pt-2">
                    @foreach($produtosEstoqueBaixo as $prod)
                        <div class="py-3 flex items-center justify-between gap-3 first:pt-0">
                            <div class="min-w-0">
                                <div class="text-xs font-bold text-zinc-900 dark:text-zinc-100 truncate">{{ $prod->nome }}</div>
                                <div class="text-[10px] text-zinc-400">{{ $prod->marca?->nome ?? 'Sem Marca' }}</div>
                            </div>
                            <span class="px-2.5 py-1 rounded-lg {{ $prod->estoque == 0 ? 'bg-red-500/20 text-red-600 dark:text-red-400 border-red-500/30' : 'bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-500/20' }} border text-xs font-extrabold whitespace-nowrap">
                                {{ $prod->estoque }} un.
                            </span>
                        </div>
                    @endforeach
                </div>
                <div class="pt-2 text-center">
                    <a href="{{ route('admin.produtos.index', ['estoque' => 'baixo']) }}" class="text-xs font-bold text-[#B8892E] dark:text-[#E0C068] hover:underline">
                        Ver Todos com Estoque Baixo &rsaquo;
                    </a>
                </div>
            @else
                <div class="py-6 text-center space-y-1">
                    <div class="w-10 h-10 rounded-full bg-emerald-500/10 text-emerald-500 mx-auto flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <p class="text-xs font-semibold text-zinc-600 dark:text-zinc-300">Estoque Saudável</p>
                    <p class="text-[11px] text-zinc-400">Nenhum produto abaixo de {{ $limiteEstoque }} unidades.</p>
                </div>
            @endif
        </div>

    </div>

</div>
