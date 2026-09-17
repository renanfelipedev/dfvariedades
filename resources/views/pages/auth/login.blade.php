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
            <h1 class="text-2xl font-black tracking-tight text-zinc-900 dark:text-white font-serif-title">
                <span class="text-[#C9A84C]">DF</span> VARIEDADES
            </h1>
            <p class="text-xs text-zinc-500 dark:text-zinc-400">
                Acesse o painel administrativo ou sua conta de cliente
            </p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <x-passkey-verify />

        <form method="POST" action="{{ route('login.store') }}" class="flex flex-col gap-5">
            @csrf

            <!-- Email Address -->
            <flux:input name="email" :label="__('E-mail')" :value="old('email')" type="email" required autofocus
                autocomplete="email" placeholder="seu.email@exemplo.com" />

            <!-- Password -->
            <div class="relative">
                <flux:input name="password" :label="__('Senha')" type="password" required
                    autocomplete="current-password" :placeholder="__('Sua senha de acesso')" viewable />

                @if (Route::has('password.request'))
                    <flux:link class="absolute top-0 text-xs end-0 text-[#C9A84C] hover:underline"
                        :href="route('password.request')" wire:navigate>
                        Esqueceu a senha?
                    </flux:link>
                @endif
            </div>

            <!-- Remember Me -->
            <flux:checkbox name="remember" :label="__('Lembrar-me')" :checked="old('remember')" />

            <div class="flex items-center justify-end">
                <flux:button variant="primary" type="submit" class="w-full bg-[#C9A84C] hover:bg-[#D4A843]  font-bold"
                    data-test="login-button">
                    Entrar no Sistema
                </flux:button>
            </div>
        </form>

        <!-- Quick Demo Credentials Selector -->
        <div class="pt-4 border-t border-zinc-200 dark:border-zinc-800 space-y-3">
            <div class="text-[11px] font-bold uppercase tracking-wider text-zinc-400 text-center">
                Acessos Rápidos de Demonstração
            </div>

            <div class="grid grid-cols-2 gap-2 text-left">
                <button type="button" @click="fillCredentials('admin@email.com', 'admin@123')"
                    class="p-2.5 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900/50 hover:border-[#C9A84C] text-left transition group">
                    <div
                        class="text-xs font-bold text-zinc-900 dark:text-white group-hover:text-[#C9A84C] flex items-center justify-between">
                        <span>👑 Administrador</span>
                    </div>
                    <div class="text-[10px] text-zinc-500 font-mono mt-0.5">admin@email.com</div>
                </button>

                <button type="button" @click="fillCredentials('gerente@dfvariedades.com.br', 'gerente@123')"
                    class="p-2.5 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900/50 hover:border-[#C9A84C] text-left transition group">
                    <div
                        class="text-xs font-bold text-zinc-900 dark:text-white group-hover:text-[#C9A84C] flex items-center justify-between">
                        <span>📦 Gerente</span>
                    </div>
                    <div class="text-[10px] text-zinc-500 font-mono mt-0.5">gerente@dfvariedades...</div>
                </button>

                <button type="button" @click="fillCredentials('atendente@dfvariedades.com.br', 'atendente@123')"
                    class="p-2.5 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900/50 hover:border-[#C9A84C] text-left transition group">
                    <div
                        class="text-xs font-bold text-zinc-900 dark:text-white group-hover:text-[#C9A84C] flex items-center justify-between">
                        <span>🎧 Atendente</span>
                    </div>
                    <div class="text-[10px] text-zinc-500 font-mono mt-0.5">atendente@dfvariedades...</div>
                </button>

                <button type="button" @click="fillCredentials('cliente@dfvariedades.com.br', 'password')"
                    class="p-2.5 rounded-xl border border-zinc-200 dark:border-zinc-800 bg-zinc-50 dark:bg-zinc-900/50 hover:border-[#C9A84C] text-left transition group">
                    <div
                        class="text-xs font-bold text-zinc-900 dark:text-white group-hover:text-[#C9A84C] flex items-center justify-between">
                        <span>👤 Cliente VIP</span>
                    </div>
                    <div class="text-[10px] text-zinc-500 font-mono mt-0.5">cliente@dfvariedades...</div>
                </button>
            </div>
        </div>

        <div class="space-x-1 text-xs text-center text-zinc-600 dark:text-zinc-400">
            <span>Ainda não possui uma conta?</span>
            <flux:link :href="route('register')" class="text-[#C9A84C] font-semibold" wire:navigate>Cadastre-se
            </flux:link>
        </div>
    </div>
</x-layouts::auth>
