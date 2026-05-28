<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pilih Login - MyRepublic</title>

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
    margin-bottom: 28px;
    text-align: center;
}

.card p {
    color: #556b86;
    text-align: center;
    font-size: 14px;
    margin-bottom: 28px;
}

.role-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 12px;
}

.role-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 14px 20px;
    border: 1.5px solid #dce5f0;
    border-radius: 12px;
    background: white;
    color: #334758;
    font-family: 'Inter', sans-serif;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s ease;
}

.role-btn:hover {
    border-color: #1c92d2;
    background: rgba(28, 146, 210, 0.08);
    color: #1c92d2;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(28, 146, 210, 0.15);
}

.role-btn:active {
    transform: translateY(0);
}

.role-btn i {
    font-size: 18px;
}

.divider {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 28px 0;
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

.register-notice {
    background: #e7f5ff;
    border: 1px solid #74c0fc;
    border-radius: 12px;
    padding: 14px 16px;
    color: #1971c2;
    text-align: center;
    font-size: 14px;
}

.register-notice strong {
    display: block;
    margin-bottom: 6px;
    font-weight: 700;
}

.register-notice a {
    color: #1971c2;
    text-decoration: none;
    font-weight: 700;
    margin-top: 8px;
    display: inline-block;
}

.register-notice a:hover {
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

    .card p {
        margin-bottom: 24px;
        font-size: 13px;
    }

    .role-btn {
        padding: 12px 16px;
        font-size: 14px;
    }
}
</style>
</head>

<body>

<div class="container">
    <div class="card">
        <h2>Pilih Jenis Login</h2>
        <p>Silakan pilih role Anda untuk melanjutkan</p>

        <div class="role-grid">
            <button class="role-btn" onclick="window.location='login.php?role=pelanggan'">
                <i class="fas fa-user"></i>
                Pelanggan
            </button>

            <button class="role-btn" onclick="window.location='login.php?role=cs'">
                <i class="fas fa-headset"></i>
                Customer Service
            </button>

            <button class="role-btn" onclick="window.location='login.php?role=teknisi'">
                <i class="fas fa-tools"></i>
                Teknisi
            </button>
        </div>

        <div class="divider">
            <span>Baru di MyRepublic?</span>
        </div>

        <div class="register-notice">
            <strong>Belum memiliki akun?</strong>
            <a href="register.php">
                <i class="fas fa-user-plus" style="margin-right: 6px;"></i> Register Sekarang
            </a>
        </div>
    </div>
</div>

</body>
</html>