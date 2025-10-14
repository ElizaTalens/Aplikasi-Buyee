<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Buyee</title>
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
            cursor: pointer;
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
        input[type="text"], input[type="password"], select {
            width: 100%; padding: 8px;
            border: 1px solid #bbb; border-radius: 6px; font-size: 1em;
            box-sizing: border-box; display: flex; align-items: center;
            margin-bottom: 12px;
            font-family: 'Inter', sans-serif;
        }
        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #f27ca5; outline: none; 
            box-shadow: 0 0 8px rgba(242,124,165,0.6);
        }
        select {
            appearance: none; -webkit-appearance: none; -moz-appearance: none;
            background: #fff url("data:image/svg+xml;utf8,<svg fill='%23666' height='20' viewBox='0 0 24 24' width='20' xmlns='http://www.w3.org/2000/svg'><path d='M7 10l5 5 5-5z'/></svg>") no-repeat right 10px center;
            background-size: 16px 16px; padding-right: 35px;
        }
        .remember { float: left; margin: 10px 0 20px 0; font-size: 0.9em; }
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
        .register-link { color: #f8a9c2; text-decoration: none; transition: all 0.3s ease; }
        .register-link:hover { color: #f27ca5; text-decoration: underline; }
        .forgot { display: block; margin: 15px 0 0 0; color: #888; font-size: 0.95em; }
        .terms { color: #888; font-size: 0.85em; margin-top: 20px; }
        .error { color: #e74c3c; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="/images/logo.png" alt="Logo" class="logo">
        <div class="title">Masuk ke Buyee</div>
        <div class="subtitle">
            Belum punya akun?
            <a href="{{ route('register.form') }}" class="register-link">Daftar disini</a>
        </div>

        {{-- Error global (pertama) --}}
        @if($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('login.submit') }}" autocomplete="off">
            @csrf

            <button type="button" class="google-btn" onclick="alert('Fitur Google belum tersedia')">
                <img src="/images/google.png" alt="google"> Masuk dengan Google
            </button>

            <div class="or">atau</div>

            <input type="text" name="email" placeholder="Email" value="{{ old('email') }}" required>
            @error('email') <div class="error">{{ $message }}</div> @enderror

            <input type="password" name="password" placeholder="Password" required>
            @error('password') <div class="error">{{ $message }}</div> @enderror

            <select name="role" required>
                <option value="" disabled {{ old('role') ? '' : 'selected' }}>Pilih Role</option>
                <option value="admin" {{ old('role')==='admin' ? 'selected' : '' }}>Admin</option>
                <option value="user"  {{ old('role')==='user'  ? 'selected' : '' }}>User</option>
            </select>
            @error('role') <div class="error">{{ $message }}</div> @enderror

            <div class="remember">
                <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label for="remember">Ingat saya</label>
            </div>

            <button type="submit" class="login-btn">Masuk</button>
        </form>

        <div class="terms">
            Dengan masuk, Saya setuju dengan Syarat & Ketentuan serta Kebijakan Privasi Buyee
        </div>
    </div>
</body>
</html>
