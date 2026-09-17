<div class="space-y-6" @keydown.window.escape="$wire.cancelDelete(); $wire.closeModal()">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-black tracking-tight text-zinc-900 dark:text-white">Lista de Espera & Leads</h1>
            <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">Gerencie os clientes interessados na inauguração e ofertas exclusivas.</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="px-4 py-2 rounded-xl bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 text-xs font-semibold text-zinc-600 dark:text-zinc-300 shadow-xs">
                Total de Leads: <strong class="text-zinc-900 dark:text-white">{{ $totalLeads }}</strong>
            </div>
            <div class="px-4 py-2 rounded-xl bg-primary-500/10 border border-primary-500/20 text-xs font-semibold text-primary-600 dark:text-primary-400">
                DF/Entorno: <strong>{{ $totalDF }}</strong>
            </div>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl p-4 shadow-xs">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <div class="sm:col-span-2 relative">
                <input 
                    type="text" 
                    wire:model.live.debounce.300ms="search"
                    placeholder="Buscar por nome, WhatsApp, e-mail ou cidade..." 
                    class="w-full pl-10 pr-4 py-2.5 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl text-sm text-zinc-900 dark:text-white placeholder-zinc-400 focus:outline-hidden focus:ring-2 focus:ring-primary-500 transition"
                >
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-zinc-400">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
            </div>

            <div>
                <select 
                    wire:model.live="estado"
                    class="w-full px-3 py-2.5 bg-zinc-50 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 rounded-xl text-sm text-zinc-900 dark:text-white focus:outline-hidden focus:ring-2 focus:ring-primary-500 transition">
                    <option value="">Todos os Estados</option>
                    @foreach($estados as $uf)
                        <option value="{{ $uf }}">{{ $uf }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>

    <!-- Leads Table -->
    <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-2xl overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-zinc-100 dark:border-zinc-800 bg-zinc-50/50 dark:bg-zinc-900/50 text-[11px] font-bold uppercase tracking-wider text-zinc-400">
                        <th class="py-3 px-4">Nome</th>
                        <th class="py-3 px-4">WhatsApp</th>
                        <th class="py-3 px-4">E-mail</th>
                        <th class="py-3 px-4">Localização</th>
                        <th class="py-3 px-4">Data Cadastro</th>
                        <th class="py-3 px-4 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100 dark:divide-zinc-800 text-sm">
                    @forelse($leads as $lead)
                        @php
                            $phoneClean = preg_replace('/\D/', '', $lead->whatsapp ?? '');
                            if (strlen($phoneClean) === 10 || strlen($phoneClean) === 11) {
                                $phoneClean = '55'.$phoneClean;
                            }
                            $waMsg = urlencode("Olá {$lead->nome}! Somos da DF Variedades. Obrigado por se cadastrar em nossa lista vip!");
                            $waLink = "https://api.whatsapp.com/send?phone={$phoneClean}&text={$waMsg}";
                        @endphp
                        <tr class="hover:bg-zinc-50/50 dark:hover:bg-zinc-800/40 transition">
                            <td class="py-4 px-4 font-bold text-zinc-900 dark:text-white">
                                {{ $lead->nome }}
                            </td>
                            <td class="py-4 px-4">
                                @if($lead->whatsapp)
                                    <div class="flex items-center gap-2">
                                        <span class="font-mono text-xs text-zinc-700 dark:text-zinc-300">{{ $lead->whatsapp }}</span>
                                        <a href="{{ $waLink }}" target="_blank" rel="noopener noreferrer" class="p-1 rounded-lg bg-emerald-500/10 text-emerald-500 hover:bg-emerald-500 hover:text-white transition" title="Conversar no WhatsApp">
                                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                            </svg>
                                        </a>
                                    </div>
                                @else
                                    <span class="text-xs text-zinc-400">—</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-xs text-zinc-600 dark:text-zinc-300">
                                {{ $lead->email ?: '—' }}
                            </td>
                            <td class="py-4 px-4 text-xs text-zinc-600 dark:text-zinc-300">
                                @if($lead->cidade || $lead->estado)
                                    <span>{{ $lead->cidade }}</span>
                                    @if($lead->estado)
                                        <span class="font-bold text-zinc-900 dark:text-white">/{{ $lead->estado }}</span>
                                    @endif
                                @else
                                    <span class="text-zinc-400">—</span>
                                @endif
                                @if($lead->cep)
                                    <span class="block text-[11px] font-mono text-zinc-400">{{ $lead->cep }}</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-xs text-zinc-500 dark:text-zinc-400 whitespace-nowrap">
                                {{ $lead->created_at->format('d/m/Y H:i') }}
                            </td>
                            <td class="py-4 px-4 text-right">
                                <button 
                                    type="button" 
                                    wire:click="confirmDelete({{ $lead->id }})"
                                    class="p-2 rounded-xl text-zinc-400 hover:text-rose-500 hover:bg-rose-500/10 transition"
                                    title="Remover Lead">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-zinc-500 dark:text-zinc-400">
                                <svg class="w-10 h-10 mx-auto text-zinc-400 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Nenhum lead encontrado.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($leads->hasPages())
            <div class="p-4 border-t border-zinc-100 dark:border-zinc-800">
                {{ $leads->links() }}
            </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
    @if($deletingId)
        <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs">
            <div class="bg-white dark:bg-zinc-900 border border-zinc-200 dark:border-zinc-800 rounded-3xl p-6 max-w-md w-full shadow-2xl space-y-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-500/10 text-rose-500 flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-zinc-900 dark:text-white">Remover Lead</h3>
                    <p class="text-sm text-zinc-500 dark:text-zinc-400 mt-1">
                        Tem certeza que deseja remover este lead da lista de espera? Esta ação não pode ser desfeita.
                    </p>
                </div>
                <div class="flex items-center justify-end gap-3 pt-2">
                    <button 
                        type="button" 
                        wire:click="cancelDelete"
                        class="px-4 py-2 rounded-xl bg-zinc-100 dark:bg-zinc-800 text-zinc-700 dark:text-zinc-300 font-semibold text-xs hover:bg-zinc-200 dark:hover:bg-zinc-700 transition">
                        Cancelar
                    </button>
                    <button 
                        type="button" 
                        wire:click="delete"
                        class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-500 text-white font-bold text-xs transition shadow-xs">
                        Confirmar Remoção
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
