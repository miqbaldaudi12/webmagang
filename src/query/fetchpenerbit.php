<?php
include dirname(__FILE__) . '/../koneksi.php';

$query = "SELECT 
            id_mentor,
            nama_mentor,
            email_mentor,
            telp_mentor,
            alamat_mentor
          FROM mentor 
          ORDER BY id_mentor ASC";

$result = mysqli_query($conn, $query);
$hasil_penerbit = array();

while ($row = mysqli_fetch_array($result)) {
  $hasil_penerbit[] = $row;
}
