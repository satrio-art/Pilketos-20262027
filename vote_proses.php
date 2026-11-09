<?php
session_start();
include 'superuser/includes/koneksi.php';


if (!isset($_SESSION['mata_pilih'])) {
    http_response_code(403);
    exit;
}

$id_matapilih = $_SESSION['id_matapilih'];
$id_paslon = intval($_POST['id_paslon'] ?? 0);

if ($id_paslon > 0) {
    // Insert ke tabel pilihan
    $query = "INSERT INTO pilihan (id_matapilih, id_paslon, skor_pilih) VALUES ('$id_matapilih', '$id_paslon', 1)";
    mysqli_query($conn, $query);

    // Update skor_mata jadi 1 (sudah memilih)
    mysqli_query($conn, "UPDATE mata_pilih SET skor_mata = 1 WHERE id_matapilih = '$id_matapilih'");

    // Hapus session biar gak bisa vote dua kali
    session_destroy();
    echo "success";
} else {
    http_response_code(400);
}
?>
