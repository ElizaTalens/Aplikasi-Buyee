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
        body { 
            background: #fff; 
            font-family: 'Inter', sans-serif; 
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh; /* biar form ketengah vertikal */
            overflow-x: hidden; /* cegah scroll horizontal */
        }
        /* overlay pop up muncul */
        .overlay {
            position: fixed; 
            top: 0; 
            left: 0; 
            width: 100%; 
            height: 100%; 
            background: rgba(0,0,0,0.5); 
            display: none; 
            justify-content: center; 
            align-items: center; 
            z-index: 9999; 
        }
        .popup-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            width: 100vw;
        }
        .popup {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.18);
            padding: 32px 24px;
            text-align: center;
            min-width: 300px;
            max-width: 90vw;
        }
        .popup h3{
            margin-bottom: 10px;
        }
        .popup p{
            color: #555;
            margin-bottom: 20px;
        }
        .popup button {
            padding: 10px 20px;
            background: #f8a9c2;
            border: none;
            border-radius: 6px;
            color: #fff;
            font-size: 1em;
            cursor: pointer;
        }
        .btn-gray{
            background: #eee;
            color: #333;
        }
        .btn-pink{
            background: #f27ca5;
            color: #fff;
        }
        .register-container {
            width: 400px; 
            max-width: 95vw;
            margin: 60px auto; 
            background: #fff;
            border-radius: 20px; 
            box-shadow: 0 0 20px rgba(0.15,0.15,0.15,0.15); 
            padding: 40px 30px; 
            text-align: center;
        }
        .logo { 
            width: 150px; 
            margin-bottom: 10px; 
            height: auto;
            margin-bottom: 10px; 
        }
        .title {
            font-size: 2em; 
            font-weight: bold; 
            margin: 10px 0 5px 0; 
        }
        .subtitle {
            color: #888; 
            margin-bottom: 20px; 
        }
        .google-btn {
            border: 1px solid #bbb; 
            border-radius: 6px; 
            padding: 8px;
            width: 100%; 
            background: #fff; 
            font-size: 1em; 
            margin-bottom: 10px;
            display: flex; 
            align-items: center; 
            justify-content: center; 
            gap: 8px;
            cursor: pointer;
            trasnsition: all 0.3s ease;
        }
        .google-btn img { 
            width: 40px; 
        }
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
        input[type="text"], input[type="password"] {
            width: 100%; 
            padding: 8px; 
            border: 1px solid #bbb; 
            border-radius: 6px; 
            font-size: 1em;
            box-sizing: border-box; 
            display: flex; 
            align-items: center;
            margin-bottom: 12px;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s ease;
        }
        input[type="text"]:focus, input[type="password"]:focus {
            border-color: #f27ca5; 
            outline: none; 
            box-shadow: 0 0 8px rgba(242,124,165,0.6);
        }
        .remember { 
            float: left; 
            margin: 10px 0 20px 0; 
            font-size: 0.9em; 
        }
        .register-btn {
            width: 100%; 
            background: #f8a9c2; 
            color: #fff;
            border: none; 
            border-radius: 8px; 
            padding: 12px;
            font-size: 1.1em; 
            font-weight: bold; 
            cursor: pointer;
            margin-top: 10px;
        }
        .register-btn:hover {
            background: #f27ca5; 
            transform: scale(1.05);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .register-link { 
            color: #f8a9c2; 
            text-decoration: none;
            transition: all 0.3s ease;
        }
        .register-link:hover {
            color: #f27ca5; 
            text-decoration: underline;
        }
        .forgot { 
            display: block; 
            margin: 15px 0 0 0; 
            color: #888; 
            font-size: 0.95em; 
        }
        .terms { 
            color: #888; 
            font-size: 0.85em; 
            margin-top: 20px; 
        }
        .error { 
            color: #e74c3c; 
            margin-bottom: 10px; 
        }
    </style>
</head>
<body>
    <div class="register-container">
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

        <form method="POST" action="{{ route('register') }}" autocomplete="off" id="registerForm">
            @csrf
            <button type="button" class="google-btn" onclick="alert('Fitur Google belum tersedia')">
                <img src="/assets/google.png" alt="google">
                Daftar dengan Google
            </button>
            <div class="or"> atau </div>
            <div>
                <input type="text" name="name" placeholder="name" required>
            </div>
            <input type="text" name="email" id="emailInput" placeholder="email" required>
            <input type="password" name="password" placeholder="Password" required>
            {{-- <button type="button" id="showPopupBtn" class="btn-pink">
                Daftar
            </button> --}}
            <button type="button" id="showPopupBtn" class="register-btn">Daftar</button>
        </form>
        <!-- Overlay + Popup -->
        <div class="overlay" id="popupOverlay">
            <div class="popup-container">
                <div class="popup">
                    <h3 id="popupEmail">email@contoh.com</h3>
                    <p>Pastikan alamat kamu benar yaa!</p>
                    <button class="btn-gray" onclick="closePopup()">Ubah</button>
                    <button class="btn-pink" onclick="submitForm()">Ya, Benar</button>
                </div>
            </div>
        </div>

        <script>
            const popupOverlay = document.getElementById('popupOverlay');
            const emailInput = document.getElementById('emailInput');
            const popupEmail = document.getElementById('popupEmail');
            const form = document.getElementById('registerForm');

            // Klik tombol daftar -> munculin popup
            document.getElementById('showPopupBtn').addEventListener('click', function() {
                @if($errors->any())
                    return; // kalau ada error validasi, popup ga usah muncul
                @endif

                if (emailInput.value.trim() === '') return; // email kosong, jangan popup

                popupEmail.textContent = emailInput.value;
                popupOverlay.style.display = 'flex';
            });

            function closePopup() {
                popupOverlay.style.display = 'none';
            }

            function submitForm() {
                form.submit();
            }
        </script>
        <div class="terms">
            Dengan masuk, Saya setuju dengan syarat dan Ketentuan serta Kebijakan Privasi Buyee
        </div>
    </div>
</body>
</html>
