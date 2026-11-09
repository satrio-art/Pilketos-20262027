<?php
include 'superuser/includes/koneksi.php';

$total = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM mata_pilih"))['total'];
$sudah = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS sudah FROM mata_pilih WHERE skor_mata = 1"))['sudah'];
$belum = $total - $sudah;

$persen_sudah = $total > 0 ? round(($sudah / $total) * 100, 2) : 0;
$persen_belum = 100 - $persen_sudah;

echo json_encode([
  'total' => $total,
  'sudah' => $sudah,
  'belum' => $belum,
  'persen_sudah' => $persen_sudah,
  'persen_belum' => $persen_belum
]);
?>
