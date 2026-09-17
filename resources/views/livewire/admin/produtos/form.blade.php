<div class="max-w-4xl mx-auto space-y-6 pb-32" 
    x-data="{ 
        activeSection: 'basico',
        scrollTo(id) {
            this.activeSection = id;
            document.getElementById(id)?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }
    }"
    @scroll-to-top.window="window.scrollTo({ top: 0, behavior: 'smooth' })"
    @keydown.window.prevent.ctrl.s="$wire.save()"
    @keydown.window.prevent.cmd.s="$wire.save()">

    <!-- Sticky Header com Ações Rápidas -->
    <div class="sticky top-16 z-20 -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8 py-3 bg-white/90 dark:bg-zinc-950/90 backdrop-blur-md border-b border-zinc-200 dark:border-zinc-800 shadow-xs flex items-center justify-between transition-all">
        <div class="flex items-center gap-3 min-w-0">
            <a href="{{ route('admin.produtos.index') }}" 
                class="p-2 rounded-xl border border-zinc-200 dark:border-zinc-800 text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-white hover:bg-zinc-100 dark:hover:bg-zinc-800 transition shrink-0"
                title="Voltar para a listagem">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </a>
            <div class="truncate">
                <div class="flex items-center gap-2">
                    <h2 class="text-base font-bold text-zinc-900 dark:text-zinc-100 truncate">
                        {{ $produtoId ? 'Editar: ' . ($nome ?: 'Sem título') : 'Novo Produto' }}
                    </h2>
                    <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $ativo ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20' : 'bg-zinc-500/10 text-zinc-500 border border-zinc-500/20' }}">
                        {{ $ativo ? '● Ativo' : '○ Inativo' }}
                    </span>
                </div>
                <p class="text-[11px] text-zinc-400 truncate">Atalho: Ctrl + S para salvar</p>
            </div>
        </div>

        <div class="flex items-center gap-2 shrink-0">
            <a href="{{ route('admin.produtos.index') }}" class="hidden sm:inline-flex px-3.5 py-2 rounded-xl border border-zinc-300 dark:border-zinc-700 text-xs font-semibold text-zinc-700 dark:text-zinc-300 hover:bg-zinc-100 dark:hover:bg-zinc-800 transition">
                Cancelar
            </a>
            
            @if(!$produtoId)
                <button 
                    type="button" 
                    wire:click="saveAndCreateAnother"
                    wire:loading.attr="disabled"
                    class="hidden md:inline-flex px-4 py-2 rounded-xl border border-[#C9A84C]/50 hover:bg-[#C9A84C]/10 text-[#C9A84C] font-bold text-xs transition items-center gap-1.5 cursor-pointer disabled:opacity-50">
                    <span>+ Salvar & Cadastrar Outro</span>
                </button>
            @endif

            <button 
                type="button" 
                wire:click="save"
                wire:loading.attr="disabled"
                class="px-5 py-2 rounded-xl bg-[#C9A84C] hover:bg-[#D4A843] text-black font-extrabold text-xs shadow hover:shadow-md transition flex items-center gap-1.5 cursor-pointer disabled:opacity-50">
                <svg wire:loading.remove wire:target="save, saveAndCreateAnother" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <svg wire:loading wire:target="save, saveAndCreateAnother" class="w-4 h-4 animate-spin text-black" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span wire:loading.remove wire:target="save, saveAndCreateAnother">{{ $produtoId ? 'Atualizar Produto' : 'Salvar Produto' }}</span>
                <span wire:loading wire:target="save, saveAndCreateAnother">Salvando...</span>
            </button>
        </div>
    </div>

    <!-- Navegação Rápida entre Seções (Pill Tabs) -->
    <div class="flex items-center gap-1.5 overflow-x-auto pb-1 text-xs no-scrollbar">
        <button type="button" @click="scrollTo('sec-basico')" class="px-3 py-1.5 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-zinc-700 dark:text-zinc-300 hover:border-[#C9A84C] hover:text-[#C9A84C] font-semibold whitespace-nowrap transition cursor-pointer">
            📌 Básicas
        </button>
        <button type="button" @click="scrollTo('sec-preco')" class="px-3 py-1.5 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-zinc-700 dark:text-zinc-300 hover:border-[#C9A84C] hover:text-[#C9A84C] font-semibold whitespace-nowrap transition cursor-pointer">
            💰 Preço & Estoque
        </button>
        <button type="button" @click="scrollTo('sec-fotos')" class="px-3 py-1.5 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-zinc-700 dark:text-zinc-300 hover:border-[#C9A84C] hover:text-[#C9A84C] font-semibold whitespace-nowrap transition cursor-pointer">
            📸 Fotos ({{ count($imagensExistentes) + count($novasImagens) }})
        </button>
        <button type="button" @click="scrollTo('sec-descricao')" class="px-3 py-1.5 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-zinc-700 dark:text-zinc-300 hover:border-[#C9A84C] hover:text-[#C9A84C] font-semibold whitespace-nowrap transition cursor-pointer">
            📝 Descrição
        </button>
        <button type="button" @click="scrollTo('sec-destaques')" class="px-3 py-1.5 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-white dark:bg-zinc-900 text-zinc-700 dark:text-zinc-300 hover:border-[#C9A84C] hover:text-[#C9A84C] font-semibold whitespace-nowrap transition cursor-pointer">
            ⭐ Destaques
        </button>
    </div>

    <!-- Alerta Visual de Erros de Validação -->
    @if ($errors->any())
        <div class="p-4 rounded-2xl bg-red-500/10 border border-red-500/30 text-red-500 space-y-2">
            <div class="flex items-center gap-2 font-bold text-xs">
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Por favor, corrija os erros abaixo antes de salvar:</span>
            </div>
            <ul class="list-disc list-inside text-xs pl-2 space-y-0.5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form Principal -->
    <form wire:submit="save" id="produto-form" class="space-y-6">
        
        <!-- Bloco 1: Informações Principais -->
        <div id="sec-basico" class="scroll-mt-36 p-6 rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-zinc-100 dark:border-zinc-800 pb-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#C9A84C] flex items-center gap-2">
                    <span>📌</span> Informações Básicas
                </h3>
                <span class="text-[11px] text-zinc-400">* Campos obrigatórios</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="prod-nome" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1 cursor-pointer">Nome do Produto *</label>
                    <input id="prod-nome" type="text" wire:model.live.debounce.300ms="nome" placeholder="Ex: Malbec Gold Desodorante Colônia 100ml" class="w-full bg-zinc-50 dark:bg-zinc-800/60 border @error('nome') border-red-500 @else border-zinc-200 dark:border-zinc-800 @enderror rounded-xl px-3 py-2.5 text-xs text-zinc-900 dark:text-zinc-100 outline-none focus:border-[#C9A84C]">
                    @error('nome') <span class="text-[10px] text-red-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="prod-slug" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1 cursor-pointer">Slug da URL *</label>
                    <input id="prod-slug" type="text" wire:model="slug" placeholder="malbec-gold-colonia-100ml" class="w-full bg-zinc-50 dark:bg-zinc-800/60 border @error('slug') border-red-500 @else border-zinc-200 dark:border-zinc-800 @enderror rounded-xl px-3 py-2.5 text-xs text-zinc-900 dark:text-zinc-100 outline-none focus:border-[#C9A84C]">
                    <span class="text-[10px] text-zinc-400">Identificador amigável na URL do catálogo.</span>
                    @error('slug') <span class="text-[10px] text-red-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="prod-marca" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1 cursor-pointer">Marca</label>
                    <select id="prod-marca" wire:model="marca_id" class="w-full bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-xs text-zinc-900 dark:text-zinc-100 outline-none focus:border-[#C9A84C]">
                        <option value="">Selecione uma marca...</option>
                        @foreach($marcas as $m)
                            <option value="{{ $m->id }}">{{ $m->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="prod-colecao" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1 cursor-pointer">Coleção</label>
                    <select id="prod-colecao" wire:model.live="colecao_id" class="w-full bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-xs text-zinc-900 dark:text-zinc-100 outline-none focus:border-[#C9A84C]">
                        <option value="">Selecione uma coleção...</option>
                        @foreach($colecoes as $c)
                            <option value="{{ $c->id }}">{{ $c->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="prod-categoria" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1 cursor-pointer">Categoria</label>
                    <select id="prod-categoria" wire:model="categoria_id" class="w-full bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-xs text-zinc-900 dark:text-zinc-100 outline-none focus:border-[#C9A84C]">
                        <option value="">Selecione uma categoria...</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Bloco 2: Preços e Estoque -->
        <div id="sec-preco" class="scroll-mt-36 p-6 rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-zinc-100 dark:border-zinc-800 pb-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#C9A84C] flex items-center gap-2">
                    <span>💰</span> Preço & Estoque
                </h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label for="prod-preco" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1 cursor-pointer">Preço Normal (R$) *</label>
                    <input id="prod-preco" type="text" wire:model="preco" placeholder="199,90" class="w-full bg-zinc-50 dark:bg-zinc-800/60 border @error('preco') border-red-500 @else border-zinc-200 dark:border-zinc-800 @enderror rounded-xl px-3 py-2.5 text-xs text-zinc-900 dark:text-zinc-100 outline-none focus:border-[#C9A84C]">
                    @error('preco') <span class="text-[10px] text-red-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="prod-preco-promocional" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1 cursor-pointer">Preço Promocional (R$)</label>
                    <input id="prod-preco-promocional" type="text" wire:model="preco_promocional" placeholder="149,90 (opcional)" class="w-full bg-zinc-50 dark:bg-zinc-800/60 border @error('preco_promocional') border-red-500 @else border-zinc-200 dark:border-zinc-800 @enderror rounded-xl px-3 py-2.5 text-xs text-zinc-900 dark:text-zinc-100 outline-none focus:border-[#C9A84C]">
                    <span class="text-[10px] text-zinc-400">Se preenchido, exibirá selo de desconto.</span>
                    @error('preco_promocional') <span class="text-[10px] text-red-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="prod-estoque" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1 cursor-pointer">Quantidade em Estoque *</label>
                    <input id="prod-estoque" type="number" wire:model="estoque" min="0" class="w-full bg-zinc-50 dark:bg-zinc-800/60 border @error('estoque') border-red-500 @else border-zinc-200 dark:border-zinc-800 @enderror rounded-xl px-3 py-2.5 text-xs text-zinc-900 dark:text-zinc-100 outline-none focus:border-[#C9A84C]">
                    @error('estoque') <span class="text-[10px] text-red-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="prod-estoque-minimo" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1 cursor-pointer">Alerta de Estoque Mínimo (unid.)</label>
                    <input id="prod-estoque-minimo" type="number" wire:model="estoque_minimo" min="0" placeholder="Padrão: 5" class="w-full bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-xs text-zinc-900 dark:text-zinc-100 outline-none focus:border-[#C9A84C]">
                    <span class="text-[10px] text-zinc-400">Gera alerta no painel quando o estoque for menor ou igual a este valor.</span>
                    @error('estoque_minimo') <span class="text-[10px] text-red-500 font-semibold block mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label for="prod-sku" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1 cursor-pointer">Código SKU / Referência</label>
                    <input id="prod-sku" type="text" wire:model="sku" placeholder="DFV-00123" class="w-full bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2.5 text-xs text-zinc-900 dark:text-zinc-100 outline-none focus:border-[#C9A84C]">
                    <span class="text-[10px] text-zinc-400">Identificador interno para controle de inventário.</span>
                </div>
            </div>
        </div>

        <!-- Bloco 3: Imagens & Fotos do Produto -->
        <div id="sec-fotos" class="scroll-mt-36 p-6 rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-xs space-y-6">
            <div class="flex items-center justify-between border-b border-zinc-100 dark:border-zinc-800 pb-3">
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-[#C9A84C] flex items-center gap-2">
                        <span>📸</span> Fotos & Galeria do Produto
                    </h3>
                    <p class="text-[11px] text-zinc-400 mt-0.5">Faça upload de fotos do seu computador ou adicione links de imagens externas.</p>
                </div>
            </div>

            <!-- Upload Dropzone -->
            <div class="space-y-3">
                <label for="file-upload-input" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 cursor-pointer">
                    Enviar Imagens do Computador (JPG, PNG, WEBP, AVIF)
                </label>
                
                <div class="relative border-2 border-dashed border-zinc-300 dark:border-zinc-700 hover:border-[#C9A84C] rounded-2xl p-6 text-center transition bg-zinc-50/50 dark:bg-zinc-800/30 group">
                    <input 
                        type="file" 
                        wire:model="novasImagens" 
                        multiple 
                        accept="image/*"
                        id="file-upload-input"
                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
                    >
                    <div class="space-y-2 pointer-events-none">
                        <div class="w-12 h-12 mx-auto rounded-2xl bg-[#C9A84C]/10 text-[#C9A84C] flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-xs font-bold text-zinc-900 dark:text-white group-hover:text-[#C9A84C] transition">
                                Clique para selecionar imagens ou arraste para cá
                            </span>
                            <p class="text-[11px] text-zinc-400 mt-0.5">Você pode selecionar várias fotos de uma vez (Máx. 10MB por foto)</p>
                        </div>
                    </div>
                </div>

                <!-- Uploading Indicator -->
                <div wire:loading wire:target="novasImagens" class="text-xs font-semibold text-[#C9A84C] flex items-center gap-2">
                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Carregando imagens...
                </div>

                @error('novasImagens.*') 
                    <span class="text-xs text-red-500 font-semibold block">{{ $message }}</span> 
                @enderror
            </div>

            <!-- Previews of Newly Uploaded Images -->
            @if(!empty($novasImagens))
                <div class="space-y-2 pt-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-emerald-500">
                        Novas Imagens Prontas para Salvar ({{ count($novasImagens) }})
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-3">
                        @foreach($novasImagens as $index => $nova)
                            @if($nova)
                                <div class="relative group rounded-xl overflow-hidden border-2 border-emerald-500/40 bg-zinc-900 aspect-square shadow-sm">
                                    <img src="{{ $nova->temporaryUrl() }}" alt="Preview" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition flex items-center justify-center p-1">
                                        <button 
                                            type="button" 
                                            wire:click="removerNovaImagem({{ $index }})"
                                            class="p-1.5 rounded-lg bg-red-600 text-white hover:bg-red-500 transition cursor-pointer"
                                            title="Remover foto">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                    <span class="absolute bottom-1 right-1 text-[9px] font-bold bg-emerald-500 text-black px-1.5 py-0.5 rounded shadow">
                                        Nova
                                    </span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Existing Images Gallery -->
            @if(!empty($imagensExistentes))
                <div class="space-y-2 pt-2">
                    <div class="flex items-center justify-between">
                        <label class="block text-xs font-bold uppercase tracking-wider text-zinc-400">
                            Galeria Atual do Produto ({{ count($imagensExistentes) }})
                        </label>
                        <span class="text-[10px] text-zinc-400">A primeira foto com selo dourado é a <strong>Capa</strong></span>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 md:grid-cols-6 gap-3">
                        @foreach($imagensExistentes as $idx => $imgUrl)
                            <div class="relative group rounded-xl overflow-hidden border {{ $idx === 0 ? 'border-[#C9A84C] ring-2 ring-[#C9A84C]/30' : 'border-zinc-200 dark:border-zinc-700' }} bg-zinc-900 aspect-square shadow-sm">
                                <img src="{{ $imgUrl }}" alt="Foto {{ $idx + 1 }}" class="w-full h-full object-cover">
                                
                                @if($idx === 0)
                                    <span class="absolute top-1 left-1 text-[9px] font-extrabold bg-[#C9A84C] text-black px-1.5 py-0.5 rounded shadow">
                                        ★ CAPA
                                    </span>
                                @endif

                                <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition flex items-center justify-center gap-1.5 p-1">
                                    @if($idx !== 0)
                                        <button 
                                            type="button" 
                                            wire:click="definirCapaExistente({{ $idx }})"
                                            class="p-1.5 rounded-lg bg-[#C9A84C] text-black hover:bg-[#D4A843] transition font-bold text-[10px] cursor-pointer"
                                            title="Tornar imagem de Capa">
                                            ★ Capa
                                        </button>
                                    @endif
                                    <button 
                                        type="button" 
                                        wire:click="removerImagemExistente({{ $idx }})"
                                        class="p-1.5 rounded-lg bg-red-600 text-white hover:bg-red-500 transition cursor-pointer"
                                        title="Remover imagem">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Optional: Add Image via External URL -->
            <div class="pt-4 border-t border-zinc-100 dark:border-zinc-800 space-y-2">
                <label for="prod-nova-imagem-url" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 cursor-pointer">
                    Ou adicione uma imagem por Link / URL Externa
                </label>
                <div class="flex gap-2">
                    <input 
                        id="prod-nova-imagem-url"
                        type="url" 
                        wire:model="novaImagemUrl" 
                        placeholder="https://exemplo.com/imagem-do-produto.jpg" 
                        class="flex-1 bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2 text-xs text-zinc-900 dark:text-zinc-100 outline-none focus:border-[#C9A84C]"
                    >
                    <button 
                        type="button" 
                        wire:click="adicionarImagemPorUrl" 
                        class="px-4 py-2 rounded-xl bg-zinc-800 hover:bg-zinc-700 text-zinc-200 font-semibold text-xs border border-zinc-700 transition cursor-pointer">
                        + Adicionar URL
                    </button>
                </div>
                @error('novaImagemUrl') <span class="text-[10px] text-red-500 font-semibold block mt-0.5">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Bloco 4: Descrição & Detalhes -->
        <div id="sec-descricao" class="scroll-mt-36 p-6 rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-zinc-100 dark:border-zinc-800 pb-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#C9A84C] flex items-center gap-2">
                    <span>📝</span> Descrição & Detalhes do Produto
                </h3>
            </div>

            <div>
                <label for="prod-descricao" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1 cursor-pointer">Descrição Curta</label>
                <textarea id="prod-descricao" wire:model="descricao" rows="3" placeholder="Resumo dos benefícios, notas olfativas e principais destaques..." class="w-full bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 rounded-xl p-3 text-xs text-zinc-900 dark:text-zinc-100 outline-none focus:border-[#C9A84C]"></textarea>
            </div>

            <div>
                <label for="prod-detalhes" class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1 cursor-pointer">Detalhes Adicionais (Modo de uso, composição, especificações)</label>
                <textarea id="prod-detalhes" wire:model="detalhes" rows="4" placeholder="Informações detalhadas para orientar o cliente no momento da compra..." class="w-full bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 rounded-xl p-3 text-xs text-zinc-900 dark:text-zinc-100 outline-none focus:border-[#C9A84C]"></textarea>
            </div>
        </div>

        <!-- Bloco 5: Destaques e Promoções Especiais -->
        <div id="sec-destaques" class="scroll-mt-36 p-6 rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-xs space-y-4">
            <div class="flex items-center justify-between border-b border-zinc-100 dark:border-zinc-800 pb-3">
                <h3 class="text-xs font-bold uppercase tracking-wider text-[#C9A84C] flex items-center gap-2">
                    <span>⭐</span> Destaques na Vitrine & Visibilidade
                </h3>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                <label for="prod-destaque" class="flex items-center gap-2.5 p-3.5 rounded-2xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 hover:border-[#C9A84C] cursor-pointer transition">
                    <input id="prod-destaque" type="checkbox" wire:model="destaque" class="text-[#C9A84C] rounded accent-[#C9A84C]">
                    <span class="text-xs font-semibold text-zinc-800 dark:text-zinc-200">Destaque Geral</span>
                </label>

                <label for="prod-escolhido" class="flex items-center gap-2.5 p-3.5 rounded-2xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 hover:border-[#C9A84C] cursor-pointer transition">
                    <input id="prod-escolhido" type="checkbox" wire:model="escolhido" class="text-[#C9A84C] rounded accent-[#C9A84C]">
                    <span class="text-xs font-semibold text-zinc-800 dark:text-zinc-200">Escolhidos p/ Você</span>
                </label>

                <label for="prod-presente" class="flex items-center gap-2.5 p-3.5 rounded-2xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 hover:border-[#C9A84C] cursor-pointer transition">
                    <input id="prod-presente" type="checkbox" wire:model="presente" class="text-[#C9A84C] rounded accent-[#C9A84C]">
                    <span class="text-xs font-semibold text-zinc-800 dark:text-zinc-200">P/ Presentear</span>
                </label>

                <label for="prod-cabelo" class="flex items-center gap-2.5 p-3.5 rounded-2xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 hover:border-[#C9A84C] cursor-pointer transition">
                    <input id="prod-cabelo" type="checkbox" wire:model="cabelo" class="text-[#C9A84C] rounded accent-[#C9A84C]">
                    <span class="text-xs font-semibold text-zinc-800 dark:text-zinc-200">Top Cabelo</span>
                </label>

                <label for="prod-flash-deal" class="flex items-center gap-2.5 p-3.5 rounded-2xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 hover:border-red-500 cursor-pointer transition">
                    <input id="prod-flash-deal" type="checkbox" wire:model.live="flash_deal" class="text-red-500 rounded accent-red-500">
                    <span class="text-xs font-bold text-red-500">Oferta Relâmpago</span>
                </label>

                <label for="prod-ativo" class="flex items-center gap-2.5 p-3.5 rounded-2xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 hover:border-emerald-500 cursor-pointer transition">
                    <input id="prod-ativo" type="checkbox" wire:model="ativo" class="text-emerald-600 rounded accent-emerald-600">
                    <span class="text-xs font-bold text-emerald-600">Produto Ativo na Loja</span>
                </label>
            </div>

            @if($flash_deal)
                <div class="pt-2 p-4 rounded-2xl bg-red-500/5 border border-red-500/20 space-y-2">
                    <label for="prod-flash-deal-fim" class="block text-xs font-bold text-red-600 dark:text-red-400 cursor-pointer">Data e Hora de Término da Oferta Relâmpago *</label>
                    <input id="prod-flash-deal-fim" type="datetime-local" wire:model="flash_deal_fim" class="bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl px-3 py-2 text-xs text-zinc-900 dark:text-zinc-100 outline-none focus:border-red-500">
                    <span class="text-[10px] text-zinc-400 block">Após essa data/hora, o produto deixará a vitrine de ofertas relâmpago automaticamente.</span>
                </div>
            @endif
        </div>

    </form>

    <!-- Barra Flutuante Fixa de Ação (Sticky Bottom Bar) -->
    <div class="fixed bottom-4 left-4 right-4 sm:left-auto sm:right-6 sm:max-w-4xl z-40 lg:left-72 lg:right-8 transition-all">
        <div class="p-3 sm:px-5 rounded-2xl bg-zinc-950/95 dark:bg-zinc-900/95 backdrop-blur-xl border border-zinc-800 shadow-2xl flex items-center justify-between gap-3 text-white">
            <div class="flex items-center gap-3 truncate min-w-0">
                <div class="w-8 h-8 rounded-xl bg-[#C9A84C]/20 border border-[#C9A84C]/30 text-[#C9A84C] flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
                <div class="truncate hidden sm:block">
                    <div class="text-xs font-bold truncate text-zinc-100">
                        {{ $nome ?: ($produtoId ? 'Editando Produto' : 'Novo Produto em Cadastro') }}
                    </div>
                    <div class="text-[10px] text-zinc-400 flex items-center gap-2">
                        <span>Preço: <strong class="text-[#C9A84C]">R$ {{ $preco ?: '0,00' }}</strong></span>
                        <span>•</span>
                        <span>Estoque: <strong class="text-white">{{ $estoque }} un.</strong></span>
                        @if($sku)
                            <span>•</span>
                            <span>SKU: <strong class="text-zinc-300">{{ $sku }}</strong></span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('admin.produtos.index') }}" 
                    class="px-3.5 py-2 rounded-xl border border-zinc-700 hover:bg-zinc-800 text-zinc-300 hover:text-white font-semibold text-xs transition">
                    Cancelar
                </a>

                @if(!$produtoId)
                    <button 
                        type="button" 
                        wire:click="saveAndCreateAnother"
                        wire:loading.attr="disabled"
                        class="hidden sm:inline-flex px-4 py-2 rounded-xl border border-[#C9A84C]/60 hover:bg-[#C9A84C]/10 text-[#C9A84C] font-bold text-xs transition items-center gap-1.5 cursor-pointer disabled:opacity-50">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        <span>Salvar & Cadastrar Outro</span>
                    </button>
                @endif

                <button 
                    type="button" 
                    wire:click="save"
                    wire:loading.attr="disabled"
                    class="px-6 py-2.5 rounded-xl bg-[#C9A84C] hover:bg-[#D4A843] text-black font-extrabold text-xs shadow-lg hover:shadow-xl transition flex items-center gap-2 cursor-pointer disabled:opacity-50">
                    <svg wire:loading.remove wire:target="save, saveAndCreateAnother" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" />
                    </svg>
                    <svg wire:loading wire:target="save, saveAndCreateAnother" class="w-4 h-4 animate-spin text-black" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span wire:loading.remove wire:target="save, saveAndCreateAnother">{{ $produtoId ? 'Atualizar Produto' : 'Salvar Produto' }}</span>
                    <span wire:loading wire:target="save, saveAndCreateAnother">Salvando...</span>
                </button>
            </div>
        </div>
    </div>

</div>
