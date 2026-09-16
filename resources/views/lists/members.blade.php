@extends('layouts.app', ['title' => 'Anggota | '.$list->name])

@section('content')
<main class="mx-auto max-w-4xl px-6 py-12">
    <a href="{{ route('lists.show', $list) }}" class="text-sm font-semibold text-blue-700 hover:text-blue-900">&larr; Kembali ke {{ $list->name }}</a>
    <h1 class="mt-4 text-3xl font-bold text-slate-900">Anggota daftar</h1>
    <p class="mt-2 text-slate-500">{{ $members->count() }} anggota dalam daftar ini.</p>

    @if ($errors->any())
        <div class="mt-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <ul class="list-inside list-disc space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <ul class="mt-6 space-y-3">
        @forelse ($members as $member)
            <li class="flex items-center justify-between gap-3 rounded-2xl border border-slate-200 bg-white px-5 py-4">
                <div><p class="font-bold text-slate-900">{{ $member->name }}</p><p class="text-sm text-slate-500">{{ $member->email }}</p></div>
                @if ($list->owner_id === auth()->id())
                    <form method="POST" action="{{ route('lists.members.destroy', [$list, $member]) }}" onsubmit="return confirm('Hapus anggota ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-sm font-bold text-red-600 hover:text-red-800">Hapus</button>
                    </form>
                @endif
            </li>
        @empty
            <li class="rounded-2xl border border-dashed border-slate-300 bg-white p-8 text-center text-sm text-slate-500">Belum ada anggota.</li>
        @endforelse
    </ul>

    @if ($list->owner_id === auth()->id())
        <form method="POST" action="{{ route('lists.members.store', $list) }}" class="mt-6 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
            @csrf
            <label for="user_id" class="field-label">Tambah anggota via ID pengguna</label>
            <div class="flex gap-3">
                <input id="user_id" type="number" name="user_id" class="field-input" required placeholder="cth: 3">
                <button type="submit" class="primary-button sm:w-auto">Tambah</button>
            </div>
        </form>
    @endif
</main>
@endsection
