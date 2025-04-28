<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
include '../koneksi.php';

$id_peserta = $_GET['id'];

$query = "DELETE FROM peserta WHERE id_peserta=?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $id_peserta);

if ($stmt->execute()) {
  $_SESSION['alert'] = [
    'type' => 'success',
    'message' => 'Data peserta berhasil dihapus!'
  ];
} else {
  $_SESSION['alert'] = [
    'type' => 'danger',
    'message' => 'Data peserta gagal dihapus!'
  ];
}

$stmt->close();
$conn->close();

header("Location: ../admin.php");
exit();
