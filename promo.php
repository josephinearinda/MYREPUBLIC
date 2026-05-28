<?php
session_start();
include 'koneksi.php';

if(!isset($_SESSION['user']) || $_SESSION['user']['role']!="pelanggan"){
    header("Location: pilih_role.php");
    exit;
}

$id_user = $_SESSION['user']['id'];

// Get user data
$user_query = mysqli_query($conn, "SELECT * FROM users WHERE id='$id_user'");
$user = mysqli_fetch_assoc($user_query);

// Get user statistics
$transaksi_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM transaksi WHERE user_id='$id_user'");
$transaksi_count = mysqli_fetch_assoc($transaksi_query)['total'];

$aktif_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM transaksi WHERE user_id='$id_user' AND status IN ('Lunas', 'Selesai')");
$aktif_count = mysqli_fetch_assoc($aktif_query)['total'];

$notif_query = mysqli_query($conn, "SELECT COUNT(*) as total FROM notifikasi WHERE user_id='$id_user' AND status='Belum dibaca'");
$unread_count = mysqli_fetch_assoc($notif_query)['total'];

if(isset($_POST['update'])){
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    mysqli_query($conn, "UPDATE users SET nama='$nama', email='$email' WHERE id='$id_user'");

    // Update session
    $_SESSION['user']['nama'] = $nama;
    $_SESSION['user']['email'] = $email;

    $_SESSION['msg'] = "✅ Profil berhasil diperbarui!";
    header("Location: profil.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Profil - MyRepublic</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<style>
*{box-sizing:border-box;}

body{
    margin:0;
    font-family:'Inter',sans-serif;
    background:linear-gradient(135deg, #0f2027 0%, #1c92d2 100%);
    min-height:100vh;
    overflow-x:hidden;
}

body::before{
    content:'';
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:
        radial-gradient(circle at 20% 80%, rgba(28, 146, 210, 0.25) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(113, 204, 254, 0.2) 0%, transparent 50%),
        radial-gradient(circle at 40% 40%, rgba(45, 114, 255, 0.15) 0%, transparent 50%);
    z-index:-1;
}

.navbar{
    background:linear-gradient(135deg,#0f2027,#1c92d2,#2c5364);
    padding:20px;
    color:white;
    border-bottom-left-radius:25px;
    border-bottom-right-radius:25px;
    position:relative;
    z-index:10;
}

.nav-top{
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.nav-menu{
    display:flex;
    flex-wrap:wrap;
    gap:12px;
    margin-top:15px;
    font-size:14px;
}

.nav-menu a{
    display:flex;
    align-items:center;
    gap:8px;
    padding:10px 14px;
    border-radius:14px;
    color:white;
    text-decoration:none;
    font-weight:600;
    transition:background 0.2s ease, transform 0.2s ease;
}

.nav-menu a span,
.nav-menu a i{
    display:inline-flex;
    width:20px;
    justify-content:center;
}

.nav-menu a:hover{
    background:rgba(255,255,255,0.12);
    color:white;
    transform:translateY(-2px);
}

.nav-menu a:focus,
.nav-menu a:active,
.nav-menu a:visited{
    color:white;
    background:transparent;
    outline:none;
}

.nav-menu a.active{
    background:rgba(255,255,255,0.16);
}

.container{
    padding:20px;
    max-width:1200px;
    margin:0 auto;
}

/* PROFILE HEADER */
.profile-header{
    background:white;
    border-radius:25px;
    padding:40px 30px;
    margin-bottom:30px;
    box-shadow:0 20px 40px rgba(0,0,0,0.1);
    text-align:center;
    position:relative;
    overflow:hidden;
}

.profile-header::before{
    content:'';
    position:absolute;
    top:0;
    left:0;
    right:0;
    bottom:0;
    background:linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    opacity:0.05;
    z-index:1;
}

.profile-header > *{
    position:relative;
    z-index:2;
}

.avatar{
    width:120px;
    height:120px;
    border-radius:50%;
    background:linear-gradient(135deg, #1c92d2 0%, #0f2027 100%);
    display:flex;
    align-items:center;
    justify-content:center;
    margin:0 auto 20px;
    font-size:50px;
    color:white;
    box-shadow:0 10px 30px rgba(0,0,0,0.2);
    border:5px solid white;
}

.user-name{
    font-size:28px;
    font-weight:700;
    color:#0f2027;
    margin-bottom:5px;
}

.user-email{
    color:#666;
    font-size:16px;
    margin-bottom:20px;
}

.user-role{
    display:inline-block;
    background:linear-gradient(135deg, #1c92d2 0%, #0f2027 100%);
    color:white;
    padding:8px 20px;
    border-radius:25px;
    font-size:14px;
    font-weight:600;
}

/* STATS CARDS */
.stats-grid{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
    gap:20px;
    margin-bottom:30px;
}

.stat-card{
    background:white;
    border-radius:20px;
    padding:25px;
    text-align:center;
    box-shadow:0 10px 30px rgba(0,0,0,0.1);
    transition:0.3s;
    position:relative;
    overflow:hidden;
}

.stat-card::before{
    content:'';
    position:absolute;
    top:0;
    left:0;
    right:0;
    bottom:0;
    background:linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    opacity:0;
    transition:0.3s;
}

.stat-card:hover{
    transform:translateY(-5px);
    box-shadow:0 20px 40px rgba(0,0,0,0.15);
}

.stat-card:hover::before{
    opacity:1;
}

.stat-icon{
    font-size:40px;
    margin-bottom:15px;
    display:block;
}

.stat-card:nth-child(1) .stat-icon{ color:#667eea; }
.stat-card:nth-child(2) .stat-icon{ color:#28a745; }
.stat-card:nth-child(3) .stat-icon{ color:#ffc107; }

.stat-value{
    font-size:32px;
    font-weight:700;
    color:#0f2027;
    margin-bottom:5px;
}

.stat-label{
    color:#666;
    font-size:14px;
    font-weight:600;
}

/* MAIN CONTENT */
.content-grid{
    display:grid;
    grid-template-columns:2fr 1fr;
    gap:30px;
}

@media(max-width:768px){
    .content-grid{
        grid-template-columns:1fr;
    }
}

/* EDIT PROFILE CARD */
.edit-card{
    background:white;
    border-radius:25px;
    padding:30px;
    box-shadow:0 10px 30px rgba(0,0,0,0.1);
}

.edit-card h3{
    margin-top:0;
    color:#0f2027;
    font-size:24px;
    margin-bottom:25px;
    display:flex;
    align-items:center;
    gap:10px;
}

.edit-card h3 i{
    color:#667eea;
}

.form-group{
    margin-bottom:20px;
}

.form-group label{
    display:block;
    margin-bottom:8px;
    font-weight:600;
    color:#0f2027;
    font-size:14px;
}

.form-group input{
    width:100%;
    padding:15px 20px;
    border:2px solid #e1e5e9;
    border-radius:15px;
    font-size:16px;
    transition:0.3s;
    background:#f8f9fa;
}

.form-group input:focus{
    outline:none;
    border-color:#667eea;
    background:white;
    box-shadow:0 0 0 3px rgba(102, 126, 234, 0.1);
}

.btn-primary{
    background:linear-gradient(135deg, #1c92d2 0%, #0f2027 100%);
    color:white;
    border:none;
    padding:15px 30px;
    border-radius:15px;
    font-size:16px;
    font-weight:600;
    cursor:pointer;
    transition:0.3s;
    width:100%;
    margin-top:10px;
}

.btn-primary:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 25px rgba(28, 146, 210, 0.35);
}

/* INFO CARD */
.info-card{
    background:white;
    border-radius:25px;
    padding:30px;
    box-shadow:0 10px 30px rgba(0,0,0,0.1);
}

.info-card h3{
    margin-top:0;
    color:#0f2027;
    font-size:20px;
    margin-bottom:20px;
    display:flex;
    align-items:center;
    gap:10px;
}

.info-card h3 i{
    color:#28a745;
}

.info-item{
    display:flex;
    justify-content:space-between;
    align-items:center;
    padding:15px 0;
    border-bottom:1px solid #f0f0f0;
}

.info-item:last-child{
    border-bottom:none;
}

.info-label{
    font-weight:600;
    color:#666;
    display:flex;
    align-items:center;
    gap:8px;
}

.info-value{
    color:#0f2027;
    font-weight:500;
}

/* ALERT */
.alert{
    background:linear-gradient(135deg, #28a745 0%, #20c997 100%);
    color:white;
    border:none;
    padding:15px 20px;
    border-radius:15px;
    margin-bottom:20px;
    display:flex;
    align-items:center;
    gap:10px;
    font-weight:600;
    box-shadow:0 5px 15px rgba(40, 167, 69, 0.2);
}

.alert i{
    font-size:20px;
}

/* ANIMATIONS */
@keyframes fadeInUp{
    from{
        opacity:0;
        transform:translateY(30px);
    }
    to{
        opacity:1;
        transform:translateY(0);
    }
}

.profile-header, .stat-card, .edit-card, .info-card{
    animation:fadeInUp 0.6s ease-out;
}

.stat-card:nth-child(1){ animation-delay:0.1s; }
.stat-card:nth-child(2){ animation-delay:0.2s; }
.stat-card:nth-child(3){ animation-delay:0.3s; }

.edit-card{ animation-delay:0.4s; }
.info-card{ animation-delay:0.5s; }

</style>
</head>

<body>

<div class="navbar">
    <div class="nav-top">
        <div>
            <h2><i class="fas fa-user-circle"></i> Profil</h2>
            <small>Halo, <?= $_SESSION['user']['nama']; ?>!</small>
        </div>
        <div>
            <a href="logout.php" style="color:white;text-decoration:none;"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>
    <div class="nav-menu">
        <a href="home_pelanggan.php"><i class="fas fa-home"></i> Home</a>
        <a href="dashboard_pelanggan.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
        <a href="status_layanan.php"><i class="fas fa-info-circle"></i> Status Layanan</a>
        <a href="riwayat.php"><i class="fas fa-history"></i> Riwayat</a>
        <a href="profil.php" class="active"><i class="fas fa-user"></i> Profil</a>
    </div>
</div>

<div class="container">

<?php if(isset($_SESSION['msg'])): ?>
<div class="alert">
    <i class="fas fa-check-circle"></i>
    <?= $_SESSION['msg']; ?>
</div>
<?php unset($_SESSION['msg']); endif; ?>

<!-- PROFILE HEADER -->
<div class="profile-header">
    <div class="avatar">
        <i class="fas fa-user"></i>
    </div>
    <h1 class="user-name"><?= htmlspecialchars($user['nama']); ?></h1>
    <p class="user-email"><?= htmlspecialchars($user['email']); ?></p>
    <span class="user-role"><i class="fas fa-crown"></i> Pelanggan Premium</span>
</div>

<!-- STATS -->
<div class="stats-grid">
    <div class="stat-card">
        <i class="fas fa-shopping-cart stat-icon"></i>
        <div class="stat-value"><?= $transaksi_count; ?></div>
        <div class="stat-label">Total Transaksi</div>
    </div>

    <div class="stat-card">
        <i class="fas fa-check-circle stat-icon"></i>
        <div class="stat-value"><?= $aktif_count; ?></div>
        <div class="stat-label">Layanan Aktif</div>
    </div>

    <div class="stat-card">
        <i class="fas fa-bell stat-icon"></i>
        <div class="stat-value"><?= $unread_count; ?></div>
        <div class="stat-label">Notifikasi Baru</div>
    </div>
</div>

<!-- MAIN CONTENT -->
<div class="content-grid">

    <!-- EDIT PROFILE -->
    <div class="edit-card">
        <h3><i class="fas fa-edit"></i> Edit Profil</h3>

        <form method="post">
            <div class="form-group">
                <label><i class="fas fa-user"></i> Nama Lengkap</label>
                <input type="text" name="nama" value="<?= htmlspecialchars($user['nama']); ?>" required placeholder="Masukkan nama lengkap">
            </div>

            <div class="form-group">
                <label><i class="fas fa-envelope"></i> Email</label>
                <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required placeholder="Masukkan alamat email">
            </div>

            <button type="submit" name="update" class="btn-primary">
                <i class="fas fa-save"></i> Simpan Perubahan
            </button>
        </form>
    </div>

    <!-- INFO SUMMARY -->
    <div class="info-card">
        <h3><i class="fas fa-info-circle"></i> Informasi Akun</h3>

        <div class="info-item">
            <span class="info-label">
                <i class="fas fa-id-card"></i> ID User
            </span>
            <span class="info-value">#<?= $user['id']; ?></span>
        </div>

        <div class="info-item">
            <span class="info-label">
                <i class="fas fa-user-tag"></i> Role
            </span>
            <span class="info-value">Pelanggan</span>
        </div>

        <div class="info-item">
            <span class="info-label">
                <i class="fas fa-calendar-alt"></i> Bergabung
            </span>
            <span class="info-value">Januari 2024</span>
        </div>

        <div class="info-item">
            <span class="info-label">
                <i class="fas fa-shield-alt"></i> Status Akun
            </span>
            <span class="info-value" style="color:#28a745;font-weight:700;">Aktif</span>
        </div>
    </div>

</div>

</div>

<script>
// Add active class to current nav item
document.querySelectorAll('.nav-menu a').forEach(link => {
    if(link.href.includes('profil.php')) {
        link.classList.add('active');
    }
});

// Smooth animations
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.stat-card, .edit-card, .info-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(30px)';

        setTimeout(() => {
            card.style.transition = 'all 0.6s ease-out';
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 100);
    });
});
</script>

</body>
</html>