<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-12">

    <!-- Top Navigation & Breadcrumbs -->
    <div class="flex items-center justify-between border-b border-[var(--border-card)] pb-4">
        <a href="{{ url()->previous() ?? route('home') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-[var(--text-secondary)] hover:text-[var(--gold)] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Voltar</span>
        </a>

        <div class="flex items-center gap-2">
            <!-- Botão Favoritar -->
            <button 
                wire:click="toggleFavorite" 
                class="w-10 h-10 rounded-full border border-[var(--border-card)] flex items-center justify-center text-[var(--text-primary)] hover:text-[#d32f2f] transition cursor-pointer"
                title="{{ $isFav ? 'Remover dos favoritos' : 'Adicionar aos favoritos' }}"
            >
                <svg class="w-5 h-5 {{ $isFav ? 'fill-[#d32f2f] text-[#d32f2f]' : 'fill-none' }}" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                </svg>
            </button>

            <!-- Botão Compartilhar -->
            <button 
                @click="navigator.clipboard.writeText(window.location.href); $dispatch('toast', { message: 'Link copiado para a área de transferência!' })" 
                class="w-10 h-10 rounded-full border border-[var(--border-card)] flex items-center justify-center text-[var(--text-primary)] hover:text-[var(--gold)] transition cursor-pointer"
                title="Compartilhar Produto"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Main Layout: Gallery + Sticky Info -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 lg:gap-14">
        
        <!-- Left: Image Gallery -->
        <div class="lg:col-span-7 space-y-4">
            <!-- Main Viewport -->
            <div class="relative aspect-square rounded-3xl overflow-hidden bg-[var(--bg-card)] border border-[var(--border-card)] shadow-[var(--shadow-card)]">
                <img src="{{ $selectedImage ?: $produto->primeira_imagem }}" alt="{{ $produto->nome }}" class="w-full h-full object-cover">
                @if($produto->tem_desconto)
                    <div class="absolute top-4 left-4 bg-[#d32f2f] text-white text-xs font-extrabold px-3 py-1 rounded-full shadow">
                        -{{ $produto->percentual_desconto }}% OFF
                    </div>
                @endif
            </div>

            <!-- Thumbnails -->
            @if(is_array($produto->imagens) && count($produto->imagens) > 1)
                <div class="flex items-center gap-3 overflow-x-auto pb-2">
                    @foreach($produto->imagens as $img)
                        <button 
                            wire:click="selectImage('{{ $img }}')" 
                            class="w-20 h-20 rounded-2xl overflow-hidden border-2 transition cursor-pointer flex-shrink-0 {{ $selectedImage === $img ? 'border-[var(--gold)] scale-105 shadow' : 'border-transparent opacity-70 hover:opacity-100' }}"
                        >
                            <img src="{{ $img }}" alt="" class="w-full h-full object-cover">
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Right: Info Panel & Buy Box -->
        <div class="lg:col-span-5 space-y-6">
            <div class="space-y-2">
                @if($produto->marca)
                    <a href="{{ route('catalogo.tipo', ['tipo' => 'marca', 'slug' => $produto->marca->slug]) }}" class="text-xs font-extrabold uppercase tracking-widest text-[var(--gold)] hover:underline">
                        {{ $produto->marca->nome }}
                    </a>
                @endif
                <h1 class="text-2xl sm:text-3xl font-bold font-serif-title text-[var(--text-primary)] leading-tight">
                    {{ $produto->nome }}
                </h1>
                <div class="flex items-center gap-2 text-xs text-[var(--text-muted)]">
                    <span>SKU: {{ $produto->sku }}</span>
                    <span>•</span>
                    <span class="{{ $produto->estoque > 0 ? 'text-green-600 font-semibold' : 'text-red-500' }}">
                        {{ $produto->estoque > 0 ? 'Em Estoque (' . $produto->estoque . ' unid.)' : 'Esgotado' }}
                    </span>
                </div>
            </div>

            <!-- Pricing Box -->
            <div class="p-5 rounded-2xl bg-[var(--bg-card)] border border-[var(--border-card)] shadow-sm space-y-2">
                <div class="flex items-baseline gap-3">
                    <span class="text-3xl sm:text-4xl font-extrabold text-[var(--text-primary)]">
                        R$ {{ number_format($produto->preco_final, 2, ',', '.') }}
                    </span>
                    @if($produto->tem_desconto)
                        <span class="text-base text-[var(--text-muted)] line-through">
                            R$ {{ number_format($produto->preco, 2, ',', '.') }}
                        </span>
                    @endif
                </div>
                <p class="text-xs text-[var(--text-secondary)]">
                    {{ $produto->parcelamento['texto'] }} no cartão ou PIX
                </p>
            </div>

            <!-- Description -->
            <div class="text-sm text-[var(--text-secondary)] leading-relaxed space-y-2">
                <p>{{ $produto->descricao }}</p>
            </div>

            <!-- Accordion Details -->
            @if($produto->detalhes)
                <details class="group bg-[var(--bg-card)] border border-[var(--border-card)] rounded-2xl p-4 cursor-pointer transition">
                    <summary class="text-xs font-bold uppercase tracking-wider text-[var(--text-primary)] flex items-center justify-between">
                        <span>Detalhes do Produto</span>
                        <svg class="w-4 h-4 text-[var(--text-muted)] group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </summary>
                    <div class="pt-3 text-xs text-[var(--text-secondary)] leading-relaxed border-t border-[var(--border-card)] mt-3">
                        {{ $produto->detalhes }}
                    </div>
                </details>
            @endif

            <!-- Quantity Selector & Action CTAs -->
            <div class="space-y-3 pt-2">
                <!-- Quantity pill -->
                <div class="flex items-center justify-between bg-[var(--bg-card)] border border-[var(--border-card)] rounded-2xl px-4 py-2">
                    <span class="text-xs font-semibold text-[var(--text-primary)]">Quantidade:</span>
                    <div class="flex items-center gap-3">
                        <button wire:click="decrementQty" class="w-8 h-8 rounded-full border border-[var(--border-card)] flex items-center justify-center font-bold text-sm hover:bg-[var(--bg-card-alt)] transition cursor-pointer">−</button>
                        <span class="font-bold text-sm text-[var(--text-primary)]">{{ $quantidade }}</span>
                        <button wire:click="incrementQty" class="w-8 h-8 rounded-full border border-[var(--border-card)] flex items-center justify-center font-bold text-sm hover:bg-[var(--bg-card-alt)] transition cursor-pointer">+</button>
                    </div>
                </div>

                <!-- CTA Buttons -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <button 
                        wire:click="buyNow" 
                        class="w-full py-4 px-6 rounded-2xl bg-gradient-to-r from-[#B8892E] to-[#8A6B2C] text-white font-bold text-xs uppercase tracking-wider shadow-lg hover:shadow-xl hover:scale-[1.02] transition flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        <span>Comprar Agora</span>
                    </button>

                    <button 
                        wire:click="addToCart" 
                        class="w-full py-4 px-6 rounded-2xl bg-[var(--bg-card)] border-2 border-[var(--gold)] text-[var(--gold)] hover:bg-[var(--gold)] hover:text-white font-bold text-xs uppercase tracking-wider transition flex items-center justify-center gap-2 cursor-pointer"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                        <span>Adicionar à Sacola</span>
                    </button>
                </div>
            </div>

            <!-- Shipping Calculator -->
            <div class="p-5 rounded-2xl bg-[var(--bg-card)] border border-[var(--border-card)] shadow-sm space-y-3">
                <div class="flex items-center gap-2 text-xs font-bold text-[var(--text-primary)] uppercase tracking-wider">
                    <svg class="w-4 h-4 text-[var(--gold)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                    <span>Calcular frete e prazo de entrega</span>
                </div>

                <div class="flex gap-2">
                    <input 
                        type="text" 
                        wire:model="cep" 
                        placeholder="Digite seu CEP (Ex: 44870-000)" 
                        maxlength="9" 
                        class="flex-1 bg-[var(--bg-card-alt)] border border-[var(--border-card)] rounded-xl px-3 py-2 text-xs text-[var(--text-primary)] outline-none focus:border-[var(--gold)]"
                    />
                    <button 
                        wire:click="calculateShipping" 
                        class="px-5 py-2 rounded-xl bg-[var(--gold)] text-white text-xs font-bold shadow hover:opacity-90 transition cursor-pointer"
                    >
                        OK
                    </button>
                </div>

                @if($isShippingCalculated && is_array($shippingOptions))
                    <div class="space-y-2 pt-2 border-t border-[var(--border-card)]">
                        @foreach($shippingOptions as $opt)
                            <div class="flex items-center justify-between p-2.5 rounded-xl bg-[var(--bg-card-alt)] text-xs">
                                <div>
                                    <div class="font-bold text-[var(--text-primary)]">{{ $opt['nome'] }}</div>
                                    <div class="text-[10px] text-[var(--text-muted)]">{{ $opt['prazo'] }}</div>
                                </div>
                                <div class="font-bold {{ $opt['gratis'] ? 'text-green-600' : 'text-[var(--text-primary)]' }}">
                                    {{ $opt['gratis'] ? 'GRÁTIS' : 'R$ ' . number_format($opt['valor'], 2, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>
    </div>

    <!-- Recommendations Section: Talvez Possa Lhe Interessar -->
    @if($recommendations->count() > 0)
        <div class="pt-12 border-t border-[var(--border-card)] space-y-6">
            <div class="flex items-baseline gap-3">
                <h2 class="font-serif-title text-2xl sm:text-3xl font-bold text-[var(--text-primary)]">
                    Talvez Possa Lhe Interessar<span class="text-[var(--gold)]">.</span>
                </h2>
                <div class="h-[1px] flex-1 bg-[var(--gold)]/30"></div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($recommendations as $p)
                    @include('livewire.storefront.partials.product-card', ['p' => $p, 'isGrid' => true])
                @endforeach
            </div>
        </div>
    @endif

</div>
