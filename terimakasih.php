<?php
session_start();

// Hapus semua data session pemilih
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Terima Kasih - E-Vote OSIS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(135deg, #007bff, #00c6ff);
      color: white;
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      text-align: center;
    }
    .card {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(10px);
      border: none;
      color: white;
      box-shadow: 0 0 20px rgba(0,0,0,0.2);
      border-radius: 20px;
      padding: 2rem;
      max-width: 450px;
    }
    .icon-check {
      font-size: 70px;
      color: #00ff99;
      margin-bottom: 1rem;
      animation: pop 0.5s ease;
    }
    @keyframes pop {
      0% { transform: scale(0); opacity: 0; }
      100% { transform: scale(1); opacity: 1; }
    }
  </style>
</head>
<body>
  <div class="card text-center">
    <i class="bi bi-check-circle-fill icon-check"></i>
    <h2 class="fw-bold">Terima Kasih!</h2>
    <p class="lead">Suara Anda telah berhasil direkam dalam sistem E-Vote OSIS 🎉</p>
    <p>Anda akan dialihkan ke halaman login dalam <span id="countdown">4</span> detik...</p>
  </div>

  <script>
    let seconds = 4;
    const countdownEl = document.getElementById('countdown');

    const timer = setInterval(() => {
      seconds--;
      countdownEl.textContent = seconds;
      if (seconds <= 0) {
        clearInterval(timer);
        window.location.href = "login.php";
      }
    }, 1000);
  </script>
</body>
</html>
