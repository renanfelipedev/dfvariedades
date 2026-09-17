<!DOCTYPE html>
<html lang="pt-BR" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, viewport-fit=cover">
    <title>{{ $title ?? 'DF Variedades — Perfumes, Cosméticos e Presentes' }}</title>
    <meta name="description"
        content="Descubra perfumes, cosméticos e produtos de beleza com os melhores preços. Frete grátis acima de R$150.">

    <!-- Favicon & Icons -->
    <link rel="icon" type="image/svg+xml" href="/favicon.svg">
    <meta name="theme-color" content="#C9A84C">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

    <!-- Styles & Scripts Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root,
        [data-theme="light"] {
            --gold: #B8892E;
            --gold-light: #D4A843;
            --gold-dim: #8A6B2C;
            --gold-glow: rgba(184, 137, 46, 0.18);
            --gold-bg: rgba(184, 137, 46, 0.08);
            --bg-page: #FAFAF8;
            --bg-card: #FFFFFF;
            --bg-card-alt: #F3F1EC;
            --bg-surface: #FFFFFF;
            --bg-hero: #FDFBF7;
            --border-card: rgba(0, 0, 0, 0.08);
            --border-nav: rgba(0, 0, 0, 0.08);
            --text-primary: #1A1A1A;
            --text-secondary: #555555;
            --text-muted: #888888;
            --nav-bg: rgba(250, 250, 248, 0.94);
            --shadow-card: 0 4px 20px rgba(0, 0, 0, 0.06);
            --shadow-nav: 0 2px 20px rgba(0, 0, 0, 0.06);
            --tag-bg: #F0EDE6;
            --tag-active-bg: #1A1A1A;
            --tag-active-text: #FAFAF8;
        }

        [data-theme="dark"] {
            --gold: #C9A84C;
            --gold-light: #E0C068;
            --gold-dim: #9E7D30;
            --gold-glow: rgba(201, 168, 76, 0.25);
            --gold-bg: rgba(201, 168, 76, 0.12);
            --bg-page: #0B0B0B;
            --bg-card: #111111;
            --bg-card-alt: #161616;
            --bg-surface: #1A1A1A;
            --bg-hero: #141414;
            --border-card: rgba(201, 168, 76, 0.15);
            --border-nav: rgba(201, 168, 76, 0.18);
            --text-primary: #EDE8DF;
            --text-secondary: #B0AAA0;
            --text-muted: #6E6860;
            --nav-bg: rgba(11, 11, 11, 0.94);
            --shadow-card: 0 4px 24px rgba(0, 0, 0, 0.6);
            --shadow-nav: 0 4px 30px rgba(0, 0, 0, 0.8);
            --tag-bg: #181818;
            --tag-active-bg: #C9A84C;
            --tag-active-text: #0B0B0B;
        }

        body {
            background-color: var(--bg-page);
            color: var(--text-primary);
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }

        .font-serif-title {
            font-family: 'Cormorant Garamond', serif;
        }

        /* Top Announcement Bar */
        .top-bar-announcement {
            background: linear-gradient(90deg, #1A1A1A 0%, #2A2A2A 50%, #1A1A1A 100%);
            color: #C9A84C;
            text-align: center;
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.08em;
            padding: 6px 12px;
            text-transform: uppercase;
        }

        /* Horizontal Scroll Carousel Styling */
        .destaques-scroll {
            display: flex;
            gap: 16px;
            overflow-x: auto;
            scroll-snap-type: x mandatory;
            padding: 6px 0 18px 0;
            scrollbar-width: thin;
            scrollbar-color: var(--gold) transparent;
        }

        .destaques-scroll::-webkit-scrollbar {
            height: 4px;
        }

        .destaques-scroll::-webkit-scrollbar-thumb {
            background: var(--gold);
            border-radius: 4px;
        }

        .product-card-snap {
            scroll-snap-align: start;
            flex: 0 0 240px;
        }

        @media (min-width: 768px) {
            .product-card-snap {
                flex: 0 0 280px;
            }
        }
    </style>
</head>

<body x-data="{
    toastMsg: '',
    showToast(msg) {
        this.toastMsg = msg;
        setTimeout(() => this.toastMsg = '', 3500);
    }
}" @toast.window="showToast($event.detail.message || $event.detail)">

    <!-- Top Announcement Bar -->
    <div class="top-bar-announcement flex items-center justify-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2">
            <path d="M12 2v20M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6" />
        </svg>
        <span>Frete Grátis nas compras acima de R$ 150,00 para Irecê e Região</span>
    </div>

    <!-- Livewire Navbar -->
    <livewire:storefront.navbar />

    <!-- Main Content -->
    <main class="min-h-screen">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-[#111111] text-[#888888] pt-14 pb-12 border-t border-white/10 mt-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10 mb-12">
                <!-- Brand Info -->
                <div class="space-y-4">
                    <span class="text-2xl font-bold tracking-wider text-white font-serif-title">
                        <span class="text-[#C9A84C]">DF</span> VARIEDADES
                    </span>
                    <p class="text-sm leading-relaxed text-[#999]">
                        Sua loja de perfumaria fina, cosméticos e presentes em Irecê e Luís Eduardo Magalhães. Qualidade
                        e sofisticação para você brilhar.
                    </p>
                    <div class="flex items-center gap-3 pt-2">
                        <span
                            class="inline-flex items-center gap-1.5 text-xs text-[#C9A84C] font-semibold bg-[#C9A84C]/10 px-3 py-1 rounded-full border border-[#C9A84C]/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-[#C9A84C] animate-pulse"></span> Loja Verificada
                        </span>
                    </div>
                </div>

                <!-- Categorias -->
                <div>
                    <h4 class="text-white text-sm font-semibold uppercase tracking-wider mb-4">Navegação</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('home') }}" class="hover:text-[#C9A84C] transition">Início</a></li>
                        <li><a href="{{ route('catalogo.tipo', ['tipo' => 'colecao', 'slug' => 'perfumaria-feminina']) }}"
                                class="hover:text-[#C9A84C] transition">Perfumaria Feminina</a></li>
                        <li><a href="{{ route('catalogo.tipo', ['tipo' => 'colecao', 'slug' => 'perfumaria-masculina']) }}"
                                class="hover:text-[#C9A84C] transition">Perfumaria Masculina</a></li>
                        <li><a href="{{ route('catalogo.tipo', ['tipo' => 'colecao', 'slug' => 'cuidados-com-o-cabelo']) }}"
                                class="hover:text-[#C9A84C] transition">Cabelos & Tratamento</a></li>
                        <li><a href="{{ route('catalogo.tipo', ['tipo' => 'colecao', 'slug' => 'presentes-kits-especiais']) }}"
                                class="hover:text-[#C9A84C] transition">Presentes & Kits</a></li>
                    </ul>
                </div>

                <!-- Atendimento -->
                <div>
                    <h4 class="text-white text-sm font-semibold uppercase tracking-wider mb-4">Atendimento</h4>
                    <ul class="space-y-2 text-sm">
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#C9A84C]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                            <span>WhatsApp: (74) 99999-9999</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#C9A84C]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            <span>Irecê - Bahia & Luís Eduardo Magalhães</span>
                        </li>
                        <li class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-[#C9A84C]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Seg a Sáb: 08:00 às 18:00</span>
                        </li>
                    </ul>
                </div>

                <!-- Pagamento & Segurança -->
                <div>
                    <h4 class="text-white text-sm font-semibold uppercase tracking-wider mb-4">Segurança & Pagamento
                    </h4>
                    <p class="text-xs text-[#999] mb-3">Aceitamos PIX com aprovação instantânea, Cartões de Crédito e
                        Débito.</p>
                    <div class="flex flex-wrap gap-2 text-xs text-white">
                        <span class="bg-white/5 border border-white/10 px-2.5 py-1 rounded">PIX</span>
                        <span class="bg-white/5 border border-white/10 px-2.5 py-1 rounded">Cartão de Crédito</span>
                        <span class="bg-white/5 border border-white/10 px-2.5 py-1 rounded">Débito</span>
                    </div>
                </div>
            </div>

            <div
                class="pt-8 border-t border-white/10 text-center text-xs text-[#666] flex flex-col md:flex-row justify-between items-center gap-4">
                <div>© {{ date('Y') }} DF Variedades. Todos os direitos reservados.</div>
                <div>Desenvolvido com padrão Laravel + Livewire</div>
            </div>
        </div>
    </footer>

    <!-- Global Reactive Drawers & Modals -->
    <livewire:storefront.cart-drawer />
    <livewire:storefront.favorites-drawer />
    <livewire:storefront.checkout-wizard />
    <livewire:storefront.waitlist-modal />

    <!-- Global Toast Notification -->
    <div x-cloak x-show="toastMsg" x-transition.opacity.duration.300ms
        class="fixed bottom-6 right-6 z-50 bg-[#1A1A1A] text-white px-5 py-3 rounded-xl shadow-2xl border border-[#C9A84C]/40 flex items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-[#C9A84C]" fill="none" stroke="currentColor"
            stroke-width="2" viewBox="0 0 24 24">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
            <polyline points="22 4 12 14.01 9 11.01" />
        </svg>
        <span x-text="toastMsg" class="text-sm font-medium"></span>
    </div>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    @fluxScripts
</body>

</html>
