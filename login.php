<?php
session_start();
include 'superuser/includes/koneksi.php';

// Jika sudah login sebagai pemilih
if (isset($_SESSION['mata_pilih'])) {
    header("Location: index.php");
    exit;
}

// Jika sudah login sebagai admin
if (isset($_SESSION['username'])) {
    header("Location: superuser/index.php");
    exit;
}

$alert = '';
$activeLogin = 'pemilih';

// Proses login pemilih
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login_pemilih'])) {
    $activeLogin = 'pemilih';

    $nama_pemilih = mysqli_real_escape_string($conn, $_POST['nama_pemilih']);
    $nip = mysqli_real_escape_string($conn, $_POST['nip']);

    $query = "SELECT * FROM mata_pilih WHERE nama_pemilih='$nama_pemilih' AND nip='$nip'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $pemilih = mysqli_fetch_assoc($result);

        if ($pemilih['skor_mata'] == 0) {
            $_SESSION['mata_pilih'] = $pemilih;
            $_SESSION['id_matapilih'] = $pemilih['id_matapilih'];
            $_SESSION['nama_pemilih'] = $pemilih['nama_pemilih'];
            $_SESSION['kelas'] = $pemilih['kelas'];
            header("Location: index.php");
            exit;
        } else {
            $alert = '
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Perhatian!</strong> Anda sudah melakukan voting. Tidak dapat login kembali.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>';
        }
    } else {
        $alert = '
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-x-circle-fill me-2"></i>
            <strong>Gagal!</strong> Nama atau NIP yang Anda masukkan salah.
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>';
    }
}

