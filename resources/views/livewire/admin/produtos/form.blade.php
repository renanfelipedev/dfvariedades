<div class="max-w-4xl mx-auto space-y-6">

    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-zinc-900 dark:text-zinc-100">
                {{ $produtoId ? 'Editar Produto' : 'Novo Produto' }}
            </h2>
            <p class="text-xs text-zinc-500">Preencha as informações completas para publicação no catálogo.</p>
        </div>

        <a href="{{ route('admin.produtos.index') }}" class="px-4 py-2 rounded-xl border border-zinc-300 dark:border-zinc-700 text-xs font-semibold hover:bg-zinc-100 dark:hover:bg-zinc-800">
            ← Voltar
        </a>
    </div>

    <!-- Form Container -->
    <form wire:submit="save" class="space-y-6">
        
        <!-- Bloco 1: Informações Principais -->
        <div class="p-6 rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-xs space-y-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-[#C9A84C]">Informações Básicas</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">Nome do Produto *</label>
                    <input type="text" wire:model.live.debounce.300ms="nome" placeholder="Ex: Lily Eau de Parfum 75ml" class="w-full bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2 text-xs text-zinc-900 dark:text-zinc-100 outline-none focus:border-[#C9A84C]">
                    @error('nome') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">Slug da URL *</label>
                    <input type="text" wire:model="slug" placeholder="lily-eau-de-parfum-75ml" class="w-full bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2 text-xs text-zinc-900 dark:text-zinc-100 outline-none focus:border-[#C9A84C]">
                    @error('slug') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">Marca</label>
                    <select wire:model="marca_id" class="w-full bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2 text-xs text-zinc-900 dark:text-zinc-100 outline-none focus:border-[#C9A84C]">
                        <option value="">Selecione uma marca...</option>
                        @foreach($marcas as $m)
                            <option value="{{ $m->id }}">{{ $m->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">Coleção</label>
                    <select wire:model.live="colecao_id" class="w-full bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2 text-xs text-zinc-900 dark:text-zinc-100 outline-none focus:border-[#C9A84C]">
                        <option value="">Selecione uma coleção...</option>
                        @foreach($colecoes as $c)
                            <option value="{{ $c->id }}">{{ $c->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">Categoria</label>
                    <select wire:model="categoria_id" class="w-full bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2 text-xs text-zinc-900 dark:text-zinc-100 outline-none focus:border-[#C9A84C]">
                        <option value="">Selecione uma categoria...</option>
                        @foreach($categorias as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Bloco 2: Preços e Estoque -->
        <div class="p-6 rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-xs space-y-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-[#C9A84C]">Preço & Estoque</h3>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">Preço Normal (R$) *</label>
                    <input type="text" wire:model="preco" placeholder="199.90" class="w-full bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2 text-xs text-zinc-900 dark:text-zinc-100 outline-none focus:border-[#C9A84C]">
                    @error('preco') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">Preço Promocional (R$)</label>
                    <input type="text" wire:model="preco_promocional" placeholder="149.90 (opcional)" class="w-full bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2 text-xs text-zinc-900 dark:text-zinc-100 outline-none focus:border-[#C9A84C]">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">Quantidade em Estoque *</label>
                    <input type="number" wire:model="estoque" min="0" class="w-full bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2 text-xs text-zinc-900 dark:text-zinc-100 outline-none focus:border-[#C9A84C]">
                    @error('estoque') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">Alerta de Estoque Mínimo (unid.)</label>
                    <input type="number" wire:model="estoque_minimo" min="0" placeholder="Padrão: 5" class="w-full bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2 text-xs text-zinc-900 dark:text-zinc-100 outline-none focus:border-[#C9A84C]">
                    <span class="text-[10px] text-zinc-400">Gera alerta no painel quando o estoque for menor ou igual a este valor.</span>
                    @error('estoque_minimo') <span class="text-[10px] text-red-500 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">Código SKU / Referência</label>
                    <input type="text" wire:model="sku" placeholder="DFV-00123" class="w-full bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2 text-xs text-zinc-900 dark:text-zinc-100 outline-none focus:border-[#C9A84C]">
                </div>
            </div>
        </div>

        <!-- Bloco 3: Imagens & Fotos do Produto -->
        <div class="p-6 rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-xs space-y-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-wider text-[#C9A84C]">Fotos & Imagens do Produto</h3>
                    <p class="text-[11px] text-zinc-400 mt-0.5">Faça o upload de arquivos do seu computador ou adicione links de imagens externas.</p>
                </div>
            </div>

            <!-- Upload Dropzone -->
            <div class="space-y-3">
                <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300">
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
                                            class="p-1.5 rounded-lg bg-red-600 text-white hover:bg-red-500 transition"
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
                                            class="p-1.5 rounded-lg bg-[#C9A84C] text-black hover:bg-[#D4A843] transition font-bold text-[10px]"
                                            title="Tornar imagem de Capa">
                                            ★
                                        </button>
                                    @endif
                                    <button 
                                        type="button" 
                                        wire:click="removerImagemExistente({{ $idx }})"
                                        class="p-1.5 rounded-lg bg-red-600 text-white hover:bg-red-500 transition"
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
                <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300">
                    Ou adicione uma imagem por Link / URL Externa
                </label>
                <div class="flex gap-2">
                    <input 
                        type="url" 
                        wire:model="novaImagemUrl" 
                        placeholder="https://exemplo.com/imagem-do-produto.jpg" 
                        class="flex-1 bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2 text-xs text-zinc-900 dark:text-zinc-100 outline-none focus:border-[#C9A84C]"
                    >
                    <button 
                        type="button" 
                        wire:click="adicionarImagemPorUrl" 
                        class="px-4 py-2 rounded-xl bg-zinc-800 hover:bg-zinc-700 text-zinc-200 font-semibold text-xs border border-zinc-700 transition">
                        + Adicionar URL
                    </button>
                </div>
                @error('novaImagemUrl') <span class="text-[10px] text-red-500 block">{{ $message }}</span> @enderror
            </div>
        </div>

        <!-- Bloco 4: Descrição & Detalhes -->
        <div class="p-6 rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-xs space-y-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-[#C9A84C]">Descrição & Detalhes</h3>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">Descrição Curta</label>
                <textarea wire:model="descricao" rows="2" placeholder="Resumo dos benefícios e notas olfativas..." class="w-full bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 rounded-xl p-3 text-xs text-zinc-900 dark:text-zinc-100 outline-none focus:border-[#C9A84C]"></textarea>
            </div>

            <div>
                <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">Detalhes Adicionais (Modo de uso, composição, etc.)</label>
                <textarea wire:model="detalhes" rows="3" placeholder="Informações detalhadas..." class="w-full bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 rounded-xl p-3 text-xs text-zinc-900 dark:text-zinc-100 outline-none focus:border-[#C9A84C]"></textarea>
            </div>
        </div>

        <!-- Bloco 5: Destaques e Promoções Especiais -->
        <div class="p-6 rounded-3xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 shadow-xs space-y-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-[#C9A84C]">Destaques na Vitrine</h3>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                <label class="flex items-center gap-2 p-3 rounded-2xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 cursor-pointer">
                    <input type="checkbox" wire:model="destaque" class="text-[#C9A84C] rounded">
                    <span class="text-xs font-semibold text-zinc-800 dark:text-zinc-200">Destaque Geral</span>
                </label>

                <label class="flex items-center gap-2 p-3 rounded-2xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 cursor-pointer">
                    <input type="checkbox" wire:model="escolhido" class="text-[#C9A84C] rounded">
                    <span class="text-xs font-semibold text-zinc-800 dark:text-zinc-200">Escolhidos p/ Você</span>
                </label>

                <label class="flex items-center gap-2 p-3 rounded-2xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 cursor-pointer">
                    <input type="checkbox" wire:model="presente" class="text-[#C9A84C] rounded">
                    <span class="text-xs font-semibold text-zinc-800 dark:text-zinc-200">P/ Presentear</span>
                </label>

                <label class="flex items-center gap-2 p-3 rounded-2xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 cursor-pointer">
                    <input type="checkbox" wire:model="cabelo" class="text-[#C9A84C] rounded">
                    <span class="text-xs font-semibold text-zinc-800 dark:text-zinc-200">Top Cabelo</span>
                </label>

                <label class="flex items-center gap-2 p-3 rounded-2xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 cursor-pointer">
                    <input type="checkbox" wire:model="flash_deal" class="text-[#C9A84C] rounded">
                    <span class="text-xs font-semibold text-red-500 font-bold">Oferta Relâmpago</span>
                </label>

                <label class="flex items-center gap-2 p-3 rounded-2xl bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 cursor-pointer">
                    <input type="checkbox" wire:model="ativo" class="text-[#C9A84C] rounded">
                    <span class="text-xs font-semibold text-emerald-600 font-bold">Produto Ativo</span>
                </label>
            </div>

            @if($flash_deal)
                <div class="pt-2">
                    <label class="block text-xs font-semibold text-zinc-700 dark:text-zinc-300 mb-1">Data/Hora de Término da Oferta Relâmpago</label>
                    <input type="datetime-local" wire:model="flash_deal_fim" class="bg-zinc-50 dark:bg-zinc-800/60 border border-zinc-200 dark:border-zinc-800 rounded-xl px-3 py-2 text-xs text-zinc-900 dark:text-zinc-100 outline-none">
                </div>
            @endif
        </div>

        <!-- Submit Buttons -->
        <div class="flex items-center justify-end gap-3 pt-4">
            <a href="{{ route('admin.produtos.index') }}" class="px-5 py-2.5 rounded-xl border border-zinc-300 dark:border-zinc-700 text-xs font-semibold hover:bg-zinc-100 dark:hover:bg-zinc-800">
                Cancelar
            </a>
            <button type="submit" class="px-8 py-3 rounded-xl bg-[#C9A84C] text-black font-bold text-xs shadow hover:opacity-90 transition cursor-pointer">
                Salvar Produto
            </button>
        </div>

    </form>

</div>
