@props([
    'p',
    'isGrid' => false,
])

@php
    $favorites = $userFavorites ?? Session::get('dfv_favorites', []);
    $isFav = in_array($p->id, $favorites);
@endphp

<div class="{{ $isGrid ? '' : 'product-card-snap' }} bg-[var(--bg-card)] border border-[var(--border-card)] rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between group relative">
    <!-- Top Image Box -->
    <div class="relative aspect-square overflow-hidden bg-[var(--bg-card-alt)]">
        <a href="{{ route('produto.show', $p->slug) }}" class="block w-full h-full">
            <img 
                src="{{ $p->primeira_imagem }}" 
                alt="{{ $p->nome }}" 
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                loading="lazy"
            >
        </a>

        <!-- Discount Badge -->
        @if($p->tem_desconto)
            <div class="absolute top-2.5 left-2.5 bg-[#d32f2f] text-white text-[11px] font-extrabold px-2 py-0.5 rounded-full shadow">
                -{{ $p->percentual_desconto }}%
            </div>
        @endif

        <!-- Favorite Button -->
        <button 
            wire:click="toggleFavorite({{ $p->id }})"
            class="absolute top-2.5 right-2.5 w-8 h-8 rounded-full bg-white/80 backdrop-blur-sm border border-black/5 flex items-center justify-center text-[var(--text-primary)] hover:text-[#d32f2f] transition cursor-pointer shadow-sm"
            title="{{ $isFav ? 'Remover dos favoritos' : 'Adicionar aos favoritos' }}"
        >
            <svg class="w-4 h-4 {{ $isFav ? 'fill-[#d32f2f] text-[#d32f2f]' : 'fill-none' }}" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
            </svg>
        </button>
    </div>

    <!-- Content / Details -->
    <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
        <div class="space-y-1">
            @if($p->marca)
                <div class="text-[11px] font-bold text-[var(--gold)] uppercase tracking-wider">{{ $p->marca->nome }}</div>
            @endif
            <h4 class="text-sm font-semibold text-[var(--text-primary)] line-clamp-2 leading-tight group-hover:text-[var(--gold)] transition">
                <a href="{{ route('produto.show', $p->slug) }}">
                    {{ $p->nome }}
                </a>
            </h4>
        </div>

        <div class="space-y-2 pt-1 border-t border-[var(--border-card)]">
            <div class="flex items-baseline gap-2">
                <span class="text-lg font-extrabold text-[var(--text-primary)]">
                    R$ {{ number_format($p->preco_final, 2, ',', '.') }}
                </span>
                @if($p->tem_desconto)
                    <span class="text-xs text-[var(--text-muted)] line-through">
                        R$ {{ number_format($p->preco, 2, ',', '.') }}
                    </span>
                @endif
            </div>

            <div class="text-[11px] text-[var(--text-muted)]">
                {{ $p->parcelamento['texto'] }}
            </div>

            <!-- Quick Add Button -->
            <button 
                wire:click="addToCart({{ $p->id }})"
                class="w-full py-2.5 px-3 rounded-xl bg-[var(--tag-bg)] hover:bg-[var(--gold)] hover:text-white text-xs font-bold text-[var(--text-primary)] transition flex items-center justify-center gap-1.5 cursor-pointer"
            >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                <span>Adicionar</span>
            </button>
        </div>
    </div>
</div>
