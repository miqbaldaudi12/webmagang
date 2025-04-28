<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
include '../koneksi.php';

$id_mentor = $_POST['id_mentor'];
$nama_mentor = $_POST['nama_mentor'];
$email_mentor = $_POST['email_mentor'];
$telp_mentor = $_POST['telp_mentor'];
$alamat_mentor = $_POST['alamat_mentor'];

// Validate phone number format
if (!preg_match('/^0[0-9]{8,14}$/', $telp_mentor)) {
  $_SESSION['alert'] = [
    'type' => 'danger',
    'message' => 'Format nomor telepon tidak valid! Harus diawali dengan 0 dan terdiri dari 9-15 digit.'
  ];
  header("Location: ../editpenerbit.php?id_mentor=" . $id_mentor);
  exit();
}

$query = "UPDATE mentor SET 
            nama_mentor = ?,
            email_mentor = ?,
            telp_mentor = ?,
            alamat_mentor = ?
          WHERE id_mentor = ?";

$stmt = $conn->prepare($query);
$stmt->bind_param(
  "ssssi",
  $nama_mentor,
  $email_mentor,
  $telp_mentor,
  $alamat_mentor,
  $id_mentor
);

if ($stmt->execute()) {
  $_SESSION['alert'] = [
    'type' => 'success',
    'message' => 'Data Mentor berhasil diubah!'
  ];
  header("Location: ../admin.php");
} else {
  $_SESSION['alert'] = [
    'type' => 'danger',
    'message' => 'Data Mentor gagal diubah!'
  ];
  header("Location: ../editpenerbit.php?id_mentor=" . $id_mentor);
}

$stmt->close();
$conn->close();
exit();
