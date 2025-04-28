<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
include '../koneksi.php';

$id_peserta = $_POST['id_peserta'];
$nama_peserta = $_POST['nama_peserta'];
$email_peserta = $_POST['email_peserta'];
$telp_peserta = $_POST['telp_peserta'];
$alamat_peserta = $_POST['alamat_peserta'];
$instansi = $_POST['instansi'];
$id_mentor = (int) $_POST['id_mentor'];
$mentor = $_POST['mentor'];
$tanggal_mulai = $_POST['tanggal_mulai'];
$tanggal_selesai = $_POST['tanggal_selesai'];

// Validate phone number format
if (!preg_match('/^0[0-9]{8,14}$/', $telp_peserta)) {
  $_SESSION['alert'] = [
    'type' => 'danger',
    'message' => 'Format nomor telepon tidak valid! Harus diawali dengan 0 dan terdiri dari 9-15 digit.'
  ];
  header("Location: ../editbuku.php?id=" . $id_peserta);
  exit();
}

$query = "UPDATE peserta SET 
            nama_peserta = ?,
            email_peserta = ?,
            telp_peserta = ?,
            alamat_peserta = ?,
            instansi = ?,
            id_mentor = ?,
            mentor = ?,
            tanggal_mulai = ?,
            tanggal_selesai = ?
          WHERE id_peserta = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param(
  "sssssisssi",
  $nama_peserta,
  $email_peserta,
  $telp_peserta,
  $alamat_peserta,
  $instansi,
  $id_mentor,
  $mentor,
  $tanggal_mulai,
  $tanggal_selesai,
  $id_peserta
);

if ($stmt->execute()) {
  $_SESSION['alert'] = [
    'type' => 'success',
    'message' => 'Data Peserta berhasil diubah!'
  ];
  header("Location: ../admin.php");
} else {
  $_SESSION['alert'] = [
    'type' => 'danger',
    'message' => 'Data Peserta gagal diubah!'
  ];
  header("Location: ../editbuku.php?id=" . $id_peserta);
}

$stmt->close();
$conn->close();
exit();
