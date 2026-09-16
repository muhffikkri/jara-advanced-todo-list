@extends('layouts.app', ['title' => 'JARA | Kelola tugas lebih terarah'])

@section('content')
<main>
    <section class="mx-auto grid max-w-6xl gap-12 px-6 py-20 lg:grid-cols-[1.1fr_0.9fr] lg:items-center">
        <div>
            <span class="inline-flex rounded-full bg-blue-50 px-3 py-1 text-xs font-bold uppercase tracking-wider text-blue-700 ring-1 ring-inset ring-blue-200">Advanced todo list</span>
            <h1 class="mt-6 text-5xl font-bold leading-tight tracking-tight text-slate-900 sm:text-6xl">Rencanakan. Kerjakan. <span class="text-blue-700">Selesaikan.</span></h1>
            <p class="mt-6 max-w-xl text-lg leading-8 text-slate-600">JARA membantu kamu mengelola tugas pribadi dan kolaborasi tim dengan lebih rapi, fokus, dan terukur.</p>
            <div class="mt-8 flex flex-wrap gap-3">
                @auth
                    <a href="{{ route('profile.edit') }}" class="primary-button sm:w-auto">Buka profil</a>
                @else
                    <a href="{{ route('register') }}" class="primary-button sm:w-auto">Mulai sekarang</a>
                    <a href="{{ route('login') }}" class="secondary-button sm:w-auto">Saya sudah punya akun</a>
                @endauth
            </div>
        </div>
        <div class="relative">
            <div class="absolute -inset-4 rounded-[2rem] bg-blue-100/60 blur-2xl"></div>
            <div class="relative rounded-3xl bg-blue-900 p-8 text-white shadow-2xl shadow-blue-900/20">
                <div class="flex items-center justify-between border-b border-blue-800 pb-5"><div><p class="text-sm text-blue-200">Ringkasan hari ini</p><p class="mt-1 text-2xl font-bold">Tetap produktif</p></div><span class="rounded-xl bg-blue-700 px-3 py-2 text-sm font-bold">JARA</span></div>
                <div class="mt-7 space-y-4">
                    <div class="flex items-center gap-4 rounded-2xl bg-blue-800/70 p-4"><span class="h-3 w-3 rounded-full bg-green-400"></span><div><p class="font-semibold">Susun prioritas tugas</p><p class="text-sm text-blue-200">Selesai hari ini</p></div></div>
                    <div class="flex items-center gap-4 rounded-2xl bg-blue-800/70 p-4"><span class="h-3 w-3 rounded-full bg-amber-400"></span><div><p class="font-semibold">Kolaborasi bersama tim</p><p class="text-sm text-blue-200">Sedang dikerjakan</p></div></div>
                    <div class="flex items-center gap-4 rounded-2xl bg-blue-800/70 p-4"><span class="h-3 w-3 rounded-full bg-blue-300"></span><div><p class="font-semibold">Pantau progres proyek</p><p class="text-sm text-blue-200">Tetap terukur</p></div></div>
                </div>
            </div>
        </div>
    </section>
    <section class="border-y border-slate-200 bg-white">
        <div class="mx-auto grid max-w-6xl gap-8 px-6 py-14 md:grid-cols-3">
            <div><div class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-blue-50 font-bold text-blue-700">01</div><h2 class="font-bold text-slate-900">Atur daftar</h2><p class="mt-2 text-sm leading-6 text-slate-500">Kelompokkan pekerjaan berdasarkan proyek dan tujuan.</p></div>
            <div><div class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-amber-50 font-bold text-amber-700">02</div><h2 class="font-bold text-slate-900">Kerjakan bersama</h2><p class="mt-2 text-sm leading-6 text-slate-500">Bangun kolaborasi yang jelas bersama anggota tim.</p></div>
            <div><div class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-green-50 font-bold text-green-700">03</div><h2 class="font-bold text-slate-900">Pantau progres</h2><p class="mt-2 text-sm leading-6 text-slate-500">Lihat kemajuan tugas dan capai target tepat waktu.</p></div>
        </div>
    </section>
</main>
@endsection
