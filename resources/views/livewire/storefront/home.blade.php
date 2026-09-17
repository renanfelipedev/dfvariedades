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
         2. BANNERS PRINCIPAIS (SWIPER CAROUSEL)
    ════════════════════════════════════════════ -->
    @if($banners->count() > 0)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="swiper main-hero-swiper rounded-3xl overflow-hidden shadow-[var(--shadow-card)] border border-[var(--border-card)]">
                <div class="swiper-wrapper">
                    @foreach($banners as $banner)
                        <div class="swiper-slide relative bg-black aspect-[21/9] min-h-[220px] sm:min-h-[380px]">
                            <img src="{{ $banner->url_midia }}" alt="{{ $banner->titulo }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent flex items-end p-6 sm:p-12">
                                <div class="max-w-2xl text-white space-y-2">
                                    <span class="text-xs uppercase font-bold tracking-widest text-[#E0C068] bg-[#B8892E]/30 px-3 py-1 rounded-full backdrop-blur-sm">Destaque DF Variedades</span>
                                    <h2 class="text-xl sm:text-4xl font-bold font-serif-title leading-tight">{{ $banner->titulo }}</h2>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="swiper-pagination"></div>
            </div>
        </section>
    @endif

    <!-- ════════════════════════════════════════════
         3. OFERTA RELÂMPAGO (É HOJE) COM CONTADOR
    ════════════════════════════════════════════ -->
    @if($flashDeal)
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @php
                $targetTimestamp = $flashDeal->flash_deal_fim 
                    ? $flashDeal->flash_deal_fim->timestamp * 1000 
                    : (now()->endOfDay()->timestamp * 1000);
            @endphp
            <div 
                x-data="{
                    targetTime: {{ $targetTimestamp }},
                    hours: '00',
                    minutes: '00',
                    seconds: '00',
                    update() {
                        let now = new Date().getTime();
                        let diff = Math.max(0, Math.floor((this.targetTime - now) / 1000));
                        let h = Math.floor(diff / 3600);
                        let m = Math.floor((diff % 3600) / 60);
                        let s = diff % 60;
                        this.hours = String(h).padStart(2, '0');
                        this.minutes = String(m).padStart(2, '0');
                        this.seconds = String(s).padStart(2, '0');
                    },
                    init() {
                        this.update();
                        setInterval(() => this.update(), 1000);
                    }
                }"
                class="bg-[var(--bg-hero)] border border-[var(--border-card)] rounded-3xl p-6 sm:p-8 shadow-[var(--shadow-card)] relative overflow-hidden"
            >
                <!-- Header / Titulo & Timer -->
                <div class="flex flex-col md:flex-row items-center justify-between gap-6 pb-6 border-b border-[var(--border-card)]">
                    <div class="flex items-center gap-4 text-center md:text-left">
                        <div class="w-12 h-12 rounded-full border-2 border-[var(--gold)] flex items-center justify-center bg-[var(--gold-bg)] text-[var(--gold)] flex-shrink-0">
                            <svg class="w-6 h-6 animate-spin" style="animation-duration: 6s;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                        </div>
                        <div>
                            <div class="font-serif-title text-2xl sm:text-3xl font-bold text-[var(--text-primary)]">É HOJE</div>
                            <div class="text-xs sm:text-sm font-extrabold tracking-widest text-[var(--gold)] uppercase">OFERTA RELÂMPAGO</div>
                        </div>
                    </div>

                    <!-- Countdown Timer -->
                    <div class="flex items-center gap-2">
                        <!-- Horas -->
                        <div class="flex flex-col items-center">
                            <div class="bg-[#d32f2f] text-white font-extrabold text-xl sm:text-2xl px-3 py-2 rounded-xl shadow" x-text="hours">14</div>
                            <span class="text-[10px] uppercase font-bold text-[#d32f2f] mt-1">Horas</span>
                        </div>
                        <span class="text-2xl font-bold text-[#d32f2f] -mt-4">:</span>
                        <!-- Minutos -->
                        <div class="flex flex-col items-center">
                            <div class="bg-[#d32f2f] text-white font-extrabold text-xl sm:text-2xl px-3 py-2 rounded-xl shadow" x-text="minutes">45</div>
                            <span class="text-[10px] uppercase font-bold text-[#d32f2f] mt-1">Minutos</span>
                        </div>
                        <span class="text-2xl font-bold text-[#d32f2f] -mt-4">:</span>
                        <!-- Segundos -->
                        <div class="flex flex-col items-center">
                            <div class="bg-[#d32f2f] text-white font-extrabold text-xl sm:text-2xl px-3 py-2 rounded-xl shadow" x-text="seconds">30</div>
                            <span class="text-[10px] uppercase font-bold text-[#d32f2f] mt-1">Segundos</span>
                        </div>
                    </div>
                </div>

                <!-- Product Box -->
                <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center pt-6">
                    <div class="md:col-span-5 relative group">
                        <img src="{{ $flashDeal->primeira_imagem }}" alt="{{ $flashDeal->nome }}" class="w-full h-64 sm:h-80 object-cover rounded-2xl shadow">
                        @if($flashDeal->tem_desconto)
                            <div class="absolute top-3 left-3 bg-[#d32f2f] text-white text-xs font-extrabold px-3 py-1 rounded-full shadow">
                                -{{ $flashDeal->percentual_desconto }}% OFF
                            </div>
                        @endif
                    </div>
                    <div class="md:col-span-7 space-y-4">
                        @if($flashDeal->marca)
                            <span class="text-xs font-bold text-[var(--gold)] uppercase tracking-wider">{{ $flashDeal->marca->nome }}</span>
                        @endif
                        <h3 class="text-xl sm:text-2xl font-bold text-[var(--text-primary)] leading-snug">
                            <a href="{{ route('produto.show', $flashDeal->slug) }}" class="hover:text-[var(--gold)] transition">
                                {{ $flashDeal->nome }}
                            </a>
                        </h3>
                        <p class="text-sm text-[var(--text-secondary)] leading-relaxed line-clamp-2">{{ $flashDeal->descricao }}</p>
                        
                        <div class="flex items-baseline gap-3 pt-2">
                            <span class="text-3xl font-extrabold text-[var(--text-primary)]">R$ {{ number_format($flashDeal->preco_final, 2, ',', '.') }}</span>
                            @if($flashDeal->tem_desconto)
                                <span class="text-base text-[var(--text-muted)] line-through">R$ {{ number_format($flashDeal->preco, 2, ',', '.') }}</span>
                            @endif
                        </div>
                        <div class="text-xs text-[var(--text-muted)]">{{ $flashDeal->parcelamento['texto'] }} sem juros</div>

                        <div class="flex flex-wrap gap-3 pt-4">
                            <button wire:click="addToCart({{ $flashDeal->id }})" class="flex-1 sm:flex-initial inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-xl bg-gradient-to-r from-[#B8892E] to-[#8A6B2C] text-white font-bold text-sm shadow-lg hover:shadow-xl hover:scale-[1.02] transition cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                COMPRAR AGORA
                            </button>
                            <a href="{{ route('produto.show', $flashDeal->slug) }}" class="inline-flex items-center justify-center px-5 py-3.5 rounded-xl bg-[var(--bg-card)] border border-[var(--border-card)] text-sm font-semibold text-[var(--text-primary)] hover:border-[var(--gold)] transition">
                                Ver Detalhes
                            </a>
                        </div>
                    </div>
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

@script
<script>
    document.addEventListener('DOMContentLoaded', () => {
        new Swiper('.main-hero-swiper', {
            loop: true,
            autoplay: {
                delay: 4500,
                disableOnInteraction: false,
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
        });
    });
</script>
@endscript
