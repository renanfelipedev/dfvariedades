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
                <svg class="w-5 h-5 text-[#d32f2f] fill-[#d32f2f]" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
                <h2 class="font-serif-title text-xl font-bold text-[var(--text-primary)]">Meus Favoritos ({{ $favoriteProducts->count() }})</h2>
            </div>
            <button wire:click="close" class="w-8 h-8 rounded-full border border-[var(--border-card)] flex items-center justify-center text-[var(--text-muted)] hover:text-[var(--text-primary)] transition cursor-pointer">
                ✕
            </button>
        </div>

        <!-- Body: Favorite Items -->
        <div class="flex-1 overflow-y-auto p-5 space-y-4">
            @if($favoriteProducts->count() > 0)
                <div class="divide-y divide-[var(--border-card)]">
                    @foreach($favoriteProducts as $p)
                        <div class="py-4 flex gap-3.5 first:pt-0">
                            <!-- Thumbnail -->
                            <a href="{{ route('produto.show', $p->slug) }}" class="flex-shrink-0">
                                <img src="{{ $p->primeira_imagem }}" alt="{{ $p->nome }}" class="w-16 h-16 object-cover rounded-xl border border-[var(--border-card)]">
                            </a>

                            <!-- Info -->
                            <div class="flex-1 min-w-0 space-y-1">
                                @if($p->marca)
                                    <div class="text-[10px] font-bold text-[var(--gold)] uppercase">{{ $p->marca->nome }}</div>
                                @endif
                                <h4 class="text-xs font-semibold text-[var(--text-primary)] truncate">
                                    <a href="{{ route('produto.show', $p->slug) }}" class="hover:text-[var(--gold)]">
                                        {{ $p->nome }}
                                    </a>
                                </h4>
                                <div class="text-sm font-extrabold text-[var(--text-primary)]">
                                    R$ {{ number_format($p->preco_final, 2, ',', '.') }}
                                </div>

                                <div class="flex items-center gap-3 pt-1">
                                    <button 
                                        wire:click="moveToCart({{ $p->id }})" 
                                        class="px-3 py-1.5 rounded-lg bg-[var(--gold)] text-white text-[11px] font-bold shadow hover:opacity-90 transition cursor-pointer"
                                    >
                                        Adicionar à Sacola
                                    </button>
                                    <button 
                                        wire:click="removeFavorite({{ $p->id }})" 
                                        class="text-[11px] text-red-500 hover:underline cursor-pointer"
                                    >
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
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </div>
                    <div class="space-y-1">
                        <h3 class="font-bold text-[var(--text-primary)] text-base font-serif-title">Nenhum favorito ainda</h3>
                        <p class="text-xs text-[var(--text-muted)]">Clique no coração dos produtos para salvá-los aqui.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
