<nav @keydown.window.escape="$wire.set('menuOpen', false)" class="sticky top-0 z-40 bg-[var(--nav-bg)] backdrop-blur-md border-b border-[var(--border-nav)] shadow-[var(--shadow-nav)] transition-all">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-18 gap-4">
            
            <!-- Left: Menu Hamburger, Início & Brand Logo -->
            <div class="flex items-center gap-2 sm:gap-3">
                <button 
                    wire:click="toggleMenu" 
                    class="w-10 h-10 rounded-xl border border-[var(--border-card)] flex items-center justify-center hover:border-[var(--gold)] transition bg-[var(--bg-card)] text-[var(--text-primary)] cursor-pointer"
                    aria-label="Abrir Menu"
                    title="Menu de Categorias"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <!-- Botão Início -->
                <a 
                    href="{{ route('home') }}" 
                    class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl border border-[var(--border-card)] hover:border-[var(--gold)] transition bg-[var(--bg-card)] text-[var(--text-primary)] hover:text-[var(--gold)] text-xs font-semibold shadow-xs"
                    title="Página Inicial"
                >
                    <svg class="w-4 h-4 text-[var(--gold)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Início</span>
                </a>

                <!-- Brand / Logo -->
                <a href="{{ route('home') }}" class="font-serif-title text-xl sm:text-2xl font-bold tracking-wider text-[var(--text-primary)] hover:opacity-90 transition">
                    <span class="text-[var(--gold)]">DF</span> VARIEDADES
                </a>
            </div>

            <!-- Center: Desktop Search Bar -->
            <div class="hidden md:flex flex-1 max-w-md mx-4 relative" @click.outside="$wire.clearSearch()">
                <div class="w-full flex items-center bg-[var(--bg-card)] border border-[var(--border-card)] rounded-2xl px-4 py-2 focus-within:border-[var(--gold)] focus-within:ring-2 focus-within:ring-[var(--gold)]/20 transition shadow-sm">
                    <svg class="w-4 h-4 text-[var(--text-muted)] mr-2.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Buscar perfumes, cosméticos, marcas..." 
                        class="w-full bg-transparent border-none outline-none text-sm text-[var(--text-primary)] placeholder-[var(--text-muted)]"
                    />
                    @if($search)
                        <button wire:click="clearSearch" class="text-xs text-[var(--text-muted)] hover:text-[var(--text-primary)] ml-1">
                            ✕
                        </button>
                    @endif
                </div>

                <!-- Autocomplete Dropdown -->
                @if(count($searchResults) > 0)
                    <div class="absolute top-full left-0 right-0 mt-2 bg-[var(--bg-card)] border border-[var(--border-card)] rounded-2xl shadow-xl overflow-hidden z-50 divide-y divide-[var(--border-card)]">
                        @foreach($searchResults as $res)
                            <a href="{{ route('produto.show', $res['slug']) }}" class="flex items-center gap-3 p-3 hover:bg-[var(--bg-card-alt)] transition">
                                <img src="{{ $res['imagem'] }}" alt="{{ $res['nome'] }}" class="w-11 h-11 object-cover rounded-lg">
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs text-[var(--gold)] font-medium">{{ $res['marca'] }}</div>
                                    <div class="text-sm font-semibold text-[var(--text-primary)] truncate">{{ $res['nome'] }}</div>
                                </div>
                                <div class="text-sm font-bold text-[var(--gold)] whitespace-nowrap">
                                    R$ {{ number_format($res['preco'], 2, ',', '.') }}
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Right: Actions (Favoritos + Carrinho + Login) -->
            <div class="flex items-center gap-2">
                <!-- Favoritos -->
                <button 
                    wire:click="openFavorites" 
                    class="relative w-10 h-10 rounded-full flex items-center justify-center text-[var(--text-primary)] hover:text-[var(--gold)] hover:bg-[var(--bg-card-alt)] transition cursor-pointer"
                    title="Meus Favoritos"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    @if($favoritesCount > 0)
                        <span class="absolute -top-1 -right-1 bg-[var(--gold)] text-black font-bold text-[10px] w-4 h-4 rounded-full flex items-center justify-center shadow">
                            {{ $favoritesCount }}
                        </span>
                    @endif
                </button>

                <!-- Carrinho -->
                <button 
                    wire:click="openCart" 
                    class="relative w-10 h-10 rounded-full flex items-center justify-center text-[var(--text-primary)] hover:text-[var(--gold)] hover:bg-[var(--bg-card-alt)] transition cursor-pointer"
                    title="Carrinho de Compras"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    @if($cartCount > 0)
                        <span class="absolute -top-1 -right-1 bg-[var(--gold)] text-black font-bold text-[10px] w-4 h-4 rounded-full flex items-center justify-center shadow animate-pulse">
                            {{ $cartCount }}
                        </span>
                    @endif
                </button>

                @auth
                    <a href="{{ route('dashboard') }}" class="hidden sm:inline-flex items-center gap-1.5 text-xs font-semibold px-3.5 py-2 rounded-xl bg-[var(--bg-card)] border border-[var(--border-card)] hover:border-[var(--gold)] transition">
                        <span>Minha Conta</span>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="hidden sm:inline-flex items-center gap-1.5 text-xs font-semibold px-3.5 py-2 rounded-xl bg-[var(--bg-card)] border border-[var(--border-card)] hover:border-[var(--gold)] transition">
                        <span>Entrar</span>
                    </a>
                @endauth
            </div>
        </div>

        <!-- Mobile Search Bar (abaixo da nav) -->
        <div class="md:hidden pb-3 pt-1">
            <div class="flex items-center bg-[var(--bg-card)] border border-[var(--border-card)] rounded-xl px-3 py-2">
                <svg class="w-4 h-4 text-[var(--text-muted)] mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search" 
                    placeholder="Buscar perfumes, cosméticos..." 
                    class="w-full bg-transparent border-none outline-none text-xs text-[var(--text-primary)] placeholder-[var(--text-muted)]"
                />
            </div>
            @if(count($searchResults) > 0)
                <div class="mt-2 bg-[var(--bg-card)] border border-[var(--border-card)] rounded-xl shadow-lg overflow-hidden divide-y divide-[var(--border-card)]">
                    @foreach($searchResults as $res)
                        <a href="{{ route('produto.show', $res['slug']) }}" class="flex items-center gap-2.5 p-2.5 hover:bg-[var(--bg-card-alt)]">
                            <img src="{{ $res['imagem'] }}" alt="{{ $res['nome'] }}" class="w-9 h-9 object-cover rounded">
                            <div class="flex-1 min-w-0">
                                <div class="text-[10px] text-[var(--gold)] font-medium">{{ $res['marca'] }}</div>
                                <div class="text-xs font-semibold text-[var(--text-primary)] truncate">{{ $res['nome'] }}</div>
                            </div>
                            <div class="text-xs font-bold text-[var(--gold)]">
                                R$ {{ number_format($res['preco'], 2, ',', '.') }}
                            </div>
                        </a>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Dropdown Menu / Drawer -->
    @if($menuOpen)
        <div class="border-t border-[var(--border-nav)] bg-[var(--bg-card)] px-4 py-6 shadow-xl animate-in fade-in slide-in-from-top-2 duration-200">
            <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Coleções -->
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-[var(--gold)] mb-3">Coleções</h3>
                    <ul class="space-y-2">
                        @foreach($colecoes as $col)
                            <li>
                                <a href="{{ route('catalogo.tipo', ['tipo' => 'colecao', 'slug' => $col->slug]) }}" class="text-sm text-[var(--text-primary)] hover:text-[var(--gold)] transition block py-1">
                                    {{ $col->nome }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Marcas Principais -->
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-[var(--gold)] mb-3">Marcas em Destaque</h3>
                    <div class="grid grid-cols-2 gap-2">
                        @foreach($marcas as $m)
                            <a href="{{ route('catalogo.tipo', ['tipo' => 'marca', 'slug' => $m->slug]) }}" class="text-sm text-[var(--text-primary)] hover:text-[var(--gold)] transition py-1">
                                {{ $m->nome }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <!-- Minha Conta / Links -->
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-[var(--gold)] mb-3">Minha Conta</h3>
                    <div class="space-y-2">
                        @auth
                            <a href="{{ route('dashboard') }}" class="block text-sm text-[var(--text-primary)] hover:text-[var(--gold)] py-1">Dashboard</a>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-sm text-red-500 hover:underline py-1 cursor-pointer">Sair da Conta</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="block text-sm text-[var(--text-primary)] hover:text-[var(--gold)] py-1">Entrar no Sistema</a>
                            <a href="{{ route('register') }}" class="block text-sm text-[var(--text-primary)] hover:text-[var(--gold)] py-1">Criar Nova Conta</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    @endif
</nav>
