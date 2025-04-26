<?php

include '../koneksi.php';

$id_mentor = $_POST['id_mentor'];
$nama_mentor = $_POST['nama_mentor'];
$email_mentor = $_POST['email_mentor'];
$alamat_mentor = $_POST['alamat_mentor'];

$query = "SELECT id_mentor FROM mentor WHERE id_mentor = '$id_mentor'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
  echo "<script>alert('ID mentor sudah ada dalam database!');window.location.href='../tambahmentor.php';</script>";
} else {
  $result = mysqli_query($conn, "INSERT INTO mentor (id_mentor, nama_mentor, email_mentor, alamat_mentor) VALUES ('$id_mentor', '$nama_mentor', '$email_mentor', '$alamat_mentor')");

  if ($result) {
    echo "<script>alert('Data mentor berhasil ditambahkan!');window.location.href='../admin.php';</script>";
  } else {
    echo "<script>alert('Data mentor gagal ditambahkan!');window.location.href='../admin.php';</script>";
  }
}

mysqli_close($conn);
