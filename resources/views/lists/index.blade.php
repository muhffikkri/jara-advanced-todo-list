@extends('layouts.app', ['title' => 'Daftar Saya | JARA'])

@section('content')
<main class="mx-auto max-w-6xl px-6 py-12">
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-sm font-semibold text-blue-700">Workspace</p>
            <h1 class="mt-2 text-3xl font-bold text-slate-900">Daftar saya</h1>
            <p class="mt-2 text-slate-500">Kelola daftar pribadi dan kolaborasi dalam satu tempat.</p>
        </div>
        <a href="{{ route('lists.joined') }}" class="secondary-button sm:w-auto">Daftar yang diikuti</a>
    </div>

    @if ($errors->any())
        <div class="mt-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <ul class="list-inside list-disc space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <section class="mt-8 rounded-2xl border border-slate-200 bg-white p-7 shadow-sm">
        <h2 class="text-lg font-bold text-slate-900">Buat daftar baru</h2>
        <form method="POST" action="{{ route('lists.store') }}" class="mt-4 grid gap-4 sm:grid-cols-[1fr_1.5fr_auto]">
            @csrf
            <div><label for="name" class="field-label">Nama daftar</label><input id="name" type="text" name="name" value="{{ old('name') }}" class="field-input" required></div>
            <div><label for="description" class="field-label">Deskripsi (opsional)</label><input id="description" type="text" name="description" value="{{ old('description') }}" class="field-input"></div>
            <div class="flex items-end"><button type="submit" class="primary-button sm:w-auto">Buat</button></div>
        </form>
    </section>

    <section class="mt-8 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
        @forelse ($lists as $list)
            @php($progress = \App\Support\ListProgress::percentage((int) $list->tasks_count, (int) $list->completed_tasks_count))
            <article class="flex flex-col rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                <div class="flex items-start justify-between gap-3">
                    <h2 class="text-lg font-bold text-slate-900">{{ $list->name }}</h2>
                    @if ($list->owner_id === auth()->id())
                        <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-bold text-blue-700">Pemilik</span>
                    @else
                        <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-bold text-amber-700">Anggota</span>
                    @endif
                </div>
                @if ($list->description)<p class="mt-2 text-sm text-slate-500">{{ $list->description }}</p>@endif
                <div class="mt-4">
                    <div class="flex items-center justify-between text-xs font-semibold text-slate-500"><span>Progres</span><span>{{ $progress }}% ({{ $list->completed_tasks_count }}/{{ $list->tasks_count }})</span></div>
                    <div class="mt-2 h-2 overflow-hidden rounded-full bg-slate-100"><div class="h-full rounded-full bg-blue-700" style="width: {{ $progress }}%"></div></div>
                </div>
                <div class="mt-6 flex items-center gap-2">
                    <a href="{{ route('lists.show', $list) }}" class="secondary-button">Buka</a>
                    @if ($list->owner_id === auth()->id())
                        <form method="POST" action="{{ route('lists.destroy', $list) }}" onsubmit="return confirm('Hapus daftar beserta seluruh tugas & anggota?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="rounded-xl px-4 py-3 text-sm font-bold text-red-600 transition hover:bg-red-50">Hapus</button>
                        </form>
                    @endif
                </div>
            </article>
        @empty
            <p class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center text-sm text-slate-500 md:col-span-2 lg:col-span-3">Belum ada daftar. Buat daftar pertama kamu di atas.</p>
        @endforelse
    </section>
</main>
@endsection
