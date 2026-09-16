<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('app.name', 'JARA') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    <nav class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-900 text-lg font-bold text-white">J</span>
                <span class="text-xl font-bold tracking-tight text-blue-900">JARA</span>
            </a>
            <div class="flex items-center gap-3 text-sm font-semibold">
                @auth
                    <a href="{{ route('profile.edit') }}" class="rounded-lg px-3 py-2 text-slate-600 transition hover:bg-blue-50 hover:text-blue-800">{{ auth()->user()->name }}</a>
                    @if (auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="rounded-lg px-3 py-2 text-slate-600 transition hover:bg-blue-50 hover:text-blue-800">Admin</a>
                    @endif
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="rounded-lg bg-blue-900 px-4 py-2 text-white transition hover:bg-blue-800">Keluar</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="rounded-lg px-3 py-2 text-slate-600 transition hover:bg-blue-50 hover:text-blue-800">Masuk</a>
                    <a href="{{ route('register') }}" class="rounded-lg bg-blue-900 px-4 py-2 text-white transition hover:bg-blue-800">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    @if (session('status'))
        <div class="mx-auto mt-6 max-w-6xl px-6">
            <div class="rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700">{{ session('status') }}</div>
        </div>
    @endif

    @yield('content')
</body>
</html>
