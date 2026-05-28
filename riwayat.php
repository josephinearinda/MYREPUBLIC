<?php
include 'koneksi.php';

if(isset($_POST['register'])){

$nama=$_POST['nama'];
$email=$_POST['email'];
$password=password_hash($_POST['password'],PASSWORD_DEFAULT);

mysqli_query($conn,"INSERT INTO users(nama,email,password,role)
VALUES('$nama','$email','$password','pelanggan')");

header("Location: login.php?role=pelanggan");
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register - MyRepublic</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: 'Inter', sans-serif;
    background: linear-gradient(135deg, #0f2027 0%, #1c92d2 45%, #2c5364 100%);
    position: relative;
    overflow: hidden;
}

body::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background:
        radial-gradient(circle at 15% 20%, rgba(255,255,255,0.18), transparent 20%),
        radial-gradient(circle at 85% 10%, rgba(255,255,255,0.12), transparent 25%);
    pointer-events: none;
}

.container {
    position: relative;
    z-index: 1;
    width: 100%;
    max-width: 440px;
    padding: 24px;
}

.card {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    padding: 36px;
    border-radius: 28px;
    box-shadow: 0 30px 80px rgba(0, 0, 0, 0.14);
    border: 1px solid rgba(255, 255, 255, 0.32);
}

.card h2 {
    color: #0f2027;
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 24px;
    text-align: center;
}

.back-link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #1c92d2;
    text-decoration: none;
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 20px;
    transition: all 0.3s ease;
}

.back-link:hover {
    gap: 10px;
    color: #0f456c;
}

.error-msg {
    background: #ffe6e6;
    border: 1px solid #ff6b6b;
    color: #c92a2a;
    padding: 12px 16px;
    border-radius: 12px;
    margin-bottom: 20px;
    font-size: 14px;
    text-align: center;
}

.success-msg {
    background: #e7f5ff;
    border: 1px solid #74c0fc;
    color: #1971c2;
    padding: 12px 16px;
    border-radius: 12px;
    margin-bottom: 20px;
    font-size: 14px;
    text-align: center;
}

.form-group {
    margin-bottom: 16px;
}

.form-group label {
    display: block;
    color: #334758;
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 8px;
}

.form-group input {
    width: 100%;
    padding: 12px 16px;
    border: 1.5px solid #dce5f0;
    border-radius: 12px;
    font-family: 'Inter', sans-serif;
    font-size: 14px;
    color: #334758;
    transition: all 0.3s ease;
    background: white;
}

.form-group input::placeholder {
    color: #a0afc0;
}

.form-group input:focus {
    outline: none;
    border-color: #1c92d2;
    box-shadow: 0 0 0 4px rgba(28, 146, 210, 0.1);
}

.btn-register {
    width: 100%;
    padding: 13px 16px;
    background: linear-gradient(135deg, #1c92d2, #0f456c);
    color: white;
    border: none;
    border-radius: 12px;
    font-family: 'Inter', sans-serif;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
    margin-top: 8px;
    box-shadow: 0 8px 20px rgba(28, 146, 210, 0.3);
}

.btn-register:hover {
    background: linear-gradient(135deg, #0f456c, #1c92d2);
    transform: translateY(-2px);
    box-shadow: 0 12px 28px rgba(28, 146, 210, 0.4);
}

.btn-register:active {
    transform: translateY(0);
}

.divider {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 24px 0;
}

.divider::before,
.divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: #dce5f0;
}

.divider span {
    color: #a0afc0;
    font-size: 13px;
    font-weight: 600;
}

.login-link {
    text-align: center;
    color: #556b86;
    font-size: 14px;
}

.login-link a {
    color: #1c92d2;
    text-decoration: none;
    font-weight: 700;
    transition: color 0.3s ease;
}

.login-link a:hover {
    color: #0f456c;
    text-decoration: underline;
}

@media (max-width: 480px) {
    .container {
        max-width: 100%;
    }

    .card {
        padding: 28px 24px;
        border-radius: 24px;
    }

    .card h2 {
        font-size: 24px;
        margin-bottom: 20px;
    }

    .form-group input {
        padding: 11px 14px;
        font-size: 16px;
    }

    .btn-register {
        padding: 12px 16px;
        font-size: 14px;
    }
}
</style>

</head>

<body>

<div class="container">
    <div class="card">
        <h2>REGISTER</h2>
        
        <a href="login.php?role=pelanggan" class="back-link">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>

        <form method="POST">
            <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" id="nama" name="nama" placeholder="Masukkan nama lengkap Anda" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Masukkan email Anda" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Masukkan password" required>
            </div>

            <button type="submit" name="register" class="btn-register">
                <i class="fas fa-user-plus" style="margin-right: 8px;"></i> Buat Akun
            </button>
        </form>

        <div class="divider">
            <span>Sudah punya akun?</span>
        </div>
        <div class="login-link">
            <a href="login.php?role=pelanggan">
                <i class="fas fa-sign-in-alt" style="margin-right: 6px;"></i> Login Sekarang
            </a>
        </div>
    </div>
</div>

</body>
</html>