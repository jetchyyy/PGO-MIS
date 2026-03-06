<x-guest-layout>
    <div class="mb-6 border-b border-white/10 pb-5 text-center">
        <h1 class="text-2xl font-black text-white tracking-tight">{{ $whiteLabel['login_heading'] }}</h1>
        <p class="mt-1.5 text-xs font-bold text-slate-400 uppercase tracking-widest">{{ $whiteLabel['login_subheading'] }}</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold tracking-widest uppercase text-slate-300 mb-2">{{ __('Email Address') }}</label>
            <input id="email" class="block w-full rounded-xl border border-white/20 bg-white/5 px-4 py-3 text-white placeholder-white/30 shadow-inner focus:ring-1 focus:ring-offset-0 focus:outline-none backdrop-blur-sm transition" style="--tw-ring-color: {{ $whiteLabel['button_color'] }}; border-color: #ffffff33;" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Enter your email" />
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-rose-400 font-medium" />
        </div>

        <!-- Password -->
        <div class="mt-6">
            <label for="password" class="block text-xs font-bold tracking-widest uppercase text-slate-300 mb-2">{{ __('Password') }}</label>
            <input id="password" class="block w-full rounded-xl border border-white/20 bg-white/5 px-4 py-3 text-white placeholder-white/30 shadow-inner focus:ring-1 focus:ring-offset-0 focus:outline-none backdrop-blur-sm transition" style="--tw-ring-color: {{ $whiteLabel['button_color'] }}; border-color: #ffffff33;" type="password" name="password" required autocomplete="current-password" placeholder="Enter your password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-rose-400 font-medium" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="mt-6 flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                <input id="remember_me" type="checkbox" class="h-4 w-4 rounded border-white/20 bg-white/10 shadow-sm focus:ring-offset-0 transition" style="color: {{ $whiteLabel['button_color'] }};" name="remember">
                <span class="ms-2 text-sm font-semibold text-slate-300 group-hover:text-white transition">{{ __('Stay signed in') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm font-bold transition" style="color: {{ $whiteLabel['accent_color'] }};" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <div class="mt-8">
            <button type="submit" class="w-full flex justify-center items-center gap-2 rounded-xl px-4 py-3.5 text-sm font-bold tracking-wide transition transform active:scale-[0.98]" style="background-color: {{ $whiteLabel['button_color'] }}; color: {{ $whiteLabel['button_text_color'] }};">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                {{ __('SECURE LOG IN') }}
            </button>
        </div>
    </form>
</x-guest-layout>
