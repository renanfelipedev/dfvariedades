<!DOCTYPE html>
<html lang="pt-BR" class="h-full bg-zinc-50 dark:bg-zinc-950">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Painel Administrativo' }} — DF Variedades</title>
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@600;700&family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --gold: #B8892E;
            --gold-light: #D4A843;
            --gold-dim: #8A6B2C;
        }

        .font-serif-title {
            font-family: 'Cormorant Garamond', serif;
        }
    </style>
</head>

<body class="min-h-screen antialiased text-zinc-800 dark:text-zinc-200 bg-zinc-50 dark:bg-zinc-950 flex"
    x-data="{
        sidebarOpen: false,
        toastMsg: '',
        showToast(msg) {
            this.toastMsg = msg;
            setTimeout(() => this.toastMsg = '', 3500);
        }
    }" 
    @keydown.window.escape="sidebarOpen = false"
    @toast.window="showToast($event.detail.message || $event.detail)">

    <!-- Sidebar Backdrop for Mobile -->
    <div x-show="sidebarOpen" @click="sidebarOpen = false" x-cloak class="fixed inset-0 z-40 bg-black/60 lg:hidden">
    </div>

    <!-- Sidebar -->
    <aside
        class="fixed inset-y-0 left-0 z-50 w-64 bg-zinc-900 text-zinc-300 border-r border-zinc-800 flex flex-col justify-between transition-transform duration-200 lg:translate-x-0"
        :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        <div class="space-y-6">
            <!-- Brand Header -->
            <div class="h-16 flex items-center justify-between px-6 border-b border-zinc-800 bg-zinc-950">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-2 font-serif-title text-xl font-bold tracking-wider text-white">
                    <span class="text-[#C9A84C]">DF</span> VARIEDADES <span
                        class="text-[10px] uppercase font-sans font-extrabold px-1.5 py-0.5 rounded bg-[#C9A84C]/20 text-[#C9A84C] border border-[#C9A84C]/30">ADMIN</span>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden text-zinc-400 hover:text-white">✕</button>
            </div>

            <!-- Navigation Links -->
            <nav class="px-3 space-y-1 text-sm font-medium">
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-xl transition {{ request()->routeIs('admin.dashboard') ? 'bg-[#C9A84C] text-black font-bold shadow' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Dashboard</span>
                </a>

                @if (auth()->user()->hasRole(['admin', 'gerente']))
                    <div class="pt-3 pb-1 px-3 text-[10px] font-bold uppercase tracking-wider text-zinc-500">Catálogo &
                        Loja</div>

                    <a href="{{ route('admin.produtos.index') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.produtos.*') ? 'bg-[#C9A84C] text-black font-bold shadow' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                        </svg>
                        <span>Produtos</span>
                    </a>

                    <a href="{{ route('admin.marcas.index') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.marcas.*') ? 'bg-[#C9A84C] text-black font-bold shadow' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        <span>Marcas</span>
                    </a>

                    <a href="{{ route('admin.colecoes.index') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.colecoes.*') ? 'bg-[#C9A84C] text-black font-bold shadow' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <span>Coleções</span>
                    </a>

                    <a href="{{ route('admin.categorias.index') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.categorias.*') ? 'bg-[#C9A84C] text-black font-bold shadow' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        <span>Categorias</span>
                    </a>

                    <a href="{{ route('admin.banners.index') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.banners.*') ? 'bg-[#C9A84C] text-black font-bold shadow' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Banners & Mídias</span>
                    </a>
                @endif

                <div class="pt-3 pb-1 px-3 text-[10px] font-bold uppercase tracking-wider text-zinc-500">Vendas &
                    Clientes</div>

                <a href="{{ route('admin.pedidos.index') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.pedidos.*') ? 'bg-[#C9A84C] text-black font-bold shadow' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    <span>Pedidos</span>
                </a>

                <a href="{{ route('admin.lista-espera.index') }}"
                    class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.lista-espera.*') ? 'bg-[#C9A84C] text-black font-bold shadow' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    <span>Lista de Espera</span>
                </a>

                @if (auth()->user()->isAdmin())
                    <div class="pt-3 pb-1 px-3 text-[10px] font-bold uppercase tracking-wider text-zinc-500">
                        Administração & ACL</div>

                    <a href="{{ route('admin.users.index') }}"
                        class="flex items-center gap-3 px-3 py-2 rounded-xl transition {{ request()->routeIs('admin.users.*') ? 'bg-[#C9A84C] text-black font-bold shadow' : 'text-zinc-400 hover:text-white hover:bg-zinc-800' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                        <span>Usuários & Permissões</span>
                    </a>
                @endif
            </nav>
        </div>

        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-zinc-800 bg-zinc-950 space-y-3">
            <a href="{{ route('home') }}" target="_blank"
                class="flex items-center justify-between px-3 py-2 rounded-xl bg-zinc-900 border border-zinc-800 hover:border-[#C9A84C] text-xs font-semibold text-zinc-300 hover:text-white transition">
                <span class="flex items-center gap-2">
                    <svg class="w-3.5 h-3.5 text-[#C9A84C]" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                    Ver Loja Online
                </span>
                <span>↗</span>
            </a>

            <div class="flex items-center justify-between text-xs">
                <div class="truncate">
                    <div class="font-bold text-white truncate">{{ auth()->user()->name }}</div>
                    <div class="text-[10px] text-[#C9A84C] font-semibold uppercase">{{ auth()->user()->role }}</div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="p-1.5 text-zinc-400 hover:text-red-400 transition cursor-pointer"
                        title="Sair">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 lg:pl-64">
        <!-- Topbar -->
        <header
            class="h-16 bg-white dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-800 px-4 sm:px-6 flex items-center justify-between sticky top-0 z-30 shadow-xs">
            <div class="flex items-center gap-3">
                <button @click="sidebarOpen = true"
                    class="lg:hidden p-2 rounded-xl text-zinc-600 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800"
                    aria-label="Abrir Menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>

                <a href="{{ route('home') }}" target="_blank"
                    class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl border border-zinc-200 dark:border-zinc-800 hover:border-[#C9A84C] text-xs font-semibold text-zinc-600 dark:text-zinc-300 hover:text-[#C9A84C] transition bg-white dark:bg-zinc-900 shadow-xs"
                    title="Ir para a Página Inicial / Loja">
                    <svg class="w-3.5 h-3.5 text-[#C9A84C]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    <span>Início</span>
                </a>

                <h1 class="text-base font-bold text-zinc-800 dark:text-zinc-100">
                    {{ $title ?? 'Painel Administrativo' }}</h1>
            </div>

            <div class="flex items-center gap-3">
                <span
                    class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-full bg-[#C9A84C]/10 text-[#B8892E] dark:text-[#E0C068] border border-[#C9A84C]/20">
                    <span class="w-1.5 h-1.5 rounded-full bg-[#C9A84C] animate-pulse"></span>
                    {{ strtoupper(auth()->user()->role) }}
                </span>
            </div>
        </header>

        <!-- Page Content -->
        <main class="p-4 sm:p-6 lg:p-8 flex-1">
            {{ $slot }}
        </main>
    </div>

    <!-- Global Toast Notification -->
    <div x-cloak x-show="toastMsg" x-transition.opacity.duration.300ms
        class="fixed bottom-6 right-6 z-50 bg-zinc-900 text-white px-5 py-3 rounded-2xl shadow-2xl border border-[#C9A84C]/40 flex items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#C9A84C]" fill="none" stroke="currentColor"
            stroke-width="2" viewBox="0 0 24 24">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
            <polyline points="22 4 12 14.01 9 11.01" />
        </svg>
        <span x-text="toastMsg" class="text-sm font-medium"></span>
    </div>

    @fluxScripts
</body>

</html>
