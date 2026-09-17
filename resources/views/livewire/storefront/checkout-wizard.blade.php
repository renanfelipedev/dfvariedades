<div @keydown.window.escape="$wire.close()">
    <!-- Backdrop Overlay -->
    @if($isOpen)
        <div 
            wire:click="close"
            class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 transition-opacity animate-in fade-in duration-300"
        ></div>

        <!-- Modal Dialog -->
        <div class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 overflow-y-auto">
            <div 
                class="bg-[var(--bg-card)] border border-[var(--border-card)] rounded-3xl shadow-2xl max-w-2xl w-full overflow-hidden flex flex-col max-h-[92vh] animate-in zoom-in-95 duration-200"
                @click.stop
            >
                <!-- Modal Header with Stepper -->
                <div class="p-6 border-b border-[var(--border-card)] bg-[var(--bg-card-alt)] space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <span class="font-serif-title text-2xl font-bold text-[var(--text-primary)]">Finalizar Compra</span>
                        </div>
                        <button wire:click="close" class="w-8 h-8 rounded-full border border-[var(--border-card)] flex items-center justify-center text-[var(--text-muted)] hover:text-[var(--text-primary)] cursor-pointer">
                            ✕
                        </button>
                    </div>

                    @if(! $pedidoCriado)
                        <!-- Stepper Progress -->
                        <div class="flex items-center justify-between relative px-2">
                            <div class="flex flex-col items-center gap-1 z-10">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition {{ $currentStep >= 1 ? 'bg-[var(--gold)] text-white shadow' : 'bg-[var(--tag-bg)] text-[var(--text-muted)]' }}">1</div>
                                <span class="text-[10px] font-semibold text-[var(--text-secondary)]">Dados</span>
                            </div>
                            <div class="flex-1 h-[2px] -mt-4 {{ $currentStep >= 2 ? 'bg-[var(--gold)]' : 'bg-[var(--border-card)]' }}"></div>

                            <div class="flex flex-col items-center gap-1 z-10">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition {{ $currentStep >= 2 ? 'bg-[var(--gold)] text-white shadow' : 'bg-[var(--tag-bg)] text-[var(--text-muted)]' }}">2</div>
                                <span class="text-[10px] font-semibold text-[var(--text-secondary)]">Entrega</span>
                            </div>
                            <div class="flex-1 h-[2px] -mt-4 {{ $currentStep >= 3 ? 'bg-[var(--gold)]' : 'bg-[var(--border-card)]' }}"></div>

                            <div class="flex flex-col items-center gap-1 z-10">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition {{ $currentStep >= 3 ? 'bg-[var(--gold)] text-white shadow' : 'bg-[var(--tag-bg)] text-[var(--text-muted)]' }}">3</div>
                                <span class="text-[10px] font-semibold text-[var(--text-secondary)]">Pagamento</span>
                            </div>
                            <div class="flex-1 h-[2px] -mt-4 {{ $currentStep >= 4 ? 'bg-[var(--gold)]' : 'bg-[var(--border-card)]' }}"></div>

                            <div class="flex flex-col items-center gap-1 z-10">
                                <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold transition {{ $currentStep >= 4 ? 'bg-[var(--gold)] text-white shadow' : 'bg-[var(--tag-bg)] text-[var(--text-muted)]' }}">4</div>
                                <span class="text-[10px] font-semibold text-[var(--text-secondary)]">Resumo</span>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Modal Body: Current Step Content -->
                <div class="p-6 overflow-y-auto flex-1 space-y-6">

                    @if($pedidoCriado)
                        <!-- Pedido Criado com Sucesso -->
                        <div class="text-center py-8 space-y-6">
                            <div class="w-20 h-20 rounded-full bg-green-500/10 text-green-600 mx-auto flex items-center justify-center">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <div class="space-y-2">
                                <h3 class="text-2xl font-bold font-serif-title text-[var(--text-primary)]">Pedido Realizado com Sucesso!</h3>
                                <p class="text-sm text-[var(--text-muted)]">Código do Pedido: <strong class="text-[var(--gold)] font-mono text-base">#{{ $pedidoCriado->codigo }}</strong></p>
                                <p class="text-xs text-[var(--text-secondary)] max-w-md mx-auto leading-relaxed">
                                    Seu pedido foi registrado no sistema. Clique no botão abaixo para enviar os detalhes pelo WhatsApp e combinar o pagamento e entrega instantaneamente!
                                </p>
                            </div>

                            <div class="flex flex-col sm:flex-row gap-3 justify-center pt-4">
                                <a 
                                    href="{{ $whatsappRedirectUrl }}" 
                                    target="_blank" 
                                    class="inline-flex items-center justify-center gap-2 px-8 py-4 rounded-2xl bg-green-600 hover:bg-green-700 text-white font-bold text-sm shadow-lg hover:shadow-xl transition"
                                >
                                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24"><path d="M12.031 6.172c-3.181 0-5.767 2.586-5.768 5.766-.001 1.298.38 2.27 1.019 3.287l-.582 2.128 2.182-.573c.978.58 1.911.928 3.145.929 3.178 0 5.767-2.587 5.768-5.766.001-3.187-2.575-5.77-5.764-5.771zm3.392 8.244c-.144.405-.837.774-1.17.824-.312.045-.694.07-2.123-.522-1.823-.756-2.99-2.61-3.08-2.731-.09-.12-.739-.982-.739-1.871 0-.889.467-1.328.633-1.506.167-.179.364-.224.485-.224.122 0 .243.001.35.006.113.005.263-.043.411.313.155.372.532 1.298.578 1.392.047.094.078.204.015.328-.063.125-.094.204-.187.313-.094.11-.197.246-.282.33-.094.094-.192.196-.083.383.11.187.488.805 1.047 1.303.72.641 1.328.84 1.516.933.188.094.298.078.407-.047.11-.125.467-.544.592-.731.125-.187.25-.156.421-.094.171.063 1.085.512 1.272.605.187.094.312.14.358.219.046.078.046.453-.098.858z"/></svg>
                                    <span>Confirmar via WhatsApp</span>
                                </a>

                                <button wire:click="close" class="px-6 py-4 rounded-2xl bg-[var(--bg-card-alt)] border border-[var(--border-card)] text-sm font-semibold hover:border-[var(--gold)] transition">
                                    Fechar Janela
                                </button>
                            </div>
                        </div>

                    @elseif($currentStep === 1)
                        <!-- STEP 1: DADOS PESSOAIS & ENDEREÇO -->
                        <div class="space-y-4">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-[var(--gold)]">1. Seus Dados de Contato</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">Nome Completo *</label>
                                    <input type="text" wire:model="nome" placeholder="Ex: Maria Silva" class="w-full bg-[var(--bg-card-alt)] border border-[var(--border-card)] rounded-xl px-3 py-2.5 text-xs text-[var(--text-primary)] outline-none focus:border-[var(--gold)]">
                                    @error('nome') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">WhatsApp *</label>
                                    <input type="tel" wire:model="whatsapp" placeholder="(74) 99999-9999" class="w-full bg-[var(--bg-card-alt)] border border-[var(--border-card)] rounded-xl px-3 py-2.5 text-xs text-[var(--text-primary)] outline-none focus:border-[var(--gold)]">
                                    @error('whatsapp') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">CPF *</label>
                                    <input type="text" wire:model="cpf" placeholder="000.000.000-00" class="w-full bg-[var(--bg-card-alt)] border border-[var(--border-card)] rounded-xl px-3 py-2.5 text-xs text-[var(--text-primary)] outline-none focus:border-[var(--gold)]">
                                    @error('cpf') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">E-mail *</label>
                                    <input type="email" wire:model="email" placeholder="seu@email.com" class="w-full bg-[var(--bg-card-alt)] border border-[var(--border-card)] rounded-xl px-3 py-2.5 text-xs text-[var(--text-primary)] outline-none focus:border-[var(--gold)]">
                                    @error('email') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                                </div>
                            </div>

                            <h3 class="text-xs font-bold uppercase tracking-wider text-[var(--gold)] pt-3">Como Deseja Receber?</h3>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="p-3 rounded-2xl border cursor-pointer flex items-center gap-2 transition {{ $tipo_entrega === 'entrega' ? 'border-[var(--gold)] bg-[var(--gold-bg)]' : 'border-[var(--border-card)] bg-[var(--bg-card-alt)]' }}">
                                    <input type="radio" wire:model.live="tipo_entrega" value="entrega" class="text-[var(--gold)]">
                                    <span class="text-xs font-bold text-[var(--text-primary)]">Entregar no Endereço</span>
                                </label>
                                <label class="p-3 rounded-2xl border cursor-pointer flex items-center gap-2 transition {{ $tipo_entrega === 'retirada' ? 'border-[var(--gold)] bg-[var(--gold-bg)]' : 'border-[var(--border-card)] bg-[var(--bg-card-alt)]' }}">
                                    <input type="radio" wire:model.live="tipo_entrega" value="retirada" class="text-[var(--gold)]">
                                    <span class="text-xs font-bold text-[var(--text-primary)]">Retirar na Loja</span>
                                </label>
                            </div>

                            @if($tipo_entrega === 'retirada')
                                <div class="space-y-2 p-3 bg-[var(--bg-card-alt)] rounded-2xl border border-[var(--border-card)]">
                                    <span class="text-xs font-semibold text-[var(--text-secondary)]">Selecione a unidade para retirada:</span>
                                    <div class="space-y-1.5">
                                        <label class="flex items-center gap-2 text-xs font-medium text-[var(--text-primary)] cursor-pointer">
                                            <input type="radio" wire:model.live="loja_retirada" value="Irecê - Bahia"> Loja Irecê - Bahia (Centro)
                                        </label>
                                        <label class="flex items-center gap-2 text-xs font-medium text-[var(--text-primary)] cursor-pointer">
                                            <input type="radio" wire:model.live="loja_retirada" value="Luís Eduardo Magalhães"> Loja Luís Eduardo Magalhães - Bahia
                                        </label>
                                    </div>
                                </div>
                            @else
                                <div class="space-y-3 pt-2">
                                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                        <div>
                                            <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">CEP *</label>
                                            <input type="text" wire:model.blur="cep" placeholder="44870-000" class="w-full bg-[var(--bg-card-alt)] border border-[var(--border-card)] rounded-xl px-3 py-2.5 text-xs text-[var(--text-primary)] outline-none focus:border-[var(--gold)]">
                                            @error('cep') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="sm:col-span-2">
                                            <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">Rua / Logradouro *</label>
                                            <input type="text" wire:model="rua" placeholder="Av. Principal" class="w-full bg-[var(--bg-card-alt)] border border-[var(--border-card)] rounded-xl px-3 py-2.5 text-xs text-[var(--text-primary)] outline-none focus:border-[var(--gold)]">
                                            @error('rua') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                        <div>
                                            <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">Número *</label>
                                            <input type="text" wire:model="numero" placeholder="123" class="w-full bg-[var(--bg-card-alt)] border border-[var(--border-card)] rounded-xl px-3 py-2.5 text-xs text-[var(--text-primary)] outline-none focus:border-[var(--gold)]">
                                            @error('numero') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">Complemento</label>
                                            <input type="text" wire:model="complemento" placeholder="Apto 101" class="w-full bg-[var(--bg-card-alt)] border border-[var(--border-card)] rounded-xl px-3 py-2.5 text-xs text-[var(--text-primary)] outline-none focus:border-[var(--gold)]">
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">Bairro *</label>
                                            <input type="text" wire:model="bairro" placeholder="Centro" class="w-full bg-[var(--bg-card-alt)] border border-[var(--border-card)] rounded-xl px-3 py-2.5 text-xs text-[var(--text-primary)] outline-none focus:border-[var(--gold)]">
                                            @error('bairro') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                                        </div>
                                        <div>
                                            <label class="block text-xs font-semibold text-[var(--text-secondary)] mb-1">Cidade / UF *</label>
                                            <input type="text" wire:model="cidade" placeholder="Irecê" class="w-full bg-[var(--bg-card-alt)] border border-[var(--border-card)] rounded-xl px-3 py-2.5 text-xs text-[var(--text-primary)] outline-none focus:border-[var(--gold)]">
                                            @error('cidade') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                    @elseif($currentStep === 2)
                        <!-- STEP 2: OPÇÕES DE FRETE -->
                        <div class="space-y-4">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-[var(--gold)]">2. Selecione a Opção de Entrega</h3>
                            
                            <div class="space-y-3">
                                @foreach($shippingOptions as $opt)
                                    <label 
                                        wire:click="selectShippingOption('{{ $opt['nome'] }}', {{ $opt['valor'] }})"
                                        class="p-4 rounded-2xl border cursor-pointer flex items-center justify-between transition {{ $opcao_frete === $opt['nome'] ? 'border-[var(--gold)] bg-[var(--gold-bg)] shadow-sm' : 'border-[var(--border-card)] bg-[var(--bg-card-alt)]' }}"
                                    >
                                        <div class="flex items-center gap-3">
                                            <input type="radio" name="opcao_frete" value="{{ $opt['nome'] }}" class="text-[var(--gold)]" {{ $opcao_frete === $opt['nome'] ? 'checked' : '' }}>
                                            <div>
                                                <div class="text-xs font-bold text-[var(--text-primary)]">{{ $opt['nome'] }}</div>
                                                <div class="text-[11px] text-[var(--text-muted)]">{{ $opt['prazo'] }}</div>
                                            </div>
                                        </div>
                                        <div class="text-xs font-extrabold {{ $opt['gratis'] ? 'text-green-600' : 'text-[var(--text-primary)]' }}">
                                            {{ $opt['gratis'] ? 'GRÁTIS' : 'R$ ' . number_format($opt['valor'], 2, ',', '.') }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                    @elseif($currentStep === 3)
                        <!-- STEP 3: FORMA DE PAGAMENTO -->
                        <div class="space-y-4">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-[var(--gold)]">3. Forma de Pagamento</h3>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <label class="p-4 rounded-2xl border cursor-pointer flex flex-col items-center gap-2 text-center transition {{ $forma_pagamento === 'pix' ? 'border-[var(--gold)] bg-[var(--gold-bg)] shadow-sm' : 'border-[var(--border-card)] bg-[var(--bg-card-alt)]' }}">
                                    <input type="radio" wire:model.live="forma_pagamento" value="pix" class="text-[var(--gold)]">
                                    <span class="text-xs font-bold text-[var(--text-primary)]">PIX Instantâneo</span>
                                    <span class="text-[10px] text-[var(--gold)] font-semibold">Aprovação Imediata</span>
                                </label>
                                <label class="p-4 rounded-2xl border cursor-pointer flex flex-col items-center gap-2 text-center transition {{ $forma_pagamento === 'credito' ? 'border-[var(--gold)] bg-[var(--gold-bg)] shadow-sm' : 'border-[var(--border-card)] bg-[var(--bg-card-alt)]' }}">
                                    <input type="radio" wire:model.live="forma_pagamento" value="credito" class="text-[var(--gold)]">
                                    <span class="text-xs font-bold text-[var(--text-primary)]">Cartão de Crédito</span>
                                    <span class="text-[10px] text-[var(--text-muted)]">Em até 6x</span>
                                </label>
                                <label class="p-4 rounded-2xl border cursor-pointer flex flex-col items-center gap-2 text-center transition {{ $forma_pagamento === 'debito' ? 'border-[var(--gold)] bg-[var(--gold-bg)] shadow-sm' : 'border-[var(--border-card)] bg-[var(--bg-card-alt)]' }}">
                                    <input type="radio" wire:model.live="forma_pagamento" value="debito" class="text-[var(--gold)]">
                                    <span class="text-xs font-bold text-[var(--text-primary)]">Cartão de Débito</span>
                                    <span class="text-[10px] text-[var(--text-muted)]">À vista</span>
                                </label>
                            </div>

                            <!-- Trust Badges -->
                            <div class="p-4 rounded-2xl bg-[var(--bg-card-alt)] border border-[var(--border-card)] flex items-center justify-around text-center text-[10px] text-[var(--text-secondary)]">
                                <div>🔒 Ambiente 100% Seguro</div>
                                <div>⚡ Envio Rápido e Garantido</div>
                                <div>✨ Produtos Originais</div>
                            </div>
                        </div>

                    @elseif($currentStep === 4)
                        <!-- STEP 4: RESUMO GERAL -->
                        <div class="space-y-4">
                            <h3 class="text-xs font-bold uppercase tracking-wider text-[var(--gold)]">4. Resumo Geral do Pedido</h3>

                            <!-- Detalhes do Cliente e Entrega -->
                            <div class="p-4 rounded-2xl bg-[var(--bg-card-alt)] border border-[var(--border-card)] text-xs space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-[var(--text-muted)]">Cliente:</span>
                                    <span class="font-bold text-[var(--text-primary)]">{{ $nome }} ({{ $whatsapp }})</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-[var(--text-muted)]">Modalidade:</span>
                                    <span class="font-bold text-[var(--text-primary)]">{{ $opcao_frete }}</span>
                                </div>
                                @if($tipo_entrega === 'entrega')
                                    <div class="flex justify-between">
                                        <span class="text-[var(--text-muted)]">Endereço:</span>
                                        <span class="font-bold text-[var(--text-primary)] text-right">{{ $rua }}, {{ $numero }} - {{ $bairro }}, {{ $cidade }}/{{ $estado }}</span>
                                    </div>
                                @endif
                                <div class="flex justify-between">
                                    <span class="text-[var(--text-muted)]">Pagamento:</span>
                                    <span class="font-bold text-[var(--text-primary)] uppercase">{{ $forma_pagamento }}</span>
                                </div>
                            </div>

                            <!-- Lista de Itens -->
                            <div class="divide-y divide-[var(--border-card)] max-h-48 overflow-y-auto">
                                @foreach($cart as $item)
                                    <div class="py-2.5 flex items-center justify-between text-xs">
                                        <div class="flex items-center gap-2">
                                            <img src="{{ $item['imagem'] }}" class="w-9 h-9 object-cover rounded-lg border">
                                            <div>
                                                <div class="font-semibold text-[var(--text-primary)] truncate max-w-[200px]">{{ $item['nome'] }}</div>
                                                <div class="text-[10px] text-[var(--text-muted)]">{{ $item['quantidade'] }}x de R$ {{ number_format($item['preco'], 2, ',', '.') }}</div>
                                            </div>
                                        </div>
                                        <span class="font-bold text-[var(--text-primary)]">R$ {{ number_format($item['subtotal'], 2, ',', '.') }}</span>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Totais -->
                            <div class="p-4 rounded-2xl bg-[var(--bg-hero)] border border-[var(--border-card)] space-y-1.5 text-xs">
                                <div class="flex justify-between text-[var(--text-secondary)]">
                                    <span>Subtotal:</span>
                                    <span>R$ {{ number_format($totals['subtotal'], 2, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-[var(--text-secondary)]">
                                    <span>Frete:</span>
                                    <span>{{ $totals['frete'] == 0 ? 'GRÁTIS' : 'R$ ' . number_format($totals['frete'], 2, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between text-base font-extrabold text-[var(--text-primary)] pt-1 border-t border-[var(--border-card)]">
                                    <span>Total:</span>
                                    <span class="text-[var(--gold)]">R$ {{ number_format($totals['total'], 2, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>

                    @endif

                </div>

                <!-- Modal Footer with Nav Buttons -->
                @if(! $pedidoCriado)
                    <div class="p-5 border-t border-[var(--border-card)] bg-[var(--bg-card-alt)] flex items-center justify-between gap-3">
                        @if($currentStep > 1)
                            <button wire:click="goToStep({{ $currentStep - 1 }})" class="px-5 py-2.5 rounded-xl border border-[var(--border-card)] bg-[var(--bg-card)] text-xs font-semibold hover:border-[var(--gold)] transition cursor-pointer">
                                ← Voltar
                            </button>
                        @else
                            <div></div>
                        @endif

                        @if($currentStep < 4)
                            <button wire:click="goToStep({{ $currentStep + 1 }})" class="px-7 py-3 rounded-xl bg-[var(--gold)] text-white text-xs font-bold shadow hover:opacity-90 transition flex items-center gap-1.5 cursor-pointer">
                                <span>Próximo Passo</span>
                                <span>→</span>
                            </button>
                        @else
                            <button wire:click="finishOrder" class="px-8 py-3.5 rounded-xl bg-gradient-to-r from-[#B8892E] to-[#8A6B2C] text-white text-xs font-extrabold uppercase tracking-wider shadow-lg hover:shadow-xl transition flex items-center gap-2 cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Confirmar e Finalizar Pedido</span>
                            </button>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    @endif
</div>
