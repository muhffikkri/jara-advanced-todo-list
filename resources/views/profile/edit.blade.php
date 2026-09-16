<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profil</title>
</head>
<body>
    <main>
        <h1>Profil pengguna</h1>

        @if (session('status'))
            <p>{{ session('status') }}</p>
        @endif

        @if ($errors->any())
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')
            <label>
                Nama
                <input type="text" name="name" value="{{ old('name', $user->name) }}" required autofocus>
            </label>
            <label>
                Email
                <input type="email" name="email" value="{{ old('email', $user->email) }}" required>
            </label>
            <label>
                Password saat ini (wajib jika mengganti password)
                <input type="password" name="current_password">
            </label>
            <label>
                Password baru
                <input type="password" name="password">
            </label>
            <label>
                Konfirmasi password baru
                <input type="password" name="password_confirmation">
            </label>
            <button type="submit">Simpan perubahan</button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit">Keluar</button>
        </form>
    </main>
</body>
</html>
