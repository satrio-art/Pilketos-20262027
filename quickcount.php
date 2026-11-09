<?php
include 'superuser/includes/koneksi.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Quick Count - E-Vote OSIS</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body {
      background: #f8f9fa;
      font-family: 'Poppins', sans-serif;
      overflow-x: hidden;
      padding-bottom: 50px;
    }

    h3 {
      font-weight: 800;
      font-size: 3rem;
      color: #1a1a1a;
    }

    .card-paslon {
      display: flex;
      align-items: center;
      justify-content: space-between;
      border: none;
      border-radius: 25px;
      background: white;
      box-shadow: 0 6px 25px rgba(0,0,0,0.1);
      padding: 35px 50px;
      transition: 0.3s ease;
    }

    .card-paslon:hover {
      transform: scale(1.02);
      box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }

    .foto-paslon {
      width: 220px;
      height: 220px;
      object-fit: cover;
      border-radius: 15px;
      flex-shrink: 0;
    }

    .info {
      flex-grow: 1;
      margin-left: 40px;
    }

    .nama {
      font-weight: 700;
      font-size: 2.3rem;
      margin-bottom: 10px;
      color: #212529;
    }

    .motto {
      font-size: 1.4rem;
      color: #6c757d;
      margin-bottom: 15px;
    }

    .score {
      font-size: 1.5rem;
      color: #343a40;
      font-weight: 600;
    }

    .chart-container {
      width: 200px;
      height: 200px;
      position: relative;
    }

    .percentage {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      font-weight: 800;
      font-size: 2rem;
      color: #0d6efd;
    }

    .statistik-box {
      background: white;
      border-radius: 25px;
      padding: 40px;
      box-shadow: 0 6px 25px rgba(0,0,0,0.08);
      margin-top: 50px;
    }

    .progress {
      height: 45px;
      border-radius: 15px;
      overflow: hidden;
    }

    .progress-bar {
      font-size: 1.3rem;
      font-weight: 700;
    }

    .footer-info {
      font-size: 1.2rem;
      color: #6c757d;
    }

    @media (max-width: 768px) {
      h3 {
        font-size: 1.5rem;
      }

      .card-paslon {
        flex-direction: column;
        text-align: center;
        padding: 25px;
      }

      .foto-paslon {
        width: 120px;
        height: 120px;
        margin-bottom: 15px;
      }

      .info {
        margin-left: 0;
      }

      .nama {
        font-size: 1.3rem;
      }

      .motto {
        font-size: 1rem;
      }

      .score {
        font-size: 1.1rem;
      }

      .chart-container {
        margin-top: 12px;
        width: 120px;
        height: 120px;
      }

      .percentage {
        font-size: 1rem;
      }

      .progress {
        height: 25px;
      }

      .progress-bar {
        font-size: 0.8rem;
      }

      .footer-info {
        font-size: 0.8rem;
      }
    }
  </style>
</head>
<body>

<div class="container py-4" style="max-width: 1500px;">
  <h3 class="text-center fw-bold mb-5">
    <i class="bi bi-bar-chart-line me-3 text-primary"></i>Quick Count E-Vote OSIS
  </h3>

  <!-- Daftar Paslon -->
  <div id="paslon-container" class="d-flex flex-column gap-4">
    <!-- Data paslon via AJAX -->
  </div>

  <!-- Statistik Pemilih -->
  <div id="statistik-container" class="statistik-box text-center mt-5">
    <h4 class="fw-bold mb-4">
      <i class="bi bi-clipboard-data text-primary me-2"></i>Statistik Partisipasi Pemilih
    </h4>
    <div class="progress mb-3">
      <div id="progress-sudah" class="progress-bar bg-success" style="width: 0%">0%</div>
      <div id="progress-belum" class="progress-bar bg-danger" style="width: 0%">0%</div>
    </div>
    <div class="d-flex justify-content-between text-muted fw-bold mt-2">
      <span id="text-sudah">Sudah memilih: 0%</span>
      <span id="text-belum">Belum memilih: 0%</span>
    </div>
  </div>

  <p class="text-center footer-info mt-4">
    Update otomatis setiap 3 menit <i class="bi bi-arrow-repeat text-primary"></i>
  </p>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
function loadQuickCount() {
  $.ajax({
    url: "get_quickcount.php",
    method: "GET",
    dataType: "json",
    success: function(data) {
      const container = $("#paslon-container");
      container.empty();

      if (data.length === 0) {
        container.html('<p class="text-center text-danger fs-3">Belum ada data paslon.</p>');
        return;
      }

      data.forEach((item, index) => {
        const card = `
          <div class="card-paslon">
            <img src="superuser/foto/${item.foto}" class="foto-paslon" alt="${item.nama_ketua}">
            <div class="info">
              <div class="nama">${item.nama_ketua}${item.nama_wakil ? ' & ' + item.nama_wakil : ''}</div>
              <div class="motto">"${item.motto}"</div>
              <div class="score">${item.skor} Suara</div>
            </div>
            <div class="chart-container">
              <canvas id="chart-${index}"></canvas>
              <div class="percentage">${item.persentase}%</div>
            </div>
          </div>
        `;
        container.append(card);

        const ctx = document.getElementById(`chart-${index}`);
        new Chart(ctx, {
          type: 'doughnut',
          data: {
            datasets: [{
              data: [item.persentase, 100 - item.persentase],
              backgroundColor: ['#0d6efd', '#e9ecef'],
              borderWidth: 0
            }]
          },
          options: {
            cutout: '78%',
            plugins: { legend: { display: false } },
            animation: { duration: 1200, easing: 'easeOutCubic' }
          }
        });
      });
    }
  });
}

function loadStatistik() {
  $.ajax({
    url: "get_statistik.php",
    method: "GET",
    dataType: "json",
    success: function(data) {
      const { sudah, belum, persen_sudah, persen_belum } = data;
      $("#progress-sudah").css("width", persen_sudah + "%").text(persen_sudah + "%");
      $("#progress-belum").css("width", persen_belum + "%").text(persen_belum + "%");
      $("#text-sudah").text(`Sudah memilih: ${sudah} (${persen_sudah}%)`);
      $("#text-belum").text(`Belum memilih: ${belum} (${persen_belum}%)`);
    }
  });
}

$(document).ready(function() {
  loadQuickCount();
  loadStatistik();
  setInterval(() => {
    loadQuickCount();
    loadStatistik();
  }, 180000);
});
</script>

</body>
</html>
