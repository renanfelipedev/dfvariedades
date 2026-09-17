<div @keydown.window.escape="$wire.close()">
    <!-- Backdrop Overlay -->
    @if($isOpen)
        <div 
            wire:click="close"
            class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 transition-opacity animate-in fade-in duration-300"
        ></div>
    @endif

    <!-- Slide-out Drawer Panel -->
    <div 
        class="fixed top-0 right-0 bottom-0 w-full max-w-md bg-[var(--bg-card)] border-l border-[var(--border-card)] shadow-2xl z-50 transform transition-transform duration-300 ease-in-out flex flex-col justify-between {{ $isOpen ? 'translate-x-0' : 'translate-x-full' }}"
    >
        <!-- Header -->
        <div class="p-5 border-b border-[var(--border-card)] flex items-center justify-between bg-[var(--bg-card-alt)]">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-[var(--gold)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <h2 class="font-serif-title text-xl font-bold text-[var(--text-primary)]">Sua Sacola ({{ $count }})</h2>
            </div>
            <button wire:click="close" class="w-8 h-8 rounded-full border border-[var(--border-card)] flex items-center justify-center text-[var(--text-muted)] hover:text-[var(--text-primary)] transition cursor-pointer">
                ✕
            </button>
        </div>

        <!-- Body: Cart Items List -->
        <div class="flex-1 overflow-y-auto p-5 space-y-4">
            @if(count($cart) > 0)
                <!-- Savings callout -->
                @if($economia > 0)
                    <div class="p-3 rounded-xl bg-green-500/10 border border-green-500/20 text-green-700 dark:text-green-400 text-xs flex items-center gap-2">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                        <span>Você está economizando <strong>R$ {{ number_format($economia, 2, ',', '.') }}</strong> nesta compra!</span>
                    </div>
                @endif

                <div class="divide-y divide-[var(--border-card)]">
                    @foreach($cart as $item)
                        <div class="py-4 flex gap-3.5 first:pt-0">
                            <!-- Thumbnail -->
                            <img src="{{ $item['imagem'] }}" alt="{{ $item['nome'] }}" class="w-16 h-16 object-cover rounded-xl border border-[var(--border-card)] flex-shrink-0">

                            <!-- Info -->
                            <div class="flex-1 min-w-0 space-y-1">
                                <div class="text-[10px] font-bold text-[var(--gold)] uppercase">{{ $item['marca'] }}</div>
                                <h4 class="text-xs font-semibold text-[var(--text-primary)] truncate">{{ $item['nome'] }}</h4>
                                <div class="flex items-baseline gap-2">
                                    <span class="text-sm font-extrabold text-[var(--text-primary)]">
                                        R$ {{ number_format($item['preco'], 2, ',', '.') }}
                                    </span>
                                    @if($item['preco_original'] > $item['preco'])
                                        <span class="text-[10px] text-[var(--text-muted)] line-through">
                                            R$ {{ number_format($item['preco_original'], 2, ',', '.') }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Quantity Controls -->
                                <div class="flex items-center justify-between pt-1">
                                    <div class="flex items-center gap-2 bg-[var(--bg-card-alt)] border border-[var(--border-card)] rounded-lg px-2 py-0.5">
                                        <button wire:click="decrementQty({{ $item['id'] }})" class="text-xs font-bold text-[var(--text-muted)] hover:text-[var(--text-primary)] px-1 cursor-pointer">−</button>
                                        <span class="text-xs font-bold text-[var(--text-primary)] px-1">{{ $item['quantidade'] }}</span>
                                        <button wire:click="incrementQty({{ $item['id'] }})" class="text-xs font-bold text-[var(--text-muted)] hover:text-[var(--text-primary)] px-1 cursor-pointer">+</button>
                                    </div>

                                    <button wire:click="removeItem({{ $item['id'] }})" class="text-[11px] text-red-500 hover:underline cursor-pointer">
                                        Remover
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-16 space-y-4">
                    <div class="w-16 h-16 rounded-full bg-[var(--bg-card-alt)] text-[var(--text-muted)] mx-auto flex items-center justify-center">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                    </div>
                    <div class="space-y-1">
                        <h3 class="font-bold text-[var(--text-primary)] text-base font-serif-title">Sua sacola está vazia</h3>
                        <p class="text-xs text-[var(--text-muted)]">Explore nossas coleções e adicione produtos incríveis.</p>
                    </div>
                    <button wire:click="close" class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl bg-[var(--gold)] text-white text-xs font-bold shadow hover:opacity-90 transition">
                        Começar a Comprar
                    </button>
                </div>
            @endif
        </div>

        <!-- Footer / Checkout CTA -->
        @if(count($cart) > 0)
            <div class="p-5 border-t border-[var(--border-card)] bg-[var(--bg-card-alt)] space-y-4">
                <div class="space-y-1 text-sm">
                    <div class="flex items-center justify-between text-xs text-[var(--text-secondary)]">
                        <span>Subtotal</span>
                        <span>R$ {{ number_format($subtotal, 2, ',', '.') }}</span>
                    </div>
                    <div class="flex items-center justify-between text-base font-extrabold text-[var(--text-primary)] pt-1 border-t border-[var(--border-card)]">
                        <span>Total</span>
                        <span class="text-[var(--gold)]">R$ {{ number_format($subtotal, 2, ',', '.') }}</span>
                    </div>
                </div>

                <button 
                    wire:click="proceedToCheckout" 
                    class="w-full py-3.5 px-6 rounded-2xl bg-gradient-to-r from-[#B8892E] to-[#8A6B2C] text-white font-bold text-xs uppercase tracking-wider shadow-lg hover:shadow-xl hover:scale-[1.02] transition flex items-center justify-center gap-2 cursor-pointer"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/></svg>
                    <span>Finalizar Pedido</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M12 5l7 7-7 7"/></svg>
                </button>
            </div>
        @endif
    </div>
</div>
