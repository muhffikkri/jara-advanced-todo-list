<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk</title>
</head>
<body>
    <main>
        <h1>Masuk</h1>

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

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <label>
                Email
                <input type="email" name="email" value="{{ old('email') }}" required autofocus>
            </label>
            <label>
                Password
                <input type="password" name="password" required>
            </label>
            <label>
                <input type="checkbox" name="remember" value="1">
                Ingat saya
            </label>
            <button type="submit">Masuk</button>
        </form>

        <a href="{{ route('register') }}">Belum punya akun?</a>
    </main>
</body>
</html>
