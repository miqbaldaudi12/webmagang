<?php

include 'koneksi.php';


$query = "SELECT * FROM peserta ";
$hasil = mysqli_query($conn, $query);

if (mysqli_num_rows($hasil) > 0) {
  $peserta = mysqli_fetch_assoc($hasil);
}

mysqli_close($conn);
