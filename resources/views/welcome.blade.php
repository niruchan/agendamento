<body class="bg-white text-gray-900 font-sans antialiased">

    <div class="flex flex-col items-center justify-center min-h-screen px-4">
        
        <div class="mb-12 text-center">
            <h1 class="text-3xl font-bold tracking-tight">予約管理</h1>
        </div>

        <div class="w-full max-w-[320px] space-y-4">
            @auth
                <a href="{{ url('/dashboard') }}" class="flex items-center justify-center w-full py-3 bg-blue-600 text-white rounded-md font-medium hover:bg-blue-700 transition">
                    ダッシュボードを開く
                </a>
            @else
                <a href="{{ route('login') }}" class="flex items-center justify-center w-full py-3 bg-blue-600 text-white rounded-md font-medium hover:bg-blue-700 transition">
                    ログイン
                </a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}" class="flex items-center justify-center w-full py-3 bg-white border border-gray-300 text-gray-700 rounded-md font-medium hover:bg-gray-50 transition">
                        新規登録
                    </a>
                @endif
            @endauth
        </div>

        <div class="mt-20 text-xs text-gray-400">
            &copy; {{ date('Y') }} Reservation System.
        </div>
        
    </div>

</body>