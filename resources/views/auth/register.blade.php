@extends('layouts.app', ['title' => 'Daftar | JARA'])

@section('content')
<main class="mx-auto flex min-h-[calc(100vh-77px)] max-w-6xl items-center justify-center px-6 py-12">
    <div class="w-full max-w-lg rounded-3xl border border-slate-200 bg-white p-8 shadow-xl shadow-blue-900/5 sm:p-10">
        <div class="text-center">
            <span class="mx-auto flex h-12 w-12 items-center justify-center rounded-2xl bg-blue-900 text-xl font-bold text-white">J</span>
            <p class="mt-6 text-sm font-semibold text-blue-700">Mulai lebih terorganisir</p>
            <h1 class="mt-2 text-3xl font-bold text-slate-900">Buat akun JARA</h1>
            <p class="mt-2 text-sm text-slate-500">Buat ruang kerja untuk mengelola tugasmu.</p>
        </div>

        @if ($errors->any())
            <div class="mt-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <ul class="list-inside list-disc space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" class="mt-7 space-y-5">
            @csrf
            <div><label for="name" class="field-label">Nama lengkap</label><input id="name" type="text" name="name" value="{{ old('name') }}" class="field-input" required autofocus></div>
            <div><label for="email" class="field-label">Email</label><input id="email" type="email" name="email" value="{{ old('email') }}" class="field-input" required></div>
            <div class="grid gap-5 sm:grid-cols-2">
                <div><label for="password" class="field-label">Password</label><input id="password" type="password" name="password" class="field-input" required></div>
                <div><label for="password_confirmation" class="field-label">Konfirmasi password</label><input id="password_confirmation" type="password" name="password_confirmation" class="field-input" required></div>
            </div>
            <button type="submit" class="primary-button">Buat akun</button>
        </form>
        <p class="mt-7 text-center text-sm text-slate-500">Sudah punya akun? <a href="{{ route('login') }}" class="font-semibold text-blue-700 hover:text-blue-900">Masuk di sini</a></p>
    </div>
</main>
@endsection
