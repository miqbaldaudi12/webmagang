<?php


include '../koneksi.php';

$id_mentor = $_GET['id_mentor'];

$query = "DELETE FROM mentor WHERE id_mentor='$id_mentor'";

$result = mysqli_query($conn, $query);

if ($result) {
  echo "<script>alert('Data mentor berhasil dihapus!');window.location.href='../admin.php';</script>";
} else {
  echo "<script>alert('Data mentor gagal dihapus!');window.location.href='../admin.php';</script>";
}


?>