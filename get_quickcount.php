<?php
include 'superuser/includes/koneksi.php';
header('Content-Type: application/json');

$query = "SELECT * FROM paslon";
$result = mysqli_query($conn, $query);

$data = [];
$total = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $total += (int)$row['skor'];
    $data[] = $row;
}

// Hindari pembagian 0
if ($total == 0) $total = 1;

foreach ($data as &$paslon) {
    $persen = ($paslon['skor'] / $total) * 100;
    $paslon['persentase'] = number_format($persen, 2, '.', ''); // 2 angka di belakang koma
}

echo json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
