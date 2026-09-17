<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-zinc-900 dark:text-zinc-100">Gestão de Pedidos</h2>
            <p class="text-xs text-zinc-500">Acompanhe as vendas realizadas, status de pagamento e entregas.</p>
        </div>
    </div>

    <!-- Status Tabs / Pills -->
    <div class="flex items-center gap-2 overflow-x-auto py-1 scrollbar-none">
        @php
            $tabs = [
                '' => 'Todos (' . $contagem['todos'] . ')',
                'pendente' => 'Pendentes (' . $contagem['pendente'] . ')',
                'pago' => 'Pagos (' . $contagem['pago'] . ')',
                'em_separacao' => 'Em Separação (' . $contagem['em_separacao'] . ')',
                'pronto_retirada' => 'Pronto Retirada (' . $contagem['pronto_retirada'] . ')',
                'enviado' => 'Enviados (' . $contagem['enviado'] . ')',
                'entregue' => 'Entregues (' . $contagem['entregue'] . ')',
                'cancelado' => 'Cancelados (' . $contagem['cancelado'] . ')',
            ];
        @endphp
        @foreach($tabs as $key => $label)
            <button 
                wire:click="$set('statusFilter', '{{ $key }}')" 
                class="px-4 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition cursor-pointer {{ $statusFilter === $key ? 'bg-[#C9A84C] text-black font-bold shadow' : 'bg-white dark:bg-zinc-900 text-zinc-600 dark:text-zinc-400 border border-zinc-200 dark:border-zinc-800 hover:border-[#C9A84C]' }}"
            >
                {{ $label }}
            </button>
        @endforeach
    </div>

    <!-- Search Bar -->
    <div class="p-4 rounded-2xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-xs">
        <div class="flex items-center bg-zinc-50 dark:bg-zinc-800/60 rounded-xl px-3 py-2 border border-zinc-200 dark:border-zinc-800">
            <svg class="w-4 h-4 text-zinc-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search" 
                placeholder="Buscar por código (#DFV-...), nome do cliente ou WhatsApp..." 
                class="w-full bg-transparent border-none outline-none text-xs text-zinc-800 dark:text-zinc-200"
            >
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-zinc-600 dark:text-zinc-300 divide-y divide-zinc-200 dark:divide-zinc-800">
                <thead>
                    <tr class="bg-zinc-50 dark:bg-zinc-950 text-zinc-400 uppercase text-[10px] font-bold tracking-wider">
                        <th class="py-3.5 px-4">Código</th>
                        <th class="py-3.5 px-3">Cliente</th>
                        <th class="py-3.5 px-3">Entrega / Retirada</th>
                        <th class="py-3.5 px-3">Pagamento</th>
                        <th class="py-3.5 px-3">Valor Total</th>
                        <th class="py-3.5 px-3">Status</th>
                        <th class="py-3.5 px-4 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800/60 font-medium">
                    @forelse($pedidos as $ped)
                        <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/30 transition">
                            <td class="py-3 px-4 font-mono font-bold text-[#B8892E] dark:text-[#E0C068]">
                                #{{ $ped->codigo }}
                                <div class="text-[10px] text-zinc-400 font-sans font-normal">{{ $ped->created_at->format('d/m/Y H:i') }}</div>
                            </td>
                            <td class="py-3 px-3">
                                <div class="font-bold text-zinc-900 dark:text-zinc-100">{{ $ped->nome_cliente }}</div>
                                <div class="text-[10px] text-zinc-400">{{ $ped->whatsapp_cliente }}</div>
                            </td>
                            <td class="py-3 px-3">
                                <div class="font-semibold text-zinc-800 dark:text-zinc-200">{{ $ped->opcao_frete ?: 'Padrão' }}</div>
                                <div class="text-[10px] text-zinc-400">
                                    {{ $ped->tipo_entrega === 'entrega' ? $ped->cidade . '/' . $ped->estado : 'Retirada na loja' }}
                                </div>
                            </td>
                            <td class="py-3 px-3 uppercase text-[11px] font-bold text-zinc-700 dark:text-zinc-300">
                                {{ $ped->forma_pagamento }}
                            </td>
                            <td class="py-3 px-3 font-extrabold text-zinc-900 dark:text-zinc-100">
                                R$ {{ number_format($ped->total, 2, ',', '.') }}
                                <div class="text-[10px] text-zinc-400 font-normal">{{ $ped->itens->sum('quantidade') }} item(ns)</div>
                            </td>
                            <td class="py-3 px-3">
                                <select 
                                    wire:change="updateStatus({{ $ped->id }}, $event.target.value)" 
                                    class="bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-lg px-2 py-1 text-[11px] font-bold outline-none cursor-pointer"
                                >
                                    <option value="pendente" {{ in_array($ped->status, ['pendente', 'aguardando_pagamento']) ? 'selected' : '' }}>Pendente</option>
                                    <option value="pago" {{ $ped->status === 'pago' ? 'selected' : '' }}>Pago</option>
                                    <option value="em_separacao" {{ $ped->status === 'em_separacao' ? 'selected' : '' }}>Em Separação</option>
                                    <option value="pronto_retirada" {{ $ped->status === 'pronto_retirada' ? 'selected' : '' }}>Pronto Retirada</option>
                                    <option value="enviado" {{ $ped->status === 'enviado' ? 'selected' : '' }}>Enviado</option>
                                    <option value="entregue" {{ $ped->status === 'entregue' ? 'selected' : '' }}>Entregue</option>
                                    <option value="cancelado" {{ $ped->status === 'cancelado' ? 'selected' : '' }}>Cancelado</option>
                                </select>
                            </td>
                            <td class="py-3 px-4 text-right">
                                <a 
                                    href="{{ route('admin.pedidos.show', $ped->id) }}" 
                                    class="inline-flex items-center px-3 py-1.5 rounded-lg bg-zinc-100 dark:bg-zinc-800 hover:bg-[#C9A84C] hover:text-black transition text-xs font-semibold"
                                >
                                    Ver Detalhes
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-zinc-500">Nenhum pedido encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pedidos->hasPages())
            <div class="p-4 border-t border-zinc-200 dark:border-zinc-800">
                {{ $pedidos->links() }}
            </div>
        @endif
    </div>

</div>
