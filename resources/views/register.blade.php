<?php
session_start();
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = $_POST['email'];
    $password = $_POST['password'];

    // Koneksi ke database
    $conn = new mysqli('127.0.0.1:8000', 'root', '', 'buyee_web');
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Cari user berdasarkan email atau no_hp
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? OR no_hp = ? LIMIT 1");
    $stmt->bind_param("ss", $input, $input);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Email/No.Hp atau password salah!";
    }
}
?>
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
            cursor: pointer;
        }
        .google-btn img { width: 40px; }
        .or { margin: 10px 0; color: #aaa; }
        input[type="text"], input[type="password"] {
            width: 100%; padding: 8px; /* samain dengan google-btn */
            border: 1px solid #bbb; border-radius: 6px; font-size: 1em;
            box-sizing: border-box; display: flex; align-items: center;
            margin-bottom: 12px;
            font-family: 'Inter', sans-serif;
        }
        .remember { float: left; margin: 10px 0 20px 0; font-size: 0.9em; }
        .login-btn {
            width: 100%; background: #f8a9c2; color: #fff;
            border: none; border-radius: 8px; padding: 12px;
            font-size: 1.1em; font-weight: bold; cursor: pointer;
            margin-top: 10px;
        }
        .register-link { color: #f8a9c2; text-decoration: none; }
        .forgot { display: block; margin: 15px 0 0 0; color: #888; font-size: 0.95em; }
        .terms { color: #888; font-size: 0.85em; margin-top: 20px; }
        .error { color: #e74c3c; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="login-container">
        <img src="/assets/logo.png" alt="Logo" class="logo">
        {{-- <div style="color:#f8a9c2;font-size:13px;margin-bottom:2px;">Fast Deals, Big Feels</div> --}}
        <div class="title">Daftar ke Buyee</div>
        <div class="subtitle">Sudah punya akun? <a href="{{ url('/login') }}" class="register-link">Login disini</a></div>

        {{-- Tampilkan error kalau ada --}}
        @if($errors->any())
            <div class="error">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ url('/login') }}" autocomplete="off">
            @csrf
            <button type="button" class="google-btn" onclick="alert('Fitur Google belum tersedia')">
                <img src="/assets/google.png" alt="google">
                Daftar dengan Google
            </button>
            <div class="or">atau</div>
            <input type="text" name="nama" placeholder="nama" required>
            <input type="text" name="email" placeholder="email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="login-btn">Daftar</button>
        </form>

        <a href="{{ url('/forgot') }}" class="forgot">lupa kata sandi?</a>
        <div class="terms">
            Dengan masuk, Saya setuju dengan syarat dan Ketentuan serta Kebijakan Privasi Buyee
        </div>
    </div>
</body>
</html>
