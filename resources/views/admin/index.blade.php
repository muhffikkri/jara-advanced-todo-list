@extends('layouts.app', ['title' => 'Admin | Kelola Pengguna'])

@section('content')
<main class="mx-auto max-w-6xl px-6 py-12">
    <p class="text-sm font-semibold text-blue-700">Admin</p>
    <h1 class="mt-2 text-3xl font-bold text-slate-900">Kelola akun pengguna</h1>
    <p class="mt-2 text-slate-500">Tambah dan hapus akun pengguna dalam sistem.</p>

    @if ($errors->any())
        <div class="mt-6 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
            <ul class="list-inside list-disc space-y-1">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>
        </div>
    @endif

    <section class="mt-8 rounded-2xl border border-slate-200 bg-white p-7 shadow-sm">
        <h2 class="text-lg font-bold text-slate-900">Tambah akun baru</h2>
        <form method="POST" action="{{ route('admin.users.store') }}" class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-[1fr_1fr_1fr_1fr_auto]">
            @csrf
            <div><label for="name" class="field-label">Nama</label><input id="name" type="text" name="name" value="{{ old('name') }}" class="field-input" required></div>
            <div><label for="email" class="field-label">Email</label><input id="email" type="email" name="email" value="{{ old('email') }}" class="field-input" required></div>
            <div><label for="password" class="field-label">Password</label><input id="password" type="password" name="password" class="field-input" required></div>
            <div><label for="password_confirmation" class="field-label">Konfirmasi</label><input id="password_confirmation" type="password" name="password_confirmation" class="field-input" required></div>
            <div>
                <label for="role" class="field-label">Peran</label>
                <select id="role" name="role" class="field-input" required>
                    <option value="user" @selected(old('role') === 'user')>User</option>
                    <option value="admin" @selected(old('role') === 'admin')>Admin</option>
                </select>
            </div>
            <div class="flex items-end sm:col-span-2 lg:col-span-1"><button type="submit" class="primary-button sm:w-auto">Tambah</button></div>
        </form>
    </section>

    <section class="mt-8 overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm">
                <thead class="border-b border-slate-200 bg-slate-50 text-xs uppercase tracking-wider text-slate-500">
                    <tr><th class="px-6 py-4">Nama</th><th class="px-6 py-4">Email</th><th class="px-6 py-4">Peran</th><th class="px-6 py-4">Dibuat</th><th class="px-6 py-4 text-right">Aksi</th></tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($users as $user)
                        <tr>
                            <td class="px-6 py-4 font-bold text-slate-900">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-slate-500">{{ $user->email }}</td>
                            <td class="px-6 py-4"><span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-bold text-slate-600">{{ $user->role }}</span></td>
                            <td class="px-6 py-4 text-slate-500">{{ $user->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                @if ($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline" onsubmit="return confirm('Hapus akun {{ $user->email }}?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="text-sm font-bold text-red-600 hover:text-red-800">Hapus</button>
                                    </form>
                                @else
                                    <span class="text-xs font-semibold text-slate-400">Akunmu</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="px-6 py-8 text-center text-slate-500">Belum ada pengguna.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($users instanceof \Illuminate\Pagination\LengthAwarePaginator)
            <div class="border-t border-slate-200 px-6 py-4">{{ $users->links() }}</div>
        @endif
    </section>
</main>
@endsection
