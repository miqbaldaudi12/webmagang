<?php

include '../koneksi.php';

$id_peserta = $_GET['id'];

$query = "DELETE FROM peserta WHERE id_peserta='$id_peserta'";

$result = mysqli_query($conn, $query);

if ($result) {
  echo "<script>alert('Data berhasil dihapus!');window.location.href='../admin.php';</script>";
} else {
  echo "<script>alert('Data gagal dihapus!');window.location.href='../admin.php';</script>";
}

?>