<?php

include '../koneksi.php';

$id_mentor = $_POST['id_mentor'];
$nama_mentor = $_POST['nama_mentor'];
$email_mentor = $_POST['email_mentor'];
$alamat_mentor = $_POST['alamat_mentor'];

$query = mysqli_query($conn, "UPDATE mentor SET nama_mentor='$nama_mentor', email_mentor='$email_mentor', alamat_mentor='$alamat_mentor' WHERE id_mentor='$id_mentor'");

if ($query) {
  echo "<script>alert('Data berhasil diupdate!');window.location.href='../admin.php';</script>";
} else {
  echo "<script>alert('Data gagal diupdate!');window.location.href='../admin.php';</script>";
}

mysqli_close($conn);
?>