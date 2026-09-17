<x-layouts::auth :title="__('Redefinir Senha')">
    <div class="flex flex-col gap-6">
        
        <div class="text-center space-y-2">
            <span class="inline-flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full bg-[#B8892E]/10 text-[#8A6B2C] border border-[#B8892E]/25">
                🔒 Nova Senha
            </span>
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900 font-serif-title">
                Redefinir Senha
            </h1>
            <p class="text-xs text-zinc-600">
                Digite sua nova senha de acesso abaixo para concluir a alteração.
            </p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.update') }}" class="flex flex-col gap-4">
            @csrf
            <!-- Token -->
            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <!-- Email Address -->
            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">E-mail *</label>
                <input 
                    name="email" 
                    type="email" 
                    value="{{ request('email') }}" 
                    required 
                    autocomplete="email"
                    class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-3.5 py-2.5 text-xs text-zinc-900 placeholder:text-zinc-400 outline-none focus:border-[#B8892E] focus:bg-white transition shadow-2xs"
                >
                @error('email') <span class="text-[10px] text-red-500 font-semibold block mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Password -->
            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Nova Senha *</label>
                <input 
                    name="password" 
                    type="password" 
                    required 
                    autocomplete="new-password" 
                    placeholder="Mínimo de 8 caracteres"
                    class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-3.5 py-2.5 text-xs text-zinc-900 placeholder:text-zinc-400 outline-none focus:border-[#B8892E] focus:bg-white transition shadow-2xs"
                >
                @error('password') <span class="text-[10px] text-red-500 font-semibold block mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">Confirmar Nova Senha *</label>
                <input 
                    name="password_confirmation" 
                    type="password" 
                    required 
                    autocomplete="new-password" 
                    placeholder="Repita a nova senha"
                    class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-3.5 py-2.5 text-xs text-zinc-900 placeholder:text-zinc-400 outline-none focus:border-[#B8892E] focus:bg-white transition shadow-2xs"
                >
                @error('password_confirmation') <span class="text-[10px] text-red-500 font-semibold block mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="pt-2">
                <button 
                    type="submit" 
                    data-test="reset-password-button"
                    class="w-full py-3.5 px-6 rounded-xl bg-gradient-to-r from-[#B8892E] via-[#C9A84C] to-[#D4A843] text-black font-extrabold text-sm shadow-md hover:shadow-lg hover:brightness-105 transition cursor-pointer flex items-center justify-center gap-2">
                    <span>Salvar Nova Senha</span>
                    <span class="text-black font-extrabold">→</span>
                </button>
            </div>
        </form>
    </div>
</x-layouts::auth>
