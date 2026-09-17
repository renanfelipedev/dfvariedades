<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        @include('partials.head')
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        
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
    <body class="min-h-screen bg-[#FAFAF8] antialiased text-zinc-800 flex flex-col justify-between selection:bg-[#C9A84C] selection:text-black">
        
        <!-- Ambient Warm Glow Background -->
        <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
            <div class="absolute -top-32 left-1/2 -translate-x-1/2 w-[600px] h-[350px] bg-[#C9A84C]/15 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 right-0 w-[400px] h-[300px] bg-[#B8892E]/10 rounded-full blur-3xl"></div>
        </div>

        <!-- Top Header Navigation -->
        <header class="relative z-10 w-full px-4 sm:px-8 py-3.5 flex items-center justify-between border-b border-zinc-200/80 bg-white/80 backdrop-blur-md shadow-xs">
            <a href="{{ route('home') }}" class="flex items-center gap-2 font-serif-title text-xl font-bold tracking-wider text-zinc-900 hover:opacity-90 transition">
                <span class="text-[#B8892E]">DF</span> VARIEDADES
            </a>

            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-xs font-semibold text-zinc-700 hover:text-[#B8892E] transition py-1.5 px-3.5 rounded-full border border-zinc-200 bg-white hover:bg-zinc-50 shadow-xs">
                <svg class="w-3.5 h-3.5 text-[#B8892E]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                <span>Voltar para a Loja</span>
            </a>
        </header>

        <!-- Main Card Area -->
        <main class="relative z-10 flex-1 flex items-center justify-center p-4 sm:p-6 md:p-10 my-4">
            <div class="w-full max-w-md">
                <div class="p-6 sm:p-8 rounded-3xl bg-white border border-zinc-200/90 shadow-xl shadow-zinc-900/5">
                    {{ $slot }}
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="relative z-10 w-full text-center py-4 text-xs text-zinc-500 border-t border-zinc-200/60 bg-white/50">
            <p>© {{ date('Y') }} DF Variedades — Perfumes, Cosméticos e Presentes Exclusivos.</p>
        </footer>

        @persist('toast')
            <flux:toast.group>
                <flux:toast />
            </flux:toast.group>
        @endpersist

        @fluxScripts
    </body>
</html>
