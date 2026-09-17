<div @keydown.window.escape="$wire.close()">
    @if($isOpen)
        <!-- Backdrop -->
        <div 
            wire:click="close"
            class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 transition-opacity animate-in fade-in duration-300"
        ></div>

        <!-- Dialog -->
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-y-auto">
            <div 
                class="bg-[var(--bg-card)] border border-[var(--border-card)] rounded-3xl shadow-2xl max-w-md w-full p-6 space-y-5 animate-in zoom-in-95 duration-200"
                @click.stop
            >
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2 text-[var(--gold)] font-bold text-sm uppercase tracking-wider">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <span>Lista de Espera</span>
                    </div>
                    <button wire:click="close" class="w-7 h-7 rounded-full border flex items-center justify-center text-[var(--text-muted)] hover:text-[var(--text-primary)] cursor-pointer">✕</button>
                </div>

                <div class="space-y-1">
                    <h3 class="text-xl font-bold font-serif-title text-[var(--text-primary)]">Entregas na sua Cidade</h3>
                    <p class="text-xs text-[var(--text-secondary)] leading-relaxed">
                        No momento, entregamos diretamente em <strong>Irecê</strong> e região. Deixe seus dados para ser avisado(a) assim que liberarmos entregas para seu CEP!
                    </p>
                </div>

                <form wire:submit="submit" class="space-y-3 text-xs">
                    <div>
                        <label class="block font-semibold text-[var(--text-secondary)] mb-1">Seu Nome *</label>
                        <input type="text" wire:model="nome" placeholder="Ex: Maria Santos" class="w-full bg-[var(--bg-card-alt)] border border-[var(--border-card)] rounded-xl px-3 py-2.5 text-[var(--text-primary)] outline-none focus:border-[var(--gold)]">
                        @error('nome') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-[var(--text-secondary)] mb-1">WhatsApp *</label>
                        <input type="tel" wire:model="whatsapp" placeholder="(74) 99999-9999" class="w-full bg-[var(--bg-card-alt)] border border-[var(--border-card)] rounded-xl px-3 py-2.5 text-[var(--text-primary)] outline-none focus:border-[var(--gold)]">
                        @error('whatsapp') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block font-semibold text-[var(--text-secondary)] mb-1">E-mail (Opcional)</label>
                        <input type="email" wire:model="email" placeholder="seu@email.com" class="w-full bg-[var(--bg-card-alt)] border border-[var(--border-card)] rounded-xl px-3 py-2.5 text-[var(--text-primary)] outline-none focus:border-[var(--gold)]">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block font-semibold text-[var(--text-secondary)] mb-1">CEP *</label>
                            <input type="text" wire:model="cep" placeholder="00000-000" class="w-full bg-[var(--bg-card-alt)] border border-[var(--border-card)] rounded-xl px-3 py-2.5 text-[var(--text-primary)] outline-none focus:border-[var(--gold)]">
                            @error('cep') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block font-semibold text-[var(--text-secondary)] mb-1">Cidade / UF *</label>
                            <input type="text" wire:model="cidade" placeholder="Sua Cidade" class="w-full bg-[var(--bg-card-alt)] border border-[var(--border-card)] rounded-xl px-3 py-2.5 text-[var(--text-primary)] outline-none focus:border-[var(--gold)]">
                            @error('cidade') <span class="text-[10px] text-red-500">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <button type="submit" class="w-full py-3.5 rounded-2xl bg-gradient-to-r from-[#B8892E] to-[#8A6B2C] text-white font-bold text-xs uppercase tracking-wider shadow-lg hover:shadow-xl transition cursor-pointer mt-2">
                        Cadastrar na Lista de Espera
                    </button>
                </form>
            </div>
        </div>
    @endif
</div>
