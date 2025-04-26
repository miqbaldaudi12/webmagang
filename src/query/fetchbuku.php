<?php
include 'koneksi.php';

$query = "SELECT * FROM peserta";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
  $hasil = array();
  while ($row = mysqli_fetch_assoc($result)) {
    $hasil[] = $row;
  }

} 

mysqli_close($conn);
