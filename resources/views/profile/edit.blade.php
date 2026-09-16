@extends('layouts.app', ['title' => 'Profil | JARA'])

@section('content')
<main class="mx-auto max-w-6xl px-6 py-12">
    <div class="mb-8">
        <p class="text-sm font-semibold text-blue-700">Pengaturan akun</p>
        <h1 class="mt-2 text-3xl font-bold text-slate-900">Profil pengguna</h1>
        <p class="mt-2 text-slate-500">Kelola informasi pribadi dan keamanan akunmu.</p>
    </div>
    <div class="grid gap-6 lg:grid-cols-[0.8fr_1.2fr]">
        <aside class="rounded-2xl bg-blue-900 p-7 text-white shadow-lg shadow-blue-900/10">
            <div class="flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-700 text-2xl font-bold">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            <h2 class="mt-6 text-xl font-bold">{{ $user->name }}</h2>
            <p class="mt-1 text-sm text-blue-200">{{ $user->email }}</p>
            <span class="mt-6 inline-flex rounded-full bg-blue-800 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-blue-100">{{ $user->role }}</span>
        </aside>
        <section class="rounded-2xl border border-slate-200 bg-white p-7 shadow-sm sm:p-9">
            @if (session('status'))<div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm font-medium text-green-700">{{ session('status') }}</div>@endif
            @if ($errors->any())<div class="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700"><ul class="list-inside list-disc space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>@endif
            <h2 class="text-xl font-bold text-slate-900">Informasi dasar</h2>
            <form method="POST" action="{{ route('profile.update') }}" class="mt-6 space-y-5">
                @csrf @method('PUT')
                <div><label for="name" class="field-label">Nama lengkap</label><input id="name" type="text" name="name" value="{{ old('name', $user->name) }}" class="field-input" required autofocus></div>
                <div><label for="email" class="field-label">Email</label><input id="email" type="email" name="email" value="{{ old('email', $user->email) }}" class="field-input" required></div>
                <div class="border-t border-slate-200 pt-6"><h3 class="font-semibold text-slate-900">Ganti password</h3><p class="mt-1 text-sm text-slate-500">Kosongkan jika tidak ingin mengganti password.</p></div>
                <div><label for="current_password" class="field-label">Password saat ini</label><input id="current_password" type="password" name="current_password" class="field-input"></div>
                <div class="grid gap-5 sm:grid-cols-2"><div><label for="password" class="field-label">Password baru</label><input id="password" type="password" name="password" class="field-input"></div><div><label for="password_confirmation" class="field-label">Konfirmasi password</label><input id="password_confirmation" type="password" name="password_confirmation" class="field-input"></div></div>
                <button type="submit" class="primary-button sm:w-auto">Simpan perubahan</button>
            </form>
        </section>
    </div>
</main>
@endsection
