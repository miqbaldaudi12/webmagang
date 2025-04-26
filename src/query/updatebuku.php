<?php

include '../koneksi.php';

$id_peserta = $_POST['id_peserta'];
$nama_peserta = $_POST['nama_peserta'];
$email_peserta = $_POST['email_peserta'];
$alamat_peserta = $_POST['alamat_peserta'];
$instansi = $_POST['instansi'];
$id_mentor = (int) $_POST['id_mentor']; // Pastikan id_mentor adalah integer
$mentor = $_POST['mentor'];

$query = "UPDATE peserta 
          SET nama_peserta='$nama_peserta', 
              email_peserta='$email_peserta', 
              alamat_peserta='$alamat_peserta', 
              instansi='$instansi', 
              id_mentor=$id_mentor, 
              mentor='$mentor' 
          WHERE id_peserta='$id_peserta'";
$result = mysqli_query($conn, $query);

if ($result) {
  echo "<script>alert('Data berhasil diupdate!');window.location.href='../admin.php';</script>";
} else {
  echo "<script>alert('Data gagal diupdate!');window.location.href='../admin.php';</script>";
}

mysqli_close($conn);
?>
