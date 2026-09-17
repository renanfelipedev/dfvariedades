<div class="space-y-12 pb-16">

    <!-- ════════════════════════════════════════════
         1. STORIES DE MARCAS (INSTAGRAM STYLE)
    ════════════════════════════════════════════ -->
    @if($marcas->count() > 0)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-4">
            <div class="flex items-center gap-4 sm:gap-6 overflow-x-auto py-2 scrollbar-none">
                @foreach($marcas as $marca)
                    <a href="{{ route('catalogo.tipo', ['tipo' => 'marca', 'slug' => $marca->slug]) }}" class="flex flex-col items-center gap-1.5 flex-shrink-0 group cursor-pointer">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full p-[2.5px] bg-gradient-to-tr from-[#B8892E] via-[#E0C068] to-[#8A6B2C] group-hover:scale-105 transition-transform duration-300 shadow-md">
                            <div class="w-full h-full rounded-full overflow-hidden bg-[var(--bg-card)] border-2 border-[var(--bg-page)] flex items-center justify-center">
                                <img src="{{ $marca->logo_url }}" alt="{{ $marca->nome }}" class="w-full h-full object-cover">
                            </div>
                        </div>
                        <span class="text-xs font-semibold text-[var(--text-primary)] group-hover:text-[var(--gold)] transition truncate max-w-[76px] text-center">
                            {{ $marca->nome }}
                        </span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <!-- ════════════════════════════════════════════
         2. BANNERS PRINCIPAIS (HERO CAROUSEL)
    ════════════════════════════════════════════ -->
    @if($banners->count() > 0)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div 
                x-data="{
                    active: 0,
                    total: {{ $banners->count() }},
                    timer: null,
                    touchStartX: 0,
                    touchEndX: 0,
                    init() {
                        if (this.total > 1) {
                            this.start();
                        }
                    },
                    start() {
                        this.stop();
                        if (this.total > 1) {
                            this.timer = setInterval(() => {
                                this.next();
                            }, 5000);
                        }
                    },
                    stop() {
                        if (this.timer) {
                            clearInterval(this.timer);
                            this.timer = null;
                        }
                    },
                    next() {
                        this.active = (this.active + 1) % this.total;
                    },
                    prev() {
                        this.active = (this.active - 1 + this.total) % this.total;
                    },
                    goTo(index) {
                        this.active = index;
                        this.start();
                    },
                    handleTouchStart(e) {
                        this.touchStartX = e.changedTouches[0].screenX;
                    },
                    handleTouchEnd(e) {
                        this.touchEndX = e.changedTouches[0].screenX;
                        let diff = this.touchStartX - this.touchEndX;
                        if (Math.abs(diff) > 40) {
                            if (diff > 0) {
                                this.next();
                            } else {
                                this.prev();
                            }
                            this.start();
                        }
                    }
                }"
                class="relative w-full overflow-hidden rounded-3xl shadow-[var(--shadow-card)] border border-[var(--border-card)] group bg-black select-none"
                @mouseenter="stop()"
                @mouseleave="start()"
                @touchstart="handleTouchStart($event)"
                @touchend="handleTouchEnd($event)"
            >
                <!-- Slides Track -->
                <div 
                    class="flex transition-transform duration-700 ease-out will-change-transform"
                    :style="`transform: translateX(-${active * 100}%)`"
                >
                    @foreach($banners as $index => $banner)
                        <div class="w-full flex-shrink-0 relative aspect-[21/9] min-h-[220px] sm:min-h-[360px] md:min-h-[420px] bg-black overflow-hidden flex items-center justify-center">
                            @if($banner->tipo_midia === 'video')
                                <video src="{{ $banner->url_midia }}" autoplay muted loop playsinline class="w-full h-full object-cover"></video>
                            @else
                                <img src="{{ $banner->url_midia }}" alt="{{ $banner->titulo }}" class="w-full h-full object-cover">
                            @endif

                            <!-- Gradient Overlay & Info Content -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/35 to-transparent flex items-end p-6 sm:p-10 md:p-14 pointer-events-none">
                                <div class="max-w-2xl text-white space-y-2 sm:space-y-3 pointer-events-auto">
                                    <span class="inline-flex items-center gap-1.5 text-[10px] sm:text-xs uppercase font-extrabold tracking-widest text-[#E0C068] bg-[#B8892E]/25 border border-[#E0C068]/30 px-3 py-1 rounded-full backdrop-blur-md shadow-sm">
                                        <span class="w-1.5 h-1.5 rounded-full bg-[#E0C068] animate-pulse"></span>
                                        Destaque DF Variedades
                                    </span>
                                    
                                    <h2 class="text-xl sm:text-3xl md:text-5xl font-bold font-serif-title leading-tight text-white drop-shadow-md">
                                        {{ $banner->titulo }}
                                    </h2>

                                    @if($banner->target_url)
                                        <div class="pt-2">
                                            <a 
                                                href="{{ $banner->target_url }}" 
                                                class="inline-flex items-center gap-2 px-5 py-2.5 sm:px-6 sm:py-3 rounded-2xl bg-gradient-to-r from-[#B8892E] via-[#D4A843] to-[#8A6B2C] text-black font-extrabold text-xs sm:text-sm shadow-xl shadow-[#B8892E]/30 hover:scale-105 active:scale-95 transition-all cursor-pointer"
                                            >
                                                <span>Conferir Ofertas</span>
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if($banner->target_url)
                                <a href="{{ $banner->target_url }}" class="absolute inset-0 z-10" aria-label="{{ $banner->titulo }}"></a>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Navigation Controls (Visible only if more than 1 banner) -->
                @if($banners->count() > 1)
                    <!-- Prev Button -->
                    <button 
                        type="button"
                        @click.stop="prev(); start()" 
                        class="absolute left-3 sm:left-5 top-1/2 -translate-y-1/2 z-20 w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-black/40 hover:bg-black/80 text-white backdrop-blur-md border border-white/20 hover:border-[var(--gold)] flex items-center justify-center transition-all duration-200 opacity-0 group-hover:opacity-100 hover:scale-110 shadow-xl cursor-pointer"
                        aria-label="Banner anterior"
                    >
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>

                    <!-- Next Button -->
                    <button 
                        type="button"
                        @click.stop="next(); start()" 
                        class="absolute right-3 sm:right-5 top-1/2 -translate-y-1/2 z-20 w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-black/40 hover:bg-black/80 text-white backdrop-blur-md border border-white/20 hover:border-[var(--gold)] flex items-center justify-center transition-all duration-200 opacity-0 group-hover:opacity-100 hover:scale-110 shadow-xl cursor-pointer"
                        aria-label="Próximo banner"
                    >
                        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>

                    <!-- Indicator Dots / Pills -->
                    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 z-20 flex items-center gap-2 px-3 py-1.5 rounded-full bg-black/50 backdrop-blur-md border border-white/10 shadow-lg">
                        @foreach($banners as $index => $b)
                            <button 
                                type="button"
                                @click.stop="goTo({{ $index }})" 
                                class="h-2 rounded-full transition-all duration-300 cursor-pointer"
                                :class="active === {{ $index }} ? 'w-7 bg-gradient-to-r from-[#B8892E] to-[#E0C068] shadow-sm' : 'w-2 bg-white/40 hover:bg-white/70'"
                                aria-label="Ir para banner {{ $index + 1 }}"
                            ></button>
                        @endforeach
                    </div>
                @endif
            </div>
        </section>
    @endif

    <!-- ════════════════════════════════════════════
         3. OFERTA RELÂMPAGO (É HOJE) COM CONTADOR
    ════════════════════════════════════════════ -->
    @if($flashDeals->count() > 0)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @php
                $dealsData = $flashDeals->map(function ($deal) {
                    $targetTimestamp = ($deal->flash_deal_fim && $deal->flash_deal_fim->isFuture())
                        ? $deal->flash_deal_fim->timestamp * 1000
                        : (now()->endOfDay()->timestamp * 1000);
                    return [
                        'id' => $deal->id,
                        'targetTime' => $targetTimestamp,
                    ];
                })->values();
            @endphp
            <div 
                x-data="{
                    activeDeal: 0,
                    totalDeals: {{ $flashDeals->count() }},
                    deals: {{ Js::from($dealsData) }},
                    hours: '00',
                    minutes: '00',
                    seconds: '00',
                    timer: null,
                    autoPlayTimer: null,
                    touchStartX: 0,
                    touchEndX: 0,
                    init() {
                        this.updateTimer();
                        this.timer = setInterval(() => this.updateTimer(), 1000);
                        if (this.totalDeals > 1) {
                            this.startAutoPlay();
                        }
                    },
                    startAutoPlay() {
                        this.stopAutoPlay();
                        if (this.totalDeals > 1) {
                            this.autoPlayTimer = setInterval(() => {
                                this.nextDeal();
                            }, 7000);
                        }
                    },
                    stopAutoPlay() {
                        if (this.autoPlayTimer) {
                            clearInterval(this.autoPlayTimer);
                            this.autoPlayTimer = null;
                        }
                    },
                    nextDeal() {
                        this.activeDeal = (this.activeDeal + 1) % this.totalDeals;
                        this.updateTimer();
                    },
                    prevDeal() {
                        this.activeDeal = (this.activeDeal - 1 + this.totalDeals) % this.totalDeals;
                        this.updateTimer();
                    },
                    goToDeal(idx) {
                        this.activeDeal = idx;
                        this.updateTimer();
                        this.startAutoPlay();
                    },
                    updateTimer() {
                        let current = this.deals[this.activeDeal] || this.deals[0];
                        if (!current) return;
                        let now = Date.now();
                        let diff = Math.max(0, Math.floor((current.targetTime - now) / 1000));
                        let h = Math.floor(diff / 3600);
                        let m = Math.floor((diff % 3600) / 60);
                        let s = diff % 60;
                        this.hours = String(h).padStart(2, '0');
                        this.minutes = String(m).padStart(2, '0');
                        this.seconds = String(s).padStart(2, '0');
                    },
                    handleTouchStart(e) {
                        this.touchStartX = e.changedTouches[0].screenX;
                    },
                    handleTouchEnd(e) {
                        this.touchEndX = e.changedTouches[0].screenX;
                        let diff = this.touchStartX - this.touchEndX;
                        if (Math.abs(diff) > 40) {
                            if (diff > 0) this.nextDeal();
                            else this.prevDeal();
                            this.startAutoPlay();
                        }
                    }
                }"
                class="bg-[var(--bg-hero)] border border-[var(--border-card)] rounded-3xl p-6 sm:p-8 shadow-[var(--shadow-card)] relative overflow-hidden group select-none"
                @mouseenter="stopAutoPlay()"
                @mouseleave="startAutoPlay()"
                @touchstart="handleTouchStart($event)"
                @touchend="handleTouchEnd($event)"
            >
                <!-- Header / Titulo & Timer -->
                <div class="flex flex-col md:flex-row items-center justify-between gap-6 pb-6 border-b border-[var(--border-card)]">
                    <div class="flex items-center gap-4 text-center md:text-left">
                        <div class="w-12 h-12 rounded-full border-2 border-[var(--gold)] flex items-center justify-center bg-[var(--gold-bg)] text-[var(--gold)] flex-shrink-0 shadow-sm">
                            <svg class="w-6 h-6 animate-spin" style="animation-duration: 6s;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </div>
                        <div>
                            <div class="flex items-center gap-2.5 justify-center md:justify-start">
                                <div class="font-serif-title text-2xl sm:text-3xl font-bold text-[var(--text-primary)]">É HOJE</div>
                                @if($flashDeals->count() > 1)
                                    <span class="text-[11px] font-extrabold px-2.5 py-0.5 rounded-full bg-[var(--gold-bg)] text-[var(--gold)] border border-[var(--gold)]/30">
                                        <span x-text="activeDeal + 1">1</span> de {{ $flashDeals->count() }} Ofertas
                                    </span>
                                @endif
                            </div>
                            <div class="text-xs sm:text-sm font-extrabold tracking-widest text-[var(--gold)] uppercase">OFERTA RELÂMPAGO</div>
                        </div>
                    </div>

                    <!-- Countdown Timer -->
                    <div class="flex items-center gap-2">
                        <!-- Horas -->
                        <div class="flex flex-col items-center">
                            <div class="bg-[#d32f2f] text-white font-extrabold text-xl sm:text-2xl px-3 py-2 rounded-xl shadow min-w-[48px] text-center" x-text="hours">00</div>
                            <span class="text-[10px] uppercase font-bold text-[#d32f2f] mt-1">Horas</span>
                        </div>
                        <span class="text-2xl font-bold text-[#d32f2f] -mt-4">:</span>
                        <!-- Minutos -->
                        <div class="flex flex-col items-center">
                            <div class="bg-[#d32f2f] text-white font-extrabold text-xl sm:text-2xl px-3 py-2 rounded-xl shadow min-w-[48px] text-center" x-text="minutes">00</div>
                            <span class="text-[10px] uppercase font-bold text-[#d32f2f] mt-1">Minutos</span>
                        </div>
                        <span class="text-2xl font-bold text-[#d32f2f] -mt-4">:</span>
                        <!-- Segundos -->
                        <div class="flex flex-col items-center">
                            <div class="bg-[#d32f2f] text-white font-extrabold text-xl sm:text-2xl px-3 py-2 rounded-xl shadow min-w-[48px] text-center" x-text="seconds">00</div>
                            <span class="text-[10px] uppercase font-bold text-[#d32f2f] mt-1">Segundos</span>
                        </div>
                    </div>
                </div>

                <!-- Slides Track -->
                <div class="relative overflow-hidden pt-6">
                    <div 
                        class="flex transition-transform duration-500 ease-out will-change-transform"
                        :style="`transform: translateX(-${activeDeal * 100}%)`"
                    >
                        @foreach($flashDeals as $index => $deal)
                            <div class="w-full flex-shrink-0 grid grid-cols-1 md:grid-cols-12 gap-6 items-center px-1">
                                <div class="md:col-span-5 relative group/img overflow-hidden rounded-2xl bg-[var(--bg-card)] border border-[var(--border-card)]">
                                    <img src="{{ $deal->primeira_imagem }}" alt="{{ $deal->nome }}" class="w-full h-64 sm:h-80 object-cover group-hover/img:scale-105 transition-transform duration-500">
                                    @if($deal->tem_desconto)
                                        <div class="absolute top-3 left-3 bg-[#d32f2f] text-white text-xs font-extrabold px-3 py-1 rounded-full shadow-lg">
                                            -{{ $deal->percentual_desconto }}% OFF
                                        </div>
                                    @endif
                                </div>
                                <div class="md:col-span-7 space-y-4">
                                    @if($deal->marca)
                                        <span class="text-xs font-bold text-[var(--gold)] uppercase tracking-wider">{{ $deal->marca->nome }}</span>
                                    @endif
                                    <h3 class="text-xl sm:text-2xl font-bold text-[var(--text-primary)] leading-snug">
                                        <a href="{{ route('produto.show', $deal->slug) }}" class="hover:text-[var(--gold)] transition">
                                            {{ $deal->nome }}
                                        </a>
                                    </h3>
                                    <p class="text-sm text-[var(--text-secondary)] leading-relaxed line-clamp-2">{{ $deal->descricao }}</p>
                                    
                                    <div class="flex items-baseline gap-3 pt-2">
                                        <span class="text-3xl font-extrabold text-[var(--text-primary)]">R$ {{ number_format($deal->preco_final, 2, ',', '.') }}</span>
                                        @if($deal->tem_desconto)
                                            <span class="text-base text-[var(--text-muted)] line-through">R$ {{ number_format($deal->preco, 2, ',', '.') }}</span>
                                        @endif
                                    </div>
                                    <div class="text-xs text-[var(--text-muted)]">{{ $deal->parcelamento['texto'] }} sem juros</div>

                                    <div class="flex flex-wrap gap-3 pt-4">
                                        <button wire:click="addToCart({{ $deal->id }})" class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-gradient-to-r from-[#B8892E] to-[#8A6B2C] text-white font-bold text-sm shadow-lg hover:shadow-xl hover:scale-[1.02] active:scale-[0.98] transition cursor-pointer">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                            COMPRAR AGORA
                                        </button>
                                        <a href="{{ route('produto.show', $deal->slug) }}" class="inline-flex items-center justify-center px-5 py-3.5 rounded-xl bg-[var(--bg-card)] border border-[var(--border-card)] text-sm font-semibold text-[var(--text-primary)] hover:border-[var(--gold)] transition cursor-pointer">
                                            Ver Detalhes
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Navigation controls if multiple deals -->
                    @if($flashDeals->count() > 1)
                        <div class="flex items-center justify-between pt-6 mt-4 border-t border-[var(--border-card)]">
                            <!-- Prev / Next buttons -->
                            <div class="flex items-center gap-2">
                                <button 
                                    type="button" 
                                    @click="prevDeal(); startAutoPlay()" 
                                    class="w-9 h-9 rounded-xl bg-[var(--bg-card)] border border-[var(--border-card)] hover:border-[var(--gold)] text-[var(--text-primary)] flex items-center justify-center transition cursor-pointer"
                                    aria-label="Oferta anterior"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M15 19l-7-7 7-7"/></svg>
                                </button>
                                <button 
                                    type="button" 
                                    @click="nextDeal(); startAutoPlay()" 
                                    class="w-9 h-9 rounded-xl bg-[var(--bg-card)] border border-[var(--border-card)] hover:border-[var(--gold)] text-[var(--text-primary)] flex items-center justify-center transition cursor-pointer"
                                    aria-label="Próxima oferta"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>

                            <!-- Indicator pills -->
                            <div class="flex items-center gap-2">
                                @foreach($flashDeals as $index => $deal)
                                    <button 
                                        type="button"
                                        @click="goToDeal({{ $index }})" 
                                        class="h-2 rounded-full transition-all duration-300 cursor-pointer"
                                        :class="activeDeal === {{ $index }} ? 'w-6 bg-[var(--gold)] shadow-sm' : 'w-2 bg-[var(--text-muted)]/40 hover:bg-[var(--text-muted)]'"
                                        aria-label="Ir para oferta {{ $index + 1 }}"
                                    ></button>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    <!-- ════════════════════════════════════════════
         4. CARROSSEL: ESCOLHIDOS PARA VOCÊ
    ════════════════════════════════════════════ -->
    @if($escolhidos->count() > 0)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-baseline justify-between mb-4">
                <div class="flex items-baseline gap-3">
                    <h2 class="font-serif-title text-2xl sm:text-3xl font-bold text-[var(--text-primary)]">Escolhidos para Você<span class="text-[var(--gold)]">.</span></h2>
                    <div class="hidden sm:block h-[1px] w-24 bg-[var(--gold)]/30"></div>
                </div>
                <a href="{{ route('catalogo') }}" class="text-xs sm:text-sm font-semibold text-[var(--gold)] hover:underline">Ver todos &rsaquo;</a>
            </div>

            <div class="destaques-scroll">
                @foreach($escolhidos as $p)
                    @include('livewire.storefront.partials.product-card', ['p' => $p])
                @endforeach
            </div>
        </section>
    @endif

    <!-- ════════════════════════════════════════════
         5. CARROSSEL: PERFEITOS PARA PRESENTEAR
    ════════════════════════════════════════════ -->
    @if($presentear->count() > 0)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-baseline justify-between mb-4">
                <div class="flex items-baseline gap-3">
                    <h2 class="font-serif-title text-2xl sm:text-3xl font-bold text-[var(--text-primary)]">Perfeitos para Presentear<span class="text-[var(--gold)]">.</span></h2>
                    <div class="hidden sm:block h-[1px] w-24 bg-[var(--gold)]/30"></div>
                </div>
                <a href="{{ route('catalogo.tipo', ['tipo' => 'colecao', 'slug' => 'presentes-kits-especiais']) }}" class="text-xs sm:text-sm font-semibold text-[var(--gold)] hover:underline">Ver todos &rsaquo;</a>
            </div>

            <div class="destaques-scroll">
                @foreach($presentear as $p)
                    @include('livewire.storefront.partials.product-card', ['p' => $p])
                @endforeach
            </div>
        </section>
    @endif

    <!-- ════════════════════════════════════════════
         6. CARROSSEL: TOP PRODUTOS PARA SEU CABELO
    ════════════════════════════════════════════ -->
    @if($cabelos->count() > 0)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-baseline justify-between mb-4">
                <div class="flex items-baseline gap-3">
                    <h2 class="font-serif-title text-2xl sm:text-3xl font-bold text-[var(--text-primary)]">Top Produtos para seu Cabelo<span class="text-[var(--gold)]">.</span></h2>
                    <div class="hidden sm:block h-[1px] w-24 bg-[var(--gold)]/30"></div>
                </div>
                <a href="{{ route('catalogo.tipo', ['tipo' => 'colecao', 'slug' => 'cuidados-com-o-cabelo']) }}" class="text-xs sm:text-sm font-semibold text-[var(--gold)] hover:underline">Ver todos &rsaquo;</a>
            </div>

            <div class="destaques-scroll">
                @foreach($cabelos as $p)
                    @include('livewire.storefront.partials.product-card', ['p' => $p])
                @endforeach
            </div>
        </section>
    @endif

    <!-- ════════════════════════════════════════════
         7. COLEÇÕES GRID
    ════════════════════════════════════════════ -->
    @if($colecoes->count() > 0)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-baseline gap-3 mb-6">
                <h2 class="font-serif-title text-2xl sm:text-3xl font-bold text-[var(--text-primary)]">Coleções<span class="text-[var(--gold)]">.</span></h2>
                <div class="h-[1px] flex-1 bg-[var(--gold)]/30"></div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-6">
                @foreach($colecoes as $col)
                    <a href="{{ route('catalogo.tipo', ['tipo' => 'colecao', 'slug' => $col->slug]) }}" class="group relative rounded-2xl overflow-hidden aspect-[4/5] bg-black shadow-[var(--shadow-card)] border border-[var(--border-card)]">
                        <img src="{{ $col->imagem_url }}" alt="{{ $col->nome }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 opacity-80 group-hover:opacity-90">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/30 to-transparent flex flex-col justify-end p-4 sm:p-5">
                            <span class="text-[11px] font-semibold text-[#E0C068] uppercase tracking-wider">{{ $col->produtos_count }} produtos</span>
                            <h3 class="text-base sm:text-lg font-bold text-white font-serif-title group-hover:text-[#E0C068] transition">{{ $col->nome }}</h3>
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    <!-- ════════════════════════════════════════════
         8. SEÇÕES DINÂMICAS POR MARCA
    ════════════════════════════════════════════ -->
    @foreach($brandSections as $bSection)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-baseline justify-between mb-4">
                <div class="flex items-baseline gap-3">
                    <h2 class="font-serif-title text-2xl sm:text-3xl font-bold text-[var(--text-primary)]">{{ $bSection->nome }}<span class="text-[var(--gold)]">.</span></h2>
                    <div class="hidden sm:block h-[1px] w-24 bg-[var(--gold)]/30"></div>
                </div>
                <a href="{{ route('catalogo.tipo', ['tipo' => 'marca', 'slug' => $bSection->slug]) }}" class="text-xs sm:text-sm font-semibold text-[var(--gold)] hover:underline">Ver catálogo {{ $bSection->nome }} &rsaquo;</a>
            </div>

            <div class="destaques-scroll">
                @foreach($bSection->produtos as $p)
                    @include('livewire.storefront.partials.product-card', ['p' => $p])
                @endforeach
            </div>
        </section>
    @endforeach

    <!-- ════════════════════════════════════════════
         9. EXPLORE MAIS SECTION (GRID COM LOAD MORE)
    ════════════════════════════════════════════ -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <div class="flex items-baseline gap-3 mb-6">
            <h2 class="font-serif-title text-2xl sm:text-3xl font-bold text-[var(--text-primary)]">Explore Mais<span class="text-[var(--gold)]">.</span></h2>
            <div class="h-[1px] flex-1 bg-[var(--gold)]/30"></div>
        </div>

        @if($exploreProdutos->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 sm:gap-6">
                @foreach($exploreProdutos as $p)
                    @include('livewire.storefront.partials.product-card', ['p' => $p, 'isGrid' => true])
                @endforeach
            </div>

            @if($hasMore)
                <div class="text-center pt-10">
                    <button wire:click="loadMore" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-2xl bg-[var(--bg-card)] border border-[var(--border-card)] hover:border-[var(--gold)] text-sm font-bold text-[var(--text-primary)] shadow-sm hover:shadow-md transition cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        Carregar Mais Produtos
                    </button>
                </div>
            @endif
        @else
            <div class="relative overflow-hidden rounded-[2.5rem] bg-gradient-to-b from-[var(--bg-card)] via-[var(--bg-card-alt)]/70 to-[var(--bg-card)] border border-[var(--border-card)] shadow-2xl px-6 py-16 sm:px-12 sm:py-24 lg:px-16 lg:py-28 text-center max-w-3xl mx-auto my-4">
                <!-- Aura dourada de fundo -->
                <div class="absolute -top-24 left-1/2 -translate-x-1/2 w-96 h-96 bg-[var(--gold)]/15 rounded-full blur-3xl pointer-events-none"></div>

                <div class="relative z-10 flex flex-col items-center">
                    <!-- Badge Superior -->
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-[var(--gold-bg)] border border-[var(--gold)]/30 text-[var(--gold)] text-[11px] sm:text-xs font-bold uppercase tracking-widest mb-6 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-[var(--gold)] animate-pulse"></span>
                        Sistema Pronto para Uso
                    </div>

                    <!-- Ícone com Efeito Vidro Dourado -->
                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-3xl bg-gradient-to-tr from-[#B8892E]/20 via-[#E0C068]/30 to-[#8A6B2C]/20 border border-[var(--gold)]/40 flex items-center justify-center text-[var(--gold)] mb-6 shadow-lg shadow-[var(--gold)]/10">
                        <svg class="w-10 h-10 sm:w-12 sm:h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                    </div>

                    <!-- Título com Tipografia Clássica -->
                    <h3 class="font-serif-title text-3xl sm:text-4xl lg:text-5xl font-bold tracking-tight text-[var(--text-primary)] leading-tight mb-4">
                        Catálogo em Preparação
                    </h3>

                    <!-- Descrição Elegante -->
                    <p class="text-sm sm:text-base lg:text-lg text-[var(--text-secondary)] font-normal leading-relaxed max-w-xl mx-auto mb-8">
                        Nenhum produto cadastrado no momento. Acesse o painel administrativo para cadastrar suas marcas, categorias, coleções e produtos do zero.
                    </p>

                    <!-- Passos Rápidos / Atalhos de Cadastro -->
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 w-full max-w-xl mb-10 text-left">
                        <a href="{{ route('admin.marcas.index') }}" class="p-3.5 rounded-2xl bg-[var(--bg-card)] border border-[var(--border-card)] hover:border-[var(--gold)]/60 transition group">
                            <div class="text-[11px] font-bold text-[var(--gold)] uppercase tracking-wider mb-0.5">Passo 1</div>
                            <div class="text-xs font-bold text-[var(--text-primary)] group-hover:text-[var(--gold)] transition">Marcas & Logos &rsaquo;</div>
                        </a>
                        <a href="{{ route('admin.colecoes.index') }}" class="p-3.5 rounded-2xl bg-[var(--bg-card)] border border-[var(--border-card)] hover:border-[var(--gold)]/60 transition group">
                            <div class="text-[11px] font-bold text-[var(--gold)] uppercase tracking-wider mb-0.5">Passo 2</div>
                            <div class="text-xs font-bold text-[var(--text-primary)] group-hover:text-[var(--gold)] transition">Coleções & Categorias &rsaquo;</div>
                        </a>
                        <a href="{{ route('admin.produtos.create') }}" class="p-3.5 rounded-2xl bg-[var(--bg-card)] border border-[var(--border-card)] hover:border-[var(--gold)]/60 transition group">
                            <div class="text-[11px] font-bold text-[var(--gold)] uppercase tracking-wider mb-0.5">Passo 3</div>
                            <div class="text-xs font-bold text-[var(--text-primary)] group-hover:text-[var(--gold)] transition">Cadastrar Produtos &rsaquo;</div>
                        </a>
                    </div>

                    <!-- Botão de Ação Principal -->
                    <div class="flex flex-col sm:flex-row items-center gap-3">
                        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center justify-center gap-3 px-8 py-4 rounded-2xl bg-gradient-to-r from-[#B8892E] via-[#D4A843] to-[#8A6B2C] text-black font-extrabold text-sm sm:text-base shadow-xl shadow-[#B8892E]/20 hover:shadow-2xl hover:scale-[1.02] active:scale-[0.98] transition-all duration-200">
                            <svg class="w-5 h-5 text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <span>Cadastrar Primeiro Produto</span>
                        </a>

                        <a href="{{ route('login') }}" class="inline-flex items-center justify-center gap-2 px-6 py-4 rounded-2xl bg-[var(--bg-card)] border border-[var(--border-card)] hover:border-[var(--gold)] text-xs sm:text-sm font-bold text-[var(--text-primary)] transition">
                            <span>Acessar Painel</span>
                            <svg class="w-4 h-4 text-[var(--gold)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </section>

</div>
