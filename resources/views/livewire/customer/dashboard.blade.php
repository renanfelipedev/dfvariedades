<div class="min-h-screen bg-[#0F0F0F] text-[#F5F5F7] py-8 lg:py-12">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 space-y-8">
        <!-- Top Header & Welcome Banner -->
        <div class="bg-[#1A1A1A] border border-[#2D2D2D] rounded-3xl p-6 sm:p-8 relative overflow-hidden shadow-2xl">
            <div class="absolute -right-12 -bottom-12 w-64 h-64 bg-[#C9A84C]/5 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[#C9A84C] to-[#8A6B2C] text-black font-black text-xl flex items-center justify-center shadow-lg shadow-[#C9A84C]/20">
                        {{ $user->initials() }}
                    </div>
                    <div>
                        <div class="flex items-center gap-3">
                            <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight">Olá, {{ $user->name }}!</h1>
                            <span class="px-3 py-1 rounded-full text-[11px] font-bold tracking-wider uppercase bg-[#C9A84C]/10 text-[#C9A84C] border border-[#C9A84C]/30">
                                Cliente VIP
                            </span>
                        </div>
                        <p class="text-xs sm:text-sm text-zinc-400 mt-1">
                            {{ $user->email }} • Membro desde {{ $user->created_at->format('M/Y') }}
                        </p>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-3">
                    <a href="{{ route('catalogo') }}" class="px-5 py-2.5 rounded-xl bg-[#C9A84C] hover:bg-[#D4A843] text-black font-bold text-xs uppercase tracking-wider transition shadow-md shadow-[#C9A84C]/20 flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        Explorar Produtos
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="px-4 py-2.5 rounded-xl bg-zinc-800 hover:bg-zinc-700 text-zinc-300 hover:text-white font-semibold text-xs transition border border-zinc-700">
                            Sair da Conta
                        </button>
                    </form>
                </div>
            </div>

            <!-- Stats Bar -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-8 pt-6 border-t border-zinc-800">
                <div class="bg-black/40 rounded-2xl p-4 border border-white/5">
                    <span class="text-xs text-zinc-400 uppercase tracking-wider font-semibold">Total de Pedidos</span>
                    <div class="text-2xl font-black text-white mt-1">{{ $pedidos->count() }}</div>
                </div>
                <div class="bg-black/40 rounded-2xl p-4 border border-white/5">
                    <span class="text-xs text-zinc-400 uppercase tracking-wider font-semibold">Pedidos em Andamento</span>
                    <div class="text-2xl font-black text-[#C9A84C] mt-1">{{ $pedidosEmAndamento }}</div>
                </div>
                <div class="bg-black/40 rounded-2xl p-4 border border-white/5">
                    <span class="text-xs text-zinc-400 uppercase tracking-wider font-semibold">Total em Compras</span>
                    <div class="text-2xl font-black text-white mt-1">R$ {{ number_format($totalGasto, 2, ',', '.') }}</div>
                </div>
            </div>
        </div>

        <!-- Orders Section -->
        <div class="space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-white tracking-tight">Meus Pedidos</h2>
                    <p class="text-xs text-zinc-400 mt-0.5">Acompanhe o status e a entrega dos seus produtos.</p>
                </div>
            </div>

            @forelse($pedidos as $pedido)
                @php
                    $statusConfig = [
                        'aguardando_pagamento' => ['label' => 'Aguardando Pagamento', 'class' => 'bg-amber-500/10 text-amber-400 border-amber-500/30'],
                        'pendente' => ['label' => 'Aguardando Pagamento', 'class' => 'bg-amber-500/10 text-amber-400 border-amber-500/30'],
                        'pago' => ['label' => 'Pagamento Confirmado', 'class' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30'],
                        'em_separacao' => ['label' => 'Em Separação no Estoque', 'class' => 'bg-blue-500/10 text-blue-400 border-blue-500/30'],
                        'pronto_retirada' => ['label' => 'Pronto para Retirada na Loja', 'class' => 'bg-purple-500/10 text-purple-400 border-purple-500/30'],
                        'enviado' => ['label' => 'Pedido Despachado / A Caminho', 'class' => 'bg-indigo-500/10 text-indigo-400 border-indigo-500/30'],
                        'entregue' => ['label' => 'Entregue com Sucesso', 'class' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/30'],
                        'cancelado' => ['label' => 'Cancelado', 'class' => 'bg-rose-500/10 text-rose-400 border-rose-500/30'],
                    ];
                    $st = $statusConfig[$pedido->status] ?? ['label' => ucfirst(str_replace('_', ' ', $pedido->status)), 'class' => 'bg-zinc-500/10 text-zinc-400 border-zinc-500/30'];
                    
                    $supportMsg = urlencode("Olá! Gostaria de informações sobre meu pedido #{$pedido->codigo} na DF Variedades.");
                    $supportLink = "https://api.whatsapp.com/send?phone=5574999999999&text={$supportMsg}";
                @endphp

                <div class="bg-[#1A1A1A] border border-[#2D2D2D] rounded-2xl overflow-hidden shadow-xl">
                    <!-- Order Header -->
                    <div class="px-6 py-4 bg-black/30 border-b border-[#2D2D2D] flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex flex-wrap items-center gap-3">
                            <span class="text-sm font-black text-white font-mono">#{{ $pedido->codigo }}</span>
                            <span class="text-xs text-zinc-400">• {{ $pedido->created_at->format('d/m/Y \à\s H:i') }}</span>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $st['class'] }}">
                                {{ $st['label'] }}
                            </span>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="{{ $supportLink }}" target="_blank" rel="noopener noreferrer" class="text-xs text-[#C9A84C] hover:underline flex items-center gap-1 font-semibold">
                                <span>Ajuda / WhatsApp</span>
                                <span>↗</span>
                            </a>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="p-6 divide-y divide-[#2D2D2D]">
                        @foreach($pedido->itens as $item)
                            <div class="py-4 first:pt-0 last:pb-0 flex items-center justify-between gap-4">
                                <div class="flex items-center gap-4 min-w-0">
                                    <div class="w-14 h-14 rounded-xl bg-zinc-900 border border-zinc-800 overflow-hidden flex-shrink-0 flex items-center justify-center">
                                        @if($item->imagem_url)
                                            <img src="{{ $item->imagem_url }}" alt="{{ $item->nome_produto }}" class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-6 h-6 text-zinc-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                            </svg>
                                        @endif
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="text-sm font-bold text-white truncate">{{ $item->nome_produto }}</h4>
                                        <p class="text-xs text-zinc-400 mt-0.5">{{ $item->quantidade }}x R$ {{ number_format($item->preco_unitario, 2, ',', '.') }}</p>
                                    </div>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <span class="text-sm font-bold text-white">R$ {{ number_format($item->subtotal, 2, ',', '.') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Order Footer Summary -->
                    <div class="px-6 py-4 bg-black/20 border-t border-[#2D2D2D] flex flex-col sm:flex-row sm:items-center justify-between gap-4 text-xs text-zinc-400">
                        <div>
                            @if($pedido->tipo_entrega === 'retirada')
                                <span>🏪 <strong>Retirada na Loja:</strong> {{ $pedido->loja_retirada ?: 'Loja Principal' }}</span>
                            @else
                                <span>🚚 <strong>Entrega:</strong> {{ $pedido->rua }}, {{ $pedido->numero }} — {{ $pedido->cidade }}/{{ $pedido->estado }}</span>
                            @endif
                            <span class="mx-2">•</span>
                            <span>Pagamento: <strong>{{ strtoupper($pedido->forma_pagamento ?: 'A combinar') }}</strong></span>
                        </div>
                        <div class="text-right">
                            <span class="text-xs text-zinc-400">Total do Pedido:</span>
                            <span class="text-base font-black text-[#C9A84C] ml-1">R$ {{ number_format($pedido->total, 2, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-[#1A1A1A] border border-[#2D2D2D] rounded-3xl p-12 text-center space-y-4 shadow-xl">
                    <div class="w-16 h-16 rounded-full bg-zinc-900 border border-zinc-800 text-zinc-500 flex items-center justify-center mx-auto">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-white">Você ainda não realizou nenhum pedido</h3>
                        <p class="text-xs sm:text-sm text-zinc-400 mt-1 max-w-md mx-auto">
                            Explore nossa coleção exclusiva de perfumes importados, cosméticos e kits para presente.
                        </p>
                    </div>
                    <div class="pt-2">
                        <a href="{{ route('catalogo') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-xl bg-[#C9A84C] hover:bg-[#D4A843] text-black font-bold text-xs uppercase tracking-wider transition shadow-lg shadow-[#C9A84C]/20">
                            Ver Catálogo de Produtos
                        </a>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
