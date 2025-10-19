<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Daftar Buyee</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { background: #fff; font-family: 'Inter', sans-serif; }
        .login-container {
            width: 400px; margin: 60px auto; background: #fff;
            border-radius: 20px; box-shadow: 10px 10px 20px #ccc;
            padding: 40px 30px; text-align: center;
        }
        .logo { width: 200px; margin-bottom: 10px; }
        .title { font-size: 2em; font-weight: bold; margin: 10px 0 5px 0; }
        .subtitle { color: #888; margin-bottom: 20px; }
        .google-btn {
            border: 1px solid #bbb; border-radius: 6px; padding: 8px;
            width: 100%; background: #fff; font-size: 1em; margin-bottom: 10px;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            cursor: pointer; text-decoration: none; color: inherit;
        }
        .google-btn img { width: 40px; }
        .or {
            display: flex;
            align-items: center;
            text-align: center;
            color: #aaa;
            margin: 15px 0;
            font-size: 14px;
        }
        .or::before, .or::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #aaa;
            margin: 0 20px;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%; padding: 8px;
            border: 1px solid #bbb; border-radius: 6px; font-size: 1em;
            box-sizing: border-box; display: flex; align-items: center;
            margin-bottom: 12px;
            font-family: 'Inter', sans-serif;
        }
        input[type="text"]:focus,
        input[type="email"]:focus,
        input[type="password"]:focus {
            border-color: #f27ca5; outline: none;
            box-shadow: 0 0 8px rgba(242,124,165,0.6);
        }
        .login-btn {
            width: 100%; background: #f8a9c2; color: #fff;
            border: none; border-radius: 8px; padding: 12px;
            font-size: 1.1em; font-weight: bold; cursor: pointer;
            margin-top: 10px;
        }
        .login-btn:hover {
            background: #f27ca5; transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .login-link { color: #f8a9c2; text-decoration: none; transition: all 0.3s ease; }
        .login-link:hover { color: #f27ca5; text-decoration: underline; }
        .error { color: #e74c3c; margin-bottom: 10px; text-align: left; font-size: 0.9em; }
        .success { color: #2ecc71; margin-bottom: 10px; font-size: 0.95em; }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="/images/logo.png" alt="Logo" class="logo">
        <div class="title">Daftar Buyee</div>
        <div class="subtitle">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="login-link">Masuk di sini</a>
        </div>

        @if(session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        <form method="POST" action="{{ route('register') }}" autocomplete="off">
            @csrf

            <input type="text" name="name" placeholder="Nama Lengkap" value="{{ old('name') }}" required>
            @error('name') <div class="error">{{ $message }}</div> @enderror

            <input type="email" name="email" placeholder="Email" value="{{ old('email') }}" required>
            @error('email') <div class="error">{{ $message }}</div> @enderror

            <input type="password" name="password" placeholder="Password" required>
            @error('password') <div class="error">{{ $message }}</div> @enderror

            <button type="submit" class="login-btn">Daftar</button>
        </form>
    </div>
</body>
</html>
