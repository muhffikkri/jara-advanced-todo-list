@extends('layouts.app', ['title' => $list->name.' | JARA'])

@section('content')
<main class="mx-auto max-w-6xl space-y-6 px-6 py-12">
    <a href="{{ route('lists.index') }}" class="text-sm font-semibold text-blue-700 hover:text-blue-900">&larr; Kembali ke daftar</a>

    @if ($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <ul class="list-inside list-disc space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <section class="rounded-2xl bg-blue-900 p-7 text-white shadow-lg sm:p-9">
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div>
                <p class="text-sm text-blue-200">{{ $isOwner ? 'Daftar milikmu' : 'Daftar kolaborasi' }}</p>
                <h1 class="mt-2 text-3xl font-bold">{{ $list->name }}</h1>
                @if ($list->description)<p class="mt-2 max-w-2xl text-blue-100">{{ $list->description }}</p>@endif
            </div>
            <span class="rounded-xl bg-blue-700 px-4 py-2 text-sm font-bold">{{ $progress }}% selesai</span>
        </div>
        <div class="mt-6 h-2.5 overflow-hidden rounded-full bg-blue-800"><div class="h-full rounded-full bg-green-400" style="width: {{ $progress }}%"></div></div>
        <p class="mt-3 text-sm text-blue-200">{{ $completedTasks }} dari {{ $totalTasks }} tugas selesai.</p>
    </section>

    @if ($isOwner)
        <section class="rounded-2xl border border-slate-200 bg-white p-7 shadow-sm">
            <h2 class="text-lg font-bold text-slate-900">Pengaturan daftar</h2>
            <form method="POST" action="{{ route('lists.update', $list) }}" class="mt-4 grid gap-4 sm:grid-cols-[1fr_1.5fr_auto]">
                @csrf @method('PATCH')
                <div><label for="name" class="field-label">Nama</label><input id="name" type="text" name="name" value="{{ old('name', $list->name) }}" class="field-input" required></div>
                <div><label for="description" class="field-label">Deskripsi</label><input id="description" type="text" name="description" value="{{ old('description', $list->description) }}" class="field-input"></div>
                <div class="flex items-end"><button type="submit" class="primary-button sm:w-auto">Simpan</button></div>
            </form>
        </section>
    @endif

    <div class="grid gap-6 lg:grid-cols-[1.4fr_1fr]">
        <section class="rounded-2xl border border-slate-200 bg-white p-7 shadow-sm">
            <h2 class="text-lg font-bold text-slate-900">Tugas ({{ $totalTasks }})</h2>
            <form method="POST" action="{{ route('tasks.store', $list) }}" class="mt-4 space-y-4 border-b border-slate-200 pb-6">
                @csrf
                <div><label for="title" class="field-label">Judul tugas</label><input id="title" type="text" name="title" value="{{ old('title') }}" class="field-input" required></div>
                <div><label for="task_description" class="field-label">Deskripsi (opsional)</label><input id="task_description" type="text" name="description" value="{{ old('description') }}" class="field-input"></div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="priority" class="field-label">Prioritas</label>
                        <select id="priority" name="priority" class="field-input" required>
                            @foreach (['low' => 'Rendah', 'medium' => 'Sedang', 'high' => 'Tinggi', 'urgent' => 'Urgent'] as $value => $label)
                                <option value="{{ $value }}" @selected(old('priority', 'medium') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div><label for="due_date" class="field-label">Tenggat (opsional)</label><input id="due_date" type="date" name="due_date" value="{{ old('due_date') }}" class="field-input"></div>
                </div>
                <button type="submit" class="primary-button sm:w-auto">Tambah tugas</button>
            </form>

            <div class="mt-6 space-y-4">
                @forelse ($tasks as $task)
                    <article class="rounded-xl border border-slate-200 p-4 {{ $task->is_completed ? 'bg-green-50/60' : 'bg-white' }}">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="font-bold text-slate-900 {{ $task->is_completed ? 'line-through' : '' }}">{{ $task->title }}</p>
                                @if ($task->description)<p class="mt-1 text-sm text-slate-500">{{ $task->description }}</p>@endif
                                <div class="mt-2 flex flex-wrap gap-2 text-xs font-semibold">
                                    <span class="rounded-full bg-slate-100 px-2 py-1 text-slate-600">{{ ucfirst($task->priority) }}</span>
                                    @if ($task->due_date)<span class="rounded-full bg-amber-50 px-2 py-1 text-amber-700">{{ $task->due_date->format('d M Y') }}</span>@endif
                                    <span class="rounded-full px-2 py-1 {{ $task->is_completed ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-600' }}">{{ $task->is_completed ? 'Selesai' : 'Belum selesai' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="mt-3 flex flex-wrap gap-2">
                            <form method="POST" action="{{ route('tasks.toggle', $task) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-bold text-slate-700 hover:bg-blue-50">{{ $task->is_completed ? 'Batalkan selesai' : 'Tandai selesai' }}</button>
                            </form>
                            <form method="POST" action="{{ route('tasks.destroy', $task) }}" onsubmit="return confirm('Hapus tugas ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="rounded-lg px-3 py-1.5 text-xs font-bold text-red-600 hover:bg-red-50">Hapus</button>
                            </form>
                        </div>
                        <details class="mt-3 text-sm">
                            <summary class="cursor-pointer font-semibold text-blue-700">Edit tugas</summary>
                            <form method="POST" action="{{ route('tasks.update', $task) }}" class="mt-3 space-y-3">
                                @csrf @method('PATCH')
                                <input type="text" name="title" value="{{ $task->title }}" class="field-input" required>
                                <input type="text" name="description" value="{{ $task->description }}" class="field-input" placeholder="Deskripsi">
                                <div class="grid grid-cols-2 gap-3">
                                    <select name="priority" class="field-input">
                                        @foreach (['low', 'medium', 'high', 'urgent'] as $value)
                                            <option value="{{ $value }}" @selected($task->priority === $value)>{{ ucfirst($value) }}</option>
                                        @endforeach
                                    </select>
                                    <input type="date" name="due_date" value="{{ $task->due_date?->format('Y-m-d') }}" class="field-input">
                                </div>
                                <button type="submit" class="secondary-button sm:w-auto">Simpan perubahan</button>
                            </form>
                        </details>
                    </article>
                @empty
                    <p class="text-sm text-slate-500">Belum ada tugas di daftar ini.</p>
                @endforelse
            </div>
        </section>

        <section class="h-fit rounded-2xl border border-slate-200 bg-white p-7 shadow-sm">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-bold text-slate-900">Anggota ({{ $members->count() }})</h2>
                <a href="{{ route('lists.members.index', $list) }}" class="text-sm font-semibold text-blue-700 hover:text-blue-900">Lihat semua</a>
            </div>
            <ul class="mt-4 space-y-3">
                @forelse ($members as $member)
                    <li class="flex items-center justify-between gap-3 rounded-xl bg-slate-50 px-4 py-3 text-sm">
                        <div><p class="font-bold text-slate-900">{{ $member->name }}</p><p class="text-slate-500">{{ $member->email }}</p></div>
                        @if ($isOwner)
                            <form method="POST" action="{{ route('lists.members.destroy', [$list, $member]) }}" onsubmit="return confirm('Hapus anggota ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs font-bold text-red-600 hover:text-red-800">Hapus</button>
                            </form>
                        @endif
                    </li>
                @empty
                    <li class="text-sm text-slate-500">Belum ada anggota.</li>
                @endforelse
            </ul>
            @if ($isOwner)
                <form method="POST" action="{{ route('lists.members.store', $list) }}" class="mt-5 space-y-3 border-t border-slate-200 pt-5">
                    @csrf
                    <div><label for="user_id" class="field-label">ID pengguna baru</label><input id="user_id" type="number" name="user_id" class="field-input" required placeholder="cth: 3"></div>
                    <button type="submit" class="primary-button">Tambah anggota</button>
                </form>
            @endif
        </section>
    </div>
</main>
@endsection