// Proses login admin tetap menggunakan cek_login.php
if (isset($_GET['error'])) {
    $activeLogin = 'admin';
    $alert = '
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-x-circle-fill me-2"></i>
        <strong>Login Admin Gagal!</strong> ' . htmlspecialchars($_GET['error']) . '
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>';
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - E-Vote OSIS</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">

<style>
    * { box-sizing: border-box; }

    body {
        margin: 0;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        font-family: Arial, sans-serif;
        background:
            radial-gradient(circle at top left, rgba(13,110,253,.25), transparent 35%),
            radial-gradient(circle at bottom right, rgba(102,16,242,.25), transparent 35%),
            linear-gradient(135deg, #07111f, #101c31 55%, #16244a);
    }

    .login-wrapper {
        width: 100%;
        max-width: 430px;
        background: rgba(255,255,255,.97);
        border-radius: 24px;
        padding: 30px;
        box-shadow: 0 20px 60px rgba(0,0,0,.35);
        animation: fadeIn .55s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(18px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .brand {
        text-align: center;
        margin-bottom: 24px;
    }

    .brand-icon {
        width: 86px;
        height: 86px;
        margin: 0 auto 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .brand-icon img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        display: block;
    }

    .brand h3 {
        margin: 0;
        font-weight: 800;
        color: #14213d;
    }

    .brand p {
        margin: 5px 0 0;
        color: #7b8494;
        font-size: 14px;
    }

    .login-panel {
        display: none;
    }

    .login-panel.active {
        display: block;
        animation: panelIn .3s ease;
    }

    @keyframes panelIn {
        from { opacity: 0; transform: translateX(8px); }
        to { opacity: 1; transform: translateX(0); }
    }

    .panel-title {
        text-align: center;
        font-size: 20px;
        font-weight: 750;
        color: #17233c;
        margin-bottom: 20px;
    }

    .form-label {
        font-weight: 600;
        color: #354052;
        font-size: 14px;
    }

    .form-control {
        min-height: 46px;
        border-radius: 11px;
        border: 1px solid #dce2ea;
    }

    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 .2rem rgba(13,110,253,.12);
    }

    .btn-login {
        min-height: 47px;
        border: 0;
        border-radius: 11px;
        color: white;
        font-weight: 700;
        font-size: 15px;
        background: linear-gradient(135deg, #0d6efd, #4f46e5);
        box-shadow: 0 8px 18px rgba(13,110,253,.2);
    }

    .btn-login:hover {
        color: white;
        transform: translateY(-1px);
    }

    .switch-title {
        text-align: center;
        margin: 25px 0 11px;
        color: #8a93a3;
        font-size: 12px;
    }

    .switch-buttons {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .switch-btn {
        min-height: 44px;
        border-radius: 11px;
        border: 1px solid #dbe2eb;
        background: #f7f9fc;
        color: #475569;
        font-weight: 700;
        transition: .2s;
    }

    .switch-btn:hover {
        background: #eef3fb;
    }

    .switch-btn.active {
        background: #14213d;
        border-color: #14213d;
        color: white;
    }

    .input-group .form-control {
        border-right: 0;
        border-radius: 11px 0 0 11px;
    }

    .input-group-text {
        background: white;
        border: 1px solid #dce2ea;
        border-left: 0;
        border-radius: 0 11px 11px 0;
        cursor: pointer;
    }

    .footer {
        text-align: center;
        margin-top: 20px;
        color: #9aa2af;
        font-size: 12px;
    }

    @media (max-width: 480px) {
        .login-wrapper {
            padding: 23px 19px;
            border-radius: 19px;
        }
    }
</style>
</head>

<body>
<div class="login-wrapper">

    <div class="brand">
        <div class="brand-icon">
            <img src="images/logo_sekolah_login.png" alt="Logo SMK Negeri 1 Ambal">
        </div>
        <h3>E-Vote OSIS</h3>
        <p>SMK Negeri 1 Ambal</p>
    </div>

    <?= $alert; ?>

    <!-- LOGIN PEMILIH -->
    <div id="panelPemilih" class="login-panel <?= $activeLogin === 'pemilih' ? 'active' : ''; ?>">
        <div class="panel-title">
            <i class="bi bi-person-circle me-2"></i>Login Pemilih
        </div>

        <form method="POST" action="login.php">
            <input type="hidden" name="login_pemilih" value="1">

            <div class="mb-3">
                <label for="nama_pemilih" class="form-label">Nama Pemilih</label>
                <input type="text" class="form-control" id="nama_pemilih"
                       name="nama_pemilih" placeholder="Masukkan nama Anda" required>
            </div>

            <div class="mb-3">
                <label for="nip" class="form-label">NIP / Nomor Induk</label>
                <div class="input-group">
                    <input type="password" class="form-control" id="nip"
                           name="nip" placeholder="Masukkan NIP Anda" required>
                    <span class="input-group-text" id="toggleNip">
                        <i class="bi bi-eye" id="eyeIcon"></i>
                    </span>
                </div>
            </div>

            <button type="submit" class="btn btn-login w-100 mt-2">
                <i class="bi bi-box-arrow-in-right me-1"></i> Masuk sebagai Pemilih
            </button>
        </form>
    </div>

    <!-- LOGIN ADMIN -->
    <div id="panelAdmin" class="login-panel <?= $activeLogin === 'admin' ? 'active' : ''; ?>">
        <div class="panel-title">
            <i class="bi bi-person-lock me-2"></i>Login Admin
        </div>

        <form action="superuser/cek_login.php" method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" name="username" id="username"
                       class="form-control" placeholder="Masukkan username admin" required>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password"
                       class="form-control" placeholder="Masukkan password admin" required>
            </div>

            <button type="submit" class="btn btn-login w-100 mt-2">
                <i class="bi bi-shield-lock me-1"></i> Masuk sebagai Admin
            </button>
        </form>
    </div>

    <!-- DUA TOMBOL DI BAGIAN BAWAH -->
    <div class="switch-title">PILIH JENIS LOGIN</div>

    <div class="switch-buttons">
        <button type="button" id="btnPemilih"
                class="switch-btn <?= $activeLogin === 'pemilih' ? 'active' : ''; ?>"
                onclick="showLogin('pemilih')">
            <i class="bi bi-person me-1"></i> Login Pemilih
        </button>

        <button type="button" id="btnAdmin"
                class="switch-btn <?= $activeLogin === 'admin' ? 'active' : ''; ?>"
                onclick="showLogin('admin')">
            <i class="bi bi-person-lock me-1"></i> Login Admin
        </button>
    </div>

    <div class="footer">
        E-Vote OSIS © <?= date('Y'); ?> · SMK Negeri 1 Ambal
    </div>
</div>

<script>
function showLogin(type) {
    const pemilih = document.getElementById('panelPemilih');
    const admin = document.getElementById('panelAdmin');
    const btnPemilih = document.getElementById('btnPemilih');
    const btnAdmin = document.getElementById('btnAdmin');

    if (type === 'admin') {
        pemilih.classList.remove('active');
        admin.classList.add('active');
        btnPemilih.classList.remove('active');
        btnAdmin.classList.add('active');
    } else {
        admin.classList.remove('active');
        pemilih.classList.add('active');
        btnAdmin.classList.remove('active');
        btnPemilih.classList.add('active');
    }
}

const toggleNip = document.getElementById("toggleNip");
const nipInput = document.getElementById("nip");
const eyeIcon = document.getElementById("eyeIcon");

if (toggleNip) {
    toggleNip.addEventListener("click", () => {
        const type = nipInput.getAttribute("type") === "password" ? "text" : "password";
        nipInput.setAttribute("type", type);
        eyeIcon.classList.toggle("bi-eye");
        eyeIcon.classList.toggle("bi-eye-slash");
    });
}
</script>
</body>
</html>
