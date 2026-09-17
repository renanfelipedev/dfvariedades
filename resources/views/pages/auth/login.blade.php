<x-layouts::auth :title="__('Entrar na Conta')">
    <div class="flex flex-col gap-6" x-data="{
        fillCredentials(email, pass) {
            let emailInput = document.querySelector('input[name=email]');
            let passInput = document.querySelector('input[name=password]');
            if (emailInput && passInput) {
                emailInput.value = email;
                passInput.value = pass;
                emailInput.dispatchEvent(new Event('input'));
                passInput.dispatchEvent(new Event('input'));
            }
        }
    }">
        <div class="text-center space-y-2">
            <span class="inline-flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full bg-[#B8892E]/10 text-[#8A6B2C] border border-[#B8892E]/25">
                🔒 Acesso Seguro
            </span>
            <h1 class="text-2xl font-bold tracking-tight text-zinc-900 font-serif-title">
                Entrar na Conta
            </h1>
            <p class="text-xs text-zinc-600">
                Acesse o painel administrativo ou sua conta de cliente
            </p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-4">
            @csrf

            <!-- Email Address -->
            <div>
                <label class="block text-xs font-semibold text-zinc-700 mb-1">E-mail *</label>
                <input 
                    name="email" 
                    type="email" 
                    value="{{ old('email') }}" 
                    required 
                    autofocus 
                    autocomplete="email" 
                    placeholder="seu.email@exemplo.com"
                    class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-3.5 py-2.5 text-xs text-zinc-900 placeholder:text-zinc-400 outline-none focus:border-[#B8892E] focus:bg-white transition shadow-2xs"
                >
                @error('email') <span class="text-[10px] text-red-500 font-semibold block mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Password -->
            <div>
                <div class="flex items-center justify-between mb-1">
                    <label class="block text-xs font-semibold text-zinc-700">Senha *</label>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-[11px] text-[#B8892E] hover:text-[#8A6B2C] font-semibold hover:underline" wire:navigate>
                            Esqueceu a senha?
                        </a>
                    @endif
                </div>
                <input 
                    name="password" 
                    type="password" 
                    required 
                    autocomplete="current-password" 
                    placeholder="Sua senha de acesso"
                    class="w-full bg-zinc-50 border border-zinc-200 rounded-xl px-3.5 py-2.5 text-xs text-zinc-900 placeholder:text-zinc-400 outline-none focus:border-[#B8892E] focus:bg-white transition shadow-2xs"
                >
                @error('password') <span class="text-[10px] text-red-500 font-semibold block mt-1">{{ $message }}</span> @enderror
            </div>

            <!-- Remember Me -->
            <label class="flex items-center gap-2 text-xs text-zinc-700 cursor-pointer pt-1">
                <input type="checkbox" name="remember" class="text-[#B8892E] rounded border-zinc-300 accent-[#B8892E]">
                <span>Lembrar-me neste dispositivo</span>
            </label>

            <!-- Submit Button (High Contrast, Bold, Gold) -->
            <div class="pt-2">
                <button 
                    type="submit" 
                    data-test="login-button"
                    class="w-full py-3.5 px-6 rounded-xl bg-gradient-to-r from-[#B8892E] via-[#C9A84C] to-[#D4A843] text-black font-extrabold text-sm shadow-md hover:shadow-lg hover:brightness-105 transition cursor-pointer flex items-center justify-center gap-2">
                    <span>Entrar no Sistema</span>
                    <span class="text-black font-extrabold">→</span>
                </button>
            </div>
        </form>

        <!-- Quick Demo Credentials Selector -->
        <div class="pt-4 border-t border-zinc-100 space-y-3">
            <div class="text-[10px] font-bold uppercase tracking-wider text-zinc-500 text-center">
                Acessos Rápidos de Demonstração
            </div>

            <div class="grid grid-cols-2 gap-2 text-left">
                <button type="button" @click="fillCredentials('admin@email.com', 'admin@123')"
                    class="p-2.5 rounded-xl border border-zinc-200 bg-zinc-50/80 hover:bg-[#FDFBF7] hover:border-[#B8892E] text-left transition group cursor-pointer shadow-2xs">
                    <div class="text-xs font-bold text-zinc-900 group-hover:text-[#B8892E] flex items-center justify-between">
                        <span>👑 Admin</span>
                    </div>
                    <div class="text-[10px] text-zinc-500 font-mono mt-0.5">admin@email.com</div>
                </button>

                <button type="button" @click="fillCredentials('gerente@dfvariedades.com.br', 'gerente@123')"
                    class="p-2.5 rounded-xl border border-zinc-200 bg-zinc-50/80 hover:bg-[#FDFBF7] hover:border-[#B8892E] text-left transition group cursor-pointer shadow-2xs">
                    <div class="text-xs font-bold text-zinc-900 group-hover:text-[#B8892E] flex items-center justify-between">
                        <span>📦 Gerente</span>
                    </div>
                    <div class="text-[10px] text-zinc-500 font-mono mt-0.5 truncate">gerente@dfvariedades...</div>
                </button>

                <button type="button" @click="fillCredentials('atendente@dfvariedades.com.br', 'atendente@123')"
                    class="p-2.5 rounded-xl border border-zinc-200 bg-zinc-50/80 hover:bg-[#FDFBF7] hover:border-[#B8892E] text-left transition group cursor-pointer shadow-2xs">
                    <div class="text-xs font-bold text-zinc-900 group-hover:text-[#B8892E] flex items-center justify-between">
                        <span>🎧 Atendente</span>
                    </div>
                    <div class="text-[10px] text-zinc-500 font-mono mt-0.5 truncate">atendente@dfvariedades...</div>
                </button>

                <button type="button" @click="fillCredentials('cliente@dfvariedades.com.br', 'password')"
                    class="p-2.5 rounded-xl border border-zinc-200 bg-zinc-50/80 hover:bg-[#FDFBF7] hover:border-[#B8892E] text-left transition group cursor-pointer shadow-2xs">
                    <div class="text-xs font-bold text-zinc-900 group-hover:text-[#B8892E] flex items-center justify-between">
                        <span>👤 Cliente VIP</span>
                    </div>
                    <div class="text-[10px] text-zinc-500 font-mono mt-0.5 truncate">cliente@dfvariedades...</div>
                </button>
            </div>
        </div>

        <div class="space-x-1 text-xs text-center text-zinc-600 pt-2 border-t border-zinc-100">
            <span>Ainda não possui uma conta?</span>
            <a href="{{ route('register') }}" class="text-[#B8892E] hover:text-[#8A6B2C] font-bold hover:underline" wire:navigate>
                Cadastre-se como Cliente
            </a>
        </div>
    </div>
</x-layouts::auth>
