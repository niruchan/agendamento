<x-guest-layout>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="flex flex-col items-center mb-6">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-12 h-12 text-blue-600 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <h2 class="text-xl font-bold text-gray-800">おかえりなさい</h2>
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">ログイン状態を保存する</span>
            </label>
            
            @if (Route::has('password.request'))
                <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('password.request') }}">
                    パスワードを忘れた
                </a>
            @endif
        </div>

        <div class="mt-6 flex flex-col items-center">
            <div class="g-recaptcha" data-sitekey="{{ env('NOCAPTCHA_SITEKEY') }}"></div>
            @if ($errors->has('g-recaptcha-response'))
                <p class="mt-2 text-xs text-red-600 text-center">
                    {{ $errors->first('g-recaptcha-response') }}
                </p>
            @endif
        </div>

        <div class="mt-6">
            <x-primary-button class="w-full justify-center py-3 bg-blue-600 hover:bg-blue-700">
                ログイン
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>