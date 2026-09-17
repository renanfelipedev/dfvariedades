<div class="space-y-6">
    <!-- Top Bar / Back Button & Title -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.pedidos.index') }}" class="p-2 rounded-xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 text-zinc-500 hover:text-zinc-900 dark:hover:text-white transition">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-black tracking-tight text-zinc-900 dark:text-white">Pedido #{{ $pedido->codigo }}</h1>
                    @php
                        $statusStyles = [
                            'aguardando_pagamento' => 'bg-amber-500/10 text-amber-500 border-amber-500/20',
                            'pago' => 'bg-emerald-500/10 text-emerald-500 border-emerald-500/20',
                            'em_separacao' => 'bg-blue-500/10 text-blue-500 border-blue-500/20',
                            'pronto_retirada' => 'bg-purple-500/10 text-purple-500 border-purple-500/20',
                            'enviado' => 'bg-indigo-500/10 text-indigo-500 border-indigo-500/20',
                            'entregue' => 'bg-emerald-500/10 text-emerald-600 border-emerald-500/30',
                            'cancelado' => 'bg-rose-500/10 text-rose-500 border-rose-500/20',
                        ];
                        $badgeStyle = $statusStyles[$pedido->status] ?? 'bg-zinc-500/10 text-zinc-500 border-zinc-500/20';
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider border {{ $badgeStyle }}">
                        {{ str_replace('_', ' ', $pedido->status) }}
                    </span>
                </div>
                <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-1">
                    Realizado em {{ $pedido->created_at->format('d/m/Y \à\s H:i') }} ({{ $pedido->created_at->diffForHumans() }})
                </p>
            </div>
        </div>

        <div class="flex items-center gap-3">
            @if($pedido->whatsapp_cliente)
                <a href="{{ $this->whatsappLink }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-sm shadow-sm shadow-emerald-600/20 transition">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                        <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                    </svg>
                    Falar no WhatsApp
                </a>
            @endif
        </div>
    </div>

    <!-- Status Change & Actions Strip -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-5 shadow-xs">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <div>
                <span class="text-xs font-bold uppercase tracking-wider text-zinc-400">Atualizar Status do Pedido</span>
                <p class="text-sm text-zinc-600 dark:text-zinc-300 mt-0.5">Avance o fluxo conforme o andamento do pagamento e despacho.</p>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                @foreach([
                    'aguardando_pagamento' => ['label' => 'Aguardando', 'color' => 'hover:bg-amber-500/20 text-amber-500 border-amber-500/30'],
                    'pago' => ['label' => 'Pago', 'color' => 'hover:bg-emerald-500/20 text-emerald-500 border-emerald-500/30'],
                    'em_separacao' => ['label' => 'Em Separação', 'color' => 'hover:bg-blue-500/20 text-blue-500 border-blue-500/30'],
                    'pronto_retirada' => ['label' => 'Pronto Retirada', 'color' => 'hover:bg-purple-500/20 text-purple-500 border-purple-500/30'],
                    'enviado' => ['label' => 'Enviado', 'color' => 'hover:bg-indigo-500/20 text-indigo-500 border-indigo-500/30'],
                    'entregue' => ['label' => 'Entregue', 'color' => 'hover:bg-emerald-600/20 text-emerald-600 border-emerald-600/30'],
                    'cancelado' => ['label' => 'Cancelar', 'color' => 'hover:bg-rose-500/20 text-rose-500 border-rose-500/30'],
                ] as $key => $opt)
                    <button 
                        type="button" 
                        wire:click="updateStatus('{{ $key }}')"
                        class="px-3 py-1.5 rounded-xl text-xs font-bold border transition {{ $pedido->status === $key ? 'bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 border-zinc-900 dark:border-white shadow-xs' : 'bg-transparent text-zinc-600 dark:text-zinc-400 border-zinc-200 dark:border-zinc-800 ' . $opt['color'] }}">
                        {{ $opt['label'] }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Column: Order Items & Notes -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Items Card -->
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl overflow-hidden shadow-xs">
                <div class="px-6 py-4 border-b border-zinc-100 dark:border-zinc-800/80 flex items-center justify-between">
                    <h2 class="text-base font-bold text-zinc-900 dark:text-white">Itens do Pedido ({{ $pedido->itens->count() }})</h2>
                    <span class="text-xs font-bold text-zinc-400">Total: R$ {{ number_format($pedido->subtotal, 2, ',', '.') }}</span>
                </div>

                <div class="divide-y divide-zinc-100 dark:divide-zinc-800">
                    @foreach($pedido->itens as $item)
                        <div class="p-6 flex items-center justify-between gap-4">
                            <div class="flex items-center gap-4 min-w-0">
                                <div class="w-16 h-16 rounded-xl bg-zinc-100 dark:bg-zinc-800 flex-shrink-0 overflow-hidden border border-zinc-200 dark:border-zinc-700 flex items-center justify-center">
                                    @if($item->imagem_url)
                                        <img src="{{ $item->imagem_url }}" alt="{{ $item->nome_produto }}" class="w-full h-full object-cover">
                                    @else
                                        <svg class="w-6 h-6 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    @endif
                                </div>
                                <div class="min-w-0">
                                    <h3 class="text-sm font-bold text-zinc-900 dark:text-white truncate">{{ $item->nome_produto }}</h3>
                                    <p class="text-xs text-zinc-500 dark:text-zinc-400 mt-0.5">
                                        {{ $item->quantidade }}x R$ {{ number_format($item->preco_unitario, 2, ',', '.') }}
                                    </p>
                                    @if($item->produto)
                                        <a href="{{ route('admin.produtos.edit', $item->produto) }}" class="text-[11px] font-semibold text-primary-500 hover:underline inline-flex items-center gap-1 mt-1">
                                            Ver produto no estoque
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right flex-shrink-0">
                                <span class="text-sm font-black text-zinc-900 dark:text-white">
                                    R$ {{ number_format($item->subtotal, 2, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Financial Breakdown Footer -->
                <div class="bg-zinc-50 dark:bg-zinc-900/60 p-6 border-t border-zinc-100 dark:border-zinc-800 space-y-2">
                    <div class="flex justify-between text-xs text-zinc-500 dark:text-zinc-400">
                        <span>Subtotal</span>
                        <span>R$ {{ number_format($pedido->subtotal, 2, ',', '.') }}</span>
                    </div>
                    @if($pedido->desconto > 0)
                        <div class="flex justify-between text-xs text-emerald-500">
                            <span>Desconto</span>
                            <span>- R$ {{ number_format($pedido->desconto, 2, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-xs text-zinc-500 dark:text-zinc-400">
                        <span>Frete ({{ $pedido->opcao_frete ?? $pedido->tipo_entrega }})</span>
                        <span>R$ {{ number_format($pedido->valor_frete, 2, ',', '.') }}</span>
                    </div>
                    @if($pedido->taxa_pagamento > 0)
                        <div class="flex justify-between text-xs text-zinc-500 dark:text-zinc-400">
                            <span>Taxa de Pagamento</span>
                            <span>+ R$ {{ number_format($pedido->taxa_pagamento, 2, ',', '.') }}</span>
                        </div>
                    @endif
                    <div class="flex justify-between text-base font-black text-zinc-900 dark:text-white pt-2 border-t border-zinc-200 dark:border-zinc-800">
                        <span>Total</span>
                        <span class="text-primary-500 dark:text-primary-400">R$ {{ number_format($pedido->total, 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <!-- Notes Card -->
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-6 shadow-xs space-y-4">
                <h2 class="text-base font-bold text-zinc-900 dark:text-white">Observações Internas</h2>
                <textarea 
                    wire:model="observacoes"
                    rows="3"
                    placeholder="Adicione observações sobre o atendimento, código de rastreio, detalhes de despacho..."
                    class="w-full px-3 py-2 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl text-sm text-zinc-900 dark:text-white focus:outline-hidden focus:ring-2 focus:ring-primary-500"></textarea>
                <div class="flex justify-end">
                    <button 
                        type="button" 
                        wire:click="saveObservacoes"
                        class="px-4 py-2 rounded-xl bg-zinc-900 dark:bg-white text-white dark:text-zinc-900 font-bold text-xs hover:opacity-90 transition shadow-xs">
                        Salvar Observação
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Details: Customer, Delivery, Payment -->
        <div class="space-y-6">
            <!-- Customer Card -->
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-6 shadow-xs space-y-4">
                <h2 class="text-sm font-bold uppercase tracking-wider text-zinc-400">Dados do Cliente</h2>
                
                <div class="space-y-3">
                    <div>
                        <span class="text-xs text-zinc-400 block">Nome completo</span>
                        <span class="text-sm font-bold text-zinc-900 dark:text-white">{{ $pedido->nome_cliente }}</span>
                    </div>

                    <div>
                        <span class="text-xs text-zinc-400 block">WhatsApp / Telefone</span>
                        <span class="text-sm font-bold text-zinc-900 dark:text-white">{{ $pedido->whatsapp_cliente ?: 'Não informado' }}</span>
                    </div>

                    @if($pedido->email_cliente)
                        <div>
                            <span class="text-xs text-zinc-400 block">E-mail</span>
                            <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ $pedido->email_cliente }}</span>
                        </div>
                    @endif

                    @if($pedido->cpf_cliente)
                        <div>
                            <span class="text-xs text-zinc-400 block">CPF</span>
                            <span class="text-sm font-mono text-zinc-900 dark:text-white">{{ $pedido->cpf_cliente }}</span>
                        </div>
                    @endif

                    @if($pedido->user)
                        <div class="pt-2 border-t border-zinc-100 dark:border-zinc-800">
                            <span class="text-[11px] text-zinc-400 block">Conta do Usuário</span>
                            <span class="text-xs font-semibold text-primary-500">ID #{{ $pedido->user->id }} - {{ $pedido->user->email }}</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Delivery / Shipping Card -->
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-6 shadow-xs space-y-4">
                <h2 class="text-sm font-bold uppercase tracking-wider text-zinc-400">Entrega / Retirada</h2>

                <div class="space-y-3">
                    <div>
                        <span class="text-xs text-zinc-400 block">Modalidade</span>
                        <span class="text-sm font-bold text-zinc-900 dark:text-white">
                            @if($pedido->tipo_entrega === 'retirada')
                                🏪 Retirada na Loja
                            @else
                                🚚 Entrega em Domicílio
                            @endif
                        </span>
                    </div>

                    @if($pedido->tipo_entrega === 'retirada')
                        <div>
                            <span class="text-xs text-zinc-400 block">Loja Escolhida</span>
                            <span class="text-sm font-medium text-zinc-900 dark:text-white">{{ $pedido->loja_retirada ?: 'Loja Principal' }}</span>
                        </div>
                    @else
                        <div>
                            <span class="text-xs text-zinc-400 block">Endereço de Entrega</span>
                            <p class="text-sm font-medium text-zinc-900 dark:text-white mt-1 leading-relaxed">
                                {{ $pedido->rua }}, {{ $pedido->numero }} {{ $pedido->complemento ? '('.$pedido->complemento.')' : '' }}<br>
                                {{ $pedido->bairro }} - {{ $pedido->cidade }}/{{ $pedido->estado }}<br>
                                <span class="text-xs font-mono text-zinc-400">CEP: {{ $pedido->cep }}</span>
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Payment Card -->
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-6 shadow-xs space-y-4">
                <h2 class="text-sm font-bold uppercase tracking-wider text-zinc-400">Pagamento</h2>

                <div class="space-y-3">
                    <div>
                        <span class="text-xs text-zinc-400 block">Forma Escolhida</span>
                        <span class="text-sm font-bold uppercase text-zinc-900 dark:text-white">{{ $pedido->forma_pagamento ?: 'A combinar' }}</span>
                    </div>

                    <div>
                        <span class="text-xs text-zinc-400 block">Valor Final</span>
                        <span class="text-xl font-black text-primary-500 dark:text-primary-400">R$ {{ number_format($pedido->total, 2, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
