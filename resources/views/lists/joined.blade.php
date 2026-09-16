@extends('layouts.app', ['title' => 'Daftar Diikuti | JARA'])

@section('content')
<main class="mx-auto max-w-6xl px-6 py-12">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-sm font-semibold text-blue-700">Kolaborasi</p>
            <h1 class="mt-2 text-3xl font-bold text-slate-900">Daftar yang diikuti</h1>
            <p class="mt-2 text-slate-500">Daftar milik orang lain yang kamu ikuti sebagai anggota.</p>
        </div>
        <a href="{{ route('lists.index') }}" class="secondary-button sm:w-auto">Kembali ke daftar saya</a>
    </div>

    <section class="mt-8 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
        @forelse ($lists as $list)
            @php($progress = \App\Support\ListProgress::percentage((int) $list->tasks_count, (int) $list->completed_tasks_count))
            <article class="flex flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-bold text-slate-900">{{ $list->name }}</h2>
                @if ($list->description)<p class="mt-2 text-sm text-slate-500">{{ $list->description }}</p>@endif
                <div class="mt-4">
                    <div class="flex items-center justify-between text-xs font-semibold text-slate-500"><span>Progres</span><span>{{ $progress }}% ({{ $list->completed_tasks_count }}/{{ $list->tasks_count }})</span></div>
                    <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100"><div class="h-full rounded-full bg-blue-700" style="width: {{ $progress }}%"></div></div>
                </div>
                <a href="{{ route('lists.show', $list) }}" class="secondary-button mt-6">Buka</a>
            </article>
        @empty
            <p class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center text-sm text-slate-500 md:col-span-2 lg:col-span-3">Kamu belum mengikuti daftar apapun.</p>
        @endforelse
    </section>
</main>
@endsection
