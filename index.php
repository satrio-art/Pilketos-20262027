<?php
session_start();
include 'superuser/includes/koneksi.php';

// Cek login
if (!isset($_SESSION['mata_pilih'])) {
    header("Location: login.php");
    exit;
}

$id_matapilih = $_SESSION['id_matapilih'];
$nama_pemilih = $_SESSION['nama_pemilih'];
$kelas = $_SESSION['kelas'];

// Ambil data paslon
$query = "SELECT * FROM paslon ORDER BY id_paslon ASC";
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Vote Sekarang - E-Vote OSIS</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
body {
  background: linear-gradient(135deg, #f8f9fc, #e9eefc);
  font-family: "Poppins", sans-serif;
  min-height: 100vh;
}

.navbar {
  background: #0d6efd;
  box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

.navbar-brand img {
  height: 42px;
}

.navbar-title {
  color: white;
  font-weight: 600;
  font-size: 1.2rem;
}

.logout-icon {
  color: white;
  font-size: 1.5rem;
  cursor: pointer;
  transition: 0.3s;
}
.logout-icon:hover {
  color: #ffc107;
}

.nama-pemilih {
  text-align: center;
  background: #f8f9fa;
  padding: 8px 0;
  font-weight: 500;
  border-bottom: 1px solid #dee2e6;
}

.foto-paslon {
  width: 100%;
  height: 220px;
  object-fit: cover;
  border-radius: 15px;
}
.card-paslon {
  border-radius: 20px;
  overflow: hidden;
  transition: transform 0.25s, box-shadow 0.25s;
}
.card-paslon:hover {
  transform: translateY(-5px);
  box-shadow: 0 6px 15px rgba(0,0,0,0.2);
}
.btn-pilih {
  font-size: 16px;
  font-weight: 600;
  border-radius: 10px;
  padding: 10px 0;
}
h3 {
  font-weight: 700;
}

@media (max-width: 768px) {
  .foto-paslon {
    height: 180px;
  }
  .btn-pilih {
    font-size: 15px;
    padding: 8px 0;
  }
  h3 {
    font-size: 1.3rem;
  }
  .col-md-4 {
    width: 100%;
  }
}
</style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
  <div class="container d-flex justify-content-between align-items-center">
    <!-- Logo kiri -->
    <a class="navbar-brand" href="#">
      <img src="images/log.png" alt="Logo OSIS">
    </a>

    <!-- Judul Tengah -->
    <div class="navbar-title text-center mx-auto">
      E-Vote OSIS
    </div>

    <!-- Icon Logout Kanan -->
    <div class="logout-icon" onclick="logout()">
      <i class="bi bi-box-arrow-right"></i>
    </div>
  </div>
</nav>

<!-- Nama Pemilih -->
<div class="nama-pemilih">
  👋 <?= htmlspecialchars($nama_pemilih); ?> (<?= htmlspecialchars($kelas); ?>)
</div>

<!-- Konten utama -->
<div class="container py-4">
  <h3 class="text-center text-primary mb-4">Pilih Ketua & Wakil OSIS</h3>

  <div class="row g-4 justify-content-center">
    <?php while ($row = mysqli_fetch_assoc($result)): ?>
      <div class="col-md-4 col-sm-6 col-12">
        <div class="card card-paslon shadow-sm border-0 p-3">
          <img src="superuser/foto/<?= $row['foto'] ?: 'default.jpg'; ?>" class="foto-paslon mb-3" alt="Foto Paslon">
          <div class="card-body text-center">
            <h5 class="fw-bold mb-1"><?= htmlspecialchars($row['nama_ketua']); ?></h5>
            <?php if (!empty($row['nama_wakil'])): ?>
              <p class="text-muted small mb-1">Wakil: <?= htmlspecialchars($row['nama_wakil']); ?></p>
            <?php endif; ?>
            <p class="fst-italic text-secondary small">“<?= htmlspecialchars($row['motto']); ?>”</p>
            <button class="btn btn-success w-100 btn-pilih pilih-btn mt-2" data-id="<?= $row['id_paslon']; ?>">
              <i class="bi bi-check-circle me-1"></i> Pilih
            </button>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>

<!-- Modal konfirmasi -->
<div class="modal fade" id="konfirmasiModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title"><i class="bi bi-patch-check"></i> Konfirmasi Pilihan</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body text-center p-4">
        <p class="mb-0 fs-6">Apakah Anda yakin ingin memilih pasangan ini?</p>
        <p class="text-muted small mt-1">Pilihan tidak dapat diubah setelah disimpan.</p>
      </div>
      <div class="modal-footer justify-content-center">
        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Batal</button>
        <button type="button" id="konfirmasiPilih" class="btn btn-success px-4">Ya, Pilih</button>
      </div>
    </div>
  </div>
</div>

<!-- Script -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
let idPaslonTerpilih = null;

$(".pilih-btn").on("click", function() {
  idPaslonTerpilih = $(this).data("id");
  const modal = new bootstrap.Modal(document.getElementById('konfirmasiModal'));
  modal.show();
});

$("#konfirmasiPilih").on("click", function() {
  if (idPaslonTerpilih) {
    $.ajax({
      url: "vote_proses.php",
      method: "POST",
      data: { id_paslon: idPaslonTerpilih },
      success: function() {
        window.location.href = "terimakasih.php";
      },
      error: function() {
        alert("Terjadi kesalahan, coba lagi.");
      }
    });
  }
});

function logout() {
  if (confirm("Apakah Anda yakin ingin logout?")) {
    window.location.href = "logout.php";
  }
}
</script>

</body>
</html>
