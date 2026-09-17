<x-layouts::auth :title="__('Recuperar Senha')">
    <div class="flex flex-col gap-6">
        
        <div class="text-center space-y-2">
            <span class="inline-flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full bg-[#B8892E]/10 text-[#8A6B2C] border border-[#B8892E]/25">
                🔑 Recuperação
            </span>
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900 font-serif-title">
                Esqueceu a Senha?
            </h1>
            <p class="text-xs text-zinc-600">
                Informe seu e-mail cadastrado e enviaremos um link para você redefinir sua senha de acesso.
            </p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-4">
            @csrf

            <!-- Email Address -->
            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">E-mail Cadastrado *</label>
                <input 
                    name="email" 
                    type="email" 
                    value="{{ old('email') }}" 
                    required 
                    autofocus 
                    placeholder="seu.email@exemplo.com"
                    class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-3.5 py-2.5 text-xs text-zinc-900 placeholder:text-zinc-400 outline-none focus:border-[#B8892E] focus:bg-white transition shadow-2xs"
                >
                @error('email') <span class="text-[10px] text-red-500 font-semibold block mt-1">{{ $message }}</span> @enderror
            </div>

            <div class="pt-2">
                <button 
                    type="submit" 
                    data-test="email-password-reset-link-button"
                    class="w-full py-3.5 px-6 rounded-xl bg-gradient-to-r from-[#B8892E] via-[#C9A84C] to-[#D4A843] text-black font-extrabold text-sm shadow-md hover:shadow-lg hover:brightness-105 transition cursor-pointer flex items-center justify-center gap-2">
                    <span>Enviar Link de Recuperação</span>
                    <span class="text-black font-extrabold">→</span>
                </button>
            </div>
        </form>

        <div class="space-x-1 text-center text-xs text-zinc-600 pt-2 border-t border-zinc-100">
            <span>Lembrou sua senha?</span>
            <a href="{{ route('login') }}" class="text-[#B8892E] hover:text-[#8A6B2C] font-bold hover:underline" wire:navigate>
                Voltar para o Login
            </a>
        </div>
    </div>
</x-layouts::auth>
