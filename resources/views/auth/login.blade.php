@extends('layouts.app', ['title' => 'Masuk | JARA'])

@section('content')
<main class="mx-auto flex min-h-[calc(100vh-77px)] max-w-6xl items-center justify-center px-6 py-12">
    <div class="grid w-full max-w-4xl overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl shadow-blue-900/5 md:grid-cols-2">
        <div class="hidden bg-blue-900 p-10 text-white md:block">
            <span class="inline-flex rounded-full bg-blue-800 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-blue-100">Workspace produktif</span>
            <h1 class="mt-8 text-4xl font-bold leading-tight">Selesaikan tugas dengan lebih terarah.</h1>
            <p class="mt-5 text-blue-100">Kelola daftar pekerjaan pribadi maupun tim dalam satu ruang yang rapi.</p>
        </div>
        <div class="p-8 sm:p-10">
            <p class="text-sm font-semibold text-blue-700">Selamat datang kembali</p>
            <h2 class="mt-2 text-3xl font-bold text-slate-900">Masuk ke JARA</h2>
            <p class="mt-2 text-sm text-slate-500">Gunakan akunmu untuk melanjutkan.</p>

            @if ($errors->any())
                <div class="mt-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    <ul class="list-inside list-disc space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="mt-7 space-y-5">
                @csrf
                <div>
                    <label for="email" class="field-label">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" class="field-input" required autofocus>
                </div>
                <div>
                    <label for="password" class="field-label">Password</label>
                    <input id="password" type="password" name="password" class="field-input" required>
                </div>
                <label class="flex items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" name="remember" value="1" class="h-4 w-4 rounded border-slate-300 text-blue-700 focus:ring-blue-500">
                    Ingat saya
                </label>
                <button type="submit" class="primary-button">Masuk ke akun</button>
            </form>
            <p class="mt-7 text-center text-sm text-slate-500">Belum punya akun? <a href="{{ route('register') }}" class="font-semibold text-blue-700 hover:text-blue-900">Daftar sekarang</a></p>
        </div>
    </div>
</main>
@endsection
