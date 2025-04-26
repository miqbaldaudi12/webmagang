<?php

include 'koneksi.php';

$query = "SELECT * FROM mentor";
$result = mysqli_query($conn, $query);



if (mysqli_num_rows($result) > 0) {
  $hasil_penerbit = array();
  while ($row = mysqli_fetch_assoc($result)) {
    $hasil_penerbit[] = $row;
  }
  
} 

mysqli_close($conn);
