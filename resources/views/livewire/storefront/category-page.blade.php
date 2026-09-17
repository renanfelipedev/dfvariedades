<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 space-y-6">

    <!-- Header & Breadcrumbs -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 border-b border-[var(--border-card)] pb-6">
        <div>
            <div class="flex items-center gap-2 text-xs text-[var(--text-muted)] mb-1">
                <a href="{{ route('home') }}" class="hover:text-[var(--gold)]">Início</a>
                <span>/</span>
                <span class="text-[var(--text-primary)] font-medium">{{ $pageTitle }}</span>
            </div>
            <h1 class="font-serif-title text-3xl sm:text-4xl font-bold text-[var(--text-primary)]">
                {{ $pageTitle }} <span class="text-sm font-normal text-[var(--text-muted)]">({{ $total }} itens)</span>
            </h1>
        </div>

        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-xs font-semibold px-4 py-2 rounded-xl bg-[var(--bg-card)] border border-[var(--border-card)] hover:border-[var(--gold)] transition self-start sm:self-auto">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Voltar para a Loja</span>
        </a>
    </div>

    <!-- Toolbar: Search & Sort Filter -->
    <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center bg-[var(--bg-card)] p-3 rounded-2xl border border-[var(--border-card)] shadow-sm">
        <!-- Search inside category -->
        <div class="sm:col-span-8 flex items-center bg-[var(--bg-card-alt)] rounded-xl px-3 py-2">
            <svg class="w-4 h-4 text-[var(--text-muted)] mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input 
                type="text" 
                wire:model.live.debounce.300ms="search" 
                placeholder="Filtrar por nome ou descrição..." 
                class="w-full bg-transparent border-none outline-none text-xs text-[var(--text-primary)] placeholder-[var(--text-muted)]"
            />
        </div>

        <!-- Sort dropdown -->
        <div class="sm:col-span-4">
            <select wire:model.live="sort" class="w-full bg-[var(--bg-card-alt)] border border-[var(--border-card)] rounded-xl px-3 py-2 text-xs font-medium text-[var(--text-primary)] outline-none cursor-pointer">
                <option value="padrao">Ordenar: Padrão</option>
                <option value="menor_maior">Preço: Menor para Maior</option>
                <option value="maior_menor">Preço: Maior para Menor</option>
                <option value="promocoes">Maiores Descontos (% OFF)</option>
            </select>
        </div>
    </div>

    <!-- Subcategories Pill Strip -->
    @if($subcategories->count() > 0)
        <div class="flex items-center gap-2 overflow-x-auto py-1 scrollbar-none">
            <button 
                wire:click="filterSubcategory(null)" 
                class="px-4 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition cursor-pointer {{ is_null($subcategoriaId) ? 'bg-[var(--tag-active-bg)] text-[var(--tag-active-text)] shadow-sm' : 'bg-[var(--tag-bg)] text-[var(--text-secondary)] hover:bg-[var(--gold)]/20' }}"
            >
                Todos
            </button>
            @foreach($subcategories as $sub)
                <button 
                    wire:click="filterSubcategory({{ $sub->id }})" 
                    class="px-4 py-2 rounded-xl text-xs font-semibold whitespace-nowrap transition cursor-pointer {{ $subcategoriaId === $sub->id ? 'bg-[var(--tag-active-bg)] text-[var(--tag-active-text)] shadow-sm' : 'bg-[var(--tag-bg)] text-[var(--text-secondary)] hover:bg-[var(--gold)]/20' }}"
                >
                    {{ $sub->nome }}
                </button>
            @endforeach
        </div>
    @endif

    <!-- Product Grid -->
    @if($produtos->count() > 0)
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 sm:gap-6 pt-2">
            @foreach($produtos as $p)
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
        <div class="bg-[var(--bg-card)] border border-[var(--border-card)] rounded-3xl p-12 text-center space-y-4 max-w-lg mx-auto">
            <div class="w-16 h-16 rounded-full bg-[var(--gold-bg)] text-[var(--gold)] mx-auto flex items-center justify-center">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            </div>
            <h3 class="text-xl font-bold font-serif-title text-[var(--text-primary)]">Nenhum produto encontrado</h3>
            <p class="text-sm text-[var(--text-muted)]">Tente ajustar seus termos de pesquisa ou remover os filtros aplicados.</p>
            <button wire:click="$set('search', '')" class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl bg-[var(--gold)] text-white text-xs font-bold shadow hover:opacity-90 transition">
                Limpar Filtros
            </button>
        </div>
    @endif

</div>
