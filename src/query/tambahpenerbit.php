<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
include '../koneksi.php';

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
  header("Location: ../tambahpenerbit.php");
  exit();
}

$query = "INSERT INTO mentor (nama_mentor, email_mentor, telp_mentor, alamat_mentor) 
          VALUES (?, ?, ?, ?)";

$stmt = $conn->prepare($query);
$stmt->bind_param(
  "ssss",
  $nama_mentor,
  $email_mentor,
  $telp_mentor,
  $alamat_mentor
);

if ($stmt->execute()) {
  $_SESSION['alert'] = [
    'type' => 'success',
    'message' => 'Data Mentor berhasil ditambahkan!'
  ];
  header("Location: ../admin.php");
} else {
  $_SESSION['alert'] = [
    'type' => 'danger',
    'message' => 'Data Mentor gagal ditambahkan! ' . $stmt->error
  ];
  header("Location: ../tambahpenerbit.php");
}

$stmt->close();
$conn->close();
exit();
